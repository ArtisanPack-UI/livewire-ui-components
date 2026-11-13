{{--
    Clipboard (#65). Markup contract: design-system Clipboard. The root
    (a span, or a div for the field) holds the field's value box, a neutral
    copy button and the role=status live region (the tooltip part, shown
    under the icon button). The apuiClipboard Alpine component swaps the
    label to "Copied" for the timeout and fills the live region, on success
    or failure; the button copies its value attribute, so a value Livewire
    changes copies the new one (and clears a stale "Copied"). The root
    dispatches copied (with the value written; named apart from the native
    copy event) or error. After a failure the button and icon variants show
    the value in the field's box, selected, so it can be copied by hand.
--}}
@php
    $variant  = $variantValue();
    $size     = $sizeValue();
    $iconOnly = 'icon' === $variant;
    $tag      = 'field' === $variant ? 'div' : 'span';
@endphp
<{{ $tag }}
    {{ $attributes->class( $rootClasses( 'apui-clipboard' ) ) }}
    data-scope="clipboard"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-state="idle"
    x-data="{{ $alpineData() }}"
    x-bind:data-state="status"
>
    @if ( 'field' === $variant )
        <span data-scope="clipboard" data-part="field"{{ $partClass( 'field' ) }}><span data-scope="clipboard" data-part="value"{{ $partClass( 'value' ) }}>{{ $value }}</span></span>
    @else
        <template x-if="null !== manual"><span data-scope="clipboard" data-part="field"{{ $partClass( 'field' ) }}><span data-scope="clipboard" data-part="value"{{ $partClass( 'value' ) }} x-text="manual"></span></span></template>
    @endif
    <button
        type="button"
        class="{{ trim( 'apui-button ' . ( $classes['button'] ?? '' ) ) }}"
        data-scope="clipboard"
        data-part="button"
        data-variant="{{ $iconOnly ? 'ghost' : 'outline' }}"
        data-color="neutral"
        data-size="{{ $size }}"
        data-state="idle"
        @if ( $iconOnly ) data-icon-only aria-label="{{ $label }}" x-bind:aria-label="label" @endif
        value="{{ $value }}"
        x-bind:data-state="status"
        x-on:click="const value = $el.value; if ( await copy( value ) ) { $dispatch( 'copied', value ) } else { $dispatch( 'error' ) }"
    >
        <span data-scope="button" data-part="icon" aria-hidden="true" x-html="icon">{{ $iconSvg( 'copy' ) }}</span>
        @unless ( $iconOnly )
            <span data-scope="button" data-part="label" x-text="label">{{ $label }}</span>
        @endunless
    </button>
    <span data-scope="clipboard" data-part="tooltip" role="status"{{ $partClass( 'tooltip' ) }}><span aria-hidden="true" x-show="copied" style="display: none">{{ $iconSvg( 'check' ) }}</span><span x-text="message"></span></span>
</{{ $tag }}>
