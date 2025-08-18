---
title: Menu Component
---

# Menu Component

The Menu component provides a vertical navigation menu with support for titles, separators, and nested submenus.

## Basic Usage

```blade
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
</x-artisanpack-menu>
```

## With Title and Separator

```blade
<x-artisanpack-menu title="Main Navigation" separator>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
</x-artisanpack-menu>
```

## With Submenu

```blade
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    
    <x-artisanpack-menu-sub title="Settings" icon="o-cog-6-tooth">
        <x-artisanpack-menu-item title="General" link="/settings/general" />
        <x-artisanpack-menu-item title="Security" link="/settings/security" />
    </x-artisanpack-menu-sub>
</x-artisanpack-menu>
```

## With Separator and Title

```blade
<x-artisanpack-menu>
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    
    <x-artisanpack-menu-separator title="Settings" icon="o-cog-6-tooth" />
    <x-artisanpack-menu-item title="General" link="/settings/general" />
    <x-artisanpack-menu-item title="Security" link="/settings/security" />
</x-artisanpack-menu>
```

## With Menu Title

```blade
<x-artisanpack-menu>
    <x-artisanpack-menu-title title="Main Navigation" icon="o-bars-3" />
    <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
    <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
</x-artisanpack-menu>
```

## Menu Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the menu |
| title | string | null | Optional title for the menu |
| icon | string | null | Optional icon for the menu title |
| iconClasses | string | 'w-4 h-4' | CSS classes for the icon |
| separator | boolean | false | Whether to show a separator below the title |
| activateByRoute | boolean | false | Whether to activate menu items based on the current route |
| activeBgColor | string | 'bg-base-300' | Background color for active menu items |

## MenuItem Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the menu item |
| title | string | null | Text to display for the menu item |
| icon | string | null | Icon to display for the menu item |
| iconClasses | string | null | CSS classes for the icon |
| spinner | string | null | Target for loading spinner (1 for wire:click) |
| link | string | null | URL for the menu item |
| route | string | null | Route name for the menu item |
| external | boolean | false | Whether the link is external (opens in new tab) |
| noWireNavigate | boolean | false | Whether to disable wire:navigate |
| badge | string | null | Text for a badge on the menu item |
| badgeClasses | string | null | CSS classes for the badge |
| active | boolean | false | Whether the menu item is active |
| separator | boolean | false | Whether to show a separator |
| hidden | boolean | false | Whether to hide the menu item |
| disabled | boolean | false | Whether the menu item is disabled |
| exact | boolean | false | Whether to match the route exactly |
| bgColor | string | null | Background color for the menu item (theme color like 'primary' or hex color like '#ff0000') |

## MenuSeparator Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the separator |
| title | string | null | Optional title for the separator |
| icon | string | null | Optional icon for the separator |
| iconClasses | string | null | CSS classes for the icon |

## MenuSub Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the submenu |
| title | string | null | Title for the submenu |
| icon | string | null | Icon for the submenu |
| iconClasses | string | null | CSS classes for the icon |
| open | boolean | false | Whether the submenu is open by default |
| hidden | boolean | false | Whether to hide the submenu |
| disabled | boolean | false | Whether the submenu is disabled |
| active | boolean | false | Whether the submenu is active |
| bgColor | string | null | Background color for the submenu (theme color like 'primary' or hex color like '#ff0000') |

## MenuTitle Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the title |
| title | string | null | Text for the title |
| icon | string | null | Icon for the title |
| iconClasses | string | null | CSS classes for the icon |
