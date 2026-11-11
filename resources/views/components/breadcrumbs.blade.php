{{--
    Breadcrumbs (#56). Markup contract: design-system wave 4, Breadcrumbs.
    A nav landmark with an ordered list, from `items` and then composed
    breadcrumbs-item (or link) children, which register with the trail and
    render nothing themselves. The last level is the current page
    (aria-current, never a link). Separators are aria-hidden and mirror in RTL.
    Collapsed levels render with `hidden`; the overflow button reveals them and
    moves focus to the first.
--}}
@php
    // Deprecated 2.x opt-out: `no-wire-navigate` turns navigate off.
    $navigate   = $navigate && ! $attributes->has( 'no-wire-navigate' ) && ! $attributes->has( 'noWireNavigate' );
    $attributes = $attributes->except( [ 'no-wire-navigate', 'noWireNavigate' ] );
    $entries   = $entries();
    $hidden    = $hiddenCount( $entries );
    $separator = $iconSvg( 'chevron-right' );
    $finishRendering();
@endphp
<nav
    {{ $attributes->filter( fn ( $value ) => is_scalar( $value ) )->class( $rootClasses( 'apui-breadcrumbs' ) ) }}
    aria-label="{{ $label }}"
    @if ( $hidden > 0 ) x-data="{ expanded: false }" @endif
>
    <ol data-scope="breadcrumbs" data-part="list"{{ $partClass( 'list' ) }}>
        @foreach ( $entries as $index => $entry )
            @if ( $hidden > 0 && 1 === $index )
                <li data-scope="breadcrumbs" data-part="item"{{ $partClass( 'item' ) }} x-bind:hidden="expanded"><span data-scope="breadcrumbs" data-part="separator" aria-hidden="true" data-mirror{{ $partClass( 'separator' ) }}>{{ $separator }}</span><button type="button" data-scope="breadcrumbs" data-part="overflow"{{ $partClass( 'overflow' ) }} aria-label="{{ $overflowName( $hidden ) }}" aria-expanded="false" x-bind:aria-expanded="expanded ? 'true' : 'false'" x-on:click="expanded = true; $nextTick( () => $root.querySelector( '[data-collapsed] a' )?.focus() )">{{ $iconSvg( 'ellipsis' ) }}</button></li>
            @endif
@php
    $labelHtml = $entry['label'] instanceof \Illuminate\Contracts\Support\Htmlable ? $content( $entry['label'] )->toHtml() : e( $entry['label'] );
    $inner     = ( null !== $entry['icon'] ? $iconSvg( $entry['icon'] )->toHtml() : '' ) . ( $entry['hideLabel'] ? '<span class="apui-sr-only">' . $labelHtml . '</span>' : $labelHtml );
    $childBag  = $entry['attributes'] ?? new \Illuminate\View\ComponentAttributeBag();
    $linkBag   = $childBag->except( [ 'href' ] )->when( $filled( $classes['link'] ?? null ), fn ( $bag ) => $bag->class( $classes['link'] ) );
    $currentBag = $childBag->only( [ 'class' ] )->when( $filled( $classes['current'] ?? null ), fn ( $bag ) => $bag->class( $classes['current'] ) );
@endphp
            <li data-scope="breadcrumbs" data-part="item"{{ $partClass( 'item' ) }}@if ( $entry['collapsed'] ) hidden data-collapsed x-bind:hidden="! expanded"@endif>@if ( $index > 0 )<span data-scope="breadcrumbs" data-part="separator" aria-hidden="true" data-mirror{{ $partClass( 'separator' ) }}>{{ $separator }}</span>@endif
@if ( $entry['current'] )<span {{ $currentBag }} data-scope="breadcrumbs" data-part="current" aria-current="page">{!! $inner !!}</span>@elseif ( null !== $entry['href'] )<a {{ $linkBag }} data-scope="breadcrumbs" data-part="link" href="{{ $entry['href'] }}"@if ( $navigate && \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $entry['href'] ) ) wire:navigate @endif>{!! $inner !!}</a>@else<span>{!! $inner !!}</span>@endif</li>
        @endforeach
    </ol>
</nav>
