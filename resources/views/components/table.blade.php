{{--
    Table (#62). Markup contract: design-system Data Display wave, Table.
    The root frames the toolbar (title, toolbar content), the scroll part with
    the native table, and the footer (footer content, the "Rows per page"
    Select and Pagination). Sortable headers carry aria-sort and hold a sort
    button; selectable rows a Checkbox and aria-selected, with a select-all box
    that is mixed while some rows are selected; expandable rows an expand
    button (aria-expanded, aria-controls) and a full-width expansion row.
    Loading shows aria-hidden skeleton rows under aria-busy; no rows shows an
    EmptyState. Selection and expansion run in Alpine; wire:model binds the
    selection through $wire.entangle() (the expansion, for a 2.x expandable
    table without selectable). Scoped slots: @scope('cell_<key>', $row),
    @scope('header_<key>', $header), @scope('actions', $row) and
    @scope('expansion', $row). Each is called inside a @foreach, which the
    scope directive's $loop needs.
--}}
@php
    $columns       = $visibleColumns();
    $rowList       = $rowList();
    $density       = $resolvedDensity();
    $selected      = $selectedKeys();
    $expandedList  = $expandedKeys();
    $pageKeys      = $selectable ? array_values( array_map( fn ( $row ) => $selectionKey( $row ), $rowList ) ) : [];
    $allState      = $selectAllState( $pageKeys, $selected );
    $actionsSlot   = $__data['actions'] ?? null;
    $expansionSlot = $__data['expansion'] ?? null;
    $emptySlot     = $__data['empty'] ?? null;
    $hasActions    = $actionsSlot instanceof \Closure || $filled( $actionsSlot );
    $sortHandle    = $hasSortHandle();
    $dragSort      = $isSortingEnabled();
    $colspan       = count( $columns ) + ( $selectable ? 1 : 0 ) + ( $expandable ? 1 : 0 ) + ( $sortHandle ? 1 : 0 ) + ( $hasActions ? 1 : 0 );
    $hasTitle      = $filled( $title );
    $hasToolbar    = $hasTitle || $filled( $toolbar );
    $legacyFooter  = $footerHoldsRows( $footer );
    $hasFooterText = ! $legacyFooter && $filled( $footer );
    $hasPagination = [] !== $pagination;
    $paginator     = $rendersPaginator();
    $hasFooter     = $hasFooterText || $hasPagination || $paginator;
    $rowClick      = $listensForRowClick();
    $setsSortBy    = $setsLivewireSortBy();
    $titleId       = $partId( 'title' );
    $sortableKeys  = [];
    $tableName     = $filled( $label ) ? ' aria-label="' . e( $label ) . '"' : ( $hasTitle ? ' aria-labelledby="' . e( $titleId ) . '"' : '' );
    $expansionModel = $modelBindsExpansion();
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-table' ) ) }}
    data-surface="{{ in_array( $surface, [ 'default', 'glass' ], true ) ? $surface : 'default' }}"
    @if ( null !== $density ) data-density="{{ $density }}" @endif
    @if ( $stickyHeader ) data-sticky-header @endif
    @if ( $loading ) aria-busy="true" @endif
    x-data="{
        selection: {{ $selectable ? $modelExpression( $selected ) : '[]' }},
        expanded: {{ $expansionModel ? $modelExpression( $expandedList ) : ( json_encode( $expandedList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) ?: '[]' ) }},
        pageKeys: {{ json_encode( $pageKeys, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) ?: '[]' }},
        has( list, key ) { return Array.isArray( list ) && list.some( ( item ) => String( item ) === String( key ) ) },
        without( list, key ) { return ( Array.isArray( list ) ? list : [] ).filter( ( item ) => String( item ) !== String( key ) ) },
        toggleRow( key, on ) { this.selection = on ? [ ...this.without( this.selection, key ), key ] : this.without( this.selection, key ) },
        toggleAll( on ) { const rest = ( Array.isArray( this.selection ) ? this.selection : [] ).filter( ( item ) => ! this.has( this.pageKeys, item ) ); this.selection = on ? [ ...rest, ...this.pageKeys ] : rest },
        allState() { const count = this.pageKeys.filter( ( key ) => this.has( this.selection, key ) ).length; return 0 === count ? 'unchecked' : ( count === this.pageKeys.length ? 'checked' : 'indeterminate' ) },
        toggleExpanded( key ) { this.expanded = this.has( this.expanded, key ) ? this.without( this.expanded, key ) : [ ...( Array.isArray( this.expanded ) ? this.expanded : [] ), key ]; this.$dispatch( 'expanded-change', this.expanded ) },
        sort( next ) { this.$dispatch( 'sort-change', next ){{ $setsSortBy ? '; this.$wire.set( \'sortBy\', next )' : '' }} },
        rowClick( event, row ) { if ( event.target.closest( 'a, button, input, label, select, textarea' ) ) { return } this.$dispatch( 'row-click', row ) },
    }"
>
    @if ( $hasToolbar )
        <div data-scope="table" data-part="toolbar"{{ $partClass( 'toolbar' ) }}>
            @if ( $hasTitle )
                <h3 data-scope="table" data-part="title" id="{{ $titleId }}"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</h3>
            @endif
            @if ( $filled( $toolbar ) )
                {{ $content( $toolbar ) }}
            @endif
        </div>
    @endif
    <div data-scope="table" data-part="scroll"{{ $partClass( 'scroll' ) }}>
        <table data-scope="table" data-part="table"{{ $partClass( 'table' ) }}{!! $tableName !!}>
            <thead @if ( $headersHidden() ) class="apui-sr-only" @endif>
                <tr>
                    @if ( $selectable )
                        <th data-scope="table" data-part="select" scope="col"{{ $partClass( 'select' ) }}>
                            <x-artisanpack-checkbox
                                :aria-label="$selectAllLabel"
                                :checked="'checked' === $allState"
                                :indeterminate="'indeterminate' === $allState"
                                :disabled="[] === $pageKeys"
                                x-effect="const state = allState(); $el.checked = 'checked' === state; $el.closest('.apui-checkbox').dataset.state = state"
                                x-on:input="toggleAll( $el.checked )"
                            />
                        </th>
                    @endif
                    @if ( $expandable )
                        <td data-shrink></td>
                    @endif
                    @if ( $sortHandle )
                        <td data-shrink></td>
                    @endif
                    @foreach ( $columns as $column )
                        @php
                            $slotKey   = 'header_' . str_replace( '.', '___', (string) $column['key'] );
                            $headerSlot = $__data[ $slotKey ] ?? null;
                            $sorts     = $isSortable( $column );
                            $headClass = trim( ( $classes['headCell'] ?? '' ) . ' ' . ( is_string( $column['class'] ?? null ) ? $column['class'] : '' ) );
                        @endphp
                        <th data-scope="table" data-part="head-cell" scope="col"{!! $classAttribute( $headClass ) !!}@if ( $sorts ) aria-sort="{{ $ariaSort( $column ) }}"@endif @if ( $column['numeric'] ?? false ) data-numeric @endif>
                            @if ( $sorts )
                                <button type="button" data-scope="table" data-part="sort"{{ $partClass( 'sort' ) }} aria-label="{{ $fill( $sortLabel, [ 'column' => $columnName( $column ) ] ) }}" x-on:click="sort( {{ \Illuminate\Support\Js::from( $nextSort( $column ) ) }} )">@if ( $headerSlot instanceof \Closure ){{ $headerSlot( $column ) }}@else{{ $column['label'] ?? $column['key'] }}@endif{{ $iconSvg( $sortIcon( $column ) ) }}</button>
                            @elseif ( $headerSlot instanceof \Closure )
                                {{ $headerSlot( $column ) }}
                            @else
                                {{ $column['label'] ?? $column['key'] }}
                            @endif
                        </th>
                    @endforeach
                    @if ( $hasActions )
                        <th data-scope="table" data-part="head-cell" scope="col" data-shrink{{ $partClass( 'headCell' ) }}><span class="apui-sr-only">{{ $actionsLabel }}</span></th>
                    @endif
                </tr>
            </thead>
            <tbody
                @if ( $dragSort )
                    wire:sort="{{ $sortMethod() }}"
                    @if ( is_string( $legacy( 'sort-group' ) ) ) wire:sort:group="{{ $legacy( 'sort-group' ) }}" @endif
                @endif
            >
                @if ( $loading )
                    @foreach ( range( 1, 3 ) as $skeletonRow )
                        <tr data-scope="table" data-part="row" aria-hidden="true"{{ $partClass( 'row' ) }}>
                            @if ( $selectable )<td data-scope="table" data-part="select"{{ $partClass( 'select' ) }}></td>@endif
                            @if ( $expandable )<td data-scope="table" data-part="cell" data-shrink{{ $partClass( 'cell' ) }}></td>@endif
                            @if ( $sortHandle )<td data-scope="table" data-part="cell" data-shrink{{ $partClass( 'cell' ) }}></td>@endif
                            @foreach ( $columns as $column )
                                <td data-scope="table" data-part="cell"{{ $partClass( 'cell' ) }}><span class="apui-skeleton" data-shape="text" aria-hidden="true"></span></td>
                            @endforeach
                            @if ( $hasActions )<td data-scope="table" data-part="cell" data-shrink{{ $partClass( 'cell' ) }}></td>@endif
                        </tr>
                    @endforeach
                @elseif ( [] === $rowList )
                    <tr>
                        <td data-empty colspan="{{ max( 1, $colspan ) }}">
                            @if ( $filled( $emptySlot ) )
                                {{ $content( $emptySlot ) }}
                            @else
                                <div class="apui-empty-state" data-variant="no-results" data-size="sm"><p data-scope="empty-state" data-part="title">{{ $emptyText }}</p></div>
                            @endif
                        </td>
                    </tr>
                @else
                    @foreach ( $rowList as $index => $row )
                        @php
                            $thisKey    = $rowKey( $row );
                            $selectKey  = $selectable ? $selectionKey( $row ) : null;
                            $isSelected = $selectable && $hasKey( $selected, $selectKey );
                            $expandKey  = $expandable ? $expansionKey( $row ) : null;
                            $expands    = $expandable && $canExpand( $row );
                            $isOpen     = $expands && $hasKey( $expandedList, $expandKey );
                            $thisName   = $rowName( $row );
                            $expansionId = $partId( 'expansion-' . $index );
                            $selectJs   = \Illuminate\Support\Js::from( $selectKey );
                            $expandJs   = \Illuminate\Support\Js::from( $expandKey );
                        @endphp
                        <tr
                            data-scope="table"
                            data-part="row"{!! $classAttribute( $rowClasses( $row ) ) !!}
                            wire:key="{{ $baseId() }}-row-{{ is_scalar( $thisKey ) ? $thisKey : $index }}"
                            @if ( $selectable ) aria-selected="{{ $isSelected ? 'true' : 'false' }}" x-bind:aria-selected="has( selection, {{ $selectJs }} ) ? 'true' : 'false'" @endif
                            @if ( $dragSort ) wire:sort:item="{{ $getSortableItemValue( $row ) }}" @endif
                            @if ( $rowClick ) data-clickable x-on:click="rowClick( $event, {{ \Illuminate\Support\Js::from( $row ) }} )" @endif
                        >
                            @if ( $selectable )
                                <td data-scope="table" data-part="select"{{ $partClass( 'select' ) }}>
                                    <x-artisanpack-checkbox
                                        :aria-label="$fill( $selectRowLabel, [ 'row' => $thisName ] )"
                                        :value="is_scalar( $selectKey ) ? (string) $selectKey : ''"
                                        :checked="$isSelected"
                                        x-bind:checked="has( selection, {{ $selectJs }} )"
                                        x-on:input="toggleRow( {{ $selectJs }}, $el.checked )"
                                    />
                                </td>
                            @endif
                            @if ( $expandable )
                                <td data-scope="table" data-part="cell" data-shrink{{ $partClass( 'cell' ) }}>
                                    @if ( $expands )
                                        <button
                                            type="button"
                                            data-scope="table"
                                            data-part="expand-trigger"{{ $partClass( 'expandTrigger' ) }}
                                            aria-label="{{ $fill( $expandLabel, [ 'row' => $thisName ] ) }}"
                                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                            aria-controls="{{ $expansionId }}"
                                            data-mirror
                                            x-bind:aria-expanded="has( expanded, {{ $expandJs }} ) ? 'true' : 'false'"
                                            x-on:click="toggleExpanded( {{ $expandJs }} )"
                                        >{{ $iconSvg( 'chevron-right' ) }}</button>
                                    @endif
                                </td>
                            @endif
                            @if ( $sortHandle )
                                <td data-scope="table" data-part="cell" data-shrink{{ $partClass( 'cell' ) }}>
                                    <span data-drag-handle wire:sort:handle aria-hidden="true">{{ $iconSvg( 'grip-vertical' ) }}</span>
                                </td>
                            @endif
                            @foreach ( $columns as $column )
                                @php
                                    $slotKey   = 'cell_' . str_replace( '.', '___', (string) $column['key'] );
                                    $cellSlot  = $__data[ $slotKey ] ?? null;
                                    $href      = $cellHref( $row, $column );
                                    $secondary = is_string( $column['secondary'] ?? null ) ? data_get( $row, $column['secondary'] ) : null;
                                @endphp
                                <td data-scope="table" data-part="cell"{!! $classAttribute( $cellClasses( $row, $column ) ) !!}@if ( $column['numeric'] ?? false ) data-numeric @endif>
                                    @if ( null !== $href )<a href="{{ $href }}" @if ( \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $href ) ) wire:navigate @endif>@endif
                                    @if ( $cellSlot instanceof \Closure )
                                        {{ $cellSlot( $row ) }}
                                    @elseif ( null !== $secondary && '' !== $secondary )
                                        <span data-scope="table" data-part="primary"{{ $partClass( 'primary' ) }}>{{ $format( $row, data_get( $row, $column['key'] ), $column ) }}</span><span data-scope="table" data-part="secondary"{{ $partClass( 'secondary' ) }}>{{ $secondary }}</span>
                                    @else
                                        {{ $format( $row, data_get( $row, $column['key'] ), $column ) }}
                                    @endif
                                    @if ( null !== $href )</a>@endif
                                </td>
                            @endforeach
                            @if ( $hasActions )
                                <td data-scope="table" data-part="cell" data-shrink data-actions{{ $partClass( 'cell' ) }}>
                                    @if ( $actionsSlot instanceof \Closure )
                                        {{ $actionsSlot( $row ) }}
                                    @else
                                        {{ $content( $actionsSlot ) }}
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @if ( $expands )
                            <tr
                                data-scope="table"
                                data-part="expansion"{{ $partClass( 'expansion' ) }}
                                id="{{ $expansionId }}"
                                wire:key="{{ $baseId() }}-expansion-{{ is_scalar( $thisKey ) ? $thisKey : $index }}"
                                @if ( ! $isOpen ) hidden @endif
                                x-bind:hidden="! has( expanded, {{ $expandJs }} )"
                            >
                                <td colspan="{{ $colspan }}">
                                    @if ( $expansionSlot instanceof \Closure )
                                        {{ $expansionSlot( $row ) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            @if ( $legacyFooter )
                <tfoot>{{ $footer }}</tfoot>
            @endif
        </table>
        @if ( $isInfiniteScrollEnabled() )
            <div data-load-more {{ $getInfiniteScrollDirective() }}="{{ $infiniteScrollMethod() }}">
                <span wire:loading.remove wire:target="{{ $infiniteScrollMethod() }}">{{ is_string( $legacy( 'infinite-scroll-text' ) ) ? $legacy( 'infinite-scroll-text' ) : __( 'Scroll for more' ) }}</span>
                <span wire:loading wire:target="{{ $infiniteScrollMethod() }}">{{ __( 'Loading…' ) }}</span>
            </div>
        @endif
    </div>
    @if ( $hasFooter )
        <div data-scope="table" data-part="footer"{{ $partClass( 'footer' ) }}>
            @if ( $hasFooterText )
                <span>{{ $content( $footer ) }}</span>
            @endif
            @if ( $paginator && $perPage && $rows instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $rows->isNotEmpty() )
                <x-artisanpack-select
                    size="sm"
                    placeholder=""
                    :label="__( 'Rows per page' )"
                    :options="array_map( 'strval', $perPageValues )"
                    :value="(string) $rows->perPage()"
                    wire:model.live="{{ $perPage }}"
                />
            @endif
            @if ( $paginator )
                <x-artisanpack-pagination :rows="$rows" summary />
            @elseif ( $hasPagination )
                <x-artisanpack-pagination
                    :page="(int) ( $pagination['page'] ?? 1 )"
                    :page-count="isset( $pagination['pageCount'] ) ? (int) $pagination['pageCount'] : null"
                    :total="isset( $pagination['total'] ) ? (int) $pagination['total'] : null"
                    :per-page="(int) ( $pagination['perPage'] ?? 10 )"
                    :siblings="(int) ( $pagination['siblings'] ?? 1 )"
                    :summary="(bool) ( $pagination['summary'] ?? false )"
                    :label="$pagination['label'] ?? null"
                    :previous-label="$pagination['previousLabel'] ?? null"
                    :next-label="$pagination['nextLabel'] ?? null"
                    :page-label="$pagination['pageLabel'] ?? null"
                    :summary-label="$pagination['summaryLabel'] ?? null"
                    :classes="(array) ( $pagination['classes'] ?? [] )"
                />
            @endif
        </div>
    @endif
</div>
