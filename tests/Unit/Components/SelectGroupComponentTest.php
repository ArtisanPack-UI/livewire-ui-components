<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * select-group (#145) is a deprecated alias of select: the 2.x map of group
 * label to options becomes Select's { label, options } groups.
 */

$legacyGroups = "[ 'Apartments' => [ [ 'id' => 'studio', 'name' => 'Studio' ], [ 'id' => 'loft', 'name' => 'Loft', 'disabled' => true ] ], 'Houses' => [ [ 'id' => 'cabin', 'name' => 'Cabin' ] ] ]";

test('renders the select markup with native option groups', function () use ($legacyGroups): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-select-group id="home" label="Home" wire:model="home" :options="'.$legacyGroups.'" />'));
    $groups = $dom->query('//optgroup');

    expect($dom->query('//select[@id="home"]'))->toHaveCount(1)
        ->and($dom->query('//select')->item(0)->getAttribute('wire:model'))->toBe('home')
        ->and($dom->query('//*[contains(@class, "apui-select")]')->length)->toBeGreaterThan(0)
        ->and($groups)->toHaveCount(2)
        ->and($groups->item(0)->getAttribute('label'))->toBe('Apartments')
        ->and($groups->item(1)->getAttribute('label'))->toBe('Houses')
        ->and($dom->query('//optgroup[@label="Apartments"]/option')->length)->toBe(2)
        ->and(trim($dom->query('//optgroup/option[@value="studio"]')->item(0)->textContent))->toBe('Studio')
        ->and($dom->query('//optgroup/option[@value="loft"]')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//optgroup[@label="Houses"]/option[@value="cabin"]'))->toHaveCount(1);
});

test('has no placeholder option unless one is given, as in 2.x', function () use ($legacyGroups): void {
    $without = designSystemDom(Blade::render('<x-artisanpack-select-group :options="'.$legacyGroups.'" />'));
    $with    = designSystemDom(Blade::render('<x-artisanpack-select-group placeholder="Pick a home" placeholder-value="none" :options="'.$legacyGroups.'" />'));

    expect($without->query('//select/option'))->toHaveCount(0)
        ->and(trim($with->query('//select/option')->item(0)->textContent))->toBe('Pick a home')
        ->and($with->query('//select/option')->item(0)->getAttribute('value'))->toBe('');
});

test('reads custom option keys through option-value and option-label', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-select-group option-value="key" option-label="title" :options="[ \'Group\' => [ [ \'key\' => \'a\', \'title\' => \'Alpha\' ] ] ]" />'));

    expect(trim($dom->query('//optgroup[@label="Group"]/option[@value="a"]')->item(0)->textContent))->toBe('Alpha');
});

test('takes 3.0 { label, options } groups too', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-select-group :options="[ [ \'label\' => \'G\', \'options\' => [ [ \'value\' => \'v\', \'label\' => \'Vee\' ] ] ] ]" />'));

    expect(trim($dom->query('//optgroup[@label="G"]/option[@value="v"]')->item(0)->textContent))->toBe('Vee');
});

test('icon is the leading icon and the hint and error classes become part classes', function () use ($legacyGroups): void {
    $html = Blade::render('<x-artisanpack-select-group icon="o-home" hint="H" error="Pick one." hint-class="my-hint" error-class="my-error" :options="'.$legacyGroups.'" />');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-scope="select"][@data-part="icon"]')->length)->toBeGreaterThan(0)
        ->and($dom->query('//*[@data-part="hint"]')->item(0)->getAttribute('class'))->toBe('my-hint')
        ->and($dom->query('//*[@data-part="error"]')->item(0)->getAttribute('class'))->toBe('my-error');
});

test('removed 2.x props do not leak as HTML attributes', function () use ($legacyGroups): void {
    $html = Blade::render('<x-artisanpack-select-group icon-right="o-x" hint-class="h" error-class="e" placeholder-value="none" inline option-value="id" option-label="name" prepend="p" append="a" omit-error first-error-only error-field="home" :options="'.$legacyGroups.'" />');

    foreach (['icon-right', 'hint-class', 'error-class', 'placeholder-value', 'inline', 'option-value', 'option-label', 'prepend', 'append', 'omit-error', 'first-error-only', 'error-field'] as $attribute) {
        expect($html)->not->toContain($attribute);
    }
});
