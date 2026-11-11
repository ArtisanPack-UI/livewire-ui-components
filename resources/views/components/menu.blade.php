{{--
    Menu (#56). Markup contract: design-system Menu. The .apui-menu nav holds the
    list; children are menu-item, menu-sub, menu-title and menu-separator, and
    `items` renders the same children, before the slot. Parts are scoped menu.
    Every attribute but activate-by-route lands on the nav.
--}}
<nav
    {{ $rootAttributes()->class( $rootClasses( 'apui-menu' ) ) }}
    id="{{ $baseId() }}"
    @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
    @if ( $collapsible ) data-collapsible @endif
>
    <ul data-scope="menu" data-part="list"{{ $partClass( 'list' ) }}>
        @include( 'livewire-ui-components::components.partials.menu-entries', [ 'entries' => $entries() ] )
        {{ $slot ?? '' }}
    </ul>
</nav>
@php $finishRendering(); @endphp
