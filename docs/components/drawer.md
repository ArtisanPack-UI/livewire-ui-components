---
title: Drawer Component
---

# Drawer Component

The Drawer component provides a sliding panel that appears from the side of the screen. It's useful for navigation menus, filters, forms, and other secondary content that doesn't need to be visible at all times.

## Basic Usage

```php
<x-artisanpack-drawer wire:model="showDrawer">
    <p>This is the drawer content.</p>
</x-artisanpack-drawer>

<!-- Toggle button -->
<x-artisanpack-button wire:click="$toggle('showDrawer')">
    Toggle Drawer
</x-artisanpack-button>
```

## Examples

### Basic Drawer

```php
<x-artisanpack-drawer wire:model="showDrawer">
    <div class="p-4">
        <h3 class="text-lg font-bold">Drawer Content</h3>
        <p class="mt-2">This is a basic drawer with simple content.</p>
    </div>
</x-artisanpack-drawer>
```

### Drawer with Title and Subtitle

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    title="Drawer Title"
    subtitle="Additional information about this drawer"
>
    <p>This drawer has a title and subtitle.</p>
</x-artisanpack-drawer>
```

### Right-Aligned Drawer

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    :right="true"
>
    <p>This drawer appears from the right side of the screen.</p>
</x-artisanpack-drawer>
```

### Drawer with Separator

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    title="Drawer with Separator"
    :separator="true"
>
    <p>This drawer has a separator line below the title.</p>
</x-artisanpack-drawer>
```

### Drawer with Close Button

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    title="Closable Drawer"
    :withCloseButton="true"
>
    <p>This drawer has a close button in the top-right corner.</p>
</x-artisanpack-drawer>
```

### Drawer with Actions

```php
<x-artisanpack-drawer wire:model="showDrawer" title="Edit Profile">
    <div class="space-y-4">
        <x-artisanpack-input label="Name" wire:model="name" />
        <x-artisanpack-input label="Email" wire:model="email" type="email" />
    </div>
    
    <x-slot:actions>
        <div class="flex justify-end gap-2">
            <x-artisanpack-button wire:click="$set('showDrawer', false)" color="ghost">
                Cancel
            </x-artisanpack-button>
            <x-artisanpack-button wire:click="saveProfile" color="primary">
                Save
            </x-artisanpack-button>
        </div>
    </x-slot:actions>
</x-artisanpack-drawer>
```

### Drawer with Close on Escape

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    :closeOnEscape="true"
>
    <p>Press the Escape key to close this drawer.</p>
</x-artisanpack-drawer>
```

### Drawer without Focus Trapping

```php
<x-artisanpack-drawer 
    wire:model="showDrawer"
    :withoutTrapFocus="true"
>
    <p>This drawer doesn't trap focus, allowing you to interact with elements outside the drawer while it's open.</p>
</x-artisanpack-drawer>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the drawer element |
| `right` | boolean\|null | `false` | Whether the drawer should appear from the right side |
| `title` | string\|null | `null` | Optional title for the drawer |
| `subtitle` | string\|null | `null` | Optional subtitle for the drawer |
| `separator` | boolean\|null | `false` | Whether to show a separator line below the title |
| `withCloseButton` | boolean\|null | `false` | Whether to show a close button in the drawer |
| `closeOnEscape` | boolean\|null | `false` | Whether to close the drawer when the Escape key is pressed |
| `withoutTrapFocus` | boolean\|null | `false` | Whether to disable focus trapping within the drawer |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The main content of the drawer |
| `actions` | Optional slot for action buttons at the bottom of the drawer |

## Styling

The Drawer component uses DaisyUI's drawer component under the hood, which provides a wide range of styling options. The drawer content is wrapped in a Card component for structure and styling.

### Default Classes

- `drawer` - Base drawer class
- `drawer-end` - Applied when `right` is true
- `drawer-side` - Container for the drawer content
- `drawer-overlay` - Overlay that appears behind the drawer
- `min-h-screen` - Makes the drawer full height
- `rounded-none` - Removes border radius from the card
- `px-8` - Adds horizontal padding

## Accessibility

The Drawer component follows accessibility best practices:

- Uses appropriate ARIA attributes for modal dialogs
- Supports keyboard navigation
- Provides focus trapping within the drawer (unless disabled)
- Allows closing with the Escape key (when enabled)
- Includes a close button option for better usability

## Related Components

- [Modal](modal.md) - Dialog box that appears in the center of the screen
- [Card](card.md) - Content container used within the drawer
- [Sidebar](sidebar.md) - Permanent or collapsible side navigation