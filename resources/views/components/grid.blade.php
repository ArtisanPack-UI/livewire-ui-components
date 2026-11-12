{{--
    Grid (#58). Markup contract: design-system Grid, a token-driven layout
    primitive. data-columns, data-gap, data-align and data-justify; the minimum
    item width is a custom property on the root.
--}}
@php
    $userStyle = rtrim( trim( (string) $attributes->get( 'style', '' ) ), '; ' );
    $style     = implode( '; ', array_filter( [ $rootStyle(), $userStyle ], fn ( $part ) => null !== $part && '' !== $part ) );
@endphp
<div
    {{ $attributes->except( 'style' )->class( $rootClasses( 'apui-grid' ) ) }}
    @if ( isset( $id ) && is_string( $id ) && '' !== $id ) id="{{ $id }}" @endif
    data-columns="{{ $columns }}"
    data-gap="{{ $gap }}"
    data-align="{{ $align }}"
    data-justify="{{ $justify }}"
    @if ( '' !== $style ) style="{{ $style }}" @endif
>{{ $slot ?? '' }}</div>
