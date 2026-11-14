<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use ArtisanPack\LivewireUiComponents\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success(__('Filters cleared.'));
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning(__('Will delete #:id', ['id' => $id]), __('It is fake.'));
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => __('Name')],
            ['key' => 'age', 'label' => __('Age'), 'numeric' => true],
            ['key' => 'email', 'label' => __('Email'), 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you query an Eloquent model instead.
     */
    public function users(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Ada', 'email' => 'ada@example.com', 'age' => 36],
            ['id' => 2, 'name' => 'Grace', 'email' => 'grace@example.com', 'age' => 45],
            ['id' => 3, 'name' => 'Linus', 'email' => 'linus@example.com', 'age' => 28],
        ])
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
            });
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <x-artisanpack-page-header :title="__( 'Hello' )">
        <x-slot:actions>
            <x-artisanpack-input
                id="header-search"
                :placeholder="__( 'Search...' )"
                :aria-label="__( 'Search' )"
                wire:model.live.debounce="search"
                leading-icon="search"
            />
            <x-artisanpack-button variant="outline" color="neutral" leading-icon="o-funnel" x-on:click="$wire.drawer = true">{{ __( 'Filters' ) }}</x-artisanpack-button>
        </x-slot:actions>
    </x-artisanpack-page-header>

    <x-artisanpack-card>
        <x-artisanpack-table :headers="$headers" :rows="$users" :sort-by="$sortBy" :label="__( 'Users' )">
            @scope( 'actions', $user )
                <x-artisanpack-button
                    variant="ghost"
                    color="error"
                    size="sm"
                    icon-only
                    leading-icon="trash"
                    :aria-label="__( 'Delete' )"
                    wire:click="delete( {{ $user['id'] }} )"
                    :wire:confirm="__( 'Are you sure?' )"
                />
            @endscope
        </x-artisanpack-table>
    </x-artisanpack-card>

    <x-artisanpack-drawer wire:model="drawer" :title="__( 'Filters' )">
        <x-artisanpack-input
            id="drawer-search"
            :placeholder="__( 'Search...' )"
            :aria-label="__( 'Search' )"
            wire:model.live.debounce="search"
            leading-icon="search"
            x-on:keydown.enter="$wire.drawer = false"
        />

        <x-slot:footer>
            <x-artisanpack-button variant="outline" color="neutral" leading-icon="x" wire:click="clear">{{ __( 'Reset' ) }}</x-artisanpack-button>
            <x-artisanpack-button leading-icon="check" x-on:click="$wire.drawer = false">{{ __( 'Done' ) }}</x-artisanpack-button>
        </x-slot:footer>
    </x-artisanpack-drawer>
</div>
