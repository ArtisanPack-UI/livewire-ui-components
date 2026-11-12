{{--
    TimelineItem (#61). Markup contract: design-system wave 5, Timeline.
    One event: the marker (aria-hidden, with data-color), the head (title or
    the default slot, then the time element), the body and the card. 2.x
    subtitle still works, as time.
--}}
@php
    $time       = $filled( $time ) ? $time : $attributes->get( 'subtitle' );
    $attributes = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\TimelineItem::DEPRECATED_ATTRIBUTES );
    $headHtml   = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $title ?? '' );
    $color      = $colorValue();
@endphp
<li
    {{ $attributes->when( $filled( $classes['item'] ?? null ), fn ( $bag ) => $bag->class( $classes['item'] ) ) }}
    data-scope="timeline"
    data-part="item"
>
    <span data-scope="timeline" data-part="marker"{{ $partClass( 'marker' ) }}@if ( null !== $color ) data-color="{{ $color }}"@endif aria-hidden="true">@if ( $filled( $icon ) ){{ $iconSvg( $icon ) }}@endif</span>
    <div data-scope="timeline" data-part="head"{{ $partClass( 'head' ) }}>{{ $headHtml }}@if ( $filled( $time ) )<time data-scope="timeline" data-part="time"{{ $partClass( 'time' ) }}@if ( $filled( $datetime ) ) datetime="{{ $datetime }}"@endif>{{ $time }}</time>@endif</div>
    @if ( $filled( $description ) )
        <div data-scope="timeline" data-part="body"{{ $partClass( 'body' ) }}>{{ $description }}</div>
    @endif
    @if ( $filled( $card ) )
        <div data-scope="timeline" data-part="card"{{ $partClass( 'card' ) }}>{{ $content( $card ) }}</div>
    @endif
</li>
