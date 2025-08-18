---
title: Header Component
---

# Header Component

The Header component provides a flexible and consistent way to create page or section headers. It supports titles, subtitles, icons, action buttons, and more, making it ideal for creating structured and visually appealing page layouts.

## Basic Usage

```php
<x-artisanpack-header title="Dashboard" />
```

## Examples

### Basic Header

```php
<x-artisanpack-header title="Dashboard" />
```

### Header with Subtitle

```php
<x-artisanpack-header 
    title="Dashboard" 
    subtitle="Welcome to your personal dashboard"
/>
```

### Header with Icon

```php
<x-artisanpack-header 
    title="Dashboard" 
    icon="o-home"
/>
```

### Header with Separator

```php
<x-artisanpack-header 
    title="Dashboard" 
    :separator="true"
/>
```

### Header with Actions

```php
<x-artisanpack-header title="Users">
    <x-slot:actions>
        <x-artisanpack-button icon="o-plus" wire:click="createUser">
            Add User
        </x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-header>
```

### Header with Middle Content

```php
<x-artisanpack-header title="Products">
    <x-slot:middle>
        <x-artisanpack-input placeholder="Search products..." wire:model.live="search" />
    </x-slot:middle>
    
    <x-slot:actions>
        <x-artisanpack-button icon="o-plus">Add Product</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-header>
```

### Header with Progress Indicator

```php
<x-artisanpack-header 
    title="Data" 
    :separator="true"
    progressIndicator="loadData"
>
    <x-slot:actions>
        <x-artisanpack-button wire:click="loadData">Refresh Data</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-header>
```

### Header with Anchor Link

```php
<x-artisanpack-header 
    title="Section Title" 
    :withAnchor="true"
/>
```

### Header with Custom Size

```php
<x-artisanpack-header 
    title="Large Title" 
    size="text-4xl"
/>
```

### Comprehensive Header Example

```php
<x-artisanpack-header 
    title="User Management" 
    subtitle="Manage your application users"
    icon="o-users"
    :separator="true"
    progressIndicator="loadUsers"
    :withAnchor="true"
>
    <x-slot:middle>
        <div class="flex gap-2">
            <x-artisanpack-input placeholder="Search users..." wire:model.live="search" />
            <x-artisanpack-select wire:model.live="role" :options="$roles" placeholder="Filter by role" />
        </div>
    </x-slot:middle>
    
    <x-slot:actions>
        <x-artisanpack-button color="ghost" wire:click="exportUsers">
            <x-artisanpack-icon name="o-arrow-down-tray" class="mr-2" />
            Export
        </x-artisanpack-button>
        
        <x-artisanpack-button wire:click="createUser">
            <x-artisanpack-icon name="o-plus" class="mr-2" />
            Add User
        </x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-header>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | string\|null | `null` | The main title text |
| `subtitle` | string\|null | `null` | Optional subtitle text |
| `separator` | boolean\|null | `false` | Whether to show a separator line below the header |
| `progressIndicator` | string\|null | `null` | Optional progress indicator for loading states |
| `progressIndicatorClass` | string | `"progress-primary"` | CSS class for the progress indicator |
| `withAnchor` | boolean\|null | `false` | Whether to make the title a clickable anchor |
| `size` | string\|null | `'text-2xl'` | Size of the title text |
| `icon` | string\|null | `null` | Optional icon to display before the title |
| `iconClasses` | string\|null | `null` | CSS classes for the icon |

## Slots

| Slot | Description |
|------|-------------|
| `middle` | Content to display in the middle section of the header |
| `actions` | Content to display in the right section of the header (typically action buttons) |

## Styling

The Header component uses Tailwind CSS for styling and provides a flexible layout that adapts to different screen sizes.

### Default Classes

- `mb-10` - Bottom margin
- `mary-header-anchor` - Applied when `withAnchor` is true
- `text-2xl font-extrabold` - Default title styling
- `text-base-content/50 text-sm mt-1` - Subtitle styling
- `border-t-[length:var(--border)] border-base-content/10` - Separator styling
- `progress progress-primary` - Progress indicator styling

## Layout Structure

The Header component uses a three-column layout:

1. **Left Section**: Contains the title, subtitle, and optional icon
2. **Middle Section**: Optional content provided via the `middle` slot
3. **Right Section**: Optional content provided via the `actions` slot

On small screens, the middle section moves below the other sections for better responsiveness.

## Accessibility

The Header component follows accessibility best practices:

- Uses semantic HTML for proper document structure
- Provides anchor links for easy navigation when `withAnchor` is enabled
- Maintains proper color contrast for readability
- Supports proper focus management for interactive elements

## Related Components

- [Card](card.md) - Content container that often uses headers
- [Button](button.md) - Used in the actions slot
- [Icon](icon.md) - Used for the header icon