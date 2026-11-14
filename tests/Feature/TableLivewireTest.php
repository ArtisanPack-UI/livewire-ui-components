<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * Table inside a Livewire component (#62): wire:model renders the bound
 * selection on the server and entangles it, and a sortable header sets the
 * component's sortBy property, as 2.x did.
 */

class TableLivewireTestComponent extends Component
{
    /** @var list<int> */
    public array $selected = [2];

    /** @var array{column: string, direction: string} */
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-table
                    :headers="[ [ 'key' => 'name', 'label' => 'Name', 'sortable' => true ] ]"
                    :rows="[ [ 'id' => 1, 'name' => 'Ada' ], [ 'id' => 2, 'name' => 'Grace' ] ]"
                    :sort-by="$sortBy"
                    selectable
                    wire:model="selected"
                />
            </div>
            BLADE;
    }
}

test('renders the bound selection and entangles it', function (): void {
    $html = Livewire::test(TableLivewireTestComponent::class)->html();
    $dom  = designSystemDom($html);

    expect($dom->query('//tbody/tr[2]')->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($dom->query('//tbody/tr[1]')->item(0)->getAttribute('aria-selected'))->toBe('false')
        ->and($dom->query('//thead//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('indeterminate')
        ->and($html)->toContain('$wire.entangle(&quot;selected&quot;)')
        ->and($html)->toContain('this.$wire.set( &#039;sortBy&#039;, next )')
        ->and($dom->query('//th')->item(1)->getAttribute('aria-sort'))->toBe('ascending');
});

test('row keys are stable wire:keys', function (): void {
    $dom  = designSystemDom(Livewire::test(TableLivewireTestComponent::class)->html());
    $keys = array_map(static fn (DOMElement $row): string => $row->getAttribute('wire:key'), iterator_to_array($dom->query('//tbody/tr')));

    expect($keys)->toHaveCount(2)
        ->and($keys[0])->toEndWith('-row-1')
        ->and($keys[1])->toEndWith('-row-2');
});

class TableLivewireEmptySortTestComponent extends Component
{
    /** @var array{column?: string, direction?: string} */
    public array $sortBy = [];

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-table
                    :headers="[ [ 'key' => 'name', 'label' => 'Name', 'sortable' => true ] ]"
                    :rows="[ [ 'id' => 1, 'name' => 'Ada' ] ]"
                    :sort-by="$sortBy"
                />
            </div>
            BLADE;
    }
}

class TableLivewireNoSortPropertyTestComponent extends Component
{
    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-table
                    :headers="[ [ 'key' => 'name', 'label' => 'Name', 'sortable' => true ] ]"
                    :rows="[ [ 'id' => 1, 'name' => 'Ada' ] ]"
                />
            </div>
            BLADE;
    }
}

test('a sortBy property that starts empty is still set by a header click', function (): void {
    $html = Livewire::test(TableLivewireEmptySortTestComponent::class)->html();

    expect(designSystemDom($html)->query('//button[@data-part="sort"]'))->toHaveCount(1)
        ->and($html)->toContain('this.$wire.set( &#039;sortBy&#039;, next )');
});

test('without a public sortBy property a header click only dispatches sort-change', function (): void {
    $html = Livewire::test(TableLivewireNoSortPropertyTestComponent::class)->html();

    expect(designSystemDom($html)->query('//button[@data-part="sort"]'))->toHaveCount(1)
        ->and($html)->toContain('sort-change')
        ->and($html)->not->toContain('$wire.set( &#039;sortBy&#039;');
});
