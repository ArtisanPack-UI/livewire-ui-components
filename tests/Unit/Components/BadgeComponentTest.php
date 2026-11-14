<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Badge (#61): the design-system markup contract. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

test('renders a soft neutral md badge with a label part', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-badge>Draft</x-artisanpack-badge>'))->query('//span[contains(@class, "apui-badge")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('data-variant'))->toBe('soft')
        ->and($root->getAttribute('data-color'))->toBe('neutral')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and(trim($root->textContent))->toBe('Draft');
});

test('variant, color and size become data attributes; unknown values fall back', function (): void {
    $set     = designSystemDom(Blade::render('<x-artisanpack-badge variant="solid" color="success" size="lg">Paid</x-artisanpack-badge>'))->query('//span')->item(0);
    $unknown = designSystemDom(Blade::render('<x-artisanpack-badge variant="glass" color="red-500" size="xl">Paid</x-artisanpack-badge>'))->query('//span')->item(0);

    expect($set->getAttribute('data-variant'))->toBe('solid')
        ->and($set->getAttribute('data-color'))->toBe('success')
        ->and($set->getAttribute('data-size'))->toBe('lg')
        ->and($unknown->getAttribute('data-variant'))->toBe('soft')
        ->and($unknown->getAttribute('data-color'))->toBe('neutral')
        ->and($unknown->getAttribute('data-size'))->toBe('md');
});

test('the dot and the icon are decorative parts, and arrows mirror', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-badge dot leading-icon="arrow-right">Up</x-artisanpack-badge>'));
    $dot  = $dom->query('//*[@data-scope="badge"][@data-part="dot"]')->item(0);
    $icon = $dom->query('//*[@data-scope="badge"][@data-part="icon"]')->item(0);

    expect($dot->getAttribute('aria-hidden'))->toBe('true')
        ->and($icon->getAttribute('aria-hidden'))->toBe('true')
        ->and($icon->hasAttribute('data-mirror'))->toBeTrue()
        ->and($dom->query('//*[@data-part="icon"]/svg'))->toHaveCount(1);
});

test('the icon slot overrides leading-icon', function (): void {
    $html = Blade::render('<x-artisanpack-badge leading-icon="check"><x-slot:icon><b>!</b></x-slot:icon> New</x-artisanpack-badge>');

    expect(designSystemDom($html)->query('//*[@data-part="icon"]/b'))->toHaveCount(1)
        ->and(designSystemDom($html)->query('//*[@data-part="icon"]/svg'))->toHaveCount(0);
});

test('the deprecated value attribute renders as the label', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-badge value="New" />'))->query('//span[contains(@class, "apui-badge")]')->item(0);

    expect(trim($root->textContent))->toBe('New')
        ->and($root->hasAttribute('value'))->toBeFalse();
});

test('classes reach the parts and attributes reach the root', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-badge id="b1" :classes="[ \'label\' => \'x-label\' ]" class="extra">Hi</x-artisanpack-badge>'));

    expect($dom->query('//span[@id="b1"][contains(@class, "extra")]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="label"][@class="x-label"]'))->toHaveCount(1);
});
