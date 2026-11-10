<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;

/*
 * The design-system icon registry (D-017): names resolve against the copied
 * Lucide set, apps register more, and unknown names fall back to blade-icons.
 */

afterEach(function (): void {
    IconRegistry::reset();
});

const LUCIDE_SVG_OPEN = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

test('a default name renders the exact design-system svg', function (): void {
    expect(IconRegistry::svg('plus'))->toBe(LUCIDE_SVG_OPEN.'<path d="M5 12h14" /><path d="M12 5v14" /></svg>');
});

test('the default set is the copied lucide.json without its comment', function (): void {
    $json = json_decode((string) file_get_contents(dirname(__DIR__, 3).'/resources/design-system/icons/lucide.json'), true);
    unset($json['$comment']);

    expect(IconRegistry::names())->toEqualCanonicalizing(array_keys($json))
        ->and(IconRegistry::resolve('circle-alert'))->toBe($json['circle-alert']);
});

test('unknown names resolve to nothing in the registry', function (): void {
    expect(IconRegistry::resolve('not-an-icon'))->toBeNull()
        ->and(IconRegistry::has('not-an-icon'))->toBeFalse()
        ->and(IconRegistry::svg('not-an-icon'))->toBe('');
});

test('register adds a name and replaces an existing one', function (): void {
    IconRegistry::register(['rocket' => '<path d="M1 1" />', 'plus' => '<path d="M2 2" />']);

    expect(IconRegistry::svg('rocket'))->toBe(LUCIDE_SVG_OPEN.'<path d="M1 1" /></svg>')
        ->and(IconRegistry::resolve('plus'))->toBe('<path d="M2 2" />')
        ->and(IconRegistry::names())->toContain('rocket');

    IconRegistry::reset();

    expect(IconRegistry::has('rocket'))->toBeFalse()
        ->and(IconRegistry::resolve('plus'))->toBe('<path d="M5 12h14" /><path d="M12 5v14" />');
});

test('the registered_icons config adds names', function (): void {
    config()->set('artisanpack.livewire-ui-components.registered_icons', ['star' => '<path d="M3 3" />']);

    expect(IconRegistry::svg('star'))->toBe(LUCIDE_SVG_OPEN.'<path d="M3 3" /></svg>')
        ->and(IconRegistry::names())->toContain('star');
});

test('runtime registrations win over the config', function (): void {
    config()->set('artisanpack.livewire-ui-components.registered_icons', ['star' => '<path d="M3 3" />']);
    IconRegistry::register(['star' => '<path d="M4 4" />']);

    expect(IconRegistry::resolve('star'))->toBe('<path d="M4 4" />');
});

test('render falls back to blade-icons for names outside the registry', function (): void {
    $svg = IconRegistry::render('o-plus');

    expect($svg)->toBe(svg('heroicon-o-plus', '', ['aria-hidden' => 'true'])->toHtml())
        ->and($svg)->toContain('aria-hidden="true"')
        ->and($svg)->not->toContain('focusable="false"');
});

test('render prefers the registry over blade-icons', function (): void {
    expect(IconRegistry::render('plus'))->toBe(IconRegistry::svg('plus'));
});

test('render returns nothing for unknown names and empty names', function (string|null $name): void {
    expect(IconRegistry::render($name))->toBe('');
})->with([
    'unknown' => 'definitely-not-an-icon',
    'empty'   => '',
    'null'    => null,
]);

test('arrows and chevrons pointing left or right are directional', function (): void {
    expect(IconRegistry::isDirectional('arrow-right'))->toBeTrue()
        ->and(IconRegistry::isDirectional('chevron-left'))->toBeTrue()
        ->and(IconRegistry::isDirectional('chevron-down'))->toBeFalse()
        ->and(IconRegistry::isDirectional(null))->toBeFalse();
});

test('register rejects bodies that could run script', function (string $body): void {
    expect(fn () => IconRegistry::register(['ok' => '<path d="M1 1" />', 'bad' => $body]))
        ->toThrow(InvalidArgumentException::class, 'Icon "bad" was not registered');

    expect(IconRegistry::has('ok'))->toBeFalse()
        ->and(IconRegistry::has('bad'))->toBeFalse();
})->with([
    'script'        => '<script>alert(1)</script>',
    'event handler' => '<path d="M1 1" onload="alert(1)" />',
    'style element' => '<style>*{}</style>',
    'foreignObject' => '<foreignObject><div></div></foreignObject>',
    'use'           => '<use href="#x" />',
    'image'         => '<image href="x.png" />',
    'anchor'        => '<a href="/x"><path /></a>',
    'iframe'        => '<iframe src="/x"></iframe>',
    'javascript url'=> '<path d="M1 1" fill="url(javascript:alert(1))" />',
    'data url'      => '<path fill="url(data:image/png;base64,AA)" />',
]);

test('the registered_icons config rejects unsafe bodies when read', function (): void {
    config()->set('artisanpack.livewire-ui-components.registered_icons', ['bad' => '<path onclick="alert(1)" />']);

    expect(fn () => IconRegistry::resolve('plus'))->toThrow(InvalidArgumentException::class);
});

test('svg adds extra attributes after the standard ones', function (): void {
    expect(IconRegistry::svg('check', ['data-icon' => 'check']))
        ->toStartWith(substr(LUCIDE_SVG_OPEN, 0, -1).' data-icon="check">');
});

test('the safety pattern matches behavior UNSAFE_MARKUP', function (): void {
    $source = (string) file_get_contents(dirname(__DIR__, 3).'/../design-system/packages/behavior/src/icons.ts');
    if ('' === $source || 1 !== preg_match('/const UNSAFE_MARKUP =\s*\n?\s*(\/.*\/i);/', $source, $match)) {
        $this->markTestSkipped('No design-system checkout next to this package.');
    }

    expect(IconRegistry::UNSAFE_MARKUP)->toBe($match[1]);
});

test('SafeUrl drops script schemes for links and keeps data: for media', function (): void {
    expect(SafeUrl::href('/reports'))->toBe('/reports')
        ->and(SafeUrl::href('https://example.com'))->toBe('https://example.com')
        ->and(SafeUrl::href("\x01 javascript:alert(1)"))->toBeNull()
        ->and(SafeUrl::href('DATA:text/html,x'))->toBeNull()
        ->and(SafeUrl::href(''))->toBeNull()
        ->and(SafeUrl::href(null))->toBeNull()
        ->and(SafeUrl::media('data:image/png;base64,AA'))->toBe('data:image/png;base64,AA')
        ->and(SafeUrl::media('vbscript:x'))->toBeNull();
});
