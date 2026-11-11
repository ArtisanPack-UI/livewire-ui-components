{{--
    Dialog (#49). Markup contract: design-system pilot, Dialog (D-019).
    The .apui-dialog panel is the machine's content part. Every part has a stable,
    server-rendered id, so Livewire's morph keeps the panel (D-015). The closed
    panel still renders, hidden. `class` lands on the panel; every other
    attribute lands on the apuiDialog element, where open-change is dispatched.
--}}
@php
    $baseId         = $baseId();
    $isOpen         = $isOpen();
    $state          = $isOpen ? 'open' : 'closed';
    $iconSlot       = $icon instanceof \Illuminate\Contracts\Support\Htmlable ? $icon : null;
    $hasIcon        = null !== $iconSlot ? $filled( $iconSlot ) : $filled( $icon );
    $hasTitle       = $filled( $title );
    $hasDescription = $filled( $description );
    $hasFooter      = $filled( $footer );
    $hasTrigger     = $filled( $trigger );
    $hasBody        = isset( $slot ) && $slot->hasActualContent();
    $hasHeader      = $hasIcon || $hasTitle || $hasDescription || ! $hideClose;
@endphp
<div {{ $hostAttributes() }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    @if ( $hasTrigger )
        <x-artisanpack-button
            variant="outline"
            color="neutral"
            :id="$partId( 'trigger' )"
            :class="$classes['trigger'] ?? null"
            data-scope="dialog"
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
        <div data-scope="dialog" data-part="backdrop" id="{{ $partId( 'backdrop' ) }}" data-state="{{ $state }}"{{ $partClass( 'backdrop' ) }} x-apui:backdrop @if ( ! $isOpen ) hidden @endif></div>
        <div data-scope="dialog" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
            <div
                {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-dialog' ) ) }}
                data-scope="dialog"
                data-part="content"
                id="{{ $partId( 'content' ) }}"
                role="{{ 'alertdialog' === $role ? 'alertdialog' : 'dialog' }}"
                tabindex="-1"
                data-state="{{ $state }}"
                data-size="{{ $size }}"
                data-surface="{{ $surface }}"
                @if ( null !== $color && '' !== $color ) data-color="{{ $color }}" @endif
                @if ( $modal ) aria-modal="true" @endif
                @if ( $hasTitle ) aria-labelledby="{{ $partId( 'title' ) }}" @endif
                @if ( $hasDescription ) aria-describedby="{{ $partId( 'description' ) }}" @endif
                @if ( ! $isOpen ) hidden @endif
                x-apui:content
            >
                @if ( $hasHeader )
                    <div data-scope="dialog" data-part="header" id="{{ $partId( 'header' ) }}"{{ $partClass( 'header' ) }}>
                        @if ( $hasIcon )
                            <span data-scope="dialog" data-part="icon" id="{{ $partId( 'icon' ) }}" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $icon ) }}@endif</span>
                        @endif
                        @if ( $hasTitle )
                            <h2 data-scope="dialog" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }} x-apui:title>{{ $content( $title ) }}</h2>
                        @endif
                        @if ( $hasDescription )
                            <p data-scope="dialog" data-part="description" id="{{ $partId( 'description' ) }}"{{ $partClass( 'description' ) }} x-apui:description>{{ $content( $description ) }}</p>
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
                                data-scope="dialog"
                                data-part="close-trigger"
                                x-apui:close-trigger=""
                            />
                        @endif
                    </div>
                @endif
                @if ( $hasBody )
                    <div data-scope="dialog" data-part="body" id="{{ $partId( 'body' ) }}"{{ $partClass( 'body' ) }}>{{ $content( $slot ) }}</div>
                @endif
                @if ( $hasFooter )
                    <div data-scope="dialog" data-part="footer" id="{{ $partId( 'footer' ) }}"{{ $partClass( 'footer' ) }}>{{ $content( $footer ) }}</div>
                @endif
            </div>
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
