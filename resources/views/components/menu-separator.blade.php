{{-- MenuSeparator (#56). Markup contract: design-system Menu. A rule between items, hidden from assistive technology. --}}
<li {{ $attributes->when( $filled( $classes['separator'] ?? null ), fn ( $bag ) => $bag->class( $classes['separator'] ) ) }} data-scope="menu" data-part="separator" aria-hidden="true"></li>
@if ( $filled( $title ) )
    <x-artisanpack-menu-title :label="$title" />
@endif
