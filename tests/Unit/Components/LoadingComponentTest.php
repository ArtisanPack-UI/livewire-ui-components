<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Loading (#60). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function loadingRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//span[contains(@class, "apui-loading")]')->item(0);
}

test('the default renders a status with a spinner and a hidden label', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-loading />'));
    $root  = loadingRoot($dom);
    $label = $dom->query('//*[@data-part="label"]')->item(0);

    expect($root->getAttribute('role'))->toBe('status')
        ->and($root->getAttribute('data-variant'))->toBe('spinner')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($root->getAttribute('style'))->toBe('--apui-_delay: 300ms')
        ->and($root->hasAttribute('data-overlay'))->toBeFalse()
        ->and($dom->query('//*[@data-part="indicator"][@aria-hidden="true"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="indicator"]/span'))->toHaveCount(0)
        ->and($label->getAttribute('class'))->toBe('apui-sr-only')
        ->and($label->textContent)->toBe('Loading');
});

test('dots render three spans', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-loading variant="dots" />'))->query('//*[@data-part="indicator"]/span'))->toHaveCount(3);
});

test('the default slot is visible text', function (): void {
    $label = designSystemDom(Blade::render('<x-artisanpack-loading>Saving changes…</x-artisanpack-loading>'))->query('//*[@data-part="label"]')->item(0);

    expect($label->hasAttribute('class'))->toBeFalse()
        ->and($label->textContent)->toBe('Saving changes…');
});

test('the label is translated by default and can be set', function (): void {
    app('translator')->addLines(['*.Loading' => 'Chargement'], 'fr');
    app()->setLocale('fr');
    expect(designSystemDom(Blade::render('<x-artisanpack-loading />'))->query('//*[@data-part="label"]')->item(0)->textContent)->toBe('Chargement');
    app()->setLocale('en');

    expect(designSystemDom(Blade::render('<x-artisanpack-loading label="Fetching" />'))->query('//*[@data-part="label"]')->item(0)->textContent)->toBe('Fetching');
});

test('overlay, delay, size, color and attributes render on the root', function (): void {
    $root = loadingRoot(designSystemDom(Blade::render('<x-artisanpack-loading overlay :delay="0" size="lg" color="success" class="extra" style="color: red" wire:loading />')));

    expect($root->hasAttribute('data-overlay'))->toBeTrue()
        ->and($root->getAttribute('style'))->toBe('--apui-_delay: 0ms; color: red')
        ->and($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->getAttribute('data-color'))->toBe('success')
        ->and($root->getAttribute('class'))->toBe('apui-loading extra')
        ->and($root->hasAttribute('wire:loading'))->toBeTrue();
});
