<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Range;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Range (#53): native range inputs over the drawn track, the fill position,
 * the value format, marks, the dual mode and its labels. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-range id="radius" label="Search radius" :value="20" :max="50" format="{value} km" show-value="always" hint="Listings within this distance." error="Pick at least 5 km." :marks="[ [ \'value\' => 0, \'label\' => \'0 km\' ], [ \'value\' => 50, \'label\' => \'50 km\' ] ]" />',
    );
    $dom = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="range"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $root    = $dom->query('//div[contains(@class, "apui-range")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['label', 'track', 'fill', 'control', 'thumb', 'bubble', 'marks', 'mark', 'mark', 'hint', 'error'])
        ->and($root->getAttribute('style'))->toBe('--apui-_from: 0%; --apui-_to: 40%')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-show-value'))->toBe('always')
        ->and($control->getAttribute('type'))->toBe('range')
        ->and($control->getAttribute('id'))->toBe('radius')
        ->and($control->getAttribute('value'))->toBe('20')
        ->and($control->getAttribute('max'))->toBe('50')
        ->and($control->getAttribute('aria-valuetext'))->toBe('20 km')
        ->and($control->getAttribute('aria-describedby'))->toBe('radius-hint radius-error')
        ->and($dom->query('//*[@data-part="bubble"]')->item(0)->textContent)->toBe('20 km')
        ->and($dom->query('//*[@data-part="mark"]')->item(1)->getAttribute('style'))->toBe('--apui-_at: 100%');
});

test('without a value the native input sits at the midpoint and has no value attribute', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-range label="A" />'));

    expect($dom->query('//input')->item(0)->hasAttribute('value'))->toBeFalse()
        ->and($dom->query('//div')->item(0)->getAttribute('style'))->toBe('--apui-_from: 0%; --apui-_to: 50%');
});

test('showValue never drops the bubble and format is optional', function (): void {
    $html = Blade::render('<x-artisanpack-range label="A" :value="5" show-value="never" />');

    expect($html)->not->toContain('data-part="bubble"')
        ->and($html)->not->toContain('aria-valuetext');
});

test('one thumb: wire:model lands on the native input', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-range class="mt-2" wire:model.live="radius" name="radius" label="Radius" />'));

    expect($dom->query('//div')->item(0)->getAttribute('class'))->toBe('apui-range mt-2')
        ->and($dom->query('//div')->item(0)->hasAttribute('x-modelable'))->toBeFalse()
        ->and($dom->query('//input')->item(0)->getAttribute('wire:model.live'))->toBe('radius')
        ->and($dom->query('//input')->item(0)->getAttribute('name'))->toBe('radius');
});

test('dual renders two named thumbs and binds the array on the root', function (): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-range id="price" wire:model.live="price" label="Price" dual :min="100" :max="700" :value="[ 200, 450 ]" />'));
    $root   = $dom->query('//div')->item(0);
    $inputs = $dom->query('//input');

    expect($root->hasAttribute('data-dual'))->toBeTrue()
        ->and($root->getAttribute('x-modelable'))->toBe('value')
        ->and($root->getAttribute('wire:model.live'))->toBe('price')
        ->and($root->getAttribute('style'))->toBe('--apui-_from: 16.6667%; --apui-_to: 58.3333%')
        ->and($inputs->length)->toBe(2)
        ->and($inputs->item(0)->getAttribute('data-thumb'))->toBe('start')
        ->and($inputs->item(0)->getAttribute('value'))->toBe('200')
        ->and($inputs->item(0)->getAttribute('aria-labelledby'))->toBe('price-label price-start-label')
        ->and($inputs->item(0)->hasAttribute('wire:model.live'))->toBeFalse()
        ->and($inputs->item(1)->getAttribute('id'))->toBe('price-end')
        ->and($inputs->item(1)->getAttribute('value'))->toBe('450')
        ->and($inputs->item(1)->getAttribute('aria-labelledby'))->toBe('price-label price-end-label')
        ->and($dom->query('//*[@id="price-start-label"]')->item(0)->textContent)->toBe('Minimum')
        ->and($dom->query('//*[@id="price-end-label"]')->item(0)->textContent)->toBe('Maximum');
});

test('dual defaults to [min, max] and orders a reversed value', function (): void {
    expect((new Range(dual: true))->bounds())->toBe([0, 100])
        ->and((new Range(dual: true, value: [80, 20]))->bounds())->toBe([20, 80]);
});

test('thumbLabels override the dual thumb names', function (): void {
    $html = Blade::render('<x-artisanpack-range label="Precio" dual :thumb-labels="[ \'start\' => \'Desde\', \'end\' => \'Hasta\' ]" />');

    expect($html)->toContain('>Desde</span>')->and($html)->toContain('>Hasta</span>');
});

test('percent clamps and survives an empty range', function (): void {
    expect((new Range)->percent(150))->toBe(100.0)
        ->and((new Range)->percent(-5))->toBe(0.0)
        ->and((new Range(min: 5, max: 5))->percent(5))->toBe(0.0);
});

test('disabled, color, size and the error from $errors render', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['radius' => 'Pick at least 5 km.'])));

    $dom  = designSystemDom(Blade::render('<x-artisanpack-range wire:model="radius" label="Radius" color="success" size="sm" disabled />'));
    $root = $dom->query('//div')->item(0);

    expect($root->getAttribute('data-color'))->toBe('success')
        ->and($root->getAttribute('data-size'))->toBe('sm')
        ->and($root->hasAttribute('data-disabled'))->toBeTrue()
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('Pick at least 5 km.');
});

test('dual thumbs post into one array and each is named once', function (): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-range id="price" name="price" aria-label="Price" aria-required="true" data-test="x" dual />'));
    $inputs = $dom->query('//input');
    $start  = $inputs->item(0);
    $end    = $inputs->item(1);

    expect($start->getAttribute('name'))->toBe('price[]')
        ->and($end->getAttribute('name'))->toBe('price[]')
        ->and($start->hasAttribute('aria-label'))->toBeFalse()
        ->and($end->hasAttribute('aria-label'))->toBeFalse()
        ->and($start->getAttribute('aria-required'))->toBe('true')
        ->and($end->hasAttribute('aria-required'))->toBeFalse()
        ->and($start->getAttribute('data-test'))->toBe('x')
        ->and($end->getAttribute('data-test'))->toBe('x')
        ->and($start->getAttribute('aria-labelledby'))->toBe('price-aria-label price-start-label')
        ->and($end->getAttribute('aria-labelledby'))->toBe('price-aria-label price-end-label')
        ->and($dom->query('//*[@id="price-aria-label"]')->item(0)->textContent)->toBe('Price');
});

test('a name already ending in [] is kept, and one thumb keeps name and aria-label', function (): void {
    $dual   = designSystemDom(Blade::render('<x-artisanpack-range name="price[]" dual label="Price" />'))->query('//input');
    $single = designSystemDom(Blade::render('<x-artisanpack-range name="radius" aria-label="Radius" />'))->query('//input')->item(0);

    expect($dual->item(0)->getAttribute('name'))->toBe('price[]')
        ->and($dual->item(0)->getAttribute('aria-labelledby'))->toContain('-label ')
        ->and($single->getAttribute('name'))->toBe('radius')
        ->and($single->getAttribute('aria-label'))->toBe('Radius');
});

test('Alpine rereads the inputs after a form reset', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-range label="A" />'))->query('//div')->item(0);

    expect($root->getAttribute('x-data'))->toContain("this.form?.addEventListener('reset', this.onReset)")
        ->and($root->getAttribute('x-data'))->toContain("this.form?.removeEventListener('reset', this.onReset)");
});
