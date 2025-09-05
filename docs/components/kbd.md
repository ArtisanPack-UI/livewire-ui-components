---
title: Kbd Component
---

# Kbd Component

The Kbd component provides a way to display keyboard keys or keyboard shortcuts in a visually distinct way. It's useful for displaying keyboard commands, shortcuts, or key combinations in documentation, tutorials, or help sections.

## Basic Usage

```php
<x-artisanpack-kbd>Ctrl</x-artisanpack-kbd>
```

## Examples

### Single Key

```php
<x-artisanpack-kbd>A</x-artisanpack-kbd>
```

### Modifier Key

```php
<x-artisanpack-kbd>Ctrl</x-artisanpack-kbd>
```

### Key Combination

```php
<div class="flex items-center gap-1">
    <x-artisanpack-kbd>Ctrl</x-artisanpack-kbd>
    +
    <x-artisanpack-kbd>C</x-artisanpack-kbd>
</div>
```

### Multiple Key Combinations

```php
<div class="flex items-center gap-1">
    <x-artisanpack-kbd>Ctrl</x-artisanpack-kbd>
    +
    <x-artisanpack-kbd>Shift</x-artisanpack-kbd>
    +
    <x-artisanpack-kbd>P</x-artisanpack-kbd>
</div>
```

### Custom Styling

```php
<x-artisanpack-kbd class="bg-primary text-white">Enter</x-artisanpack-kbd>
```

### Function Keys

```php
<x-artisanpack-kbd>F1</x-artisanpack-kbd>
```

### Special Keys

```php
<x-artisanpack-kbd>⌘</x-artisanpack-kbd>
<x-artisanpack-kbd>⌥</x-artisanpack-kbd>
<x-artisanpack-kbd>⇧</x-artisanpack-kbd>
<x-artisanpack-kbd>⌃</x-artisanpack-kbd>
<x-artisanpack-kbd>↵</x-artisanpack-kbd>
<x-artisanpack-kbd>⇥</x-artisanpack-kbd>
<x-artisanpack-kbd>⎋</x-artisanpack-kbd>
```

### In a Paragraph

```php
<p>
    To copy text, press <x-artisanpack-kbd>Ctrl</x-artisanpack-kbd> + <x-artisanpack-kbd>C</x-artisanpack-kbd>.
    To paste text, press <x-artisanpack-kbd>Ctrl</x-artisanpack-kbd> + <x-artisanpack-kbd>V</x-artisanpack-kbd>.
</p>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the kbd element |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the kbd element (typically the key or key name) |

## Styling

The Kbd component uses DaisyUI's kbd component under the hood, which provides a visual style that resembles a physical keyboard key.

### Default Classes

- `kbd` - Base kbd class from DaisyUI

### Custom Styling Example

```php
<x-artisanpack-kbd class="kbd-lg">Space</x-artisanpack-kbd>
```

DaisyUI provides several modifier classes for the kbd component:

- `kbd-xs` - Extra small key
- `kbd-sm` - Small key
- `kbd-md` - Medium key (default)
- `kbd-lg` - Large key

You can also apply custom colors and other Tailwind CSS classes:

```php
<x-artisanpack-kbd class="bg-secondary text-white">Tab</x-artisanpack-kbd>
```

## Accessibility

The Kbd component follows accessibility best practices:

- Uses the semantic HTML `<kbd>` element, which is specifically designed for representing keyboard input
- Maintains proper contrast for readability
- Preserves text semantics for screen readers
- Supports keyboard navigation

## Related Components

- [Button](button) - Interactive button element
- [Icon](icon) - SVG icon display