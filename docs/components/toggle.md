---
title: Toggle Component
---

# Toggle Component

The Toggle component is a form element that provides a switch-like interface for boolean inputs. It offers a more intuitive and visually appealing alternative to checkboxes for toggling between two states.

## Basic Usage

```php
<x-artisanpack-toggle label="Dark Mode" />
```

## Examples

### Simple Toggle

```php
<x-artisanpack-toggle 
    label="Notifications" 
    name="notifications"
/>
```

### Toggle with Default Value

```php
<x-artisanpack-toggle 
    label="Dark Mode" 
    checked
/>
```

### Required Toggle

```php
<x-artisanpack-toggle 
    label="Accept Terms" 
    required
/>
```

### Disabled Toggle

```php
<x-artisanpack-toggle 
    label="Maintenance Mode" 
    checked 
    disabled
/>
```

### Toggle with Helper Text

```php
<x-artisanpack-toggle 
    label="Email Notifications" 
    helper="Receive updates about your account via email"
/>
```

### Toggle with Error

```php
<x-artisanpack-toggle 
    label="Accept Terms" 
    error="You must accept the terms and conditions"
/>
```

### Toggle with Livewire Binding

```php
<x-artisanpack-toggle 
    label="Dark Mode" 
    wire:model="darkMode"
/>

<!-- Lazy Toggle (updates on blur) -->
<x-artisanpack-toggle 
    label="Dark Mode" 
    wire:model.lazy="darkMode"
/>
```

### Toggle with Custom Colors

```php
<x-artisanpack-toggle 
    label="Primary color" 
    color="primary"
/>

<x-artisanpack-toggle 
    label="Secondary color" 
    color="secondary"
/>

<x-artisanpack-toggle 
    label="Accent color" 
    color="accent"
/>

<x-artisanpack-toggle 
    label="Success color" 
    color="success"
/>

<x-artisanpack-toggle 
    label="Warning color" 
    color="warning"
/>

<x-artisanpack-toggle 
    label="Error color" 
    color="error"
/>
```

### Toggle Sizes

```php
<x-artisanpack-toggle 
    label="Small toggle" 
    size="sm"
/>

<x-artisanpack-toggle 
    label="Default toggle"
/>

<x-artisanpack-toggle 
    label="Large toggle" 
    size="lg"
/>
```

### Toggle with Right-aligned Label

```php
<x-artisanpack-toggle 
    label="Right-aligned label" 
    label-position="right"
/>
```

### Toggle with Custom Value

```php
<x-artisanpack-toggle 
    label="Status" 
    value="active" 
    checked
/>
```

### Toggle with On/Off Labels

```php
<x-artisanpack-toggle 
    label="Power" 
    on-label="ON" 
    off-label="OFF"
/>
```

### Toggle with Icons

```php
<x-artisanpack-toggle 
    label="Dark Mode" 
    on-icon="heroicon-o-moon" 
    off-icon="heroicon-o-sun"
/>
```

### Toggle with Animation

```php
<x-artisanpack-toggle 
    label="Animated Toggle" 
    animated
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the toggle |
| `name` | string | `null` | The name attribute for the toggle |
| `id` | string | `null` | The ID attribute for the toggle (auto-generated if not provided) |
| `value` | string | `'1'` | The value attribute for the toggle when checked |
| `checked` | boolean | `false` | Whether the toggle is checked |
| `required` | boolean | `false` | Whether the toggle is required |
| `disabled` | boolean | `false` | Whether the toggle is disabled |
| `helper` | string | `null` | Helper text displayed below the toggle |
| `error` | string | `null` | Error message to display |
| `color` | string | `'primary'` | The color of the toggle (`primary`, `secondary`, `accent`, etc.) |
| `size` | string | `'md'` | The size of the toggle (`sm`, `md`, `lg`) |
| `label-position` | string | `'right'` | The position of the label relative to the toggle (`left`, `right`) |
| `on-label` | string | `null` | Text to display when toggle is on |
| `off-label` | string | `null` | Text to display when toggle is off |
| `on-icon` | string | `null` | Icon to display when toggle is on |
| `off-icon` | string | `null` | Icon to display when toggle is off |
| `animated` | boolean | `false` | Whether to apply animation effects to the toggle |

## Events

The Toggle component supports all standard HTML input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`
- `wire:model.live`

## Styling

The Toggle component uses DaisyUI's toggle component under the hood, which provides a consistent styling with other form components. You can customize the appearance by:

1. Using the provided props (`color`, `size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-toggle 
    label="Custom styled toggle" 
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

### DaisyUI Variables

You can customize the Toggle component by modifying these DaisyUI variables in your theme file:

```css
.toggle {
    --size: 1.5rem; /* Toggle size */
}
```

## Accessibility

The Toggle component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation (space key to toggle)
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast
- Provides clear visual indication of the current state

## Related Components

- [Form](Form) - Container for form elements
- [Checkbox](Checkbox) - Checkbox input
- [ThemeToggle](Theme-Toggle) - Specialized toggle for theme switching
