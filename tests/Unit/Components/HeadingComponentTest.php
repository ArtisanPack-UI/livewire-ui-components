<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Heading (#58): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders an h2 by default, with size following level', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-heading>Listings</x-artisanpack-heading>'))->query('//h2[contains(@class, "apui-heading")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('data-level'))->toBe('2')
        ->and($root->getAttribute('data-size'))->toBe('2')
        ->and(trim($root->textContent))->toBe('Listings');
});

test('level and size are set apart', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-heading level="3" size="5">Recent activity</x-artisanpack-heading>'))->query('//h3')->item(0);
    $int  = designSystemDom(Blade::render('<x-artisanpack-heading :level="1">Title</x-artisanpack-heading>'))->query('//h1')->item(0);

    expect($root->getAttribute('data-size'))->toBe('5')
        ->and($int)->not->toBeNull();
});

test('an out-of-range level falls back to 2 and size to the level', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-heading level="9" size="0">Title</x-artisanpack-heading>'))->query('//h2')->item(0);

    expect($root->getAttribute('data-size'))->toBe('2');
});

test('the eyebrow is a paragraph just before the heading', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-heading eyebrow="Portfolio">Every property</x-artisanpack-heading>'));
    $eyebrow = $dom->query('//p[@data-scope="heading"][@data-part="eyebrow"]')->item(0);

    expect(trim($eyebrow->textContent))->toBe('Portfolio')
        ->and($dom->query('//p[@data-part="eyebrow"]/following-sibling::h2'))->toHaveCount(1);
});

test('an eyebrow slot overrides the prop', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-heading eyebrow="A"><x-slot:eyebrow><em>B</em></x-slot:eyebrow> Title</x-artisanpack-heading>'));

    expect($dom->query('//p[@data-part="eyebrow"]/em'))->toHaveCount(1);
});

test('attributes land on the heading and classes on the eyebrow', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-heading id="t" class="x" eyebrow="E" :classes="[ \'eyebrow\' => \'y\' ]">Title</x-artisanpack-heading>'));

    expect($dom->query('//h2[@id="t"][contains(@class, "x")]'))->toHaveCount(1)
        ->and($dom->query('//p[@data-part="eyebrow"][@class="y"]'))->toHaveCount(1);
});
