<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Form (#53): the native form, its attributes and the actions row. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

test('it renders a native form with its attributes', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-form wire:submit="save" aria-label="Invite" class="mt-2" novalidate>Fields</x-artisanpack-form>'));
    $form = $dom->query('//form')->item(0);

    expect($form->getAttribute('class'))->toBe('apui-form mt-2')
        ->and($form->getAttribute('wire:submit'))->toBe('save')
        ->and($form->getAttribute('aria-label'))->toBe('Invite')
        ->and($form->hasAttribute('novalidate'))->toBeTrue()
        ->and($form->hasAttribute('data-divided'))->toBeFalse()
        ->and($dom->query('//*[@data-part="actions"]')->length)->toBe(0);
});

test('the actions slot renders the actions row last', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-form divided>Fields<x-slot:actions><button type="submit">Save</button></x-slot:actions></x-artisanpack-form>'));

    $actions = $dom->query('//form/*[last()]')->item(0);

    expect($dom->query('//form')->item(0)->hasAttribute('data-divided'))->toBeTrue()
        ->and($actions->getAttribute('data-scope'))->toBe('form')
        ->and($actions->getAttribute('data-part'))->toBe('actions')
        ->and($actions->textContent)->toBe('Save');
});

test('the actions prop renders text', function (): void {
    expect(Blade::render('<x-artisanpack-form actions="Save">Fields</x-artisanpack-form>'))->toContain('<div data-scope="form" data-part="actions">Save</div>');
});
