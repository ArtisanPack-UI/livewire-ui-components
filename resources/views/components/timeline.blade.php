{{--
    Timeline (#61). Markup contract: design-system wave 5, Timeline.
    An ordered list of timeline-item children, or the same items from
    `items`, rendered before them (D-023).
--}}
<ol
    {{ $attributes->class( $rootClasses( 'apui-timeline' ) ) }}
    data-variant="{{ $variantValue() }}"
    @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
>
    @foreach ( $entries() as $entry )
        <x-artisanpack-timeline-item :title="$entry['title']" :time="$entry['time']" :datetime="$entry['datetime']" :icon="$entry['icon']" :color="$entry['color']" :description="$entry['description']" :card="$entry['card']" />
    @endforeach
    {{ $slot ?? '' }}
</ol>
@php $finishRendering(); @endphp
