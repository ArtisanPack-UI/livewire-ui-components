---
title: Icon Component
---

# Icon Component

The Icon component provides an easy way to display SVG icons in your application. It supports Heroicons out of the box and can be extended to use custom icons.

## Basic Usage

```php
<x-artisanpack-icon name="o-home" />
```

## Examples

### Icon Variants

```php
<!-- Outline style (o-) -->
<x-artisanpack-icon name="o-user" />

<!-- Solid style (s-) -->
<x-artisanpack-icon name="s-user" />

<!-- Mini style (m-) -->
<x-artisanpack-icon name="m-user" />
```

### Custom Sizing

```php
<!-- Default size (w-5 h-5) -->
<x-artisanpack-icon name="o-bell" />

<!-- Small icon -->
<x-artisanpack-icon name="o-bell" class="w-4 h-4" />

<!-- Large icon -->
<x-artisanpack-icon name="o-bell" class="w-8 h-8" />
```

### Icon with Label

```php
<x-artisanpack-icon name="o-envelope" label="Messages" />

<!-- With custom styling -->
<x-artisanpack-icon name="o-envelope" label="Messages" class="w-6 h-6 text-primary" />
```

### Icon with Custom Color

```php
<x-artisanpack-icon name="o-exclamation-triangle" class="text-warning" />
<x-artisanpack-icon name="o-check-circle" class="text-success" />
<x-artisanpack-icon name="o-x-circle" class="text-error" />
<x-artisanpack-icon name="o-information-circle" class="text-info" />
```

### Using Custom Icons

```php
<!-- Using a custom icon with dot notation -->
<x-artisanpack-icon name="custom.icon-name" />
```

### Icon in Button

```php
<x-artisanpack-button>
    <x-artisanpack-icon name="o-plus" class="mr-2" />
    Add Item
</x-artisanpack-button>

<!-- Icon-only button -->
<x-artisanpack-button circle>
    <x-artisanpack-icon name="o-trash" />
</x-artisanpack-button>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | - | The name of the icon to display (required) |
| `id` | string\|null | `null` | Optional ID for the icon element |
| `label` | string\|null | `null` | Optional text label to display next to the icon |

## Icon Naming Convention

The Icon component supports the following naming conventions:

1. **Heroicons** (default): Use the prefix followed by the icon name
   - `o-` for outline style (e.g., `o-home`)
   - `s-` for solid style (e.g., `s-home`)
   - `m-` for mini style (e.g., `m-home`)

2. **Custom Icons**: Use dot notation to reference custom icons
   - Example: `custom.icon-name` will be converted to `custom-icon-name`

## Styling

The Icon component has a default size of `w-5 h-5` (1.25rem × 1.25rem). You can customize the appearance by:

1. Adding size classes: `w-{size} h-{size}`
2. Adding color classes: `text-{color}`
3. Adding other utility classes for opacity, transitions, etc.

### Default Classes

- `inline` - Makes the icon display inline with text
- `w-5 h-5` - Default size (can be overridden)

## Accessibility

When using icons that convey meaning (rather than just decoration), it's important to provide appropriate accessibility features:

- Use the `label` prop to provide a text alternative for screen readers
- For decorative icons, no label is necessary
- When using icons in interactive elements like buttons, ensure the button has accessible text

## Related Components

- [Button](Button) - Interactive button element
- [Alert](Alert) - Alert messages
- [Toast](Toast) - Toast notifications