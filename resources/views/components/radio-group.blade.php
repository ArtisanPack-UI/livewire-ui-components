{{--
    RadioGroup (#53). Markup contract: design-system RadioGroup.
    `class` lands on the root; every other attribute (wire:model, name) lands on every
    native radio, so wire:model binds natively. The radios share one name, so the
    platform supplies roving focus and arrow keys (which follow the text direction).
--}}
@php
    $groupId     = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $name        = $groupName();
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $groupId . '-hint' : null,
        null !== $message ? $groupId . '-error' : null,
    ] ) ) );
@endphp
<fieldset
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-radio-group' ) ) }}
    id="{{ $groupId }}"
    data-orientation="{{ $orientation }}"
    data-variant="{{ $variant }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled disabled @endif
    @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
>
    @if ( $hasLabel )
        <legend data-scope="radio-group" data-part="label"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="radio-group" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</legend>
    @endif
    <div data-scope="radio-group" data-part="items"{{ $partClass( 'items' ) }}>
        @foreach ( $normalizedOptions() as $index => $option )
            @php
                $itemId         = $groupId . '-' . $index;
                $itemDisabled   = $disabled || $option['disabled'];
                $segmented      = 'segmented' === $variant;
                $hasDescription = null !== $option['description'] && ! $segmented;
            @endphp
            <label data-scope="radio-group" data-part="item" wire:key="{{ $itemId }}"{{ $partClass( 'item' ) }} @if ( $itemDisabled ) data-disabled @endif>
                <span data-scope="radio-group" data-part="box"{{ $partClass( 'box' ) }}>
                    <input {{ $attributes->except( [ 'class', 'id', 'name', 'value', 'aria-describedby' ] ) }}
                        type="radio"
                        data-scope="radio-group"
                        data-part="control"
                        id="{{ $itemId }}"
                        name="{{ $name }}"
                        value="{{ $option['value'] }}"{{ $partClass( 'control' ) }}
                        @if ( $isSelected( $option['value'] ) ) checked @endif
                        @if ( $itemDisabled ) disabled @endif
                        @if ( $required ) required @endif
                        @if ( $isInvalid ) aria-invalid="true" @endif
                        @if ( $hasDescription ) aria-describedby="{{ $itemId }}-description" @endif
                    />
                    <span data-scope="radio-group" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}></span>
                </span>
                <span data-scope="radio-group" data-part="item-text"{{ $partClass( 'itemText' ) }}>{{ $option['label'] }}</span>
                @if ( $hasDescription )
                    <span data-scope="radio-group" data-part="item-description" id="{{ $itemId }}-description"{{ $partClass( 'itemDescription' ) }}>{{ $option['description'] }}</span>
                @endif
                @if ( null !== $option['meta'] && ! $segmented )
                    <span data-scope="radio-group" data-part="item-meta"{{ $partClass( 'itemMeta' ) }}>{{ $option['meta'] }}</span>
                @endif
            </label>
        @endforeach
    </div>
    @if ( $hasHint )
        <p data-scope="radio-group" data-part="hint" id="{{ $groupId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="radio-group" data-part="error" id="{{ $groupId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</fieldset>
