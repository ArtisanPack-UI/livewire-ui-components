{{--
    Password (#53). Markup contract: design-system form-text wave, Password.
    `class` lands on the root; every other attribute lands on the native input.
    Alpine holds the reveal state and the value; the strength meter and the
    requirements follow it as people type. The server renders the same state
    for the initial value. The reveal button keeps one name and toggles
    aria-pressed.
--}}
@php
    $controlId       = $controlId();
    $message         = $errorMessage( $errors ?? null );
    $isInvalid       = $invalid || null !== $message;
    $hasLabel        = $filled( $label );
    $hasHint         = $filled( $hint );
    $iconSlot        = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $hasIcon         = null !== $iconSlot || $filled( $leadingIcon );
    $text            = $value ?? '';
    $rules           = $rules();
    $level           = $strengthLevel( $text );
    $describedBy     = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        [] !== $rules ? $controlId . '-requirements' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-password' ) ) }}
    data-size="{{ $size }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
    x-data="{
        revealed: false,
        init() { const input = this.$root.querySelector('[data-part=control]'); const read = () => { this.value = input.value }; this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset); if (window.Livewire?.hook) this.unhook = window.Livewire.hook('commit', ({ succeed }) => succeed(() => queueMicrotask(read))) }, destroy() { this.form?.removeEventListener('reset', this.onReset); if ('function' === typeof this.unhook) this.unhook() }, 
        value: @js( $text ),
        labels: @js( $labels ),
        rules: @js( $rules ),
        get level() {
            const v = this.value;
            if ('' === v) return 0;
            const length = [ ...v ].length;
            const points = (length >= 8) + (length >= 12) + (/[a-z]/.test(v) && /[A-Z]/.test(v)) + /\d/.test(v) + /[^A-Za-z0-9]/.test(v);
            return Math.max(1, Math.min(4, points - 1));
        },
        get levelName() { return ['', this.labels.weak, this.labels.fair, this.labels.good, this.labels.strong][this.level]; },
        met(i) { try { return new RegExp(this.rules[i].pattern, 'u').test(this.value) } catch (e) { return false } },
    }"
>
    @if ( $hasLabel )
        <label data-scope="password" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="password" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="password" data-part="box"{{ $partClass( 'box' ) }}>
        @if ( $hasIcon )
            <span data-scope="password" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>
        @endif
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            data-scope="password"
            data-part="control"
            id="{{ $controlId }}"
            type="password"
            autocomplete="{{ $autocomplete }}"{{ $partClass( 'control' ) }}
            x-bind:type="revealed ? 'text' : 'password'"
            x-init="value = $el.value"
            x-on:input="value = $el.value"
            x-on:change="value = $el.value"
            @if ( null !== $placeholder ) placeholder="{{ $placeholder }}" @endif
            @if ( null !== $value ) value="{{ $value }}" @endif
            @if ( $disabled ) disabled @endif
            @if ( $readonly ) readonly @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        <button
            type="button"
            data-scope="password"
            data-part="reveal"
            aria-label="{{ $revealLabel }}"
            aria-pressed="false"
            aria-controls="{{ $controlId }}"{{ $partClass( 'reveal' ) }}
            x-bind:aria-pressed="revealed ? 'true' : 'false'"
            x-on:click="revealed = ! revealed"
            @if ( $disabled ) disabled @endif
        >{{ $revealIcons() }}</button>
    </div>
    @if ( $strengthMeter )
        <div data-scope="password" data-part="strength" data-level="{{ $level }}" aria-hidden="true"{{ $partClass( 'strength' ) }} x-bind:data-level="level">@for ( $i = 0; $i < 4; $i++ )<span data-scope="password" data-part="segment"{{ $partClass( 'segment' ) }}></span>@endfor</div>
        <div data-scope="password" data-part="strengthLabel"{{ $partClass( 'strengthLabel' ) }}><span>{{ $labels['strength'] }}</span><span aria-live="polite" x-text="levelName">{{ $levelName( $level ) }}</span></div>
    @endif
    @if ( [] !== $rules )
        <ul data-scope="password" data-part="requirements" id="{{ $controlId }}-requirements"{{ $partClass( 'requirements' ) }}>
            @foreach ( $rules as $index => $rule )
                @php( $isMet = $meets( $rule['pattern'], $text ) )
                <li data-scope="password" data-part="requirement"{{ $partClass( 'requirement' ) }} @if ( $isMet ) data-met @endif x-bind:data-met="met({{ $index }}) ? '' : null">{{ $requirementIcons() }}{{ $rule['label'] }}<span class="apui-sr-only" x-text="' (' + (met({{ $index }}) ? labels.met : labels.unmet) + ')'"> ({{ $isMet ? $labels['met'] : $labels['unmet'] }})</span></li>
            @endforeach
        </ul>
    @endif
    @if ( $hasHint )
        <p data-scope="password" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="password" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
