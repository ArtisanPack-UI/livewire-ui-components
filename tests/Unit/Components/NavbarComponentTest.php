<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Navbar (#56): the design-system top bar. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('renders the header with brand, links, search and actions', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-navbar>
            <x-slot:brand><a href="/">Horizon</a></x-slot:brand>
            <x-artisanpack-navbar-item href="/properties" current>Properties</x-artisanpack-navbar-item>
            <x-artisanpack-navbar-item href="/analytics">Analytics</x-artisanpack-navbar-item>
            <x-slot:search>Search</x-slot:search>
            <x-slot:actions><button>Sign in</button></x-slot:actions>
        </x-artisanpack-navbar>
        BLADE);
    $dom    = designSystemDom($html);
    $header = $dom->query('//header[contains(@class, "apui-navbar")]')->item(0);

    expect($header->getAttribute('data-layout'))->toBe('bar')
        ->and($header->getAttribute('data-surface'))->toBe('default')
        ->and($header->hasAttribute('data-sticky'))->toBeTrue();
    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="navbar"]')));
    expect($parts)->toBe(['brand', 'links', 'link', 'link', 'search', 'actions']);
    expect($dom->query('//nav[@data-part="links"]')->item(0)->getAttribute('aria-label'))->toBe('Main');
    expect($dom->query('//a[@aria-current="page"]')->item(0)->getAttribute('href'))->toBe('/properties');
});

test('renders the same links from data as from composition', function (): void {
    $data = Blade::render('<x-artisanpack-navbar brand="H" :links="$links" />', ['links' => [
        ['label' => 'Properties', 'href' => '/properties', 'current' => true],
        ['label' => 'Analytics', 'href' => '/analytics'],
    ]]);
    $composed  = Blade::render('<x-artisanpack-navbar brand="H"><x-artisanpack-navbar-item href="/properties" current>Properties</x-artisanpack-navbar-item><x-artisanpack-navbar-item href="/analytics">Analytics</x-artisanpack-navbar-item></x-artisanpack-navbar>');
    $normalize = static fn (string $html): string => (string) preg_replace('/\s+/', ' ', trim($html));

    expect($normalize($data))->toBe($normalize($composed));
});

test('emits layout, surface and sticky, and leaves empty parts out', function (): void {
    $html   = Blade::render('<x-artisanpack-navbar layout="island" surface="glass" :sticky="false" label="Primary" brand="H" />');
    $dom    = designSystemDom($html);
    $header = $dom->query('//header')->item(0);

    expect($header->getAttribute('data-layout'))->toBe('island')
        ->and($header->getAttribute('data-surface'))->toBe('glass')
        ->and($header->hasAttribute('data-sticky'))->toBeFalse()
        ->and($dom->query('//*[@data-part="links"]')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="actions"]')->length)->toBe(0);
});

test('falls back to bar and default for unknown values', function (): void {
    $header = designSystemDom(Blade::render('<x-artisanpack-navbar layout="floating" surface="frosted" brand="H" />'))->query('//header')->item(0);

    expect($header->getAttribute('data-layout'))->toBe('bar')
        ->and($header->getAttribute('data-surface'))->toBe('default');
});

test('applies classes per part, and the links label', function (): void {
    $html = Blade::render('<x-artisanpack-navbar label="Primary" :classes="[\'links\' => \'my-links\', \'link\' => \'my-link\']"><x-artisanpack-navbar-item href="/a">A</x-artisanpack-navbar-item></x-artisanpack-navbar>');
    $dom  = designSystemDom($html);

    expect($dom->query('//nav[@aria-label="Primary" and contains(@class, "my-links")]')->length)->toBe(1)
        ->and($dom->query('//a[@class="my-link"]')->length)->toBe(1);
});

test('drops a javascript: href from links and items', function (): void {
    $html  = Blade::render('<x-artisanpack-navbar :links="$links"><x-artisanpack-navbar-item href="javascript:alert(2)">B</x-artisanpack-navbar-item></x-artisanpack-navbar>', ['links' => [['label' => 'A', 'href' => 'javascript:alert(1)']]]);
    $links = designSystemDom($html)->query('//a[@data-part="link"]');

    expect($html)->not->toContain('javascript:')
        ->and($links->length)->toBe(2)
        ->and($links->item(0)->hasAttribute('href'))->toBeFalse()
        ->and($links->item(1)->hasAttribute('href'))->toBeFalse();
});
