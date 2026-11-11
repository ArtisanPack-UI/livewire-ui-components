<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Breadcrumbs (#56): the design-system markup contract. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function breadcrumbsDom(string $props = ''): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-breadcrumbs :items="$items" '.$props.' />', [
        'items' => [
            ['label' => 'Home', 'href' => '/', 'icon' => 'house', 'hideLabel' => true],
            ['label' => 'Properties', 'href' => '/properties'],
            ['label' => 'Lakeview Lofts', 'href' => '/properties/1'],
            ['label' => 'Analytics', 'href' => '/properties/1/analytics'],
            ['label' => 'Visitor', 'href' => '/ignored'],
        ],
    ]));
}

test('renders a named nav landmark with an ordered list', function (): void {
    $dom  = breadcrumbsDom(':max-items="0"');
    $root = $dom->query('//nav')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-breadcrumbs')
        ->and($root->getAttribute('aria-label'))->toBe('Breadcrumb')
        ->and($dom->query('//nav/ol[@data-part="list"]/li[@data-part="item"]'))->toHaveCount(5)
        ->and($dom->query('//*[@data-part="overflow"]'))->toHaveCount(0)
        ->and($root->hasAttribute('x-data'))->toBeFalse();
});

test('the last item is the current page, never a link', function (): void {
    $dom     = breadcrumbsDom(':max-items="0"');
    $current = $dom->query('//*[@data-part="current"]')->item(0);

    expect($current->tagName)->toBe('span')
        ->and($current->getAttribute('aria-current'))->toBe('page')
        ->and(trim($current->textContent))->toBe('Visitor')
        ->and($dom->query('//a[@href="/ignored"]'))->toHaveCount(0)
        ->and($dom->query('//a[@data-part="link"]'))->toHaveCount(4);
});

test('separators sit before every level but the first, hidden and mirrored', function (): void {
    $dom = breadcrumbsDom(':max-items="0"');

    expect($dom->query('//li[1]/*[@data-part="separator"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="separator"]'))->toHaveCount(4);

    foreach ($dom->query('//*[@data-part="separator"]') as $separator) {
        expect($separator->getAttribute('aria-hidden'))->toBe('true')
            ->and($separator->hasAttribute('data-mirror'))->toBeTrue();
    }
});

test('a hidden label stays for screen readers next to the icon', function (): void {
    $link = breadcrumbsDom(':max-items="0"')->query('//a[@href="/"]')->item(0);

    expect($link->getElementsByTagName('svg'))->toHaveCount(1)
        ->and($link->getElementsByTagName('span')->item(0)->getAttribute('class'))->toBe('apui-sr-only');
});

test('past maxItems the middle collapses behind the overflow button', function (): void {
    $dom      = breadcrumbsDom();
    $overflow = $dom->query('//button[@data-part="overflow"]')->item(0);
    $hidden   = $dom->query('//li[@hidden]');

    expect($overflow->getAttribute('aria-label'))->toBe('Show 2 more levels')
        ->and($overflow->getAttribute('aria-expanded'))->toBe('false')
        ->and($overflow->getAttribute('type'))->toBe('button')
        ->and($hidden)->toHaveCount(2)
        ->and(trim($hidden->item(0)->textContent))->toBe('Properties')
        ->and($dom->query('//nav')->item(0)->getAttribute('x-data'))->toBe('{ expanded: false }');

    $order = [];
    foreach ($dom->query('//li[not(@hidden)]') as $item) {
        $order[] = trim($item->textContent);
    }
    expect($order)->toBe(['Home', '', 'Analytics', 'Visitor']);
});

test('the labels are translatable props', function (): void {
    $dom = breadcrumbsDom('label="Fil d\'Ariane" overflow-label="Afficher {count} niveaux"');

    expect($dom->query('//nav')->item(0)->getAttribute('aria-label'))->toBe("Fil d'Ariane")
        ->and($dom->query('//button')->item(0)->getAttribute('aria-label'))->toBe('Afficher 2 niveaux');
});

test('the 2.x link key still works, and unsafe URLs are dropped', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-breadcrumbs :items="$items" />', [
        'items' => [
            ['label' => 'Forms', 'link' => '/forms'],
            ['label' => 'Bad', 'href' => 'javascript:alert(1)'],
            ['label' => 'Submissions'],
        ],
    ]));

    expect($dom->query('//a[@href="/forms"]'))->toHaveCount(1)
        ->and($dom->query('//a'))->toHaveCount(1)
        ->and(trim($dom->query('//li[2]/span[not(@data-part)]')->item(0)->textContent))->toBe('Bad');
});

test('internal links use wire:navigate by default, and navigate off turns it off', function (): void {
    $on  = breadcrumbsDom(':max-items="0"');
    $off = breadcrumbsDom(':max-items="0" :navigate="false"');

    expect($on->query('//a'))->not->toHaveCount(0);
    foreach ($on->query('//a') as $link) {
        expect($link->hasAttribute('wire:navigate'))->toBeTrue();
    }
    foreach ($off->query('//a') as $link) {
        expect($link->hasAttribute('wire:navigate'))->toBeFalse();
    }
});

test('fragment and off-site links never use wire:navigate', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-breadcrumbs :items="$items" />', [
        'items' => [
            ['label' => 'Docs', 'href' => 'https://example.org/docs'],
            ['label' => 'Section', 'href' => '#section'],
            ['label' => 'Here'],
        ],
    ]));

    expect($dom->query('//a'))->toHaveCount(2);
    foreach ($dom->query('//a') as $link) {
        expect($link->hasAttribute('wire:navigate'))->toBeFalse();
    }
});

test('array attributes from 2.x callers are dropped instead of breaking', function (): void {
    $html = Blade::render('<x-artisanpack-breadcrumbs :items="[[\'label\' => \'A\']]" :legacy="[1, 2]" class="mt-2" />');

    expect($html)->toContain('class="apui-breadcrumbs mt-2"')
        ->and($html)->not->toContain('legacy');
});

test('composes the trail from breadcrumbs-item children after items', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-breadcrumbs :items="[[ 'label' => 'Home', 'href' => '/' ]]">
            <x-artisanpack-breadcrumbs-item href="/properties" label="Properties" />
            <x-artisanpack-breadcrumbs-item href="/properties/1" wire:click.prevent="open(1)"><strong>Lakeview</strong> Lofts</x-artisanpack-breadcrumbs-item>
            <x-artisanpack-breadcrumbs-item href="/ignored" label="Visitor" />
        </x-artisanpack-breadcrumbs>
        BLADE));

    $labels = [];
    foreach ($dom->query('//li[@data-part="item"]') as $item) {
        $labels[] = trim($item->textContent);
    }

    $current = $dom->query('//*[@data-part="current"]')->item(0);
    $rich    = $dom->query('//a[@href="/properties/1"]')->item(0);

    expect($labels)->toBe(['Home', 'Properties', 'Lakeview Lofts', 'Visitor'])
        ->and($dom->query('//*[@data-part="separator"]'))->toHaveCount(3)
        ->and($current->getAttribute('aria-current'))->toBe('page')
        ->and(trim($current->textContent))->toBe('Visitor')
        ->and($dom->query('//a[@href="/ignored"]'))->toHaveCount(0)
        ->and($rich->getAttribute('data-part'))->toBe('link')
        ->and($rich->getAttribute('wire:click.prevent'))->toBe('open(1)')
        ->and($rich->getElementsByTagName('strong'))->toHaveCount(1);
});

test('renders media-library\'s 2.x links in the slot as the trail', function (): void {
    // resources/views/livewire/pages/media-library.blade.php in artisanpack-ui/media-library.
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-breadcrumbs class="mt-2">
            <x-artisanpack-link href="#" wire:click.prevent="setFolder(null)">
                All Media
            </x-artisanpack-link>
            <x-artisanpack-link>Invoices</x-artisanpack-link>
        </x-artisanpack-breadcrumbs>
        BLADE);
    $dom  = designSystemDom($html);
    $link = $dom->query('//a[@data-part="link"]')->item(0);

    expect($dom->query('//nav')->item(0)->getAttribute('class'))->toBe('apui-breadcrumbs mt-2')
        ->and($dom->query('//li[@data-part="item"]'))->toHaveCount(2)
        ->and($link->getAttribute('href'))->toBe('#')
        ->and($link->getAttribute('wire:click.prevent'))->toBe('setFolder(null)')
        ->and(trim($link->textContent))->toBe('All Media')
        ->and($dom->query('//li[2]/*[@data-part="separator"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="current"][@aria-current="page"]')->item(0)->textContent)->toBe('Invoices')
        ->and($dom->query('//*[contains(@class, "apui-link")]'))->toHaveCount(0);
});

test('composed children collapse like items', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-breadcrumbs>
            <x-artisanpack-breadcrumbs-item href="/" label="A" />
            <x-artisanpack-breadcrumbs-item href="/b" label="B" />
            <x-artisanpack-breadcrumbs-item href="/c" label="C" />
            <x-artisanpack-breadcrumbs-item href="/d" label="D" />
            <x-artisanpack-breadcrumbs-item label="E" />
        </x-artisanpack-breadcrumbs>
        BLADE));

    expect($dom->query('//button[@data-part="overflow"]')->item(0)->getAttribute('aria-label'))->toBe('Show 2 more levels')
        ->and($dom->query('//li[@hidden]'))->toHaveCount(2);
});

test('a link and a breadcrumbs item outside a trail behave on their own', function (): void {
    expect(Blade::render('<x-artisanpack-breadcrumbs-item label="Alone" />'))->not->toContain('Alone')
        ->and(Blade::render('<x-artisanpack-breadcrumbs :items="[[\'label\' => \'A\']]" /><x-artisanpack-link href="/x">After</x-artisanpack-link>'))->toContain('class="apui-link"');
});

test('the deprecated 2.x no-wire-navigate attribute still turns navigate off', function (): void {
    $dom = breadcrumbsDom(':max-items="0" no-wire-navigate');

    foreach ($dom->query('//a') as $link) {
        expect($link->hasAttribute('wire:navigate'))->toBeFalse();
    }
    expect($dom->query('//nav')->item(0)->hasAttribute('no-wire-navigate'))->toBeFalse();
});
