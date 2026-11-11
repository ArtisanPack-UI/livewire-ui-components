<div>
    <!-- HEADER -->
    <x-artisanpack-header title="Hello" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-artisanpack-input id="header-search" placeholder="Search..." aria-label="Search" wire:model.live.debounce="search" leading-icon="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-artisanpack-button @click="$wire.drawer = true" leading-icon="o-funnel">Filters</x-artisanpack-button>
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-artisanpack-card>
        <x-artisanpack-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('actions', $user)
            <x-artisanpack-button variant="ghost" color="error" size="sm" icon-only leading-icon="trash" aria-label="Delete" wire:click="delete({{ $user['id'] }})" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-artisanpack-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-artisanpack-input id="drawer-search" placeholder="Search..." aria-label="Search" wire:model.live.debounce="search" leading-icon="search" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-artisanpack-button variant="outline" color="neutral" leading-icon="x" wire:click="clear">Reset</x-artisanpack-button>
            <x-artisanpack-button leading-icon="check" @click="$wire.drawer = false">Done</x-artisanpack-button>
        </x-slot:actions>
    </x-drawer>
</div>
