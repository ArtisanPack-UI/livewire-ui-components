# Badge Component

The Badge component displays a small count, label, or status indicator with flexible color customization options.

## Basic Usage

```blade
<x-artisanpack-badge value="New" />
```

## Color System

### Predefined Variants

Use predefined color variants for consistent theming:

```blade
<x-artisanpack-badge value="Primary" color="primary" />
<x-artisanpack-badge value="Secondary" color="secondary" />
<x-artisanpack-badge value="Accent" color="accent" />
<x-artisanpack-badge value="Info" color="info" />
<x-artisanpack-badge value="Success" color="success" />
<x-artisanpack-badge value="Warning" color="warning" />
<x-artisanpack-badge value="Error" color="error" />
```

### Tailwind Color Palette

Use any Tailwind color with intensity levels:

```blade
<x-artisanpack-badge value="Red 500" color="red-500" />
<x-artisanpack-badge value="Blue 600" color="blue-600" />
<x-artisanpack-badge value="Green 400" color="green-400" />
<x-artisanpack-badge value="Purple 700" color="purple-700" />
<x-artisanpack-badge value="Yellow 300" color="yellow-300" />
```

### Custom Hex Colors

Use custom hex color codes:

```blade
<x-artisanpack-badge value="Custom" color="#ff6b6b" />
<x-artisanpack-badge value="Brand" color="#4ecdc4" />
<x-artisanpack-badge value="Special" color="#ffe66d" />
```

### Color Adjustments

Fine-tune the background appearance with color adjustments:

```blade
<!-- Lighter background -->
<x-artisanpack-badge value="Lighter" color="blue-500" color-adjustment="lighter" />

<!-- Darker background -->
<x-artisanpack-badge value="Darker" color="blue-500" color-adjustment="darker" />

<!-- Transparent background -->
<x-artisanpack-badge value="Transparent" color="blue-500" color-adjustment="transparent" />

<!-- Subtle background -->
<x-artisanpack-badge value="Subtle" color="blue-500" color-adjustment="subtle" />
```

## With Different Sizes

```blade
<x-artisanpack-badge value="Large" class="badge-lg" />
<x-artisanpack-badge value="Medium" class="badge-md" />
<x-artisanpack-badge value="Small" class="badge-sm" />
<x-artisanpack-badge value="Extra Small" class="badge-xs" />
```

## With Outline

```blade
<x-artisanpack-badge value="Outline" class="badge-outline" />
<x-artisanpack-badge value="Primary Outline" class="badge-primary badge-outline" />
```

## With Custom Content

```blade
<x-artisanpack-badge class="badge-primary">
    <div class="flex items-center gap-1">
        <x-artisanpack-icon name="o-check-circle" class="w-3 h-3" />
        <span>Verified</span>
    </div>
</x-artisanpack-badge>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the badge |
| value | string | null | Text content to display in the badge |
| color | string | null | Color variant, Tailwind color (e.g., 'red-500'), or hex code (e.g., '#ff0000') |
| color-adjustment | string | null | Background adjustment: 'lighter', 'darker', 'transparent', or 'subtle' |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. The component uses DaisyUI's badge classes for styling. Here are some of the available classes:

- `badge-neutral`: Default style
- `badge-primary`, `badge-secondary`, `badge-accent`: Brand colors
- `badge-info`, `badge-success`, `badge-warning`, `badge-error`: State colors
- `badge-outline`: Outlined style
- `badge-lg`, `badge-md`, `badge-sm`, `badge-xs`: Size variations

You can combine these classes to create different badge styles.
