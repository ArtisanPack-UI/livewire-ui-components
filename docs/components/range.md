# Range Component

The Range component is a form element that provides a slider interface for selecting a numeric value within a specified range. It offers a more intuitive way for users to input numeric values compared to standard number inputs.

## Basic Usage

```php
<x-artisanpack-range label="Volume" />
```

## Examples

### Simple Range Slider

```php
<x-artisanpack-range 
    label="Brightness" 
    name="brightness"
/>
```

### Range with Min and Max Values

```php
<x-artisanpack-range 
    label="Temperature" 
    min="15" 
    max="30"
/>
```

### Range with Default Value

```php
<x-artisanpack-range 
    label="Volume" 
    value="50"
/>
```

### Range with Step

```php
<x-artisanpack-range 
    label="Quantity" 
    min="0" 
    max="100" 
    step="5"
/>
```

### Required Range

```php
<x-artisanpack-range 
    label="Rating" 
    required
/>
```

### Disabled Range

```php
<x-artisanpack-range 
    label="Progress" 
    value="75" 
    disabled
/>
```

### Range with Helper Text

```php
<x-artisanpack-range 
    label="Opacity" 
    helper="Adjust the transparency level"
/>
```

### Range with Error

```php
<x-artisanpack-range 
    label="Quantity" 
    error="Please select a valid quantity"
/>
```

### Range with Livewire Binding

```php
<x-artisanpack-range 
    label="Volume" 
    wire:model="volume"
/>

<!-- Lazy Range (updates on blur) -->
<x-artisanpack-range 
    label="Volume" 
    wire:model.lazy="volume"
/>
```

### Range with Value Display

```php
<x-artisanpack-range 
    label="Zoom" 
    min="50" 
    max="200" 
    value="100" 
    with-value
/>
```

### Range with Custom Value Format

```php
<x-artisanpack-range 
    label="Price" 
    min="0" 
    max="1000" 
    step="50" 
    with-value 
    value-format="$%s"
/>
```

### Range with Value Tooltip

```php
<x-artisanpack-range 
    label="Progress" 
    with-tooltip
/>
```

### Range with Custom Colors

```php
<x-artisanpack-range 
    label="Temperature" 
    color="error" 
    min="0" 
    max="100" 
    value="75"
/>

<x-artisanpack-range 
    label="Progress" 
    color="success" 
    min="0" 
    max="100" 
    value="50"
/>

<x-artisanpack-range 
    label="Volume" 
    color="accent" 
    min="0" 
    max="100" 
    value="25"
/>
```

### Range Sizes

```php
<x-artisanpack-range 
    label="Small range" 
    size="sm"
/>

<x-artisanpack-range 
    label="Default range"
/>

<x-artisanpack-range 
    label="Large range" 
    size="lg"
/>

<x-artisanpack-range 
    label="Extra large range" 
    size="xl"
/>
```

### Range with Custom Marks

```php
<x-artisanpack-range 
    label="Volume" 
    min="0" 
    max="100" 
    step="25" 
    with-marks
/>
```

### Range with Custom Width

```php
<x-artisanpack-range 
    label="Zoom" 
    class="w-64"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the range slider |
| `name` | string | `null` | The name attribute for the range slider |
| `id` | string | `null` | The ID attribute for the range slider (auto-generated if not provided) |
| `value` | number | `50` | The default value for the range slider |
| `min` | number | `0` | The minimum value of the range |
| `max` | number | `100` | The maximum value of the range |
| `step` | number | `1` | The step increment of the range |
| `required` | boolean | `false` | Whether the range slider is required |
| `disabled` | boolean | `false` | Whether the range slider is disabled |
| `helper` | string | `null` | Helper text displayed below the range slider |
| `error` | string | `null` | Error message to display |
| `color` | string | `'primary'` | The color of the range slider (`primary`, `secondary`, `accent`, etc.) |
| `size` | string | `'md'` | The size of the range slider (`sm`, `md`, `lg`, `xl`) |
| `with-value` | boolean | `false` | Whether to display the current value |
| `value-format` | string | `'%s'` | Format string for the displayed value (use %s as placeholder) |
| `with-tooltip` | boolean | `false` | Whether to show a tooltip with the current value |
| `with-marks` | boolean | `false` | Whether to show marks at step intervals |

## Events

The Range component supports all standard HTML input events:

- `input`
- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`
- `wire:model.live`

## Styling

The Range component uses DaisyUI's range component under the hood, which provides a consistent styling with other form components. You can customize the appearance by:

1. Using the provided props (`color`, `size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-range 
    label="Custom styled range" 
    class="w-full max-w-xs"
    color="accent"
/>
```

## Accessibility

The Range component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation (arrow keys for incremental changes)
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast
- Provides visual feedback on the current value

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [Slider](slider.md) - Advanced slider component with additional features
