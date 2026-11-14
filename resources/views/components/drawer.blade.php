{{--
    Drawer (#58). Markup contract: design-system Drawer (D-019).
    The .apui-drawer panel is the dialog machine's content part, scoped drawer.
    Every part has a stable, server-rendered id, so Livewire's morph keeps the
    panel (D-015). The closed panel still renders, hidden. `class` lands on the
    panel; every other attribute lands on the apuiDrawer element, where
    open-change is dispatched.
--}}
@php
    $baseId         = $baseId();
    $isOpen         = $isOpen();
    $state          = $isOpen ? 'open' : 'closed';
    $side           = $resolvedSide();
    // Deprecated 2.x spellings: subtitle → description, the actions slot → footer.
    $description    = $filled( $description ) ? $description : $attributes->get( 'subtitle' );
    $footer         = $filled( $footer ) ? $footer : ( $actions ?? null );
    $hasTitle       = $filled( $title );
    $hasDescription = $filled( $description );
    $hasFooter      = $filled( $footer );
    $hasTrigger     = $filled( $trigger );
    $hasBody        = isset( $slot ) && $slot->hasActualContent();
    $hasHeader      = $hasTitle || $hasDescription || ! $hideClose;
    $host           = $hostAttributes()->except( \ArtisanPack\LivewireUiComponents\View\Components\Drawer::DROPPED_ATTRIBUTES );
@endphp
<div {{ $host }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    @if ( $hasTrigger )
        <x-artisanpack-button
            variant="outline"
            color="neutral"
            :id="$partId( 'trigger' )"
            :class="$classes['trigger'] ?? null"
            data-scope="drawer"
            data-part="trigger"
            aria-haspopup="dialog"
            :aria-expanded="$isOpen ? 'true' : 'false'"
            :aria-controls="$partId( 'content' )"
            :data-state="$state"
            x-apui:trigger=""
        >{{ $content( $trigger ) }}</x-artisanpack-button>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div id="{{ $partId( 'layer' ) }}" style="display: contents">
        <div data-scope="drawer" data-part="backdrop" id="{{ $partId( 'backdrop' ) }}" data-state="{{ $state }}"{{ $partClass( 'backdrop' ) }} x-apui:backdrop @if ( ! $isOpen ) hidden @endif></div>
        <div data-scope="drawer" data-part="positioner" id="{{ $partId( 'positioner' ) }}" data-side="{{ $side }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
            <aside
                {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-drawer' ) ) }}
                data-scope="drawer"
                data-part="content"
                id="{{ $partId( 'content' ) }}"
                role="dialog"
                tabindex="-1"
                data-state="{{ $state }}"
                data-side="{{ $side }}"
                data-size="{{ $size }}"
                data-surface="{{ $surface }}"
                @if ( $modal ) aria-modal="true" @endif
                @if ( $hasTitle ) aria-labelledby="{{ $partId( 'title' ) }}" @endif
                @if ( $hasDescription ) aria-describedby="{{ $partId( 'description' ) }}" @endif
                @if ( ! $isOpen ) hidden @endif
                x-apui:content
            >
                @if ( 'bottom' === $side )
                    <div data-scope="drawer" data-part="handle" id="{{ $partId( 'handle' ) }}" aria-hidden="true"{{ $partClass( 'handle' ) }}></div>
                @endif
                @if ( $hasHeader )
                    <div data-scope="drawer" data-part="header" id="{{ $partId( 'header' ) }}"{{ $partClass( 'header' ) }}>
                        @if ( $hasTitle )
                            <h2 data-scope="drawer" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }} x-apui:title>{{ $content( $title ) }}</h2>
                        @endif
                        @if ( $hasDescription )
                            <p data-scope="drawer" data-part="description" id="{{ $partId( 'description' ) }}"{{ $partClass( 'description' ) }} x-apui:description>{{ $content( $description ) }}</p>
                        @endif
                        @if ( ! $hideClose )
                            <x-artisanpack-button
                                variant="ghost"
                                color="neutral"
                                size="sm"
                                icon-only
                                leading-icon="x"
                                :aria-label="$closeTriggerLabel()"
                                :id="$partId( 'close-trigger' )"
                                :class="$classes['closeTrigger'] ?? null"
                                data-scope="drawer"
                                data-part="close-trigger"
                                x-apui:close-trigger=""
                            />
                        @endif
                    </div>
                @endif
                @if ( $hasBody )
                    <div data-scope="drawer" data-part="body" id="{{ $partId( 'body' ) }}"{{ $partClass( 'body' ) }}>{{ $content( $slot ) }}</div>
                @endif
                @if ( $hasFooter )
                    <div data-scope="drawer" data-part="footer" id="{{ $partId( 'footer' ) }}"{{ $partClass( 'footer' ) }}>{{ $content( $footer ) }}</div>
                @endif
            </aside>
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
