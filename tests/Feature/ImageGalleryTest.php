<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * ImageGallery (#61) inside a Livewire component: wire:model entangles the
 * lightbox's open state, and the server render opens it from the property.
 */

class ImageGalleryLivewireTestComponent extends Component
{
    public bool $showPhotos = false;

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-image-gallery wire:model="showPhotos" :index="1" :portal="false" :images="[
                    [ 'src' => '/photos/a.jpg', 'alt' => 'Lobby', 'caption' => 'The lobby' ],
                    [ 'src' => '/photos/b.jpg', 'alt' => 'Roof', 'caption' => 'The roof' ],
                ]" />
            </div>
            BLADE;
    }
}

test('wire:model entangles the open state and stays off the root', function (): void {
    $dom  = designSystemDom(Livewire::test(ImageGalleryLivewireTestComponent::class)->html());
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('x-data'))->toContain("\$wire.entangle('showPhotos')")
        ->and($root->hasAttribute('wire:model'))->toBeFalse()
        ->and($dom->query('//div[@data-part="content"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('the server render opens the lightbox from the bound property', function (): void {
    $dom = designSystemDom(Livewire::test(ImageGalleryLivewireTestComponent::class)->set('showPhotos', true)->html());

    expect($dom->query('//div[@data-part="content"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//div[@data-part="backdrop"]')->item(0)->getAttribute('data-state'))->toBe('open')
        ->and($dom->query('//div[@data-part="stage"]/img')->item(0)->getAttribute('alt'))->toBe('Roof')
        ->and(trim($dom->query('//h2[@data-part="title"]')->item(0)->textContent))->toBe('2 / 2 · The roof');
});
