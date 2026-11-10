<?php

/**
 * Generate Preset Command
 *
 * Artisan command that generates a brand preset from the design tokens (#27).
 * It replaces `artisanpack:generate-theme`: instead of Tailwind palettes and
 * DaisyUI variables it writes token reassignments that switch with
 * `data-preset`, checked for WCAG 2.2 AA contrast in every theme.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;
use ArtisanPack\LivewireUiComponents\Theming\ContrastChecker;
use ArtisanPack\LivewireUiComponents\Theming\GeneratedPreset;
use ArtisanPack\LivewireUiComponents\Theming\PresetGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

/**
 * Generates a brand preset as CSS, PHP or JSON.
 *
 * @since 3.0.0
 */
class GeneratePresetCommand extends Command
{
    /**
     * The output formats.
     */
    public const FORMATS = ['css', 'php', 'json'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisanpack:generate-preset
                            {name? : The preset name, used as data-preset="name"}
                            {--primary= : The primary brand color, as a hex code}
                            {--secondary= : The secondary color, as a hex code (default: unchanged)}
                            {--accent= : The accent color, as a hex code (default: unchanged)}
                            {--neutral=tinted : tinted, pure or keep}
                            {--font-sans= : Comma-separated font families for UI text}
                            {--font-mono= : Comma-separated font families for code}
                            {--font-display= : Comma-separated font families for display text}
                            {--description= : A description for the preset}
                            {--format=css : css, php or json}
                            {--output= : The file to write (default: resources/css/presets/NAME.FORMAT)}
                            {--force : Overwrite an existing file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a brand preset from the design tokens, switched with data-preset.';

    /**
     * Execute the console command.
     *
     * @since 3.0.0
     */
    public function handle(PresetGenerator $generator): int
    {
        $name = $this->argument('name') ?: ($this->input->isInteractive()
            ? text(label: __('Preset name'), placeholder: 'acme', required: true, hint: __('Used as data-preset="name".'))
            : null);
        $primary = $this->option('primary') ?: ($this->input->isInteractive()
            ? text(label: __('Primary brand color'), placeholder: '#7c3aed', required: true)
            : null);
        $format = (string) $this->option('format');

        if (! in_array($format, self::FORMATS, true)) {
            $this->error(__('--format must be one of: :formats.', ['formats' => implode(', ', self::FORMATS)]));

            return self::FAILURE;
        }

        try {
            $preset = $generator->generate([
                'name'        => (string) $name,
                'primary'     => $primary,
                'secondary'   => $this->option('secondary'),
                'accent'      => $this->option('accent'),
                'neutral'     => $this->option('neutral'),
                'fontSans'    => $this->option('font-sans'),
                'fontMono'    => $this->option('font-mono'),
                'fontDisplay' => $this->option('font-display'),
                'description' => $this->option('description'),
            ]);
        } catch (ThemeGeneratorException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if (! $preset->passes()) {
            $this->error(__('The generated preset fails these contrast checks, so nothing was written:'));
            foreach (ContrastChecker::describe($preset->failures) as $line) {
                $this->line('  '.$line);
            }

            return self::FAILURE;
        }

        $path = $this->option('output') ?: resource_path("css/presets/{$preset->name}.{$format}");
        if (File::exists($path) && ! $this->option('force')
            && ! ($this->input->isInteractive() && confirm(__(':path exists. Overwrite it?', ['path' => $path]), false))) {
            $this->error(__(':path exists. Use --force to overwrite it.', ['path' => $path]));

            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->render($preset, $format));

        foreach ($preset->anchors as $role => $anchor) {
            $this->line($anchor['exact']
                ? __(':role: :ramp ramp, input placed exactly at step :step', ['role' => $role, 'ramp' => $anchor['ramp'], 'step' => $anchor['step']])
                : __(':role: :ramp ramp, input matched to the luminance of step :step', ['role' => $role, 'ramp' => $anchor['ramp'], 'step' => $anchor['step']]));
        }
        $this->info(__('Preset ":name" written to :path.', ['name' => $preset->name, 'path' => $path]));
        if ('css' === $format) {
            $this->line(__('Import it after @artisanpack-ui/css and apply it with <html data-preset=":name">.', ['name' => $preset->name]));
        }

        return self::SUCCESS;
    }

    /**
     * Renders the preset in one of the output formats.
     */
    private function render(GeneratedPreset $preset, string $format): string
    {
        return match ($format) {
            'php'   => "<?php\n\n/**\n * Brand preset \"{$preset->name}\", generated by php artisan artisanpack:generate-preset.\n */\n\nreturn ".var_export($preset->toArray(), true).";\n",
            'json'  => json_encode($preset->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n",
            default => $preset->css(),
        };
    }
}
