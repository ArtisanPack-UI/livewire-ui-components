<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Rating;
use Illuminate\Support\Facades\Blade;

/*
 * Rating (#53): read-only (role=img) and input (native radios) markup,
 * half stars and the translatable labels. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('read-only is one image with the value label, the value and the count', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-rating readonly :value="4.2" :count="17" />'));
    $root = $dom->query('//div[contains(@class, "apui-rating")]')->item(0);

    expect($root->getAttribute('role'))->toBe('img')
        ->and($root->getAttribute('aria-label'))->toBe('4.2 out of 5')
        ->and($root->hasAttribute('data-readonly'))->toBeTrue()
        ->and($dom->query('//input')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="star"][@data-filled]')->length)->toBe(4)
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('4.2')
        ->and($dom->query('//*[@data-part="count"]')->item(0)->textContent)->toBe('(17 reviews)');
});

test('read-only rounds to the nearest half star', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-rating readonly :value="3.4" />'));

    expect($dom->query('//*[@data-part="star"][@data-filled]')->length)->toBe(3)
        ->and($dom->query('//*[@data-part="star"][@data-half]')->length)->toBe(1);
});

test('the count is singular for one review', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-rating readonly :value="5" :count="1" />'));

    expect($dom->query('//*[@data-part="count"]')->item(0)->textContent)->toBe('(1 review)');
});

test('the input is a radiogroup of native radios that share a name', function (): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-rating wire:model="score" :value="3" label="Rate the viewing" />'));
    $root   = $dom->query('//div[contains(@class, "apui-rating")]')->item(0);
    $inputs = $dom->query('//input');

    expect($root->getAttribute('role'))->toBe('radiogroup')
        ->and($root->getAttribute('aria-label'))->toBe('Rate the viewing')
        ->and($inputs->length)->toBe(5)
        ->and($inputs->item(0)->getAttribute('aria-label'))->toBe('1 out of 5')
        ->and($inputs->item(0)->getAttribute('wire:model'))->toBe('score')
        ->and($inputs->item(2)->hasAttribute('checked'))->toBeTrue()
        ->and($inputs->item(0)->getAttribute('name'))->toBe($inputs->item(4)->getAttribute('name'));
});

test('labels are templates with :value, :max and :count', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-rating :max="10" :count="3" value-label="Note :value sur :max" count-label=":count avis" />'));

    expect($dom->query('//input')->length)->toBe(10)
        ->and($dom->query('//input')->item(1)->getAttribute('aria-label'))->toBe('Note 2 sur 10')
        ->and($dom->query('//*[@data-part="count"]')->item(0)->textContent)->toBe('3 avis');
});

test('the input falls back to the translated Rating name', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-rating />'))->query('//div[contains(@class, "apui-rating")]')->item(0);

    expect($root->getAttribute('aria-label'))->toBe('Rating')->and($root->getAttribute('data-size'))->toBe('md');
});

test('disabled disables every radio', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-rating disabled />'));

    expect($dom->query('//input[@disabled]')->length)->toBe(5)
        ->and($dom->query('//div[@data-disabled]')->length)->toBe(1);
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Rating::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['value', 'max', 'readonly', 'disabled', 'size', 'count', 'label', 'valueLabel', 'countLabel', 'classes']);
});
