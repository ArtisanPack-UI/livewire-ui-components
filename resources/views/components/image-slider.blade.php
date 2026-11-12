{{--
    ImageSlider (#61). Markup contract: design-system ImageSlider. The
    .apui-image-slider root is the carousel machine's root (role=region) and
    holds the frame (viewport) and the thumbnails. The server renders the
    machine's layout styles too (the track is a scroll-snapping grid), so the
    slider lays out before Alpine starts. Slides, dots and thumbs are named
    per index; every part has a stable, server-rendered id (D-015). The counter follows the widget's index on the client.
    `class` lands on the root with the other attributes; `wire:model` binds
    the slide shown.
--}}
@php
    $baseId  = $baseId();
    $entries = $entries();
    $count   = count( $entries );
    $current = $currentIndex();
    $userStyle = trim( (string) $attributes->get( 'style', '' ) );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-image-slider' ) ) }}
    id="{{ $baseId }}"
    data-scope="image-slider"
    data-part="root"
    role="region"
    aria-roledescription="carousel"
    aria-label="{{ $text( 'label' ) }}"
    data-orientation="horizontal"
    style="--apui-_image-slider-ratio: {{ $aspectRatio }}; --slides-per-page: 1; --slide-spacing: 0px; --slide-item-size: calc(100% / var(--slides-per-page) - var(--slide-spacing) * (var(--slides-per-page) - 1) / var(--slides-per-page)){{ '' !== $userStyle ? '; ' . rtrim( $userStyle, '; ' ) : '' }}"
    x-data="{{ $alpineData() }}"
    x-apui:root
>
    <div data-scope="image-slider" data-part="viewport" id="{{ $partId( 'viewport' ) }}"{{ $partClass( 'viewport' ) }}>
        <div data-scope="image-slider" data-part="item-group" id="{{ $partId( 'item-group' ) }}" data-orientation="horizontal" aria-live="{{ $autoplay ? 'off' : 'polite' }}" tabindex="0" style="display: grid; gap: var(--slide-spacing); scroll-snap-type: x mandatory; grid-auto-flow: column; scrollbar-width: none; overscroll-behavior-x: contain; grid-auto-columns: var(--slide-item-size); overflow-x: auto"{{ $partClass( 'itemGroup' ) }} x-apui:item-group>
            @foreach ( $entries as $i => $entry )
                <div
                    data-scope="image-slider"
                    data-part="item"
                    id="{{ $partId( 'item-' . $i ) }}"
                    role="group"
                    data-index="{{ $i }}"
                    data-orientation="horizontal"
                    style="flex: 0 0 auto; max-width: 100%; scroll-snap-align: start"
                    aria-roledescription="slide"
                    aria-label="{{ $format( 'slide', $i ) }}"
                    @if ( $i !== $current ) aria-hidden="true" @endif
                    wire:key="{{ $baseId }}-item-{{ $i }}"
                    {{ $partClass( 'item' ) }}
                    x-apui:item="{ index: {{ $i }} }"
                >
                    <img data-scope="image-slider" data-part="image" src="{{ $entry['src'] }}" alt="{{ $entry['alt'] }}"@if ( $i > 0 ) loading="lazy"@endif decoding="async"{{ $partClass( 'image' ) }}>
                    @if ( '' !== $entry['caption'] || '' !== $entry['description'] )
                        <div data-scope="image-slider" data-part="caption"{{ $partClass( 'caption' ) }}>
                            @if ( '' !== $entry['caption'] )
                                <p data-scope="image-slider" data-part="title"{{ $partClass( 'title' ) }}>{{ $entry['caption'] }}</p>
                            @endif
                            @if ( '' !== $entry['description'] )
                                <p data-scope="image-slider" data-part="description"{{ $partClass( 'description' ) }}>{{ $entry['description'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <span data-scope="image-slider" data-part="progress-text" id="{{ $partId( 'progress-text' ) }}"{{ $partClass( 'progressText' ) }} x-apui:progress-text x-text="progressText">{{ $format( 'counter', $current ) }}</span>
        <x-artisanpack-button
            variant="soft"
            color="neutral"
            icon-only
            leading-icon="chevron-left"
            :aria-label="$text( 'previous' )"
            :aria-controls="$partId( 'item-group' )"
            :id="$partId( 'prev-trigger' )"
            :class="$classes['prevTrigger'] ?? null"
            data-scope="image-slider"
            data-part="prev-trigger"
            x-apui:prev-trigger=""
        />
        <x-artisanpack-button
            variant="soft"
            color="neutral"
            icon-only
            leading-icon="chevron-right"
            :aria-label="$text( 'next' )"
            :aria-controls="$partId( 'item-group' )"
            :id="$partId( 'next-trigger' )"
            :class="$classes['nextTrigger'] ?? null"
            data-scope="image-slider"
            data-part="next-trigger"
            x-apui:next-trigger=""
        />
        <div data-scope="image-slider" data-part="indicator-group" id="{{ $partId( 'indicator-group' ) }}"{{ $partClass( 'indicatorGroup' ) }} x-apui:indicator-group>
            @foreach ( $entries as $i => $entry )
                <button
                    type="button"
                    data-scope="image-slider"
                    data-part="indicator"
                    id="{{ $partId( 'indicator-' . $i ) }}"
                    aria-label="{{ $format( 'indicator', $i ) }}"
                    @if ( $i === $current ) aria-current="true" data-current @endif
                    {{ $partClass( 'indicator' ) }}
                    x-apui:indicator="{ index: {{ $i }} }"
                ></button>
            @endforeach
        </div>
        @if ( $autoplay )
            <button
                type="button"
                data-scope="image-slider"
                data-part="autoplay-trigger"
                id="{{ $partId( 'autoplay-trigger' ) }}"
                aria-label="{{ $text( 'pause' ) }}"
                {{ $partClass( 'autoplayTrigger' ) }}
                x-apui:autoplay-trigger
            ><template x-if="playing">{{ $iconSvg( 'pause' ) }}</template><template x-if="! playing">{{ $iconSvg( 'play' ) }}</template></button>
        @endif
    </div>
    @if ( $thumbnails && $count > 0 )
        <div data-scope="image-slider" data-part="thumbs" id="{{ $partId( 'thumbs' ) }}"{{ $partClass( 'thumbs' ) }}>
            @foreach ( $entries as $i => $entry )
                <button
                    type="button"
                    data-scope="image-slider"
                    data-part="thumb"
                    id="{{ $partId( 'thumb-' . $i ) }}"
                    @if ( $i === $current ) aria-current="true" @endif
                    {{ $partClass( 'thumb' ) }}
                    x-apui:thumb="{ index: {{ $i }} }"
                ><img src="{{ $entry['src'] }}" alt="{{ $entry['alt'] }}" loading="lazy" decoding="async"></button>
            @endforeach
        </div>
    @endif
</div>
