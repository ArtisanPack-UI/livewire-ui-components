<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ImageLibrary;
use Illuminate\Support\Facades\Blade;

/*
 * ImageLibrary (#61), Livewire only (D-003): the design-system markup
 * contract. The WithMediaSync integration is in
 * tests/Feature/ImageLibraryLivewireTest.php; the manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function libraryAssets(): array
{
    return [
        ['id' => '1', 'src' => '/media/lobby.jpg', 'name' => 'lobby.jpg', 'alt' => 'The lobby', 'size' => '2.4 MB', 'width' => 4032, 'height' => 3024],
        ['id' => '2', 'src' => '/media/roof.jpg', 'name' => 'roof.jpg', 'size' => '1.8 MB'],
        ['id' => '3', 'name' => 'floor-plan.pdf', 'size' => '640 KB', 'type' => 'pdf'],
    ];
}

function libraryDom(string $props = '', ?array $assets = null): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-image-library :assets="$assets" '.$props.' />',
        ['assets' => $assets ?? libraryAssets()],
    ));
}

test('renders the library with a toolbar and a listbox of assets', function (): void {
    $dom  = libraryDom();
    $root = $dom->query('//div[@data-part="root"]')->item(0);
    $grid = $dom->query('//div[@data-part="grid"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-image-library')
        ->and($root->getAttribute('data-view'))->toBe('grid')
        ->and($root->getAttribute('x-data'))->toStartWith('apuiImageLibrary(')
        ->and($grid->getAttribute('role'))->toBe('listbox')
        ->and($grid->getAttribute('aria-multiselectable'))->toBe('true')
        ->and($grid->getAttribute('aria-label'))->toBe('Media library')
        ->and($dom->query('//div[@data-part="grid"]/div[@role="option"][@data-part="asset"]'))->toHaveCount(3)
        ->and($dom->query('//div[@data-part="toolbar"]//input[@type="search"][@aria-label="Search files"]'))->toHaveCount(1)
        ->and($dom->query('//div[@data-part="view"]//button[@aria-pressed="true"]'))->toHaveCount(1);
});

test('one option is in the Tab order, and each carries its data', function (): void {
    $options = libraryDom()->query('//div[@role="option"]');

    expect($options->item(0)->getAttribute('tabindex'))->toBe('0')
        ->and($options->item(1)->getAttribute('tabindex'))->toBe('-1')
        ->and($options->item(0)->getAttribute('aria-selected'))->toBe('false')
        ->and($options->item(0)->getAttribute('data-alt'))->toBe('The lobby')
        ->and($options->item(0)->getAttribute('data-dimensions'))->toBe('4032 × 3024')
        ->and($options->item(2)->getAttribute('data-image'))->toBe('false');
});

test('images show thumbnails, other files an icon and their type', function (): void {
    $dom = libraryDom();

    expect($dom->query('//div[@role="option"][1]//img')->item(0)->getAttribute('src'))->toBe('/media/lobby.jpg')
        ->and(trim($dom->query('//div[@role="option"][3]//span[@data-part="type"]')->item(0)->textContent))->toBe('PDF')
        ->and($dom->query('//span[@data-part="check"][@aria-hidden="true"]'))->toHaveCount(3)
        ->and(trim($dom->query('//span[@data-part="name"]')->item(0)->textContent))->toBe('lobby.jpg')
        ->and(trim($dom->query('//span[@data-part="meta"]')->item(0)->textContent))->toBe('2.4 MB · 4032 × 3024');
});

test('the details panel, bulk bar and empty state start hidden', function (): void {
    $dom = libraryDom();

    expect($dom->query('//aside[@data-part="details"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//aside[@data-part="details"]')->item(0)->getAttribute('aria-label'))->toBe('Details')
        ->and($dom->query('//div[@data-part="bulk"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//div[@data-part="empty"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@role="status"]'))->toHaveCount(1)
        ->and($dom->query('//aside//label[normalize-space()="Alt text"]'))->toHaveCount(1);
});

test('without wire:model the library is read-only', function (): void {
    $dom = libraryDom();

    expect($dom->query('//input[@type="file"]'))->toHaveCount(0)
        ->and($dom->query('//button[normalize-space()="Upload"]'))->toHaveCount(0)
        ->and($dom->query('//button[normalize-space()="Remove"]'))->toHaveCount(0)
        ->and($dom->query('//button[normalize-space()="Insert"]'))->toHaveCount(2);
});

test('an empty library says so, and single selection is not multiselectable', function (): void {
    $dom = libraryDom('selectable="single" view="list"', []);

    expect($dom->query('//div[@data-part="empty"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and(trim($dom->query('//div[@data-part="empty"]')->item(0)->textContent))->toBe('No files yet.')
        ->and($dom->query('//div[@data-part="grid"]')->item(0)->getAttribute('aria-multiselectable'))->toBe('false')
        ->and($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('data-view'))->toBe('list')
        ->and($dom->query('//div[@data-part="bulk"]//input[@type="checkbox"]'))->toHaveCount(0);
});

test('droppable, hint and the 2.x drag-and-drop props render the dropzone', function (): void {
    $dom  = libraryDom('with-drag-drop drag-drop-text="Drop it" drag-drop-class="my-drop" hint="PNG or JPEG"');
    $zone = $dom->query('//div[@data-part="dropzone"]')->item(0);

    expect(trim($zone->textContent))->toBe('Drop it')
        ->and($zone->getAttribute('class'))->toBe('my-drop')
        ->and($zone->hasAttribute('hidden'))->toBeTrue()
        ->and(trim($dom->query('//p[@data-part="hint"]')->item(0)->textContent))->toBe('PNG or JPEG');
});

test('takes a WithMediaSync preview collection as assets', function (): void {
    $html = Blade::render('<x-artisanpack-image-library :preview="$preview" />', ['preview' => collect([
        ['uuid' => 'a-1', 'url' => '/storage/a.jpg?updated_at=1', 'path' => 'photos/a.jpg'],
    ])]);
    $option = designSystemDom($html)->query('//div[@role="option"]')->item(0);

    expect($option->getAttribute('data-id'))->toBe('a-1')
        ->and($option->getAttribute('data-key'))->toBe('0')
        ->and($option->getAttribute('data-name'))->toBe('a.jpg')
        ->and($option->getAttribute('data-image'))->toBe('true');
});

test('the class normalizes its props', function (): void {
    $library = new ImageLibrary(view: 'tiles', selectable: 'many', withDragDrop: true, dragDropClass: 'x');

    expect($library->view)->toBe('grid')
        ->and($library->selectable)->toBe('multiple')
        ->and($library->droppable)->toBeTrue()
        ->and($library->classes['dropzone'])->toBe('x');
});

test('drops asset sources with a script scheme and shows them as files', function (): void {
    $entries = (new ImageLibrary(assets: [
        ['id' => '1', 'src' => '/media/lobby.jpg', 'name' => 'lobby.jpg'],
        ['id' => '2', 'src' => 'javascript:alert(1)', 'name' => 'bad.jpg'],
    ]))->entries();

    expect($entries[0])->toMatchArray(['src' => '/media/lobby.jpg', 'image' => true])
        ->and($entries[1])->toMatchArray(['src' => '', 'image' => false, 'name' => 'bad.jpg']);
});

test('the select-all box writes its mixed state to data-state, where the CSS reads it', function (): void {
    $effect = libraryDom()->query('//div[@data-part="bulk"]//input[@type="checkbox"]')->item(0)->getAttribute('x-effect');

    expect($effect)->toContain("allSelected ? 'checked' : ( someSelected ? 'indeterminate' : 'unchecked' )")
        ->and($effect)->toContain("\$el.indeterminate = 'indeterminate' === state")
        ->and($effect)->toContain("\$el.closest('.apui-checkbox').dataset.state = state");
});

test('without wire:library, an item id wins over its uuid', function (): void {
    $option = libraryDom('', [['id' => '7', 'uuid' => 'a-1', 'src' => '/media/lobby.jpg']])->query('//div[@role="option"]')->item(0);

    expect($option->getAttribute('data-id'))->toBe('7');
});
