<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\CheckboxGroup;
use Illuminate\Support\Facades\Blade;

/*
 * CheckboxGroup (#53): a fieldset of pilot Checkbox markup, the array value,
 * select all and the layout attributes. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

$topics = "[ [ 'value' => 'offers', 'label' => 'Offers', 'description' => 'Hourly' ], [ 'value' => 'prices', 'label' => 'Prices' ], [ 'value' => 'news', 'label' => 'News', 'disabled' => true ] ]";

test('each option renders the Checkbox contract inside the items part', function () use ($topics): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox-group id="t" label="Notify me" :options="'.$topics.'" />'));

    expect($dom->query('//fieldset')->item(0)->getAttribute('class'))->toBe('apui-checkbox-group')
        ->and($dom->query('//legend[@data-part="label"]')->item(0)->textContent)->toBe('Notify me')
        ->and($dom->query('//*[@data-part="items"]/div[@class="apui-checkbox"]')->length)->toBe(3)
        ->and($dom->query('//input[@id="t-0"]')->item(0)->getAttribute('aria-describedby'))->toBe('t-0-description')
        ->and($dom->query('//label[@for="t-1"]')->item(0)->textContent)->toBe('Prices')
        ->and($dom->query('//input[@id="t-2"]')->item(0)->hasAttribute('disabled'))->toBeTrue();
});

test('wire:model and name land on every option, with the option value', function () use ($topics): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox-group wire:model="topics" class="mt-2" :options="'.$topics.'" />'));

    $values = [];
    foreach ($dom->query('//*[@data-part="items"]//input') as $input) {
        expect($input->getAttribute('wire:model'))->toBe('topics');
        $values[] = $input->getAttribute('value');
    }

    expect($values)->toBe(['offers', 'prices', 'news'])
        ->and($dom->query('//fieldset')->item(0)->getAttribute('class'))->toBe('apui-checkbox-group mt-2')
        ->and($dom->query('//fieldset')->item(0)->hasAttribute('wire:model'))->toBeFalse();
});

test('value checks the matching options and sets their data-state', function () use ($topics): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox-group :value="[\'prices\']" :options="'.$topics.'" />'));

    expect($dom->query('//input[@value="prices"]')->item(0)->hasAttribute('checked'))->toBeTrue()
        ->and($dom->query('//input[@value="offers"]')->item(0)->hasAttribute('checked'))->toBeFalse()
        ->and($dom->query('//div[@class="apui-checkbox"][@data-state="checked"]')->length)->toBe(1);
});

test('select all is indeterminate while some enabled options are checked', function (array $value, string $state) use ($topics): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox-group select-all :value="$value" :options="'.$topics.'" />', ['value' => $value]));
    $all = $dom->query('//*[@data-part="select-all"]/div')->item(0);

    expect($all->getAttribute('data-state'))->toBe($state)
        ->and($dom->query('//*[@data-part="select-all"]//label')->item(0)->textContent)->toBe('Select all');
})->with([
    'none'          => [[], 'unchecked'],
    'some'          => [['offers'], 'indeterminate'],
    'every enabled' => [['offers', 'prices'], 'checked'],
]);

test('Alpine toggles every enabled option with a change event each', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-checkbox-group select-all :options="[\'A\']" />'))->query('//fieldset')->item(0);

    expect($root->getAttribute('x-data'))->toContain("box.dispatchEvent(new Event('change', { bubbles: true }))")
        ->and($root->getAttribute('x-on:change'))->toBe("\$event.target.closest('[data-part=select-all]') ? toggleAll(\$event.target.checked) : sync()");
});

test('layout props are data attributes', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-checkbox-group variant="cards" orientation="horizontal" :columns="3" :options="[\'A\']" />'))->query('//fieldset')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('cards')
        ->and($root->getAttribute('data-orientation'))->toBe('horizontal')
        ->and($root->getAttribute('data-columns'))->toBe('3');
});

test('the group error is linked to the fieldset and marks every option invalid', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox-group id="g" error="Pick one." :options="[\'A\', \'B\']" />'));

    expect($dom->query('//fieldset')->item(0)->getAttribute('aria-describedby'))->toBe('g-error')
        ->and($dom->query('//div[@class="apui-checkbox"][@data-invalid]')->length)->toBe(2)
        ->and($dom->query('//input[@aria-invalid="true"]')->length)->toBe(2);
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(CheckboxGroup::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['options', 'value', 'label', 'hint', 'error', 'invalid', 'disabled', 'orientation', 'variant', 'columns', 'selectAll', 'selectAllLabel', 'errorField', 'classes']);
});
