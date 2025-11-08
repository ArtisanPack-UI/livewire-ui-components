---
title: Avatar Component
---

# Avatar Component

The Avatar component displays a user's profile picture with optional title and subtitle text.

## Basic Usage

```blade
<x-artisanpack-avatar image="/path/to/image.jpg" alt="User Name" />
```

## With Placeholder

```blade
<x-artisanpack-avatar placeholder="JD" alt="John Doe" />
```

## Color System

### Predefined Variants

Use predefined color variants for avatar backgrounds:

```blade
<x-artisanpack-avatar placeholder="JD" alt="John Doe" color="primary" />
<x-artisanpack-avatar placeholder="SM" alt="Sarah Miller" color="secondary" />
<x-artisanpack-avatar placeholder="AB" alt="Alex Brown" color="accent" />
<x-artisanpack-avatar placeholder="LJ" alt="Lisa Johnson" color="info" />
<x-artisanpack-avatar placeholder="MW" alt="Mike Wilson" color="success" />
<x-artisanpack-avatar placeholder="ER" alt="Emily Rodriguez" color="warning" />
<x-artisanpack-avatar placeholder="CT" alt="Chris Taylor" color="error" />
```

### Tailwind Color Palette

Use any Tailwind color with intensity levels:

```blade
<x-artisanpack-avatar placeholder="JD" alt="John Doe" color="blue-500" />
<x-artisanpack-avatar placeholder="SM" alt="Sarah Miller" color="purple-600" />
<x-artisanpack-avatar placeholder="AB" alt="Alex Brown" color="green-400" />
<x-artisanpack-avatar placeholder="LJ" alt="Lisa Johnson" color="red-500" />
<x-artisanpack-avatar placeholder="MW" alt="Mike Wilson" color="yellow-300" />
```

### Custom Hex Colors

Use custom hex color codes:

```blade
<x-artisanpack-avatar placeholder="JD" alt="John Doe" color="#ff6b6b" />
<x-artisanpack-avatar placeholder="SM" alt="Sarah Miller" color="#4ecdc4" />
<x-artisanpack-avatar placeholder="AB" alt="Alex Brown" color="#ffe66d" />
```

### Color Adjustments

Fine-tune the background appearance:

```blade
<!-- Lighter background -->
<x-artisanpack-avatar placeholder="JD" alt="John Doe" color="blue-500" color-adjustment="lighter" />

<!-- Darker background -->
<x-artisanpack-avatar placeholder="SM" alt="Sarah Miller" color="blue-500" color-adjustment="darker" />

<!-- Subtle background -->
<x-artisanpack-avatar placeholder="AB" alt="Alex Brown" color="blue-500" color-adjustment="subtle" />
```

## With Title and Subtitle

```blade
<x-artisanpack-avatar 
    image="/path/to/image.jpg" 
    alt="John Doe"
    title="John Doe"
    subtitle="Software Engineer"
/>
```

## With Slot Content

```blade
<x-artisanpack-avatar image="/path/to/image.jpg" alt="John Doe">
    <x-slot:title>
        <div class="flex items-center gap-2">
            <span>John Doe</span>
            <x-artisanpack-badge value="Pro" class="badge-primary badge-sm" />
        </div>
    </x-slot:title>
    
    <x-slot:subtitle>
        <div class="flex items-center gap-2">
            <x-artisanpack-icon name="o-envelope" class="w-3 h-3" />
            <span>john.doe@example.com</span>
        </div>
    </x-slot:subtitle>
</x-artisanpack-avatar>
```

## Custom Size

```blade
<x-artisanpack-avatar 
    image="/path/to/image.jpg" 
    alt="John Doe"
    class="w-12 rounded-full"
/>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the avatar |
| image | string | '' | URL of the avatar image |
| alt | string | '' | Alternative text for the avatar image |
| placeholder | string | '' | Text to display when no image is provided |
| title | string | null | Title text displayed beside the avatar |
| subtitle | string | null | Subtitle text displayed beside the avatar |
| color | string | null | Color variant, Tailwind color (e.g., 'red-500'), or hex code (e.g., '#ff0000') |
| color-adjustment | string | null | Background adjustment: 'lighter', 'darker', 'transparent', or 'subtle' |

## Slots

| Name | Description |
|------|-------------|
| title | Custom content for the title area |
| subtitle | Custom content for the subtitle area |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. By default, the avatar image is displayed with a size of `w-7` and a `rounded-full` shape. You can override these by passing your own classes to the component.

## Accessibility

The Avatar component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The Avatar component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `alt` | string | **Required** - Describes the person or entity in the avatar |
| `role` | string | Set to "img" when using placeholder text |
| `aria-label` | string | Provides accessible label for the entire avatar component |

### Semantic HTML

- Uses native `<img>` element for avatar images with proper `alt` text
- Placeholder avatars use semantic text with appropriate ARIA roles
- Title and subtitle use proper heading hierarchy when needed

### Screen Reader Behavior

- **With image**: Announces the alt text (e.g., "John Doe, profile picture")
- **With placeholder**: Announces the initials and person's name from alt text
- **With title/subtitle**: Announces all text content in logical order
- Decorative avatars should have `alt=""` and `aria-hidden="true"`

### Example: Accessible Avatar with Image

```blade
<x-artisanpack-avatar
    image="/path/to/john-doe.jpg"
    alt="John Doe profile picture"
    title="John Doe"
    subtitle="Software Engineer"
/>
```

### Example: Accessible Placeholder Avatar

```blade
<x-artisanpack-avatar
    placeholder="JD"
    alt="John Doe"
    color="primary"
/>
```

### Example: Decorative Avatar

```blade
<!-- When avatar is purely decorative and name appears elsewhere -->
<x-artisanpack-avatar
    image="/path/to/user.jpg"
    alt=""
    aria-hidden="true"
/>
<span>John Doe</span>
```

### Color Contrast

All avatar placeholder backgrounds meet WCAG AA color contrast requirements:
- Placeholder text: 4.5:1 minimum contrast ratio with background
- Works properly in high contrast mode

### Best Practices

1. **Always provide alt text**: Describe who the person is, not what they look like
2. **Use descriptive names**: "John Doe profile picture" not just "avatar"
3. **Handle missing images**: Provide meaningful placeholder text with initials
4. **Decorative use**: Use empty alt (`alt=""`) only when name appears adjacent to avatar
5. **Color accessibility**: Don't rely solely on color to convey information

### Common Accessibility Issues to Avoid

❌ **Don't**: Use generic or missing alt text
```blade
<x-artisanpack-avatar image="/user.jpg" alt="user" />
<x-artisanpack-avatar image="/user.jpg" />
```

✅ **Do**: Provide descriptive alt text
```blade
<x-artisanpack-avatar image="/user.jpg" alt="John Doe profile picture" />
```

❌ **Don't**: Use images without considering failures
```blade
<x-artisanpack-avatar image="/user.jpg" alt="John Doe" />
<!-- No fallback if image fails to load -->
```

✅ **Do**: Provide fallback placeholder
```blade
<x-artisanpack-avatar
    image="/user.jpg"
    alt="John Doe"
    placeholder="JD"
/>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter AvatarAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Images](https://www.w3.org/WAI/WCAG21/quickref/#images-of-text)
- [WebAIM Alternative Text](https://webaim.org/techniques/alttext/)
- [Accessibility Guidelines](../accessibility/guidelines.md)
