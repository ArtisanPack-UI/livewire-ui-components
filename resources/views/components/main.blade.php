{{--
    Main (deprecated 3.0.0): the 2.x frame on the design-system AppShell. The
    `content` slot is the content, the `sidebar` slot the sidebar (its
    `collapsible` and `collapse-text` attributes still apply), and the 2.x
    `footer` slot, a page footer, follows the content. `$content` here is the
    slot, so this view never calls the parts helper of that name.
--}}
@php
    $sidebarAttributes = $sidebar instanceof \Illuminate\View\ComponentSlot ? $sidebar->attributes : new \Illuminate\View\ComponentAttributeBag();
    $isCollapsible     = $collapsible || $sidebarAttributes->has( 'collapsible' );
    $collapseName      = $sidebarAttributes->get( 'collapse-text' ) ?? $collapseText;
    $legacyContent     = $content instanceof \Illuminate\View\ComponentSlot ? $content : ( $slot ?? '' );
    $pageFooter        = $footer instanceof \Illuminate\View\ComponentSlot || ( is_string( $footer ) && '' !== $footer ) ? $footer : null;
@endphp
<x-artisanpack-app-shell
    {{ $attributes }}
    :collapsed="$collapsed"
    :collapsible="$isCollapsible"
    :collapse-label="$collapseName"
>
    @if ( $sidebar instanceof \Illuminate\View\ComponentSlot || ( is_string( $sidebar ) && '' !== $sidebar ) )
        <x-slot:sidebar>{{ $sidebar }}</x-slot:sidebar>
    @endif
    {{ $legacyContent }}
    @if ( null !== $pageFooter )
        <footer>{{ $pageFooter }}</footer>
    @endif
</x-artisanpack-app-shell>
