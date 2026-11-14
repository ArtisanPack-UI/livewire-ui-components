{{--
    Sparkline (#61). Markup contract: design-system wave 5, Sparkline.
    An SVG image with a label stating its first and latest values. The marks
    come from SparklineGeometry, the same coordinates React and Vue draw. The
    plot stays LTR (D-004).
--}}
@php
    $variant    = $variantValue();
    $geometry   = $geometry();
    $attributes = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\Sparkline::DEPRECATED_ATTRIBUTES );
@endphp
<svg
    {{ $attributes->class( $rootClasses( 'apui-sparkline' ) ) }}
    data-variant="{{ $variant }}"
    width="{{ $width }}"
    height="{{ $height }}"
    viewBox="0 0 {{ $width }} {{ $height }}"
    role="img"
    aria-label="{{ $accessibleLabel() }}"
>@if ( null !== $geometry['baseline'] )<line data-scope="sparkline" data-part="baseline"{{ $partClass( 'baseline' ) }} x1="0" x2="{{ $width }}" y1="{{ $geometry['baseline'] }}" y2="{{ $geometry['baseline'] }}" />@endif
@if ( '' !== $geometry['area'] )<path data-scope="sparkline" data-part="area"{{ $partClass( 'area' ) }} d="{{ $geometry['area'] }}" />@endif
@if ( '' !== $geometry['line'] )<path data-scope="sparkline" data-part="line"{{ $partClass( 'line' ) }} d="{{ $geometry['line'] }}" />@endif
@if ( null !== $geometry['dot'] )<circle data-scope="sparkline" data-part="dot"{{ $partClass( 'dot' ) }} cx="{{ $geometry['dot']['cx'] }}" cy="{{ $geometry['dot']['cy'] }}" r="2.5" />@endif
@foreach ( $geometry['bars'] as $bar )<rect data-scope="sparkline" data-part="bar"{{ $partClass( 'bar' ) }} x="{{ $bar['x'] }}" y="{{ $bar['y'] }}" width="{{ $bar['width'] }}" height="{{ $bar['height'] }}" rx="1" />@endforeach
</svg>
