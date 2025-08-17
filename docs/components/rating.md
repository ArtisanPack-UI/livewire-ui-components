# Rating Component

The Rating component is a form element that allows users to provide a rating using a visual star system. Built on DaisyUI's rating component, it provides an intuitive interface for collecting user feedback or displaying ratings with extensive customization options.

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
    :value="3"
    :total="5"
/>
```

### Required Rating

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :required="true"
/>
```

### Disabled Rating

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :value="4" 
    :disabled="true"
/>
```

### Read-Only Rating

```php
<x-artisanpack-rating 
    label="Average Rating" 
    :value="4" 
    :readonly="true"
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

### Rating with Custom Total

```php
<x-artisanpack-rating 
    label="Rate out of 10" 
    :total="10"
/>
```

### Rating with Value Display

```php
<x-artisanpack-rating 
    label="Product Rating" 
    :value="4"
    :show-value="true"
/>
```

### Rating with Custom Colors

```php
<!-- DaisyUI semantic colors -->
<x-artisanpack-rating color="primary" :value="3" />
<x-artisanpack-rating color="secondary" :value="4" />
<x-artisanpack-rating color="accent" :value="5" />

<!-- Tailwind colors -->
<x-artisanpack-rating color="red-500" :value="2" />
<x-artisanpack-rating color="green-600" :value="4" />
<x-artisanpack-rating color="blue-400" :value="3" />

<!-- Hex colors -->
<x-artisanpack-rating color="#ff6b6b" :value="5" />
<x-artisanpack-rating color="#4ecdc4" :value="4" />
```

### Rating with Different Sizes

```php
<x-artisanpack-rating label="Small Rating" size="sm" :value="3" />
<x-artisanpack-rating label="Medium Rating" size="md" :value="3" />
<x-artisanpack-rating label="Large Rating" size="lg" :value="3" />
<x-artisanpack-rating label="Extra Large Rating" size="xl" :value="3" />
```

### Rating with Different Icons

```php
<!-- Heart icons -->
<x-artisanpack-rating 
    label="Love Rating" 
    icon="s-heart"
    :value="4"
/>

<!-- Triangle icons -->
<x-artisanpack-rating 
    label="Priority Rating" 
    icon="s-triangle"
    :value="3"
/>

<!-- Square icons -->
<x-artisanpack-rating 
    label="Quality Rating" 
    icon="s-square"
    :value="5"
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
| `value` | integer | `0` | The current value for the rating (1 to total) |
| `total` | integer | `5` | The total number of rating items to display |
| `color` | string | `'warning'` | The color of the rating items. Supports DaisyUI colors (`primary`, `secondary`, `accent`, `warning`, `error`, `success`, `info`), Tailwind colors (`red-500`, `blue-400`, etc.), or hex values (`#ff6b6b`) |
| `icon` | string | `null` | The icon type to use. Options: `s-heart`, `s-triangle`, `s-square` (defaults to star if not specified) |
| `size` | string | `'md'` | The size of the rating items (`sm`, `md`, `lg`, `xl`) |
| `show-value` | boolean | `false` | Whether to display the current rating value next to the rating |
| `inline-label` | boolean | `false` | Whether to display the label inline with the rating items |
| `required` | boolean | `false` | Whether the rating component is required |
| `disabled` | boolean | `false` | Whether the rating component is disabled |
| `readonly` | boolean | `false` | Whether the rating component is readonly |
| `helper` | string | `null` | Helper text displayed below the rating component |
| `error` | string | `null` | Error message to display |

## Events

The Rating component supports all standard form events and Livewire model binding directives:

- `wire:model` - Two-way data binding
- `wire:model.live` - Real-time updates
- `wire:change` - Triggered when the rating value changes

## Styling

The Rating component is built on DaisyUI's rating component and uses CSS masks for icons. You can customize the appearance by:

1. Using the provided props (`size`, `color`, `icon`)
2. Adding custom classes via the `class` attribute
3. Using DaisyUI's built-in rating utilities

### Custom Styling Example

```php
<x-artisanpack-rating 
    label="Custom styled rating" 
    color="primary"
    size="lg"
    :total="10"
/>
```

### Implementation Details

The component uses DaisyUI's mask utilities to create the star shapes:
- `mask-star-2` for star icons (default)
- `mask-heart` for heart icons
- `mask-triangle` for triangle icons  
- `mask-squircle` for square icons

Colors are applied using background color classes that work with the mask to create the desired appearance.

## Accessibility

The Rating component follows accessibility best practices:

- Uses semantic HTML with radio inputs for proper form behavior
- Associates labels with the rating component using proper HTML markup
- Supports keyboard navigation (Tab to focus, arrow keys or Enter/Space to select)
- Maintains focus management within the rating group
- Provides screen reader accessible labels and values
- Ensures adequate color contrast with customizable colors

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [Range](range.md) - Range slider input
