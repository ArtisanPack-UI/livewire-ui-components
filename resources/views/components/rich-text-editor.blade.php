{{--
    RichTextEditor (#53). Markup contract: design-system RichTextEditor.
    The apuiRichTextEditor Alpine component runs the toolbar on TinyMCE (inline,
    when the page loads it) or on the built-in contentEditable engine, and
    entangles the value with wire:model. The box is wire:ignore, so a morph
    never resets what is being edited. `class` lands on the root, `name` on the
    hidden input, every other attribute on the root.
--}}
@php
    $baseId      = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $strings     = $strings();
    $items       = $toolbarItems();
    $html        = $contentHtml();
    $isEmpty     = '' === trim( strip_tags( $html, '<img>' ) );
    $editable    = ! $disabled && ! $readonly;
    $hasFooter   = $counter || null !== $maxLength;
    $hasHolder   = $filled( $placeholder );
    $firstButton = true;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $baseId . '-hint' : null,
        null !== $message ? $baseId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-rich-text-editor' ) ) }}
    id="{{ $baseId }}"
    data-output="{{ $outputFormat() }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
    x-data="{{ $alpineData() }}"
>
    @if ( $hasLabel )
        <span data-scope="rich-text-editor" data-part="label" id="{{ $baseId }}-label"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="rich-text-editor" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</span>
    @endif
    <div data-scope="rich-text-editor" data-part="box" id="{{ $baseId }}-box"{{ $partClass( 'box' ) }} wire:ignore>
        <div
            data-scope="rich-text-editor"
            data-part="toolbar"
            id="{{ $baseId }}-toolbar"{{ $partClass( 'toolbar' ) }}
            role="toolbar"
            aria-label="{{ $strings['toolbar'] }}"
            aria-controls="{{ $baseId }}-content"
            x-ref="toolbar"
            x-on:keydown="toolbarKeydown($event)"
        >
            @foreach ( $items as $item )
                @if ( '|' === $item )
                    <span data-scope="rich-text-editor" data-part="divider" role="separator" aria-orientation="vertical"{{ $partClass( 'divider' ) }}></span>
                @else
                    @php $toggle = $isToggle( $item ); @endphp
                    <button
                        type="button"
                        data-scope="rich-text-editor"
                        data-part="button"
                        data-command="{{ $item }}"
                        id="{{ $baseId }}-{{ $item }}"{{ $partClass( 'button' ) }}
                        aria-label="{{ $strings[ $item ] }}"
                        tabindex="{{ $firstButton ? '0' : '-1' }}"
                        @if ( $toggle ) aria-pressed="false" x-bind:aria-pressed="String(!! active.{{ $item }})" @endif
                        @if ( ! $editable ) disabled @endif
                        x-on:mousedown.prevent
                        x-on:click="run('{{ $item }}')"
                    >{{ $iconSvg( $commandIcon( $item ) ) }}</button>
                    @php $firstButton = false; @endphp
                @endif
            @endforeach
        </div>
        <div
            data-scope="rich-text-editor"
            data-part="content"
            id="{{ $baseId }}-content"{{ $partClass( 'content' ) }}
            contenteditable="{{ $editable ? 'true' : 'false' }}"
            role="textbox"
            aria-multiline="true"
            @if ( $hasLabel ) aria-labelledby="{{ $baseId }}-label" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( $required ) aria-required="true" @endif
            @if ( $readonly ) aria-readonly="true" @endif
            @if ( $disabled ) aria-disabled="true" @endif
            @if ( $hasHolder ) aria-placeholder="{{ $placeholder }}" @endif
            x-ref="content"
        >{!! $html !!}</div>
        @if ( $hasHolder )
            <p data-scope="rich-text-editor" data-part="placeholder" aria-hidden="true"{{ $partClass( 'placeholder' ) }} @if ( ! $isEmpty ) hidden @endif x-bind:hidden="! empty">{{ $placeholder }}</p>
        @endif
        @if ( $hasFooter )
            <div data-scope="rich-text-editor" data-part="footer"{{ $partClass( 'footer' ) }}>
                <span x-bind:hidden="engine !== 'native'">{{ $strings['shortcuts'] }}</span>
                <span data-scope="rich-text-editor" data-part="count"{{ $partClass( 'count' ) }} x-text="count">{{ $countLabel( $html ) }}</span>
            </div>
        @endif
    </div>
    <input type="hidden" {{ $attributes->only( 'name' ) }} value="{{ $currentValue() }}" x-bind:value="value" />
    @if ( $hasHint )
        <p data-scope="rich-text-editor" data-part="hint" id="{{ $baseId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="rich-text-editor" data-part="error" id="{{ $baseId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
