{{--
    Spotlight (#57). Markup contract: design-system Spotlight (D-019).
    The .apui-spotlight panel is the dialog's content part; the search row, query
    and results are the combobox's control, input and content. Every part has a
    stable, server-rendered id and items carry wire:key, so Livewire's morph keeps
    them (D-015). Recent choices and url results render on the client, inside
    wire:ignore. `class` lands on the panel; every other attribute lands on the
    apuiSpotlight element, where open-change, select and search-change are dispatched.
--}}
@php
    $baseId     = $baseId();
    $isOpen     = $isOpen();
    $state      = $isOpen ? 'open' : 'closed';
    $groupsList = $resultGroups();
    $hasItems   = $hasItems();
    $active     = $isOpen ? $activeValue() : null;
    $label      = $labelText();
    $keys       = $shortcutKeys();
    $ariaKeys   = $ariaKeyShortcuts();
    $hasTrigger = $filled( $trigger );
    $footerSlot = $filled( $footer ) ? $footer : ( isset( $append ) && $filled( $append ) ? $append : null );
    $hasFooter  = null !== $footerSlot;
    $emptySlot  = isset( $empty ) && $filled( $empty ) ? $empty : null;
@endphp
<div {{ $hostAttributes() }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    @if ( $hasTrigger )
        <x-artisanpack-button
            variant="outline"
            color="neutral"
            :id="$partId( 'trigger' )"
            :class="$classes['trigger'] ?? null"
            data-scope="spotlight"
            data-part="trigger"
            aria-haspopup="dialog"
            :aria-expanded="$isOpen ? 'true' : 'false'"
            :aria-controls="$partId( 'content' )"
            :aria-keyshortcuts="$ariaKeys"
            :data-state="$state"
            x-apui:trigger=""
        >{{ $content( $trigger ) }}@if ( [] !== $keys )<span data-scope="spotlight" data-part="shortcut" aria-hidden="true">@foreach ( $keys as $index => $key )<kbd x-text="keys[{{ $index }}]">{{ $key }}</kbd>@endforeach</span>@endif</x-artisanpack-button>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div id="{{ $partId( 'layer' ) }}" style="display: contents">
        <div data-scope="spotlight" data-part="backdrop" id="{{ $partId( 'backdrop' ) }}" data-state="{{ $state }}"{{ $partClass( 'backdrop' ) }} x-apui:backdrop @if ( ! $isOpen ) hidden @endif></div>
        <div data-scope="spotlight" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
            <div
                {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-spotlight' ) ) }}
                data-scope="spotlight"
                data-part="content"
                id="{{ $partId( 'content' ) }}"
                role="dialog"
                aria-label="{{ $label }}"
                tabindex="-1"
                data-state="{{ $state }}"
                @if ( $modal ) aria-modal="true" @endif
                @if ( ! $isOpen ) hidden @endif
                x-apui:content
            >
                <div data-scope="spotlight" data-part="search" id="{{ $partId( 'search' ) }}"{{ $partClass( 'search' ) }} x-apui:search>
                    <span data-scope="spotlight" data-part="icon" id="{{ $partId( 'icon' ) }}" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( 'search' ) }}</span>
                    <input
                        data-scope="spotlight"
                        data-part="query"
                        id="{{ $partId( 'query' ) }}"
                        type="text"
                        role="combobox"
                        aria-label="{{ $label }}"
                        aria-autocomplete="list"
                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                        aria-controls="{{ $partId( 'results' ) }}"
                        @if ( null !== $active ) aria-activedescendant="{{ $itemId( $active ) }}" @endif
                        @if ( null !== $ariaKeys ) aria-keyshortcuts="{{ $ariaKeys }}" @endif
                        placeholder="{{ $placeholderText() }}"
                        autocomplete="off"
                        spellcheck="false"
                        {{ $partClass( 'query' ) }}
                        x-apui:query
                    />
                    <button type="button" data-scope="spotlight" data-part="close-trigger" id="{{ $partId( 'close-trigger' ) }}" aria-label="{{ $closeTriggerLabel() }}" aria-keyshortcuts="Escape"{{ $partClass( 'closeTrigger' ) }} x-apui:close-trigger>Esc</button>
                </div>
                <div
                    data-scope="spotlight"
                    data-part="results"
                    id="{{ $partId( 'results' ) }}"
                    role="listbox"
                    aria-label="{{ $label }}"
                    tabindex="-1"
                    @if ( $loading ) aria-busy="true" data-loading @endif
                    {{ $partClass( 'results' ) }}
                    x-apui:results
                >
                    @if ( $recent )
                        <div data-apui-unfiltered wire:ignore style="display: contents">
                            @include( 'livewire-ui-components::components.partials.spotlight-client-groups', [ 'source' => 'recent' ] )
                        </div>
                    @endif
                    @foreach ( $groupsList as $group )
                        @php
                            $groupId = $partId( 'group-' . $group['id'] );
                        @endphp
                        <div data-scope="spotlight" data-part="item-group" id="{{ $groupId }}" role="group" @if ( null !== $group['label'] ) aria-labelledby="{{ $groupId }}-label" @endif{{ $partClass( 'itemGroup' ) }} x-apui:item-group="{{ \Illuminate\Support\Js::from( [ 'id' => $group['id'] ] ) }}" @if ( [] === $group['items'] ) hidden @endif>
                            @if ( null !== $group['label'] )
                                <div data-scope="spotlight" data-part="item-group-label" id="{{ $groupId }}-label" role="presentation"{{ $partClass( 'itemGroupLabel' ) }} x-apui:item-group-label="{{ \Illuminate\Support\Js::from( [ 'htmlFor' => $group['id'] ] ) }}">{{ $group['label'] }}</div>
                            @endif
                            @foreach ( $group['items'] as $item )
                                @php
                                    $itemTag  = isset( $item['href'] ) ? 'a' : 'div';
                                    $entryKeys = $itemKeys( $item['shortcut'] ?? null );
                                @endphp
                                <{{ $itemTag }}
                                    data-scope="spotlight"
                                    data-part="item"
                                    wire:key="{{ $baseId }}-item-{{ $item['value'] }}"
                                    id="{{ $itemId( $item['value'] ) }}"
                                    role="option"
                                    aria-selected="{{ $active === $item['value'] ? 'true' : 'false' }}"
                                    @if ( $active === $item['value'] ) data-highlighted @endif
                                    tabindex="-1"
                                    data-value="{{ $item['value'] }}"
                                    @if ( isset( $item['href'] ) ) href="{{ \ArtisanPack\LivewireUiComponents\Support\SafeUrl::href( $item['href'] ) }}" @if ( $navigate && \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $item['href'] ) ) wire:navigate @endif @endif
                                    @if ( isset( $item['color'] ) ) data-color="{{ $item['color'] }}" @endif
                                    @if ( ! empty( $item['disabled'] ) ) aria-disabled="true" data-disabled @endif
                                    {{ $partClass( 'item' ) }}
                                    x-apui:item="{{ \Illuminate\Support\Js::from( $item ) }}"
                                >
                                    @if ( isset( $item['icon'] ) )
                                        <span data-scope="spotlight" data-part="tile" aria-hidden="true"{{ $partClass( 'tile' ) }}>{{ $iconSvg( $item['icon'] ) }}</span>
                                    @endif
                                    <span data-scope="spotlight" data-part="item-text"{{ $partClass( 'itemText' ) }} x-apui:item-text><span data-apui-label>{{ $item['label'] }}</span>@if ( isset( $item['description'] ) )<span data-scope="spotlight" data-part="item-description"{{ $partClass( 'itemDescription' ) }}>{{ $item['description'] }}</span>@endif</span>
                                    @if ( [] !== $entryKeys )
                                        <span data-scope="spotlight" data-part="shortcut" aria-hidden="true"{{ $partClass( 'shortcut' ) }}>@foreach ( $entryKeys as $key )<kbd>{{ $key }}</kbd>@endforeach</span>
                                    @endif
                                    <span data-scope="spotlight" data-part="item-indicator" aria-hidden="true"{{ $partClass( 'itemIndicator' ) }}>{{ $iconSvg( 'corner-down-left' ) }}</span>
                                </{{ $itemTag }}>
                            @endforeach
                        </div>
                    @endforeach
                    <div data-apui-unfiltered wire:ignore style="display: contents">
                        @include( 'livewire-ui-components::components.partials.spotlight-client-groups', [ 'source' => 'remote' ] )
                    </div>
                    <div data-scope="spotlight" data-part="empty" id="{{ $partId( 'empty' ) }}"{{ $partClass( 'empty' ) }} x-apui:empty @if ( $hasItems || $loading ) hidden @endif>@if ( null !== $emptySlot ){{ $content( $emptySlot ) }}@else{{ $emptyTextValue() }}@endif</div>
                    <div data-scope="spotlight" data-part="loading" id="{{ $partId( 'loading' ) }}"{{ $partClass( 'loading' ) }} x-apui:loading @if ( ! $loading ) hidden @endif>{{ $loadingTextValue() }}</div>
                </div>
                <div data-scope="spotlight" data-part="footer" id="{{ $partId( 'footer' ) }}"{{ $partClass( 'footer' ) }}>
                    @if ( $hasFooter )
                        {{ $content( $footerSlot ) }}
                    @else
                        <span><span data-scope="spotlight" data-part="shortcut" aria-hidden="true"><kbd>↑</kbd><kbd>↓</kbd></span>{{ $text( $navigateLabel, 'Navigate' ) }}</span>
                        <span><span data-scope="spotlight" data-part="shortcut" aria-hidden="true"><kbd>↵</kbd></span>{{ $text( $selectLabel, 'Open' ) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
