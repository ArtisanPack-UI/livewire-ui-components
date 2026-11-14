<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Menu (#56): the design-system navigation menu's markup contract. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function menuComposition(string $attributes = 'id="main" label="Main"'): string
{
    return Blade::render(<<<BLADE
        <x-artisanpack-menu {$attributes}>
            <x-artisanpack-menu-item label="Dashboard" href="#dashboard" icon="list" />
            <x-artisanpack-menu-sub label="Properties" icon="settings">
                <x-artisanpack-menu-item label="Lakeview" href="#lakeview" :current="true" />
                <x-artisanpack-menu-item label="Drafts" href="#drafts" badge="3" />
            </x-artisanpack-menu-sub>
            <x-artisanpack-menu-title label="Settings" />
            <x-artisanpack-menu-separator />
            <x-artisanpack-menu-item label="Billing" href="#billing" :disabled="true" />
        </x-artisanpack-menu>
        BLADE);
}

function menuData(): array
{
    return [
        ['label' => 'Dashboard', 'href' => '#dashboard', 'icon' => 'list'],
        ['label' => 'Properties', 'icon' => 'settings', 'items' => [
            ['label' => 'Lakeview', 'href' => '#lakeview', 'current' => true],
            ['label' => 'Drafts', 'href' => '#drafts', 'badge' => '3'],
        ]],
        ['type'  => 'title', 'label' => 'Settings'],
        ['type'  => 'separator'],
        ['label' => 'Billing', 'href' => '#billing', 'disabled' => true],
    ];
}

/** @return list<string> */
function menuParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="menu"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    return $parts;
}

test('renders a labelled nav with every part, scoped menu', function (): void {
    $html = menuComposition();
    $nav  = designSystemDom($html)->query('//nav[contains(@class, "apui-menu")]')->item(0);

    expect($nav->getAttribute('aria-label'))->toBe('Main')
        ->and($nav->hasAttribute('data-collapsible'))->toBeTrue();
    expect(menuParts($html))->toBe([
        'list',
        'item', 'icon', 'item-text',
        'item', 'icon', 'item-text', 'chevron',
        'sub',
        'item', 'item-text',
        'item', 'item-text', 'badge',
        'title',
        'separator',
        'item', 'item-text',
    ]);
});

test('renders the same markup from items as from composition', function (): void {
    $data      = Blade::render('<x-artisanpack-menu id="main" label="Main" :items="$items" />', ['items' => menuData()]);
    $normalize = static fn (string $html): string => (string) preg_replace('/\s+/', ' ', trim($html));

    expect($normalize($data))->toBe($normalize(menuComposition()));
});

test('marks the current page and opens the sub-menu holding it', function (): void {
    $dom = designSystemDom(menuComposition());

    expect($dom->query('//a[@aria-current="page"]')->item(0)->getAttribute('href'))->toBe('#lakeview');
    $toggle = $dom->query('//button[@data-part="item"]')->item(0);
    expect($toggle->getAttribute('aria-expanded'))->toBe('true')
        ->and($toggle->getAttribute('aria-controls'))->toBe('main-sub-1');
    expect($dom->query('//*[@id="main-sub-1"]')->item(0)->hasAttribute('hidden'))->toBeFalse();
});

test('renders a disabled item without href, aria-disabled, and the separator hidden', function (): void {
    $dom = designSystemDom(menuComposition());

    $billing = $dom->query('//*[@data-part="item"][.//text()[contains(., "Billing")]]')->item(0);
    expect($billing->hasAttribute('href'))->toBeFalse()
        ->and($billing->getAttribute('aria-disabled'))->toBe('true');
    expect($dom->query('//*[@data-part="separator"]')->item(0)->getAttribute('aria-hidden'))->toBe('true');
});

test('shows every sub-menu under a heading when not collapsible', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m" :collapsible="false"><x-artisanpack-menu-sub label="Account"><x-artisanpack-menu-item label="Profile" href="#p" /></x-artisanpack-menu-sub></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    expect($dom->query('//nav')->item(0)->hasAttribute('data-collapsible'))->toBeFalse()
        ->and($dom->query('//button')->length)->toBe(0)
        ->and($dom->query('//span[@data-part="item"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="chevron"]')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="sub"]')->item(0)->hasAttribute('hidden'))->toBeFalse();
});

test('marks items by route with activate-by-route, without leaking the attribute', function (): void {
    $this->get('/');
    $html = Blade::render('<x-artisanpack-menu id="m" activate-by-route><x-artisanpack-menu-item label="Home" href="/" /><x-artisanpack-menu-item label="Other" href="/other" /></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    expect($dom->query('//a[@aria-current="page"]')->length)->toBe(1)
        ->and($dom->query('//a[@aria-current="page"]')->item(0)->getAttribute('href'))->toBe('/')
        ->and($dom->query('//nav')->item(0)->hasAttribute('activate-by-route'))->toBeFalse();
});

test('applies classes per part to the children', function (): void {
    $html = menuComposition('id="main" :classes="[\'item\' => \'my-item\', \'sub\' => \'my-sub\', \'title\' => \'my-title\']"');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-part="item" and contains(@class, "my-item")]')->length)->toBe(5)
        ->and($dom->query('//*[@data-part="sub" and @class="my-sub"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="title" and @class="my-title"]')->length)->toBe(1);
});
