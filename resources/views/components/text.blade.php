{{--
    Text (#58). Markup contract: design-system wave, Text.
    p, span or div with data-size, data-tone and data-weight. truncate and clamp
    cut the text; plain-text content is copied to title so it stays available.
--}}
@php
    $tag   = $tag();
    $html  = isset( $slot ) ? $content( $slot ) : $content( '' );
    $title = $fullTextTitle( $html->toHtml() );
    $lines = $clampValue();
    $style = null !== $lines ? '--apui-text-clamp: ' . $lines : null;
@endphp
<{{ $tag }}
    {{ $attributes->class( $rootClasses( 'apui-text' ) )->merge( null !== $style ? [ 'style' => $style ] : [] ) }}
    data-size="{{ $sizeValue() }}"
    data-tone="{{ $toneValue() }}"
    data-weight="{{ $weightValue() }}"
    @if ( $truncate ) data-truncate @endif
    @if ( null !== $lines ) data-clamp="{{ $lines }}" @endif
    @if ( null !== $title ) title="{{ $title }}" @endif
>{{ $html }}</{{ $tag }}>
