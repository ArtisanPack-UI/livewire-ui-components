---
title: Pin Component
---

# Pin Component

The Pin component is a specialized form element designed for entering PIN codes, verification codes, or any other short numeric or alphanumeric sequences. It provides a user-friendly interface with individual input fields for each character, automatic focus management, and validation capabilities.

## Basic Usage

```php
<x-artisanpack-pin label="Verification Code" length="6" />
```

## Examples

### Simple PIN Input

```php
<x-artisanpack-pin 
    label="PIN Code" 
    name="pin"
    length="4"
/>
```

### PIN with Default Value

```php
<x-artisanpack-pin 
    label="Verification Code" 
    value="123456" 
    length="6"
/>
```

### Required PIN

```php
<x-artisanpack-pin 
    label="Security Code" 
    length="4" 
    required
/>
```

### Disabled PIN

```php
<x-artisanpack-pin 
    label="PIN Code" 
    value="1234" 
    length="4" 
    disabled
/>
```

### PIN with Helper Text

```php
<x-artisanpack-pin 
    label="Verification Code" 
    length="6" 
    helper="Enter the 6-digit code sent to your phone"
/>
```

### PIN with Error

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    error="Invalid PIN code"
/>
```

### PIN with Livewire Binding

```php
<x-artisanpack-pin 
    label="Verification Code" 
    length="6" 
    wire:model="verificationCode"
/>
```

### PIN with Custom Type

```php
<x-artisanpack-pin 
    label="Alphanumeric Code" 
    length="6" 
    type="text"
/>
```

### PIN with Mask

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    masked
/>
```

### PIN with Custom Mask Character

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    masked 
    mask-char="•"
/>
```

### PIN with Auto Submit

```php
<x-artisanpack-pin 
    label="Verification Code" 
    length="6" 
    auto-submit
    wire:submit="verifyCode"
/>
```

### PIN with Custom Placeholder

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    placeholder="0"
/>
```

### PIN with Custom Separator

```php
<x-artisanpack-pin 
    label="Credit Card" 
    length="16" 
    separator="-" 
    group-size="4"
/>
```

### PIN with Custom Size

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    size="lg"
/>
```

### PIN with Custom Styles

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    class="gap-4"
    input-class="border-2 border-primary focus:border-primary-focus"
/>
```

### PIN with Autofocus

```php
<x-artisanpack-pin 
    label="Verification Code" 
    length="6" 
    autofocus
/>
```

### PIN with Completion Callback

```php
<x-artisanpack-pin 
    label="PIN Code" 
    length="4" 
    x-on:complete="alert('PIN completed: ' + $event.detail.value)"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the PIN input |
| `name` | string | `null` | The name attribute for the PIN input |
| `id` | string | `null` | The ID attribute for the PIN input (auto-generated if not provided) |
| `value` | string | `null` | The default value for the PIN input |
| `length` | integer | `4` | The number of characters in the PIN |
| `type` | string | `'number'` | The type of input (`number` or `text`) |
| `placeholder` | string | `''` | Placeholder character for each input field |
| `masked` | boolean | `false` | Whether to mask the input (like a password) |
| `mask-char` | string | `'*'` | The character to use for masking |
| `auto-submit` | boolean | `false` | Whether to automatically submit the form when all fields are filled |
| `separator` | string | `''` | Character to visually separate groups of inputs |
| `group-size` | integer | `0` | Number of inputs per group (0 means no grouping) |
| `size` | string | `'md'` | The size of the PIN input fields (`sm`, `md`, `lg`) |
| `input-class` | string | `null` | Additional classes for the individual input fields |
| `required` | boolean | `false` | Whether the PIN input is required |
| `disabled` | boolean | `false` | Whether the PIN input is disabled |
| `readonly` | boolean | `false` | Whether the PIN input is readonly |
| `autofocus` | boolean | `false` | Whether the first PIN input should be automatically focused |
| `helper` | string | `null` | Helper text displayed below the PIN input |
| `error` | string | `null` | Error message to display |

## Events

The Pin component supports the following events:

- `input` - Triggered when any input field changes
- `change` - Triggered when the PIN value changes
- `complete` - Triggered when all fields are filled
- `focus` - Triggered when an input field is focused
- `blur` - Triggered when an input field loses focus

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Pin component provides a customizable PIN input interface. You can customize the appearance by:

1. Using the provided props (`size`, `input-class`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the spacing between input fields

### Custom Styling Example

```php
<x-artisanpack-pin 
    label="Custom styled PIN" 
    length="4" 
    class="gap-6"
    input-class="h-16 w-16 text-2xl font-bold border-2 border-primary rounded-lg focus:border-primary-focus focus:ring-primary"
/>
```

## Accessibility

The Pin component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation (arrow keys to move between fields)
- Maintains focus management (automatically moves focus to the next field)
- Ensures adequate color contrast
- Provides clear feedback on input status

## Related Components

- [Form](Form) - Container for form elements
- [Input](Input) - Standard text input
- [Password](Password) - Password input component
