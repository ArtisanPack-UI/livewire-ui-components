<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Upgrade\BladeUpgrader;
use ArtisanPack\LivewireUiComponents\Upgrade\Finding;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeRules;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

/*
 * artisanpack:upgrade-ui (design-system #67), against Blade files taken from
 * the packages in the Phase 0 codemod-scope report. Each fixture in
 * tests/Fixtures/upgrade/ecosystem has its upgraded twin next to it as
 * `*.expected`. To refresh them after a rules change:
 *
 *   UPDATE_UPGRADE_FIXTURES=1 vendor/bin/pest tests/Feature/UpgradeUiCommandTest.php
 */

/**
 * The ecosystem fixtures, as `package/file` => path.
 *
 * @return array<string, array{0: string}>
 */
function upgradeFixtures(): array
{
    $root  = dirname(__DIR__).'/Fixtures/upgrade/ecosystem';
    $files = [];

    foreach (glob($root.'/*/*.blade.php') ?: [] as $file) {
        $files[basename(dirname($file)).'/'.basename($file)] = [$file];
    }

    return $files;
}

beforeEach(function (): void {
    $this->views = storage_path('upgrade-ui-test');
    File::deleteDirectory($this->views);
    File::ensureDirectoryExists($this->views.'/orders');
    File::put($this->views.'/orders/index.blade.php', "<x-artisanpack-header title=\"Orders\" subtitle=\"All of them\" separator />\n<a class=\"btn btn-primary\">New</a>\n");
    File::put($this->views.'/clean.blade.php', "<x-artisanpack-ec-price :amount=\"5\" />\n");
    File::put($this->views.'/notes.txt', '<x-artisanpack-header subtitle="x" />');
});

afterEach(function (): void {
    File::deleteDirectory($this->views);
});

test('upgrades ecosystem fixtures', function (string $file): void {
    $result   = (new BladeUpgrader(UpgradeRules::load()))->upgrade(File::get($file));
    $expected = $file.'.expected';

    if (getenv('UPDATE_UPGRADE_FIXTURES')) {
        File::put($expected, $result->code);
    }

    expect($result->code)->toBe(File::get($expected))
        ->and($result->changed())->toBeTrue();

    // Upgrading it again changes nothing.
    expect((new BladeUpgrader(UpgradeRules::load()))->upgrade($result->code)->code)->toBe($result->code);

    // Tags that packages own are exactly as they were.
    preg_match_all('~<x-artisanpack-ec-[^>]*>~', File::get($file), $before);
    preg_match_all('~<x-artisanpack-ec-[^>]*>~', $result->code, $after);
    expect($after[0])->toBe($before[0]);

    // Every library tag left in the file is one 3.0 registers.
    preg_match_all('~<x-(artisanpack-[a-z-]+)~', $result->code, $tags);
    $aliases = Blade::getClassComponentAliases();

    foreach (array_unique($tags[1]) as $tag) {
        if (! str_starts_with($tag, 'artisanpack-ec-')) {
            expect($aliases)->toHaveKey($tag);
        }
    }
})->with(upgradeFixtures());

test('the fixtures cover the packages in the codemod-scope report', function (): void {
    $packages = array_unique(array_map(fn (string $name): string => explode('/', $name)[0], array_keys(upgradeFixtures())));

    expect($packages)->toContain('ecommerce-admin-livewire', 'forms', 'media-library', 'livewire-starter-kit', 'analytics', 'ecommerce-storefront-livewire');
});

test('a dry run shows a diff and writes nothing', function (): void {
    $before = File::get($this->views.'/orders/index.blade.php');

    $this->artisan('artisanpack:upgrade-ui', ['paths' => [$this->views], '--dry-run' => true])
        ->expectsOutputToContain('-<x-artisanpack-header title="Orders" subtitle="All of them" separator />')
        ->expectsOutputToContain('+<x-artisanpack-page-header title="Orders" description="All of them" />')
        ->expectsOutputToContain('2 files scanned, 1 would change.')
        ->expectsOutputToContain('Run again without --dry-run')
        ->assertSuccessful();

    expect(File::get($this->views.'/orders/index.blade.php'))->toBe($before);
});

test('reports DaisyUI classes and removed attributes with file and line', function (): void {
    Artisan::call('artisanpack:upgrade-ui', ['paths' => [$this->views], '--dry-run' => true]);
    $output = Artisan::output();

    expect($output)->toContain('orders/index.blade.php')
        ->toMatch('/1:\d+\s+removed\s+Removed `separator`/')
        ->toMatch('/2:11\s+class\s+`btn` is a DaisyUI class/')
        ->toMatch('/2:15\s+class\s+`btn-primary`/')
        ->toContain('2 DaisyUI classes and theme references')
        ->not->toContain('clean.blade.php');
});

test('writes the upgraded files', function (): void {
    $this->artisan('artisanpack:upgrade-ui', ['paths' => [$this->views], '--force' => true])
        ->expectsOutputToContain('2 files scanned, 1 changed.')
        ->assertSuccessful();

    expect(File::get($this->views.'/orders/index.blade.php'))
        ->toBe("<x-artisanpack-page-header title=\"Orders\" description=\"All of them\" />\n<a class=\"btn btn-primary\">New</a>\n")
        ->and(File::get($this->views.'/clean.blade.php'))->toBe("<x-artisanpack-ec-price :amount=\"5\" />\n")
        ->and(File::get($this->views.'/notes.txt'))->toBe('<x-artisanpack-header subtitle="x" />');
});

test('asks before rewriting files', function (): void {
    $before = File::get($this->views.'/orders/index.blade.php');

    $this->artisan('artisanpack:upgrade-ui', ['paths' => [$this->views]])
        ->expectsConfirmation('1 file will be rewritten. Commit your work first. Continue?', 'no')
        ->expectsOutputToContain('Nothing was written.')
        ->assertSuccessful();

    expect(File::get($this->views.'/orders/index.blade.php'))->toBe($before);

    $this->artisan('artisanpack:upgrade-ui', ['paths' => [$this->views]])
        ->expectsConfirmation('1 file will be rewritten. Commit your work first. Continue?', 'yes')
        ->assertSuccessful();

    expect(File::get($this->views.'/orders/index.blade.php'))->toContain('x-artisanpack-page-header');
});

test('takes single files and skips the class report', function (): void {
    Artisan::call('artisanpack:upgrade-ui', ['paths' => [$this->views.'/orders/index.blade.php'], '--dry-run' => true, '--no-classes' => true]);
    $output = Artisan::output();

    expect($output)->toContain('1 file scanned, 1 would change.')
        ->not->toContain('DaisyUI');
});

test('prints the report as JSON', function (): void {
    Artisan::call('artisanpack:upgrade-ui', ['paths' => [$this->views], '--dry-run' => true, '--json' => true]);
    $report = json_decode(Artisan::output(), true);

    expect($report['dryRun'])->toBeTrue()
        ->and($report['scanned'])->toBe(2)
        ->and($report['totals']['files'])->toBe(1)
        ->and($report['totals'][Finding::DAISY_CLASS])->toBe(2)
        ->and($report['files'][0]['changed'])->toBeTrue()
        ->and($report['files'][0]['findings'][0])->toMatchArray(['kind' => 'rewrote', 'line' => 1, 'column' => 1, 'component' => 'header']);
});

test('fails on a path that does not exist', function (): void {
    $this->artisan('artisanpack:upgrade-ui', ['paths' => [$this->views.'/missing']])
        ->expectsOutputToContain('No such file or directory')
        ->assertFailed();
});

test('upgrades resources/views by default', function (): void {
    $file = resource_path('views/upgrade-ui-default.blade.php');
    File::ensureDirectoryExists(dirname($file));
    File::put($file, '<x-artisanpack-separator />');

    $this->artisan('artisanpack:upgrade-ui', ['--force' => true])->assertSuccessful();

    expect(File::get($file))->toBe('<x-artisanpack-divider />');
    File::delete($file);
});
