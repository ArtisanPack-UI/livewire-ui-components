# Main Component

The Main component provides a structured page layout with a main content area, an optional sidebar, and an optional footer. It's designed to create consistent, responsive layouts for your application with support for collapsible sidebars and various configuration options.

## Basic Usage

```php
<x-artisanpack-main>
    <x-slot:sidebar>
        <!-- Sidebar content -->
    </x-slot:sidebar>
    
    <x-slot:content>
        <!-- Main content -->
    </x-slot:content>
    
    <x-slot:footer>
        <!-- Footer content -->
    </x-slot:footer>
</x-artisanpack-main>
```

## Examples

### Basic Layout with Sidebar and Content

```php
<x-artisanpack-main>
    <x-slot:sidebar drawer="main-drawer">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
            <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
            <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        <p>Welcome to your application dashboard.</p>
    </x-slot:content>
</x-artisanpack-main>
```

### Layout with Right Sidebar

```php
<x-artisanpack-main>
    <x-slot:sidebar right drawer="right-drawer">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Profile" icon="o-user" link="/profile" />
            <x-artisanpack-menu-item title="Notifications" icon="o-bell" link="/notifications" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Content</h1>
        <p>Main content with right sidebar.</p>
    </x-slot:content>
</x-artisanpack-main>
```

### Layout with Collapsible Sidebar

```php
<x-artisanpack-main>
    <x-slot:sidebar drawer="main-drawer" collapsible collapse-text="Collapse Menu" collapse-icon="o-chevron-left">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
            <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
            <x-artisanpack-menu-item title="Settings" icon="o-cog-6-tooth" link="/settings" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        <p>This layout has a collapsible sidebar.</p>
    </x-slot:content>
</x-artisanpack-main>
```

### Full Width Layout

```php
<x-artisanpack-main :fullWidth="true">
    <x-slot:sidebar drawer="main-drawer">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
            <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Full Width Content</h1>
        <p>This content area spans the full width of the screen.</p>
    </x-slot:content>
</x-artisanpack-main>
```

### Layout with Navigation Bar

```php
<x-artisanpack-nav>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button icon="o-user-circle" class="btn-ghost" />
    </x-slot:actions>
</x-artisanpack-nav>

<x-artisanpack-main :withNav="true">
    <x-slot:sidebar drawer="main-drawer">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
            <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Content with Nav</h1>
        <p>This layout includes a navigation bar above the main content.</p>
    </x-slot:content>
</x-artisanpack-main>
```

### Layout with Footer

```php
<x-artisanpack-main>
    <x-slot:sidebar drawer="main-drawer">
        <x-artisanpack-menu>
            <x-artisanpack-menu-item title="Dashboard" icon="o-home" link="/" />
            <x-artisanpack-menu-item title="Users" icon="o-users" link="/users" />
        </x-artisanpack-menu>
    </x-slot:sidebar>
    
    <x-slot:content>
        <h1 class="text-2xl font-bold mb-4">Content with Footer</h1>
        <p>This layout includes a footer below the main content.</p>
    </x-slot:content>
    
    <x-slot:footer>
        <div class="p-4 text-center border-t">
            <p>&copy; 2023 My Application. All rights reserved.</p>
        </div>
    </x-slot:footer>
</x-artisanpack-main>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `fullWidth` | boolean\|null | `false` | Whether the layout should use the full width of the screen |
| `withNav` | boolean\|null | `false` | Whether the layout should be adjusted to work with a navigation bar |
| `collapseText` | string\|null | `'Collapse'` | Text for the sidebar collapse button |
| `collapseIcon` | string\|null | `'o-bars-3-bottom-right'` | Icon for the sidebar collapse button |
| `collapsible` | boolean\|null | `false` | Whether the sidebar should be collapsible |

## Slots

| Slot | Description |
|------|-------------|
| `sidebar` | Content for the sidebar area |
| `content` | Main content area |
| `footer` | Optional footer content |

## Sidebar Attributes

The sidebar slot supports several attributes to customize its behavior:

| Attribute | Description |
|-----------|-------------|
| `drawer` | ID for the drawer element (required for mobile functionality) |
| `right` | Whether the sidebar should be positioned on the right side |
| `right-mobile` | Whether the sidebar should be positioned on the right side on mobile devices |
| `collapsible` | Whether the sidebar should be collapsible |
| `collapse-text` | Text for the sidebar collapse button |
| `collapse-icon` | Icon for the sidebar collapse button |

Example:

```php
<x-slot:sidebar drawer="main-drawer" right collapsible collapse-text="Collapse Menu">
    <!-- Sidebar content -->
</x-slot:sidebar>
```

## Styling

The Main component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a responsive layout that adapts to different screen sizes.

### Default Classes

- `w-full mx-auto` - Base layout for the main container
- `max-w-screen-2xl` - Maximum width constraint (when not using full width)
- `drawer lg:drawer-open` - DaisyUI drawer component for the sidebar
- `drawer-content w-full mx-auto p-5 lg:px-10 lg:py-5` - Content area styling
- `drawer-side` - Sidebar container
- `w-[270px]` - Default expanded sidebar width
- `w-[62px]` - Collapsed sidebar width

### Sidebar States

The sidebar has two states:
1. **Expanded** - Shows full sidebar content with text and icons
2. **Collapsed** - Shows only icons with minimal width

The sidebar state is persisted in the session, so it will remain in the same state across page loads.

## Accessibility

The Main component follows accessibility best practices:

- Uses semantic HTML with proper `<main>` and `<footer>` elements
- Provides proper drawer overlay for mobile navigation
- Maintains focus management when opening/closing the sidebar
- Supports keyboard navigation
- Preserves content structure for screen readers

## Related Components

- [Nav](nav.md) - Navigation bar component that works with the Main component
- [Menu](menu.md) - Menu component commonly used in the sidebar
- [MenuItem](menu-item.md) - Menu item component for navigation links