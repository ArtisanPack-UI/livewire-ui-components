{{--
    MenuSub (#56). Markup contract: design-system Menu. The disclosure pattern:
    a button with aria-expanded and aria-controls toggles the sub list (plain
    Alpine state, no behavior widget; the Menu isn't an ARIA menu). Alpine owns
    the two attributes it toggles (wire:ignore.self), so a re-render keeps the
    sub-menu as the person left it. Without collapsible the item is a heading
    and the list always shows. Every attribute lands on the item.
--}}
@unless ( $hidden )
    @php
        $subId       = $subId();
        $isOpen      = $isOpen();
        $collapsible = $collapsible();
        $bag         = $attributes->when( $partClasses( 'item' ), fn ( $bag, $classes ) => $bag->class( $classes ) );
    @endphp
    <li @if ( $collapsible ) x-data="{ open: @js( $isOpen ) }" @endif>
        @if ( $collapsible )
            <button
                {{ $bag }}
                type="button"
                data-scope="menu"
                data-part="item"
                aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                aria-controls="{{ $subId }}"
                @if ( $disabled ) disabled aria-disabled="true" @endif
                x-on:click="open = ! open"
                x-bind:aria-expanded="open ? 'true' : 'false'"
                wire:ignore.self
            >
        @else
            <span {{ $bag }} data-scope="menu" data-part="item">
        @endif
            @if ( $filled( $icon ) )
                <span data-scope="menu" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( $mirrors( $icon ) ) data-mirror @endif>{{ $iconSvg( $icon ) }}</span>
            @endif
            <span data-scope="menu" data-part="item-text"{{ $partClass( 'itemText' ) }}>{{ $content( $labelText() ?? '' ) }}</span>
            @if ( null !== $badge && '' !== (string) $badge )
                <span data-scope="menu" data-part="badge"{{ $partClass( 'badge' ) }}>{{ $badge }}</span>
            @endif
        @if ( $collapsible )
                <span data-scope="menu" data-part="chevron" aria-hidden="true" data-mirror{{ $partClass( 'chevron' ) }}>{{ $iconSvg( 'chevron-right' ) }}</span>
            </button>
        @else
            </span>
        @endif
        <ul
            data-scope="menu"
            data-part="sub"
            id="{{ $subId }}"
            {{ $partClass( 'sub' ) }}
            @if ( $collapsible )
                @if ( ! $isOpen ) hidden @endif
                x-bind:hidden="! open"
                wire:ignore.self
            @endif
        >{{ $slot }}</ul>
    </li>
@endunless
@php $finishRendering(); @endphp
