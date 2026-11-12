{{--
    ImageGallery (#61). Markup contract: design-system ImageGallery. A grid of
    figures whose buttons open the lightbox, the apuiLightbox widget: a dialog
    whose .apui-lightbox panel is the machine's content part (D-019). Every
    part has a stable, server-rendered id (D-015); the closed lightbox still
    renders, hidden. The stage image, counter, thumbs and download follow the
    widget's index on the client. `class` lands on the root, with the other
    attributes; `wire:model` binds the lightbox's open state.
--}}
@php
    $baseId  = $baseId();
    $entries = $entries();
    $count   = count( $entries );
    $index   = $currentIndex();
    $isOpen  = $startsOpen();
    $state   = $isOpen ? 'open' : 'closed';
    $current = $entries[ $index ] ?? null;
    $hasBox  = $lightbox && $count > 0;
    $userStyle = trim( (string) $attributes->get( 'style', '' ) );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-image-gallery' ) ) }}
    id="{{ $baseId }}"
    data-scope="image-gallery"
    data-part="root"
    @if ( $featured ) data-featured @endif
    style="--apui-_image-gallery-columns: {{ $columns }}{{ '' !== $userStyle ? '; ' . rtrim( $userStyle, '; ' ) : '' }}"
    @if ( $hasBox ) x-data="{{ $alpineData() }}" @endif
>
    <div data-scope="image-gallery" data-part="grid" id="{{ $partId( 'grid' ) }}"{{ $partClass( 'grid' ) }}>
        @foreach ( $entries as $i => $entry )
            <figure data-scope="image-gallery" data-part="item" id="{{ $partId( 'item-' . $i ) }}"{{ $partClass( 'item' ) }}>
                @if ( $hasBox )
                    <button
                        type="button"
                        data-scope="image-gallery"
                        data-part="trigger"
                        id="{{ $partId( 'trigger-' . $i ) }}"
                        aria-haspopup="dialog"
                        {{ $partClass( 'trigger' ) }}
                        x-apui:trigger="{ index: {{ $i }} }"
                    ><img data-scope="image-gallery" data-part="image" src="{{ $entry['src'] }}" alt="{{ $entry['alt'] }}" loading="lazy" decoding="async"{{ $partClass( 'image' ) }}></button>
                @else
                    <img data-scope="image-gallery" data-part="image" src="{{ $entry['src'] }}" alt="{{ $entry['alt'] }}" loading="lazy" decoding="async"{{ $partClass( 'image' ) }}>
                @endif
                @if ( '' !== $entry['caption'] )
                    <figcaption data-scope="image-gallery" data-part="caption"{{ $partClass( 'caption' ) }}>{{ $entry['caption'] }}</figcaption>
                @endif
            </figure>
        @endforeach
    </div>
    @if ( $hasBox )
        @if ( $portal )<template x-teleport="body">@endif
        <div id="{{ $partId( 'layer' ) }}" style="display: contents">
            <div data-scope="image-gallery" data-part="backdrop" id="{{ $partId( 'backdrop' ) }}" data-state="{{ $state }}"{{ $partClass( 'backdrop' ) }} x-apui:backdrop @if ( ! $isOpen ) hidden @endif></div>
            <div data-scope="image-gallery" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
                <div
                    class="{{ trim( 'apui-lightbox ' . ( $classes['content'] ?? '' ) ) }}"
                    data-scope="image-gallery"
                    data-part="content"
                    id="{{ $partId( 'content' ) }}"
                    role="dialog"
                    tabindex="-1"
                    data-state="{{ $state }}"
                    aria-labelledby="{{ $partId( 'title' ) }}"
                    @if ( $modal ) aria-modal="true" @endif
                    @if ( ! $isOpen ) hidden @endif
                    x-apui:content
                >
                    <div data-scope="image-gallery" data-part="bar" id="{{ $partId( 'bar' ) }}"{{ $partClass( 'bar' ) }}>
                        <h2 data-scope="image-gallery" data-part="title" id="{{ $partId( 'title' ) }}" aria-live="polite"{{ $partClass( 'title' ) }} x-apui:title x-text="title">{{ $titleFor( $index ) }}</h2>
                        @if ( $downloadable )
                            <x-artisanpack-button
                                variant="ghost"
                                color="neutral"
                                size="sm"
                                icon-only
                                leading-icon="download"
                                :href="$current['src'] ?? null"
                                download
                                :aria-label="$label( 'download' )"
                                :id="$partId( 'download' )"
                                :class="$classes['download'] ?? null"
                                data-scope="image-gallery"
                                data-part="download"
                                x-apui:download=""
                            />
                        @endif
                        <x-artisanpack-button
                            variant="ghost"
                            color="neutral"
                            size="sm"
                            icon-only
                            leading-icon="x"
                            :aria-label="$label( 'close' )"
                            :id="$partId( 'close-trigger' )"
                            :class="$classes['closeTrigger'] ?? null"
                            data-scope="image-gallery"
                            data-part="close-trigger"
                            x-apui:close-trigger=""
                        />
                    </div>
                    <div data-scope="image-gallery" data-part="stage" id="{{ $partId( 'stage' ) }}"{{ $partClass( 'stage' ) }}>
                        <img data-scope="image-gallery" data-part="image" id="{{ $partId( 'stage-image' ) }}" src="{{ $current['src'] ?? '' }}" alt="{{ $current['alt'] ?? '' }}"{{ $partClass( 'image' ) }} x-apui:image>
                        <x-artisanpack-button
                            variant="soft"
                            color="neutral"
                            icon-only
                            leading-icon="chevron-left"
                            :aria-label="$label( 'previous' )"
                            :id="$partId( 'prev-trigger' )"
                            :class="$classes['prevTrigger'] ?? null"
                            :disabled="$count < 2"
                            data-scope="image-gallery"
                            data-part="prev-trigger"
                            x-apui:prev-trigger=""
                        />
                        <x-artisanpack-button
                            variant="soft"
                            color="neutral"
                            icon-only
                            leading-icon="chevron-right"
                            :aria-label="$label( 'next' )"
                            :id="$partId( 'next-trigger' )"
                            :class="$classes['nextTrigger'] ?? null"
                            :disabled="$count < 2"
                            data-scope="image-gallery"
                            data-part="next-trigger"
                            x-apui:next-trigger=""
                        />
                    </div>
                    <div data-scope="image-gallery" data-part="thumbs" id="{{ $partId( 'thumbs' ) }}" role="group" aria-label="{{ $label( 'thumbs' ) }}"{{ $partClass( 'thumbs' ) }}>
                        @foreach ( $entries as $i => $entry )
                            <button
                                type="button"
                                data-scope="image-gallery"
                                data-part="thumb"
                                id="{{ $partId( 'thumb-' . $i ) }}"
                                @if ( $i === $index ) aria-current="true" @endif
                                {{ $partClass( 'thumb' ) }}
                                x-apui:thumb="{ index: {{ $i }} }"
                            ><img src="{{ $entry['src'] }}" alt="{{ $entry['alt'] }}" loading="lazy" decoding="async"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if ( $portal )</template>@endif
    @endif
</div>
