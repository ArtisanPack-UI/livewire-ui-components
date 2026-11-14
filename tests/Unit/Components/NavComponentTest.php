<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Nav;
use ArtisanPack\LivewireUiComponents\View\Components\Navbar;
use Illuminate\Support\Facades\Blade;

/*
 * nav (#145) is a deprecated alias of navbar.
 */

test('extends Navbar', function (): void {
    expect(is_subclass_of(Nav::class, Navbar::class))->toBeTrue();
});

test('renders the navbar with the 2.x brand and actions slots', function (): void {
    $dom    = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-nav full-width>
            <x-slot:brand><a href="/">Horizon</a></x-slot:brand>
            <x-slot:actions><button>Sign in</button></x-slot:actions>
        </x-artisanpack-nav>
        BLADE));
    $header = $dom->query('//header[contains(@class, "apui-navbar")]')->item(0);

    expect($header->getAttribute('data-layout'))->toBe('bar')
        ->and($header->getAttribute('data-surface'))->toBe('default')
        ->and($header->hasAttribute('data-sticky'))->toBeFalse()
        ->and($dom->query('//*[@data-part="brand"]/a[@href="/"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="actions"]/button')->item(0)->textContent))->toBe('Sign in');
});

test('sticky keeps its 2.x meaning', function (): void {
    $header = designSystemDom(Blade::render('<x-artisanpack-nav sticky brand="Horizon" />'))
        ->query('//header[contains(@class, "apui-navbar")]')->item(0);

    expect($header->hasAttribute('data-sticky'))->toBeTrue();
});

test('the removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-nav sticky full-width brand="Horizon" actions="Menu" />');

    foreach (['full-width', 'sticky=', 'brand=', 'actions='] as $attribute) {
        expect($html)->not->toContain(' '.$attribute);
    }
});
