<?php

/**
 * Upgrade UI Command
 *
 * Artisan command that migrates an app's Blade files from the 2.x components
 * to 3.0 (design-system #67).
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use ArtisanPack\LivewireUiComponents\Upgrade\BladeUpgrader;
use ArtisanPack\LivewireUiComponents\Upgrade\Finding;
use ArtisanPack\LivewireUiComponents\Upgrade\UnifiedDiff;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeResult;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeRules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Finder\Finder;

use function Laravel\Prompts\confirm;

/**
 * Rewrites renamed components and attributes in Blade files and reports what
 * needs a person: attributes with no automatic replacement, and raw DaisyUI
 * classes and theme references, each with its file and line.
 *
 * @since 3.0.0
 */
class UpgradeUiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisanpack:upgrade-ui
                            {paths?* : Files or directories to upgrade (default: resources/views)}
                            {--dry-run : Show the changes as a diff without writing any file}
                            {--no-classes : Do not report raw DaisyUI classes and theme references}
                            {--json : Print the report as JSON, without asking before writing}
                            {--force : Write the changes without asking (required when the command cannot ask)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrades Blade files from the Livewire UI Components 2.x API to 3.0.';

    /**
     * Execute the console command.
     *
     * @since 3.0.0
     */
    public function handle(): int
    {
        $paths   = $this->argument('paths') ?: [resource_path('views')];
        $missing = array_filter($paths, fn (string $path): bool => ! File::exists($path));

        if ([] !== $missing) {
            $this->error(__('No such file or directory: :paths', ['paths' => implode(', ', $missing)]));

            return self::FAILURE;
        }

        $dryRun   = (bool) $this->option('dry-run');
        $json     = (bool) $this->option('json');
        $upgrader = new BladeUpgrader(UpgradeRules::load());
        $files    = $this->files($paths);
        $results  = [];

        foreach ($files as $file) {
            $result = $upgrader->upgrade(File::get($file), ! $this->option('no-classes'));

            if ($result->changed() || [] !== $result->findings) {
                $results[$file] = $result;
            }
        }

        $changed = array_filter($results, fn (UpgradeResult $result): bool => $result->changed());

        if (! $dryRun && [] !== $changed && ! $json && ! $this->option('force')) {
            // Rewriting files needs a yes: asked for, or given up front.
            if (! $this->input->isInteractive()) {
                $this->error(__('Nothing was written. Pass --force to write the changes, or --dry-run to preview them.'));

                return self::FAILURE;
            }

            if (! confirm(trans_choice(':count file will be rewritten. Commit your work first. Continue?|:count files will be rewritten. Commit your work first. Continue?', count($changed), ['count' => count($changed)]), true)) {
                $this->line(__('Nothing was written. Use --dry-run to preview the changes.'));

                return self::SUCCESS;
            }
        }

        if (! $dryRun) {
            // A file saved while the command waited would lose that edit, so nothing is written.
            $stale = array_keys(array_filter($changed, fn (UpgradeResult $result, string $file): bool => ! File::exists($file) || File::get($file) !== $result->original, ARRAY_FILTER_USE_BOTH));

            if ([] !== $stale) {
                $this->error(__('Nothing was written: these files changed while the command ran. Run it again.'));
                foreach ($stale as $file) {
                    $this->line('  '.OutputFormatter::escape($this->relative($file)));
                }

                return self::FAILURE;
            }

            foreach ($changed as $file => $result) {
                File::put($file, $result->code);
            }
        }

        if ($json) {
            $this->line((string) json_encode($this->report($results, count($files), $dryRun), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        foreach ($results as $file => $result) {
            $this->describe($this->relative($file), $result, $dryRun);
        }

        $this->summary($results, count($files), $dryRun);

        return self::SUCCESS;
    }

    /**
     * The Blade files under the given paths.
     *
     * @param  array<int, string>  $paths
     *
     * @return array<int, string>
     */
    private function files(array $paths): array
    {
        $files = [];

        foreach ($paths as $path) {
            if (File::isFile($path)) {
                $files[] = (string) realpath($path);

                continue;
            }

            $finder = Finder::create()->files()->in($path)->name('*.blade.php')->exclude(['vendor', 'node_modules'])->sortByName();

            foreach ($finder as $file) {
                $files[] = (string) $file->getRealPath();
            }
        }

        return array_values(array_unique($files));
    }

    /**
     * A path relative to the app, for the report.
     */
    private function relative(string $file): string
    {
        $base = rtrim((string) realpath(base_path()), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        return str_starts_with($file, $base) ? substr($file, strlen($base)) : $file;
    }

    /**
     * Prints one file's diff (in a dry run) and what needs a person.
     */
    private function describe(string $file, UpgradeResult $result, bool $dryRun): void
    {
        if ($dryRun && $result->changed()) {
            $name = OutputFormatter::escape($file);
            $this->line("<comment>--- a/{$name}</comment>");
            $this->line("<comment>+++ b/{$name}</comment>");

            foreach (UnifiedDiff::lines($result->original, $result->code, 2) as $line) {
                $escaped = OutputFormatter::escape($line);

                $this->line(match (true) {
                    str_starts_with($line, '+') => "<info>{$escaped}</info>",
                    str_starts_with($line, '-') => "<fg=red>{$escaped}</>",
                    default                     => $escaped,
                });
            }

            $this->newLine();
        }

        // Plain rewrites show in the diff; the report lists what needs a person.
        $notable = $result->findings(Finding::MANUAL, Finding::REVIEW, Finding::REMOVED, Finding::DAISY_CLASS);

        if ([] === $notable) {
            return;
        }

        $this->line('<options=bold>'.OutputFormatter::escape($file).'</>');

        foreach ($notable as $finding) {
            $this->line(sprintf('  %s  %s%s', str_pad("{$finding->line}:{$finding->column}", 8), str_pad($finding->kind, 9), OutputFormatter::escape($finding->message)));
        }

        $this->newLine();
    }

    /**
     * Prints the totals.
     *
     * @param  array<string, UpgradeResult>  $results
     */
    private function summary(array $results, int $scanned, bool $dryRun): void
    {
        $totals = $this->totals($results);

        $this->info($dryRun
            ? trans_choice(':count file scanned, :changed would change.|:count files scanned, :changed would change.', $scanned, ['count' => $scanned, 'changed' => $totals['files']])
            : trans_choice(':count file scanned, :changed changed.|:count files scanned, :changed changed.', $scanned, ['count' => $scanned, 'changed' => $totals['files']]));
        $this->line('  '.__(':rewrites rewrites (:review to review), :removed attributes removed', ['rewrites' => $totals[Finding::REWROTE] + $totals[Finding::REVIEW], 'review' => $totals[Finding::REVIEW], 'removed' => $totals[Finding::REMOVED]]));
        $this->line('  '.__(':count to migrate by hand', ['count' => $totals[Finding::MANUAL]]));

        if (! $this->option('no-classes')) {
            $this->line('  '.__(':count DaisyUI classes and theme references', ['count' => $totals[Finding::DAISY_CLASS]]));
        }

        if ($dryRun && $totals['files'] > 0) {
            $this->line(__('Run again without --dry-run to write the changes.'));
        }
    }

    /**
     * Counts the changed files and the findings of each kind.
     *
     * @param  array<string, UpgradeResult>  $results
     *
     * @return array<string, int>
     */
    private function totals(array $results): array
    {
        $totals = ['files' => 0, Finding::REWROTE => 0, Finding::REVIEW => 0, Finding::REMOVED => 0, Finding::MANUAL => 0, Finding::DAISY_CLASS => 0];

        foreach ($results as $result) {
            $totals['files'] += $result->changed() ? 1 : 0;

            foreach ($result->findings as $finding) {
                $totals[$finding->kind]++;
            }
        }

        return $totals;
    }

    /**
     * The report as data, for `--json`.
     *
     * @param  array<string, UpgradeResult>  $results
     *
     * @return array<string, mixed>
     */
    private function report(array $results, int $scanned, bool $dryRun): array
    {
        $files = [];

        foreach ($results as $file => $result) {
            $files[] = [
                'file'     => $this->relative($file),
                'changed'  => $result->changed(),
                'findings' => array_map(fn (Finding $finding): array => $finding->toArray(), $result->findings),
            ];
        }

        return ['dryRun' => $dryRun, 'scanned' => $scanned, 'totals' => $this->totals($results), 'files' => $files];
    }
}
