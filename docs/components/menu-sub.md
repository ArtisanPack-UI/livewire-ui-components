---
title: MenuSub Component
---

# MenuSub Component

The MenuSub component provides a way to create nested submenus within a menu. It allows for organizing menu items into collapsible sections, creating a hierarchical navigation structure.

## Basic Usage

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    
    <x-artisanpack-menu-sub title="Users" icon="o-users">
        <x-artisanpack-menu-item title="All Users" link="/users" />
        <x-artisanpack-menu-item title="Add User" link="/users/create" />
        <x-artisanpack-menu-item title="User Groups" link="/user-groups" />
    </x-artisanpack-menu-sub>
    
    <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
</x-artisanpack-menu>
```

## Examples

### Basic Submenu

```php
<x-artisanpack-menu-sub title="Users" icon="o-users">
    <x-artisanpack-menu-item title="All Users" link="/users" />
    <x-artisanpack-menu-item title="Add User" link="/users/create" />
</x-artisanpack-menu-sub>
```

### Initially Open Submenu

```php
<x-artisanpack-menu-sub title="Products" icon="o-shopping-bag" :open="true">
    <x-artisanpack-menu-item title="All Products" link="/products" />
    <x-artisanpack-menu-item title="Add Product" link="/products/create" />
    <x-artisanpack-menu-item title="Categories" link="/product-categories" />
</x-artisanpack-menu-sub>
```

### Disabled Submenu

```php
<x-artisanpack-menu-sub title="Reports" icon="o-chart-bar" :disabled="true">
    <x-artisanpack-menu-item title="Sales Report" link="/reports/sales" />
    <x-artisanpack-menu-item title="User Activity" link="/reports/activity" />
</x-artisanpack-menu-sub>
```

### Submenu with Custom Icon Styling

```php
<x-artisanpack-menu-sub 
    title="Important" 
    icon="o-exclamation-triangle" 
    iconClasses="text-warning" 
>
    <x-artisanpack-menu-item title="Alerts" link="/alerts" />
    <x-artisanpack-menu-item title="Notifications" link="/notifications" />
</x-artisanpack-menu-sub>
```

### Active Submenu

```php
<x-artisanpack-menu-sub 
    title="Current Section" 
    icon="o-folder-open" 
    :active="true"
>
    <x-artisanpack-menu-item title="Overview" link="/section/overview" />
    <x-artisanpack-menu-item title="Details" link="/section/details" />
</x-artisanpack-menu-sub>
```

### Submenu with Custom Background Color

```php
<x-artisanpack-menu-sub 
    title="Primary Section" 
    icon="o-bolt" 
    bgColor="primary"
>
    <x-artisanpack-menu-item title="Features" link="/features" />
    <x-artisanpack-menu-item title="Pricing" link="/pricing" />
</x-artisanpack-menu-sub>
```

### Submenu with Custom Hex Background Color

```php
<x-artisanpack-menu-sub 
    title="Special Section" 
    icon="o-star" 
    bgColor="#4a90e2"
>
    <x-artisanpack-menu-item title="VIP" link="/vip" />
    <x-artisanpack-menu-item title="Premium" link="/premium" />
</x-artisanpack-menu-sub>
```

### Nested Submenus

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-sub title="Content" icon="o-document-text">
        <x-artisanpack-menu-item title="Pages" link="/pages" />
        
        <x-artisanpack-menu-sub title="Blog">
            <x-artisanpack-menu-item title="All Posts" link="/blog/posts" />
            <x-artisanpack-menu-item title="Categories" link="/blog/categories" />
            <x-artisanpack-menu-item title="Tags" link="/blog/tags" />
        </x-artisanpack-menu-sub>
        
        <x-artisanpack-menu-item title="Media" link="/media" />
    </x-artisanpack-menu-sub>
</x-artisanpack-menu>
```

### Complex Menu Structure with Submenus

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    
    <x-artisanpack-menu-separator title="CONTENT" />
    
    <x-artisanpack-menu-sub title="Products" icon="o-shopping-bag">
        <x-artisanpack-menu-item title="All Products" link="/products" />
        <x-artisanpack-menu-item title="Add Product" link="/products/create" />
        <x-artisanpack-menu-item title="Categories" link="/product-categories" />
    </x-artisanpack-menu-sub>
    
    <x-artisanpack-menu-sub title="Users" icon="o-users">
        <x-artisanpack-menu-item title="All Users" link="/users" />
        <x-artisanpack-menu-item title="Add User" link="/users/create" />
        <x-artisanpack-menu-item title="User Groups" link="/user-groups" />
    </x-artisanpack-menu-sub>
    
    <x-artisanpack-menu-separator title="SYSTEM" />
    
    <x-artisanpack-menu-sub title="Settings" icon="o-cog-6-tooth">
        <x-artisanpack-menu-item title="General" link="/settings/general" />
        <x-artisanpack-menu-item title="Security" link="/settings/security" />
        <x-artisanpack-menu-item title="Appearance" link="/settings/appearance" />
    </x-artisanpack-menu-sub>
    
    <x-artisanpack-menu-item title="Logout" icon="o-arrow-right-on-rectangle" wire:click="logout" />
</x-artisanpack-menu>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the submenu element |
| `title` | string\|null | `null` | The text to display for the submenu title |
| `icon` | string\|null | `null` | The icon to display before the title |
| `iconClasses` | string\|null | `null` | Additional CSS classes for the icon |
| `open` | boolean | `false` | Whether the submenu should be initially open |
| `hidden` | boolean\|null | `false` | Whether the submenu should be hidden |
| `disabled` | boolean\|null | `false` | Whether the submenu should be disabled |
| `active` | boolean | `false` | Whether the submenu is active |
| `bgColor` | string\|null | `null` | Background color for the submenu (theme color like 'primary' or hex color like '#ff0000') |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the submenu (typically MenuItem components) |

## Menu Awareness

The MenuSub component is aware of its parent Menu component and can receive properties from it:

| Property | Description |
|----------|-------------|
| `activeBgColor` | The background color to use for active menu items and submenus |

## Behavior

The MenuSub component has several special behaviors:

1. **Auto-opening**: If any child menu item is active (based on the current route), the submenu will automatically open
2. **Sidebar integration**: When used within a collapsible sidebar, clicking a submenu in collapsed mode will expand the sidebar
3. **Toggle functionality**: Clicking the submenu title toggles the visibility of its child menu items

## Styling

The MenuSub component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a consistent appearance with proper spacing and alignment.

### Default Classes

- `hover:text-inherit px-4 py-1.5 my-0.5 text-inherit` - Base styling for the submenu title
- `menu-disabled` - Applied to disabled submenus
- `mary-hideable` - Applied to content that should be hidden when a sidebar is collapsed

## Accessibility

The MenuSub component follows accessibility best practices:

- Uses semantic HTML with proper list structure
- Uses the `<details>` and `<summary>` elements for native disclosure functionality
- Provides visual indicators for active and disabled states
- Supports keyboard navigation
- Maintains proper focus management

## Related Components

- [Menu](menu.md) - Container for menu items and submenus
- [MenuItem](menu-item.md) - Individual menu item
- [MenuSeparator](menu-separator.md) - Separator between menu items
- [MenuTitle](menu-title.md) - Title for a group of menu items