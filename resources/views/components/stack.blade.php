{{--
    Stack (#58). Markup contract: design-system Stack, a token-driven layout
    primitive: data-orientation, data-gap, data-align, data-justify, data-wrap.
--}}
<div
    {{ $attributes->class( $rootClasses( 'apui-stack' ) ) }}
    data-orientation="{{ $orientation }}"
    data-gap="{{ $gap }}"
    data-align="{{ $align }}"
    data-justify="{{ $justify }}"
    @if ( $wrap ) data-wrap @endif
>{{ $slot ?? '' }}</div>
