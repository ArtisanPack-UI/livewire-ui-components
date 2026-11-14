{{--
    Pin (#53). Markup contract: design-system form-text wave, Pin.
    `class` lands on the root; every other attribute lands on the one native
    input, which lies transparent over the aria-hidden cells, so SMS autofill
    (autocomplete=one-time-code), paste and screen readers treat the code as
    one field. Alpine drops characters the type doesn't allow (re-firing
    input so wire:model sees the clean value), mirrors the value into the
    cells and dispatches `complete` from the input with the code once every cell is filled.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $cells       = $cells();
    $filledCount = count( array_filter( $cells, static fn ( string $cell ): bool => '' !== $cell ) );
    $active      = min( $filledCount, $length - 1 );
    $numeric     = 'alphanumeric' !== $type;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-pin' ) ) }}
    data-size="{{ $size }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    x-data="{
        value: @js( mb_substr( $value ?? '', 0, $length ) ),
        length: {{ $length }},
        init() { const input = this.$root.querySelector('[data-part=control]'); const read = () => { this.value = this.clean(input.value) }; this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset); if (window.Livewire?.hook) this.unhook = window.Livewire.hook('commit', ({ succeed }) => succeed(() => queueMicrotask(read))) }, destroy() { this.form?.removeEventListener('reset', this.onReset); if ('function' === typeof this.unhook) this.unhook() }, 
        mask: @js( $mask ),
        allowed: {{ $numeric ? '/[^0-9]/g' : '/[^A-Za-z0-9]/g' }},
        clean(text) { return [ ...String(text).replace(this.allowed, '') ].slice(0, this.length).join('') },
        char(i) { const c = [ ...this.value ][i] ?? ''; return '' !== c && this.mask ? '•' : c },
        get active() { return Math.min([ ...this.value ].length, this.length - 1) },
        write(el, text) {
            const next = this.clean(text);
            if (el.value !== next) { el.value = next; el.dispatchEvent(new Event('input', { bubbles: true })); return false }
            const changed = next !== this.value;
            this.value = next;
            return changed && [ ...next ].length === this.length;
        },
    }"
>
    @if ( $hasLabel )
        <label data-scope="pin" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="pin" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="pin" data-part="cells"{{ $partClass( 'cells' ) }}>
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            data-scope="pin"
            data-part="control"
            id="{{ $controlId }}"
            type="{{ $mask ? 'password' : 'text' }}"
            inputmode="{{ $numeric ? 'numeric' : 'text' }}"
            autocomplete="one-time-code"
            autocapitalize="off"
            spellcheck="false"
            maxlength="{{ $length }}"{{ $partClass( 'control' ) }}
            @if ( $numeric ) pattern="[0-9]*" @endif
            x-init="$nextTick(() => { value = clean($el.value) })"
            x-on:input="if (write($el, $el.value)) $dispatch('complete', value)"
            x-on:paste.prevent="write($el, ($event.clipboardData || window.clipboardData).getData('text'))"
            @if ( null !== $value ) value="{{ $value }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        @foreach ( $cells as $index => $cell )
            <span data-scope="pin" data-part="cell" aria-hidden="true"{{ $partClass( 'cell' ) }} @if ( '' !== $cell ) data-filled @endif @if ( $index === $active ) data-active @endif x-bind:data-filled="'' !== char({{ $index }}) ? '' : null" x-bind:data-active="active === {{ $index }} ? '' : null" x-text="char({{ $index }})">{{ $cell }}</span>
            @if ( $separatorAfter( $index ) )
                <span data-scope="pin" data-part="separator" aria-hidden="true"{{ $partClass( 'separator' ) }}></span>
            @endif
        @endforeach
    </div>
    @if ( $hasHint )
        <p data-scope="pin" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="pin" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
