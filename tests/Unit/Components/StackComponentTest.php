<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Stack (#58): the token-driven layout primitive. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders the defaults as data attributes', function (): void {
    $stack = designSystemDom(Blade::render('<x-artisanpack-stack><div>A</div></x-artisanpack-stack>'))->query('//div[contains(@class, "apui-stack")]')->item(0);

    expect($stack->getAttribute('data-orientation'))->toBe('vertical')
        ->and($stack->getAttribute('data-gap'))->toBe('2')
        ->and($stack->getAttribute('data-align'))->toBe('stretch')
        ->and($stack->getAttribute('data-justify'))->toBe('start')
        ->and($stack->hasAttribute('data-wrap'))->toBeFalse();
});

test('takes orientation, gap, align, justify and wrap', function (): void {
    $stack = designSystemDom(Blade::render('<x-artisanpack-stack orientation="horizontal" :gap="4" align="center" justify="between" wrap>A</x-artisanpack-stack>'))->query('//div[contains(@class, "apui-stack")]')->item(0);

    expect($stack->getAttribute('data-orientation'))->toBe('horizontal')
        ->and($stack->getAttribute('data-gap'))->toBe('4')
        ->and($stack->getAttribute('data-align'))->toBe('center')
        ->and($stack->getAttribute('data-justify'))->toBe('between')
        ->and($stack->hasAttribute('data-wrap'))->toBeTrue();
});

test('falls back for unknown values', function (): void {
    $stack = designSystemDom(Blade::render('<x-artisanpack-stack orientation="row" gap="7" justify="space-between">A</x-artisanpack-stack>'))->query('//div[contains(@class, "apui-stack")]')->item(0);

    expect($stack->getAttribute('data-orientation'))->toBe('vertical')
        ->and($stack->getAttribute('data-gap'))->toBe('2')
        ->and($stack->getAttribute('data-justify'))->toBe('start');
});
