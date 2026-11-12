{{--
    Progress (#60). Markup contract: design-system wave #58–#62, Progress.
    The root is the progressbar; aria-valuenow is left out while
    indeterminate. Without a label, name it with aria-label.
--}}
@php
    $hasLabel     = null !== $label && '' !== $label;
    $valueDisplay = $valueDisplay();
    [ $segmentCount, $segmentsDone ] = $segments();
@endphp
<div
    {{ $attributes->except( 'id' )->class( $rootClasses( 'apui-progress' ) ) }}
    data-scope="progress"
    data-part="root"
    id="{{ $baseId() }}"
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="{{ $number( $maximum() ) }}"
    @unless ( $indeterminate ) aria-valuenow="{{ $number( $clampedValue() ) }}" @endunless
    @if ( null !== $valueText && '' !== $valueText ) aria-valuetext="{{ $valueText }}" @endif
    @if ( $hasLabel ) aria-labelledby="{{ $partId( 'label' ) }}" @endif
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    @if ( $indeterminate ) data-indeterminate @endif
    @if ( $segmented ) data-segmented @endif
>
    @if ( $hasLabel || null !== $valueDisplay )
        <div data-scope="progress" data-part="head"{{ $partClass( 'head' ) }}>
            @if ( $hasLabel )
                <span data-scope="progress" data-part="label" id="{{ $partId( 'label' ) }}"{{ $partClass( 'label' ) }}>{{ $label }}</span>
            @endif
            @if ( null !== $valueDisplay )
                <span data-scope="progress" data-part="value"{{ $partClass( 'value' ) }}>{{ $valueDisplay }}</span>
            @endif
        </div>
    @endif
    <div data-scope="progress" data-part="track"{{ $partClass( 'track' ) }}>
        @if ( $segmented )
            @for ( $segment = 0; $segment < $segmentCount; $segment++ )
                <div data-scope="progress" data-part="bar"{{ $partClass( 'bar' ) }} @if ( $segment < $segmentsDone ) data-done @endif></div>
            @endfor
        @elseif ( $indeterminate )
            <div data-scope="progress" data-part="bar"{{ $partClass( 'bar' ) }}></div>
        @else
            <div data-scope="progress" data-part="bar"{{ $partClass( 'bar' ) }} style="--apui-_v: {{ $percent() }}%"></div>
        @endif
    </div>
</div>
