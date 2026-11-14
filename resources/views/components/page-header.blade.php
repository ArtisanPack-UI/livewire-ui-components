{{--
    PageHeader (#58). Markup contract: design-system PageHeader. The
    .apui-page-header block: breadcrumbs, then the row (meta, title and
    description beside the actions), then the tabs. Empty parts are left out.
    The deprecated `header` alias adds its 2.x `middle` slot before the actions.
--}}
@php
    $middleSlot = $middle ?? null;
    // The deprecated `header` alias's 2.x `subtitle` slot is the description.
    if ( ! $filled( $description ) && isset( $subtitle ) && $filled( $subtitle ) ) {
        $description = $subtitle;
    }
@endphp
<div {{ $attributes->class( $rootClasses( 'apui-page-header' ) ) }}>
    @if ( $filled( $breadcrumbs ) )
        <div data-scope="page-header" data-part="breadcrumbs"{{ $partClass( 'breadcrumbs' ) }}>{{ $content( $breadcrumbs ) }}</div>
    @endif
    <div data-scope="page-header" data-part="row"{{ $partClass( 'row' ) }}>
        <div>
            @if ( $filled( $meta ) )
                <div data-scope="page-header" data-part="meta"{{ $partClass( 'meta' ) }}>{{ $content( $meta ) }}</div>
            @endif
            @if ( $filled( $title ) )
                <h{{ $level }} data-scope="page-header" data-part="title"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</h{{ $level }}>
            @endif
            @if ( $filled( $description ) )
                <p data-scope="page-header" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</p>
            @endif
        </div>
        @if ( null !== $middleSlot && $filled( $middleSlot ) )
            <div>{{ $content( $middleSlot ) }}</div>
        @endif
        @if ( $filled( $actions ) )
            <div data-scope="page-header" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
        @endif
    </div>
    @if ( $filled( $tabs ) )
        <div data-scope="page-header" data-part="tabs"{{ $partClass( 'tabs' ) }}>{{ $content( $tabs ) }}</div>
    @endif
</div>
