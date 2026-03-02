---
title: Colorpicker Component
---

# Colorpicker Component

The Colorpicker component is a form element that provides an interactive interface for selecting colors. It offers various color selection modes, format options, and integrates seamlessly with Livewire for handling color inputs.

## Basic Usage

```php
<x-artisanpack-colorpicker label="Select Color" />
```

## Examples

### Simple Colorpicker

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    name="brand_color"
/>
```

### Colorpicker with Default Value

```php
<x-artisanpack-colorpicker 
    label="Primary Color" 
    value="#3b82f6"
/>
```

### Required Colorpicker

```php
<x-artisanpack-colorpicker 
    label="Theme Color" 
    required
/>
```

### Disabled Colorpicker

```php
<x-artisanpack-colorpicker 
    label="System Color" 
    value="#10b981" 
    disabled
/>
```

### Colorpicker with Helper Text

```php
<x-artisanpack-colorpicker 
    label="Accent Color" 
    helper="Choose a color that complements your primary color"
/>
```

### Colorpicker with Error

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    error="Please select a valid color"
/>
```

### Colorpicker with Livewire Binding

```php
<x-artisanpack-colorpicker 
    label="Theme Color" 
    wire:model="themeColor"
/>

<!-- Lazy Colorpicker (updates on blur) -->
<x-artisanpack-colorpicker 
    label="Theme Color" 
    wire:model.lazy="themeColor"
/>
```

### Colorpicker with Different Formats

```php
<x-artisanpack-colorpicker 
    label="Hex Color" 
    format="hex"
/>

<x-artisanpack-colorpicker 
    label="RGB Color" 
    format="rgb"
/>

<x-artisanpack-colorpicker 
    label="RGBA Color" 
    format="rgba"
/>

<x-artisanpack-colorpicker 
    label="HSL Color" 
    format="hsl"
/>

<x-artisanpack-colorpicker 
    label="HSLA Color" 
    format="hsla"
/>
```

### Colorpicker with Alpha Channel

```php
<x-artisanpack-colorpicker 
    label="Color with Transparency" 
    with-alpha
/>
```

### Colorpicker with Color Palette

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    :palette="['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#d946ef', '#ec4899']"
/>
```

### Colorpicker with Custom Swatches

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    :swatches="[
        '#000000', '#ffffff', '#f44336', '#e91e63', 
        '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', 
        '#03a9f4', '#00bcd4', '#009688', '#4caf50', 
        '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107'
    ]"
/>
```

### Inline Colorpicker

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    inline
/>
```

### Colorpicker with Custom Popup Position

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    position="top-right"
/>
```

### Colorpicker with Custom Width

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    class="w-64"
/>
```

### Colorpicker with Preview

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    with-preview
/>
```

### Colorpicker with Input Field

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    with-input
/>
```

### Colorpicker with Custom Trigger

```php
<x-artisanpack-colorpicker 
    label="Brand Color" 
    trigger-style="circle"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the colorpicker |
| `name` | string | `null` | The name attribute for the colorpicker |
| `id` | string | `null` | The ID attribute for the colorpicker (auto-generated if not provided) |
| `value` | string | `'#000000'` | The default value for the colorpicker |
| `format` | string | `'hex'` | The color format (`hex`, `rgb`, `rgba`, `hsl`, `hsla`) |
| `with-alpha` | boolean | `false` | Whether to include alpha channel (transparency) |
| `palette` | array | `[]` | Array of predefined colors to show as a palette |
| `swatches` | array | `[]` | Array of color swatches for quick selection |
| `inline` | boolean | `false` | Whether to display the colorpicker inline (always visible) |
| `position` | string | `'bottom-left'` | Position of the popup (`top-left`, `top-right`, `bottom-left`, `bottom-right`) |
| `with-preview` | boolean | `false` | Whether to show a preview of the selected color |
| `with-input` | boolean | `false` | Whether to show an input field for manual color entry |
| `trigger-style` | string | `'square'` | Style of the trigger button (`square`, `circle`, `input`) |
| `required` | boolean | `false` | Whether the colorpicker is required |
| `disabled` | boolean | `false` | Whether the colorpicker is disabled |
| `helper` | string | `null` | Helper text displayed below the colorpicker |
| `error` | string | `null` | Error message to display |

## Events

The Colorpicker component supports the following events:

- `change` - Triggered when the color is changed
- `input` - Triggered when the color is being changed
- `open` - Triggered when the color picker popup is opened
- `close` - Triggered when the color picker popup is closed

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`
- `wire:model.live`

## Styling

The Colorpicker component provides a customizable color selection interface. You can customize the appearance by:

1. Using the provided props (`trigger-style`, `with-preview`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the color palette and swatches

### Custom Styling Example

```php
<x-artisanpack-colorpicker 
    label="Custom styled colorpicker" 
    class="border-2 border-purple-500 focus:border-purple-700"
    trigger-style="circle"
/>
```

## Accessibility

The Colorpicker component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation
- Maintains focus management between trigger and popup
- Ensures adequate color contrast
- Provides multiple ways to select colors (visual picker, input field)

## Related Components

- [Form](Form) - Container for form elements
- [Input](Input) - Standard text input
- [ThemeToggle](Theme-Toggle) - Specialized toggle for theme switching
