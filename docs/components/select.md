---
title: Select Component
---

# Select Component

The Select component is a form element that allows users to choose one option from a dropdown list. It provides a user-friendly way to implement select inputs with various styling options and features.

## Basic Usage

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
/>
```

## Examples

### Select with Key-Value Options

```php
<x-artisanpack-select 
    label="Country" 
    :options="[
        'us' => 'United States',
        'ca' => 'Canada',
        'uk' => 'United Kingdom',
        'au' => 'Australia'
    ]"
/>
```

### Select with Placeholder

```php
<x-artisanpack-select 
    label="Country" 
    placeholder="Select a country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
/>
```

### Select with Default Value

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    value="Canada"
/>
```

### Required Select

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    required
/>
```

### Disabled Select

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    disabled
/>
```

### Select with Helper Text

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    helper="Please select your country of residence"
/>
```

### Select with Error

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    error="Please select a country"
/>
```

### Select with Livewire Binding

```php
<x-artisanpack-select 
    label="Country" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    wire:model="country"
/>
```

### Select with Option Groups

```php
<x-artisanpack-select label="Country">
    <optgroup label="North America">
        <option value="us">United States</option>
        <option value="ca">Canada</option>
        <option value="mx">Mexico</option>
    </optgroup>
    <optgroup label="Europe">
        <option value="uk">United Kingdom</option>
        <option value="fr">France</option>
        <option value="de">Germany</option>
    </optgroup>
    <optgroup label="Asia">
        <option value="jp">Japan</option>
        <option value="cn">China</option>
        <option value="in">India</option>
    </optgroup>
</x-artisanpack-select>
```

### Select with Custom Slot Content

```php
<x-artisanpack-select label="Country">
    <option value="">Select a country</option>
    <option value="us">United States</option>
    <option value="ca">Canada</option>
    <option value="uk">United Kingdom</option>
    <option value="au">Australia</option>
</x-artisanpack-select>
```

### Select Sizes

```php
<x-artisanpack-select 
    label="Small select" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    size="sm"
/>

<x-artisanpack-select 
    label="Default select" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
/>

<x-artisanpack-select 
    label="Large select" 
    :options="['USA', 'Canada', 'UK', 'Australia']"
    size="lg"
/>
```

### Select with Prefix and Suffix

```php
<x-artisanpack-select 
    label="Currency" 
    :options="['USD', 'EUR', 'GBP', 'JPY']"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-currency-dollar" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Currency</span>
    </x-slot:suffix>
</x-artisanpack-select>
```

### Multiple Select

```php
<x-artisanpack-select 
    label="Countries" 
    :options="['USA', 'Canada', 'UK', 'Australia', 'Germany', 'France', 'Japan']"
    multiple
/>
```

### Select with Search (Using SelectGroup Component)

```php
<x-artisanpack-select-group
    label="Country"
    :options="[
        'us' => 'United States',
        'ca' => 'Canada',
        'uk' => 'United Kingdom',
        'au' => 'Australia',
        'de' => 'Germany',
        'fr' => 'France',
        'jp' => 'Japan'
    ]"
    searchable
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the select |
| `name` | string | `null` | The name attribute for the select |
| `id` | string | `null` | The ID attribute for the select (auto-generated if not provided) |
| `options` | array | `[]` | Array of options for the select (can be simple array or associative array) |
| `value` | string/array | `null` | The selected value(s) |
| `placeholder` | string | `null` | Placeholder text (creates a disabled option at the top of the list) |
| `required` | boolean | `false` | Whether the select is required |
| `disabled` | boolean | `false` | Whether the select is disabled |
| `multiple` | boolean | `false` | Whether multiple options can be selected |
| `size` | string | `'md'` | The size of the select (`sm`, `md`, `lg`) |
| `helper` | string | `null` | Helper text displayed below the select |
| `error` | string | `null` | Error message to display |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Custom option content (overrides the `options` prop) |
| `prefix` | Content to display before the select |
| `suffix` | Content to display after the select |

## Events

The Select component supports all standard HTML select events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Select component uses DaisyUI's select component under the hood, which provides a consistent styling with other form components. You can customize the appearance of selects by:

1. Using the provided props (`size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-select 
    label="Custom styled select" 
    :options="['Option 1', 'Option 2', 'Option 3']"
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

### DaisyUI Variables

You can customize the Select component by modifying these DaisyUI variables in your theme file:

```css
.select {
    --input-color: oklch(var(--b2));
    --size: 3rem;
}
```

## Accessibility

The Select component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Supports keyboard navigation
- Includes appropriate ARIA attributes
- Maintains adequate color contrast
- Provides clear focus states

## Related Components

- [Form](form.md) - Container for form elements
- [SelectGroup](select-group.md) - Enhanced select with search and other advanced features
- [Choices](choices.md) - Multi-select dropdown with search
- [Input](input.md) - Text input field
