<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Grid (#58): the token-driven layout primitive. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders the defaults as data attributes', function (): void {
    $grid = designSystemDom(Blade::render('<x-artisanpack-grid><div>A</div></x-artisanpack-grid>'))->query('//div[contains(@class, "apui-grid")]')->item(0);

    expect($grid->getAttribute('data-columns'))->toBe('1')
        ->and($grid->getAttribute('data-gap'))->toBe('4')
        ->and($grid->getAttribute('data-align'))->toBe('stretch')
        ->and($grid->getAttribute('data-justify'))->toBe('stretch')
        ->and($grid->hasAttribute('style'))->toBeFalse();
});

test('takes columns, gap, align, justify and a minimum item width', function (): void {
    $grid = designSystemDom(Blade::render('<x-artisanpack-grid :columns="3" gap="8" align="center" justify="end" min-item-width="16rem">A</x-artisanpack-grid>'))->query('//div[contains(@class, "apui-grid")]')->item(0);

    expect($grid->getAttribute('data-columns'))->toBe('3')
        ->and($grid->getAttribute('data-gap'))->toBe('8')
        ->and($grid->getAttribute('data-align'))->toBe('center')
        ->and($grid->getAttribute('data-justify'))->toBe('end')
        ->and($grid->getAttribute('style'))->toBe('--apui-grid-min-item-width: 16rem');
});

test('clamps columns and falls back for unknown values', function (): void {
    $grid = designSystemDom(Blade::render('<x-artisanpack-grid :columns="20" gap="7" align="baseline" min-item-width="1rem;}">A</x-artisanpack-grid>'))->query('//div[contains(@class, "apui-grid")]')->item(0);

    expect($grid->getAttribute('data-columns'))->toBe('12')
        ->and($grid->getAttribute('data-gap'))->toBe('4')
        ->and($grid->getAttribute('data-align'))->toBe('stretch')
        ->and($grid->getAttribute('style'))->toBe('--apui-grid-min-item-width: 1rem');
});

test('merges an author style into the minimum item width style', function (): void {
    $html = Blade::render('<x-artisanpack-grid min-item-width="16rem" style="max-width:40rem">A</x-artisanpack-grid>');
    preg_match('/<div\b[^>]*>/', $html, $root);

    expect(substr_count($root[0], ' style="'))->toBe(1)
        ->and(designSystemDom($html)->query('//div[contains(@class, "apui-grid")]')->item(0)->getAttribute('style'))->toBe('--apui-grid-min-item-width: 16rem; max-width:40rem');
});

test('writes an author style alone when there is no minimum item width', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-grid style="max-width:40rem;">A</x-artisanpack-grid>'))->query('//div[contains(@class, "apui-grid")]')->item(0)->getAttribute('style'))->toBe('max-width:40rem');
});
