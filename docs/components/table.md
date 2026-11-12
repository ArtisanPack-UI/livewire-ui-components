---
title: Table Component
---

# Table Component

Rows of structured data in real table markup, built on the ArtisanPack UI design system (`.apui-table`): a toolbar with the title, the table in a scroll area, and a footer with Pagination. Headers sort, rows select and expand, and loading and empty states are built in.

## Basic Usage

```blade
<x-artisanpack-table
	:title="__( 'Transaction history' )"
	:headers="[
		[ 'key' => 'symbol', 'label' => __( 'Product name' ), 'secondary' => 'company' ],
		[ 'key' => 'amount', 'label' => __( 'Order amount' ), 'numeric' => true ],
		[ 'key' => 'date', 'label' => __( 'Date' ) ],
		[ 'key' => 'owner.name', 'label' => __( 'Executed by' ) ],
	]"
	:rows="$transactions"
/>
```

A column's `key` is a dot path into each row (an array or an object). `numeric` end-aligns the column with tabular figures; `secondary` is a second dot path shown muted under the value. The title names the table; without one, give it a `label`.

### Columns

| Key | Description |
|---|---|
| `key` | Dot path to the value. Required. |
| `label` | The header text. |
| `sortable` | Makes the header a sort button with `aria-sort`. |
| `numeric` | End-aligns the column with tabular figures. |
| `hidden` | Leaves the column out. |
| `secondary` | A second dot path, shown muted under the value. |
| `class` | Classes for the header and body cells. |
| `format` | A closure `fn ( $row, $value )`, `[ 'currency', '2.,', '$' ]` (decimals, decimal and thousands separators, prefix) or `[ 'date', 'M j, Y' ]`. |
| `sortBy` | The column name sorting uses, when it isn't the key. |
| `disableLink` | Keeps this column's cells out of the row `link`. |

Columns pass through the `ap.livewireUiComponents.tableColumns` filter with the table's `id` (or `default`).

## Sorting

Sortable headers carry `aria-sort` and hold a button named "Sort by {column}". Clicking one asks for the next sort (ascending first, then toggling): inside a Livewire component with a public `sortBy` property the table sets it, even when it starts as `[]`, and it always dispatches `sort-change` with `{ column, direction }`. The table never sorts rows itself, so sort them in your query.

```blade
{{-- public array $sortBy = [ 'column' => 'date', 'direction' => 'desc' ]; --}}
<x-artisanpack-table :headers="$headers" :rows="$this->transactions" :sort-by="$sortBy" />
```

When any column sets `sortable => true`, only those columns sort. Otherwise, as in 2.x, every column sorts once `sort-by` is set, unless it sets `sortable => false`.

## Selection

`selectable` adds a Checkbox to each row and a select-all box to the header, which is mixed while some rows of the page are selected. `wire:model` binds the selected keys (from `key-by`, `id` by default); add `.live` to send each change.

```blade
<x-artisanpack-table :headers="$headers" :rows="$rows" selectable wire:model.live="selected" />
```

Selected rows carry `aria-selected="true"`. Row boxes are named "Select {row}" from the row's first column.

## Cells, headers, actions and expansion

Scoped slots render a column's cells or header, row actions in a last column, and the content of expanded rows. A dotted key such as `user.name` becomes `cell_user.name`.

```blade
<x-artisanpack-table :headers="$headers" :rows="$transactions" expandable>
	@scope( 'cell_status', $row )
		<x-artisanpack-badge :color="$row['color']" :value="$row['status']" />
	@endscope

	@scope( 'header_amount', $header )
		{{ $header['label'] }} (USD)
	@endscope

	@scope( 'actions', $row )
		<x-artisanpack-button variant="ghost" size="sm" :href="route( 'transactions.show', $row['id'] )">{{ __( 'View' ) }}</x-artisanpack-button>
	@endscope

	@scope( 'expansion', $row )
		{{ $row['notes'] }}
	@endscope
</x-artisanpack-table>
```

`expandable` adds an expand button (with `aria-expanded` and `aria-controls`) to each row; `:expanded="[ 1 ]"` opens rows on render. `expandable-condition="has_notes"` limits it to rows where that path is truthy. Use a Badge for status; don't color whole rows.

## Loading and empty states

`loading` shows skeleton rows and sets `aria-busy` on the table. With no rows, an EmptyState shows `empty-text` ("No records found." by default); the `empty` slot replaces it.

```blade
<x-artisanpack-table :headers="$headers" :rows="$rows" :loading="$isLoading">
	<x-slot:empty>
		<p>{{ __( 'No transactions yet.' ) }} <a href="{{ route( 'transactions.create' ) }}">{{ __( 'Add one' ) }}</a></p>
	</x-slot:empty>
</x-artisanpack-table>
```

## Footer and pagination

`footer` (or a `footer` slot) sits at the start of the footer bar. With a paginator in `rows`, `paginated` renders Pagination at its end, and `per-page` names the Livewire property a "Rows per page" Select binds. For other data, `pagination` takes Pagination's props.

```blade
{{-- use WithPagination; public int $perPage = 10; --}}
<x-artisanpack-table
	:headers="$headers"
	:rows="Transaction::paginate( $perPage )"
	paginated
	per-page="perPage"
	:footer="__( ':count selected', [ 'count' => count( $selected ) ] )"
/>

<x-artisanpack-table :headers="$headers" :rows="$rows" :pagination="[ 'page' => 3, 'total' => 128, 'perPage' => 10 ]" />
```

## Density and surface

`density="compact"` (for operational screens) or `comfortable` (for browsing) sets row height, cell padding and type size; unset, the table follows the page's `data-density`. `surface="glass"` puts it on a translucent surface. The header row sticks inside the scroll area; give it a height with `style="--apui-table-max-block-size: 480px"`, or turn it off with `:sticky-header="false"`.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `headers` | array | required | The columns (see Columns). |
| `rows` | array, Collection or paginator | required | The rows. |
| `key-by` | string | `id` | Dot path to each row's key, for selection, expansion and `wire:key`. |
| `title` | string | none | The toolbar title; it names the table. A `title` slot overrides it. |
| `label` | string | none | The table's accessible name instead of the title. |
| `density` | string | none | `compact`, `default` or `comfortable`. |
| `surface` | string | `default` | `default` or `glass`. |
| `sticky-header` | bool | `true` | Keeps the header row in view while the rows scroll. |
| `hide-headers` | bool | `false` | Hides the header row visually; screen readers still read it. |
| `selectable` | bool | `false` | Adds row boxes and a select-all box. |
| `value` | array | `[]` | The selected keys without `wire:model`. |
| `expandable` | bool | `false` | Adds an expand button to each row. |
| `expanded` | array | `[]` | The expanded keys. |
| `sort-by` | array | `[]` | The current sort: `[ 'column' => …, 'direction' => 'asc' or 'desc' ]`. |
| `loading` | bool | `false` | Shows skeleton rows and sets `aria-busy`. |
| `empty-text` | string | `__( 'No records found.' )` | The empty state's title. |
| `toolbar` | string | none | Toolbar content after the title. A `toolbar` slot overrides it. |
| `footer` | string | none | Footer content. A `footer` slot overrides it. |
| `pagination` | array | `[]` | Pagination's props, rendered at the end of the footer. |
| `paginated` | bool | `false` | Renders Pagination from the paginator in `rows`. |
| `per-page` | string | none | The Livewire property the "Rows per page" Select binds. |
| `per-page-values` | array | `[10, 20, 50, 100]` | The "Rows per page" options. |
| `select-all-label` | string | `__( 'Select all' )` | The select-all box's name. |
| `select-row-label` | string | `__( 'Select {row}' )` | Each row box's name. |
| `sort-label` | string | `__( 'Sort by {column}' )` | Each sort button's name. |
| `expand-label` | string | `__( 'Show details for {row}' )` | Each expand button's name. |
| `actions-label` | string | `__( 'Actions' )` | The actions column's hidden header. |
| `classes` | array | `[]` | Extra classes per part: `root`, `toolbar`, `title`, `scroll`, `table`, `headCell`, `sort`, `row`, `cell`, `select`, `expandTrigger`, `expansion`, `primary`, `secondary`, `footer`. |

Every other attribute lands on the root `<div>`, except `wire:model`.

## Events

| Event | Detail | When |
|---|---|---|
| `sort-change` | `{ column, direction }` | A sortable header was chosen. |
| `expanded-change` | the expanded keys | A row was expanded or collapsed. |
| `row-click` | the row | A row was clicked outside its links, buttons and boxes. Only fires while something listens: `x-on:row-click`, `@row-click` or `wire:row-click`. |

The selection changes through `wire:model`.

## Upgrading from 2.x

- `headers`, `rows`, `key-by`, `class`, `@scope( 'cell_…', $row )`, `@scope( 'header_…', $header )`, `@scope( 'actions', $row )`, `@scope( 'expansion', $row )`, `sort-by`, `selectable`, `wire:model`, `expandable`, `selectable-key`, `expandable-key`, `expandable-condition`, `link`, `row-decoration`, `cell-decoration`, `format`, `with-pagination`, `per-page`, `per-page-values`, `sortable` (drag and drop), `sort-group`, `sort-handle` and `infinite-scroll` keep working.
- `class` and other attributes now land on the root `<div>`, not the `<table>`.
- `compact` sets `density="compact"`; `no-headers` sets `hide-headers`; `with-pagination` is now `paginated`.
- `selectable` and `expandable` can now be combined. With `expandable` alone, `wire:model` still binds the expanded keys.
- An empty table now always shows the empty state; `show-empty-text` is no longer needed.
- A `footer` slot now fills the footer bar. A slot of `<tr>` rows still renders in a `<tfoot>`.
- The `row-selection` and `row-selection-all` events are gone; read the `wire:model` property.
- `striped`, `no-hover`, `container-class` and `header-glass`, `header-glass-tint`, `header-glass-tint-opacity` are gone; use `surface="glass"` and `classes`.
- Virtual scrolling (`virtual-scroll`, `virtual-row-height`, `virtual-buffer`, `virtual-container-height`) and the export toolbar (`exportable`, `export-formats`, `export-filename` and the `table-exported`, `table-export-error` and `table-export-request` events) are removed. The `TableExporter` class remains for server-side exports.

## Accessibility

- Real table markup: `thead` and `tbody`, header cells with `scope="col"`, named by the title or `label`.
- Sortable headers carry `aria-sort` and a button named "Sort by {column}"; the icon is decorative.
- Row boxes are named and selected rows carry `aria-selected`; the select-all box is mixed while some rows are selected.
- Expand buttons carry `aria-expanded` and `aria-controls`; the chevron mirrors in right-to-left layouts.
- While loading, the table carries `aria-busy` and the skeleton rows are hidden from assistive technology.
- Every label is translatable.
