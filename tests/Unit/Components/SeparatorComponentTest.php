<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Divider;
use ArtisanPack\LivewireUiComponents\View\Components\Separator;
use Illuminate\Support\Facades\Blade;

/*
 * Separator (deprecated 3.0.0): the 2.x separator, rendered by the
 * design-system Divider (#58).
 */

test('is a Divider', function (): void {
    expect(new Separator)->toBeInstanceOf(Divider::class);
});

test('renders an hr, or a vertical separator with vertical', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-separator />'))->query('//hr[@class="apui-divider"]')->length)->toBe(1);

    $vertical = designSystemDom(Blade::render('<x-artisanpack-separator vertical id="sep" />'))->query('//div[contains(@class, "apui-divider")]')->item(0);
    expect($vertical->getAttribute('data-orientation'))->toBe('vertical')
        ->and($vertical->getAttribute('id'))->toBe('sep');
});

test('ignores the 2.x loading bar, color and image', function (): void {
    $html = Blade::render('<x-artisanpack-separator progress target="save" color="primary" image="/logo.png" />');

    expect($html)->not->toContain('<progress')->not->toContain('<img')->not->toContain('border-primary');
});
