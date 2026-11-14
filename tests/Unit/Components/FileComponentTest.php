<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Js;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/*
 * File (#53): the native input over the drop zone, the file list, remove
 * buttons and Livewire upload support. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

$files = "[ [ 'name' => 'a.jpg', 'size' => 2400000 ], [ 'name' => 'b.jpg', 'size' => 1000, 'progress' => 64 ], [ 'name' => 'c.heic', 'error' => 'Not supported.' ] ]";

test('the native input covers the drop zone and takes wire:model', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-file id="f" wire:model="photos" label="Photos" hint="JPG only" multiple accept="image/*" :max-size="1000" />'));
    $input = $dom->query('//input')->item(0);

    expect($input->getAttribute('type'))->toBe('file')
        ->and($input->parentNode->getAttribute('data-part'))->toBe('dropzone')
        ->and($input->getAttribute('wire:model'))->toBe('photos')
        ->and($input->hasAttribute('multiple'))->toBeTrue()
        ->and($input->getAttribute('accept'))->toBe('image/*')
        ->and($input->getAttribute('aria-describedby'))->toBe('f-hint')
        ->and($dom->query('//label[@for="f"]')->item(0)->textContent)->toBe('Photos')
        ->and($dom->query('//*[@data-part="title"]')->item(0)->textContent)->toBe('Drop files here or browse')
        ->and($dom->query('//div[contains(@class, "apui-file")]')->item(0)->getAttribute('data-max-size'))->toBe('1000');
});

test('without a label the title names the input', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-file id="f" />'));

    expect($dom->query('//input')->item(0)->getAttribute('aria-labelledby'))->toBe('f-title');
});

test('the list shows sizes, progress and errors, each with a labelled remove button', function () use ($files): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-file :value="'.$files.'" />'));
    $items = $dom->query('//li[@data-part="item"][not(@hidden)]');

    expect($items->length)->toBe(3)
        ->and($dom->query('.//*[@data-part="meta"]', $items->item(0))->item(0)->textContent)->toBe('2.4 MB')
        ->and($dom->query('.//progress', $items->item(1))->item(0)->getAttribute('value'))->toBe('64')
        ->and($items->item(2)->getAttribute('data-state'))->toBe('error')
        ->and(trim($dom->query('.//*[@data-part="meta"]', $items->item(2))->item(0)->textContent))->toBe('Not supported.')
        ->and($dom->query('.//button', $items->item(0))->item(0)->getAttribute('aria-label'))->toBe('Remove a.jpg')
        ->and($dom->query('.//button', $items->item(0))->item(0)->getAttribute('x-on:click'))->toBe("\$dispatch('remove', { index: 0, name: 'a.jpg' })");
});

test('temporary uploads are removed through Livewire', function (): void {
    $upload = Mockery::mock(TemporaryUploadedFile::class);
    $upload->shouldReceive('getClientOriginalName')->andReturn('lease.pdf');
    $upload->shouldReceive('getSize')->andReturn(240000);
    $upload->shouldReceive('getFilename')->andReturn('tmp-abc.pdf');

    $dom    = designSystemDom(Blade::render('<x-artisanpack-file wire:model="contract" :value="$upload" />', ['upload' => $upload]));
    $button = $dom->query('//li[not(@hidden)]//button')->item(0);

    expect($dom->query('//li[not(@hidden)]//*[@data-part="name"]')->item(0)->textContent)->toBe('lease.pdf')
        ->and($button->getAttribute('x-on:click'))->toBe('$wire.$removeUpload('.Js::from('contract').', '.Js::from('tmp-abc.pdf').')');
});

test('an empty list stays hidden until an upload starts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-file wire:model="photos" />'));
    $ul  = $dom->query('//ul')->item(0);

    expect($ul->hasAttribute('hidden'))->toBeTrue()
        ->and($ul->getAttribute('x-bind:hidden'))->toBe('! uploading')
        ->and($dom->query('//div[contains(@class, "apui-file")]')->item(0)->getAttribute('x-on:livewire-upload-progress'))->toBe('progress = $event.detail.progress');
});

test('remove labels are templates with :name', function () use ($files): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-file remove-label="Quitar :name" :value="'.$files.'" />'));

    expect($dom->query('//button')->item(0)->getAttribute('aria-label'))->toBe('Quitar a.jpg');
});

test('variant, required and invalid render on the root and the input', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-file variant="compact" required invalid label="Doc" />'));

    expect($dom->query('//div[contains(@class, "apui-file")]')->item(0)->getAttribute('data-variant'))->toBe('compact')
        ->and($dom->query('//div[@data-invalid]')->length)->toBe(1)
        ->and($dom->query('//input')->item(0)->hasAttribute('required'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->getAttribute('aria-invalid'))->toBe('true');
});

test('sizes use decimal units', function (int $bytes, string $text): void {
    expect((new File)->formatSize($bytes))->toBe($text);
})->with([[999, '999 B'], [1000, '1 KB'], [2400000, '2.4 MB'], [1250000, '1.3 MB']]);

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(File::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['label', 'title', 'hint', 'error', 'invalid', 'disabled', 'required', 'accept', 'multiple', 'maxSize', 'variant', 'value', 'removeLabel', 'errorField', 'classes']);
});
