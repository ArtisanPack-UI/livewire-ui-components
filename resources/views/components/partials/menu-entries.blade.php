{{-- Menu `items` (#56): the same children as composition. --}}
@foreach ( $entries as $entry )
    @if ( 'separator' === $entry['type'] )
        <x-artisanpack-menu-separator />
    @elseif ( 'title' === $entry['type'] )
        <x-artisanpack-menu-title :label="$entry['label']" />
    @elseif ( 'sub' === $entry['type'] )
        <x-artisanpack-menu-sub :label="$entry['label']" :icon="$entry['icon']" :badge="$entry['badge']" :disabled="$entry['disabled']" :open="$entry['open']">
            @include( 'livewire-ui-components::components.partials.menu-entries', [ 'entries' => $entry['items'] ] )
        </x-artisanpack-menu-sub>
    @else
        <x-artisanpack-menu-item
            :label="$entry['label']"
            :href="$entry['href']"
            :icon="$entry['icon']"
            :badge="$entry['badge']"
            :current="$entry['current']"
            :disabled="$entry['disabled']"
        />
    @endif
@endforeach
