{{--
    Code (#65). Markup contract: design-system Code. A block: the header
    (file, language badge, copy button and its hidden live region) and the
    labeled, focusable pre with one span per line (data-n, data-highlight).
    Inline: a code element. The copy button runs on apuiClipboard and copies
    the rendered lines. The header mirrors in RTL; the code stays LTR (D-004).
--}}
@php
    $source      = $source( $slot ?? null );
    $attributes  = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\Code::DEPRECATED_ATTRIBUTES );
    $highlighted = $highlighted();
@endphp
@if ( $inline )
<code {{ $attributes->class( $rootClasses( 'apui-code' ) ) }} data-scope="code" data-part="root" data-inline>{{ $source }}</code>
@else
<div
    {{ $attributes->class( $rootClasses( 'apui-code' ) ) }}
    data-scope="code"
    data-part="root"
    @if ( $numbered ) data-numbered @endif
    @if ( $copyable ) x-data="{{ $alpineData() }}" @endif
>
    @if ( $hasHeader() )
        <div data-scope="code" data-part="header"{{ $partClass( 'header' ) }}>
            @if ( $filled( $fileName ) )
                <span data-scope="code" data-part="file"{{ $partClass( 'file' ) }}>{{ $fileName }}</span>
            @endif
            @if ( $filled( $language ) )
                <span class="{{ trim( 'apui-badge ' . ( $classes['language'] ?? '' ) ) }}" data-scope="code" data-part="language" data-color="neutral" data-variant="outline" data-size="sm">{{ $language }}</span>
            @endif
            @if ( $copyable )
                <button
                    type="button"
                    class="{{ trim( 'apui-button ' . ( $classes['copy'] ?? '' ) ) }}"
                    data-scope="code"
                    data-part="copy"
                    data-variant="ghost"
                    data-color="neutral"
                    data-size="xs"
                    data-state="idle"
                    x-bind:data-state="status"
                    x-on:click="copyCode()"
                >
                    <span data-scope="button" data-part="icon" aria-hidden="true" x-html="icon">{{ $iconSvg( 'copy' ) }}</span>
                    <span data-scope="button" data-part="label" x-text="label">{{ $copyLabel }}</span>
                </button>
                <span class="{{ trim( 'apui-sr-only ' . ( $classes['status'] ?? '' ) ) }}" data-scope="code" data-part="status" role="status" x-text="message"></span>
            @endif
        </div>
    @endif
    <pre data-scope="code" data-part="pre" role="region" aria-label="{{ $regionLabel() }}" tabindex="0"{{ $partClass( 'pre' ) }}><code>@foreach ( $lines( $source ) as $index => $line )<span data-scope="code" data-part="line" data-n="{{ $index + 1 }}"@if ( isset( $highlighted[ $index + 1 ] ) ) data-highlight @endif{{ $partClass( 'line' ) }}>{{ $line }}</span>@endforeach</code></pre>
</div>
@endif
