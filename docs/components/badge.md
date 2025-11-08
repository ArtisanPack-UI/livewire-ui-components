---
title: Badge Component
---

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

## Accessibility

The Badge component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The Badge component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `role` | string | Typically "status" for informational badges |
| `aria-label` | string | Provides context when badge text alone isn't sufficient |
| `aria-live` | string | Set to "polite" for dynamic badge updates |
| `aria-hidden` | boolean | Set to "true" for purely decorative badges |

### Semantic HTML

- Badges are inline elements that supplement existing content
- Use `<span>` elements with appropriate ARIA roles
- Badge content should be concise and meaningful

### Screen Reader Behavior

- **Informational badges**: Announced as supplementary information
- **Status badges**: Announced with role="status" for state changes
- **Count badges**: Announced with proper context (e.g., "5 notifications")
- **Decorative badges**: Hidden from screen readers with `aria-hidden="true"`

### Color Contrast

All badge variants meet WCAG AA color contrast requirements:
- Badge text: 4.5:1 minimum contrast ratio with background
- Badge border: 3:1 minimum contrast ratio (for outline variants)
- Information is never conveyed by color alone

### Example: Accessible Status Badge

```blade
<div>
    <span>Order Status:</span>
    <x-artisanpack-badge
        value="Shipped"
        color="success"
        role="status"
        aria-label="Order status: Shipped"
    />
</div>
```

### Example: Accessible Count Badge

```blade
<button aria-label="Notifications: 5 unread">
    Notifications
    <x-artisanpack-badge value="5" color="error" aria-label="5 unread" />
</button>
```

### Example: Decorative Badge

```blade
<!-- When badge duplicates visible text -->
<h2>
    Premium Plan
    <x-artisanpack-badge value="Premium" color="primary" aria-hidden="true" />
</h2>
```

### Best Practices

1. **Provide context**: Don't rely solely on badge color to convey meaning
2. **Use with labels**: Always pair badges with descriptive text
3. **Meaningful text**: Badge content should be self-explanatory
4. **Status updates**: Use `aria-live` for dynamic badge changes
5. **Avoid redundancy**: Use `aria-hidden` when badge duplicates visible content

### Common Accessibility Issues to Avoid

❌ **Don't**: Rely on color alone
```blade
<x-artisanpack-badge value="●" color="success" />
<x-artisanpack-badge value="●" color="error" />
<!-- No way to distinguish without seeing colors -->
```

✅ **Do**: Use descriptive text
```blade
<x-artisanpack-badge value="Active" color="success" />
<x-artisanpack-badge value="Inactive" color="error" />
```

❌ **Don't**: Use badges without context
```blade
<x-artisanpack-badge value="5" color="error" />
<!-- 5 what? Users don't know -->
```

✅ **Do**: Provide context
```blade
<button aria-label="Messages: 5 unread">
    Messages
    <x-artisanpack-badge value="5" color="error" />
</button>
```

❌ **Don't**: Use unclear abbreviations
```blade
<x-artisanpack-badge value="VIP" />
<!-- Not everyone knows what VIP means in your context -->
```

✅ **Do**: Provide aria-label for clarity
```blade
<x-artisanpack-badge value="VIP" aria-label="Very Important Person" />
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter BadgeAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Use of Color](https://www.w3.org/WAI/WCAG21/quickref/#use-of-color)
- [MDN ARIA: status role](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/status_role)
- [Accessibility Guidelines](../accessibility/guidelines.md)
