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
