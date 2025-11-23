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

## Events

The Table component supports the following events:

| Event | Payload | Description |
|-------|---------|-------------|
| `row-selection` | `{row, selected}` | Fired when a row is selected/deselected |
| `row-selection-all` | `{selected}` | Fired when all rows are selected/deselected |
| `row-click` | row data | Fired when a row is clicked (requires `@row-click` attribute) |

## Notes

- You cannot combine `selectable` with `expandable` - they are mutually exclusive
- When using dot notation in header keys (e.g., `user.name`), the scope slots use triple underscores: `@scope('cell_user___name')`
- The table automatically handles responsive scrolling via the container class
- Row links support nested field references using dot notation (e.g., `{user.id}`)
- Sorting requires a wire:model binding to a `sortBy` array property in your component
- The table uses Alpine.js for interactive features like selection and expansion

## Related Components

- [Pagination](pagination) - Pagination controls
- [Button](button) - Interactive button element
- [Checkbox](checkbox) - Checkbox input for row selection
- [Icon](icon) - Icon component used in sort indicators
