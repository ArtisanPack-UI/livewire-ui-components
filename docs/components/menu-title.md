# MenuTitle Component

The MenuTitle component provides a way to add section titles or headers within a menu. It's useful for organizing menu items into logical groups and improving the visual hierarchy of navigation menus.

## Basic Usage

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-title title="MAIN" />
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
    
    <x-artisanpack-menu-title title="SETTINGS" />
    <x-artisanpack-menu-item title="Profile" icon="o-user" link="/profile" />
    <x-artisanpack-menu-item title="Preferences" icon="o-cog-6-tooth" link="/preferences" />
</x-artisanpack-menu>
```

## Examples

### Basic Menu Title

```php
<x-artisanpack-menu-title title="MAIN" />
```

### Menu Title with Icon

```php
<x-artisanpack-menu-title title="USERS" icon="o-users" />
```

### Menu Title with Custom Icon Styling

```php
<x-artisanpack-menu-title 
    title="IMPORTANT" 
    icon="o-exclamation-triangle" 
    iconClasses="text-warning" 
/>
```

### Menu Title with Custom Classes

```php
<x-artisanpack-menu-title 
    title="ADMIN" 
    icon="o-shield-check" 
    class="text-primary font-bold" 
/>
```

### Menu Titles in a Complete Menu Structure

```php
<x-artisanpack-menu>
    <x-artisanpack-menu-title title="MAIN" icon="o-home" />
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Analytics" icon="o-chart-bar" link="/analytics" />
    
    <x-artisanpack-menu-title title="CONTENT" icon="o-document-text" />
    <x-artisanpack-menu-item title="Pages" icon="o-document" link="/pages" />
    <x-artisanpack-menu-item title="Blog" icon="o-newspaper" link="/blog" />
    <x-artisanpack-menu-item title="Media" icon="o-photo" link="/media" />
    
    <x-artisanpack-menu-title title="USERS" icon="o-users" />
    <x-artisanpack-menu-item title="All Users" icon="o-users" link="/users" />
    <x-artisanpack-menu-item title="Add User" icon="o-user-plus" link="/users/create" />
    <x-artisanpack-menu-item title="User Groups" icon="o-user-group" link="/user-groups" />
    
    <x-artisanpack-menu-title title="SYSTEM" icon="o-cog-6-tooth" />
    <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
    <x-artisanpack-menu-item title="Logs" icon="o-document-text" link="/logs" />
</x-artisanpack-menu>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the menu title element |
| `title` | string\|null | `null` | The text to display for the menu title |
| `icon` | string\|null | `null` | The icon to display before the title |
| `iconClasses` | string\|null | `null` | Additional CSS classes for the icon |

## Styling

The MenuTitle component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a visual distinction for section titles within a menu.

### Default Classes

- `menu-title` - DaisyUI class for menu titles
- `flex items-center gap-2` - Layout for the title and icon

By default, the `menu-title` class in DaisyUI applies:
- Smaller font size
- Uppercase text
- Reduced opacity
- Proper padding and spacing

## Comparison with MenuSeparator

The MenuTitle component is similar to MenuSeparator, but with some key differences:

1. **MenuTitle** - Provides just a title without a separator line
2. **MenuSeparator** - Provides a horizontal separator line, optionally followed by a title

Choose the appropriate component based on your design needs:
- Use MenuTitle for simple section headers without visual separation
- Use MenuSeparator when you want a clear visual divider between menu sections

## Accessibility

The MenuTitle component follows accessibility best practices:

- Uses semantic HTML with proper list structure
- Uses the `menu-title` class which provides appropriate styling for section headers
- Improves navigation by organizing menu items into logical groups
- Enhances the visual hierarchy of the menu, making it easier to scan

## Related Components

- [Menu](menu.md) - Container for menu items and titles
- [MenuItem](menu-item.md) - Individual menu item
- [MenuSeparator](menu-separator.md) - Separator between menu items
- [MenuSub](menu-sub.md) - Submenu container