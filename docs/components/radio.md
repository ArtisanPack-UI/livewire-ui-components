---
title: Radio Component
---

# Radio Component

The Radio component is a form element that allows users to select a single option from a set of mutually exclusive choices. It provides a simple way to implement radio button inputs with labels and various styling options.

## Basic Usage

```php
<x-artisanpack-radio name="gender" label="Male" value="male" />
<x-artisanpack-radio name="gender" label="Female" value="female" />
<x-artisanpack-radio name="gender" label="Other" value="other" />
```

## Examples

### Simple Radio Group

```php
<div>
    <x-artisanpack-radio name="payment" label="Credit Card" value="credit" />
    <x-artisanpack-radio name="payment" label="PayPal" value="paypal" />
    <x-artisanpack-radio name="payment" label="Bank Transfer" value="bank" />
</div>
```

### Checked Radio Button

```php
<x-artisanpack-radio name="size" label="Small" value="small" />
<x-artisanpack-radio name="size" label="Medium" value="medium" checked />
<x-artisanpack-radio name="size" label="Large" value="large" />
```

### Disabled Radio Button

```php
<x-artisanpack-radio name="plan" label="Basic" value="basic" />
<x-artisanpack-radio name="plan" label="Premium" value="premium" />
<x-artisanpack-radio name="plan" label="Enterprise" value="enterprise" disabled />
```

### Radio with Helper Text

```php
<x-artisanpack-radio 
    name="shipping" 
    label="Standard Shipping" 
    value="standard" 
    helper="Delivery in 5-7 business days"
/>
<x-artisanpack-radio 
    name="shipping" 
    label="Express Shipping" 
    value="express" 
    helper="Delivery in 1-2 business days"
/>
```

### Radio with Error

```php
<x-artisanpack-radio 
    name="terms" 
    label="I agree to the terms" 
    value="agree" 
    error="You must agree to the terms"
/>
```

### Radio with Livewire Binding

```php
<x-artisanpack-radio 
    name="color" 
    label="Red" 
    value="red" 
    wire:model="selectedColor"
/>
<x-artisanpack-radio 
    name="color" 
    label="Green" 
    value="green" 
    wire:model="selectedColor"
/>
<x-artisanpack-radio 
    name="color" 
    label="Blue" 
    value="blue" 
    wire:model="selectedColor"
/>
```

### Radio with Custom Color

```php
<x-artisanpack-radio 
    name="priority" 
    label="Low" 
    value="low" 
    color="primary"
/>
<x-artisanpack-radio 
    name="priority" 
    label="Medium" 
    value="medium" 
    color="secondary"
/>
<x-artisanpack-radio 
    name="priority" 
    label="High" 
    value="high" 
    color="accent"
/>
```

### Radio Sizes

```php
<x-artisanpack-radio 
    name="size_preference" 
    label="Small radio" 
    value="small" 
    size="sm"
/>
<x-artisanpack-radio 
    name="size_preference" 
    label="Default radio" 
    value="default"
/>
<x-artisanpack-radio 
    name="size_preference" 
    label="Large radio" 
    value="large" 
    size="lg"
/>
```

### Radio with Right-aligned Label

```php
<x-artisanpack-radio 
    name="alignment" 
    label="Right-aligned label" 
    value="right" 
    label-position="right"
/>
```

### Horizontal Radio Group

```php
<div class="flex space-x-4">
    <x-artisanpack-radio name="rating" label="1" value="1" />
    <x-artisanpack-radio name="rating" label="2" value="2" />
    <x-artisanpack-radio name="rating" label="3" value="3" />
    <x-artisanpack-radio name="rating" label="4" value="4" />
    <x-artisanpack-radio name="rating" label="5" value="5" />
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the radio button |
| `name` | string | `null` | The name attribute for the radio button (required for grouping) |
| `value` | string | `null` | The value attribute for the radio button |
| `checked` | boolean | `false` | Whether the radio button is checked |
| `disabled` | boolean | `false` | Whether the radio button is disabled |
| `required` | boolean | `false` | Whether the radio button is required |
| `helper` | string | `null` | Helper text displayed below the radio button |
| `error` | string | `null` | Error message to display |
| `color` | string | `'primary'` | The color of the radio button (`primary`, `secondary`, `accent`, etc.) |
| `size` | string | `'md'` | The size of the radio button (`sm`, `md`, `lg`) |
| `label-position` | string | `'right'` | The position of the label relative to the radio button (`left`, `right`) |

## Events

The Radio component supports all standard HTML input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Radio component uses DaisyUI's radio component under the hood, which provides a consistent styling with other form components. You can customize the appearance of radio buttons by:

1. Using the provided props (`color`, `size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-radio 
    name="custom" 
    label="Custom styled radio" 
    value="custom" 
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

### DaisyUI Variables

You can customize the Radio component by modifying these DaisyUI variables in your theme file:

```css
.radio {
    --size: 1.5rem; /* Radio button size */
}
```

## Accessibility

The Radio component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Supports keyboard navigation and focus states
- Includes appropriate ARIA attributes
- Maintains adequate color contrast
- Ensures radio buttons in a group share the same name attribute for proper functionality

## Related Components

- [Form](form) - Container for form elements
- [Checkbox](checkbox) - Checkbox input
- [Select](select) - Dropdown select input
