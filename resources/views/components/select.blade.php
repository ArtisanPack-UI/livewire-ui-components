{{--
    Select (#46). Markup contract: design-system pilot, Select.
    `class` lands on the root; every other attribute lands on the native select.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $iconSlot    = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $hasIcon     = null !== $iconSlot || $filled( $leadingIcon );
    $placeholder = $placeholderText();
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-select' ) ) }}
    data-size="{{ $size }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
>
    @if ( $hasLabel )
        <label data-scope="select" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="select" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="select" data-part="box"{{ $partClass( 'box' ) }}>
        @if ( $hasIcon )
            <span data-scope="select" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>
        @endif
        <select {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            data-scope="select"
            data-part="control"
            id="{{ $controlId }}"{{ $partClass( 'control' ) }}
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        >
            @if ( null !== $placeholder )
                <option value=""@if ( $placeholderSelected() ) selected @endif @if ( $required ) disabled @endif>{{ $placeholder }}</option>
            @endif
            @foreach ( $normalizedOptions() as $option )
                @if ( isset( $option['group'] ) )
                    <optgroup label="{{ $option['group'] }}">
                        @foreach ( $option['options'] as $groupOption )
                            <option value="{{ $groupOption['value'] }}"@if ( $isSelected( $groupOption['value'] ) ) selected @endif @if ( $groupOption['disabled'] ) disabled @endif>{{ $groupOption['label'] }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $option['value'] }}"@if ( $isSelected( $option['value'] ) ) selected @endif @if ( $option['disabled'] ) disabled @endif>{{ $option['label'] }}</option>
                @endif
            @endforeach
            {{ $slot ?? '' }}
        </select>
        <span data-scope="select" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}>{{ $iconSvg( 'chevron-down' ) }}</span>
    </div>
    @if ( $hasHint )
        <p data-scope="select" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="select" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
