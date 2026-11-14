<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * Dialog (#49) and DropdownMenu (#50) inside Livewire components: the server
 * renders the bound property's open state, ids are unique on the page (they
 * include the Livewire component's id, and repeats are numbered), and a
 * re-render keeps every id so the morph keeps the panels (D-015).
 */

class OverlayLivewireTestComponent extends Component
{
    public bool $showInvite = false;

    public bool $menuOpen = false;

    public string $label = 'Options';

    public array $rows = [1, 2];

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-dialog wire:model="showInvite" :title="__( 'Invite member' )" :portal="false">Body</x-artisanpack-dialog>
                <x-artisanpack-dropdown-menu wire:model="menuOpen" :label="$label" :portal="false">
                    <x-artisanpack-dropdown-menu-item value="edit">{{ __( 'Edit' ) }}</x-artisanpack-dropdown-menu-item>
                </x-artisanpack-dropdown-menu>
                @foreach ( $rows as $row )
                    <x-artisanpack-dropdown-menu :label="__( 'Actions' )" :portal="false">
                        <x-artisanpack-dropdown-menu-item value="open item">{{ __( 'Open' ) }}</x-artisanpack-dropdown-menu-item>
                    </x-artisanpack-dropdown-menu>
                @endforeach
            </div>
            BLADE;
    }
}

/**
 * Every id and wire:key in the markup, in order.
 *
 * @return list<string>
 */
function overlayIds(string $html): array
{
    preg_match_all('/\s(?:id|wire:key)="([^"]+)"/', $html, $matches);

    return array_values(array_filter($matches[1], static fn (string $id): bool => str_starts_with($id, 'apui-')));
}

test('the server renders the bound open state', function (): void {
    $component = Livewire::test(OverlayLivewireTestComponent::class);
    $closed    = designSystemDom($component->html());

    expect($closed->query('//div[contains(@class, "apui-dialog")]')->item(0)->getAttribute('data-state'))->toBe('closed');

    $component->set('showInvite', true)->set('menuOpen', true);
    $open = designSystemDom($component->html());

    expect($open->query('//div[contains(@class, "apui-dialog")]')->item(0)->getAttribute('data-state'))->toBe('open')
        ->and($open->query('//div[contains(@class, "apui-dropdown-menu")]')->item(0)->getAttribute('data-state'))->toBe('open');
});

test('ids include the component id and stay the same across re-renders', function (): void {
    $component = Livewire::test(OverlayLivewireTestComponent::class);
    $id        = $component->id();
    $before    = overlayIds($component->html());

    $component->set('showInvite', true)->set('menuOpen', true)->set('label', 'More');

    expect(overlayIds($component->html()))->toBe($before)
        ->and($before)->toContain('apui-dialog-showinvite-'.substr(md5($id."\0showInvite"), 0, 8).'-content')
        ->and($before)->toContain('apui-dropdown-menu-menuopen-'.substr(md5($id."\0menuOpen"), 0, 8).'-content');
});

test('menus in a foreach get distinct ids, item ids and wire:keys', function (): void {
    $ids = overlayIds(Livewire::test(OverlayLivewireTestComponent::class)->html());

    $menus = array_values(array_filter($ids, static fn (string $id): bool => str_ends_with($id, '-content') && str_starts_with($id, 'apui-dropdown-menu-') && ! str_contains($id, 'menuopen')));
    $keys  = array_values(array_filter($ids, static fn (string $id): bool => str_ends_with($id, '-item-open-item')));
    $items = array_values(array_filter($ids, static fn (string $id): bool => str_ends_with($id, '/open-item')));

    expect($menus)->toHaveCount(2)
        ->and($menus[1])->toBe(str_replace('-content', '-2-content', $menus[0]))
        ->and(array_unique($keys))->toHaveCount(2)
        ->and(array_unique($items))->toHaveCount(2)
        ->and(array_unique($ids))->toHaveCount(count($ids));
});

test('two components with the same wire:model name render distinct ids', function (): void {
    $first  = overlayIds(Livewire::test(OverlayLivewireTestComponent::class)->html());
    $second = overlayIds(Livewire::test(OverlayLivewireTestComponent::class)->html());

    expect(array_intersect($first, $second))->toBe([]);
});
