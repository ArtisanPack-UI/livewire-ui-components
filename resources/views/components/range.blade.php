{{--
    Range (#53). Markup contract: design-system form-text wave, Range.
    `class` lands on the root. One thumb: every other attribute, wire:model
    included, lands on the native input, which binds natively. Dual: the
    root is x-modelable, so wire:model binds the [from, to] array, and the
    other attributes land on both inputs. Alpine keeps --apui-_from/--apui-_to,
    the bubbles and aria-valuetext in step, and stops the thumbs crossing.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    [ $from, $to ] = $bounds();
    $marks       = $markList();
    $showBubble  = 'never' !== $showValue;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
    $model       = $attributes->whereStartsWith( 'wire:model' );
    $passed      = $dual
        ? $attributes->except( [ 'class', 'id', 'aria-describedby', 'aria-label', 'aria-labelledby', 'name', ...array_keys( $model->getAttributes() ) ] )
        : $attributes->except( [ 'class', 'id', 'aria-describedby' ] );
    // With dual, both thumbs post into name[]; an aria-label joins each thumb's
    // name, and other aria-* go on the start thumb only (as in React and Vue).
    $dualName    = $dualName();
    $startOnly   = $dual ? $passed->whereStartsWith( 'aria-' ) : $passed->only( [] );
    $bothThumbs  = $dual ? $passed->whereDoesntStartWith( 'aria-' ) : $passed;
    $nameId      = $thumbNameId( $controlId, $hasLabel );
    $initial     = $dual ? [ $from, $to ] : ( is_numeric( $value ) ? $to : null );
    $config      = [ 'min' => $min, 'max' => $max, 'dual' => $dual, 'format' => $format, 'thumbLabels' => $thumbLabels ];
    $endId       = $dual ? $controlId . '-end' : $controlId;
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-range' ) ) }}
    @if ( $dual ) {{ $model }} x-modelable="value" @endif
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-show-value="{{ $showValue }}"
    @if ( $dual ) data-dual @endif
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    style="--apui-_from: {{ $percent( $from ) }}%; --apui-_to: {{ $percent( $to ) }}%"
    x-data="{
        ...@js( $config ),
        value: @js( $initial ),
        get from() { return this.dual ? Number(this.value?.[0] ?? this.min) : this.min },
        get to() { return this.dual ? Number(this.value?.[1] ?? this.max) : Number(this.value ?? (this.min + (this.max - this.min) / 2)) },
        pct(v) { return this.max <= this.min ? 0 : Math.max(0, Math.min(100, (v - this.min) / (this.max - this.min) * 100)) },
        fmt(v) { return null === this.format ? String(v) : this.format.replaceAll('{value}', String(v)) },
        // One thumb binds natively, so a server change to the property, or a
        // form reset, only sets the input's value; read it back after each
        // Livewire commit and after a reset.
        init() {
            const inputs = [ ...this.$root.querySelectorAll('[data-part=control]') ];
            const read = () => { this.value = this.dual ? inputs.map((input) => Number(input.value)) : Number(inputs[inputs.length - 1].value) };
            this.form = inputs[0]?.form;
            this.onReset = () => setTimeout(read);
            this.form?.addEventListener('reset', this.onReset);
            if (! this.dual && window.Livewire?.hook) this.unhook = window.Livewire.hook('commit', ({ succeed }) => succeed(() => queueMicrotask(read)));
        },
        destroy() { this.form?.removeEventListener('reset', this.onReset); if ('function' === typeof this.unhook) this.unhook() },
        set(thumb, el) {
            const v = Number(el.value);
            if (! this.dual) { this.value = v; return }
            if ('start' === thumb) { const next = Math.min(v, this.to); el.value = next; this.value = [ next, this.to ] }
            else { const next = Math.max(v, this.from); el.value = next; this.value = [ this.from, next ] }
        },
    }"
    x-bind:style="{ '--apui-_from': pct(from) + '%', '--apui-_to': pct(to) + '%' }"
>
    @if ( $hasLabel )
        <label data-scope="range" data-part="label" id="{{ $controlId }}-label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}</label>
    @endif
    <div data-scope="range" data-part="track"{{ $partClass( 'track' ) }}>
        <div data-scope="range" data-part="fill"{{ $partClass( 'fill' ) }}></div>
        @if ( $dual )
            @if ( ! $hasLabel && ! $attributes->has( 'aria-labelledby' ) && $filled( $attributes->get( 'aria-label' ) ) )
                <span class="apui-sr-only" id="{{ $controlId }}-aria-label">{{ $attributes->get( 'aria-label' ) }}</span>
            @endif
            <span class="apui-sr-only" id="{{ $controlId }}-start-label">{{ $thumbLabels['start'] }}</span>
            <span class="apui-sr-only" id="{{ $controlId }}-end-label">{{ $thumbLabels['end'] }}</span>
            <input {{ $bothThumbs }} {{ $startOnly }}
                @if ( null !== $dualName ) name="{{ $dualName }}" @endif
                type="range"
                data-scope="range"
                data-part="control"
                data-thumb="start"
                id="{{ $controlId }}"
                min="{{ $min }}"
                max="{{ $max }}"
                step="{{ $step }}"
                value="{{ $from }}"{{ $partClass( 'control' ) }}
                aria-labelledby="{{ null !== $nameId ? $nameId . ' ' : '' }}{{ $controlId }}-start-label"
                @if ( null !== $format ) aria-valuetext="{{ $formatted( $from ) }}" x-bind:aria-valuetext="fmt(from)" @endif
                x-effect="$el.value = from"
                x-on:input="set('start', $el)"
                @if ( $disabled ) disabled @endif
                @if ( $isInvalid ) aria-invalid="true" @endif
                @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            />
            <span data-scope="range" data-part="thumb" data-thumb="start" aria-hidden="true"{{ $partClass( 'thumb' ) }}>@if ( $showBubble )<span data-scope="range" data-part="bubble"{{ $partClass( 'bubble' ) }} x-text="fmt(from)">{{ $formatted( $from ) }}</span>@endif</span>
        @endif
        <input {{ $bothThumbs }}
            @if ( null !== $dualName ) name="{{ $dualName }}" @endif
            type="range"
            data-scope="range"
            data-part="control"
            data-thumb="end"
            id="{{ $endId }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"{{ $partClass( 'control' ) }}
            @if ( null !== $initial ) value="{{ $to }}" @endif
            @if ( $dual ) aria-labelledby="{{ null !== $nameId ? $nameId . ' ' : '' }}{{ $controlId }}-end-label" x-effect="$el.value = to" @else x-init="$nextTick(() => { value = Number($el.value) })" @endif
            @if ( null !== $format ) aria-valuetext="{{ $formatted( $to ) }}" x-bind:aria-valuetext="fmt(to)" @endif
            x-on:input="set('end', $el)"
            @if ( $disabled ) disabled @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        <span data-scope="range" data-part="thumb" data-thumb="end" aria-hidden="true"{{ $partClass( 'thumb' ) }}>@if ( $showBubble )<span data-scope="range" data-part="bubble"{{ $partClass( 'bubble' ) }} x-text="fmt(to)">{{ $formatted( $to ) }}</span>@endif</span>
    </div>
    @if ( [] !== $marks )
        <div data-scope="range" data-part="marks" aria-hidden="true"{{ $partClass( 'marks' ) }}>
            @foreach ( $marks as $mark )
                <span data-scope="range" data-part="mark" style="--apui-_at: {{ $mark['at'] }}%"{{ $partClass( 'mark' ) }}>{{ $mark['label'] }}</span>
            @endforeach
        </div>
    @endif
    @if ( $hasHint )
        <p data-scope="range" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="range" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
