<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * group (#145) is a deprecated alias of radio-group with variant segmented:
 * the 2.x joined buttons.
 */

$legacyOptions = "[ [ 'id' => 'day', 'name' => 'Day' ], [ 'id' => 'week', 'name' => 'Week' ], [ 'id' => 'month', 'name' => 'Month', 'disabled' => true ] ]";

test('renders a horizontal segmented radio group', function () use ($legacyOptions): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-group id="range" label="Range" wire:model="range" :options="'.$legacyOptions.'" />'));
    $root = $dom->query('//fieldset')->item(0);

    $values = [];
    foreach ($dom->query('//input') as $input) {
        $values[] = $input->getAttribute('value');
        expect($input->getAttribute('type'))->toBe('radio')
            ->and($input->getAttribute('name'))->toBe('range')
            ->and($input->getAttribute('wire:model'))->toBe('range');
    }

    expect($root->getAttribute('class'))->toBe('apui-radio-group')
        ->and($root->getAttribute('id'))->toBe('range')
        ->and($root->getAttribute('data-variant'))->toBe('segmented')
        ->and($root->getAttribute('data-orientation'))->toBe('horizontal')
        ->and(trim($dom->query('//legend')->item(0)->textContent))->toBe('Range')
        ->and($values)->toBe(['day', 'week', 'month'])
        ->and(trim($dom->query('//*[@data-part="item-text"]')->item(2)->textContent))->toBe('Month')
        ->and($dom->query('//input[@value="month"]')->item(0)->hasAttribute('disabled'))->toBeTrue();
});

test('reads custom option keys through option-value and option-label', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-group option-value="key" option-label="title" :options="[ [ \'key\' => \'a\', \'title\' => \'Alpha\' ] ]" />'));

    expect($dom->query('//input')->item(0)->getAttribute('value'))->toBe('a')
        ->and(trim($dom->query('//*[@data-part="item-text"]')->item(0)->textContent))->toBe('Alpha');
});

test('hint-class and error-class become part classes', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-group hint="H" error="Pick one." hint-class="my-hint" error-class="my-error" :options="[ \'A\' ]" />'));

    expect($dom->query('//*[@data-part="hint"]')->item(0)->getAttribute('class'))->toBe('my-hint')
        ->and($dom->query('//*[@data-part="error"]')->item(0)->getAttribute('class'))->toBe('my-error');
});

test('removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-group hint-class="h" error-class="e" option-value="id" option-label="name" omit-error first-error-only error-field="range" :options="[ \'A\' ]" />');

    foreach (['hint-class', 'error-class', 'option-value', 'option-label', 'omit-error', 'first-error-only', 'error-field'] as $attribute) {
        expect($html)->not->toContain($attribute);
    }
});
