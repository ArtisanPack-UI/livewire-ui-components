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

### Checkbox with Right Label

```php
<x-artisanpack-checkbox
    label="I agree to the terms"
    right
/>
```

### Checkbox with Card Style

Display the checkbox as a card with enhanced visual feedback:

```php
<x-artisanpack-checkbox
    label="Premium Plan"
    hint="Access to all premium features"
    card
    wire:model="selectedPlan"
/>

<!-- Custom card styling -->
<x-artisanpack-checkbox
    label="Enterprise Plan"
    hint="Custom solutions for your business"
    card
    card-class="card card-compact border-2 border-primary/30 hover:border-primary cursor-pointer"
    wire:model="selectedPlan"
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
| `id` | string | `null` | The ID attribute for the checkbox |
| `label` | string | `null` | The label text for the checkbox |
| `value` | mixed | `null` | The value attribute for the checkbox |
| `right` | boolean | `false` | Whether to position the label on the right side |
| `hint` | string | `null` | Hint text displayed below the label |
| `hint-class` | string | `'fieldset-label'` | CSS class for the hint text |
| `card` | boolean | `false` | Whether to display the checkbox as a card |
| `card-class` | string | `'card card-compact border-2 border-base-300 hover:border-primary cursor-pointer transition-colors'` | CSS classes for the card variant |
| `error-field` | string | `null` | The field name for error validation |
| `error-class` | string | `'text-error'` | CSS class for error messages |
| `omit-error` | boolean | `false` | Whether to hide error messages |
| `first-error-only` | boolean | `false` | Whether to show only the first error message |

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

- [Form](form) - Container for form elements
- [Radio](radio) - Radio button input
- [Toggle](toggle) - Toggle switch input
