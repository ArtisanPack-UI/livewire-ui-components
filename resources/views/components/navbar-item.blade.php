{{-- NavbarItem (#56). Markup contract: design-system Navbar. A primary link; aria-current="page" when current. --}}
@php
    $text = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $label ?? '' );
    // A disallowed href (javascript:, data:, …) renders the link part without one: a plain non-link.
    $href = \ArtisanPack\LivewireUiComponents\Support\SafeUrl::href( $href );
@endphp
<a
    {{ $attributes->when( $partClasses( 'link' ), fn ( $bag, $classes ) => $bag->class( $classes ) ) }}
    @if ( null !== $href ) href="{{ $href }}" @endif
    @if ( $navigate && \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $href ) ) wire:navigate @endif
    data-scope="navbar"
    data-part="link"
    @if ( $current ) aria-current="page" @endif
>{{ $text }}</a>
