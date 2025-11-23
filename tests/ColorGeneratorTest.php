<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

beforeEach(function (): void {
    $this->colorGenerator = new ColorGenerator;
});

test('resolves predefined variants', function (): void {
    $variants = [
        'primary' => [
            'bg'     => 'bg-primary',
            'border' => 'border-primary',
            'text'   => 'text-primary-content',
            'style'  => '--artisanpack-variant-hover-color: #2563eb; --artisanpack-variant-focus-color: #2563eb;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'secondary' => [
            'bg'     => 'bg-secondary',
            'border' => 'border-secondary',
            'text'   => 'text-secondary-content',
            'style'  => '--artisanpack-variant-hover-color: #475569; --artisanpack-variant-focus-color: #475569;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'accent' => [
            'bg'     => 'bg-accent',
            'border' => 'border-accent',
            'text'   => 'text-accent-content',
            'style'  => '--artisanpack-variant-hover-color: #d97706; --artisanpack-variant-focus-color: #d97706;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'success' => [
            'bg'     => 'bg-success',
            'border' => 'border-success',
            'text'   => 'text-success-content',
            'style'  => '--artisanpack-variant-hover-color: #16a34a; --artisanpack-variant-focus-color: #16a34a;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'warning' => [
            'bg'     => 'bg-warning',
            'border' => 'border-warning',
            'text'   => 'text-warning-content',
            'style'  => '--artisanpack-variant-hover-color: #ea580c; --artisanpack-variant-focus-color: #ea580c;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'error' => [
            'bg'     => 'bg-error',
            'border' => 'border-error',
            'text'   => 'text-error-content',
            'style'  => '--artisanpack-variant-hover-color: #dc2626; --artisanpack-variant-focus-color: #dc2626;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'info' => [
            'bg'     => 'bg-info',
            'border' => 'border-info',
            'text'   => 'text-info-content',
            'style'  => '--artisanpack-variant-hover-color: #0284c7; --artisanpack-variant-focus-color: #0284c7;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
        'neutral' => [
            'bg'     => 'bg-neutral',
            'border' => 'border-neutral',
            'text'   => 'text-neutral-content',
            'style'  => '--artisanpack-variant-hover-color: #4b5563; --artisanpack-variant-focus-color: #4b5563;',
            'hover'  => 'hover:[background-color:var(--artisanpack-variant-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-variant-focus-color)]',
        ],
    ];

    foreach ($variants as $variant => $expected) {
        $result = $this->colorGenerator->resolveComponentColor($variant, null, 'button');
        expect($result)->toBe($expected, "Failed for variant: {$variant}");
    }
});

test('resolves tailwind colors with intensity', function (): void {
    $colors = [
        'red-500' => [
            'style'  => '--artisanpack-tailwind-color: #ef4444; --artisanpack-tailwind-hover-color: #dc2626; --artisanpack-tailwind-focus-color: #dc2626;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-white',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
        'blue-400' => [
            'style'  => '--artisanpack-tailwind-color: #60a5fa; --artisanpack-tailwind-hover-color: #3b82f6; --artisanpack-tailwind-focus-color: #3b82f6;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-black',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
        'green-600' => [
            'style'  => '--artisanpack-tailwind-color: #16a34a; --artisanpack-tailwind-hover-color: #15803d; --artisanpack-tailwind-focus-color: #15803d;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-white',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
        'purple-300' => [
            'style'  => '--artisanpack-tailwind-color: #d8b4fe; --artisanpack-tailwind-hover-color: #c084fc; --artisanpack-tailwind-focus-color: #c084fc;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-black',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
    ];

    foreach ($colors as $color => $expected) {
        $result = $this->colorGenerator->resolveComponentColor($color, null, 'alert');
        expect($result)->toBe($expected, "Failed for color: {$color}");
    }
});

test('resolves tailwind colors without intensity', function (): void {
    $colors = [
        'red' => [
            'style'  => '--artisanpack-tailwind-color: #ef4444; --artisanpack-tailwind-hover-color: #dc2626; --artisanpack-tailwind-focus-color: #dc2626;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-white',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
        'blue' => [
            'style'  => '--artisanpack-tailwind-color: #3b82f6; --artisanpack-tailwind-hover-color: #2563eb; --artisanpack-tailwind-focus-color: #2563eb;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-white',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
        'green' => [
            'style'  => '--artisanpack-tailwind-color: #22c55e; --artisanpack-tailwind-hover-color: #16a34a; --artisanpack-tailwind-focus-color: #16a34a;',
            'bg'     => '[background-color:var(--artisanpack-tailwind-color)]',
            'border' => '[border-color:var(--artisanpack-tailwind-color)]',
            'text'   => 'text-black',
            'hover'  => 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]',
            'focus'  => 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]',
        ],
    ];

    foreach ($colors as $color => $expected) {
        $result = $this->colorGenerator->resolveComponentColor($color, null, 'badge');
        expect($result)->toBe($expected, "Failed for color: {$color}");
    }
});

test('resolves hex colors', function (): void {
    $hexColors = ['#FF5733', '#33FF57', '#3357FF'];

    foreach ($hexColors as $hex) {
        $result = $this->colorGenerator->resolveComponentColor($hex, null, 'avatar');

        expect($result)->toHaveKey('style');
        expect($result)->toHaveKey('bg');
        expect($result)->toHaveKey('border');
        expect($result)->toHaveKey('text');

        expect($result['style'])->toContain("--artisanpack-custom-color: {$hex};");
        expect($result['style'])->toContain('--artisanpack-custom-hover-color:');
        expect($result['style'])->toContain('--artisanpack-custom-focus-color:');
        expect($result['bg'])->toBe('[background-color:var(--artisanpack-custom-color)]');
        expect($result['border'])->toBe('[border-color:var(--artisanpack-custom-color)]');
        expect($result['text'])->toBeIn(['text-black', 'text-white']);
    }
});

test('applies color adjustments', function (): void {
    // Test lighter adjustment
    $result = $this->colorGenerator->resolveComponentColor('red-500', 'lighter', 'button');
    expect($result['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($result['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');
    expect($result)->toHaveKey('style');
    expect($result['style'])->toContain('--artisanpack-tailwind-color:');

    // Test darker adjustment
    $result = $this->colorGenerator->resolveComponentColor('blue-500', 'darker', 'button');
    expect($result['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($result['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');
    expect($result)->toHaveKey('style');
    expect($result['style'])->toContain('--artisanpack-tailwind-color:');

    // Test transparent adjustment
    $result = $this->colorGenerator->resolveComponentColor('green-500', 'transparent', 'button');
    expect($result['bg'])->toBe('bg-transparent');
    expect($result['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');

    // Test subtle adjustment
    $result = $this->colorGenerator->resolveComponentColor('purple-500', 'subtle', 'button');
    expect($result['bg'])->toBe('[background-color:var(--artisanpack-tailwind-color)]');
    expect($result['border'])->toBe('[border-color:var(--artisanpack-tailwind-color)]');
    expect($result)->toHaveKey('style');
    expect($result['style'])->toContain('--artisanpack-tailwind-color:');
});

test('applies hex adjustments', function (): void {
    // Test lighter adjustment with hex
    $result = $this->colorGenerator->resolveComponentColor('#FF5733', 'lighter', 'button');
    expect($result)->toHaveKey('style');
    expect($result['style'])->toContain('--artisanpack-custom-color:');

    // Test transparent adjustment with hex
    $result = $this->colorGenerator->resolveComponentColor('#FF5733', 'transparent', 'button');
    expect($result['bg'])->toBe('bg-transparent');
    expect($result['border'])->toBe('[border-color:var(--artisanpack-custom-color)]');
});

test('handles invalid colors', function (): void {
    $invalidColors = [
        'invalid-color',
        'not-a-hex',
        'blue-1000',
        'red-',
        '#ZZZZZZ',
        '',
        'nonexistent',
    ];

    foreach ($invalidColors as $invalid) {
        $result = $this->colorGenerator->resolveComponentColor($invalid, null, 'button');
        expect($result)->toBeEmpty("Should return empty array for invalid color: {$invalid}");
    }
});

test('handles null color input', function (): void {
    $result = $this->colorGenerator->resolveComponentColor(null, null, 'button');
    expect($result)->toBeEmpty();
});

test('variant adjustments work correctly', function (): void {
    // Test variant with adjustment
    $result = $this->colorGenerator->resolveComponentColor('primary', 'lighter', 'button');
    expect($result)->not->toBeEmpty();
    expect($result)->toHaveKey('bg');
    expect($result)->toHaveKey('border');
    expect($result)->toHaveKey('text');
});

test('contrasting text calculation', function (): void {
    // Light colors should get dark text
    $result = $this->colorGenerator->resolveComponentColor('yellow-200', null, 'button');
    expect($result['text'])->toBe('text-black');

    // Dark colors should get light text
    $result = $this->colorGenerator->resolveComponentColor('blue-800', null, 'button');
    expect($result['text'])->toBe('text-white');
});

test('ghost and outline variants', function (): void {
    $result = $this->colorGenerator->resolveComponentColor('ghost', null, 'button');
    expect($result['bg'])->toBe('bg-transparent');
    expect($result['border'])->toBe('border-transparent');
    expect($result['text'])->toBe('text-current');

    $result = $this->colorGenerator->resolveComponentColor('outline', null, 'button');
    expect($result['bg'])->toBe('bg-transparent');
    expect($result['border'])->toBe('border-current');
    expect($result['text'])->toBe('text-current');
});

test('component context parameter', function (): void {
    // Test that component parameter is accepted (functionality is the same regardless)
    $result1 = $this->colorGenerator->resolveComponentColor('primary', null, 'button');
    $result2 = $this->colorGenerator->resolveComponentColor('primary', null, 'alert');
    $result3 = $this->colorGenerator->resolveComponentColor('primary', null, 'badge');

    // Results should be the same regardless of component
    expect($result1)->toBe($result2);
    expect($result2)->toBe($result3);
});
