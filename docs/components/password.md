---
title: Password Component
---

# Password Component

The Password component is a specialized input element designed for secure password entry. It includes a visibility toggle feature that allows users to show or hide the password text, enhancing usability while maintaining security.

## Basic Usage

```php
<x-artisanpack-password label="Password" />
```

## Examples

### Simple Password Input

```php
<x-artisanpack-password 
    label="Password" 
    name="password" 
    placeholder="Enter your password"
/>
```

### Password with Helper Text

```php
<x-artisanpack-password 
    label="Password" 
    helper="Password must be at least 8 characters long"
/>
```

### Password with Error

```php
<x-artisanpack-password 
    label="Password" 
    error="Password is too weak"
/>
```

### Required Password

```php
<x-artisanpack-password 
    label="Password" 
    required
/>
```

### Disabled Password

```php
<x-artisanpack-password 
    label="Password" 
    value="securepassword123" 
    disabled
/>
```

### Password with Livewire Binding

```php
<x-artisanpack-password 
    label="Password" 
    wire:model="password"
/>

<!-- Lazy Password (updates on blur) -->
<x-artisanpack-password 
    label="Password" 
    wire:model.lazy="password"
/>
```

### Password with Custom Toggle Icons

```php
<x-artisanpack-password 
    label="Password" 
    show-icon="heroicon-o-eye" 
    hide-icon="heroicon-o-eye-slash"
/>
```

### Password with Strength Meter

```php
<x-artisanpack-password 
    label="Password" 
    with-strength-meter
/>
```

### Password with Autofocus

```php
<x-artisanpack-password 
    label="Password" 
    autofocus
/>
```

### Password with Autocomplete

```php
<x-artisanpack-password 
    label="Password" 
    autocomplete="current-password"
/>

<x-artisanpack-password 
    label="New Password" 
    autocomplete="new-password"
/>
```

### Password Sizes

```php
<x-artisanpack-password 
    label="Small password" 
    size="sm"
/>

<x-artisanpack-password 
    label="Default password"
/>

<x-artisanpack-password 
    label="Large password" 
    size="lg"
/>
```

### Password with Prefix

```php
<x-artisanpack-password 
    label="Password" 
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-lock-closed" class="w-5 h-5" />
    </x-slot:prefix>
</x-artisanpack-password>
```

### Password with Custom Validation

```php
<x-artisanpack-password 
    label="Password" 
    minlength="8" 
    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
    title="Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the password input |
| `name` | string | `null` | The name attribute for the password input |
| `id` | string | `null` | The ID attribute for the password input (auto-generated if not provided) |
| `value` | string | `null` | The default value for the password input |
| `placeholder` | string | `null` | Placeholder text |
| `helper` | string | `null` | Helper text displayed below the password input |
| `error` | string | `null` | Error message to display |
| `required` | boolean | `false` | Whether the password input is required |
| `disabled` | boolean | `false` | Whether the password input is disabled |
| `readonly` | boolean | `false` | Whether the password input is readonly |
| `autofocus` | boolean | `false` | Whether the password input should be automatically focused |
| `autocomplete` | string | `null` | Value for the autocomplete attribute (e.g., 'current-password', 'new-password') |
| `size` | string | `'md'` | The size of the password input (`sm`, `md`, `lg`) |
| `show-icon` | string | `'heroicon-o-eye'` | The icon to show when password is hidden |
| `hide-icon` | string | `'heroicon-o-eye-slash'` | The icon to show when password is visible |
| `with-strength-meter` | boolean | `false` | Whether to display a password strength meter |
| `minlength` | string/number | `null` | Minimum length of password |
| `maxlength` | string/number | `null` | Maximum length of password |
| `pattern` | string | `null` | Regular expression pattern for validation |
| `title` | string | `null` | Title attribute (used for validation tooltip) |

## Slots

| Slot | Description |
|------|-------------|
| `prefix` | Content to display before the password input |

## Events

The Password component supports all standard HTML input events:

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

The Password component uses DaisyUI's input component under the hood, which provides a consistent styling with other form components. You can customize the appearance of password inputs by:

1. Using the provided props (`size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-password 
    label="Custom styled password" 
    class="border-2 border-purple-500 focus:border-purple-700 focus:ring-purple-700"
/>
```

### DaisyUI Variables

You can customize the Password component by modifying these DaisyUI variables in your theme file:

```css
.input {
    --input-color: oklch(var(--b2));
    --size: 3rem;
}
```

## Accessibility

The Password component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Provides a visible toggle for password visibility
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast
- Includes appropriate autocomplete attributes for password managers

## Related Components

- [Form](form) - Container for form elements
- [Input](input) - Standard text input
- [Pin](pin) - PIN code input
