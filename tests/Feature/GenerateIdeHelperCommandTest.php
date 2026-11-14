<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

afterEach(function (): void {
    File::delete(base_path('_ide_helper_artisanpack.blade.php'));
});

it('lists every registered component under the configured prefix', function (): void {
    $this->artisan('artisanpack:generate-ide-helper')->assertSuccessful();

    $helper = File::get(base_path('_ide_helper_artisanpack.blade.php'));

    expect($helper)
        ->toContain('<x-artisanpack-button />')
        ->toContain('<x-artisanpack-dialog />')
        ->toContain('<x-artisanpack-toaster />')
        ->toContain('<livewire:artisanpack-calendar />')
        ->not->toContain('spotlight-class');

    $aliases = array_filter(
        Blade::getClassComponentAliases(),
        fn (string $class, string $name): bool => str_starts_with($name, 'artisanpack-') && str_starts_with($class, 'ArtisanPack\\LivewireUiComponents\\'),
        ARRAY_FILTER_USE_BOTH,
    );

    expect(substr_count($helper, '<x-artisanpack-'))->toBe(count($aliases));
});

it('writes the tags under a custom or empty prefix', function (string $prefix, string $button, string $calendar): void {
    config()->set('artisanpack.livewire-ui-components.prefix', $prefix);

    $this->artisan('artisanpack:generate-ide-helper')->assertSuccessful();

    expect(File::get(base_path('_ide_helper_artisanpack.blade.php')))
        ->toContain($button)
        ->toContain($calendar)
        ->not->toContain('<x--');
})->with([
    'custom' => ['ui', '<x-ui-button />', '<livewire:ui-calendar />'],
    'empty'  => ['', '<x-button />', '<livewire:calendar />'],
]);
