{{--
    Checkbox (#47). Markup contract: design-system pilot, Checkbox.
    `class` lands on the root; every other attribute lands on the native checkbox.
    Only script can set the native indeterminate property, so Alpine does it, and
    keeps the root's data-state in step on change. The indicator holds both
    glyphs; CSS shows one.
--}}
@php
    $controlId      = $controlId();
    $message        = $errorMessage( $errors ?? null );
    $isInvalid      = $invalid || null !== $message;
    $hasLabel       = $filled( $label );
    $hasDescription = $filled( $description );
    $state          = $state();
    $describedBy    = $describedBy( array_values( array_filter( [
        $hasDescription ? $controlId . '-description' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-checkbox' ) ) }}
    data-state="{{ $state }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
>
    <span data-scope="checkbox" data-part="box"{{ $partClass( 'box' ) }}>
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            type="checkbox"
            data-scope="checkbox"
            data-part="control"
            id="{{ $controlId }}"{{ $partClass( 'control' ) }}
            x-data
            x-effect="$el.indeterminate = {{ $indeterminate ? 'true' : 'false' }}"
            x-on:change="$el.closest('.apui-checkbox').dataset.state = $el.indeterminate ? 'indeterminate' : ($el.checked ? 'checked' : 'unchecked')"
            @if ( $checked ) checked @endif
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        <span data-scope="checkbox" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}>{{ $indicatorIcons() }}</span>
    </span>
    @if ( $hasLabel )
        <label data-scope="checkbox" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}</label>
    @endif
    @if ( $hasDescription )
        <p data-scope="checkbox" data-part="description" id="{{ $controlId }}-description"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="checkbox" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
