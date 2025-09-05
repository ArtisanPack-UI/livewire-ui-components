---
title: Heading Component
---

# Heading Component

The Heading component is used to display headings with customizable styling options. It supports all heading levels (h1-h6) with appropriate default styling for each level.

## Basic Usage

```php
<x-artisanpack-heading>
    This is a Heading
</x-artisanpack-heading>
```

## Examples

### Heading Levels

```php
<x-artisanpack-heading level="1">Heading Level 1 (h1)</x-artisanpack-heading>
<x-artisanpack-heading level="2">Heading Level 2 (h2)</x-artisanpack-heading>
<x-artisanpack-heading level="3">Heading Level 3 (h3)</x-artisanpack-heading>
<x-artisanpack-heading level="4">Heading Level 4 (h4)</x-artisanpack-heading>
<x-artisanpack-heading level="5">Heading Level 5 (h5)</x-artisanpack-heading>
<x-artisanpack-heading level="6">Heading Level 6 (h6)</x-artisanpack-heading>
```

### Custom Sizes

```php
<x-artisanpack-heading size="text-5xl">Extra Large Heading</x-artisanpack-heading>
<x-artisanpack-heading size="text-sm">Small Heading</x-artisanpack-heading>
```

### Font Weights

```php
<x-artisanpack-heading>Default Bold Heading</x-artisanpack-heading>
<x-artisanpack-heading semibold>Semibold Heading</x-artisanpack-heading>
<x-artisanpack-heading extrabold>Extra Bold Heading</x-artisanpack-heading>
```

### Text Colors

```php
<x-artisanpack-heading>Default Color Heading</x-artisanpack-heading>
<x-artisanpack-heading color="text-primary">Primary Color Heading</x-artisanpack-heading>
<x-artisanpack-heading color="text-secondary">Secondary Color Heading</x-artisanpack-heading>
<x-artisanpack-heading color="text-accent">Accent Color Heading</x-artisanpack-heading>
```

### Alignment

```php
<x-artisanpack-heading>Left-aligned Heading (Default)</x-artisanpack-heading>
<x-artisanpack-heading center>Centered Heading</x-artisanpack-heading>
```

### With ID (for anchor links)

```php
<x-artisanpack-heading id="section-1">Section 1</x-artisanpack-heading>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the heading |
| `level` | string | `'1'` | Heading level (1-6) |
| `size` | string | `null` | Custom size class. If not provided, defaults based on level |
| `color` | string | `null` | Text color class |
| `semibold` | boolean | `false` | Whether to use semibold font weight |
| `bold` | boolean | `false` | Whether to use bold font weight (default is true) |
| `extrabold` | boolean | `false` | Whether to use extra bold font weight |
| `center` | boolean | `false` | Whether to center align the heading |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The heading content |

## Styling

The Heading component uses Tailwind CSS classes for styling. By default, it applies appropriate text sizes based on the heading level:

- `h1`: text-4xl
- `h2`: text-3xl
- `h3`: text-2xl
- `h4`: text-xl
- `h5`: text-lg
- `h6`: text-base

You can customize the appearance by:

1. Using the provided props (`level`, `size`, `color`, etc.)
2. Adding custom classes via the `class` attribute

### Custom Styling Example

```php
<x-artisanpack-heading class="text-gradient-to-r from-blue-500 to-purple-500 italic">
    Custom Gradient Heading
</x-artisanpack-heading>
```

## Accessibility

The Heading component follows accessibility best practices:

- Uses the appropriate HTML heading element (`<h1>` through `<h6>`) based on the `level` prop
- Maintains proper heading hierarchy for screen readers
- Supports ID attributes for anchor links and document structure

## Related Components

- [Subheading](subheading) - For secondary headings or subtitles
- [Text](text) - For paragraph text
- [Link](link) - For hyperlinks
