{{--
    Popover (#58). Markup contract: design-system Popover (D-019).
    The .apui-popover panel is Zag's content part (role=dialog); parts are scoped
    popover. Every part has a stable, server-rendered id, so Livewire's morph
    keeps the panel (D-015). The closed panel still renders, hidden. `class`
    lands on the panel; every other attribute lands on the apuiPopover element,
    where open-change is dispatched.
--}}
@php
    $baseId     = $baseId();
    $isOpen     = $isOpen();
    $state      = $isOpen ? 'open' : 'closed';
    $placement  = $resolvedPlacement();
    // Deprecated 2.x spelling: the content slot is the body.
    $body       = isset( $slot ) && $slot->hasActualContent() ? $slot : ( isset( $content ) && $content instanceof \Illuminate\Contracts\Support\Htmlable ? $content : null );
    $hasBody    = null !== $body && $filled( $body );
    $hasTitle   = $filled( $title );
    $hasActions = $filled( $actions );
    $hasTrigger = $filled( $trigger );
    $ownTrigger = $hasTrigger ? $elementTrigger( $trigger ) : null;
    $host       = $hostAttributes()->except( [ ...\ArtisanPack\LivewireUiComponents\View\Components\Popover::DROPPED_ATTRIBUTES, 'aria-label' ] );
@endphp
<div {{ $host }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    @if ( null !== $ownTrigger )
        {{ $ownTrigger }}
    @elseif ( $hasTrigger )
        <x-artisanpack-button
            variant="outline"
            color="neutral"
            :id="$partId( 'trigger' )"
            :class="$classes['trigger'] ?? null"
            data-scope="popover"
            data-part="trigger"
            aria-haspopup="dialog"
            :aria-expanded="$isOpen ? 'true' : 'false'"
            :aria-controls="$partId( 'content' )"
            :data-state="$state"
            x-apui:trigger=""
        >{{ $markup( $trigger ) }}</x-artisanpack-button>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="popover" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
        <div
            {{ $attributes->only( [ 'class', 'aria-label' ] )->class( $rootClasses( 'apui-popover' ) ) }}
            data-scope="popover"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="dialog"
            tabindex="-1"
            data-state="{{ $state }}"
            data-placement="{{ $placement }}"
            @if ( $hasTitle ) aria-labelledby="{{ $partId( 'title' ) }}" @endif
            @if ( ! $isOpen ) hidden @endif
            x-apui:content
        >
            @if ( $arrow )
                <span data-scope="popover" data-part="arrow" id="{{ $partId( 'arrow' ) }}" aria-hidden="true"{{ $partClass( 'arrow' ) }} x-apui:arrow></span>
            @endif
            @if ( $hasTitle )
                <h3 data-scope="popover" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }} x-apui:title>{{ $markup( $title ) }}</h3>
            @endif
            @if ( $hasBody )
                <div data-scope="popover" data-part="body" id="{{ $partId( 'body' ) }}"{{ $partClass( 'body' ) }}>{{ $markup( $body ) }}</div>
            @endif
            @if ( $hasActions )
                <div data-scope="popover" data-part="actions" id="{{ $partId( 'actions' ) }}"{{ $partClass( 'actions' ) }}>{{ $markup( $actions ) }}</div>
            @endif
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
