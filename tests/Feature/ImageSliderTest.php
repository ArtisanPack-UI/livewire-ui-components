<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * ImageSlider (#61) inside a Livewire component: wire:model entangles the
 * slide shown, and the server render starts on the bound slide.
 */

class ImageSliderLivewireTestComponent extends Component
{
    public int $photo = 0;

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-image-slider wire:model.live="photo" thumbnails :images="[
                    [ 'src' => '/photos/a.jpg', 'alt' => 'Lobby' ],
                    [ 'src' => '/photos/b.jpg', 'alt' => 'Roof' ],
                ]" />
            </div>
            BLADE;
    }
}

test('wire:model entangles the slide shown and stays off the root', function (): void {
    $dom  = designSystemDom(Livewire::test(ImageSliderLivewireTestComponent::class)->html());
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('x-data'))->toContain("index: \$wire.entangle('photo').live")
        ->and($root->hasAttribute('wire:model.live'))->toBeFalse();
});

test('the server render starts on the bound slide', function (): void {
    $dom = designSystemDom(Livewire::test(ImageSliderLivewireTestComponent::class)->set('photo', 1)->html());

    expect(trim($dom->query('//span[@data-part="progress-text"]')->item(0)->textContent))->toBe('2 / 2')
        ->and($dom->query('//button[@data-part="thumb"]')->item(1)->getAttribute('aria-current'))->toBe('true')
        ->and($dom->query('//div[@data-part="item"]')->item(0)->getAttribute('aria-hidden'))->toBe('true');
});
