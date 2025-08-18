---
title: Table Component
---

# Table Component

The Table component is a powerful data display element for presenting structured information in rows and columns. It supports sorting, pagination, and various styling options.

## Basic Usage

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']">
    <tr>
        <td>John Doe</td>
        <td>john@example.com</td>
        <td>Admin</td>
    </tr>
    <tr>
        <td>Jane Smith</td>
        <td>jane@example.com</td>
        <td>Editor</td>
    </tr>
    <tr>
        <td>Bob Johnson</td>
        <td>bob@example.com</td>
        <td>User</td>
    </tr>
</x-artisanpack-table>
```

## Examples

### Table with Custom Headers

```php
<x-artisanpack-table>
    <x-slot:headers>
        <th>Name</th>
        <th>Email</th>
        <th class="text-right">Actions</th>
    </x-slot:headers>
    
    <tr>
        <td>John Doe</td>
        <td>john@example.com</td>
        <td class="text-right">
            <x-artisanpack-button size="sm">Edit</x-artisanpack-button>
        </td>
    </tr>
    <tr>
        <td>Jane Smith</td>
        <td>jane@example.com</td>
        <td class="text-right">
            <x-artisanpack-button size="sm">Edit</x-artisanpack-button>
        </td>
    </tr>
</x-artisanpack-table>
```

### Table with Pagination

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']">
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
    
    <x-slot:pagination>
        {{ $users->links() }}
    </x-slot:pagination>
</x-artisanpack-table>
```

### Table with Sorting

```php
<x-artisanpack-table :headers="[
    ['label' => 'Name', 'sortable' => true, 'field' => 'name'],
    ['label' => 'Email', 'sortable' => true, 'field' => 'email'],
    ['label' => 'Role', 'sortable' => true, 'field' => 'role'],
]" sort-by="name" sort-dir="asc" wire:sort="sortUsers">
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Table with Zebra Striping

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']" zebra>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Compact Table

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']" compact>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Table with Hover Effect

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']" hover>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Table with Borders

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']" bordered>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Table with Row Selection

```php
<x-artisanpack-table :headers="['Select', 'Name', 'Email', 'Role']" wire:model="selectedUsers">
    @foreach($users as $user)
        <tr>
            <td>
                <x-artisanpack-checkbox value="{{ $user->id }}" />
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>

<div class="mt-4">
    <x-artisanpack-button wire:click="deleteSelected" color="error" :disabled="empty($selectedUsers)">
        Delete Selected
    </x-artisanpack-button>
</div>
```

### Table with Loading State

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']" loading>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

### Table with Empty State

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']">
    @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center py-4">
                No users found.
            </td>
        </tr>
    @endforelse
</x-artisanpack-table>
```

### Table with Custom Row Classes

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Status']">
    <tr class="bg-success text-success-content bg-opacity-20">
        <td>John Doe</td>
        <td>john@example.com</td>
        <td>Active</td>
    </tr>
    <tr class="bg-error text-error-content bg-opacity-20">
        <td>Jane Smith</td>
        <td>jane@example.com</td>
        <td>Inactive</td>
    </tr>
    <tr class="bg-warning text-warning-content bg-opacity-20">
        <td>Bob Johnson</td>
        <td>bob@example.com</td>
        <td>Pending</td>
    </tr>
</x-artisanpack-table>
```

### Responsive Table

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role', 'Status', 'Created At']" responsive>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ $user->status }}</td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `headers` | array | `[]` | Array of header labels or objects with `label`, `sortable`, and `field` properties |
| `sort-by` | string | `null` | Field to sort by |
| `sort-dir` | string | `'asc'` | Sort direction (`asc` or `desc`) |
| `zebra` | boolean | `false` | Whether to apply zebra striping to rows |
| `compact` | boolean | `false` | Whether to use reduced padding for a more compact appearance |
| `hover` | boolean | `false` | Whether to apply hover effect to rows |
| `bordered` | boolean | `false` | Whether to display borders around cells |
| `loading` | boolean | `false` | Whether to display the table in a loading state |
| `responsive` | boolean | `true` | Whether the table should be responsive on small screens |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The table rows content |
| `headers` | Custom header content (alternative to using the `headers` prop) |
| `pagination` | Content for the pagination section |
| `empty` | Content to display when there are no rows |

## Events

The Table component supports Livewire events for sorting and selection:

- `wire:sort` - Event triggered when a sortable column is clicked
- `wire:model` - For binding selected rows when using row selection

## Styling

The Table component uses DaisyUI's table component under the hood, which provides a consistent styling with other components. You can customize the appearance of tables by:

1. Using the provided props (`zebra`, `compact`, `hover`, `bordered`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-table 
    :headers="['Name', 'Email', 'Role']" 
    class="border border-primary rounded-lg overflow-hidden"
>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

## Accessibility

The Table component follows accessibility best practices:

- Uses semantic HTML table structure (`<table>`, `<thead>`, `<tbody>`, `<th>`, `<td>`)
- Includes appropriate ARIA attributes for sorting and loading states
- Maintains proper heading hierarchy
- Ensures adequate color contrast
- Provides responsive behavior for small screens

## Related Components

- [Pagination](pagination.md) - Pagination controls
- [Button](button.md) - Interactive button element
- [Checkbox](checkbox.md) - Checkbox input for row selection
