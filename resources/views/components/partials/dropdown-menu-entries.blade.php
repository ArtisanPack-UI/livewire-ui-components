{{-- DropdownMenu `items` (#50): the same children as composition. --}}
@foreach ( $entries as $entry )
    @if ( 'group' === $entry['type'] )
        <x-artisanpack-dropdown-menu-group :label="$entry['label']" :id="$entry['id']">
            @include( 'livewire-ui-components::components.partials.dropdown-menu-entries', [ 'entries' => $entry['items'] ] )
        </x-artisanpack-dropdown-menu-group>
    @elseif ( 'separator' === $entry['type'] )
        <x-artisanpack-dropdown-menu-separator />
    @else
        <x-artisanpack-dropdown-menu-item
            :value="$entry['value']"
            :label="$entry['label']"
            :icon="$entry['icon']"
            :shortcut="$entry['shortcut']"
            :color="$entry['color']"
            :disabled="$entry['disabled']"
            :type="$entry['type']"
            :checked="$entry['checked']"
        />
    @endif
@endforeach
