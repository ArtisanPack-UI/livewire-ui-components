{{--
    DropdownMenuSeparator (#50). Markup contract: design-system pilot, DropdownMenu.
--}}
<hr
    {{ $attributes->except( 'id' )->when( $partClasses( 'separator' ), fn ( $bag, $classes ) => $bag->class( $classes ) ) }}
    id="{{ $separatorId() }}"
    data-scope="dropdown-menu"
    data-part="separator"
    role="separator"
    aria-orientation="horizontal"
    x-apui:separator
/>
