<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Link (#56): the design-system markup contract. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

test('renders an anchor with the tone and the slot', function (): void {
    $html = Blade::render('<x-artisanpack-link href="/terms">Listing terms</x-artisanpack-link>');
    $root = designSystemDom($html)->query('//a[contains(@class, "apui-link")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('href'))->toBe('/terms')
        ->and($root->getAttribute('data-tone'))->toBe('primary')
        ->and($root->hasAttribute('data-standalone'))->toBeFalse()
        ->and($root->hasAttribute('target'))->toBeFalse()
        ->and($root->hasAttribute('wire:navigate'))->toBeTrue()
        ->and(trim($root->textContent))->toBe('Listing terms');
});

test('renders a button without an href, keeping wire:click', function (): void {
    $html = Blade::render('<x-artisanpack-link wire:click="logout">Log out</x-artisanpack-link>');
    $root = designSystemDom($html)->query('//*[contains(@class, "apui-link")]')->item(0);

    expect($root->tagName)->toBe('button')
        ->and($root->getAttribute('type'))->toBe('button')
        ->and($root->getAttribute('wire:click'))->toBe('logout')
        ->and($root->hasAttribute('href'))->toBeFalse();
});

test('an unsafe href renders the button form', function (): void {
    $html = Blade::render('<x-artisanpack-link href="javascript:alert(1)">Bad</x-artisanpack-link>');

    expect($html)->not->toContain('javascript:')
        ->and(designSystemDom($html)->query('//button[contains(@class, "apui-link")]'))->toHaveCount(1);
});

test('tone and standalone become data attributes; an unknown tone falls back', function (): void {
    $muted   = designSystemDom(Blade::render('<x-artisanpack-link href="/" tone="muted" standalone>Privacy</x-artisanpack-link>'))->query('//a')->item(0);
    $unknown = designSystemDom(Blade::render('<x-artisanpack-link href="/" tone="loud">Privacy</x-artisanpack-link>'))->query('//a')->item(0);

    expect($muted->getAttribute('data-tone'))->toBe('muted')
        ->and($muted->hasAttribute('data-standalone'))->toBeTrue()
        ->and($unknown->getAttribute('data-tone'))->toBe('primary');
});

test('external opens a new tab, shows the icon and says so', function (): void {
    $html = Blade::render('<x-artisanpack-link href="https://example.com" external>Docs</x-artisanpack-link>');
    $dom  = designSystemDom($html);
    $root = $dom->query('//a')->item(0);

    expect($root->getAttribute('target'))->toBe('_blank')
        ->and($root->getAttribute('rel'))->toBe('noopener noreferrer')
        ->and($dom->query('//*[@data-scope="link"][@data-part="iconEnd"]/svg'))->toHaveCount(1)
        ->and(trim($dom->query('//span[@class="apui-sr-only"]')->item(0)->textContent))->toBe('(opens in a new tab)');
});

test('the external label is translatable', function (): void {
    app('translator')->addLines(['*.(opens in a new tab)' => '(öffnet in neuem Tab)'], 'de');
    app()->setLocale('de');

    $html = Blade::render('<x-artisanpack-link href="https://example.com" external>Docs</x-artisanpack-link>');

    expect($html)->toContain('(öffnet in neuem Tab)');
});

test('icons render as parts, and arrows mirror', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-link href="/all" standalone leading-icon="download" trailing-icon="arrow-right">View all</x-artisanpack-link>'));

    $icon    = $dom->query('//*[@data-part="icon"]')->item(0);
    $iconEnd = $dom->query('//*[@data-part="iconEnd"]')->item(0);

    expect($icon->getAttribute('aria-hidden'))->toBe('true')
        ->and($icon->hasAttribute('data-mirror'))->toBeFalse()
        ->and($iconEnd->hasAttribute('data-mirror'))->toBeTrue();
});

test('trailingIcon replaces the external icon', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-link href="https://example.com" external trailing-icon="arrow-right">Docs</x-artisanpack-link>'));

    expect($dom->query('//*[@data-part="iconEnd"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="iconEnd"]')->item(0)->hasAttribute('data-mirror'))->toBeTrue();
});

test('icon slots override the names', function (): void {
    $html = Blade::render('<x-artisanpack-link href="/"><x-slot:icon><i>A</i></x-slot> Home</x-artisanpack-link>');

    expect($html)->toContain('<span data-scope="link" data-part="icon" aria-hidden="true"><i>A</i></span>');
});

test('internal links use wire:navigate by default, and navigate off turns it off', function (): void {
    $on  = designSystemDom(Blade::render('<x-artisanpack-link href="/dashboard">Dashboard</x-artisanpack-link>'))->query('//a')->item(0);
    $off = designSystemDom(Blade::render('<x-artisanpack-link href="/dashboard" :navigate="false">Dashboard</x-artisanpack-link>'))->query('//a')->item(0);

    expect($on->hasAttribute('wire:navigate'))->toBeTrue()
        ->and($off->hasAttribute('wire:navigate'))->toBeFalse();
});

test('fragment, mailto, off-site and new-tab links never use wire:navigate', function (string $markup): void {
    $root = designSystemDom(Blade::render($markup))->query('//a')->item(0);

    expect($root->hasAttribute('wire:navigate'))->toBeFalse();
})->with([
    'fragment'          => '<x-artisanpack-link href="#terms">Terms</x-artisanpack-link>',
    'mailto'            => '<x-artisanpack-link href="mailto:team@example.com">Email</x-artisanpack-link>',
    'off-site'          => '<x-artisanpack-link href="https://example.org/docs">Docs</x-artisanpack-link>',
    'protocol-relative' => '<x-artisanpack-link href="//example.org/docs">Docs</x-artisanpack-link>',
    'external'          => '<x-artisanpack-link href="/docs" external>Docs</x-artisanpack-link>',
]);

test('classes reach the root and the parts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-link href="/" leading-icon="download" :classes="[\'root\' => \'r\', \'icon\' => \'i\']" class="extra">A</x-artisanpack-link>'));

    expect($dom->query('//a')->item(0)->getAttribute('class'))->toBe('apui-link r extra')
        ->and($dom->query('//*[@data-part="icon"]')->item(0)->getAttribute('class'))->toBe('i');
});

test('the deprecated 2.x no-wire-navigate attribute still turns navigate off', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-link href="/dashboard" no-wire-navigate>Dashboard</x-artisanpack-link>'))->query('//a')->item(0);

    expect($root->hasAttribute('wire:navigate'))->toBeFalse()
        ->and($root->hasAttribute('no-wire-navigate'))->toBeFalse();
});
