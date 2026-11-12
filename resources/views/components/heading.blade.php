{{--
    Heading (#58). Markup contract: design-system wave, Heading.
    h1–h6 from level, with data-level and data-size (size defaults to level). The
    eyebrow is a paragraph just before the heading, so it never adds a level.
--}}
@php
    $tag         = 'h' . $levelValue();
    $eyebrowSlot = isset( $eyebrow ) && $eyebrow instanceof \Illuminate\View\ComponentSlot && $filled( $eyebrow ) ? $eyebrow : null;
    $hasEyebrow  = null !== $eyebrowSlot || $filled( $eyebrow );
@endphp
@if ( $hasEyebrow )<p data-scope="heading" data-part="eyebrow"{{ $partClass( 'eyebrow' ) }}>{{ $content( $eyebrowSlot ?? $eyebrow ) }}</p>@endif
<{{ $tag }} {{ $attributes->class( $rootClasses( 'apui-heading' ) ) }} data-level="{{ $levelValue() }}" data-size="{{ $sizeValue() }}">{{ isset( $slot ) ? $content( $slot ) : '' }}</{{ $tag }}>
