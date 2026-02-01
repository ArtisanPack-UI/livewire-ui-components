---
title: Input Component
---

# Input Component

The Input component is a versatile form element for collecting user text input. It supports various input types, validation, and integrates seamlessly with Livewire.

## Basic Usage

```php
<x-artisanpack-input label="Username" />
```

## Examples

### Input Types

```php
<!-- Text Input (default) -->
<x-artisanpack-input label="Name" type="text" />

<!-- Email Input -->
<x-artisanpack-input label="Email Address" type="email" />

<!-- Password Input -->
<x-artisanpack-input label="Password" type="password" />

<!-- Number Input -->
<x-artisanpack-input label="Age" type="number" min="0" max="120" />

<!-- Date Input -->
<x-artisanpack-input label="Birth Date" type="date" />

<!-- URL Input -->
<x-artisanpack-input label="Website" type="url" />

<!-- Tel Input -->
<x-artisanpack-input label="Phone Number" type="tel" />

<!-- Search Input -->
<x-artisanpack-input label="Search" type="search" />
```

### Input with Placeholder

```php
<x-artisanpack-input 
    label="Username" 
    placeholder="Enter your username"
/>
```

### Input with Helper Text

```php
<x-artisanpack-input 
    label="Username" 
    helper="Choose a unique username with at least 3 characters"
/>
```

### Required Input

```php
<x-artisanpack-input 
    label="Email Address" 
    type="email" 
    required
/>
```

### Input with Validation

```php
<x-artisanpack-input 
    label="Email Address" 
    type="email" 
    required
    error="{{ $errors->first('email') }}"
/>
```

### Input with Prefix and Suffix

```php
<!-- Input with Prefix -->
<x-artisanpack-input 
    label="Price" 
    type="number" 
    step="0.01"
>
    <x-slot:prefix>$</x-slot:prefix>
</x-artisanpack-input>

<!-- Input with Suffix -->
<x-artisanpack-input 
    label="Weight" 
    type="number" 
    step="0.1"
>
    <x-slot:suffix>kg</x-slot:suffix>
</x-artisanpack-input>

<!-- Input with Icon Prefix -->
<x-artisanpack-input 
    label="Search" 
    type="search"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-magnifying-glass" class="w-5 h-5" />
    </x-slot:prefix>
</x-artisanpack-input>
```

### Input Sizes

```php
<x-artisanpack-input label="Small Input" size="sm" />
<x-artisanpack-input label="Default Input" />
<x-artisanpack-input label="Large Input" size="lg" />
```

### Disabled Input

```php
<x-artisanpack-input 
    label="Username" 
    value="johndoe" 
    disabled
/>
```

### Readonly Input

```php
<x-artisanpack-input 
    label="Username" 
    value="johndoe" 
    readonly
/>
```

### Input with Default Value

```php
<x-artisanpack-input 
    label="Username" 
    value="johndoe"
/>
```

### Input with Livewire Binding

```php
<x-artisanpack-input 
    label="Email Address" 
    type="email" 
    wire:model="email"
/>

<!-- Debounced Input -->
<x-artisanpack-input 
    label="Search" 
    wire:model.debounce.500ms="search"
/>

<!-- Lazy Input (updates on blur) -->
<x-artisanpack-input 
    label="Username" 
    wire:model.lazy="username"
/>
```

### Input with Autofocus

```php
<x-artisanpack-input 
    label="Search" 
    autofocus
/>
```

### Input with Autocomplete

```php
<x-artisanpack-input 
    label="Email Address" 
    type="email" 
    autocomplete="email"
/>
```

### Input with Custom Validation Attributes

```php
<x-artisanpack-input 
    label="Username" 
    minlength="3" 
    maxlength="20" 
    pattern="[a-zA-Z0-9]+" 
    title="Username can only contain letters and numbers"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | ID attribute for the input (auto-generated if not provided) |
| `label` | string | `null` | Label text for the input |
| `icon` | string | `null` | Icon to display on the left side of the input |
| `icon-right` | string | `null` | Icon to display on the right side of the input |
| `hint` | string | `null` | Hint text displayed below the input |
| `hint-class` | string | `'fieldset-label'` | CSS class for the hint text |
| `prefix` | string | `null` | Text prefix to display inside the input |
| `suffix` | string | `null` | Text suffix to display inside the input |
| `inline` | boolean | `false` | Whether to display the label inline with the input |
| `clearable` | boolean | `false` | Whether to show a clear button |
| `money` | boolean | `false` | Whether to format as currency input |
| `locale` | string | `'en-US'` | Locale for money formatting |
| `error-field` | string | `null` | The field name for error validation |
| `error-class` | string | `'text-error'` | CSS class for error messages |
| `omit-error` | boolean | `false` | Whether to hide error messages |
| `first-error-only` | boolean | `false` | Whether to show only the first error message |

## Slots

| Slot | Description |
|------|-------------|
| `prepend` | Content to display before the input (outside the input wrapper) |
| `append` | Content to display after the input (outside the input wrapper) |

Note: For text prefix/suffix inside the input, use the `prefix` and `suffix` props instead.

## Events

The Input component supports all standard HTML input events:

- `input`
- `change`
- `focus`
- `blur`
- `keydown`
- `keyup`
- `keypress`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`
- `wire:model.debounce`

## Styling

The Input component uses DaisyUI's input component under the hood, which provides a consistent styling with other form components. You can customize the appearance of inputs by:

1. Using the provided props (`size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-input 
    label="Custom Input" 
    class="border-purple-500 focus:border-purple-700 focus:ring-purple-700"
/>
```

## Accessibility

The Input component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Displays validation errors with proper associations
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast

## Related Components

- [Form](Form) - Container for form elements
- [Password](Password) - Specialized input for password fields with visibility toggle
- [Textarea](Textarea) - Multi-line text input
- [Select](Select) - Dropdown select input
- [Checkbox](Checkbox) - Checkbox input
