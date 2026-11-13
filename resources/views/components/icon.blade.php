{{--
    Icon (#65). Markup contract: design-system Icon. span.apui-icon sizes one
    glyph (the svg part, from the icon registry) or the default slot's own
    <svg>. data-variant, data-size and data-color; a tile also carries the
    apui-icon-tile class. Labeled (label, or the caller's own aria-label or
    aria-labelledby): role=img with the name; else aria-hidden.
    data-mirror flips directional icons under RTL. Every attribute lands on
    the root.
--}}
@php
    $hasSlot    = isset( $slot ) && $filled( $slot );
    $hasLabel   = $filled( $label );
    // A caller's own aria-label or aria-labelledby names it too: only an unnamed icon is hidden.
    $named      = $hasLabel || $filled( $attributes->get( 'aria-label' ) ) || $filled( $attributes->get( 'aria-labelledby' ) );
    $ownRole    = $attributes->has( 'role' );
    $attributes = $attributes->except( array_filter( [ $hasLabel ? 'aria-label' : null, $named ? null : 'aria-hidden' ] ) );
@endphp
<span
    {{ $attributes->class( $iconClasses() ) }}
    data-scope="icon"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    @if ( null !== $color ) data-color="{{ $color }}" @endif
    @if ( $mirrored( $hasSlot ) ) data-mirror @endif
    @if ( $named )@unless ( $ownRole ) role="img"@endunless @if ( $hasLabel ) aria-label="{{ $label }}"@endif @else aria-hidden="true" @endif
>@if ( $hasSlot ){{ $content( $slot ) }}@else{{ $svg() }}@endif</span>
