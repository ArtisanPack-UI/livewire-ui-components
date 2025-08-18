---
title: MenuSeparator Component
---

# MenuSeparator Component

The MenuSeparator component provides a visual separator between groups of menu items. It can be used to create logical sections within a menu and optionally include a section title with an icon.

## Basic Usage

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
    
    <x-artisanpack-menu-separator />
    
    <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
    <x-artisanpack-menu-item title="Logout" icon="o-arrow-right-on-rectangle" wire:click="logout" />
</x-artisanpack-menu>
```

## Examples

### Basic Separator

```php
<x-artisanpack-menu-separator />
```

### Separator with Title

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
    
    <x-artisanpack-menu-separator title="SETTINGS" />
    
    <x-artisanpack-menu-item title="Profile" icon="o-user" link="/profile" />
    <x-artisanpack-menu-item title="Preferences" icon="o-cog-6-tooth" link="/preferences" />
</x-artisanpack-menu>
```

### Separator with Icon and Title

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
    
    <x-artisanpack-menu-separator title="ACCOUNT" icon="o-user-circle" />
    
    <x-artisanpack-menu-item title="Profile" icon="o-user" link="/profile" />
    <x-artisanpack-menu-item title="Logout" icon="o-arrow-right-on-rectangle" wire:click="logout" />
</x-artisanpack-menu>
```

### Separator with Custom Icon Styling

```php
<x-artisanpack-menu-separator 
    title="IMPORTANT" 
    icon="o-exclamation-triangle" 
    iconClasses="text-warning" 
/>
```

### Multiple Separators for Organizing a Menu

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-separator title="MAIN" icon="o-home" />
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Analytics" icon="o-chart-bar" link="/analytics" />
    
    <x-artisanpack-menu-separator title="MANAGEMENT" icon="o-users" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
    <x-artisanpack-menu-item title="Products" icon="o-shopping-bag" link="/products" />
    <x-artisanpack-menu-item title="Orders" icon="o-shopping-cart" link="/orders" />
    
    <x-artisanpack-menu-separator title="SYSTEM" icon="o-cog-6-tooth" />
    <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
    <x-artisanpack-menu-item title="Logs" icon="o-document-text" link="/logs" />
</x-artisanpack-menu>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the separator element |
| `title` | string\|null | `null` | Optional title text to display after the separator |
| `icon` | string\|null | `null` | Optional icon to display before the title |
| `iconClasses` | string\|null | `null` | Additional CSS classes for the icon |

## Styling

The MenuSeparator component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a visual separation between menu items with an optional title.

### Default Classes

- `my-3 border-t-[length:var(--border)] border-base-content/10` - Styling for the separator line
- `menu-title text-inherit uppercase` - Styling for the title
- `flex items-center gap-2` - Layout for the title and icon

## Accessibility

The MenuSeparator component follows accessibility best practices:

- Uses semantic HTML with proper list structure
- Uses an `<hr>` element for the separator, which has semantic meaning
- Uses the `menu-title` class for section titles, which helps with organization
- Provides visual separation between groups of menu items, improving usability

## Related Components

- [Menu](menu.md) - Container for menu items
- [MenuItem](menu-item.md) - Individual menu item
- [MenuSub](menu-sub.md) - Submenu container
- [MenuTitle](menu-title.md) - Title for a group of menu items