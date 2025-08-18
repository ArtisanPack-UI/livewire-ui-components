---
title: Checkbox Component
---

# Checkbox Component

The Checkbox component is a form element that allows users to select one or more options from a set. It provides a simple way to implement checkbox inputs with labels and various styling options.

## Basic Usage

```php
<x-artisanpack-checkbox label="Accept terms and conditions" />
```

## Examples

### Simple Checkbox

```php
<x-artisanpack-checkbox label="Remember me" name="remember" />
```

### Checkbox with Value

```php
<x-artisanpack-checkbox label="Apple" name="fruits[]" value="apple" />
<x-artisanpack-checkbox label="Orange" name="fruits[]" value="orange" />
<x-artisanpack-checkbox label="Banana" name="fruits[]" value="banana" />
```

### Checked Checkbox

```php
<x-artisanpack-checkbox label="Subscribe to newsletter" checked />
```

### Disabled Checkbox

```php
<x-artisanpack-checkbox label="Admin access" disabled />
```

### Checkbox with Helper Text

```php
<x-artisanpack-checkbox 
    label="Subscribe to newsletter" 
    helper="You'll receive weekly updates about our products"
/>
```

### Checkbox with Error

```php
<x-artisanpack-checkbox 
    label="Accept terms and conditions" 
    error="You must accept the terms and conditions"
/>
```

### Checkbox with Livewire Binding

```php
<x-artisanpack-checkbox 
    label="Remember me" 
    wire:model="remember"
/>

<!-- Array Binding -->
<x-artisanpack-checkbox 
    label="Apple" 
    value="apple" 
    wire:model="selectedFruits"
/>
<x-artisanpack-checkbox 
    label="Orange" 
    value="orange" 
    wire:model="selectedFruits"
/>
<x-artisanpack-checkbox 
    label="Banana" 
    value="banana" 
    wire:model="selectedFruits"
/>
```

### Checkbox with Custom Color

```php
<x-artisanpack-checkbox 
    label="Primary color" 
    color="primary"
/>

<x-artisanpack-checkbox 
    label="Secondary color" 
    color="secondary"
/>

<x-artisanpack-checkbox 
    label="Accent color" 
    color="accent"
/>
```

### Checkbox Sizes

```php
<x-artisanpack-checkbox 
    label="Small checkbox" 
    size="sm"
/>

<x-artisanpack-checkbox 
    label="Default checkbox"
/>

<x-artisanpack-checkbox 
    label="Large checkbox" 
    size="lg"
/>
```

### Indeterminate Checkbox

```php
<x-artisanpack-checkbox 
    label="Select all" 
    indeterminate
/>
```

### Checkbox with Right-aligned Label

```php
<x-artisanpack-checkbox 
    label="Right-aligned label" 
    label-position="right"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the checkbox |
| `name` | string | `null` | The name attribute for the checkbox |
| `value` | string | `null` | The value attribute for the checkbox |
| `checked` | boolean | `false` | Whether the checkbox is checked |
| `indeterminate` | boolean | `false` | Whether the checkbox is in an indeterminate state |
| `disabled` | boolean | `false` | Whether the checkbox is disabled |
| `required` | boolean | `false` | Whether the checkbox is required |
| `helper` | string | `null` | Helper text displayed below the checkbox |
| `error` | string | `null` | Error message to display |
| `color` | string | `'primary'` | The color of the checkbox (`primary`, `secondary`, `accent`, etc.) |
| `size` | string | `'md'` | The size of the checkbox (`sm`, `md`, `lg`) |
| `label-position` | string | `'right'` | The position of the label relative to the checkbox (`left`, `right`) |

## Events

The Checkbox component supports all standard HTML input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Checkbox component uses DaisyUI's checkbox component under the hood, which provides a consistent styling with other form components. You can customize the appearance of checkboxes by:

1. Using the provided props (`color`, `size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-checkbox 
    label="Custom styled checkbox" 
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

### DaisyUI Variables

You can customize the Checkbox component by modifying these DaisyUI variables in your theme file:

```css
.checkbox {
    --size: 1.5rem; /* Checkbox size */
}
```

## Accessibility

The Checkbox component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Supports keyboard navigation and focus states
- Includes appropriate ARIA attributes
- Maintains adequate color contrast

## Related Components

- [Form](form.md) - Container for form elements
- [Radio](radio.md) - Radio button input
- [Toggle](toggle.md) - Toggle switch input
