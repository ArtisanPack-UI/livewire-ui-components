{{--
    Skeleton (#60). Markup contract: design-system wave #58–#62, Skeleton.
    One aria-hidden placeholder shape; the loading container sets aria-busy.
--}}
@php
    $style = $sizeStyle( $attributes->get( 'style' ) );
@endphp
<span
    {{ $attributes->except( 'style' )->class( [ 'apui-skeleton' ] ) }}
    data-scope="skeleton"
    data-part="root"
    data-shape="{{ $shape }}"
    aria-hidden="true"
    @if ( '' !== $style ) style="{{ $style }}" @endif
></span>
