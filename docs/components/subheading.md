# Subheading Component

The Subheading component is used to display secondary headings or subtitles with customizable styling options. It's designed to complement the Heading component with appropriate styling for subheadings.

## Basic Usage

```php
<x-artisanpack-subheading>
    This is a Subheading
</x-artisanpack-subheading>
```

## Examples

### Default Subheading

```php
<x-artisanpack-heading>Main Heading</x-artisanpack-heading>
<x-artisanpack-subheading>Supporting subheading that provides additional context</x-artisanpack-subheading>
```

### Custom Sizes

```php
<x-artisanpack-subheading size="text-xl">Larger Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading size="text-sm">Small Subheading</x-artisanpack-subheading>
```

### Font Weights

```php
<x-artisanpack-subheading>Default Medium Weight Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading semibold>Semibold Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading bold>Bold Subheading</x-artisanpack-subheading>
```

### Text Colors

```php
<x-artisanpack-subheading>Default Color Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading color="text-primary">Primary Color Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading color="text-secondary">Secondary Color Subheading</x-artisanpack-subheading>
<x-artisanpack-subheading muted>Muted Subheading</x-artisanpack-subheading>
```

### Alignment

```php
<x-artisanpack-subheading>Left-aligned Subheading (Default)</x-artisanpack-subheading>
<x-artisanpack-subheading center>Centered Subheading</x-artisanpack-subheading>
```

### With ID

```php
<x-artisanpack-subheading id="subtitle-1">Subtitle with ID</x-artisanpack-subheading>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the subheading |
| `size` | string | `null` | Custom size class. Defaults to text-lg |
| `color` | string | `null` | Text color class |
| `semibold` | boolean | `false` | Whether to use semibold font weight |
| `bold` | boolean | `false` | Whether to use bold font weight |
| `center` | boolean | `false` | Whether to center align the subheading |
| `muted` | boolean | `false` | Whether to use muted text color |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The subheading content |

## Styling

The Subheading component uses Tailwind CSS classes for styling. By default, it renders as a paragraph element with:

- Font size: text-lg
- Font weight: font-medium
- Line height: leading-relaxed
- Text color: text-base-content (or text-base-content/70 when muted)

You can customize the appearance by:

1. Using the provided props (`size`, `color`, `semibold`, `bold`, etc.)
2. Adding custom classes via the `class` attribute

### Custom Styling Example

```php
<x-artisanpack-subheading class="italic text-indigo-600 tracking-wide">
    Custom Styled Subheading
</x-artisanpack-subheading>
```

## Accessibility

The Subheading component follows accessibility best practices:

- Renders as a semantic paragraph element
- Maintains appropriate contrast ratios for text
- Supports ID attributes for document structure

## Related Components

- [Heading](heading.md) - For main headings
- [Text](text.md) - For paragraph text
- [Link](link.md) - For hyperlinks
