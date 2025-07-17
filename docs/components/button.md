# Button Component

The Button component is a versatile interactive element used for triggering actions, submitting forms, and navigating between pages.

## Basic Usage

```php
<x-artisanpack-button>
    Click Me
</x-artisanpack-button>
```

## Examples

### Button Colors

```php
<x-artisanpack-button color="primary">Primary</x-artisanpack-button>
<x-artisanpack-button color="secondary">Secondary</x-artisanpack-button>
<x-artisanpack-button color="accent">Accent</x-artisanpack-button>
<x-artisanpack-button color="info">Info</x-artisanpack-button>
<x-artisanpack-button color="success">Success</x-artisanpack-button>
<x-artisanpack-button color="warning">Warning</x-artisanpack-button>
<x-artisanpack-button color="error">Error</x-artisanpack-button>
```

### Button Sizes

```php
<x-artisanpack-button size="xs">Extra Small</x-artisanpack-button>
<x-artisanpack-button size="sm">Small</x-artisanpack-button>
<x-artisanpack-button size="md">Medium</x-artisanpack-button>
<x-artisanpack-button size="lg">Large</x-artisanpack-button>
<x-artisanpack-button size="xl">Extra Large</x-artisanpack-button>
```

### Button Variants

```php
<!-- Default (Filled) Button -->
<x-artisanpack-button>Default</x-artisanpack-button>

<!-- Outline Button -->
<x-artisanpack-button outline>Outline</x-artisanpack-button>

<!-- Ghost Button -->
<x-artisanpack-button ghost>Ghost</x-artisanpack-button>

<!-- Link Button -->
<x-artisanpack-button link>Link</x-artisanpack-button>
```

### Button with Icon

```php
<x-artisanpack-button>
    <x-artisanpack-icon name="heroicon-o-plus" class="w-5 h-5 mr-2" />
    Add Item
</x-artisanpack-button>

<!-- Icon Only Button -->
<x-artisanpack-button circle>
    <x-artisanpack-icon name="heroicon-o-trash" class="w-5 h-5" />
</x-artisanpack-button>
```

### Button States

```php
<!-- Disabled Button -->
<x-artisanpack-button disabled>Disabled</x-artisanpack-button>

<!-- Loading Button -->
<x-artisanpack-button loading>Loading</x-artisanpack-button>

<!-- Active Button -->
<x-artisanpack-button active>Active</x-artisanpack-button>
```

### Button as Link

```php
<x-artisanpack-button href="https://example.com">
    Visit Website
</x-artisanpack-button>

<!-- Link with Target -->
<x-artisanpack-button href="https://example.com" target="_blank">
    Open in New Tab
</x-artisanpack-button>
```

### Button with Events

```php
<x-artisanpack-button @click="alert('Button clicked!')">
    Click Me
</x-artisanpack-button>

<!-- Livewire Event -->
<x-artisanpack-button wire:click="save">
    Save
</x-artisanpack-button>
```

### Form Submit Button

```php
<form action="/submit" method="POST">
    @csrf
    <!-- Other form fields -->
    
    <x-artisanpack-button type="submit" color="primary">
        Submit Form
    </x-artisanpack-button>
</form>
```

### Block Button (Full Width)

```php
<x-artisanpack-button block>
    Full Width Button
</x-artisanpack-button>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | string | `'button'` | HTML button type (`button`, `submit`, `reset`) |
| `color` | string | `'primary'` | Button color (`primary`, `secondary`, `accent`, `info`, `success`, `warning`, `error`, `neutral`) |
| `size` | string | `'md'` | Button size (`xs`, `sm`, `md`, `lg`, `xl`) |
| `outline` | boolean | `false` | Whether to display as an outline button |
| `ghost` | boolean | `false` | Whether to display as a ghost button (transparent background) |
| `link` | boolean | `false` | Whether to display as a link button (no background or border) |
| `circle` | boolean | `false` | Whether to display as a circle button (equal width and height) |
| `square` | boolean | `false` | Whether to display as a square button (equal width and height but with small border radius) |
| `block` | boolean | `false` | Whether the button should take up the full width of its container |
| `disabled` | boolean | `false` | Whether the button is disabled |
| `loading` | boolean | `false` | Whether to show a loading indicator |
| `active` | boolean | `false` | Whether the button is in an active state |
| `href` | string | `null` | URL to navigate to when clicked (renders as an `<a>` tag) |
| `target` | string | `null` | Target attribute for the link (only used when `href` is set) |
| `rel` | string | `null` | Rel attribute for the link (only used when `href` is set) |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The button content |

## Events

The Button component supports all standard HTML button events:

- `click`
- `focus`
- `blur`
- `mouseenter`
- `mouseleave`

It also supports all Livewire events:

- `wire:click`
- `wire:click.prevent`
- `wire:click.stop`
- etc.

## Styling

The Button component uses DaisyUI's button component under the hood, which provides a wide range of styling options. You can customize the appearance of buttons by:

1. Using the provided props (`color`, `size`, `outline`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-button class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold">
    Custom Gradient Button
</x-artisanpack-button>
```

## Accessibility

The Button component follows accessibility best practices:

- Uses the appropriate HTML element (`<button>` or `<a>`) based on usage
- Includes proper ARIA attributes when in loading or disabled states
- Maintains focus styles for keyboard navigation
- Ensures adequate color contrast for all button variants

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Text input field
- [Icon](icon.md) - SVG icon display
