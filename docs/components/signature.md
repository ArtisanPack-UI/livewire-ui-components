---
title: Signature Component
---

# Signature Component

The Signature component is a specialized form element that allows users to draw their signature using a mouse, touch, or stylus. It provides a canvas-based interface for capturing handwritten signatures with various customization options and export capabilities.

## Basic Usage

```php
<x-artisanpack-signature label="Signature" />
```

## Examples

### Simple Signature Pad

```php
<x-artisanpack-signature 
    label="Please sign here" 
    name="signature"
/>
```

### Required Signature

```php
<x-artisanpack-signature 
    label="Signature" 
    required
/>
```

### Signature with Helper Text

```php
<x-artisanpack-signature 
    label="Signature" 
    helper="Use your mouse or finger to sign"
/>
```

### Signature with Error

```php
<x-artisanpack-signature 
    label="Signature" 
    error="Signature is required"
/>
```

### Signature with Livewire Binding

```php
<x-artisanpack-signature 
    label="Signature" 
    wire:model="signature"
/>
```

### Signature with Custom Dimensions

```php
<x-artisanpack-signature 
    label="Signature" 
    width="500" 
    height="200"
/>
```

### Signature with Custom Background Color

```php
<x-artisanpack-signature 
    label="Signature" 
    background-color="#f3f4f6"
/>
```

### Signature with Custom Pen Color

```php
<x-artisanpack-signature 
    label="Signature" 
    pen-color="#3b82f6"
/>
```

### Signature with Custom Pen Width

```php
<x-artisanpack-signature 
    label="Signature" 
    pen-width="3"
/>
```

### Signature with Border

```php
<x-artisanpack-signature 
    label="Signature" 
    bordered
/>
```

### Signature with Custom Border

```php
<x-artisanpack-signature 
    label="Signature" 
    bordered 
    border-color="#3b82f6" 
    border-width="2"
/>
```

### Signature with Clear Button

```php
<x-artisanpack-signature 
    label="Signature" 
    with-clear-button
/>
```

### Signature with Custom Clear Button Text

```php
<x-artisanpack-signature 
    label="Signature" 
    with-clear-button 
    clear-button-text="Reset Signature"
/>
```

### Signature with Download Button

```php
<x-artisanpack-signature 
    label="Signature" 
    with-download-button
/>
```

### Signature with Custom Download Button Text

```php
<x-artisanpack-signature 
    label="Signature" 
    with-download-button 
    download-button-text="Save as Image"
/>
```

### Signature with Custom Download Format

```php
<x-artisanpack-signature 
    label="Signature" 
    with-download-button 
    download-format="png"
/>
```

### Signature with Custom Download Filename

```php
<x-artisanpack-signature 
    label="Signature" 
    with-download-button 
    download-filename="my-signature"
/>
```

### Signature with Read-Only Mode

```php
<x-artisanpack-signature 
    label="Signature" 
    :value="$existingSignature" 
    readonly
/>
```

### Signature with Disabled State

```php
<x-artisanpack-signature 
    label="Signature" 
    disabled
/>
```

### Signature with Custom Classes

```php
<x-artisanpack-signature 
    label="Signature" 
    class="border-2 border-primary rounded-lg"
/>
```

### Signature with Custom Button Classes

```php
<x-artisanpack-signature 
    label="Signature" 
    with-clear-button 
    with-download-button 
    button-class="btn btn-sm btn-primary"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the signature pad |
| `name` | string | `null` | The name attribute for the signature pad |
| `id` | string | `null` | The ID attribute for the signature pad (auto-generated if not provided) |
| `value` | string | `null` | The default value for the signature pad (base64 encoded image) |
| `width` | integer | `300` | The width of the signature pad in pixels |
| `height` | integer | `150` | The height of the signature pad in pixels |
| `background-color` | string | `'#ffffff'` | The background color of the signature pad |
| `pen-color` | string | `'#000000'` | The color of the pen/stroke |
| `pen-width` | float | `2.0` | The width of the pen/stroke |
| `bordered` | boolean | `false` | Whether to display a border around the signature pad |
| `border-color` | string | `'#d1d5db'` | The color of the border |
| `border-width` | integer | `1` | The width of the border in pixels |
| `with-clear-button` | boolean | `false` | Whether to display a clear button |
| `clear-button-text` | string | `'Clear'` | The text for the clear button |
| `with-download-button` | boolean | `false` | Whether to display a download button |
| `download-button-text` | string | `'Download'` | The text for the download button |
| `download-format` | string | `'jpeg'` | The format for downloaded signatures (`jpeg`, `png`, `svg`) |
| `download-filename` | string | `'signature'` | The filename for downloaded signatures |
| `button-class` | string | `null` | Additional classes for the buttons |
| `required` | boolean | `false` | Whether the signature pad is required |
| `disabled` | boolean | `false` | Whether the signature pad is disabled |
| `readonly` | boolean | `false` | Whether the signature pad is readonly |
| `helper` | string | `null` | Helper text displayed below the signature pad |
| `error` | string | `null` | Error message to display |

## Events

The Signature component supports the following events:

- `begin` - Triggered when the user starts drawing
- `end` - Triggered when the user stops drawing
- `change` - Triggered when the signature changes
- `clear` - Triggered when the signature is cleared
- `download` - Triggered when the signature is downloaded

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Methods

The Signature component exposes the following methods that can be accessed via JavaScript:

| Method | Description |
|--------|-------------|
| `clear()` | Clears the signature pad |
| `isEmpty()` | Returns true if the signature pad is empty |
| `toDataURL(type, quality)` | Converts the signature to a data URL |
| `fromDataURL(dataURL)` | Loads a signature from a data URL |
| `toSVG()` | Converts the signature to SVG |

## Styling

The Signature component provides a customizable signature pad interface. You can customize the appearance by:

1. Using the provided props (`width`, `height`, `background-color`, `pen-color`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the button appearance via the `button-class` attribute

### Custom Styling Example

```php
<x-artisanpack-signature 
    label="Custom styled signature" 
    class="border-2 border-primary rounded-lg shadow-lg"
    background-color="#f8fafc"
    pen-color="#3b82f6"
    pen-width="2.5"
    with-clear-button
    with-download-button
    button-class="bg-primary text-white hover:bg-primary-focus px-4 py-2 rounded-md"
/>
```

## Accessibility

The Signature component follows accessibility best practices:

- Associates labels with the signature pad using proper HTML markup
- Includes appropriate ARIA attributes
- Provides keyboard shortcuts for common actions
- Ensures adequate color contrast
- Provides clear feedback on signature status

## Related Components

- [Form](form) - Container for form elements
- [Input](input) - Standard text input
- [File](file) - File upload component
