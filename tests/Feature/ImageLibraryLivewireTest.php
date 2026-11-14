<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Traits\WithMediaSync;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithFileUploads;

/*
 * ImageLibrary (#61) with the WithMediaSync trait, as 2.x used it:
 * wire:model names the uploads, wire:library the collection, and the
 * library's actions call the trait. Alt text edits set library.<key>.alt.
 */

class ImageLibraryLivewireTestComponent extends Component
{
    use WithFileUploads;
    use WithMediaSync;

    public array $files = [];

    public Collection $library;

    public function mount(): void
    {
        $this->library = collect([
            ['uuid' => 'a-1', 'url' => '/storage/lobby.jpg', 'path' => 'photos/lobby.jpg'],
            ['uuid' => 'b-2', 'url' => '/storage/roof.jpg', 'path' => 'photos/roof.jpg', 'alt' => 'The roof'],
        ]);
    }

    public function failValidation(): void
    {
        $this->addError('library', 'Add at least three photos.');
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-image-library wire:model="files" wire:library="library" :preview="$library" droppable />
            </div>
            BLADE;
    }
}

test('binds the uploads and the library as WithMediaSync expects', function (): void {
    $dom  = designSystemDom(Livewire::test(ImageLibraryLivewireTestComponent::class)->html());
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->hasAttribute('wire:model'))->toBeFalse()
        ->and($root->hasAttribute('wire:library'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toContain("model: 'files', library: 'library'")
        ->and($dom->query('//input[@type="file"][@*[name()="wire:model"]="files.*"][@multiple]'))->toHaveCount(1)
        ->and($dom->query('//input[@type="file"][@*[name()="wire:model"]="files.1"]'))->toHaveCount(1)
        ->and($dom->query('//button[normalize-space()="Upload"]'))->toHaveCount(1)
        ->and($dom->query('//button[normalize-space()="Replace"]'))->toHaveCount(1)
        ->and($dom->query('//div[@role="option"]'))->toHaveCount(2)
        ->and($dom->query('//div[@role="option"][2]')->item(0)->getAttribute('data-alt'))->toBe('The roof');
});

test('an alt text edit sets library.<key>.alt, and the option shows it', function (): void {
    $component = Livewire::test(ImageLibraryLivewireTestComponent::class)->set('library.0.alt', 'The lobby at noon');

    expect($component->get('library')->get(0)['alt'])->toBe('The lobby at noon')
        ->and(designSystemDom($component->html())->query('//div[@role="option"][1]')->item(0)->getAttribute('data-alt'))->toBe('The lobby at noon');
});

test('removing calls the trait and the option goes', function (): void {
    $component = Livewire::test(ImageLibraryLivewireTestComponent::class)
        ->call('removeMedia', 'a-1', 'files', 'library', '/storage/lobby.jpg');

    expect(designSystemDom($component->html())->query('//div[@role="option"]'))->toHaveCount(1);
});

test('a library error shows in the error part', function (): void {
    $component = Livewire::test(ImageLibraryLivewireTestComponent::class)->call('failValidation');

    $error = designSystemDom($component->html())->query('//p[@data-part="error"]')->item(0);

    expect($error->hasAttribute('hidden'))->toBeFalse()
        ->and(trim($error->textContent))->toBe('Add at least three photos.');
});

class ImageLibraryWithIdsLivewireTestComponent extends Component
{
    use WithFileUploads;
    use WithMediaSync;

    public array $files = [];

    public Collection $library;

    public function mount(): void
    {
        $this->library = collect([
            ['id' => 7, 'uuid' => 'a-1', 'url' => '/storage/lobby.jpg', 'path' => 'photos/lobby.jpg'],
            ['id' => 8, 'uuid' => 'b-2', 'url' => '/storage/roof.jpg', 'path' => 'photos/roof.jpg'],
        ]);
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-image-library wire:model="files" wire:library="library" :preview="$library" />
            </div>
            BLADE;
    }
}

test('with wire:library, options carry the uuid WithMediaSync matches on, even when items have an id', function (): void {
    $component = Livewire::test(ImageLibraryWithIdsLivewireTestComponent::class);
    $ids       = array_map(static fn (DOMElement $option): string => $option->getAttribute('data-id'), iterator_to_array(designSystemDom($component->html())->query('//div[@role="option"]')));

    expect($ids)->toBe(['a-1', 'b-2']);

    $component->call('refreshMediaOrder', array_reverse($ids), 'library')
        ->call('removeMedia', $ids[0], 'files', 'library', '/storage/lobby.jpg');

    expect($component->get('library')->pluck('uuid')->values()->all())->toBe(['b-2']);
});
