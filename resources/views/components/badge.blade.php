{{--
    Badge (#61). Markup contract: design-system wave, Badge.
    A span with data-variant, data-color and data-size; the dot and the icon are
    decorative, the label part holds the text. The icon slot overrides
    leading-icon. The deprecated 2.x `value` attribute renders as the label when
    the slot is empty.
--}}
@php
    $iconSlot   = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $hasIcon    = null !== $iconSlot || $filled( $leadingIcon );
    $label      = isset( $slot ) && $filled( $slot ) ? $content( $slot ) : $content( $attributes->get( 'value', '' ) );
    $attributes = $attributes->except( [ 'value' ] );
@endphp
<span
    {{ $attributes->class( $rootClasses( 'apui-badge' ) ) }}
    data-variant="{{ $variantValue() }}"
    data-color="{{ $colorValue() }}"
    data-size="{{ $sizeValue() }}"
>@if ( $dot )<span data-scope="badge" data-part="dot" aria-hidden="true"{{ $partClass( 'dot' ) }}></span>@endif @if ( $hasIcon )<span data-scope="badge" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( null === $iconSlot && $mirrors( $leadingIcon ) ) data-mirror @endif>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>@endif<span data-scope="badge" data-part="label"{{ $partClass( 'label' ) }}>{{ $label }}</span></span>
