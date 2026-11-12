{{--
    Loading (#60). Markup contract: design-system wave #58–#62, Loading.
    role=status with an aria-hidden indicator (a spinner ring, or three dots)
    and a label: the default slot as visible text, else the label visually
    hidden. delay is written inline as --apui-_delay.
--}}
@php
    $userStyle = trim( (string) $attributes->get( 'style', '' ) );
    $style     = $delayStyle() . ( '' !== $userStyle ? '; ' . rtrim( $userStyle, '; ' ) : '' );
    $labelHidden = ! $filled( $slot );
    $labelClass  = trim( ( $labelHidden ? 'apui-sr-only ' : '' ) . ( $classes['label'] ?? '' ) );
@endphp
<span
    {{ $attributes->except( 'style' )->class( $rootClasses( 'apui-loading' ) ) }}
    data-scope="loading"
    data-part="root"
    role="status"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    @if ( $overlay ) data-overlay @endif
    style="{{ $style }}"
>
    <span data-scope="loading" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}>@if ( 'dots' === $variant )<span></span><span></span><span></span>@endif</span>
    <span data-scope="loading" data-part="label"@if ( '' !== $labelClass ) class="{{ $labelClass }}"@endif>@if ( $labelHidden ){{ $labelText() }}@else{{ $content( $slot ) }}@endif</span>
</span>
