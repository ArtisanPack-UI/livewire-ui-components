{{--
    Carousel (#65). Markup contract: design-system Carousel, on the carousel
    machine scoped `carousel` (D-041). The .apui-carousel root is the
    machine's root (role=region). With a title, a header holds the heading
    and the outline previous and next buttons; without one, soft buttons
    overlay the viewport's edges. The server renders the machine's layout
    styles (the track is a scroll-snapping grid; per-view above one adds a
    peek), so the carousel lays out before Alpine starts. Slides and dots are
    named per index; every part has a stable, server-rendered id (D-015).
    Each slide is a Card unless @scope('slide', $slide) renders it ($loop->index
    is its index).
    `class` lands on the root with the other attributes; `wire:model` binds
    the position shown.
--}}
@php
    $baseId    = $baseId();
    $count     = count( $slides );
    $pages     = $pageCount();
    $current   = $currentIndex();
    $slideSlot = $__data['slide'] ?? null;
    $hasTitle  = $filled( $title );
    $userStyle = trim( (string) $attributes->get( 'style', '' ) );
    $navProps  = [
        'variant' => $hasTitle ? 'outline' : 'soft',
        'canPrev' => $loop || $current > 0,
        'canNext' => $loop || $current < $pages - 1,
    ];
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-carousel' ) ) }}
    id="{{ $baseId }}"
    data-scope="carousel"
    data-part="root"
    role="region"
    aria-roledescription="carousel"
    aria-label="{{ $text( 'label' ) }}"
    data-orientation="horizontal"
    style="{{ $layoutStyle() }}{{ '' !== $userStyle ? '; ' . rtrim( $userStyle, '; ' ) : '' }}"
    x-data="{{ $alpineData() }}"
    x-apui:root
>
    @if ( $hasTitle )
        <div data-scope="carousel" data-part="header" id="{{ $partId( 'header' ) }}"{{ $partClass( 'header' ) }}>
            <h{{ $headingLevel }} data-scope="carousel" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }}>{{ $title }}</h{{ $headingLevel }}>
            @include( 'livewire-ui-components::components.partials.carousel-triggers' )
        </div>
    @endif
    <div data-scope="carousel" data-part="viewport" id="{{ $partId( 'viewport' ) }}"{{ $partClass( 'viewport' ) }}>
        <div data-scope="carousel" data-part="item-group" id="{{ $partId( 'item-group' ) }}" data-orientation="horizontal" aria-live="{{ $autoplay ? 'off' : 'polite' }}" tabindex="0" style="display: grid; gap: var(--slide-spacing); scroll-snap-type: x mandatory; grid-auto-flow: column; scrollbar-width: none; overscroll-behavior-x: contain; grid-auto-columns: var(--slide-item-size); overflow-x: auto"{{ $partClass( 'itemGroup' ) }} x-apui:item-group>
            @foreach ( $slides as $i => $slide )
                <div
                    data-scope="carousel"
                    data-part="item"
                    id="{{ $partId( 'item-' . $i ) }}"
                    role="group"
                    data-index="{{ $i }}"
                    data-orientation="horizontal"
                    style="flex: 0 0 auto; max-width: 100%; scroll-snap-align: start"
                    aria-roledescription="slide"
                    aria-label="{{ $format( 'slide', $i ) }}"
                    @if ( $inView( $i ) ) data-inview @else aria-hidden="true" @endif
                    wire:key="{{ $baseId }}-item-{{ $i }}"
                    {{ $partClass( 'item' ) }}
                    x-apui:item="{ index: {{ $i }} }"
                >
                    @if ( $slideSlot instanceof \Closure )
                        {{ $slideSlot( $slide, $i ) }}
                    @else
                        @php $cardProps = $card( $slide ); @endphp
                        <x-artisanpack-card
                            :media="$cardProps['media']"
                            :media-alt="$cardProps['mediaAlt']"
                            :title="$cardProps['title']"
                            :description="$cardProps['description']"
                            :href="$cardProps['href']"
                        />
                    @endif
                </div>
            @endforeach
        </div>
        @unless ( $hasTitle )
            @include( 'livewire-ui-components::components.partials.carousel-triggers' )
        @endunless
        @if ( $autoplay )
            <button
                type="button"
                data-scope="carousel"
                data-part="autoplay-trigger"
                id="{{ $partId( 'autoplay-trigger' ) }}"
                aria-label="{{ $text( 'pause' ) }}"
                {{ $partClass( 'autoplayTrigger' ) }}
                x-apui:autoplay-trigger
            ><template x-if="playing">{{ $iconSvg( 'pause' ) }}</template><template x-if="! playing">{{ $iconSvg( 'play' ) }}</template></button>
        @endif
    </div>
    @if ( $pages > 1 )
        <div data-scope="carousel" data-part="indicator-group" id="{{ $partId( 'indicator-group' ) }}"{{ $partClass( 'indicatorGroup' ) }} x-apui:indicator-group>
            @for ( $i = 0; $i < $pages; $i++ )
                <button
                    type="button"
                    data-scope="carousel"
                    data-part="indicator"
                    id="{{ $partId( 'indicator-' . $i ) }}"
                    aria-label="{{ $format( 'indicator', $i ) }}"
                    @if ( $i === $current ) aria-current="true" data-current @endif
                    {{ $partClass( 'indicator' ) }}
                    x-apui:indicator="{ index: {{ $i }} }"
                ></button>
            @endfor
        </div>
    @endif
</div>
