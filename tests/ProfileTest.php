<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Profile;

test('profile can be instantiated with default values', function (): void {
    $profile = new Profile;

    expect($profile->id)->toBeNull();
    expect($profile->image)->toBe('');
    expect($profile->alt)->toBe('');
    expect($profile->placeholder)->toBe('');
    expect($profile->color)->toBeNull();
    expect($profile->colorAdjustment)->toBeNull();
    expect($profile->title)->toBeNull();
    expect($profile->subtitle)->toBeNull();
    expect($profile->right)->toBeFalse();
    expect($profile->top)->toBeFalse();
    expect($profile->noXAnchor)->toBeFalse();
});

test('profile accepts avatar properties', function (): void {
    $profile = new Profile(
        image: '/path/to/avatar.jpg',
        alt: 'User Avatar',
        placeholder: 'JD',
        title: 'John Doe',
        subtitle: 'Software Engineer',
        color: 'primary',
    );

    expect($profile->image)->toBe('/path/to/avatar.jpg');
    expect($profile->alt)->toBe('User Avatar');
    expect($profile->placeholder)->toBe('JD');
    expect($profile->title)->toBe('John Doe');
    expect($profile->subtitle)->toBe('Software Engineer');
    expect($profile->color)->toBe('primary');
});

test('profile accepts dropdown properties', function (): void {
    $profile = new Profile(
        right: true,
        top: true,
        noXAnchor: true,
    );

    expect($profile->right)->toBeTrue();
    expect($profile->top)->toBeTrue();
    expect($profile->noXAnchor)->toBeTrue();
});

test('profile generates uuid', function (): void {
    $profile = new Profile;

    expect($profile->uuid)->not->toBeEmpty();
    expect($profile->uuid)->toStartWith('artisanpack');
});

test('profile uuid includes id when provided', function (): void {
    $profile = new Profile(id: 'test-profile');

    expect($profile->uuid)->not->toBeEmpty();
    expect($profile->uuid)->toStartWith('artisanpack');
    expect($profile->uuid)->toEndWith('test-profile');
});

test('profile returns empty color classes when no color set', function (): void {
    $profile      = new Profile;
    $colorClasses = $profile->getColorClasses();

    expect($colorClasses)->toBeArray();
    expect($colorClasses)->toBeEmpty();
});

test('profile resolves predefined color variants', function (): void {
    $colorVariants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

    foreach ($colorVariants as $color) {
        $profile      = new Profile(color: $color);
        $colorClasses = $profile->getColorClasses();

        expect($colorClasses)->not->toBeEmpty("Color classes should not be empty for color: {$color}");
    }
});

test('profile resolves tailwind colors', function (): void {
    $profile      = new Profile(color: 'blue-500');
    $colorClasses = $profile->getColorClasses();

    expect($colorClasses)->not->toBeEmpty();
    expect($colorClasses)->toBeArray();
});

test('profile applies color adjustments', function (): void {
    $adjustments = ['lighter', 'darker', 'transparent', 'subtle'];

    foreach ($adjustments as $adjustment) {
        $profile      = new Profile(color: 'primary', colorAdjustment: $adjustment);
        $colorClasses = $profile->getColorClasses();

        expect($colorClasses)->not->toBeEmpty("Color classes should not be empty for adjustment: {$adjustment}");
    }
});

test('profile handles hex colors', function (): void {
    $profile      = new Profile(color: '#ff0000');
    $colorClasses = $profile->getColorClasses();

    expect($colorClasses)->not->toBeEmpty();
    expect($colorClasses)->toBeArray();
});

test('profile supports image mode', function (): void {
    $profile = new Profile(
        image: '/path/to/image.jpg',
        alt: 'Profile Picture',
    );

    expect($profile->image)->toBe('/path/to/image.jpg');
    expect($profile->alt)->toBe('Profile Picture');
    expect($profile->placeholder)->toBe('');
});

test('profile supports placeholder mode', function (): void {
    $profile = new Profile(
        placeholder: 'AB',
        alt: 'Alex Brown',
        color: 'accent',
    );

    expect($profile->image)->toBe('');
    expect($profile->placeholder)->toBe('AB');
    expect($profile->alt)->toBe('Alex Brown');
    expect($profile->color)->toBe('accent');
});

test('profile combines avatar and dropdown functionality', function (): void {
    $profile = new Profile(
        id: 'user-profile',
        image: '/avatar.jpg',
        alt: 'User Avatar',
        title: 'Jane Smith',
        subtitle: 'Product Manager',
        color: 'primary',
        right: true,
        top: false,
        noXAnchor: false,
    );

    // Avatar properties
    expect($profile->image)->toBe('/avatar.jpg');
    expect($profile->alt)->toBe('User Avatar');
    expect($profile->title)->toBe('Jane Smith');
    expect($profile->subtitle)->toBe('Product Manager');
    expect($profile->color)->toBe('primary');

    // Dropdown properties
    expect($profile->right)->toBeTrue();
    expect($profile->top)->toBeFalse();
    expect($profile->noXAnchor)->toBeFalse();

    // UUID generation
    expect($profile->uuid)->toContain('user-profile');
});
