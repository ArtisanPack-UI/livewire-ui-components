---
title: Swap Component
---

# Swap Component

The Swap component allows you to toggle between two different content elements with a smooth transition effect. It's useful for creating toggles, showing/hiding content, or any interface element that needs to alternate between two states.

## Basic Usage

```php
<x-artisanpack-swap>
    <x-slot:on>
        <x-artisanpack-icon name="heroicon-o-sun" class="w-6 h-6" />
    </x-slot:on>
    
    <x-slot:off>
        <x-artisanpack-icon name="heroicon-o-moon" class="w-6 h-6" />
    </x-slot:off>
</x-artisanpack-swap>
```

## Examples

### Basic Swap

```php
<x-artisanpack-swap>
    <x-slot:on>
        <x-artisanpack-icon name="heroicon-o-sun" class="w-6 h-6" />
    </x-slot:on>
    
    <x-slot:off>
        <x-artisanpack-icon name="heroicon-o-moon" class="w-6 h-6" />
    </x-slot:off>
</x-artisanpack-swap>
```

### Swap with Text

```php
<x-artisanpack-swap>
    <x-slot:on>Show Less</x-slot:on>
    <x-slot:off>Show More</x-slot:off>
</x-artisanpack-swap>
```

### Swap with Different Transition Effects

```php
<!-- Rotate Effect -->
<x-artisanpack-swap effect="rotate">
    <x-slot:on>
        <x-artisanpack-icon name="heroicon-o-plus" class="w-6 h-6" />
    </x-slot:on>
    
    <x-slot:off>
        <x-artisanpack-icon name="heroicon-o-x-mark" class="w-6 h-6" />
    </x-slot:off>
</x-artisanpack-swap>

<!-- Flip Effect -->
<x-artisanpack-swap effect="flip">
    <x-slot:on>
        <x-artisanpack-icon name="heroicon-o-heart" class="w-6 h-6 text-error" />
    </x-slot:on>
    
    <x-slot:off>
        <x-artisanpack-icon name="heroicon-o-heart" class="w-6 h-6" />
    </x-slot:off>
</x-artisanpack-swap>

<!-- Fade Effect -->
<x-artisanpack-swap effect="fade">
    <x-slot:on>On</x-slot:on>
    <x-slot:off>Off</x-slot:off>
</x-artisanpack-swap>
```

### Swap with Initial State

```php
<x-artisanpack-swap active>
    <x-slot:on>Hide Details</x-slot:on>
    <x-slot:off>Show Details</x-slot:off>
</x-artisanpack-swap>
```

### Swap with Custom Styling

```php
<x-artisanpack-swap class="text-lg font-bold">
    <x-slot:on class="text-success">
        <x-artisanpack-icon name="heroicon-o-check" class="w-6 h-6 mr-1" />
        Enabled
    </x-slot:on>
    
    <x-slot:off class="text-error">
        <x-artisanpack-icon name="heroicon-o-x-mark" class="w-6 h-6 mr-1" />
        Disabled
    </x-slot:off>
</x-artisanpack-swap>
```

### Swap with Event Handling

```php
<x-artisanpack-swap wire:click="toggleFeature">
    <x-slot:on>
        <x-artisanpack-icon name="heroicon-o-check-circle" class="w-6 h-6 text-success" />
    </x-slot:on>
    
    <x-slot:off>
        <x-artisanpack-icon name="heroicon-o-x-circle" class="w-6 h-6 text-error" />
    </x-slot:off>
</x-artisanpack-swap>
```

### Practical Example: Expand/Collapse Content

```php
<div>
    <x-artisanpack-swap x-on:click="$refs.content.classList.toggle('hidden')" class="mb-2">
        <x-slot:on>
            <span class="flex items-center">
                <x-artisanpack-icon name="heroicon-o-chevron-up" class="w-4 h-4 mr-1" />
                Hide Details
            </span>
        </x-slot:on>
        
        <x-slot:off>
            <span class="flex items-center">
                <x-artisanpack-icon name="heroicon-o-chevron-down" class="w-4 h-4 mr-1" />
                Show Details
            </span>
        </x-slot:off>
    </x-artisanpack-swap>
    
    <div x-ref="content" class="p-4 bg-base-200 rounded-md hidden">
        <p>This is the expandable content that can be shown or hidden.</p>
    </div>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `active` | boolean | `false` | Whether the swap is in its active state initially |
| `effect` | string | `null` | The transition effect to use (`rotate`, `flip`, `fade`) |
| `id` | string | `null` | Optional ID for the swap element |

## Slots

| Slot | Description |
|------|-------------|
| `on` | Content to show when the swap is in its active state |
| `off` | Content to show when the swap is in its inactive state |

## Behavior

The Swap component:

1. Toggles between two content elements when clicked
2. Applies a smooth transition effect between states
3. Can be initialized in either the active or inactive state
4. Can be controlled programmatically through Alpine.js or Livewire

## Styling

The Swap component uses DaisyUI's swap component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`effect`)
2. Adding custom classes via the `class` attribute
3. Styling the individual `on` and `off` slots with their own classes

### Default Classes

- The swap container has appropriate positioning to handle the transition effects
- Transition effects are smooth and use appropriate timing
- The inactive content is hidden when the active content is shown

## Accessibility

The Swap component follows accessibility best practices:

- Uses appropriate ARIA attributes to indicate the current state
- Maintains proper focus management
- Supports keyboard interaction

For better accessibility:

1. Ensure the swap has sufficient contrast in both states
2. Provide clear visual indication of the current state
3. Consider adding additional context for screen readers about what will happen when the swap is toggled

## Related Components

- [Toggle](toggle.md) - Form control for boolean values
- [ThemeToggle](theme-toggle.md) - Specialized component for toggling between light and dark themes
- [Dropdown](dropdown.md) - Shows/hides content with more complex interaction