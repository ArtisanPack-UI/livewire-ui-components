---
title: Breadcrumbs Component
---

# Breadcrumbs Component

The Breadcrumbs component provides a navigation aid that helps users understand their current location within the application's hierarchy.

## Basic Usage

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/'],
    ['label' => 'Products', 'link' => '/products'],
    ['label' => 'Product Name'],
]" />
```

## With Icons

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'icon' => 'o-home'],
    ['label' => 'Products', 'link' => '/products', 'icon' => 'o-shopping-bag'],
    ['label' => 'Product Name', 'icon' => 'o-tag'],
]" />
```

## With Custom Separator

```blade
<x-artisanpack-breadcrumbs 
    separator="o-chevron-double-right"
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Product Name'],
    ]" 
/>
```

## With Tooltips

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'tooltip' => 'Go to homepage'],
    ['label' => 'Products', 'link' => '/products', 'tooltip' => 'View all products'],
    ['label' => 'Product Name', 'tooltip' => 'You are here'],
]" />
```

## With Custom Classes

```blade
<x-artisanpack-breadcrumbs 
    link-item-class="text-primary hover:text-primary-focus"
    text-item-class="text-gray-500"
    icon-class="w-5 h-5"
    separator-class="h-4 w-4 mx-2 text-gray-400"
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Product Name'],
    ]" 
/>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the breadcrumbs |
| items | array | [] | Array of breadcrumb items. Each item can have: 'label', 'link', 'icon', 'tooltip', 'tooltip-left', 'tooltip-right', 'tooltip-bottom', 'tooltip-top' |
| separator | string | 'o-chevron-right' | Icon name for the separator between breadcrumb items |
| linkItemClass | string | "hover:underline text-sm" | CSS classes for breadcrumb items with links |
| textItemClass | string | "text-sm" | CSS classes for breadcrumb items without links |
| iconClass | string | "h-4 w-4" | CSS classes for icons in breadcrumb items |
| separatorClass | string | "h-3 w-3 mx-1 text-base-content/40" | CSS classes for the separator |
| noWireNavigate | boolean | false | Whether to disable wire:navigate on links |

## Item Properties

Each item in the `items` array can have the following properties:

| Name | Type | Description |
|------|------|-------------|
| label | string | Text to display for the breadcrumb item |
| link | string | Optional URL for the breadcrumb item |
| icon | string | Optional icon name for the breadcrumb item |
| tooltip | string | Optional tooltip text for the breadcrumb item |
| tooltip-left | string | Optional tooltip text positioned to the left |
| tooltip-right | string | Optional tooltip text positioned to the right |
| tooltip-bottom | string | Optional tooltip text positioned to the bottom |
| tooltip-top | string | Optional tooltip text positioned to the top |

## Accessibility

The Breadcrumbs component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The Breadcrumbs component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `aria-label` | string | Labels the navigation landmark (default: "Breadcrumb") |
| `aria-current` | string | Set to "page" for the current page item |
| `role` | string | Set to "navigation" for the container |

### Semantic HTML

- Uses `<nav>` element with `aria-label="Breadcrumb"` for navigation landmark
- Uses `<ol>` (ordered list) to maintain hierarchy
- Current page indicated with `aria-current="page"`
- Separators are decorative and hidden from screen readers

### Screen Reader Behavior

- Breadcrumb navigation is announced as a landmark
- Each link is announced with its label
- Current page is announced as "current page"
- Separators between items are hidden from screen readers
- Navigation order follows visual hierarchy

### Keyboard Support

| Key | Action |
|-----|--------|
| Tab | Navigate to next breadcrumb link |
| Shift+Tab | Navigate to previous breadcrumb link |
| Enter | Activate focused link |

### Example: Accessible Breadcrumbs

```blade
<x-artisanpack-breadcrumbs
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Laptop Computers', 'link' => '/products/laptops'],
        ['label' => 'Dell XPS 15'],
    ]"
    aria-label="Breadcrumb navigation"
/>
```

### Color Contrast

All breadcrumb links meet WCAG AA color contrast requirements:
- Link text: 4.5:1 minimum contrast ratio
- Hover states have clear visual indicators
- Current page text has sufficient contrast

### Best Practices

1. **Include home link**: Always start breadcrumbs with a link to the home page
2. **Current page last**: The final item should be the current page (no link)
3. **Meaningful labels**: Use descriptive text, not URLs or IDs
4. **Keep it short**: Limit breadcrumb depth to 5-7 levels
5. **Consistent separator**: Use the same separator throughout

### Common Accessibility Issues to Avoid

❌ **Don't**: Make the current page a link
```blade
<x-artisanpack-breadcrumbs
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Current', 'link' => '/products/current'], // Wrong!
    ]"
/>
```

✅ **Do**: Current page should not have a link
```blade
<x-artisanpack-breadcrumbs
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Current'], // Correct - no link
    ]"
/>
```

❌ **Don't**: Use technical IDs or codes
```blade
<x-artisanpack-breadcrumbs
    :items="[
        ['label' => 'CAT_001', 'link' => '/cat/001'],
        ['label' => 'PROD_12345'],
    ]"
/>
```

✅ **Do**: Use human-readable labels
```blade
<x-artisanpack-breadcrumbs
    :items="[
        ['label' => 'Electronics', 'link' => '/electronics'],
        ['label' => 'Dell XPS 15'],
    ]"
/>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter BreadcrumbsAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Breadcrumb Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/breadcrumb/)
- [MDN ARIA: navigation role](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/navigation_role)
- [Accessibility Guidelines](../accessibility/guidelines.md)
