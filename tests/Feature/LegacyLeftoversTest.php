<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

test('the 2.x icon config keys are gone', function (): void {
    expect(config('artisanpack.livewire-ui-components'))->not->toHaveKey('icons');
});

test('the deprecated generate-theme keys are still there, for the deprecated command', function (): void {
    expect(config('artisanpack.livewire-ui-components'))
        ->toHaveKey('theme_output_path')
        ->toHaveKey('glass')
        ->toHaveKey('design_tokens')
        ->and(Artisan::all())->toHaveKey('artisanpack:generate-theme');
});

test('the config documents the real Spotlight route', function (): void {
    $config = (string) file_get_contents(dirname(__DIR__, 2).'/config/livewire-ui-components.php');

    expect($config)->toContain("'/artisanpack/spotlight'")
        ->not->toContain('/livewire-ui-components/spotlight')
        ->and(route('artisanpack.spotlight', absolute: false))->toBe('/artisanpack/spotlight');
});

test('the glass and design token publish tags are gone', function (string $tag): void {
    expect(ServiceProvider::pathsToPublish(LivewireUiComponentsServiceProvider::class, $tag))->toBe([])
        ->and(ServiceProvider::publishableGroups())->not->toContain($tag);
})->with(['artisanpack-glass-tokens', 'artisanpack-design-tokens']);

test('the maryUI bootcamp command is gone', function (): void {
    expect(Artisan::all())->not->toHaveKey('livewire-ui-components:bootcamp')
        ->and(dirname(__DIR__, 2).'/stubs/bootcamp')->not->toBeDirectory();
});

test('generate-theme describes itself as deprecated', function (): void {
    expect(Artisan::all()['artisanpack:generate-theme']->getDescription())->toStartWith('Deprecated:')
        ->toContain('artisanpack:generate-preset');
});
