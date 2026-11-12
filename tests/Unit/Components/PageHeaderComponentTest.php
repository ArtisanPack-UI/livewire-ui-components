<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * PageHeader (#58): the design-system page title block. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders breadcrumbs, the row with meta, title, description and actions, then tabs', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-page-header title="Dashboard" description="Income and profit.">
            <x-slot:breadcrumbs><nav>Apexify</nav></x-slot:breadcrumbs>
            <x-slot:meta>Draft</x-slot:meta>
            <x-slot:actions><button>Share</button></x-slot:actions>
            <x-slot:tabs><div>Overview</div></x-slot:tabs>
        </x-artisanpack-page-header>
        BLADE));

    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="page-header"]')));
    expect($parts)->toBe(['breadcrumbs', 'row', 'meta', 'title', 'description', 'actions', 'tabs'])
        ->and($dom->query('//h1[@data-part="title"]')->item(0)->textContent)->toBe('Dashboard')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('Income and profit.')
        ->and($dom->query('//*[@data-part="actions"]/button')->length)->toBe(1);
});

test('leaves empty parts out', function (): void {
    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array(designSystemDom(Blade::render('<x-artisanpack-page-header title="T" />'))->query('//*[@data-scope="page-header"]')));

    expect($parts)->toBe(['row', 'title']);
});

test('takes the heading level, clamped to 1–6, and a title slot', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-page-header title="T" :level="3" />'))->query('//h3')->length)->toBe(1)
        ->and(designSystemDom(Blade::render('<x-artisanpack-page-header title="T" :level="9" />'))->query('//h6')->length)->toBe(1)
        ->and(designSystemDom(Blade::render('<x-artisanpack-page-header><x-slot:title>A <em>B</em></x-slot:title></x-artisanpack-page-header>'))->query('//h1/em')->length)->toBe(1);
});

test('applies classes per part and class to the root', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-page-header class="mb-6" title="T" :classes="[\'title\' => \'my-title\']" />'));

    expect($dom->query('//div[contains(@class, "apui-page-header") and contains(@class, "mb-6")]')->length)->toBe(1)
        ->and($dom->query('//h1[@class="my-title"]')->length)->toBe(1);
});
