---
title: Table Component
---

# Table Component

The Table component is a powerful, feature-rich data table for displaying structured information with support for sorting, row selection, expandable rows, custom formatting, pagination, and more.

## Basic Usage

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
        ];

        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Editor'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'User'],
        ];

        return [
            'headers' => $headers,
            'users' => $users,
        ];
    }
}; ?>

<x-artisanpack-table :headers="$headers" :rows="$users" />
```

## Examples

### Table with Sortable Columns

The table supports sorting with a wire:model binding for the sortBy parameter:

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function with(): array
    {
        $headers = [
            ['key' => 'id', 'label' => '#', 'sortable' => false],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'created_at', 'label' => 'Created'],
        ];

        // Apply sorting logic
        $users = User::query()
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->get();

        return [
            'headers' => $headers,
            'users' => $users,
        ];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    :sort-by="$sortBy"
    wire:model="sortBy" />
```

### Table with Custom Cell Rendering

Use `@scope` directives to customize individual cell rendering:

```php
<x-artisanpack-table :headers="$headers" :rows="$users">
    @scope('cell_name', $user)
        <div class="flex items-center gap-2">
            <x-artisanpack-avatar :image="$user->avatar" class="size-8" />
            <span class="font-semibold">{{ $user->name }}</span>
        </div>
    @endscope

    @scope('cell_email', $user)
        <a href="mailto:{{ $user->email }}" class="link link-primary">
            {{ $user->email }}
        </a>
    @endscope

    @scope('cell_status', $user)
        <x-artisanpack-badge
            :value="$user->status"
            class="{{ $user->status === 'active' ? 'badge-success' : 'badge-error' }}" />
    @endscope
</x-artisanpack-table>
```

### Table with Custom Header Rendering

Customize header cells using `@scope('header_fieldname')`:

```php
<x-artisanpack-table :headers="$headers" :rows="$users">
    @scope('header_name', $header)
        <div class="flex items-center gap-2">
            <x-artisanpack-icon name="o-user" class="size-4" />
            {{ $header['label'] }}
        </div>
    @endscope

    @scope('header_email', $header)
        <div class="flex items-center gap-2">
            <x-artisanpack-icon name="o-envelope" class="size-4" />
            {{ $header['label'] }}
        </div>
    @endscope
</x-artisanpack-table>
```

### Table with Actions Column

Add an actions column using the `@scope('actions')` directive:

```php
<x-artisanpack-table :headers="$headers" :rows="$users">
    @scope('actions', $user)
        <div class="flex gap-2">
            <x-artisanpack-button
                icon="o-pencil"
                wire:click="edit({{ $user->id }})"
                class="btn-sm btn-ghost" />
            <x-artisanpack-button
                icon="o-trash"
                wire:click="delete({{ $user->id }})"
                class="btn-sm btn-ghost text-error" />
        </div>
    @endscope
</x-artisanpack-table>
```

### Table with Striped Rows

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    striped />
```

### Table with Row Selection

Enable row selection with checkboxes:

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $selectedUsers = [];

    public function deleteSelected()
    {
        User::whereIn('id', $this->selectedUsers)->delete();
        $this->selectedUsers = [];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    selectable
    selectable-key="id"
    wire:model="selectedUsers" />

<div class="mt-4" x-show="$wire.selectedUsers.length > 0">
    <x-artisanpack-button
        wire:click="deleteSelected"
        class="btn-error">
        Delete Selected ({{ count($selectedUsers) }})
    </x-artisanpack-button>
</div>
```

### Table with Expandable Rows

Create expandable rows for additional details:

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $expandedRows = [];
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    expandable
    expandable-key="id"
    expandable-condition="can_expand"
    wire:model="expandedRows">

    @scope('expansion', $user)
        <div class="p-4 bg-base-200">
            <h4 class="font-bold mb-2">Additional Details</h4>
            <p>Phone: {{ $user->phone }}</p>
            <p>Address: {{ $user->address }}</p>
            <p>Department: {{ $user->department }}</p>
        </div>
    @endscope
</x-artisanpack-table>
```

### Table with Row Links

Make entire rows clickable with the `link` prop:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    link="/users/{id}/edit" />
```

You can use any field from the row data in the link pattern:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    link="/users/{id}/profile/{name}" />
```

To disable links on specific columns:

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'actions', 'label' => 'Actions', 'disableLink' => true],
];
?>
```

### Table with Pagination

```php
<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public int $perPage = 10;

    public function with(): array
    {
        return [
            'users' => User::paginate($this->perPage),
        ];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    with-pagination
    per-page="perPage"
    :per-page-values="[5, 10, 20, 50]" />
```

### Table with Data Formatting

Format cell data using the `format` key in headers:

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Name'],
    [
        'key' => 'created_at',
        'label' => 'Created',
        'format' => ['date', 'M d, Y']
    ],
    [
        'key' => 'price',
        'label' => 'Price',
        'format' => ['currency', '20', '$']
    ],
];
?>
```

You can also use a custom closure for formatting:

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Name'],
    [
        'key' => 'status',
        'label' => 'Status',
        'format' => fn($row, $field) => strtoupper($field)
    ],
];
?>
```

### Table with Row Decoration

Apply conditional classes to rows:

```php
<?php
$rowDecoration = [
    'bg-success/10' => fn($row) => $row->status === 'active',
    'bg-error/10' => fn($row) => $row->status === 'inactive',
    'bg-warning/10' => fn($row) => $row->status === 'pending',
];
?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    :row-decoration="$rowDecoration" />
```

### Table with Cell Decoration

Apply conditional classes to specific cells:

```php
<?php
$cellDecoration = [
    'email' => [
        'text-success' => fn($row) => $row->email_verified,
        'text-error' => fn($row) => !$row->email_verified,
    ],
    'status' => [
        'font-bold text-success' => fn($row) => $row->status === 'active',
        'font-bold text-error' => fn($row) => $row->status === 'inactive',
    ],
];
?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    :cell-decoration="$cellDecoration" />
```

### Table with Custom Empty State

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    show-empty-text
    empty-text="No users found. Create your first user to get started.">

    @scope('empty')
        <div class="text-center py-8">
            <x-artisanpack-icon name="o-users" class="size-16 mx-auto text-base-content/20 mb-4" />
            <p class="text-lg font-semibold mb-2">No users yet</p>
            <p class="text-base-content/60 mb-4">Get started by creating your first user.</p>
            <x-artisanpack-button wire:click="createUser">Create User</x-artisanpack-button>
        </div>
    @endscope
</x-artisanpack-table>
```

### Table with Hidden Columns

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#', 'hidden' => true],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
    ['key' => 'internal_code', 'label' => 'Code', 'hidden' => true],
];
?>
```

### Table with Custom Column Classes

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#', 'class' => 'w-16 text-center'],
    ['key' => 'name', 'label' => 'Name', 'class' => 'font-semibold'],
    ['key' => 'email', 'label' => 'Email', 'class' => 'text-sm'],
    ['key' => 'created_at', 'label' => 'Created', 'class' => 'text-right'],
];
?>
```

### Table without Headers

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    no-headers />
```

### Table without Hover Effect

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    no-hover />
```

### Table with Footer

```php
<x-artisanpack-table :headers="$headers" :rows="$users">
    <x-slot:footer>
        <tr>
            <td colspan="3" class="text-right font-bold">Total:</td>
            <td class="font-bold">{{ $users->sum('amount') }}</td>
        </tr>
    </x-slot:footer>
</x-artisanpack-table>
```

### Table with Row Click Event

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    @row-click="$wire.showUserDetails($event.detail)" />
```

### Table with Custom Sort Column

By default, sorting uses the header's `key` value. You can specify a different column to sort by:

```php
<?php
$headers = [
    ['key' => 'user.name', 'label' => 'Name', 'sortBy' => 'users.name'],
    ['key' => 'user.email', 'label' => 'Email', 'sortBy' => 'users.email'],
];
?>
```

### Drag-and-Drop Sorting (Livewire 4+)

The table supports native drag-and-drop sorting using Livewire 4's `wire:sort` directive. This feature is automatically enabled when using Livewire 4 and the `sortable` prop.

**Note:** This feature requires Livewire 4 or higher. On Livewire 3, the `sortable` prop is gracefully ignored.

#### Basic Drag-and-Drop

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $tasks = [
        ['id' => 1, 'name' => 'Task 1', 'order' => 1],
        ['id' => 2, 'name' => 'Task 2', 'order' => 2],
        ['id' => 3, 'name' => 'Task 3', 'order' => 3],
    ];

    public function updateOrder(array $orderedIds): void
    {
        // $orderedIds contains the new order of IDs
        foreach ($orderedIds as $index => $id) {
            // Update your database/model with new order
            Task::where('id', $id)->update(['order' => $index + 1]);
        }

        // Refresh tasks
        $this->tasks = Task::orderBy('order')->get()->toArray();
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$tasks"
    sortable
    wire:sort="updateOrder" />
```

#### Drag-and-Drop with Sort Handle

Use a dedicated handle for dragging instead of the entire row:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$tasks"
    sortable
    sort-handle
    wire:sort="updateOrder" />
```

You can also provide a custom sort handle using the `sortHandleSlot`:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$tasks"
    sortable
    sort-handle
    wire:sort="updateOrder">

    @scope('sortHandleSlot', $row)
        <x-artisanpack-icon
            name="o-arrows-up-down"
            class="cursor-move p-2 w-8 h-8 text-primary hover:text-primary-focus" />
    @endscope
</x-artisanpack-table>
```

#### Cross-List Dragging

Enable dragging items between multiple tables using the `sort-group` prop:

```php
{{-- Active Tasks Table --}}
<x-artisanpack-table
    :headers="$headers"
    :rows="$activeTasks"
    sortable
    sort-group="tasks"
    wire:sort="updateActiveOrder" />

{{-- Completed Tasks Table --}}
<x-artisanpack-table
    :headers="$headers"
    :rows="$completedTasks"
    sortable
    sort-group="tasks"
    wire:sort="updateCompletedOrder" />
```

#### Custom Sortable Key

By default, the `keyBy` prop is used for item identification. You can specify a different key:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$tasks"
    sortable
    sortable-key="uuid"
    wire:sort="updateOrder" />
```

### Infinite Scroll (Livewire 4+)

The table supports infinite scrolling using Livewire 4's `wire:intersect` directive. This feature automatically loads more data when the user scrolls to the bottom of the table.

**Note:** This feature requires Livewire 4 or higher. On Livewire 3, the `infinite-scroll` prop is gracefully ignored and you should use standard pagination instead.

#### Basic Infinite Scroll

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public int $page = 1;
    public int $perPage = 20;
    public array $users = [];

    public function mount(): void
    {
        $this->loadMore();
    }

    public function loadMore(): void
    {
        $newUsers = User::query()
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get()
            ->toArray();

        $this->users = array_merge($this->users, $newUsers);
        $this->page++;
    }

    public function hasMorePages(): bool
    {
        return User::count() > count($this->users);
    }

    public function with(): array
    {
        return [
            'headers' => [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
            ],
        ];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    :has-more-pages="$this->hasMorePages()" />
```

#### Custom Load Method

Specify a custom method name to call when loading more data:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    infinite-scroll-method="fetchNextPage"
    :has-more-pages="$hasMore" />
```

#### Using Modifiers

The `infinite-scroll-modifier` prop supports Livewire 4's intersection modifiers:

- `.once` - Only trigger once
- `.half` - Trigger when element is 50% visible
- `.full` - Trigger when element is fully visible

```php
{{-- Load more only once when scrolled into view --}}
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    infinite-scroll-modifier="once"
    :has-more-pages="$hasMore" />

{{-- Trigger when the loading indicator is half visible --}}
<x-artisanpack-table
    :headers="$headers"
    :rows="$items"
    infinite-scroll
    infinite-scroll-modifier="half"
    :has-more-pages="$hasMore" />
```

#### Custom Loading Text

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    infinite-scroll-text="Loading more users..."
    :has-more-pages="$hasMore" />
```

#### Combining with Other Features

Infinite scroll works alongside other table features:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    striped
    infinite-scroll
    :has-more-pages="$hasMore">

    @scope('cell_name', $user)
        <div class="flex items-center gap-2">
            <x-artisanpack-avatar :image="$user->avatar" class="size-8" />
            <span>{{ $user->name }}</span>
        </div>
    @endscope

    @scope('actions', $user)
        <x-artisanpack-button icon="o-eye" wire:click="view({{ $user->id }})" class="btn-sm" />
    @endscope
</x-artisanpack-table>
```

### Data Export (v2.0+)

The Table component supports exporting data to CSV, Excel (XLSX), and PDF formats. Export can be handled client-side (CSV) or server-side (XLSX, PDF) using the `WithTableExport` trait.

#### Basic Export

Enable export functionality with the `exportable` prop:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable />
```

This will display an export button that downloads the table data as a CSV file.

#### Multiple Export Formats

Specify which export formats to allow using the `export-formats` prop:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable
    :export-formats="['csv', 'xlsx', 'pdf']" />
```

When multiple formats are enabled, a dropdown menu appears with all available options.

**Note:** XLSX export requires `phpoffice/phpspreadsheet` to be installed. PDF export requires `barryvdh/laravel-dompdf`. If these packages are not installed, those export options will be automatically hidden.

#### Custom Export Filename

Set a custom filename for the exported file:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable
    export-filename="users-report-{{ date('Y-m-d') }}" />
```

#### Server-Side Export with WithTableExport Trait

For XLSX and PDF exports, you need to use the `WithTableExport` trait in your Livewire component. This handles the server-side generation of these file formats.

```php
<?php

use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;
use Livewire\Volt\Component;

new class extends Component {
    use WithTableExport;

    public function with(): array
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
        ];

        $users = User::all()->toArray();

        return [
            'headers' => $headers,
            'users' => $users,
        ];
    }

    /**
     * Required: Provide export data for the table.
     */
    public function getTableExportData(string $tableId = 'default'): array
    {
        return [
            'headers' => [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
                ['key' => 'role', 'label' => 'Role'],
            ],
            'rows' => User::all()->toArray(),
            'filename' => 'users-export-' . date('Y-m-d'),
        ];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable
    :export-formats="['csv', 'xlsx', 'pdf']" />
```

#### Multiple Tables with Export

When you have multiple exportable tables on the same page, use the `id` prop to distinguish them:

```php
<?php

use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;
use Livewire\Volt\Component;

new class extends Component {
    use WithTableExport;

    public function getTableExportData(string $tableId = 'default'): array
    {
        return match($tableId) {
            'users-table' => [
                'headers' => $this->userHeaders,
                'rows' => User::all()->toArray(),
                'filename' => 'users-export',
            ],
            'orders-table' => [
                'headers' => $this->orderHeaders,
                'rows' => Order::all()->toArray(),
                'filename' => 'orders-export',
            ],
            default => ['headers' => [], 'rows' => []],
        };
    }
}; ?>

<x-artisanpack-table
    id="users-table"
    :headers="$userHeaders"
    :rows="$users"
    exportable
    :export-formats="['csv', 'xlsx']" />

<x-artisanpack-table
    id="orders-table"
    :headers="$orderHeaders"
    :rows="$orders"
    exportable
    :export-formats="['csv', 'pdf']" />
```

#### Export with Formatted Data

The exporter respects your header configuration, including custom formatting:

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Full Name'],
    [
        'key' => 'created_at',
        'label' => 'Created',
        'format' => ['date', 'M d, Y']
    ],
    [
        'key' => 'price',
        'label' => 'Price',
        'format' => ['currency', '2', '$']
    ],
];
?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$products"
    exportable />
```

#### Hidden Columns in Export

Columns marked as `hidden` in the headers will be excluded from exports:

```php
<?php
$headers = [
    ['key' => 'id', 'label' => '#', 'hidden' => true], // Not exported
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
    ['key' => 'internal_code', 'label' => 'Code', 'hidden' => true], // Not exported
];
?>
```

#### Installing Export Dependencies

For full export functionality, install the optional dependencies:

```bash
# For Excel (XLSX) export
composer require phpoffice/phpspreadsheet

# For PDF export
composer require barryvdh/laravel-dompdf
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `headers` | array | `[]` | Array of header definitions (required) |
| `rows` | array\|ArrayAccess | `[]` | Data rows to display (required) |
| `id` | string\|null | `null` | Optional ID for the table |
| `striped` | bool | `false` | Whether to use striped/zebra rows |
| `no-headers` | bool | `false` | Whether to hide table headers |
| `selectable` | bool | `false` | Whether rows can be selected with checkboxes |
| `selectable-key` | string | `'id'` | Key to use for selectable rows |
| `expandable` | bool | `false` | Whether rows can be expanded |
| `expandable-key` | string | `'id'` | Key to use for expandable rows |
| `expandable-condition` | mixed | `null` | Condition field to determine if a row is expandable |
| `link` | string\|null | `null` | URL pattern for row links (e.g., `/users/{id}/edit`) |
| `with-pagination` | bool | `false` | Whether to show pagination controls |
| `per-page` | string\|null | `null` | Wire model name for items per page |
| `per-page-values` | array | `[10, 20, 50, 100]` | Available options for items per page |
| `sort-by` | array | `[]` | Sorting configuration with `column` and `direction` keys |
| `row-decoration` | array | `[]` | Row decoration rules (class => closure) |
| `cell-decoration` | array | `[]` | Cell decoration rules (key => [class => closure]) |
| `show-empty-text` | bool | `false` | Whether to show text when no records found |
| `empty-text` | string | `'No records found.'` | Text to display when no records found |
| `container-class` | string | `'overflow-x-auto'` | CSS class for the table container |
| `no-hover` | bool | `false` | Whether to disable hover effect on rows |
| `key-by` | string | `'id'` | Field to use as unique key for rows |
| `sortable` | bool | `false` | Whether rows can be drag-and-drop sorted (Livewire 4+ only) |
| `sortable-key` | string\|null | `null` | Key to use for sortable item identification (defaults to `key-by`) |
| `sort-group` | string\|null | `null` | Group name for cross-list dragging (`wire:sort:group`) |
| `sort-handle` | bool | `false` | Whether to use a sort handle instead of the entire row |
| `infinite-scroll` | bool | `false` | Whether to enable infinite scrolling (Livewire 4+ only) |
| `infinite-scroll-method` | string | `'loadMore'` | The Livewire method to call when loading more data |
| `infinite-scroll-modifier` | string\|null | `null` | Modifier for wire:intersect (`.once`, `.half`, `.full`) |
| `infinite-scroll-text` | string | `'Scroll for more'` | Text to display while loading more items |
| `has-more-pages` | bool | `true` | Whether there are more pages to load |
| `exportable` | bool | `false` | Whether to enable data export functionality (v2.0+) |
| `export-formats` | array | `['csv']` | Available export formats: `csv`, `xlsx`, `pdf` (v2.0+) |
| `export-filename` | string\|null | `null` | Custom filename for exports (without extension) (v2.0+) |

## Header Configuration

Each header in the `headers` array can have the following keys:

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `key` | string | required | The field key to display from row data |
| `label` | string | required | The header label text |
| `class` | string | `''` | Additional CSS classes for the column |
| `sortable` | bool | `true` | Whether this column is sortable (when sortBy is enabled) |
| `sortBy` | string | `null` | Custom column name to use for sorting (defaults to `key`) |
| `hidden` | bool | `false` | Whether to hide this column |
| `disableLink` | bool | `false` | Whether to disable row links for this column |
| `format` | array\|callable | `null` | Formatting rules for cell values |

## Slots

| Slot | Description |
|------|-------------|
| `cell_{fieldname}` | Custom rendering for a specific cell (use `@scope`) |
| `header_{fieldname}` | Custom rendering for a specific header (use `@scope`) |
| `actions` | Content for the actions column (use `@scope`) |
| `expansion` | Content for expanded row (use `@scope`) |
| `empty` | Content to display when there are no rows |
| `footer` | Content for the table footer |
| `sortHandleSlot` | Custom content for the sort handle (use `@scope`, Livewire 4+ only) |

## Events

The Table component supports the following events:

| Event | Payload | Description |
|-------|---------|-------------|
| `row-selection` | `{row, selected}` | Fired when a row is selected/deselected |
| `row-selection-all` | `{selected}` | Fired when all rows are selected/deselected |
| `row-click` | row data | Fired when a row is clicked (requires `@row-click` attribute) |
| `table-exported` | `{format, filename}` | Fired when a client-side export (CSV) completes successfully (v2.0+) |
| `table-export-error` | `{error}` | Fired when an export fails (v2.0+) |
| `table-export-request` | `{format, tableId}` | Dispatched to request server-side export (XLSX, PDF) (v2.0+) |

## Notes

- You cannot combine `selectable` with `expandable` - they are mutually exclusive
- When using dot notation in header keys (e.g., `user.name`), the scope slots use triple underscores: `@scope('cell_user___name')`
- The table automatically handles responsive scrolling via the container class
- Row links support nested field references using dot notation (e.g., `{user.id}`)
- Sorting requires a wire:model binding to a `sortBy` array property in your component
- The table uses Alpine.js for interactive features like selection and expansion
- Drag-and-drop sorting (`sortable` prop) requires Livewire 4 or higher and is gracefully ignored on Livewire 3
- Infinite scroll (`infinite-scroll` prop) requires Livewire 4 or higher and is gracefully ignored on Livewire 3
- Export functionality requires v2.0+. XLSX export requires `phpoffice/phpspreadsheet`, PDF export requires `barryvdh/laravel-dompdf`
- For server-side exports (XLSX, PDF), use the `WithTableExport` trait in your Livewire component

## Related Components

- [Pagination](pagination) - Pagination controls
- [Button](button) - Interactive button element
- [Checkbox](checkbox) - Checkbox input for row selection
- [Icon](icon) - Icon component used in sort indicators
