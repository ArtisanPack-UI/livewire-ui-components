<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Header;
use ArtisanPack\LivewireUiComponents\View\Components\PageHeader;
use Illuminate\Support\Facades\Blade;

/*
 * Header (deprecated 3.0.0): the 2.x header, rendered by the design-system
 * PageHeader (#58).
 */

test('is a PageHeader with the 2.x level 2', function (): void {
    $header = new Header(title: 'T');

    expect($header)->toBeInstanceOf(PageHeader::class)
        ->and($header->level)->toBe(2);
});

test('renders subtitle as the description, the middle slot and actions', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-header title="Users" subtitle="All accounts" separator progress-indicator><x-slot:middle><input></x-slot:middle><x-slot:actions><button>Add</button></x-slot:actions></x-artisanpack-header>'));

    expect($dom->query('//div[contains(@class, "apui-page-header")]')->length)->toBe(1)
        ->and($dom->query('//h2[@data-part="title"]')->item(0)->textContent)->toBe('Users')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('All accounts')
        ->and($dom->query('//*[@data-part="row"]/div/input')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="actions"]/button')->length)->toBe(1)
        ->and($dom->query('//progress | //hr')->length)->toBe(0);
});

test('renders a subtitle slot as the description', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-header title="T"><x-slot:subtitle>Sub</x-slot:subtitle></x-artisanpack-header>'));

    expect($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('Sub');
});
