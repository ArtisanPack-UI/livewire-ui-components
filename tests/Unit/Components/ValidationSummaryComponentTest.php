<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\ValidationSummary;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * ValidationSummary (#53): the error bag, the errors prop, links to fields,
 * focus and the warning color. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

beforeEach(function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag([
        'postal_code' => ['Spanish postal codes have 5 digits.', 'Second message.'],
        'photos'      => 'Add at least 2 more photos.',
    ])));
});

test('it lists the first message of every key in $errors, linked to its field', function (): void {
    $html = Blade::render('<x-artisanpack-validation-summary />');
    $dom  = designSystemDom($html);
    $root = $dom->query('//div')->item(0);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="validation-summary"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $links = $dom->query('//a');

    expect($root->getAttribute('class'))->toBe('apui-validation-summary')
        ->and($root->getAttribute('role'))->toBe('alert')
        ->and($root->getAttribute('tabindex'))->toBe('-1')
        ->and($root->getAttribute('data-color'))->toBe('error')
        ->and($root->getAttribute('x-init'))->toBe('$nextTick(() => $el.focus())')
        ->and($parts)->toBe(['icon', 'title', 'list', 'item', 'link', 'item', 'link'])
        ->and($dom->query('//p')->item(0)->textContent)->toBe('Fix 2 fields')
        ->and($links->item(0)->getAttribute('href'))->toBe('#postal_code')
        ->and($links->item(0)->textContent)->toBe('Spanish postal codes have 5 digits.')
        ->and($links->item(1)->getAttribute('href'))->toBe('#photos')
        ->and($html)->toContain(IconRegistry::svg('circle-alert'));
});

test('a link focuses the field by id, name or wire:model', function (): void {
    $click = designSystemDom(Blade::render('<x-artisanpack-validation-summary />'))->query('//a')->item(0)->getAttribute('x-on:click');

    expect($click)->toContain('document.getElementById(field)')
        ->and($click)->toContain("[name='\${CSS.escape(field)}']")
        ->and($click)->toContain("attr.name.startsWith('wire:model')")
        ->and($click)->toContain('target.focus()');
});

test('only picks and orders the keys', function (): void {
    $links = designSystemDom(Blade::render('<x-artisanpack-validation-summary :only="[ \'photos\', \'missing\', \'postal_code\' ]" />'))->query('//a');

    expect($links->length)->toBe(2)
        ->and($links->item(0)->getAttribute('href'))->toBe('#photos');
});

test('it renders nothing without errors', function (): void {
    view()->share('errors', new ViewErrorBag);

    expect(trim(Blade::render('<x-artisanpack-validation-summary />')))->toBe('');
});

test('the errors prop wins over the bag, as a list or a map', function (): void {
    expect(ValidationSummary::normalize([['field' => 'a', 'message' => 'A']]))->toBe([['field' => 'a', 'message' => 'A']])
        ->and(ValidationSummary::normalize(['a' => ['A1', 'A2'], 'b' => 'B']))->toBe([['field' => 'a', 'message' => 'A1'], ['field' => 'b', 'message' => 'B']]);

    $links = designSystemDom(Blade::render('<x-artisanpack-validation-summary :errors="[ \'email\' => \'Enter an email.\' ]" />'))->query('//a');

    expect($links->length)->toBe(1)->and($links->item(0)->getAttribute('href'))->toBe('#email');
});

test('a warning is a status that does not take focus', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-validation-summary color="warning" :errors="[ [ \'field\' => \'d\', \'message\' => \'Short.\' ] ]" />'));
    $root = $dom->query('//div')->item(0);

    expect($root->getAttribute('role'))->toBe('status')
        ->and($root->hasAttribute('tabindex'))->toBeFalse()
        ->and($root->hasAttribute('x-init'))->toBeFalse()
        ->and($dom->query('//p')->item(0)->textContent)->toBe('1 recommendation');
});

test('a title prop or slot replaces the count', function (): void {
    expect(Blade::render('<x-artisanpack-validation-summary title="Fix these" />'))->toContain('data-part="title">Fix these</p>');
});
