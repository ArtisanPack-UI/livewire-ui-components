{{-- MenuTitle (#56). Markup contract: design-system Menu. A section title between items. --}}
@php
    $text = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $label ?? $title ?? '' );
@endphp
<li {{ $attributes->when( $filled( $classes['title'] ?? null ), fn ( $bag ) => $bag->class( $classes['title'] ) ) }} data-scope="menu" data-part="title">{{ $text }}</li>
