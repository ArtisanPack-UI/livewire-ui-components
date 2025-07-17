# Badge Component

The Badge component displays a small count, label, or status indicator.

## Basic Usage

```blade
<x-artisanpack-badge value="New" />
```

## With Different Colors

```blade
<x-artisanpack-badge value="Primary" class="badge-primary" />
<x-artisanpack-badge value="Secondary" class="badge-secondary" />
<x-artisanpack-badge value="Accent" class="badge-accent" />
<x-artisanpack-badge value="Info" class="badge-info" />
<x-artisanpack-badge value="Success" class="badge-success" />
<x-artisanpack-badge value="Warning" class="badge-warning" />
<x-artisanpack-badge value="Error" class="badge-error" />
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

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. The component uses DaisyUI's badge classes for styling. Here are some of the available classes:

- `badge-neutral`: Default style
- `badge-primary`, `badge-secondary`, `badge-accent`: Brand colors
- `badge-info`, `badge-success`, `badge-warning`, `badge-error`: State colors
- `badge-outline`: Outlined style
- `badge-lg`, `badge-md`, `badge-sm`, `badge-xs`: Size variations

You can combine these classes to create different badge styles.
