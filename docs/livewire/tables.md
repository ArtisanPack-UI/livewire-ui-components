---
title: Tables
menu_order: 4
---

# Tables

[Table](https://docs.artisanpackui.dev/documentation/design-system/components/table) renders rows of structured data in real table markup, with sorting, row selection, expandable rows, loading and empty states, and Pagination in the footer. The component page covers its props and parts. This page covers the Blade and Livewire side: scoped slots, binding its state to a component, and exporting.

## Scoped slots

Cells, headers, row actions and expanded rows are scoped slots, written with `@scope`. The slot receives the row (or the header) for each cell it renders:

```blade
<x-artisanpack-table :headers="$headers" :rows="$users" :title="__( 'Users' )">
    @scope( 'cell_name', $user )
        <strong>{{ $user->name }}</strong>
    @endscope

    @scope( 'header_email', $header )
        {{ $header['label'] }}
    @endscope

    @scope( 'actions', $user )
        <x-artisanpack-button size="sm" variant="ghost" wire:click="edit( {{ $user->id }} )">{{ __( 'Edit' ) }}</x-artisanpack-button>
    @endscope
</x-artisanpack-table>
```

| Slot | Renders |
|---|---|
| `cell_<key>` | The cell for a column, by its `key`. A dot in the key is fine: `cell_author.name` |
| `header_<key>` | A column's header |
| `actions` | A last column of row actions |
| `expansion` | The expanded content of a row, with `expandable` |

Variables from your view aren't in scope inside `@scope` automatically. Pass them after the row: `@scope( 'cell_status', $order, $statuses )`.

## Binding state to Livewire

```php
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public array $sortBy = [ 'column' => 'name', 'direction' => 'asc' ];

    public array $selected = [];

    public int $perPage = 20;

    public function render(): View
    {
        return view( 'livewire.users', [
            'users' => User::query()
                ->orderBy( $this->sortBy['column'], $this->sortBy['direction'] )
                ->paginate( $this->perPage ),
        ] );
    }
}
```

```blade
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    :sort-by="$sortBy"
    selectable
    wire:model="selected"
    paginated
    per-page="perPage"
/>
```

- **Sorting.** With `sort-by` set, the headers sort. Inside a Livewire component with a public `sortBy` property, clicking a header sets it; the table also dispatches `sort-change`. Validate the column before you order by it.
- **Selection.** `selectable` adds row checkboxes and a select-all box. `wire:model` binds the selected row keys (`key-by`, `id` by default).
- **Pagination.** Pass a paginator as `rows` and set `paginated`: the footer renders Pagination from it, and inside a component using `WithPagination` its links call `gotoPage()`. `per-page` names the Livewire property the "Rows per page" select binds, with the choices in `per-page-values` (`[10, 20, 50, 100]`).
- **Reordering.** On Livewire 4, the 2.x `sortable` attribute makes rows draggable with `wire:sort`, calling `updateOrder` or the method you name in `wire:sort`.

## Exporting

The 2.x export toolbar is gone, but the export classes remain for downloads you trigger yourself.

### The `WithTableExport` trait

Add the trait to your component and return the data to export from `getTableExportData()`:

```php
use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;

class Users extends Component
{
    use WithTableExport;

    public function getTableExportData( string $tableId = 'default' ): array
    {
        return [
            'headers'  => $this->headers,
            'rows'     => User::query()->get()->toArray(),
            'filename' => 'users-' . now()->format( 'Y-m-d' ),
        ];
    }
}
```

```blade
<x-artisanpack-button wire:click="exportTableToCsv( 'users' )">{{ __( 'Export CSV' ) }}</x-artisanpack-button>
<x-artisanpack-button wire:click="exportTableToXlsx( 'users' )">{{ __( 'Export Excel' ) }}</x-artisanpack-button>
<x-artisanpack-button wire:click="handleTableExport( 'pdf', 'users' )">{{ __( 'Export PDF' ) }}</x-artisanpack-button>
```

The `$tableId` you pass reaches `getTableExportData()`, so one component can export several tables. If the export fails, the exception is reported and the action returns a generic error response. `canExportXlsx()` tells you whether Excel export is available, to hide its button.

### `TableExporter`

To export from anywhere else, such as a controller, use the exporter directly:

```php
use ArtisanPack\LivewireUiComponents\Support\TableExporter;

return TableExporter::make( $headers, $rows, 'users' )->export( 'csv' );
```

`export()` takes `csv`, `xlsx` (or `excel`) and `pdf`. Headers are the Table's column arrays (their `label` is used) or plain strings. For PDFs, `setPdfTitle()`, `setPdfOrientation()`, `setPdfPaperSize()`, `setPdfHeader()` and `setPdfFooter()` set the page.

| Format | Needs |
|---|---|
| CSV | Nothing |
| XLSX | `composer require phpoffice/phpspreadsheet` |
| PDF | `composer require barryvdh/laravel-dompdf` |

`TableExporter::supportsXlsx()` and `TableExporter::supportsPdf()` check for them.

## Extending columns

The `ap.livewireUiComponents.tableColumns` filter lets other code add or reshape a table's columns, keyed by the table's `id`. See [Extension Hooks](Advanced-Extension-Hooks).
