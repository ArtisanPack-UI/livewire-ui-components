<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Button;

test('button uses primary variant by default', function (): void {
    $button = new Button;

    expect($button->variant)->toBe('primary');
    expect($button->resolvedColor)->toBe('primary');
    expect($button->getVariantClasses())->toBe('btn-primary');
});

test('button accepts valid variants', function (): void {
    $validVariants = [
        'primary'   => 'btn-primary',
        'secondary' => 'btn-secondary',
        'accent'    => 'btn-accent',
        'success'   => 'btn-success',
        'warning'   => 'btn-warning',
        'error'     => 'btn-error',
        'ghost'     => 'btn-ghost',
        'outline'   => 'btn-outline',
    ];

    foreach ($validVariants as $variant => $expectedClass) {
        $button = new Button(variant: $variant);

        expect($button->variant)->toBe($variant);
        expect($button->resolvedColor)->toBe($variant);
        expect($button->getVariantClasses())->toBe($expectedClass);
    }
});

test('button falls back to primary for invalid variants', function (): void {
    $invalidVariants = ['invalid', 'nonexistent', '', null];

    foreach ($invalidVariants as $variant) {
        $button = new Button(variant: $variant);

        expect($button->variant)->toBe('primary');
        expect($button->resolvedColor)->toBe('primary');
        expect($button->getVariantClasses())->toBe('btn-primary');
    }
});

test('button maintains existing functionality', function (): void {
    $button = new Button(
        id: 'test-btn',
        label: 'Test Button',
        icon: 'save',
        variant: 'success',
    );

    expect($button->id)->toBe('test-btn');
    expect($button->label)->toBe('Test Button');
    expect($button->icon)->toBe('save');
    expect($button->variant)->toBe('success');
    expect($button->resolvedColor)->toBe('success');
    expect($button->getVariantClasses())->toBe('btn-success');
});

test('button generates uuid', function (): void {
    $button = new Button;

    expect($button->uuid)->not->toBeEmpty();
    expect($button->uuid)->toStartWith('artisanpack');
});

// New Color System Tests

test('button color prop overrides variant', function (): void {
    $button = new Button(variant: 'success', color: 'red-500');

    expect($button->variant)->toBe('success');
    expect($button->resolvedColor)->toBe('red-500');
});

test('button resolves predefined color variants', function (): void {
    $colorVariants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

    foreach ($colorVariants as $color) {
        $button       = new Button(color: $color);
        $colorClasses = $button->getColorClasses();

        expect($colorClasses)->not->toBeEmpty();
        expect($colorClasses)->toHaveKey('bg');
        expect($colorClasses)->toHaveKey('border');
        expect($colorClasses)->toHaveKey('text');
    }
});

test('button resolves tailwind colors', function (): void {
    $button       = new Button(color: 'blue-500');
    $colorClasses = $button->getColorClasses();

    $expected = [
        'style'  => '--artisanpack-tailwind-color: #3b82f6; --artisanpack-tailwind-hover-color: #2563eb; --artisanpack-tailwind-focus-color: #2563eb;',
        'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
        'border' => '[border-color:var(--artisanpack-tailwind-color)]',
        'text'   => 'text-white',
        'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
        'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
    ];

    expect($colorClasses)->toBe($expected);
});

test('button resolves tailwind colors without intensity', function (): void {
    $button       = new Button(color: 'red');
    $colorClasses = $button->getColorClasses();

    $expected = [
        'style'  => '--artisanpack-tailwind-color: #ef4444; --artisanpack-tailwind-hover-color: #dc2626; --artisanpack-tailwind-focus-color: #dc2626;',
        'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
        'border' => '[border-color:var(--artisanpack-tailwind-color)]',
        'text'   => 'text-white',
        'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
        'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
    ];

    expect($colorClasses)->toBe($expected);
});

test('button resolves hex colors', function (): void {
    $button       = new Button(color: '#FF5733');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses)->toHaveKey('style');
    expect($colorClasses)->toHaveKey('bg');
    expect($colorClasses)->toHaveKey('border');
    expect($colorClasses)->toHaveKey('text');

    expect($colorClasses['style'])->toBe('--artisanpack-custom-color: #FF5733; --artisanpack-custom-hover-color: #cc4528; --artisanpack-custom-focus-color: #cc4528;');
    expect($colorClasses['bg'])->toBe('[background-color:var(--artisanpack-custom-color)]');
    expect($colorClasses['border'])->toBe('[border-color:var(--artisanpack-custom-color)]');
    expect($colorClasses['text'])->toBeIn(['text-black', 'text-white']);
});

test('button applies color adjustments', function (): void {
    // Test lighter adjustment
    $button       = new Button(color: 'blue-500', colorAdjustment: 'lighter');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses['style'])->toBe('--artisanpack-tailwind-color: #dbeafe;');
    expect($colorClasses['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($colorClasses['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');

    // Test darker adjustment
    $button       = new Button(color: 'red-500', colorAdjustment: 'darker');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses['style'])->toBe('--artisanpack-tailwind-color: #b91c1c;');
    expect($colorClasses['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($colorClasses['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');

    // Test transparent adjustment
    $button       = new Button(color: 'green-500', colorAdjustment: 'transparent');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses['bg'])->toBe('bg-transparent');
    expect($colorClasses['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');

    // Test subtle adjustment
    $button       = new Button(color: 'purple-500', colorAdjustment: 'subtle');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses['style'])->toBe('--artisanpack-tailwind-color: #faf5ff;');
    expect($colorClasses['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($colorClasses['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');
});

test('button handles invalid colors', function (): void {
    $button       = new Button(color: 'invalid-color');
    $colorClasses = $button->getColorClasses();

    // Should fall back to DaisyUI variant classes
    expect($colorClasses)->not->toBeEmpty();
    expect($colorClasses)->toHaveKey('btn');
});

test('button maintains backward compatibility when no color specified', function (): void {
    $button = new Button(variant: 'success');

    // When no color is specified, should use variant for color resolution
    expect($button->resolvedColor)->toBe('success');

    $colorClasses = $button->getColorClasses();
    expect($colorClasses)->not->toBeEmpty();
});

test('button color adjustment with variants', function (): void {
    $button       = new Button(color: 'primary', colorAdjustment: 'lighter');
    $colorClasses = $button->getColorClasses();

    expect($colorClasses)->not->toBeEmpty();
    expect($colorClasses)->toHaveKey('bg');
    expect($colorClasses)->toHaveKey('border');
    expect($colorClasses)->toHaveKey('text');
});
