{{--
    Steps (#56). Markup contract: design-system wave 4, Steps.
    An ordered list of step children (or the same children from `steps`).
    wire:model binds the current step; with clickable, completed steps'
    titles are buttons that set it through $wire.entangle. Use steps or
    children, not both: data steps number themselves from 1.
--}}
@php
    $alpineData = $alpineData();
@endphp
<ol
    {{ $rootAttributes()->class( $rootClasses( 'apui-steps' ) ) }}
    data-orientation="{{ $orientationValue() }}"
    @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
    @if ( null !== $alpineData ) x-data="{{ $alpineData }}" @endif
>
    @foreach ( $entries() as $entry )
        <x-artisanpack-step :title="$entry['title']" :description="$entry['description']" :state="$entry['state']" :icon="$entry['icon']" :step="$entry['number']" />
    @endforeach
    {{ $slot ?? '' }}
</ol>
@php $finishRendering(); @endphp
