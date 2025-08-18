---
title: ThemeToggle Component
---

# ThemeToggle Component

The ThemeToggle component provides a user interface element for switching between light and dark themes. It's a specialized version of the Swap component designed specifically for theme switching.

## Basic Usage

```php
<x-artisanpack-theme-toggle />
```

## Examples

### Basic Theme Toggle

```php
<x-artisanpack-theme-toggle />
```

### Theme Toggle with Custom Icons

```php
<x-artisanpack-theme-toggle>
    <x-slot:light-icon>
        <x-artisanpack-icon name="heroicon-o-sun" class="w-6 h-6" />
    </x-slot:light-icon>
    
    <x-slot:dark-icon>
        <x-artisanpack-icon name="heroicon-o-moon" class="w-6 h-6" />
    </x-slot:dark-icon>
</x-artisanpack-theme-toggle>
```

### Theme Toggle with Text

```php
<x-artisanpack-theme-toggle>
    <x-slot:light-icon>
        <div class="flex items-center">
            <x-artisanpack-icon name="heroicon-o-sun" class="w-5 h-5 mr-2" />
            Light Mode
        </div>
    </x-slot:light-icon>
    
    <x-slot:dark-icon>
        <div class="flex items-center">
            <x-artisanpack-icon name="heroicon-o-moon" class="w-5 h-5 mr-2" />
            Dark Mode
        </div>
    </x-slot:dark-icon>
</x-artisanpack-theme-toggle>
```

### Theme Toggle with Custom Styling

```php
<x-artisanpack-theme-toggle class="p-2 bg-base-200 rounded-full">
    <x-slot:light-icon>
        <x-artisanpack-icon name="heroicon-o-sun" class="w-6 h-6 text-yellow-500" />
    </x-slot:light-icon>
    
    <x-slot:dark-icon>
        <x-artisanpack-icon name="heroicon-o-moon" class="w-6 h-6 text-blue-400" />
    </x-slot:dark-icon>
</x-artisanpack-theme-toggle>
```

### Theme Toggle in Navigation

```php
<nav class="navbar bg-base-100">
    <div class="flex-1">
        <a class="btn btn-ghost normal-case text-xl">My App</a>
    </div>
    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li><a>Home</a></li>
            <li><a>About</a></li>
            <li><a>Contact</a></li>
            <li>
                <x-artisanpack-theme-toggle />
            </li>
        </ul>
    </div>
</nav>
```

### Theme Toggle with Custom Transition Effect

```php
<x-artisanpack-theme-toggle effect="rotate" />
```

### Theme Toggle with System Theme Detection

```php
<x-artisanpack-theme-toggle detect-system-theme />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `effect` | string | `null` | The transition effect to use (`rotate`, `flip`, `fade`) |
| `detectSystemTheme` | boolean | `false` | Whether to detect and use the system's preferred color scheme |
| `storageKey` | string | `'theme'` | The key used to store the theme preference in localStorage |
| `id` | string | `null` | Optional ID for the theme toggle element |

## Slots

| Slot | Description |
|------|-------------|
| `light-icon` | Content to show when the theme is light (and clicking will switch to dark) |
| `dark-icon` | Content to show when the theme is dark (and clicking will switch to light) |

## Behavior

The ThemeToggle component:

1. Toggles between light and dark themes when clicked
2. Applies the selected theme to the document by changing the `data-theme` attribute
3. Stores the user's preference in localStorage for persistence across page loads
4. Can detect the system's preferred color scheme when `detectSystemTheme` is enabled
5. Uses smooth transition effects when switching between themes

## Styling

The ThemeToggle component uses the Swap component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`effect`)
2. Adding custom classes via the `class` attribute
3. Customizing the light and dark icons through the slots

### Default Classes

- The toggle has appropriate sizing for use in navigation bars and other UI elements
- Transition effects are smooth and provide visual feedback when switching themes
- Default icons are clear and recognizable as representing light and dark modes

## Accessibility

The ThemeToggle component follows accessibility best practices:

- Uses appropriate ARIA attributes to indicate its purpose and current state
- Maintains proper focus management
- Supports keyboard interaction

For better accessibility:

1. Consider adding a visually hidden label that explains the purpose of the toggle
2. Ensure sufficient contrast for the toggle in both light and dark modes
3. If using custom icons, make sure they clearly represent light and dark themes

## Implementation Details

The ThemeToggle component:

1. Uses Alpine.js to handle the theme switching logic
2. Modifies the `data-theme` attribute on the `<html>` element
3. Stores the user's preference in localStorage using the key specified in the `storageKey` prop
4. Initializes with the stored preference or the system preference if `detectSystemTheme` is enabled

## Related Components

- [Swap](swap.md) - The underlying component used for toggling between states
- [Toggle](toggle.md) - Form control for boolean values
- [Icon](icon.md) - Component for displaying SVG icons