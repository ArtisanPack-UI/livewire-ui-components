<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Skeleton (#60). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('the default is a hidden block', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-skeleton />'))->query('//span[contains(@class, "apui-skeleton")]')->item(0);

    expect($root->getAttribute('data-shape'))->toBe('block')
        ->and($root->getAttribute('aria-hidden'))->toBe('true')
        ->and($root->getAttribute('data-scope'))->toBe('skeleton')
        ->and($root->hasAttribute('style'))->toBeFalse();
});

test('shape, width and height render, merged with a style', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-skeleton shape="circle" width="40px" height="40px" style="margin: 0;" class="me-2" />'))->query('//span')->item(0);

    expect($root->getAttribute('data-shape'))->toBe('circle')
        ->and($root->getAttribute('style'))->toBe('width: 40px; height: 40px; margin: 0')
        ->and($root->getAttribute('class'))->toBe('apui-skeleton me-2');
});

test('a text line takes only a width', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-skeleton shape="text" width="80%" />'))->query('//span')->item(0);

    expect($root->getAttribute('style'))->toBe('width: 80%');
});
