---
title: Group Component
---

# Group Component

The Group component provides a way to create a group of radio buttons styled as a button group. It's useful for selecting a single option from a small set of choices in a compact, visually appealing format.

## Basic Usage

```php
@php
$options = [
    ['id' => 'small', 'name' => 'Small'],
    ['id' => 'medium', 'name' => 'Medium'],
    ['id' => 'large', 'name' => 'Large']
];
@endphp

<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
/>
```

## Examples

### Basic Group

```php
@php
$options = [
    ['id' => 'small', 'name' => 'Small'],
    ['id' => 'medium', 'name' => 'Medium'],
    ['id' => 'large', 'name' => 'Large']
];
@endphp

<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
/>
```

### Group with Hint

```php
<x-artisanpack-group 
    label="Size"
    hint="Select the size for your product"
    wire:model="size"
    :options="$options"
/>
```

### Group with Custom Option Properties

```php
@php
$colorOptions = [
    ['value' => 'red', 'label' => 'Red'],
    ['value' => 'green', 'label' => 'Green'],
    ['value' => 'blue', 'label' => 'Blue']
];
@endphp

<x-artisanpack-group 
    label="Color"
    wire:model="color"
    :options="$colorOptions"
    optionValue="value"
    optionLabel="label"
/>
```

### Group with Disabled Option

```php
@php
$options = [
    ['id' => 'small', 'name' => 'Small'],
    ['id' => 'medium', 'name' => 'Medium', 'disabled' => true],
    ['id' => 'large', 'name' => 'Large']
];
@endphp

<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
/>
```

### Group with Custom Styling

```php
<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
    class="btn-sm"
/>
```

### Group with Validation

```php
<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
    required
/>

<!-- In your Livewire component -->
protected $rules = [
    'size' => 'required',
];
```

### Group with Custom Error Field

```php
<x-artisanpack-group 
    label="Size"
    wire:model="size"
    :options="$options"
    errorField="product_size"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the group element |
| `label` | string\|null | `null` | Optional label for the group |
| `hint` | string\|null | `null` | Optional hint text displayed below the group |
| `hintClass` | string\|null | `'fieldset-label'` | CSS class for the hint text |
| `optionValue` | string\|null | `'id'` | The property to use as the value for each option |
| `optionLabel` | string\|null | `'name'` | The property to use as the label for each option |
| `options` | Collection\|array | `new Collection()` | Collection or array of options to display in the group |
| `errorField` | string\|null | `null` | Custom field name for validation errors |
| `errorClass` | string\|null | `'text-error'` | CSS class for error messages |
| `omitError` | boolean\|null | `false` | Whether to hide error messages |
| `firstErrorOnly` | boolean\|null | `false` | Whether to show only the first error message |

## Option Format

Each option in the `options` array or collection should have the following properties:

```php
[
    'id' => 'option_value', // The value of the option (or custom property specified by optionValue)
    'name' => 'Option Label', // The label of the option (or custom property specified by optionLabel)
    'disabled' => true // Optional, whether the option is disabled
]
```

## Styling

The Group component uses DaisyUI's join and button components for styling. The radio inputs are styled as buttons with the selected option highlighted.

### Default Classes

- `fieldset` - Container for the group
- `fieldset-legend` - Applied to the label
- `fieldset-label` - Applied to the hint text
- `join` - Container for the button group
- `join-item btn` - Applied to each radio button
- `[&:checked]:btn-neutral` - Applied to the selected radio button
- `text-error` - Applied to error messages

## Accessibility

The Group component follows accessibility best practices:

- Uses semantic HTML with proper fieldset and legend elements
- Includes proper labeling for each option using aria-label
- Supports keyboard navigation
- Provides error messages for validation
- Includes required field indication

## Related Components

- [Radio](radio) - Standard radio button input
- [Button](button) - Button component
- [Toggle](toggle) - Toggle switch component