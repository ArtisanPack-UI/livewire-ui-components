<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\MenuItem;
use Illuminate\Support\Facades\Blade;

/*
 * MenuItem (#56): a link, a button without href, or a disabled link.
 */

test('renders a link with wire:navigate, and a button without href', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item href="/inbox">Inbox</x-artisanpack-menu-item><x-artisanpack-menu-item wire:click="logout">Log out</x-artisanpack-menu-item></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    $link = $dom->query('//a[@data-part="item"]')->item(0);
    expect($link->getAttribute('href'))->toBe('/inbox')
        ->and($link->hasAttribute('wire:navigate'))->toBeTrue();
    $button = $dom->query('//button[@data-part="item"]')->item(0);
    expect($button->getAttribute('type'))->toBe('button')
        ->and($button->getAttribute('wire:click'))->toBe('logout');
});

test('keeps the deprecated title, link and active working', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item title="Old" link="/old" :active="true" /></x-artisanpack-menu>');
    $link = designSystemDom($html)->query('//a[@data-part="item"]')->item(0);

    expect($link->getAttribute('href'))->toBe('/old')
        ->and($link->getAttribute('aria-current'))->toBe('page')
        ->and($link->textContent)->toContain('Old');
});

test('opens external links in a new tab, and honors no-wire-navigate', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item href="https://example.com" external>Docs</x-artisanpack-menu-item><x-artisanpack-menu-item href="/a" no-wire-navigate>A</x-artisanpack-menu-item><x-artisanpack-menu-item href="/b" :navigate="false">B</x-artisanpack-menu-item></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    expect($dom->query('//a[@target="_blank" and @rel="noopener"]')->length)->toBe(1)
        ->and($html)->not->toContain('wire:navigate');
});

test('matches routes as a prefix, or exactly with exact', function (): void {
    $this->get('/properties/12');
    $prefix = new MenuItem(href: '/properties', activateByRoute: true);
    $exact  = new MenuItem(href: '/properties', activateByRoute: true, exact: true);

    expect($prefix->isCurrent())->toBeTrue()
        ->and($exact->isCurrent())->toBeFalse();
});

test('renders nothing when hidden', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item href="/a" hidden>A</x-artisanpack-menu-item></x-artisanpack-menu>');

    expect(designSystemDom($html)->query('//*[@data-part="item"]')->length)->toBe(0);
});

test('escapes the label', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item href="/a" label="<b>x</b>" /></x-artisanpack-menu>');

    expect($html)->toContain('&lt;b&gt;x&lt;/b&gt;');
});

test('drops a javascript: href and renders a plain non-link', function (string $href): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-item :href="$href" wire:click="go">Bad</x-artisanpack-menu-item></x-artisanpack-menu>', ['href' => $href]);
    $item = designSystemDom($html)->query('//*[@data-part="item"]')->item(0);

    expect($html)->not->toContain('javascript:')
        ->and($html)->not->toContain('data:text')
        ->and($item->nodeName)->toBe('a')
        ->and($item->hasAttribute('href'))->toBeFalse()
        ->and($item->hasAttribute('wire:click'))->toBeFalse();
})->with(['javascript:alert(1)', ' JavaScript:alert(1)', 'data:text/html,<script>alert(1)</script>']);

test('drops a javascript: href from items data', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m" :items="$items" />', ['items' => [['label' => 'Bad', 'href' => 'javascript:alert(1)'], ['label' => 'Bad sub', 'items' => [['label' => 'Inner', 'link' => 'javascript:alert(2)']]]]]);

    expect($html)->not->toContain('javascript:');
});
