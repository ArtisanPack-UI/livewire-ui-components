{{--
    Navbar (#56). Markup contract: design-system Navbar. The .apui-navbar header
    holds the brand, the links in a nav landmark (`links` data first, then
    navbar-item children from the default slot), the search and the actions.
    Named slots override the brand, search and actions props.
--}}
@php
    ob_start();
@endphp
@foreach ( $entries() as $entry )
    <x-artisanpack-navbar-item :href="$entry['href']" :current="$entry['current']" :label="$entry['label']" />
@endforeach
@php
    $dataLinks = new \Illuminate\Support\HtmlString( (string) ob_get_clean() );
    $hasLinks  = '' !== trim( $dataLinks->toHtml() ) || ( isset( $slot ) && $slot->hasActualContent() );
@endphp
<header
    {{ $attributes->class( $rootClasses( 'apui-navbar' ) ) }}
    data-layout="{{ $layout }}"
    data-surface="{{ $surface }}"
    @if ( $sticky ) data-sticky @endif
>
    @if ( $filled( $brand ) )
        <div data-scope="navbar" data-part="brand"{{ $partClass( 'brand' ) }}>{{ $content( $brand ) }}</div>
    @endif
    @if ( $hasLinks )
        <nav data-scope="navbar" data-part="links" aria-label="{{ $linksLabel() }}"{{ $partClass( 'links' ) }}>
            {{ $dataLinks }}
            {{ $slot ?? '' }}
        </nav>
    @endif
    @if ( $filled( $search ) )
        <div data-scope="navbar" data-part="search"{{ $partClass( 'search' ) }}>{{ $content( $search ) }}</div>
    @endif
    @if ( $filled( $actions ) )
        <div data-scope="navbar" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
</header>
@php $finishRendering(); @endphp
