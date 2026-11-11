{{--
    Pagination (#56). Markup contract: design-system wave 4, Pagination.
    A nav landmark: the summary, previous, the pages (aria-current on the
    current one), ellipses (aria-hidden) and next. Previous and next are
    icon-only, named by their labels, and aria-disabled at the ends; their
    chevrons mirror in RTL. Every enabled control dispatches page-change with
    { page }; paginator links also navigate, or call gotoPage() in Livewire.
--}}
@php
    $summaryText = $summaryText();
    $controls    = [ 'previous' => $control( 'previous' ), 'next' => $control( 'next' ) ];
@endphp
<nav
    {{ $attributes->filter( fn ( $value, $key ) => is_scalar( $value ) && ! str_starts_with( $key, 'wire:model' ) )->class( $rootClasses( 'apui-pagination' ) ) }}
    aria-label="{{ $label }}"
    x-data
>
    @if ( null !== $summaryText )
        <span data-scope="pagination" data-part="summary"{{ $partClass( 'summary' ) }}>{{ $summaryText }}</span>
    @endif
    @foreach ( [ 'previous', 'pages', 'next' ] as $slotName )
        @if ( 'pages' === $slotName )
            @foreach ( $pageItems() as $item )
                @if ( null === $item )
                    <span data-scope="pagination" data-part="ellipsis" aria-hidden="true"{{ $partClass( 'ellipsis' ) }}>…</span>
                @else
                    @php $pageControl = $control( 'page', $item ); @endphp
                    <{{ $pageControl['tag'] }}
                        @if ( 'a' === $pageControl['tag'] ) href="{{ $pageControl['href'] }}" @else type="button" @endif
                        data-scope="pagination"
                        data-part="page"{{ $partClass( 'page' ) }}
                        aria-label="{{ $pageName( $item ) }}"
                        @if ( $pageControl['current'] ) aria-current="page" @else x-on:click="$dispatch( 'page-change', { page: {{ $item }} } )" @if ( null !== $pageControl['wireClick'] ) wire:click.prevent="{{ $pageControl['wireClick'] }}" @endif @endif
                    >{{ $item }}</{{ $pageControl['tag'] }}>
                @endif
            @endforeach
        @else
            @php $edge = $controls[ $slotName ]; @endphp
            <{{ $edge['tag'] }}
                @if ( 'a' === $edge['tag'] ) href="{{ $edge['href'] }}" rel="{{ 'previous' === $slotName ? 'prev' : 'next' }}" @else type="button" @endif
                data-scope="pagination"
                data-part="{{ $slotName }}"{{ $partClass( $slotName ) }}
                aria-label="{{ 'previous' === $slotName ? $previousLabel : $nextLabel }}"
                data-mirror
                @if ( $edge['disabled'] ) aria-disabled="true" @else @if ( null !== $edge['page'] ) x-on:click="$dispatch( 'page-change', { page: {{ $edge['page'] }} } )" @endif @if ( null !== $edge['wireClick'] ) wire:click.prevent="{{ $edge['wireClick'] }}" @endif @endif
            >{{ $iconSvg( 'previous' === $slotName ? 'chevron-left' : 'chevron-right' ) }}</{{ $edge['tag'] }}>
        @endif
    @endforeach
</nav>
