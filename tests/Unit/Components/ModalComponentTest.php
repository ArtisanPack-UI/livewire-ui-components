<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Dialog;
use ArtisanPack\LivewireUiComponents\View\Components\Modal;
use Illuminate\Support\Facades\Blade;

/*
 * modal (#145) is a deprecated alias of dialog.
 */

function modalPanel(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-dialog")]')->item(0);
}

test('extends Dialog', function (): void {
    expect(is_subclass_of(Modal::class, Dialog::class))->toBeTrue();
});

test('renders the dialog from the 2.x props', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-modal id="confirm" title="Delete listing" subtitle="This can\'t be undone." box-class="max-w-lg" actions="Delete" glass="frosted" :portal="false">Are you sure?</x-artisanpack-modal>'));
    $panel = modalPanel($dom);

    expect($panel->getAttribute('id'))->toBe('confirm-content')
        ->and($panel->getAttribute('role'))->toBe('dialog')
        ->and($panel->getAttribute('data-surface'))->toBe('glass')
        ->and($panel->getAttribute('class'))->toContain('max-w-lg')
        ->and($dom->query('//h2[@data-part="title"]')->item(0)->textContent)->toBe('Delete listing')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('This can\'t be undone.')
        ->and($dom->query('//div[@data-part="body"]')->item(0)->textContent)->toBe('Are you sure?')
        ->and(trim($dom->query('//div[@data-part="footer"]')->item(0)->textContent))->toBe('Delete')
        ->and($dom->query('//*[@data-part="close-trigger"]'))->toHaveCount(1)
        ->and($dom->query('//dialog'))->toHaveCount(0);
});

test('the 3.0 description and footer override subtitle and actions', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-modal title="T" subtitle="Old" description="New" actions="Old" footer="New" :portal="false" />'));

    expect($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('New')
        ->and(trim($dom->query('//div[@data-part="footer"]')->item(0)->textContent))->toBe('New');
});

test('box-class joins the root classes', function (): void {
    $panel = modalPanel(designSystemDom(Blade::render('<x-artisanpack-modal title="T" box-class="w-11/12" :classes="[\'root\' => \'p-2\']" :portal="false" />')));

    expect($panel->getAttribute('class'))->toContain('apui-dialog')
        ->and($panel->getAttribute('class'))->toContain('p-2 w-11/12');
});

test('persistent renders an alertdialog without a close button', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-modal title="Session expired" persistent :portal="false" />'));

    expect(modalPanel($dom)->getAttribute('role'))->toBe('alertdialog')
        ->and($dom->query('//*[@data-part="close-trigger"]'))->toHaveCount(0)
        ->and($dom->query('//div[@x-data]')->item(0)->getAttribute('x-data'))->toContain("role: 'alertdialog'");
});

test('wire:model entangles the open state, as in 2.x', function (): void {
    $host = designSystemDom(Blade::render('<x-artisanpack-modal wire:model="showModal" title="Confirm" />'))
        ->query('//div[@x-data]')->item(0);

    expect($host->getAttribute('x-data'))->toContain("open: \$wire.entangle('showModal')")
        ->and($host->hasAttribute('wire:model'))->toBeFalse();
});

test('wire:model.live entangles live', function (): void {
    expect(Blade::render('<x-artisanpack-modal wire:model.live="showModal" title="Confirm" />'))
        ->toContain('$wire.entangle(&#039;showModal&#039;).live');
});

test('without wire:model, open is plain Alpine state', function (): void {
    expect(Blade::render('<x-artisanpack-modal title="Edit" open />'))->toContain('open: true');
});

test('the removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-modal title="T" subtitle="S" box-class="b" separator persistent without-trap-focus glass="frosted" glass-tint="#ff0000" :glass-tint-opacity="40" actions="A" :portal="false" />');

    foreach (['subtitle=', 'box-class', 'separator', 'persistent', 'without-trap-focus', 'glass=', 'glass-tint', 'actions='] as $attribute) {
        expect($html)->not->toContain(' '.$attribute);
    }
});

test('the 2.x actions and subtitle slots become the footer and description', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-modal title="Delete listing" :portal="false">
            Are you sure?
            <x-slot:subtitle>This <strong>can't</strong> be undone.</x-slot:subtitle>
            <x-slot:actions><x-artisanpack-button>Cancel</x-artisanpack-button><x-artisanpack-button color="error">Delete</x-artisanpack-button></x-slot:actions>
        </x-artisanpack-modal>
        BLADE));

    $footer = $dom->query('//div[@data-part="footer"]')->item(0);

    expect($footer)->not->toBeNull()
        ->and($dom->query('.//button', $footer))->toHaveCount(2)
        ->and(trim($footer->textContent))->toContain('Delete')
        ->and($dom->query('//p[@data-part="description"]/strong')->item(0)->textContent)->toBe('can\'t');
});

test('a 2.x slot wins over the matching 2.x attribute, and 3.0 props win over both', function (): void {
    $legacy = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-modal title="T" subtitle="Attribute subtitle" actions="Attribute actions" :portal="false">
            <x-slot:subtitle>Slot subtitle</x-slot:subtitle>
            <x-slot:actions><x-artisanpack-button>Slot action</x-artisanpack-button></x-slot:actions>
        </x-artisanpack-modal>
        BLADE));

    expect(trim($legacy->query('//p[@data-part="description"]')->item(0)->textContent))->toBe('Slot subtitle')
        ->and(trim($legacy->query('//div[@data-part="footer"]')->item(0)->textContent))->toBe('Slot action');

    $modern = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-modal title="T" description="New description" footer="New footer" :portal="false">
            <x-slot:subtitle>Slot subtitle</x-slot:subtitle>
            <x-slot:actions>Slot action</x-slot:actions>
        </x-artisanpack-modal>
        BLADE));

    expect(trim($modern->query('//p[@data-part="description"]')->item(0)->textContent))->toBe('New description')
        ->and(trim($modern->query('//div[@data-part="footer"]')->item(0)->textContent))->toBe('New footer');
});
