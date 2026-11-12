{{--
    The Livewire Calendar's event details (#64), inside its Dialog: the
    category as a soft Badge in the event's tint, then the description. The
    Dialog's title and description already give the title and the time.
    Internal: not public API.
--}}
<div>
    @if ( $event )
        <x-artisanpack-stack gap="3">
            @if ( ! empty( $event['category'] ) )
                <div><x-artisanpack-badge variant="soft" :color="$event['color'] ?? 'primary'">{{ $event['category'] }}</x-artisanpack-badge></div>
            @endif
            @if ( ! empty( $event['description'] ) )
                <x-artisanpack-text>{{ $event['description'] }}</x-artisanpack-text>
            @endif
        </x-artisanpack-stack>
    @endif
</div>
