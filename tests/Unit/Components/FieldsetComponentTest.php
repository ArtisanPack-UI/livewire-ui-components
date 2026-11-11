<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Fieldset (#53): the native fieldset and legend, variants and disabled.
 * The manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

test('it renders a native fieldset with the legend parts', function (): void {
    $html = Blade::render('<x-artisanpack-fieldset title="Contact" description="Who buyers should reach." class="mt-4">Fields</x-artisanpack-fieldset>');
    $dom  = designSystemDom($html);
    $root = $dom->query('//fieldset')->item(0);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="fieldset"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($root->getAttribute('class'))->toBe('apui-fieldset mt-4')
        ->and($root->getAttribute('data-variant'))->toBe('panel')
        ->and($parts)->toBe(['legend', 'title', 'description'])
        ->and($dom->query('//legend')->item(0)->textContent)->toContain('Contact')
        ->and($html)->toContain('Fields');
});

test('without a title or description there is no legend', function (): void {
    expect(Blade::render('<x-artisanpack-fieldset>Fields</x-artisanpack-fieldset>'))->not->toContain('<legend');
});

test('variant and disabled render', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-fieldset variant="divided" title="A" disabled />'))->query('//fieldset')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('divided')
        ->and($root->hasAttribute('disabled'))->toBeTrue()
        ->and($root->hasAttribute('data-disabled'))->toBeTrue();
});

test('title and description slots override the props', function (): void {
    $html = Blade::render("<x-artisanpack-fieldset>\n<x-slot:title><em>Billing</em></x-slot:title>\nFields\n</x-artisanpack-fieldset>");

    expect($html)->toContain('<span data-scope="fieldset" data-part="title"><em>Billing</em></span>');
});
