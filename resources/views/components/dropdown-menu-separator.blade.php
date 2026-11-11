{{--
    DropdownMenuSeparator (#50). Markup contract: design-system pilot, DropdownMenu.
--}}
<hr
    {{ $attributes->except( 'id' )->when( $filled( $classes['separator'] ?? null ), fn ( $bag ) => $bag->class( $classes['separator'] ) ) }}
    id="{{ $separatorId() }}"
    data-scope="dropdown-menu"
    data-part="separator"
    role="separator"
    aria-orientation="horizontal"
    x-apui:separator
/>
