# Rating Component

The Rating component is a form element that allows users to provide a rating using a visual star (or other icon) system. It provides an intuitive interface for collecting user feedback or displaying ratings with various customization options.

## Basic Usage

```php
<x-artisanpack-rating label="Rate this product" />
```

## Examples

### Simple Rating

```php
<x-artisanpack-rating 
    label="Product Rating" 
    name="product_rating"
/>
```

### Rating with Default Value

```php
<x-artisanpack-rating 
    label="Product Rating" 
    value="3.5"
/>
```

### Required Rating

```php
<x-artisanpack-rating 
    label="Product Rating" 
    required
/>
```

### Disabled Rating

```php
<x-artisanpack-rating 
    label="Product Rating" 
    value="4" 
    disabled
/>
```

### Read-Only Rating

```php
<x-artisanpack-rating 
    label="Average Rating" 
    value="4.2" 
    readonly
/>
```

### Rating with Helper Text

```php
<x-artisanpack-rating 
    label="Product Rating" 
    helper="Click on a star to rate"
/>
```

### Rating with Error

```php
<x-artisanpack-rating 
    label="Product Rating" 
    error="Please provide a rating"
/>
```

### Rating with Livewire Binding

```php
<x-artisanpack-rating 
    label="Product Rating" 
    wire:model="productRating"
/>
```

### Rating with Custom Maximum

```php
<x-artisanpack-rating 
    label="Rate out of 10" 
    :max="10"
/>
```

### Rating with Half Stars

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :half-stars="true"
/>
```

### Rating with Custom Icon

```php
<x-artisanpack-rating 
    label="Product Rating" 
    icon="heroicon-o-heart"
/>
```

### Rating with Different Icons for Filled and Empty

```php
<x-artisanpack-rating 
    label="Product Rating" 
    filled-icon="heroicon-s-heart" 
    empty-icon="heroicon-o-heart"
/>
```

### Rating with Custom Colors

```php
<x-artisanpack-rating 
    label="Product Rating" 
    filled-color="primary" 
    empty-color="gray-300"
/>
```

### Rating with Custom Size

```php
<x-artisanpack-rating 
    label="Product Rating" 
    size="lg"
/>
```

### Rating with Hover Effect

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :hover-effect="true"
/>
```

### Rating with Value Display

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :show-value="true"
/>
```

### Rating with Custom Value Format

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :show-value="true" 
    value-format="{value}/{max}"
/>
```

### Rating with Clear Button

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :clearable="true"
/>
```

### Rating with Custom Clear Icon

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :clearable="true" 
    clear-icon="heroicon-o-x-mark"
/>
```

### Rating with Custom Spacing

```php
<x-artisanpack-rating 
    label="Product Rating" 
    class="gap-2"
/>
```

### Rating with Inline Label

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :inline-label="true"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the rating component |
| `name` | string | `null` | The name attribute for the rating component |
| `id` | string | `null` | The ID attribute for the rating component (auto-generated if not provided) |
| `value` | number | `0` | The default value for the rating |
| `max` | integer | `5` | The maximum rating value |
| `half-stars` | boolean | `false` | Whether to allow half-star ratings |
| `icon` | string | `'heroicon-s-star'` | The icon to use for the rating stars |
| `filled-icon` | string | `null` | The icon to use for filled stars (overrides `icon` for filled stars) |
| `empty-icon` | string | `null` | The icon to use for empty stars (overrides `icon` for empty stars) |
| `filled-color` | string | `'warning'` | The color of filled stars (`primary`, `secondary`, `accent`, etc.) |
| `empty-color` | string | `'gray-200'` | The color of empty stars |
| `size` | string | `'md'` | The size of the rating stars (`sm`, `md`, `lg`, `xl`) |
| `hover-effect` | boolean | `false` | Whether to show a hover effect when hovering over stars |
| `show-value` | boolean | `false` | Whether to display the current rating value |
| `value-format` | string | `'{value}'` | Format for the displayed value (use `{value}` and `{max}` as placeholders) |
| `clearable` | boolean | `false` | Whether to allow clearing the rating |
| `clear-icon` | string | `'heroicon-o-x-circle'` | The icon to use for the clear button |
| `inline-label` | boolean | `false` | Whether to display the label inline with the rating stars |
| `required` | boolean | `false` | Whether the rating component is required |
| `disabled` | boolean | `false` | Whether the rating component is disabled |
| `readonly` | boolean | `false` | Whether the rating component is readonly |
| `helper` | string | `null` | Helper text displayed below the rating component |
| `error` | string | `null` | Error message to display |

## Events

The Rating component supports the following events:

- `change` - Triggered when the rating changes
- `hover` - Triggered when hovering over a star
- `clear` - Triggered when the rating is cleared

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Rating component provides a customizable rating interface. You can customize the appearance by:

1. Using the provided props (`size`, `filled-color`, `empty-color`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the spacing between stars

### Custom Styling Example

```php
<x-artisanpack-rating 
    label="Custom styled rating" 
    class="gap-3"
    filled-color="primary"
    empty-color="gray-300"
    size="lg"
    :hover-effect="true"
/>
```

## Accessibility

The Rating component follows accessibility best practices:

- Associates labels with the rating component using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation (arrow keys to change rating)
- Maintains focus management
- Ensures adequate color contrast
- Provides clear feedback on rating selection

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [Range](range.md) - Range slider input
