---
title: Textarea Component
---

# Textarea Component

The Textarea component is a form element that allows users to enter multi-line text input. It provides a user-friendly way to implement textarea inputs with various styling options and features.

## Basic Usage

```php
<x-artisanpack-textarea label="Description" />
```

## Examples

### Simple Textarea

```php
<x-artisanpack-textarea 
    label="Message" 
    name="message" 
    placeholder="Enter your message here..."
/>
```

### Textarea with Default Value

```php
<x-artisanpack-textarea 
    label="Bio" 
    value="I am a software developer with 5 years of experience."
/>
```

### Required Textarea

```php
<x-artisanpack-textarea 
    label="Feedback" 
    required
/>
```

### Disabled Textarea

```php
<x-artisanpack-textarea 
    label="Terms and Conditions" 
    value="These are the terms and conditions..." 
    disabled
/>
```

### Readonly Textarea

```php
<x-artisanpack-textarea 
    label="Terms and Conditions" 
    value="These are the terms and conditions..." 
    readonly
/>
```

### Textarea with Helper Text

```php
<x-artisanpack-textarea 
    label="Bio" 
    helper="Tell us about yourself in a few sentences"
/>
```

### Textarea with Error

```php
<x-artisanpack-textarea 
    label="Message" 
    error="Please enter a message"
/>
```

### Textarea with Livewire Binding

```php
<x-artisanpack-textarea 
    label="Message" 
    wire:model="message"
/>

<!-- Debounced Textarea -->
<x-artisanpack-textarea 
    label="Message" 
    wire:model.debounce.500ms="message"
/>

<!-- Lazy Textarea (updates on blur) -->
<x-artisanpack-textarea 
    label="Message" 
    wire:model.lazy="message"
/>
```

### Textarea with Custom Rows

```php
<x-artisanpack-textarea 
    label="Short note" 
    rows="3"
/>

<x-artisanpack-textarea 
    label="Detailed description" 
    rows="10"
/>
```

### Textarea with Character Count

```php
<x-artisanpack-textarea 
    label="Tweet" 
    maxlength="280" 
    show-character-count
/>
```

### Textarea with Autofocus

```php
<x-artisanpack-textarea 
    label="Quick note" 
    autofocus
/>
```

### Textarea with Resize Options

```php
<x-artisanpack-textarea 
    label="No resize" 
    resize="none"
/>

<x-artisanpack-textarea 
    label="Vertical resize only" 
    resize="vertical"
/>

<x-artisanpack-textarea 
    label="Horizontal resize only" 
    resize="horizontal"
/>

<x-artisanpack-textarea 
    label="Both directions resize" 
    resize="both"
/>
```

### Textarea Sizes

```php
<x-artisanpack-textarea 
    label="Small textarea" 
    size="sm"
/>

<x-artisanpack-textarea 
    label="Default textarea"
/>

<x-artisanpack-textarea 
    label="Large textarea" 
    size="lg"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the textarea |
| `name` | string | `null` | The name attribute for the textarea |
| `id` | string | `null` | The ID attribute for the textarea (auto-generated if not provided) |
| `value` | string | `null` | The default value for the textarea |
| `placeholder` | string | `null` | Placeholder text |
| `rows` | integer | `4` | Number of visible text lines |
| `cols` | integer | `null` | Number of visible text columns |
| `maxlength` | integer | `null` | Maximum number of characters allowed |
| `minlength` | integer | `null` | Minimum number of characters required |
| `show-character-count` | boolean | `false` | Whether to display the character count |
| `required` | boolean | `false` | Whether the textarea is required |
| `disabled` | boolean | `false` | Whether the textarea is disabled |
| `readonly` | boolean | `false` | Whether the textarea is readonly |
| `autofocus` | boolean | `false` | Whether the textarea should be automatically focused |
| `resize` | string | `'vertical'` | Resize behavior (`none`, `vertical`, `horizontal`, `both`) |
| `size` | string | `'md'` | The size of the textarea (`sm`, `md`, `lg`) |
| `helper` | string | `null` | Helper text displayed below the textarea |
| `error` | string | `null` | Error message to display |

## Events

The Textarea component supports all standard HTML textarea events:

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

The Textarea component uses DaisyUI's textarea component under the hood, which provides a consistent styling with other form components. You can customize the appearance of textareas by:

1. Using the provided props (`size`, `resize`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-textarea 
    label="Custom styled textarea" 
    class="border-2 border-purple-500 focus:border-purple-700 focus:ring-purple-700"
/>
```

### DaisyUI Variables

You can customize the Textarea component by modifying these DaisyUI variables in your theme file:

```css
.textarea {
    --input-color: oklch(var(--b2));
    --size: 3rem;
}
```

## Accessibility

The Textarea component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Displays validation errors with proper associations
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Single-line text input
- [Editor](editor.md) - Rich text editor
