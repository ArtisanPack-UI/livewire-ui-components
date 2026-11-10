{{--
    Button (#44). Markup contract: design-system pilot, Button.
    Every attribute lands on the root. With a safe href, an anchor; disabled or
    loading drops the href and the click handlers.
--}}
@php
    $iconSlot      = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $iconEndSlot   = isset( $iconEnd ) && $filled( $iconEnd ) ? $iconEnd : null;
    $hasIcon       = null !== $iconSlot || $filled( $leadingIcon );
    $hasIconEnd    = null !== $iconEndSlot || $filled( $trailingIcon );
    $showLabel     = ! $iconOnly && isset( $slot ) && $slot->hasActualContent();
    $tag           = $isLink() ? 'a' : 'button';
@endphp
<{{ $tag }}
    @if ( 'a' === $tag )
        @if ( $isInertLink() ) aria-disabled="true" @else href="{{ $linkHref() }}" @endif
    @else
        type="{{ $type }}"
        @if ( $disabled ) disabled @endif
    @endif
    {{ $rootAttributes()->class( $rootClasses( 'apui-button' ) ) }}
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if ( $iconOnly ) data-icon-only @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $loading ) aria-busy="true" @endif
>
    @if ( $hasIcon )
        <span data-scope="button" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( null === $iconSlot && $mirrors( $leadingIcon ) ) data-mirror @endif>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>
    @endif
    @if ( $showLabel )
        <span data-scope="button" data-part="label"{{ $partClass( 'label' ) }}>{{ $content( $slot ) }}</span>
    @endif
    @if ( $hasIconEnd )
        <span data-scope="button" data-part="iconEnd" aria-hidden="true"{{ $partClass( 'iconEnd' ) }}@if ( null === $iconEndSlot && $mirrors( $trailingIcon ) ) data-mirror @endif>@if ( null !== $iconEndSlot ){{ $content( $iconEndSlot ) }}@else{{ $iconSvg( $trailingIcon ) }}@endif</span>
    @endif
    @if ( $loading )
        <span data-scope="button" data-part="spinner" aria-hidden="true"{{ $partClass( 'spinner' ) }}></span>
    @endif
</{{ $tag }}>
