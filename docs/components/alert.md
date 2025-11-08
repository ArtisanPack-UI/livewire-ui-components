---
title: Alert Component
---

# Alert Component

The Alert component displays important messages or notifications to users in a prominent way. Unlike toasts, alerts are static elements that remain on the page until dismissed by the user or programmatically.

## Basic Usage

```php
<x-artisanpack-alert>
    This is a simple alert message.
</x-artisanpack-alert>
```

## Examples

### Color System

#### Predefined Variants

Use predefined color variants for consistent theming:

```php
<x-artisanpack-alert 
    title="Info Alert" 
    description="This is an informational message."
    color="info"
>
</x-artisanpack-alert>

<x-artisanpack-alert 
    title="Success Alert" 
    description="Operation completed successfully."
    color="success"
>
</x-artisanpack-alert>

<x-artisanpack-alert 
    title="Warning Alert" 
    description="Please review this warning."
    color="warning"
>
</x-artisanpack-alert>

<x-artisanpack-alert 
    title="Error Alert" 
    description="An error has occurred."
    color="error"
>
</x-artisanpack-alert>
```

#### Tailwind Color Palette

Use any Tailwind color with intensity levels:

```php
<x-artisanpack-alert 
    title="Custom Blue" 
    description="Using Tailwind blue-500 color."
    color="blue-500"
>
</x-artisanpack-alert>

<x-artisanpack-alert 
    title="Custom Purple" 
    description="Using Tailwind purple-600 color."
    color="purple-600"
>
</x-artisanpack-alert>
```

#### Custom Hex Colors

Use custom hex color codes:

```php
<x-artisanpack-alert 
    title="Brand Alert" 
    description="Using custom brand colors."
    color="#4ecdc4"
>
</x-artisanpack-alert>
```

#### Color Adjustments

Fine-tune the background appearance:

```php
<x-artisanpack-alert 
    title="Subtle Alert" 
    description="Very light background."
    color="blue-500" 
    color-adjustment="subtle"
>
</x-artisanpack-alert>

<x-artisanpack-alert 
    title="Transparent Alert" 
    description="Transparent background with colored border."
    color="green-500" 
    color-adjustment="transparent"
>
</x-artisanpack-alert>
```

### Alert with Title and Description

```php
<x-artisanpack-alert 
    title="Update Available" 
    description="A new version of the application is available."
    color="info"
>
</x-artisanpack-alert>
```

### Alert with Icon

```php
<x-artisanpack-alert 
    title="Success" 
    description="Your changes have been saved."
    icon="o-check-circle" 
    color="success"
>
</x-artisanpack-alert>
```

### Dismissible Alert

```php
<x-artisanpack-alert 
    title="Warning" 
    description="This action cannot be undone."
    icon="o-exclamation-triangle" 
    color="warning"
    dismissible
>
</x-artisanpack-alert>
```

### Alert with Shadow

```php
<x-artisanpack-alert 
    title="Error" 
    description="There was a problem processing your request."
    icon="o-x-circle" 
    color="error"
    shadow
>
</x-artisanpack-alert>
```

### Alert with Actions

```php
<x-artisanpack-alert 
    title="Confirm Action" 
    description="Are you sure you want to delete this item?"
    icon="o-question-mark-circle" 
    class="alert-warning"
>
    <x-slot:actions>
        <x-artisanpack-button color="error">Delete</x-artisanpack-button>
        <x-artisanpack-button color="ghost">Cancel</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-alert>
```

### Alert with Custom Content

```php
<x-artisanpack-alert class="alert-info">
    <p>This alert contains <strong>custom HTML content</strong> instead of using the title/description props.</p>
    <p class="mt-2">You can include any HTML elements here.</p>
</x-artisanpack-alert>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the alert element |
| `title` | string\|null | `null` | The title of the alert, displayed in bold |
| `icon` | string\|null | `null` | The icon displayed at the beginning of the alert |
| `description` | string\|null | `null` | A short description displayed under the title |
| `color` | string\|null | `null` | Color variant, Tailwind color (e.g., 'red-500'), or hex code (e.g., '#ff0000') |
| `color-adjustment` | string\|null | `null` | Background adjustment: 'lighter', 'darker', 'transparent', or 'subtle' |
| `shadow` | boolean | `false` | Whether to apply a shadow effect to the alert |
| `dismissible` | boolean | `false` | Whether the alert can be dismissed by the user |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the alert (used when no title is provided) |
| `actions` | Slot for actionable elements like buttons or links |

## Styling

The Alert component uses DaisyUI's alert component under the hood, which provides a wide range of styling options. You can customize the appearance of alerts by adding DaisyUI alert classes:

- `alert-info` - Blue info alert
- `alert-success` - Green success alert
- `alert-warning` - Yellow warning alert
- `alert-error` - Red error alert

### Custom Styling Example

```php
<x-artisanpack-alert 
    title="Custom Alert" 
    class="bg-purple-100 text-purple-800 border border-purple-200"
    icon="o-sparkles"
>
</x-artisanpack-alert>
```

## Accessibility

The Alert component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The Alert component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `role` | string | Set to "alert" or "status" depending on urgency |
| `aria-live` | string | Automatically set to "polite" or "assertive" |
| `aria-atomic` | boolean | Set to "true" for complete message announcement |
| `aria-label` | string | Provides accessible label for the alert |

### Semantic HTML

- Uses semantic HTML structure with proper heading hierarchy
- Icon elements are decorative and hidden from screen readers with `aria-hidden="true"`
- Dismissible alerts use proper button elements with accessible labels

### Screen Reader Behavior

- Alert messages are announced to screen readers automatically
- Success/info alerts use `aria-live="polite"` (announced when user is idle)
- Warning/error alerts use `aria-live="assertive"` (announced immediately)
- Alert title and description are both announced
- Dismiss button announces as "Close alert" or "Dismiss"

### Keyboard Support

| Key | Action |
|-----|--------|
| Tab | Move focus to dismiss button (if dismissible) |
| Enter / Space | Dismiss the alert (when dismiss button is focused) |
| Escape | Dismiss the alert (if dismissible) |

### Color Contrast

All alert variants meet WCAG AA color contrast requirements:
- Info alerts: 4.5:1 contrast ratio
- Success alerts: 4.5:1 contrast ratio
- Warning alerts: 4.5:1 contrast ratio
- Error alerts: 4.5:1 contrast ratio
- Text is never conveyed by color alone

### Example: Accessible Alert

```php
<x-artisanpack-alert
    title="Success"
    description="Your changes have been saved successfully."
    icon="o-check-circle"
    color="success"
    role="status"
    aria-live="polite"
    dismissible
>
</x-artisanpack-alert>
```

### Best Practices

1. **Use appropriate severity**: Match the color/icon to the message importance
2. **Provide clear messages**: Write concise, actionable alert text
3. **Include helpful icons**: Visual indicators support understanding
4. **Don't rely on color alone**: Always include text and/or icons
5. **Use role appropriately**: Use "alert" for urgent messages, "status" for less critical updates

### Common Accessibility Issues to Avoid

❌ **Don't**: Use alerts without clear messaging
```php
<x-artisanpack-alert color="error">Error!</x-artisanpack-alert>
```

✅ **Do**: Provide descriptive messages
```php
<x-artisanpack-alert
    title="Error"
    description="Failed to save your changes. Please try again."
    color="error"
>
</x-artisanpack-alert>
```

❌ **Don't**: Convey information through color only
```php
<x-artisanpack-alert color="success">Saved</x-artisanpack-alert>
```

✅ **Do**: Include text and icons
```php
<x-artisanpack-alert
    title="Success"
    description="Your changes have been saved."
    icon="o-check-circle"
    color="success"
>
</x-artisanpack-alert>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter AlertAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Alert Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/alert/)
- [MDN ARIA: alert role](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/alert_role)
- [Accessibility Guidelines](../accessibility/guidelines.md)

## Related Components

- [Toast](toast) - Temporary notifications
- [Modal](modal) - Dialog boxes for important interactions
- [Icon](icon) - SVG icon display