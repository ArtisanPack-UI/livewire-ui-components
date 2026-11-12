<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Swap (#65): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

function swapDom(string $blade): DOMXPath
{
    return designSystemDom(Blade::render($blade));
}

test('renders a named checkbox over the on and off contents', function (): void {
    $dom     = swapDom('<x-artisanpack-swap label="Dark mode" />');
    $root    = $dom->query('//label[@data-part="root"]')->item(0);
    $control = $dom->query('//label/input[@data-part="control"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-swap')
        ->and($root->getAttribute('data-scope'))->toBe('swap')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-transition'))->toBe('fade')
        ->and($root->getAttribute('data-state'))->toBe('unchecked')
        ->and($control->getAttribute('type'))->toBe('checkbox')
        ->and($control->getAttribute('aria-label'))->toBe('Dark mode')
        ->and($control->hasAttribute('checked'))->toBeFalse()
        ->and($dom->query('//span[@data-part="on"][@aria-hidden="true"]/svg'))->toHaveCount(1)
        ->and($dom->query('//span[@data-part="off"][@aria-hidden="true"]/svg'))->toHaveCount(1);
});

test('checked and disabled reach the checkbox and the root', function (): void {
    $dom = swapDom('<x-artisanpack-swap label="Dark mode" checked disabled />');

    expect($dom->query('//label[@data-state="checked"][@data-disabled]'))->toHaveCount(1)
        ->and($dom->query('//input[@checked][@disabled]'))->toHaveCount(1);
});

test('wire:model and other attributes land on the checkbox; class on the root', function (): void {
    $dom = swapDom('<x-artisanpack-swap label="Dark mode" wire:model.live="dark" name="dark" class="ms-2" />');

    expect($dom->query('//label[@class="apui-swap ms-2"]'))->toHaveCount(1)
        ->and($dom->query('//input[@*[name()="wire:model.live"]="dark"][@name="dark"]'))->toHaveCount(1)
        ->and($dom->query('//label[@name]'))->toHaveCount(0);
});

test('size and transition fall back to md and fade', function (): void {
    $root = swapDom('<x-artisanpack-swap label="x" size="huge" transition="spin" />')->query('//label')->item(0);

    expect($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-transition'))->toBe('fade');

    $root = swapDom('<x-artisanpack-swap label="x" size="lg" transition="rotate" />')->query('//label')->item(0);

    expect($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->getAttribute('data-transition'))->toBe('rotate');
});

test('on and off slots replace the icons', function (): void {
    $dom = swapDom('<x-artisanpack-swap label="Menu"><x-slot:on><b>On</b></x-slot:on><x-slot:off><i>Off</i></x-slot:off></x-artisanpack-swap>');

    expect($dom->query('//span[@data-part="on"]/b'))->toHaveCount(1)
        ->and($dom->query('//span[@data-part="off"]/i'))->toHaveCount(1);
});

test('the 2.x true and false slots and icons still work', function (): void {
    $dom = swapDom('<x-artisanpack-swap label="Menu"><x-slot:true><b>On</b></x-slot:true><x-slot:false><i>Off</i></x-slot:false></x-artisanpack-swap>');

    expect($dom->query('//span[@data-part="on"]/b'))->toHaveCount(1)
        ->and($dom->query('//span[@data-part="off"]/i'))->toHaveCount(1);

    $legacy  = swapDom('<x-artisanpack-swap label="Theme" true-icon="o-sun" false-icon="x" />');
    $current = swapDom('<x-artisanpack-swap label="Theme" on-icon="sun" off-icon="x" />');

    expect($legacy->query('//span[@data-part="on"]/svg')->item(0)->C14N())
        ->toBe($current->query('//span[@data-part="on"]/svg')->item(0)->C14N())
        ->and($legacy->query('//span[@data-part="off"]/svg')->item(0)->C14N())
        ->toBe($current->query('//span[@data-part="off"]/svg')->item(0)->C14N());
});
