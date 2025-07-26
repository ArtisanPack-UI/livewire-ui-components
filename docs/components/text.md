# Text Component

The Text component is used to display paragraphs of text with customizable styling options. It provides a consistent way to style text content throughout your application.

## Basic Usage

```php
<x-artisanpack-text>
    This is a paragraph of text content that can be styled consistently.
</x-artisanpack-text>
```

## Examples

### Default Text

```php
<x-artisanpack-text>
    This is a standard paragraph of text with default styling. It includes a bottom margin
    to provide proper spacing between paragraphs.
</x-artisanpack-text>
```

### Text Sizes

```php
<x-artisanpack-text size="text-xs">Extra Small Text</x-artisanpack-text>
<x-artisanpack-text size="text-sm">Small Text</x-artisanpack-text>
<x-artisanpack-text>Default Size Text (text-base)</x-artisanpack-text>
<x-artisanpack-text size="text-lg">Large Text</x-artisanpack-text>
<x-artisanpack-text size="text-xl">Extra Large Text</x-artisanpack-text>
```

### Lead Paragraph

```php
<x-artisanpack-text lead>
    This is a lead paragraph that stands out more than regular text. It's typically used
    for introductory text at the beginning of a section or article.
</x-artisanpack-text>
```

### Font Weights

```php
<x-artisanpack-text>Default Weight Text (font-normal)</x-artisanpack-text>
<x-artisanpack-text semibold>Semibold Text</x-artisanpack-text>
<x-artisanpack-text bold>Bold Text</x-artisanpack-text>
```

### Text Colors

```php
<x-artisanpack-text>Default Color Text</x-artisanpack-text>
<x-artisanpack-text color="text-primary">Primary Color Text</x-artisanpack-text>
<x-artisanpack-text color="text-secondary">Secondary Color Text</x-artisanpack-text>
<x-artisanpack-text muted>Muted Text</x-artisanpack-text>
```

### Alignment

```php
<x-artisanpack-text>Left-aligned Text (Default)</x-artisanpack-text>
<x-artisanpack-text center>Centered Text</x-artisanpack-text>
```

### Prose Styling

```php
<x-artisanpack-text prose>
    This text will use Tailwind's prose styling for rich text content. It's useful for
    content that includes multiple paragraphs, lists, and other rich text elements.
    
    The prose styling provides good typography defaults for content-heavy text.
</x-artisanpack-text>
```

### With ID

```php
<x-artisanpack-text id="paragraph-1">Text with ID for referencing</x-artisanpack-text>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the text element |
| `size` | string | `null` | Custom size class. Defaults to text-base |
| `color` | string | `null` | Text color class |
| `semibold` | boolean | `false` | Whether to use semibold font weight |
| `bold` | boolean | `false` | Whether to use bold font weight |
| `center` | boolean | `false` | Whether to center align the text |
| `muted` | boolean | `false` | Whether to use muted text color |
| `lead` | boolean | `false` | Whether to style as lead paragraph (larger, more prominent) |
| `prose` | boolean | `false` | Whether to apply prose styling for rich text content |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The text content |

## Styling

The Text component uses Tailwind CSS classes for styling. By default, it renders as a paragraph element with:

- Font size: text-base (or text-lg when `lead` is true)
- Font weight: font-normal (or font-medium when `lead` is true)
- Line height: leading-relaxed
- Text color: text-base-content (or text-base-content/70 when `muted` is true)
- Margin bottom: mb-4 (unless overridden with a custom class)

You can customize the appearance by:

1. Using the provided props (`size`, `color`, `semibold`, `bold`, etc.)
2. Adding custom classes via the `class` attribute
3. Using the `prose` prop for rich text content

### Custom Styling Example

```php
<x-artisanpack-text class="italic text-blue-600 tracking-wide">
    Custom styled text with italic blue text and wider letter spacing.
</x-artisanpack-text>
```

## Accessibility

The Text component follows accessibility best practices:

- Renders as a semantic paragraph element
- Maintains appropriate contrast ratios for text
- Supports ID attributes for document structure

## Related Components

- [Heading](heading.md) - For main headings
- [Subheading](subheading.md) - For secondary headings or subtitles
- [Link](link.md) - For hyperlinks
