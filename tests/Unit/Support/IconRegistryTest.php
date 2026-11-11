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

test('register rejects bodies outside the allowlist', function (mixed $body): void {
    expect(fn () => IconRegistry::register(['ok' => '<path d="M1 1" />', 'bad' => $body]))
        ->toThrow(InvalidArgumentException::class, 'Icon "bad" was not registered');

    expect(IconRegistry::has('ok'))->toBeFalse()
        ->and(IconRegistry::has('bad'))->toBeFalse()
        ->and(IconRegistry::isSafe($body))->toBeFalse();
})->with([
    // The same cases as packages/behavior/test/icons.test.ts.
    'script'                 => '<script>alert(1)</script>',
    'event handler'          => '<path d="M1 1" onload="alert(1)" />',
    'foreignObject'          => '<foreignObject><div>x</div></foreignObject>',
    'external use'           => '<use href="https://example.com/sprite.svg#x" />',
    'image'                  => '<image href="x" />',
    'javascript url'         => '<path d="javascript:alert(1)" />',
    'style'                  => '<style>*{}</style>',
    'handler after a slash'  => '<path/onmouseover=alert(1) />',
    'handler after a quote'  => '<path d="M0 0"onclick=alert(1) />',
    'animate'                => '<animate attributeName="href" values="javascript:alert(1)" />',
    'set'                    => '<set attributeName="href" to="x" />',
    'entity-encoded scheme'  => '<path d="java&#115;cript:x" />',
    'style attribute'        => '<path style="fill:red" d="M1 1" />',
    'comment'                => '<!-- x --><path d="M1 1" />',
    'text'                   => '<text>hi</text>',
    // Livewire extras.
    'anchor'                 => '<a href="/x"><path /></a>',
    'iframe'                 => '<iframe src="/x"></iframe>',
    'data url'               => '<path fill="url(data:image/png;base64,AA)" />',
    'processing instruction' => '<?xml version="1.0"?><path d="M1 1" />',
    'not a string'           => [['<path />']],
]);

test('every default icon and every allowed shape passes', function (): void {
    $json = json_decode((string) file_get_contents(dirname(__DIR__, 3).'/resources/design-system/icons/lucide.json'), true);
    unset($json['$comment']);

    foreach ($json as $name => $body) {
        expect(IconRegistry::isSafe($body))->toBeTrue("Default icon {$name} fails the allowlist");
    }

    expect(IconRegistry::isSafe('<g><polygon points="1 1 2 2" /><ellipse cx="1" cy="1" rx="1" ry="1" /></g><rect width="1" height="1" />'))->toBeTrue();
});

test('the registered_icons config rejects unsafe bodies when read', function (): void {
    config()->set('artisanpack.livewire-ui-components.registered_icons', ['bad' => '<path onclick="alert(1)" />']);

    expect(fn () => IconRegistry::resolve('plus'))->toThrow(InvalidArgumentException::class);
});

test('svg adds extra attributes after the standard ones', function (): void {
    expect(IconRegistry::svg('check', ['data-icon' => 'check']))
        ->toStartWith(substr(LUCIDE_SVG_OPEN, 0, -1).' data-icon="check">');
});

test('the allowlist matches behavior isSafeIconBody', function (): void {
    $file = dirname(__DIR__, 3).'/../design-system/packages/behavior/src/icons.ts';
    if (! is_file($file)) {
        $this->markTestSkipped('No design-system checkout next to this package.');
    }

    $source = (string) file_get_contents($file);
    preg_match('/ICON_SHAPE_TAGS = Object\.freeze\(\[(.*?)\]/s', $source, $tags);
    preg_match('/const UNSAFE_ATTRIBUTES =\s*(\/.*\/i);/', $source, $pattern);
    preg_match_all("/'([a-z]+)'/", $tags[1] ?? '', $names);

    // Same body and flags; JavaScript delimits with `/`, PHP with `#`.
    expect($names[1])->toBe(IconRegistry::SHAPE_TAGS)
        ->and(substr($pattern[1] ?? '', 1, -2))->toBe(substr(IconRegistry::UNSAFE_ATTRIBUTES, 1, -2))
        ->and(substr($pattern[1] ?? '', -1))->toBe(substr(IconRegistry::UNSAFE_ATTRIBUTES, -1));
});

test('SafeUrl allows relative, http(s), mailto, tel and sms links only', function (string $url, bool $allowed): void {
    expect(SafeUrl::href($url))->toBe($allowed ? $url : null);
})->with([
    'root relative'    => ['/reports', true],
    'dot relative'     => ['./reports', true],
    'bare relative'    => ['reports/q3', true],
    'query'            => ['?page=2', true],
    'fragment'         => ['#top', true],
    'scheme relative'  => ['//example.com/x', true],
    'http'             => ['http://example.com', true],
    'https upper'      => ['HTTPS://example.com', true],
    'mailto'           => ['mailto:a@example.com', true],
    'tel'              => ['tel:+15555550100', true],
    'sms'              => ['sms:+15555550100', true],
    'javascript'       => ['javascript:alert(1)', false],
    'control chars'    => ["\x01 java\tscript:alert(1)", false],
    'vbscript'         => ['vbscript:x', false],
    'data'             => ['DATA:text/html,x', false],
    'ftp'              => ['ftp://example.com', false],
    'custom'           => ['web+app:x', false],
    'empty'            => ['', false],
]);

test('SafeUrl allows relative, http(s), blob and data:image media only', function (string $url, bool $allowed): void {
    expect(SafeUrl::media($url))->toBe($allowed ? $url : null);
})->with([
    'relative'       => ['/img/a.png', true],
    'https'          => ['https://cdn.example.com/a.png', true],
    'blob'           => ['blob:https://example.com/1234', true],
    'data image'     => ['data:image/png;base64,AA', true],
    'data svg'       => ['data:image/svg+xml,%3Csvg%3E%3C/svg%3E', true],
    'data html'      => ['data:text/html,x', false],
    'javascript'     => ['javascript:x', false],
    'vbscript'       => ['vbscript:x', false],
    'mailto'         => ['mailto:a@example.com', false],
]);

test('SafeUrl treats null as absent', function (): void {
    expect(SafeUrl::href(null))->toBeNull()->and(SafeUrl::media(null))->toBeNull();
});
