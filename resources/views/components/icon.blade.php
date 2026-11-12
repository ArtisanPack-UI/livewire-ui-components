{{--
    Icon (#65). Markup contract: design-system Icon. span.apui-icon sizes one
    glyph (the svg part, from the icon registry) or the default slot's own
    <svg>. data-variant, data-size and data-color; a tile also carries the
    apui-icon-tile class. Labeled: role=img with aria-label; else aria-hidden.
    data-mirror flips directional icons under RTL. Every attribute lands on
    the root.
--}}
@php
    $hasSlot  = isset( $slot ) && $filled( $slot );
    $hasLabel = $filled( $label );
@endphp
<span
    {{ $attributes->class( $iconClasses() ) }}
    data-scope="icon"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    @if ( null !== $color ) data-color="{{ $color }}" @endif
    @if ( $mirrored( $hasSlot ) ) data-mirror @endif
    @if ( $hasLabel ) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
>@if ( $hasSlot ){{ $content( $slot ) }}@else{{ $svg() }}@endif</span>
