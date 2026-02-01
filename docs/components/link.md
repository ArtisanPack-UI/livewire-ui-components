---
title: Link Component
---

# Link Component

The Link component is used to display hyperlinks with customizable styling options. It provides a consistent way to style links throughout your application with support for icons, tooltips, and external link behavior.

## Basic Usage

```php
<x-artisanpack-link href="https://example.com">
    Visit Example Website
</x-artisanpack-link>
```

## Examples

### Default Link

```php
<x-artisanpack-link href="https://example.com">
    This is a standard link with default styling
</x-artisanpack-link>
```

### External Links

```php
<x-artisanpack-link href="https://example.com" external>
    External Link (opens in new tab)
</x-artisanpack-link>
```

### Links with Icons

```php
<x-artisanpack-link href="https://example.com" icon="heroicon-o-arrow-right">
    Link with Icon Before Text
</x-artisanpack-link>

<x-artisanpack-link href="https://example.com" iconRight="heroicon-o-arrow-right">
    Link with Icon After Text
</x-artisanpack-link>
```

### Underline Options

```php
<x-artisanpack-link href="#" hoverUnderline>
    Underline on Hover (Default)
</x-artisanpack-link>

<x-artisanpack-link href="#" underline>
    Always Underlined
</x-artisanpack-link>

<x-artisanpack-link href="#" :hoverUnderline="false">
    Never Underlined
</x-artisanpack-link>
```

### Custom Colors

```php
<x-artisanpack-link href="#">Default Primary Color Link</x-artisanpack-link>
<x-artisanpack-link href="#" color="text-secondary hover:text-secondary-focus">Secondary Color Link</x-artisanpack-link>
<x-artisanpack-link href="#" color="text-accent hover:text-accent-focus">Accent Color Link</x-artisanpack-link>
```

### With Tooltips

```php
<x-artisanpack-link href="#" tooltip="Click to learn more">
    Link with Tooltip
</x-artisanpack-link>

<x-artisanpack-link href="#" tooltipBottom="Click to learn more">
    Link with Bottom Tooltip
</x-artisanpack-link>
```

### Livewire Navigation

```php
<x-artisanpack-link href="/dashboard">
    Navigate with wire:navigate (Default)
</x-artisanpack-link>

<x-artisanpack-link href="/dashboard" noWireNavigate>
    Navigate without wire:navigate
</x-artisanpack-link>
```

### With ID

```php
<x-artisanpack-link href="#section-1" id="section-1-link">
    Jump to Section 1
</x-artisanpack-link>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the link |
| `href` | string | `null` | The URL the link points to |
| `color` | string | `null` | Text color class (defaults to text-primary hover:text-primary-focus) |
| `underline` | boolean | `false` | Whether to show underline |
| `hoverUnderline` | boolean | `true` | Whether to show underline only on hover |
| `external` | boolean | `false` | Whether the link should open in a new tab |
| `noWireNavigate` | boolean | `false` | Disable wire:navigate for links |
| `icon` | string | `null` | Optional icon to display before the text |
| `iconRight` | string | `null` | Optional icon to display after the text |
| `tooltip` | string | `null` | Optional tooltip text |
| `tooltipLeft` | string | `null` | Optional tooltip text (left position) |
| `tooltipRight` | string | `null` | Optional tooltip text (right position) |
| `tooltipBottom` | string | `null` | Optional tooltip text (bottom position) |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The link text content |

## Styling

The Link component uses Tailwind CSS classes for styling. By default, it renders as an anchor element with:

- Text color: text-primary hover:text-primary-focus
- Underline: no-underline hover:underline (when hoverUnderline is true)
- Display: inline-flex items-center gap-1 (for proper icon alignment)

You can customize the appearance by:

1. Using the provided props (`color`, `underline`, `hoverUnderline`, etc.)
2. Adding custom classes via the `class` attribute

### Custom Styling Example

```php
<x-artisanpack-link href="#" class="font-bold italic text-emerald-600 hover:text-emerald-800">
    Custom styled link
</x-artisanpack-link>
```

## Accessibility

The Link component follows accessibility best practices:

- Uses the semantic anchor element
- Adds appropriate attributes for external links (target="_blank" and rel="noopener noreferrer")
- Supports tooltips for additional context
- Maintains focus states for keyboard navigation

## Related Components

- [Button](Button) - For action buttons
- [Text](Text) - For paragraph text
- [Heading](Heading) - For headings
- [Subheading](Subheading) - For subheadings
