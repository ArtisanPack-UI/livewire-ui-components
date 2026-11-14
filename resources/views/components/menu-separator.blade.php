{{-- MenuSeparator (#56). Markup contract: design-system Menu. A rule between items, hidden from assistive technology. --}}
<li {{ $attributes->when( $partClasses( 'separator' ), fn ( $bag, $classes ) => $bag->class( $classes ) ) }} data-scope="menu" data-part="separator" aria-hidden="true"></li>
@if ( $filled( $title ) )
    <x-artisanpack-menu-title :label="$title" />
@endif
