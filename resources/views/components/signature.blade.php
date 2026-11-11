{{--
    Signature (#53). Markup contract: design-system Signature.
    The apuiSignature Alpine component draws on the ink canvas and turns every
    mode into one value, a PNG data URL, entangled with wire:model. The pad is
    wire:ignore, so a morph never resets the canvas. `class` lands on the root,
    `name` on the hidden input, every other attribute on the root.
--}}
@php
    $baseId      = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $modes       = $offeredModes();
    $current     = $initialMode();
    $strings     = $strings();
    $isEmpty     = null === $value || '' === $value;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $baseId . '-hint' : null,
        null !== $message ? $baseId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-signature' ) ) }}
    id="{{ $baseId }}"
    role="group"
    @if ( $hasLabel ) aria-labelledby="{{ $baseId }}-label" @else aria-label="{{ $strings['pad'] }}" @endif
    @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
    data-mode="{{ $current }}"
    data-pen-color="{{ $penColor }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    x-data="{{ $alpineData() }}"
    x-bind:data-mode="mode"
>
    @if ( $hasLabel )
        <span data-scope="signature" data-part="label" id="{{ $baseId }}-label"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="signature" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</span>
    @endif
    <div data-scope="signature" data-part="modes" role="group" aria-label="{{ $strings['modes'] }}" id="{{ $baseId }}-modes"{{ $partClass( 'modes' ) }}>
        @foreach ( $modes as $mode )
            <button
                type="button"
                data-scope="signature"
                data-part="mode"
                data-value="{{ $mode }}"
                id="{{ $baseId }}-mode-{{ $mode }}"{{ $partClass( 'mode' ) }}
                aria-pressed="{{ $mode === $current ? 'true' : 'false' }}"
                x-bind:aria-pressed="String(mode === {{ \Illuminate\Support\Js::from( $mode ) }})"
                x-on:click="setMode({{ \Illuminate\Support\Js::from( $mode ) }})"
                @if ( $disabled ) disabled @endif
            >{{ $strings[ $mode ] }}</button>
        @endforeach
    </div>
    <div data-scope="signature" data-part="pad" id="{{ $baseId }}-pad"{{ $partClass( 'pad' ) }} wire:ignore>
        <span data-scope="signature" data-part="line" aria-hidden="true"{{ $partClass( 'line' ) }}></span>
        <span data-scope="signature" data-part="mark" aria-hidden="true"{{ $partClass( 'mark' ) }}>×</span>
        <canvas
            data-scope="signature"
            data-part="ink"
            id="{{ $baseId }}-ink"{{ $partClass( 'ink' ) }}
            role="img"
            aria-label="{{ $strings['pad'] }}"
            x-ref="ink"
            @if ( 'type' === $current ) hidden @endif
            x-bind:hidden="mode === 'type'"
        ></canvas>
        <input
            type="text"
            data-scope="signature"
            data-part="typed"
            id="{{ $baseId }}-typed"{{ $partClass( 'typed' ) }}
            aria-label="{{ $strings['typed'] }}"
            autocomplete="name"
            maxlength="255"
            x-ref="typed"
            x-model="typed"
            x-on:input="type()"
            @if ( $required ) aria-required="true" @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( 'type' !== $current ) hidden @endif
            x-bind:hidden="mode !== 'type'"
        />
        @if ( in_array( 'upload', $modes, true ) )
            <label data-scope="signature" data-part="upload" id="{{ $baseId }}-upload"{{ $partClass( 'upload' ) }} @if ( 'upload' !== $current || ! $isEmpty ) hidden @endif x-bind:hidden="mode !== 'upload' || ! empty">
                <input type="file" accept="image/*" x-on:change="upload($event)" @if ( $disabled ) disabled @endif />{{ $iconSvg( 'upload' ) }}<span>{{ $strings['uploadTrigger'] }}</span>
            </label>
        @endif
        <div data-scope="signature" data-part="tools" id="{{ $baseId }}-tools"{{ $partClass( 'tools' ) }} @if ( 'type' === $current ) hidden @endif x-bind:hidden="mode === 'type'">
            <button
                type="button"
                data-scope="signature"
                data-part="undo"
                id="{{ $baseId }}-undo"{{ $partClass( 'undo' ) }}
                aria-label="{{ $strings['undo'] }}"
                disabled
                @if ( 'draw' !== $current ) hidden @endif
                x-bind:hidden="mode !== 'draw'"
                x-bind:disabled="! canUndo || {{ $disabled ? 'true' : 'false' }}"
                x-on:click="undo()"
            >{{ $iconSvg( 'undo-2' ) }}</button>
            <button
                type="button"
                data-scope="signature"
                data-part="clear"
                id="{{ $baseId }}-clear"{{ $partClass( 'clear' ) }}
                @if ( $isEmpty || $disabled ) disabled @endif
                x-bind:disabled="empty || {{ $disabled ? 'true' : 'false' }}"
                x-on:click="clear()"
            >{{ $iconSvg( 'eraser' ) }}<span>{{ $strings['clear'] }}</span></button>
        </div>
        <span data-scope="signature" data-part="placeholder" aria-hidden="true"{{ $partClass( 'placeholder' ) }}>{{ $placeholderText() }}</span>
    </div>
    <input type="hidden" {{ $attributes->only( 'name' ) }} value="{{ $value }}" x-bind:value="value" />
    @if ( $hasHint )
        <p data-scope="signature" data-part="hint" id="{{ $baseId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="signature" data-part="error" id="{{ $baseId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
    <span data-scope="signature" data-part="status" id="{{ $baseId }}-status" class="apui-sr-only" role="status" aria-live="polite" x-text="announcement"></span>
</div>
