<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\Toasts;

/*
 * Toasts (#51): the toast options the toaster takes, and flashing them
 * across a redirect.
 */

test('make keeps only the options given', function (): void {
    expect(Toasts::make(title: 'Saved'))->toBe(['title' => 'Saved'])
        ->and(Toasts::make(title: 'Saved', description: 'Live now.', color: 'success', variant: 'inverse', surface: 'glass', icon: 'check', duration: 0, hideClose: true, id: 'save'))->toBe([
            'id'          => 'save',
            'title'       => 'Saved',
            'description' => 'Live now.',
            'color'       => 'success',
            'variant'     => 'inverse',
            'surface'     => 'glass',
            'icon'        => 'check',
            'duration'    => 0,
            'hideClose'   => true,
        ])
        ->and(Toasts::make(description: 'Only text', hideClose: false))->toBe(['description' => 'Only text']);
});

test('make rejects options the toaster does not take', function (array $options, string $message): void {
    expect(static fn () => Toasts::make(...$options))->toThrow(InvalidArgumentException::class, $message);
})->with([
    'no text'      => [[], 'A toast needs a title or a description.'],
    'color'        => [['title' => 'A', 'color' => 'red'], '"red" is not a toast color.'],
    'variant'      => [['title' => 'A', 'variant' => 'solid'], '"solid" is not a toast variant.'],
    'surface'      => [['title' => 'A', 'surface' => 'raised'], '"raised" is not a toast surface.'],
    'icon'         => [['title' => 'A', 'icon' => '<svg onload=x>'], 'is not an icon name'],
    'duration'     => [['title' => 'A', 'duration' => -1], 'duration is 0'],
]);

test('flash keeps toasts for the next request and pull takes them once', function (): void {
    Toasts::flash(title: 'One');
    Toasts::flash(title: 'Two', color: 'error');

    expect(session()->get(Toasts::SESSION_KEY))->toBe([['title' => 'One'], ['title' => 'Two', 'color' => 'error']])
        ->and(Toasts::pull())->toBe([['title' => 'One'], ['title' => 'Two', 'color' => 'error']])
        ->and(Toasts::pull())->toBe([]);
});

test('pull ignores anything that is not a list of toasts', function (): void {
    session()->put(Toasts::SESSION_KEY, 'nope');
    expect(Toasts::pull())->toBe([]);

    session()->put(Toasts::SESSION_KEY, [['title' => 'Kept'], 'dropped']);
    expect(Toasts::pull())->toBe([['title' => 'Kept']]);
});

test('status icons and types follow the behavior package', function (): void {
    expect(Toasts::statusIcon('success'))->toBe('circle-check')
        ->and(Toasts::statusIcon('warning'))->toBe('triangle-alert')
        ->and(Toasts::statusIcon('error'))->toBe('circle-alert')
        ->and(Toasts::statusIcon('accent'))->toBe('info')
        ->and(Toasts::type('error'))->toBe('error')
        ->and(Toasts::type('primary'))->toBe('info');
});
