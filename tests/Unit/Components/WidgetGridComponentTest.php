<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Grid;
use ArtisanPack\LivewireUiComponents\View\Components\WidgetGrid;
use Illuminate\Support\Facades\Blade;

/*
 * WidgetGrid (deprecated 3.0.0): the 2.x widget-grid, rendered by the
 * design-system Grid (#58).
 */

test('is a Grid with the 2.x defaults, reflowing by width', function (): void {
    $grid = new WidgetGrid;

    expect($grid)->toBeInstanceOf(Grid::class)
        ->and($grid->columns)->toBe(4)
        ->and($grid->gap)->toBe('6')
        ->and($grid->minItemWidth)->toBe('14rem');
});

test('maps cols to columns and keeps the id, without Tailwind classes', function (): void {
    $html = Blade::render('<x-artisanpack-widget-grid id="stats" cols="3" gap="2">A</x-artisanpack-widget-grid>');
    $grid = designSystemDom($html)->query('//div[contains(@class, "apui-grid")]')->item(0);

    expect($grid->getAttribute('id'))->toBe('stats')
        ->and($grid->getAttribute('data-columns'))->toBe('3')
        ->and($grid->getAttribute('data-gap'))->toBe('2')
        ->and($html)->not->toContain('grid-cols');
});
