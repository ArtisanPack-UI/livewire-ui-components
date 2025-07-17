# Dropdown Component

The Dropdown component provides a toggleable menu that appears when a user clicks on a trigger element. It's useful for navigation menus, action menus, and other contextual options.

## Basic Usage

```php
<x-artisanpack-dropdown label="Options">
    <li><a>Profile</a></li>
    <li><a>Settings</a></li>
    <li><a>Logout</a></li>
</x-artisanpack-dropdown>
```

## Examples

### Dropdown with Icon

```php
<x-artisanpack-dropdown label="Menu" icon="o-ellipsis-vertical">
    <li><a>Edit</a></li>
    <li><a>Delete</a></li>
</x-artisanpack-dropdown>
```

### Dropdown Positioning

```php
<!-- Right-aligned dropdown -->
<x-artisanpack-dropdown label="Right Aligned" right>
    <li><a>Option 1</a></li>
    <li><a>Option 2</a></li>
</x-artisanpack-dropdown>

<!-- Top-positioned dropdown (opens upward) -->
<x-artisanpack-dropdown label="Opens Upward" top>
    <li><a>Option 1</a></li>
    <li><a>Option 2</a></li>
</x-artisanpack-dropdown>
```

### Custom Trigger

```php
<x-artisanpack-dropdown>
    <x-slot:trigger>
        <x-artisanpack-button>
            <x-artisanpack-icon name="o-cog-6-tooth" class="mr-2" />
            Settings
        </x-artisanpack-button>
    </x-slot:trigger>
    
    <li><a>Account Settings</a></li>
    <li><a>Preferences</a></li>
    <li><a>Security</a></li>
</x-artisanpack-dropdown>
```

### Dropdown with Dividers and Headers

```php
<x-artisanpack-dropdown label="Account">
    <li class="menu-title">User</li>
    <li><a>Profile</a></li>
    <li><a>Settings</a></li>
    
    <li class="divider"></li>
    
    <li class="menu-title">Session</li>
    <li><a>Lock Account</a></li>
    <li><a>Logout</a></li>
</x-artisanpack-dropdown>
```

### Dropdown with Icons in Menu Items

```php
<x-artisanpack-dropdown label="Actions">
    <li>
        <a>
            <x-artisanpack-icon name="o-pencil" />
            Edit
        </a>
    </li>
    <li>
        <a>
            <x-artisanpack-icon name="o-duplicate" />
            Duplicate
        </a>
    </li>
    <li>
        <a>
            <x-artisanpack-icon name="o-trash" />
            Delete
        </a>
    </li>
</x-artisanpack-dropdown>
```

### Dropdown with Livewire Actions

```php
<x-artisanpack-dropdown label="Actions">
    <li><a wire:click="edit({{ $item->id }})">Edit</a></li>
    <li><a wire:click="duplicate({{ $item->id }})">Duplicate</a></li>
    <li><a wire:click="delete({{ $item->id }})" class="text-error">Delete</a></li>
</x-artisanpack-dropdown>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the dropdown element |
| `label` | string\|null | `null` | Text label for the default trigger button |
| `icon` | string\|null | `'o-chevron-down'` | Icon to display in the default trigger button |
| `right` | boolean | `false` | Whether to right-align the dropdown menu |
| `top` | boolean | `false` | Whether the dropdown should open upward |
| `noXAnchor` | boolean | `false` | Whether to disable x-anchor positioning |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the dropdown menu (typically `<li>` elements) |
| `trigger` | Custom trigger element that replaces the default button |

## Styling

The Dropdown component uses DaisyUI's dropdown component under the hood, which provides a wide range of styling options. The dropdown menu uses the DaisyUI menu component for styling menu items.

### Default Classes

- `dropdown` - Base dropdown class
- `dropdown-end` - Applied when `right` is true and `noXAnchor` is true
- `dropdown-top` - Applied when `top` is true and `noXAnchor` is true
- `dropdown-bottom` - Applied when `noXAnchor` is true
- `btn` - Applied to the default trigger button

### Menu Item Styling

Menu items should be wrapped in `<li>` elements:

```php
<li><a>Regular item</a></li>
<li><a class="active">Active item</a></li>
<li><a class="disabled">Disabled item</a></li>
<li class="menu-title">Section Title</li>
<li class="divider"></li>
```

## Accessibility

The Dropdown component follows accessibility best practices:

- Uses semantic HTML with `<details>` and `<summary>` elements
- Closes when clicking outside or pressing Escape
- Supports keyboard navigation
- Can be controlled via keyboard when focused

## Related Components

- [Menu](menu.md) - Static menu component
- [Button](button.md) - Button component
- [Icon](icon.md) - Icon component