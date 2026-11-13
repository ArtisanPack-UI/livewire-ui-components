<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * radio (#145) is a deprecated alias of radio-group: the 2.x option keys,
 * inline and card map onto the RadioGroup API.
 */

$legacyOptions = "[ [ 'id' => 'starter', 'name' => 'Starter', 'hint' => 'Up to 5' ], [ 'id' => 'growth', 'name' => 'Growth' ], [ 'id' => 'team', 'name' => 'Team', 'disabled' => true ] ]";

test('renders the radio group markup from 2.x options', function () use ($legacyOptions): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-radio id="plan" label="Plan" hint="Change any time." wire:model="plan" :options="'.$legacyOptions.'" />'));
    $root = $dom->query('//fieldset')->item(0);

    $values = [];
    foreach ($dom->query('//input') as $input) {
        $values[] = $input->getAttribute('value');
        expect($input->getAttribute('type'))->toBe('radio')
            ->and($input->getAttribute('name'))->toBe('plan')
            ->and($input->getAttribute('wire:model'))->toBe('plan');
    }

    expect($root->getAttribute('class'))->toBe('apui-radio-group')
        ->and($root->getAttribute('id'))->toBe('plan')
        ->and($root->getAttribute('data-orientation'))->toBe('vertical')
        ->and($root->getAttribute('data-variant'))->toBe('default')
        ->and(trim($dom->query('//legend')->item(0)->textContent))->toBe('Plan')
        ->and(trim($dom->query('//*[@data-part="hint"]')->item(0)->textContent))->toBe('Change any time.')
        ->and($values)->toBe(['starter', 'growth', 'team'])
        ->and(trim($dom->query('//*[@data-part="item-text"]')->item(1)->textContent))->toBe('Growth')
        ->and(trim($dom->query('//*[@data-part="item-description"]')->item(0)->textContent))->toBe('Up to 5')
        ->and($dom->query('//input[@value="team"]')->item(0)->hasAttribute('disabled'))->toBeTrue();
});

test('reads custom option keys through option-value, option-label and option-hint', function (): void {
    $options = "[ [ 'key' => 'a', 'title' => 'Alpha', 'note' => 'First' ], (object) [ 'key' => 'b', 'title' => 'Beta' ] ]";
    $dom     = designSystemDom(Blade::render('<x-artisanpack-radio option-value="key" option-label="title" option-hint="note" :options="'.$options.'" />'));

    expect($dom->query('//input')->item(0)->getAttribute('value'))->toBe('a')
        ->and($dom->query('//input')->item(1)->getAttribute('value'))->toBe('b')
        ->and(trim($dom->query('//*[@data-part="item-text"]')->item(0)->textContent))->toBe('Alpha')
        ->and(trim($dom->query('//*[@data-part="item-description"]')->item(0)->textContent))->toBe('First');
});

test('takes 3.0-shaped options and plain strings too', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio :options="[ [ \'value\' => \'x\', \'label\' => \'Ex\' ], \'y\' ]" />'));

    expect($dom->query('//input')->item(0)->getAttribute('value'))->toBe('x')
        ->and(trim($dom->query('//*[@data-part="item-text"]')->item(0)->textContent))->toBe('Ex')
        ->and($dom->query('//input')->item(1)->getAttribute('value'))->toBe('y');
});

test('inline is a horizontal orientation and card is the cards variant', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-radio inline card :options="[ \'A\', \'B\' ]" />'))->query('//fieldset')->item(0);

    expect($root->getAttribute('data-orientation'))->toBe('horizontal')
        ->and($root->getAttribute('data-variant'))->toBe('cards');
});

test('hint-class, error-class and card-class become part classes', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio hint="H" error="Pick one." hint-class="my-hint" error-class="my-error" card-class="my-card" :options="[ \'A\' ]" />'));

    expect($dom->query('//*[@data-part="hint"]')->item(0)->getAttribute('class'))->toBe('my-hint')
        ->and($dom->query('//*[@data-part="error"]')->item(0)->getAttribute('class'))->toBe('my-error')
        ->and($dom->query('//*[@data-part="item"]')->item(0)->getAttribute('class'))->toBe('my-card');
});

test('removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-radio label="Plan" hint-class="h" error-class="e" card-class="c" option-value="id" option-label="name" option-hint="hint" inline card omit-error first-error-only error-field="plan" :options="[ \'A\' ]" />');

    foreach (['hint-class', 'error-class', 'card-class', 'option-value', 'option-label', 'option-hint', 'inline', 'card=', 'omit-error', 'first-error-only', 'error-field', 'label='] as $attribute) {
        expect($html)->not->toContain($attribute);
    }
});
