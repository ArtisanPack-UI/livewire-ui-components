{{--
    Toggle (#53). Markup contract: design-system form-text wave, Toggle.
    `class` lands on the root; every other attribute lands on the native
    checkbox (role=switch). Its CSS reads the native :checked; on change,
    Alpine writes data-state back from the input.
--}}
@php
    $controlId      = $controlId();
    $message        = $errorMessage( $errors ?? null );
    $isInvalid      = $invalid || null !== $message;
    $hasLabel       = $filled( $label );
    $hasDescription = $filled( $description );
    $describedBy    = $describedBy( array_values( array_filter( [
        $hasDescription ? $controlId . '-description' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-toggle' ) ) }}
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
>
    <span data-scope="toggle" data-part="track"{{ $partClass( 'track' ) }}>
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            type="checkbox"
            role="switch"
            data-scope="toggle"
            data-part="control"
            id="{{ $controlId }}"{{ $partClass( 'control' ) }}
            x-data
            x-on:change="$el.closest('.apui-toggle').dataset.state = $el.checked ? 'checked' : 'unchecked'"
            @if ( $checked ) checked @endif
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        <span data-scope="toggle" data-part="thumb" aria-hidden="true"{{ $partClass( 'thumb' ) }}></span>
    </span>
    @if ( $hasLabel )
        <label data-scope="toggle" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}</label>
    @endif
    @if ( $hasDescription )
        <p data-scope="toggle" data-part="description" id="{{ $controlId }}-description"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="toggle" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
