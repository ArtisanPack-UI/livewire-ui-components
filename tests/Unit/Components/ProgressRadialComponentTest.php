<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * ProgressRadial (#60): the root is the progressbar. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function progressRadialRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//span[contains(@class, "apui-progress-radial")]')->item(0);
}

test('the ring, value and label render with the spec geometry', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress-radial id="goal" :value="68" :size="80" :thickness="7" label="complete" />'));
    $root = progressRadialRoot($dom);
    $bar  = $dom->query('//*[@data-part="bar"]')->item(0);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="progress-radial"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'svg', 'track', 'bar', 'value', 'label'])
        ->and($root->getAttribute('role'))->toBe('progressbar')
        ->and($root->getAttribute('aria-valuenow'))->toBe('68')
        ->and($root->getAttribute('aria-valuemax'))->toBe('100')
        ->and($root->getAttribute('aria-labelledby'))->toBe('goal-label')
        ->and($root->getAttribute('style'))->toBe('--apui-_s: 80px')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($dom->query('//*[@data-part="svg"]')->item(0)->getAttribute('viewbox'))->toBe('0 0 80 80')
        ->and($bar->getAttribute('cx'))->toBe('40.0')
        ->and($bar->getAttribute('r'))->toBe('36.5')
        ->and($bar->getAttribute('stroke-width'))->toBe('7')
        ->and($bar->getAttribute('stroke-dasharray'))->toBe('229.3')
        ->and($bar->getAttribute('stroke-dashoffset'))->toBe('73.4')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('68%complete');
});

test('indeterminate leaves out aria-valuenow and the value, with a quarter arc', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress-radial indeterminate :size="48" :thickness="5" aria-label="Calculating" />'));
    $root = progressRadialRoot($dom);

    expect($root->hasAttribute('data-indeterminate'))->toBeTrue()
        ->and($root->hasAttribute('aria-valuenow'))->toBeFalse()
        ->and($root->getAttribute('aria-label'))->toBe('Calculating')
        ->and($dom->query('//*[@data-part="value"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="bar"]')->item(0)->getAttribute('stroke-dashoffset'))->toBe('101.3');
});

test('hideValue empties the center and names the bar with aria-label', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-progress-radial :value="40" hide-value label="Profile complete" />'));
    $root = progressRadialRoot($dom);

    expect($dom->query('//*[@data-part="value"]'))->toHaveCount(0)
        ->and($root->getAttribute('aria-label'))->toBe('Profile complete')
        ->and($root->hasAttribute('aria-labelledby'))->toBeFalse();
});

test('valueText replaces the percentage and is the value text', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-progress-radial :value="3" :max="4" value-text="3 of 4" color="success" />'));

    expect(progressRadialRoot($dom)->getAttribute('aria-valuetext'))->toBe('3 of 4')
        ->and(progressRadialRoot($dom)->getAttribute('aria-valuemax'))->toBe('4')
        ->and(progressRadialRoot($dom)->getAttribute('data-color'))->toBe('success')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('3 of 4');
});

test('an author id lands on the root unchanged', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-progress-radial id="goal" :value="40" />'));

    expect($dom->query('//*[@data-part="root"][@id="goal"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="goal-root"]'))->toHaveCount(0);
});
