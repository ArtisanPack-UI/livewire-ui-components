---
title: Nav Component
---

# Nav Component

The Nav component provides a navigation bar for your application with support for brand and action elements.

## Basic Usage

```blade
<x-artisanpack-nav>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## With Logo

```blade
<x-artisanpack-nav>
    <x-slot:brand>
        <a href="/" class="flex items-center gap-2">
            <img src="/logo.svg" alt="Logo" class="w-8 h-8" />
            <span class="text-xl font-bold">My App</span>
        </a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Sticky Navigation

```blade
<x-artisanpack-nav sticky>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Full Width Navigation

```blade
<x-artisanpack-nav full-width>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sticky | boolean | false | Whether the navigation bar should stick to the top of the viewport |
| fullWidth | boolean | false | Whether the navigation bar should span the full width of the viewport |

## Slots

| Name | Description |
|------|-------------|
| brand | Content for the brand/logo area on the left side of the navigation bar |
| actions | Content for the actions area on the right side of the navigation bar |


## Accessibility

The Nav component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### Semantic HTML

- Uses appropriate semantic HTML elements
- Maintains proper document structure
- Decorative elements are hidden from screen readers

### Screen Reader Behavior

- Component content is announced appropriately
- Semantic meaning is preserved
- Visual styling does not affect accessibility

### Color Contrast

- All text meets WCAG AA color contrast requirements (4.5:1 minimum)
- Visual indicators have sufficient contrast

### Best Practices

1. **Use semantic HTML**: Leverage native elements when possible
2. **Proper hierarchy**: Maintain logical document structure
3. **Sufficient contrast**: Ensure all content is readable
4. **Decorative vs informative**: Mark decorative elements appropriately

### Example: Accessible Nav

```blade
<x-artisanpack-nav>
    Accessible content
</x-artisanpack-nav>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter NavAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Accessibility Guidelines](../accessibility/guidelines.md)

