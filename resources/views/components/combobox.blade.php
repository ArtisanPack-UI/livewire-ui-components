{{--
    Combobox (#54). Markup contract: design-system Combobox (D-018, D-019).
    The .apui-combobox root is the field and the apuiCombobox component. Zag's
    control and input parts render as box and control. Every part has a
    stable, server-rendered id and options carry wire:key (D-015). `class`
    and every other attribute land on the root, where value-change,
    search-change, open-change and create are dispatched; wire:model binds
    the value through $wire.entangle().
--}}
@php
    $baseId      = $baseId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $iconSlot    = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $hasIcon     = null !== $iconSlot || $filled( $leadingIcon );
    $emptySlot   = isset( $empty ) && $filled( $empty ) ? $empty : null;
    $itemSlot    = isset( $item ) && $item instanceof \Closure ? $item : null;
    $values      = $values();
    $options     = $normalizedOptions();
    $byValue     = array_column( $options, null, 'value' );
    $searchText  = $searchText();
    $createValue = $createValue();
    $isOpen      = $open;
    $state       = $isOpen ? 'open' : 'closed';
    $visible     = array_filter( $options, fn ( array $option ): bool => $matches( $option['label'] ) );
    $hasItems    = [] !== $visible || null !== $createValue;
    $labels      = $labels();
    $showSpinner = $loading || $remote || null !== $searchModel;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $partId( 'hint' ) : null,
        null !== $message ? $partId( 'error' ) : null,
    ] ) ) );
    $searchHandler = $searchHandler();
    $inputValue  = '' !== $searchText ? $searchText : ( ! $multiple && [] !== $values ? ( $byValue[ $values[0] ]['label'] ?? $values[0] ) : '' );
@endphp
<div
    {{ $attributes->whereDoesntStartWith( 'wire:model' )->except( [ 'id' ] )->class( $rootClasses( 'apui-combobox' ) ) }}
    id="{{ $baseId }}"
    data-scope="combobox"
    data-part="root"
    data-size="{{ $size }}"
    @if ( $multiple ) data-multiple @endif
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $loading ) data-loading @endif
    x-data="{{ $alpineData( $isInvalid ) }}"
    x-apui:root
    x-bind:data-loading="loading ? '' : null"
    @if ( null !== $searchHandler ) x-on:search-change.debounce.{{ max( 0, $debounce ) }}ms="{{ $searchHandler }}" @endif
>
    @if ( null !== $name && '' !== $name )
        {{-- The value for a native form: one hidden input per value. Client state, like the chips. --}}
        <span id="{{ $partId( 'form-values' ) }}" style="display: contents" wire:ignore>
            @foreach ( $values as $formValue )
                <input type="hidden" name="{{ $name }}" value="{{ $formValue }}" x-init="$el.remove()" />
            @endforeach
            <template x-for="entry in formValues" :key="entry">
                <input type="hidden" name="{{ $name }}" x-bind:value="entry" />
            </template>
        </span>
    @endif
    @if ( $hasLabel )
        <label data-scope="combobox" data-part="label" id="{{ $partId( 'label' ) }}" for="{{ $partId( 'control' ) }}"{{ $partClass( 'label' ) }} x-apui:label>{{ $content( $label ) }}@if ( $required )<span data-scope="combobox" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="combobox" data-part="box" id="{{ $partId( 'box' ) }}" data-state="{{ $state }}"{{ $partClass( 'box' ) }} x-apui:control>
        @if ( $hasIcon )
            <span data-scope="combobox" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>
        @endif
        @if ( $multiple )
            {{-- The chips are client state: the server's render shows them until Alpine takes over. --}}
            <span id="{{ $partId( 'chips' ) }}" style="display: contents" wire:ignore>
                @foreach ( $values as $chipValue )
                    @php $chipOption = $byValue[ $chipValue ] ?? [ 'label' => $chipValue, 'color' => null ]; @endphp
                    <span data-scope="combobox" data-part="chip" data-value="{{ $chipValue }}"@if ( null !== $chipOption['color'] ) data-color="{{ $chipOption['color'] }}"@endif{{ $partClass( 'chip' ) }} x-init="$el.remove()"><span>{{ $chipOption['label'] }}</span><button type="button" data-scope="combobox" data-part="chip-remove" aria-label="{{ $labelText( 'remove', [ 'label' => $chipOption['label'] ] ) }}"{{ $partClass( 'chipRemove' ) }}@if ( $disabled ) disabled @endif>{{ $iconSvg( 'x' ) }}</button></span>
                @endforeach
                <template x-for="chip in chips" :key="chip.value">
                    <span data-scope="combobox" data-part="chip" x-bind:data-value="chip.value" x-bind:data-color="chip.color ?? null"{{ $partClass( 'chip' ) }}><span x-text="chip.label"></span><button type="button" data-scope="combobox" data-part="chip-remove" x-bind:aria-label="removeLabel(chip.label)" x-on:click="remove(chip.value)"{{ $partClass( 'chipRemove' ) }}@if ( $disabled ) disabled @endif>{{ $iconSvg( 'x' ) }}</button></span>
                </template>
            </span>
        @endif
        <input
            data-scope="combobox"
            data-part="control"
            id="{{ $partId( 'control' ) }}"
            type="text"
            role="combobox"
            autocomplete="off"
            aria-autocomplete="list"
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
            aria-controls="{{ $partId( 'content' ) }}"
            placeholder="{{ $labels['placeholder'] }}"
            @if ( '' !== $inputValue ) value="{{ $inputValue }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            {{ $partClass( 'control' ) }}
            x-apui:input
        />
        @if ( $showSpinner )
            <span data-scope="combobox" data-part="spinner" id="{{ $partId( 'spinner' ) }}" aria-hidden="true"{{ $partClass( 'spinner' ) }}@if ( ! $loading ) hidden @endif x-bind:hidden="! loading"></span>
        @endif
        @if ( $clearable )
            <button type="button" data-scope="combobox" data-part="clear-trigger" id="{{ $partId( 'clear-trigger' ) }}" tabindex="-1" aria-label="{{ $labels['clear'] }}" aria-controls="{{ $partId( 'control' ) }}"{{ $partClass( 'clearTrigger' ) }}@if ( [] === $values ) hidden @endif @if ( $disabled ) disabled @endif x-apui:clear-trigger>{{ $iconSvg( 'x' ) }}</button>
        @endif
        <button type="button" data-scope="combobox" data-part="trigger" id="{{ $partId( 'trigger' ) }}" tabindex="-1" aria-label="{{ $labels['trigger'] }}" aria-expanded="{{ $isOpen ? 'true' : 'false' }}" data-state="{{ $state }}"{{ $partClass( 'trigger' ) }}@if ( $disabled ) disabled @endif x-apui:trigger>{{ $iconSvg( 'chevron-down' ) }}</button>
    </div>
    @if ( $hasHint )
        <p data-scope="combobox" data-part="hint" id="{{ $partId( 'hint' ) }}"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="combobox" data-part="error" id="{{ $partId( 'error' ) }}"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="combobox" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
        <div
            data-scope="combobox"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="listbox"
            tabindex="-1"
            data-state="{{ $state }}"
            @if ( $hasLabel ) aria-labelledby="{{ $partId( 'label' ) }}" @endif
            @if ( $multiple ) aria-multiselectable="true" @endif
            @if ( $loading ) aria-busy="true" data-loading @endif
            @if ( ! $isOpen ) hidden @endif
            {{ $partClass( 'content' ) }}
            x-apui:content
        >
            @foreach ( $options as $option )
                @php
                    $isChosen = in_array( $option['value'], $values, true );
                    $atMax    = $multiple && null !== $max && count( $values ) >= $max && ! $isChosen;
                    $itemData = array_filter( [
                        'value'       => $option['raw'],
                        'label'       => $option['label'],
                        'description' => $option['description'],
                        'icon'        => $option['icon'],
                        'color'       => $option['color'],
                        'disabled'    => $option['disabled'] ?: null,
                    ], static fn ( mixed $entry ): bool => null !== $entry );
                @endphp
                <div
                    data-scope="combobox"
                    data-part="item"
                    id="combobox:{{ $baseId }}:option:{{ $option['value'] }}"
                    wire:key="{{ $baseId }}-option-{{ $option['value'] }}"
                    role="option"
                    tabindex="-1"
                    data-value="{{ $option['value'] }}"
                    data-state="{{ $isChosen ? 'checked' : 'unchecked' }}"
                    aria-selected="{{ $isChosen ? 'true' : 'false' }}"
                    @if ( $disabled || $option['disabled'] || $atMax ) aria-disabled="true" data-disabled @endif
                    @if ( ! $matches( $option['label'] ) ) hidden @endif
                    {{ $partClass( 'item' ) }}
                    x-apui:item="@js( $itemData )"
                >
                    @if ( $multiple )
                        <span data-scope="combobox" data-part="item-indicator" aria-hidden="true"{{ $partClass( 'itemIndicator' ) }}@if ( ! $isChosen ) hidden @endif x-apui:item-indicator>{{ $iconSvg( 'check' ) }}</span>
                    @endif
                    @if ( null !== $option['icon'] )
                        <span data-scope="combobox" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( $option['icon'] ) }}</span>
                    @endif
                    @if ( null !== $itemSlot )
                        <span data-scope="combobox" data-part="item-text" data-state="{{ $isChosen ? 'checked' : 'unchecked' }}"{{ $partClass( 'itemText' ) }} x-apui:item-text>{{ $itemSlot( $option ) }}</span>
                    @else
                        <span data-scope="combobox" data-part="item-text" data-state="{{ $isChosen ? 'checked' : 'unchecked' }}"{{ $partClass( 'itemText' ) }} x-apui:item-text x-html="itemHtml(@js( $option['label'] ), @js( $option['description'] ))">{!! $markedLabel( $option['label'] ) !!}@if ( null !== $option['description'] )<span data-scope="combobox" data-part="item-description"{{ $partClass( 'itemDescription' ) }}>{{ $option['description'] }}</span>@endif</span>
                    @endif
                </div>
            @endforeach
            @if ( $allowCreate )
                <div
                    data-scope="combobox"
                    data-part="item"
                    data-create
                    wire:key="{{ $baseId }}-create"
                    role="option"
                    tabindex="-1"
                    aria-selected="false"
                    data-state="unchecked"
                    @if ( null !== $createValue ) data-value="{{ $createValue }}" @else hidden @endif
                    {{ $partClass( 'item' ) }}
                    x-apui:item="createOption"
                >
                    <span data-scope="combobox" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( 'plus' ) }}</span>
                    <span data-scope="combobox" data-part="item-text"{{ $partClass( 'itemText' ) }} x-apui:item-text x-text="createText">{{ $labelText( 'create', [ 'value' => (string) $createValue ] ) }}</span>
                </div>
            @endif
            <div data-scope="combobox" data-part="empty" id="{{ $partId( 'empty' ) }}"{{ $partClass( 'empty' ) }}@if ( $hasItems ) hidden @endif x-apui:empty>
                @if ( null !== $emptySlot )
                    <span x-show="! loading">{{ $content( $emptySlot ) }}</span>
                    <span x-show="loading" x-cloak>{{ $labels['loading'] }}</span>
                @else
                    <span x-text="emptyText">{{ $loading ? $labels['loading'] : $labelText( 'empty', [ 'search' => $searchText ] ) }}</span>
                @endif
            </div>
        </div>
    </div>
    @if ( $portal )</template>@endif
    <span data-scope="combobox" data-part="status" id="{{ $partId( 'status' ) }}" role="status" aria-live="polite"{{ $partClass( 'status' ) }} x-text="status"></span>
</div>
