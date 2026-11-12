<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * AppShell (#58): the design-system application frame. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function appShell(string $attributes = 'id="shell"'): string
{
    return Blade::render(<<<BLADE
        <x-artisanpack-app-shell {$attributes}>
            <x-slot:brand><a href="/">Apexify</a></x-slot:brand>
            <x-slot:sidebar><nav class="apui-menu">Menu</nav></x-slot:sidebar>
            <x-slot:header><header class="apui-navbar">Bar</header></x-slot:header>
            <x-slot:footer>Olivia Rhye</x-slot:footer>
            <p>Page content</p>
        </x-artisanpack-app-shell>
        BLADE);
}

test('renders the skip link, sidebar, footer, main and content', function (): void {
    $dom = designSystemDom(appShell());

    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="app-shell"]')));
    expect($parts)->toBe(['skip-link', 'sidebar', 'brand', 'footer', 'main', 'content']);

    expect($dom->query('//a[@data-part="skip-link"]')->item(0)->getAttribute('href'))->toBe('#shell-main')
        ->and(trim($dom->query('//a[@data-part="skip-link"]')->item(0)->textContent))->toBe('Skip to main content')
        ->and($dom->query('//aside')->item(0)->getAttribute('aria-label'))->toBe('Sidebar')
        ->and($dom->query('//main')->item(0)->getAttribute('id'))->toBe('shell-main')
        ->and($dom->query('//main')->item(0)->getAttribute('tabindex'))->toBe('-1')
        ->and($dom->query('//main/header[contains(@class, "apui-navbar")]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="content"]/p')->item(0)->textContent)->toBe('Page content')
        ->and($dom->query('//aside/nav[contains(@class, "apui-menu")]')->length)->toBe(1);
});

test('stays static without collapsible or wire:model', function (): void {
    $root = designSystemDom(appShell())->query('//div[contains(@class, "apui-app-shell")]')->item(0);

    expect($root->hasAttribute('x-data'))->toBeFalse()
        ->and($root->hasAttribute('data-collapsed'))->toBeFalse()
        ->and($root->hasAttribute('style'))->toBeFalse();
});

test('renders the collapse button, collapsed, and dispatches collapsed-change', function (): void {
    $dom    = designSystemDom(appShell('id="shell" collapsible collapsed collapse-label="Hide menu"'));
    $root   = $dom->query('//div[contains(@class, "apui-app-shell")]')->item(0);
    $button = $dom->query('//button[@data-part="collapse-trigger"]')->item(0);

    expect($root->hasAttribute('data-collapsed'))->toBeTrue()
        ->and($root->hasAttribute('data-collapsible'))->toBeTrue()
        ->and($root->getAttribute('x-data'))->toContain('collapsed: true')->toContain("\$dispatch('collapsed-change'")
        ->and($button->getAttribute('aria-pressed'))->toBe('true')
        ->and($button->getAttribute('aria-label'))->toBe('Hide menu')
        ->and($button->getAttribute('aria-controls'))->toBe('shell-sidebar');
});

test('entangles wire:model instead of the session', function (): void {
    $root = designSystemDom(appShell('id="shell" collapsible wire:model="collapsedNav"'))->query('//div[contains(@class, "apui-app-shell")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('$wire.entangle(\'collapsedNav\')')
        ->and($root->getAttribute('x-data'))->not->toContain('fetch(');
});

test('reads the 2.x session state when collapsible', function (): void {
    session(['artisanpack-sidebar-collapsed' => 'true']);
    $root = designSystemDom(appShell('id="shell" collapsible'))->query('//div[contains(@class, "apui-app-shell")]')->item(0);

    expect($root->hasAttribute('data-collapsed'))->toBeTrue();
    session()->forget('artisanpack-sidebar-collapsed');
});

test('sets the sidebar width and gutter tokens, dropping unsafe characters', function (): void {
    $root = designSystemDom(appShell('id="shell" sidebar-width="280px" gutter="1rem;color:red"'))->query('//div[contains(@class, "apui-app-shell")]')->item(0);

    expect($root->getAttribute('style'))->toBe('--apui-sidebar-width: 280px; --apui-shell-gutter: 1remcolor:red');
});

test('takes the labels and classes per part', function (): void {
    $dom = designSystemDom(appShell('id="shell" label="Primary" skip-label="Skip" :classes="[\'sidebar\' => \'my-sidebar\', \'content\' => \'my-content\']"'));

    expect($dom->query('//aside[@aria-label="Primary" and @class="my-sidebar"]')->length)->toBe(1)
        ->and(trim($dom->query('//a[@data-part="skip-link"]')->item(0)->textContent))->toBe('Skip')
        ->and($dom->query('//*[@data-part="content" and @class="my-content"]')->length)->toBe(1);
});

test('merges an author style into the sidebar width style', function (): void {
    $html = appShell('id="shell" sidebar-width="18rem" style="max-width:40rem"');
    preg_match('/<div\b[^>]*>/', $html, $root);

    expect(substr_count($root[0], ' style="'))->toBe(1)
        ->and(designSystemDom($html)->query('//div[@id="shell"]')->item(0)->getAttribute('style'))->toBe('--apui-sidebar-width: 18rem; max-width:40rem');
});
