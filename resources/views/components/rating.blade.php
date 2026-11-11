{{--
    Rating (#53). Markup contract: design-system Rating.
    Read-only: role=img with the value label; the stars are decorative.
    Input: role=radiogroup, one label per star holding a native radio. `class` lands on
    the root; every other attribute (wire:model, name) lands on every radio, so wire:model
    binds natively. The filled stars follow :checked in CSS; Alpine only updates the
    shown value on change.
--}}
@php
    $rootId = $controlId();
    $name   = $groupName();
    $count  = $countText();
    $score  = $score();
@endphp
@if ( $readonly )
    <div
        {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-rating' ) ) }}
        id="{{ $rootId }}"
        role="img"
        aria-label="{{ $valueText() }}"
        data-size="{{ $size }}"
        data-readonly
        @if ( $disabled ) data-disabled @endif
    >
        @for ( $star = 1; $star <= $max; $star++ )
            @php $fill = $starFill( $star ); @endphp
            <span data-scope="rating" data-part="star" aria-hidden="true"{{ $partClass( 'star' ) }} @if ( 'filled' === $fill ) data-filled @elseif ( 'half' === $fill ) data-half @endif>{{ $iconSvg( 'star' ) }}<span data-scope="rating" data-part="half"{{ $partClass( 'half' ) }}>{{ $iconSvg( 'star' ) }}</span></span>
        @endfor
        @if ( null !== $score )
            <span data-scope="rating" data-part="value" aria-hidden="true"{{ $partClass( 'value' ) }}>{{ $formatted() }}</span>
        @endif
        @if ( null !== $count )
            <span data-scope="rating" data-part="count" aria-hidden="true"{{ $partClass( 'count' ) }}>{{ $count }}</span>
        @endif
    </div>
@else
    <div
        {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-rating' ) ) }}
        id="{{ $rootId }}"
        role="radiogroup"
        aria-label="{{ $groupLabel() }}"
        data-size="{{ $size }}"
        data-interactive
        @if ( $disabled ) data-disabled aria-disabled="true" @endif
        x-data
        x-on:change="const value = $el.querySelector('[data-part=value]'); if (value) value.textContent = $event.target.value"
    >
        @for ( $star = 1; $star <= $max; $star++ )
            <label data-scope="rating" data-part="item" wire:key="{{ $rootId }}-{{ $star }}"{{ $partClass( 'item' ) }}>
                <input {{ $attributes->except( [ 'class', 'id', 'name', 'value' ] ) }}
                    type="radio"
                    data-scope="rating"
                    data-part="control"
                    id="{{ $rootId }}-{{ $star }}"
                    name="{{ $name }}"
                    value="{{ $star }}"
                    aria-label="{{ $valueText( $star ) }}"{{ $partClass( 'control' ) }}
                    @if ( $isChecked( $star ) ) checked @endif
                    @if ( $disabled ) disabled @endif
                />
                <span data-scope="rating" data-part="star" aria-hidden="true"{{ $partClass( 'star' ) }}>{{ $iconSvg( 'star' ) }}<span data-scope="rating" data-part="half"{{ $partClass( 'half' ) }}>{{ $iconSvg( 'star' ) }}</span></span>
            </label>
        @endfor
        @if ( null !== $score )
            <span data-scope="rating" data-part="value" aria-hidden="true"{{ $partClass( 'value' ) }}>{{ $formatted() }}</span>
        @endif
        @if ( null !== $count )
            <span data-scope="rating" data-part="count"{{ $partClass( 'count' ) }}>{{ $count }}</span>
        @endif
    </div>
@endif
