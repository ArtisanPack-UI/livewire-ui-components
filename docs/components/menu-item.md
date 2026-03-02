---
title: MenuItem Component
---

# MenuItem Component

The MenuItem component provides a way to create individual items within a menu. It supports icons, badges, links, loading states, and various customization options. It's designed to be used within the Menu component.

## Basic Usage

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
</x-artisanpack-menu>
```

## Examples

### Basic Menu Item

```php
<x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
```

### Menu Item with Badge

```php
<x-artisanpack-menu-item title="Messages" icon="o-envelope" link="/messages" badge="5" badgeClasses="badge-primary" />
```

### Active Menu Item

```php
<x-artisanpack-menu-item title="Users" icon="o-users" link="/users" :active="true" />
```

### Disabled Menu Item

```php
<x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" :disabled="true" />
```

### Menu Item with External Link

```php
<x-artisanpack-menu-item title="Documentation" icon="o-book-open" link="https://docs.example.com" :external="true" />
```

### Menu Item with Loading Spinner

```php
<x-artisanpack-menu-item 
    title="Refresh Data" 
    icon="o-arrow-path" 
    spinner="refreshData" 
    wire:click="refreshData" 
/>
```

### Menu Item with Custom Icon Classes

```php
<x-artisanpack-menu-item 
    title="Important" 
    icon="o-exclamation-triangle" 
    iconClasses="text-warning" 
    link="/important" 
/>
```

### Menu Item with Custom Background Color

```php
<x-artisanpack-menu-item 
    title="Primary Action" 
    icon="o-bolt" 
    link="/action" 
    bgColor="primary" 
/>
```

### Menu Item with Custom Hex Background Color

```php
<x-artisanpack-menu-item 
    title="Special Item" 
    icon="o-star" 
    link="/special" 
    bgColor="#4a90e2" 
/>
```

### Menu Item with Custom Content

```php
<x-artisanpack-menu-item icon="o-user">
    <div class="flex items-center justify-between w-full">
        <span>Profile</span>
        <span class="text-xs opacity-50">John Doe</span>
    </div>
</x-artisanpack-menu-item>
```

### Menu Item with Route

```php
<x-artisanpack-menu-item title="Products" icon="o-shopping-bag" route="products.index" />
```

### Menu Item with Exact Route Matching

```php
<x-artisanpack-menu-item title="Home" icon="o-home" link="/" :exact="true" />
```

### Menu Item with Click Event

```php
<x-artisanpack-menu-item 
    title="Logout" 
    icon="o-arrow-right-on-rectangle" 
    wire:click="logout" 
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the menu item element |
| `title` | string\|null | `null` | The text to display for the menu item |
| `icon` | string\|null | `null` | The icon to display before the title |
| `iconClasses` | string\|null | `null` | Additional CSS classes for the icon |
| `spinner` | string\|null | `null` | Target for the loading spinner (Livewire method name) |
| `link` | string\|null | `null` | URL to navigate to when the menu item is clicked |
| `route` | string\|null | `null` | Named route to navigate to when the menu item is clicked |
| `external` | boolean\|null | `false` | Whether the link should open in a new tab |
| `noWireNavigate` | boolean\|null | `false` | Whether to disable Livewire navigation for the link |
| `badge` | string\|null | `null` | Text to display in a badge |
| `badgeClasses` | string\|null | `null` | Additional CSS classes for the badge |
| `active` | boolean\|null | `false` | Whether the menu item is active |
| `separator` | boolean\|null | `false` | Whether to display a separator |
| `hidden` | boolean\|null | `false` | Whether the menu item is hidden |
| `disabled` | boolean\|null | `false` | Whether the menu item is disabled |
| `exact` | boolean\|null | `false` | Whether to use exact route matching |
| `bgColor` | string\|null | `null` | Background color for the menu item (theme color like 'primary' or hex color like '#ff0000') |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Custom content to display instead of the title |

## Menu Awareness

The MenuItem component is aware of its parent Menu component and can receive properties from it:

| Property | Description |
|----------|-------------|
| `activateByRoute` | Whether menu items should be activated based on the current route |
| `activeBgColor` | The background color to use for active menu items |

## Styling

The MenuItem component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a consistent appearance with proper spacing and alignment.

### Default Classes

- `my-0.5 py-1.5 px-4 hover:text-inherit whitespace-nowrap` - Base styling for menu items
- `mary-active-menu` - Applied to active menu items
- `menu-disabled` - Applied to disabled menu items
- `badge badge-sm` - Applied to badges
- `mary-hideable` - Applied to content that should be hidden when a sidebar is collapsed

## Route Matching

The MenuItem component can automatically determine if it should be marked as active based on the current route:

1. If a `route` prop is provided, it checks if the current route matches that named route
2. If a `link` prop is provided, it checks if the current URL matches that link
3. By default, it uses partial matching (e.g., `/users` will match `/users/123`)
4. When `exact` is set to `true`, it requires an exact match

## Accessibility

The MenuItem component follows accessibility best practices:

- Uses semantic HTML with proper list structure
- Provides proper link handling for navigation
- Includes visual indicators for active and disabled states
- Supports keyboard navigation
- Maintains proper focus management

## Related Components

- [Menu](Menu) - Container for menu items
- [MenuSeparator](Menu-Separator) - Separator between menu items
- [MenuSub](Menu-Sub) - Submenu container
- [MenuTitle](Menu-Title) - Title for a group of menu items