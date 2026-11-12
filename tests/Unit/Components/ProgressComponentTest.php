<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Progress (#60): the root is the progressbar. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

function progressRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-progress")]')->item(0);
}

test('a labeled bar renders the progressbar, head and track', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress id="up" :value="64" label="Uploading 12 photos" value-text="8 of 12" />'));
    $root = progressRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="progress"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'head', 'label', 'value', 'track', 'bar'])
        ->and($root->getAttribute('role'))->toBe('progressbar')
        ->and($root->getAttribute('aria-valuemin'))->toBe('0')
        ->and($root->getAttribute('aria-valuemax'))->toBe('100')
        ->and($root->getAttribute('aria-valuenow'))->toBe('64')
        ->and($root->getAttribute('aria-valuetext'))->toBe('8 of 12')
        ->and($root->getAttribute('aria-labelledby'))->toBe('up-label')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($dom->query('//*[@id="up-label"]')->item(0)->textContent)->toBe('Uploading 12 photos')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('8 of 12')
        ->and($dom->query('//*[@data-part="bar"]')->item(0)->getAttribute('style'))->toBe('--apui-_v: 64%');
});

test('a labeled bar without valueText shows the percentage; an unlabeled one has no head', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-progress :value="3" :max="8" label="Steps" />'))->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('38%');

    $dom = designSystemDom(Blade::render('<x-artisanpack-progress :value="40" aria-label="Profile" />'));
    expect($dom->query('//*[@data-part="head"]'))->toHaveCount(0)
        ->and(progressRoot($dom)->getAttribute('aria-label'))->toBe('Profile')
        ->and(progressRoot($dom)->hasAttribute('aria-labelledby'))->toBeFalse()
        ->and(progressRoot($dom)->hasAttribute('aria-valuetext'))->toBeFalse();
});

test('the value is clamped', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-progress :value="150" />'));

    expect(progressRoot($dom)->getAttribute('aria-valuenow'))->toBe('100')
        ->and($dom->query('//*[@data-part="bar"]')->item(0)->getAttribute('style'))->toBe('--apui-_v: 100%');
});

test('indeterminate leaves out aria-valuenow and the width', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress indeterminate label="Preparing export" />'));
    $root = progressRoot($dom);

    expect($root->hasAttribute('data-indeterminate'))->toBeTrue()
        ->and($root->hasAttribute('aria-valuenow'))->toBeFalse()
        ->and($dom->query('//*[@data-part="value"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="bar"]')->item(0)->hasAttribute('style'))->toBeFalse();
});

test('segmented renders one bar per segment, the done ones marked', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-progress segmented :value="3" :max="5" />'));

    expect(progressRoot($dom)->hasAttribute('data-segmented'))->toBeTrue()
        ->and(progressRoot($dom)->getAttribute('aria-valuemax'))->toBe('5')
        ->and($dom->query('//*[@data-part="bar"]'))->toHaveCount(5)
        ->and($dom->query('//*[@data-part="bar"][@data-done]'))->toHaveCount(3);
});

test('size, color and classes render', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress size="lg" color="warning" class="w-full" :classes="[\'bar\' => \'b\']" />'));
    $root = progressRoot($dom);

    expect($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->getAttribute('data-color'))->toBe('warning')
        ->and($root->getAttribute('class'))->toBe('apui-progress w-full')
        ->and($dom->query('//*[@data-part="bar"]')->item(0)->getAttribute('class'))->toBe('b');
});

test('an author id lands on the root unchanged', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-progress id="upload" :value="40" />'));

    expect($dom->query('//*[@data-part="root"][@id="upload"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="upload-root"]'))->toHaveCount(0);
});
