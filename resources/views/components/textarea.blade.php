{{--
    Textarea (#53). Markup contract: design-system form-text wave, Textarea.
    `class` lands on the root; every other attribute lands on the native
    textarea. With maxLength, Alpine keeps the aria-hidden counter in step and
    fills the polite live region only near the limit.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $text        = $value ?? '';
    $hasCount    = null !== $maxLength;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-textarea' ) ) }}
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
    @if ( $autoResize ) data-auto-resize @endif
    @if ( $hasCount )
        x-data="{ init() { const input = this.$root.querySelector('[data-part=control]'); const read = () => { this.sync(input) }; this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset); if (window.Livewire?.hook) this.unhook = window.Livewire.hook('commit', ({ succeed }) => succeed(() => queueMicrotask(read))) }, destroy() { this.form?.removeEventListener('reset', this.onReset); if ('function' === typeof this.unhook) this.unhook() }, max: {{ $maxLength }}, within: {{ $announceWithin() }}, label: @js( $remainingLabel ), length: {{ mb_strlen( $text ) }}, announce: @js( $announcement( $text ) ), sync(el) { this.length = [ ...el.value ].length; const left = Math.max(0, this.max - this.length); const next = left <= this.within ? this.label.replace('{count}', String(left)) : ''; if (next !== this.announce) this.announce = next } }"
        x-init="$nextTick(() => sync($el.querySelector('textarea')))"
    @endif
>
    @if ( $hasLabel )
        <label data-scope="textarea" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="textarea" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="textarea" data-part="box"{{ $partClass( 'box' ) }}>
        <textarea {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            data-scope="textarea"
            data-part="control"
            id="{{ $controlId }}"
            rows="{{ $rows }}"{{ $partClass( 'control' ) }}
            @if ( $hasCount ) maxlength="{{ $maxLength }}" x-on:input="sync($el)" @endif
            @if ( null !== $placeholder ) placeholder="{{ $placeholder }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( $readonly ) readonly @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        >{{ $text }}</textarea>
        @if ( $hasCount )
            <span data-scope="textarea" data-part="count" aria-hidden="true"{{ $partClass( 'count' ) }} x-text="length + ' / ' + max">{{ mb_strlen( $text ) }} / {{ $maxLength }}</span>
            <span class="apui-sr-only" aria-live="polite" x-text="announce">{{ $announcement( $text ) }}</span>
        @endif
    </div>
    @if ( $hasHint )
        <p data-scope="textarea" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="textarea" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
