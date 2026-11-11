{{--
    DropdownMenu (#50). Markup contract: design-system pilot, DropdownMenu (D-019).
    The .apui-dropdown-menu panel is the machine's content part; parts are scoped
    dropdown-menu. Every part has a stable, server-rendered id and items carry
    wire:key (D-015). `class` lands on the panel; every other attribute lands on
    the apuiDropdownMenu element, where open-change, select and checked-change
    are dispatched.
--}}
@php
    $baseId = $baseId();
    $isOpen = $isOpen();
    $state  = $isOpen ? 'open' : 'closed';
    $align  = in_array( $align, [ 'start', 'center', 'end' ], true ) ? $align : 'start';
@endphp
<div {{ $hostAttributes() }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    <x-artisanpack-button
        variant="outline"
        color="neutral"
        trailing-icon="chevron-down"
        :id="$partId( 'trigger' )"
        :class="$classes['trigger'] ?? null"
        data-scope="dropdown-menu"
        data-part="trigger"
        aria-haspopup="menu"
        :aria-expanded="$isOpen ? 'true' : 'false'"
        :aria-controls="$partId( 'content' )"
        :data-state="$state"
        x-apui:trigger=""
    >{{ $content( $label ?? '' ) }}</x-artisanpack-button>
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="dropdown-menu" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
        <div
            {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-dropdown-menu' ) ) }}
            data-scope="dropdown-menu"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="menu"
            tabindex="0"
            aria-labelledby="{{ $partId( 'trigger' ) }}"
            data-state="{{ $state }}"
            data-align="{{ $align }}"
            data-surface="{{ $surface }}"
            @if ( ! $isOpen ) hidden @endif
            x-apui:content
        >
            @include( 'livewire-ui-components::components.partials.dropdown-menu-entries', [ 'entries' => $entries() ] )
            {{ $slot ?? '' }}
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
@php $finishRendering(); @endphp
