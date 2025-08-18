---
title: Collapse Component
---

# Collapse Component

The Collapse component provides a way to toggle the visibility of content, creating expandable and collapsible sections. It's useful for FAQs, accordion menus, and reducing visual clutter in information-dense interfaces.

## Basic Usage

```php
<x-artisanpack-collapse>
    <x-slot:heading>
        Click to expand
    </x-slot:heading>
    
    <x-slot:content>
        This content will be hidden until the collapse is toggled.
    </x-slot:content>
</x-artisanpack-collapse>
```

## Examples

### Basic Collapse

```php
<x-artisanpack-collapse>
    <x-slot:heading>
        What is ArtisanPack UI?
    </x-slot:heading>
    
    <x-slot:content>
        <p>ArtisanPack UI is a collection of Livewire components for Laravel applications.</p>
        <p>It provides a comprehensive set of UI components that are easy to use and customize.</p>
    </x-slot:content>
</x-artisanpack-collapse>
```

### Collapse with Plus/Minus Icons

```php
<x-artisanpack-collapse :collapsePlusMinus="true">
    <x-slot:heading>
        Click to expand (with plus/minus icons)
    </x-slot:heading>
    
    <x-slot:content>
        This collapse uses plus and minus icons instead of arrows.
    </x-slot:content>
</x-artisanpack-collapse>
```

### Collapse with Separator

```php
<x-artisanpack-collapse :separator="true">
    <x-slot:heading>
        Click to expand (with separator)
    </x-slot:heading>
    
    <x-slot:content>
        This content has a separator line between the heading and content.
    </x-slot:content>
</x-artisanpack-collapse>
```

### Collapse without Icon

```php
<x-artisanpack-collapse :noIcon="true">
    <x-slot:heading>
        Click to expand (no icon)
    </x-slot:heading>
    
    <x-slot:content>
        This collapse doesn't display an icon.
    </x-slot:content>
</x-artisanpack-collapse>
```

### Collapse with Livewire Binding

```php
<x-artisanpack-collapse wire:model="isExpanded">
    <x-slot:heading>
        Livewire-controlled collapse
    </x-slot:heading>
    
    <x-slot:content>
        The state of this collapse is controlled by Livewire.
    </x-slot:content>
</x-artisanpack-collapse>
```

### Collapse with Custom Styling

```php
<x-artisanpack-collapse class="bg-base-200 rounded-lg">
    <x-slot:heading class="text-primary font-bold">
        Custom styled heading
    </x-slot:heading>
    
    <x-slot:content class="prose">
        <p>This collapse has custom styling applied to both the container and its slots.</p>
        <ul>
            <li>Custom background color</li>
            <li>Rounded corners</li>
            <li>Styled heading</li>
            <li>Content with prose styling</li>
        </ul>
    </x-slot:content>
</x-artisanpack-collapse>
```

### Multiple Collapses in a Group

```php
<div class="space-y-2">
    <x-artisanpack-collapse>
        <x-slot:heading>Section 1</x-slot:heading>
        <x-slot:content>Content for section 1</x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse>
        <x-slot:heading>Section 2</x-slot:heading>
        <x-slot:content>Content for section 2</x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse>
        <x-slot:heading>Section 3</x-slot:heading>
        <x-slot:content>Content for section 3</x-slot:content>
    </x-artisanpack-collapse>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the collapse element |
| `name` | string\|null | `null` | Optional name for the collapse element (used in accordion mode) |
| `collapsePlusMinus` | boolean\|null | `false` | Whether to use plus/minus icons instead of arrows |
| `separator` | boolean\|null | `false` | Whether to show a separator line between the heading and content |
| `noIcon` | boolean\|null | `false` | Whether to hide the toggle icon |

## Slots

| Slot | Description |
|------|-------------|
| `heading` | The content for the collapse heading/title |
| `content` | The content that will be shown/hidden when the collapse is toggled |

## Styling

The Collapse component uses DaisyUI's collapse component under the hood, which provides a wide range of styling options. You can customize the appearance by:

1. Adding custom classes via the `class` attribute
2. Styling the heading and content slots with their own classes
3. Using the provided props (`collapsePlusMinus`, `separator`, etc.)

### Default Classes

- `collapse` - Base collapse class
- `border-[length:var(--border)] border-base-content/10` - Default border styling
- `collapse-arrow` - Applied when using arrow icons (default)
- `collapse-plus` - Applied when using plus/minus icons
- `collapse-title` - Applied to the heading slot
- `collapse-content` - Applied to the content slot

## Accessibility

The Collapse component follows accessibility best practices:

- Uses appropriate ARIA attributes for expandable content
- Provides keyboard navigation support
- Maintains focus management when toggling content
- Includes visual indicators for the collapsed/expanded state

## Related Components

- [Accordion](accordion.md) - Container for a group of collapses where only one can be open at a time
- [Card](card.md) - Content container that can include collapsible sections
- [Tab](tab.md) - Alternative way to organize content into selectable sections