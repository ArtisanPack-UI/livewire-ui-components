{{--
    ProgressRadial (#60). Markup contract: design-system wave #58–#62,
    ProgressRadial. The root is the progressbar; the ring's bar circle shows
    the value through its dash offset. Without a label, name it with aria-label.
--}}
@php
    $hasLabel     = null !== $label && '' !== $label;
    $geometry     = $geometry();
    $valueDisplay = $valueDisplay();
    $showValue    = ! $hideValue && ( '' !== $valueDisplay || $hasLabel );
    $userStyle    = trim( (string) $attributes->get( 'style', '' ) );
    $style        = '--apui-_s: ' . $number( $size ) . 'px' . ( '' !== $userStyle ? '; ' . rtrim( $userStyle, '; ' ) : '' );
@endphp
<span
    {{ $attributes->except( [ 'id', 'style' ] )->class( $rootClasses( 'apui-progress-radial' ) ) }}
    data-scope="progress-radial"
    data-part="root"
    id="{{ $baseId() }}"
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="{{ $number( $maximum() ) }}"
    @unless ( $indeterminate ) aria-valuenow="{{ $number( $clampedValue() ) }}" @endunless
    @if ( null !== $valueText && '' !== $valueText ) aria-valuetext="{{ $valueText }}" @endif
    @if ( $hasLabel && $showValue ) aria-labelledby="{{ $partId( 'label' ) }}" @elseif ( $hasLabel ) aria-label="{{ $label }}" @endif
    data-color="{{ $color }}"
    @if ( $indeterminate ) data-indeterminate @endif
    style="{{ $style }}"
>
    <svg data-scope="progress-radial" data-part="svg" viewBox="0 0 {{ $number( $size ) }} {{ $number( $size ) }}" aria-hidden="true"{{ $partClass( 'svg' ) }}><circle data-scope="progress-radial" data-part="track" cx="{{ $geometry['center'] }}" cy="{{ $geometry['center'] }}" r="{{ $geometry['radius'] }}" stroke-width="{{ $number( $thickness ) }}"{{ $partClass( 'track' ) }}></circle><circle data-scope="progress-radial" data-part="bar" cx="{{ $geometry['center'] }}" cy="{{ $geometry['center'] }}" r="{{ $geometry['radius'] }}" stroke-width="{{ $number( $thickness ) }}" stroke-dasharray="{{ $geometry['circumference'] }}" stroke-dashoffset="{{ $geometry['offset'] }}"{{ $partClass( 'bar' ) }}></circle></svg>
    @if ( $showValue )
        <span data-scope="progress-radial" data-part="value"{{ $partClass( 'value' ) }}>{{ $valueDisplay }}@if ( $hasLabel )<span data-scope="progress-radial" data-part="label" id="{{ $partId( 'label' ) }}"{{ $partClass( 'label' ) }}>{{ $label }}</span>@endif</span>
    @endif
</span>
