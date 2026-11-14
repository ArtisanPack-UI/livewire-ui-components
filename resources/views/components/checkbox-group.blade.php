{{--
    CheckboxGroup (#53). Markup contract: design-system CheckboxGroup; each option is
    the pilot Checkbox's markup (.apui-checkbox). `class` lands on the root; every other
    attribute (wire:model, name) lands on every option's native checkbox, so an array
    property binds natively. Alpine keeps select all (checked, or indeterminate while
    some are checked) and each option's data-state in step, and select all checks or
    clears the enabled options with a change event each, which wire:model reads.
--}}
@php
    $groupId     = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $allState    = $selectAllState();
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $groupId . '-hint' : null,
        null !== $message ? $groupId . '-error' : null,
    ] ) ) );
@endphp
<fieldset
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-checkbox-group' ) ) }}
    id="{{ $groupId }}"
    data-orientation="{{ $orientation }}"
    data-variant="{{ $variant }}"
    data-columns="{{ $columns }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled disabled @endif
    @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
    x-data="{
        options() { return [ ...$el.querySelectorAll('[data-part=items] input[type=checkbox]') ] },
        sync() {
            const boxes = this.options().filter((box) => ! box.hasAttribute('data-option-disabled'))
            boxes.concat(this.options()).forEach((box) => { box.closest('.apui-checkbox').dataset.state = box.checked ? 'checked' : 'unchecked' })
            const all = $el.querySelector('[data-part=select-all] input')
            if (! all) return
            const count = boxes.filter((box) => box.checked).length
            all.checked = count > 0 && count === boxes.length
            all.indeterminate = count > 0 && count < boxes.length
            all.closest('.apui-checkbox').dataset.state = all.indeterminate ? 'indeterminate' : (all.checked ? 'checked' : 'unchecked')
        },
        toggleAll(on) {
            this.options().filter((box) => ! box.disabled && ! box.hasAttribute('data-option-disabled') && box.checked !== on).forEach((box) => {
                box.checked = on
                box.dispatchEvent(new Event('change', { bubbles: true }))
            })
            this.sync()
        },
    }"
    x-init="sync()"
    x-on:change="$event.target.closest('[data-part=select-all]') ? toggleAll($event.target.checked) : sync()"
>
    @if ( $hasLabel )
        <legend data-scope="checkbox-group" data-part="label"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}</legend>
    @endif
    @if ( $selectAll )
        <div data-scope="checkbox-group" data-part="select-all"{{ $partClass( 'selectAll' ) }}>
            <div class="apui-checkbox" data-state="{{ $allState }}" @if ( $disabled ) data-disabled @endif>
                <span data-scope="checkbox" data-part="box">
                    <input type="checkbox" data-scope="checkbox" data-part="control" id="{{ $groupId }}-all"
                        @if ( 'checked' === $allState ) checked @endif
                        @if ( $disabled ) disabled @endif
                    />
                    <span data-scope="checkbox" data-part="indicator" aria-hidden="true">{{ $indicatorIcons() }}</span>
                </span>
                <label data-scope="checkbox" data-part="label" for="{{ $groupId }}-all">{{ $selectAllText() }}</label>
            </div>
        </div>
    @endif
    <div data-scope="checkbox-group" data-part="items"{{ $partClass( 'items' ) }}>
        @foreach ( $normalizedOptions() as $index => $option )
            @php
                $itemId         = $groupId . '-' . $index;
                $itemDisabled   = $disabled || $option['disabled'];
                $itemChecked    = $isChecked( $option['value'] );
                $hasDescription = null !== $option['description'];
            @endphp
            <div class="apui-checkbox" wire:key="{{ $itemId }}" data-state="{{ $itemChecked ? 'checked' : 'unchecked' }}" @if ( $isInvalid ) data-invalid @endif @if ( $itemDisabled ) data-disabled @endif>
                <span data-scope="checkbox" data-part="box">
                    <input {{ $attributes->except( [ 'class', 'id', 'value', 'aria-describedby' ] ) }}
                        type="checkbox"
                        data-scope="checkbox"
                        data-part="control"
                        id="{{ $itemId }}"
                        value="{{ $option['value'] }}"
                        @if ( $itemChecked ) checked @endif
                        @if ( $itemDisabled ) disabled @endif
                        @if ( $option['disabled'] ) data-option-disabled @endif
                        @if ( $isInvalid ) aria-invalid="true" @endif
                        @if ( $hasDescription ) aria-describedby="{{ $itemId }}-description" @endif
                    />
                    <span data-scope="checkbox" data-part="indicator" aria-hidden="true">{{ $indicatorIcons() }}</span>
                </span>
                <label data-scope="checkbox" data-part="label" for="{{ $itemId }}">{{ $option['label'] }}</label>
                @if ( $hasDescription )
                    <p data-scope="checkbox" data-part="description" id="{{ $itemId }}-description">{{ $option['description'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
    @if ( $hasHint )
        <p data-scope="checkbox-group" data-part="hint" id="{{ $groupId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="checkbox-group" data-part="error" id="{{ $groupId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</fieldset>
