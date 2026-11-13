<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Dropdown;
use ArtisanPack\LivewireUiComponents\View\Components\DropdownMenu;
use Illuminate\Support\Facades\Blade;

/*
 * dropdown (#145) is a deprecated alias of dropdown-menu.
 */

test('extends DropdownMenu', function (): void {
    expect(is_subclass_of(Dropdown::class, DropdownMenu::class))->toBeTrue();
});

test('renders the dropdown menu from the 2.x props', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-dropdown id="actions" label="Options" right glass="frosted" :portal="false"><span>Body</span></x-artisanpack-dropdown>'));
    $panel = $dom->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0);

    expect($panel->getAttribute('data-align'))->toBe('end')
        ->and($panel->getAttribute('data-surface'))->toBe('glass')
        ->and($panel->getAttribute('id'))->toBe('actions-content')
        ->and(trim($dom->query('//button[@data-part="trigger"]')->item(0)->textContent))->toBe('Options')
        ->and($dom->query('//div[contains(@class, "apui-dropdown-menu")]/span')->item(0)->textContent)->toBe('Body')
        ->and($dom->query('//details'))->toHaveCount(0);
});

test('defaults to the start alignment and the overlay surface', function (): void {
    $panel = designSystemDom(Blade::render('<x-artisanpack-dropdown label="Options" :portal="false" />'))
        ->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0);

    expect($panel->getAttribute('data-align'))->toBe('start')
        ->and($panel->getAttribute('data-surface'))->toBe('overlay');
});

test('a string trigger is the label when label is not set', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-dropdown trigger="More" :portal="false" />'));

    expect(trim($dom->query('//button[@data-part="trigger"]')->item(0)->textContent))->toBe('More');
});

test('the 3.0 align prop overrides right', function (): void {
    $panel = designSystemDom(Blade::render('<x-artisanpack-dropdown label="Options" right align="center" :portal="false" />'))
        ->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0);

    expect($panel->getAttribute('data-align'))->toBe('center');
});

test('the removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-dropdown label="Options" icon="o-cog" right top no-x-anchor glass="frosted" glass-tint="#ff0000" :glass-tint-opacity="40" trigger="More" :portal="false" />');

    foreach (['icon=', 'right', 'top=', 'no-x-anchor', 'glass=', 'glass-tint', 'trigger='] as $attribute) {
        expect($html)->not->toContain(' '.$attribute);
    }
});

test('a link inside the dropdown is not blocked by a click handler (#134)', function (): void {
    $html = Blade::render('<x-artisanpack-dropdown label="Account" :portal="false"><a href="/settings">Settings</a></x-artisanpack-dropdown>');
    $dom  = designSystemDom($html);
    $link = $dom->query('//div[contains(@class, "apui-dropdown-menu")]//a[@href="/settings"]');

    // 2.x wrapped the menu in a <details @click="toggleDropdown($event)"> and
    // the menu list in @click="closeDropdown()": the first called
    // preventDefault() on every click, so links never opened. The alias has
    // no click handler on any ancestor of the link; the behavior engine
    // handles item selection itself.
    expect($link)->toHaveCount(1);

    for ($node = $link->item(0)->parentNode; $node instanceof DOMElement; $node = $node->parentNode) {
        foreach (iterator_to_array($node->attributes) as $attribute) {
            expect($attribute->name)->not->toStartWith('@click')
                ->and($attribute->name)->not->toStartWith('x-on:click');
        }
    }

    expect($html)->not->toContain('preventDefault')
        ->and($html)->not->toContain('toggleDropdown');
});

test('wire:model binds the open state', function (): void {
    $host = designSystemDom(Blade::render('<x-artisanpack-dropdown label="Options" wire:model="menuOpen" :portal="false" />'))
        ->query('//div[@x-data]')->item(0);

    expect($host->getAttribute('x-data'))->toContain("open: \$wire.entangle('menuOpen')")
        ->and($host->hasAttribute('wire:model'))->toBeFalse();
});

test('the 2.x trigger slot becomes the trigger without nesting buttons', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-dropdown :portal="false">
            <x-slot:trigger><x-artisanpack-button>Settings</x-artisanpack-button></x-slot:trigger>
            <x-artisanpack-dropdown-menu-item label="Profile" />
        </x-artisanpack-dropdown>
        BLADE));

    $triggers = $dom->query('//*[@data-scope="dropdown-menu"][@data-part="trigger"]');
    $trigger  = $triggers->item(0);

    expect($triggers)->toHaveCount(1)
        ->and($trigger->nodeName)->toBe('span')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('menu')
        ->and($trigger->getAttribute('aria-controls'))->toEndWith('-content')
        ->and($dom->query('.//button', $trigger))->toHaveCount(1)
        ->and(trim($trigger->textContent))->toBe('Settings')
        ->and($dom->query('//button//button'))->toHaveCount(0);
});

test('without a trigger slot the default trigger button is kept', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-dropdown label="Actions" :portal="false" />'));

    $trigger = $dom->query('//*[@data-scope="dropdown-menu"][@data-part="trigger"]')->item(0);

    expect($trigger->nodeName)->toBe('button')
        ->and(trim($trigger->textContent))->toBe('Actions');
});
