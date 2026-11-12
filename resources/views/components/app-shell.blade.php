{{--
    AppShell (#58). Markup contract: design-system AppShell. The .apui-app-shell
    frame: a skip link to the main landmark, the sidebar island (an aside with
    the brand, the navigation and the footer holding the collapse button) and
    the main landmark (the header, then the content). Collapsing is Alpine
    state; the server renders the same attributes so nothing shifts on load.
--}}
@php
    $baseId      = $baseId();
    $sidebarId   = $partId( 'sidebar' );
    $mainId      = $partId( 'main' );
    $isCollapsed = $isCollapsed();
    $interactive = $interactive();
    $userStyle   = rtrim( trim( (string) $attributes->get( 'style', '' ) ), '; ' );
    $style       = implode( '; ', array_filter( [ $rootStyle(), $userStyle ], fn ( $part ) => null !== $part && '' !== $part ) );
    $hasFooter   = $filled( $footer ) || $collapsible;
@endphp
<div
    {{ $attributes->whereDoesntStartWith( 'wire:model' )->except( [ 'id', 'style' ] )->class( $rootClasses( 'apui-app-shell' ) ) }}
    id="{{ $baseId }}"
    @if ( $isCollapsed ) data-collapsed @endif
    @if ( $collapsible ) data-collapsible @endif
    @if ( '' !== $style ) style="{{ $style }}" @endif
    @if ( $interactive )
        x-data="{{ $alpineData() }}"
        x-bind:data-collapsed="collapsed ? '' : null"
    @endif
>
    <a data-scope="app-shell" data-part="skip-link" href="#{{ $mainId }}"{{ $partClass( 'skip-link' ) }}>{{ $skipLabel }}</a>
    <aside data-scope="app-shell" data-part="sidebar" id="{{ $sidebarId }}" aria-label="{{ $label }}"{{ $partClass( 'sidebar' ) }}>
        @if ( $filled( $brand ) )
            <div data-scope="app-shell" data-part="brand"{{ $partClass( 'brand' ) }}>{{ $content( $brand ) }}</div>
        @endif
        @if ( $filled( $sidebar ) )
            {{ $content( $sidebar ) }}
        @endif
        @if ( $hasFooter )
            <div data-scope="app-shell" data-part="footer"{{ $partClass( 'footer' ) }}>
                @if ( $filled( $footer ) )
                    {{ $content( $footer ) }}
                @endif
                @if ( $collapsible )
                    <button
                        type="button"
                        data-scope="app-shell"
                        data-part="collapse-trigger"
                        id="{{ $partId( 'collapse-trigger' ) }}"
                        aria-label="{{ $collapseLabel }}"
                        aria-pressed="{{ $isCollapsed ? 'true' : 'false' }}"
                        aria-controls="{{ $sidebarId }}"
                        x-bind:aria-pressed="collapsed ? 'true' : 'false'"
                        x-on:click="toggle()"
                        {{ $partClass( 'collapse-trigger' ) }}
                    >{{ $iconSvg( 'chevron-left' ) }}</button>
                @endif
            </div>
        @endif
    </aside>
    <main data-scope="app-shell" data-part="main" id="{{ $mainId }}" tabindex="-1"{{ $partClass( 'main' ) }}>
        @if ( $filled( $header ) )
            {{ $content( $header ) }}
        @endif
        <div data-scope="app-shell" data-part="content"{{ $partClass( 'content' ) }}>{{ $slot ?? '' }}</div>
    </main>
</div>
