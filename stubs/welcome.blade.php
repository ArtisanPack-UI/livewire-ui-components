<div>
    <!-- HEADER -->
    <x-artisanpack-header title="Hello" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-artisanpack-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-artisanpack-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-artisanpack-card>
        <x-artisanpack-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('actions', $user)
            <x-artisanpack-button icon="o-trash" wire:click="delete({{ $user['id'] }})" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-artisanpack-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-artisanpack-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-artisanpack-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-artisanpack-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
