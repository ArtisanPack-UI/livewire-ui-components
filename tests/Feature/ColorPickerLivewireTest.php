<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * ColorPicker (#53) inside a Livewire component: the server render reads the
 * wire:model property (text value, swatch fill, thumbs), and wire:model
 * entangles it.
 */

class ColorPickerLivewireTestComponent extends Component
{
    public string $brand = '#ff0000';

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-color-picker wire:model="brand" open :portal="false" :swatches="[ '#ff0000', '#0b9488' ]" :label="__( 'Brand' )" />
            </div>
            BLADE;
    }
}

test('the server render shows the bound property', function (): void {
    $dom = designSystemDom(Livewire::test(ColorPickerLivewireTestComponent::class)->html());

    expect($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('value'))->toBe('#ff0000')
        ->and($dom->query('//*[@data-part="channel-slider-thumb"][@data-channel="hue"]')->item(0)->getAttribute('aria-valuenow'))->toBe('0')
        ->and($dom->query('//button[@data-part="swatch-trigger"][@aria-pressed="true"]')->item(0)->getAttribute('aria-label'))->toBe('#ff0000')
        ->and($dom->query('//div[contains(@class, "apui-color-picker")]')->item(0)->getAttribute('x-data'))->toContain("\$wire.entangle('brand')");
});

test('a changed property re-renders the value', function (): void {
    $html = Livewire::test(ColorPickerLivewireTestComponent::class)->set('brand', '#00ff00')->html();

    expect(designSystemDom($html)->query('//input[@data-part="control"]')->item(0)->getAttribute('value'))->toBe('#00ff00');
});
