{{--
    Input (#45). Markup contract: design-system pilot, Input.
    `class` lands on the root; every other attribute lands on the native input.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $iconSlot    = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $iconEndSlot = isset( $iconEnd ) && $filled( $iconEnd ) ? $iconEnd : null;
    $hasIcon     = null !== $iconSlot || $filled( $leadingIcon );
    $hasIconEnd  = null !== $iconEndSlot || $filled( $trailingIcon );
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-input' ) ) }}
    data-size="{{ $size }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
>
    @if ( $hasLabel )
        <label data-scope="input" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="input" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="input" data-part="box"{{ $partClass( 'box' ) }}>
        @if ( $hasIcon )
            <span data-scope="input" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>
        @endif
        @if ( $filled( $prefix ) )
            <span data-scope="input" data-part="prefix"{{ $partClass( 'prefix' ) }}>{{ $content( $prefix ) }}</span>
        @endif
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            data-scope="input"
            data-part="control"
            id="{{ $controlId }}"
            type="{{ $type }}"{{ $partClass( 'control' ) }}
            @if ( null !== $placeholder ) placeholder="{{ $placeholder }}" @endif
            @if ( null !== $value ) value="{{ $value }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( $readonly ) readonly @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        @if ( $filled( $suffix ) )
            <span data-scope="input" data-part="suffix"{{ $partClass( 'suffix' ) }}>{{ $content( $suffix ) }}</span>
        @endif
        @if ( $hasIconEnd )
            <span data-scope="input" data-part="iconEnd" aria-hidden="true"{{ $partClass( 'iconEnd' ) }}>@if ( null !== $iconEndSlot ){{ $content( $iconEndSlot ) }}@else{{ $iconSvg( $trailingIcon ) }}@endif</span>
        @endif
    </div>
    @if ( $hasHint )
        <p data-scope="input" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="input" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
