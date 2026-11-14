<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;

/*
 * EmptyState (#60). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function emptyStateRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-empty-state")]')->item(0);
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-empty-state title="No properties yet" description="Add your first property." actions="Add property" />');
    $dom  = designSystemDom($html);
    $root = emptyStateRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="empty-state"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'icon', 'title', 'description', 'actions'])
        ->and($root->getAttribute('data-variant'))->toBe('first-use')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-align'))->toBe('center')
        ->and($root->hasAttribute('role'))->toBeFalse()
        ->and($dom->query('//h3[@data-part="title"]')->item(0)->textContent)->toBe('No properties yet')
        ->and($html)->toContain('<span data-scope="empty-state" data-part="icon" aria-hidden="true">'.IconRegistry::render('inbox').'</span>');
});

test('each variant has its default icon, and icon overrides it', function (string $variant, string $icon): void {
    $html = Blade::render('<x-artisanpack-empty-state title="T" :variant="$variant" />', ['variant' => $variant]);

    expect(emptyStateRoot(designSystemDom($html))->getAttribute('data-variant'))->toBe($variant)
        ->and($html)->toContain(IconRegistry::render($icon));
})->with([
    'first-use'  => ['first-use', 'inbox'],
    'no-results' => ['no-results', 'search-x'],
    'cleared'    => ['cleared', 'circle-check'],
]);

test('headingLevel picks the heading, clamped to 2–6', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-empty-state title="T" :heading-level="4" />'))->query('//h4[@data-part="title"]'))->toHaveCount(1)
        ->and(designSystemDom(Blade::render('<x-artisanpack-empty-state title="T" :heading-level="1" />'))->query('//h2[@data-part="title"]'))->toHaveCount(1);
});

test('size, align and icon render, slots override props and the default slot is the description', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-empty-state size="sm" align="start" icon="trash" class="my-4" :classes="['actions' => 'a']">
            <x-slot:title>Rich <em>title</em></x-slot:title>
            <x-slot:actions><button>Clear</button></x-slot:actions>
            Nothing <strong>here</strong>.
        </x-artisanpack-empty-state>
        BLADE);
    $dom = designSystemDom($html);

    expect(emptyStateRoot($dom)->getAttribute('data-size'))->toBe('sm')
        ->and(emptyStateRoot($dom)->getAttribute('data-align'))->toBe('start')
        ->and(emptyStateRoot($dom)->getAttribute('class'))->toBe('apui-empty-state my-4')
        ->and($html)->toContain(IconRegistry::render('trash'))
        ->and($dom->query('//h3[@data-part="title"]/em')->item(0)->textContent)->toBe('title')
        ->and($dom->query('//div[@data-part="description"]/strong')->item(0)->textContent)->toBe('here')
        ->and($dom->query('//div[@data-part="actions"]')->item(0)->getAttribute('class'))->toBe('a');
});
