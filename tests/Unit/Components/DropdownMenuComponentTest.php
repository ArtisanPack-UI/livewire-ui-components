<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\DropdownMenu;
use Illuminate\Support\Facades\Blade;

/*
 * DropdownMenu (#50): the design-system markup contract (D-019). The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function dropdownMenuComposition(string $attributes = 'id="actions" :portal="false"'): string
{
    return Blade::render(<<<BLADE
        <x-artisanpack-dropdown-menu label="Options" {$attributes}>
            <x-artisanpack-dropdown-menu-group label="Listing">
                <x-artisanpack-dropdown-menu-item value="edit" icon="pencil" shortcut="E" wire:click="edit">Edit</x-artisanpack-dropdown-menu-item>
                <x-artisanpack-dropdown-menu-item value="archive" disabled>Archive</x-artisanpack-dropdown-menu-item>
            </x-artisanpack-dropdown-menu-group>
            <x-artisanpack-dropdown-menu-separator />
            <x-artisanpack-dropdown-menu-item type="checkbox" value="grid" :checked="true">Show grid</x-artisanpack-dropdown-menu-item>
            <x-artisanpack-dropdown-menu-separator />
            <x-artisanpack-dropdown-menu-item value="delete" icon="trash" color="error">Delete</x-artisanpack-dropdown-menu-item>
        </x-artisanpack-dropdown-menu>
        BLADE);
}

function dropdownMenuData(): array
{
    return [
        ['type' => 'group', 'label' => 'Listing', 'items' => [
            ['value' => 'edit', 'label' => 'Edit', 'icon' => 'pencil', 'shortcut' => 'E'],
            ['value' => 'archive', 'label' => 'Archive', 'disabled' => true],
        ]],
        ['type'  => 'separator'],
        ['type'  => 'checkbox', 'value' => 'grid', 'label' => 'Show grid', 'checked' => true],
        ['type'  => 'separator'],
        ['value' => 'delete', 'label' => 'Delete', 'icon' => 'trash', 'color' => 'error'],
    ];
}

/**
 * The menu's parts in order, as `part#id`, ignoring Button internals.
 *
 * @return list<string>
 */
function dropdownMenuParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="dropdown-menu"]') as $element) {
        $parts[] = $element->getAttribute('data-part').($element->hasAttribute('id') ? '#'.$element->getAttribute('id') : '');
    }

    return $parts;
}

test('composition renders every part, scoped dropdown-menu, with stable ids', function (): void {
    expect(dropdownMenuParts(dropdownMenuComposition()))->toBe([
        'trigger#actions-trigger',
        'positioner#actions-positioner',
        'content#actions-content',
        'item-group#actions-group-1',
        'item-group-label#actions-group-1-label',
        'item#actions/edit',
        'icon',
        'item-text',
        'shortcut',
        'item#actions/archive',
        'item-text',
        'separator#actions-separator-1',
        'item#actions/grid',
        'item-indicator',
        'item-text',
        'separator#actions-separator-2',
        'item#actions/delete',
        'icon',
        'item-text',
    ]);
});

test('items renders the same markup as composition', function (): void {
    $data = Blade::render('<x-artisanpack-dropdown-menu id="actions" label="Options" :portal="false" :items="$items" />', ['items' => dropdownMenuData()]);

    expect(dropdownMenuParts($data))->toBe(dropdownMenuParts(dropdownMenuComposition()));
});

test('the panel is the content part with the menu role, closed and hidden by default', function (): void {
    $panel = designSystemDom(dropdownMenuComposition())->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0);

    expect($panel->getAttribute('data-part'))->toBe('content')
        ->and($panel->getAttribute('role'))->toBe('menu')
        ->and($panel->getAttribute('aria-labelledby'))->toBe('actions-trigger')
        ->and($panel->getAttribute('data-state'))->toBe('closed')
        ->and($panel->hasAttribute('hidden'))->toBeTrue()
        ->and($panel->getAttribute('data-align'))->toBe('start')
        ->and($panel->getAttribute('data-surface'))->toBe('overlay');
});

test('open, align and surface render on the panel and reach the machine', function (): void {
    $html  = Blade::render('<x-artisanpack-dropdown-menu id="m" label="View" open align="end" surface="glass" :portal="false" />');
    $panel = designSystemDom($html)->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0);

    expect($panel->getAttribute('data-state'))->toBe('open')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and($panel->getAttribute('data-align'))->toBe('end')
        ->and($panel->getAttribute('data-surface'))->toBe('glass')
        ->and($html)->toContain('apuiDropdownMenu({ id: &#039;m&#039;, open: true, align: &#039;end&#039; })');
});

test('the trigger is an outline neutral Button with the menu ARIA', function (): void {
    $trigger = designSystemDom(dropdownMenuComposition())->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('class'))->toContain('apui-button')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('data-color'))->toBe('neutral')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('menu')
        ->and($trigger->getAttribute('aria-expanded'))->toBe('false')
        ->and($trigger->getAttribute('aria-controls'))->toBe('actions-content')
        ->and($trigger->textContent)->toContain('Options');
});

test('action items carry wire:key, the machine id, their handlers and the apui binding', function (): void {
    $item = designSystemDom(dropdownMenuComposition())->query('//*[@data-value="edit"]')->item(0);

    expect($item->getAttribute('wire:key'))->toBe('actions-item-edit')
        ->and($item->getAttribute('id'))->toBe('actions/edit')
        ->and($item->getAttribute('role'))->toBe('menuitem')
        ->and($item->getAttribute('wire:click'))->toBe('edit')
        ->and($item->getAttribute('x-apui:item'))->toBe('{"value":"edit"}')
        ->and($item->getElementsByTagName('kbd')->item(0)->textContent)->toBe('E');
});

test('disabled and danger items are marked', function (): void {
    $dom = designSystemDom(dropdownMenuComposition());

    $archive = $dom->query('//*[@data-value="archive"]')->item(0);
    $delete  = $dom->query('//*[@data-value="delete"]')->item(0);

    expect($archive->getAttribute('aria-disabled'))->toBe('true')
        ->and($archive->hasAttribute('data-disabled'))->toBeTrue()
        ->and($archive->getAttribute('x-apui:item'))->toBe('{"value":"archive","disabled":true}')
        ->and($delete->getAttribute('data-color'))->toBe('error');
});

test('checkbox and radio items are option items with an indicator', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-dropdown-menu id="view" label="View" :portal="false">
            <x-artisanpack-dropdown-menu-item type="checkbox" value="grid" :checked="true">Show grid</x-artisanpack-dropdown-menu-item>
            <x-artisanpack-dropdown-menu-item type="radio" value="price">Price</x-artisanpack-dropdown-menu-item>
        </x-artisanpack-dropdown-menu>
        BLADE);
    $dom = designSystemDom($html);

    $grid  = $dom->query('//*[@data-value="grid"]')->item(0);
    $price = $dom->query('//*[@data-value="price"]')->item(0);

    expect($grid->getAttribute('role'))->toBe('menuitemcheckbox')
        ->and($grid->getAttribute('aria-checked'))->toBe('true')
        ->and($grid->getAttribute('data-state'))->toBe('checked')
        ->and($grid->getAttribute('x-apui:option-item'))->toBe('{"type":"checkbox","value":"grid","checked":true}')
        ->and($price->getAttribute('role'))->toBe('menuitemradio')
        ->and($price->getAttribute('aria-checked'))->toBe('false')
        ->and($dom->query('//*[@data-value="grid"]/*[@data-part="item-indicator"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@data-value="price"]/*[@data-part="item-indicator"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('an item without a value uses its label', function (): void {
    $item = designSystemDom(Blade::render('<x-artisanpack-dropdown-menu id="m" label="A" :portal="false"><x-artisanpack-dropdown-menu-item>Rename</x-artisanpack-dropdown-menu-item></x-artisanpack-dropdown-menu>'))
        ->query('//*[@data-part="item"]')->item(0);

    expect($item->getAttribute('data-value'))->toBe('Rename')
        ->and($item->getAttribute('wire:key'))->toBe('m-item-Rename');
});

test('a given wire:key and group id are kept', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-dropdown-menu id="m" label="A" :portal="false">
            <x-artisanpack-dropdown-menu-group id="sorting" label="Sort">
                <x-artisanpack-dropdown-menu-item value="new" wire:key="sort-new">Newest</x-artisanpack-dropdown-menu-item>
            </x-artisanpack-dropdown-menu-group>
        </x-artisanpack-dropdown-menu>
        BLADE));

    expect($dom->query('//*[@data-part="item"]')->item(0)->getAttribute('wire:key'))->toBe('sort-new')
        ->and($dom->query('//*[@data-part="item-group"]')->item(0)->getAttribute('id'))->toBe('sorting')
        ->and($dom->query('//*[@data-part="item-group"]')->item(0)->getAttribute('x-apui:item-group'))->toBe('{"id":"sorting"}')
        ->and($dom->query('//*[@data-part="item-group-label"]')->item(0)->getAttribute('x-apui:item-group-label'))->toBe('{"htmlFor":"sorting"}');
});

test('wire:model entangles the open state', function (): void {
    expect(Blade::render('<x-artisanpack-dropdown-menu wire:model="menuOpen" label="A" />'))
        ->toContain('open: $wire.entangle(&#039;menuOpen&#039;)');
});

test('portal teleports the panel; off renders it in place', function (): void {
    expect(Blade::render('<x-artisanpack-dropdown-menu label="A" />'))->toContain('<template x-teleport="body">')
        ->and(Blade::render('<x-artisanpack-dropdown-menu label="A" :portal="false" />'))->not->toContain('x-teleport');
});

test('classes reach the menu and its children', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-dropdown-menu id="m" label="A" class="w-64" :portal="false" :classes="['item' => 'py-1', 'itemText' => 'truncate', 'separator' => 'my-2']">
            <x-artisanpack-dropdown-menu-item value="a">A</x-artisanpack-dropdown-menu-item>
            <x-artisanpack-dropdown-menu-separator />
        </x-artisanpack-dropdown-menu>
        BLADE));

    expect($dom->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0)->getAttribute('class'))->toBe('apui-dropdown-menu w-64')
        ->and($dom->query('//*[@data-part="item"]')->item(0)->getAttribute('class'))->toBe('py-1')
        ->and($dom->query('//*[@data-part="item-text"]')->item(0)->getAttribute('class'))->toBe('truncate')
        ->and($dom->query('//*[@data-part="separator"]')->item(0)->getAttribute('class'))->toBe('my-2');
});

test('the label slot overrides the label prop', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-dropdown-menu :portal="false"><x-slot:label><em>More</em></x-slot:label></x-artisanpack-dropdown-menu>'));

    expect($dom->query('//button[@data-part="trigger"]//em')->item(0)->textContent)->toBe('More');
});

test('the menu leaves the rendering stack once its view renders', function (): void {
    dropdownMenuComposition();

    expect(DropdownMenu::current())->toBeNull();
});

test('labels are escaped', function (): void {
    expect(Blade::render('<x-artisanpack-dropdown-menu label="A" :items="$items" />', ['items' => [['value' => 'x', 'label' => '<script>alert(1)</script>']]]))
        ->not->toContain('<script>alert')
        ->toContain('&lt;script&gt;');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(DropdownMenu::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['open', 'label', 'items', 'align', 'surface', 'portal', 'classes']);
});
