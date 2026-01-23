---
title: Button Component
---

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

#### Predefined Variants

Use predefined color variants for consistent theming:

```php
<x-artisanpack-button color="primary">Primary</x-artisanpack-button>
<x-artisanpack-button color="secondary">Secondary</x-artisanpack-button>
<x-artisanpack-button color="accent">Accent</x-artisanpack-button>
<x-artisanpack-button color="info">Info</x-artisanpack-button>
<x-artisanpack-button color="success">Success</x-artisanpack-button>
<x-artisanpack-button color="warning">Warning</x-artisanpack-button>
<x-artisanpack-button color="error">Error</x-artisanpack-button>
```

#### Tailwind Color Palette

Use any Tailwind color with intensity levels:

```php
<x-artisanpack-button color="red-500">Red 500</x-artisanpack-button>
<x-artisanpack-button color="blue-600">Blue 600</x-artisanpack-button>
<x-artisanpack-button color="green-400">Green 400</x-artisanpack-button>
<x-artisanpack-button color="purple-700">Purple 700</x-artisanpack-button>
<x-artisanpack-button color="yellow-300">Yellow 300</x-artisanpack-button>
```

#### Custom Hex Colors

Use custom hex color codes:

```php
<x-artisanpack-button color="#ff6b6b">Custom Red</x-artisanpack-button>
<x-artisanpack-button color="#4ecdc4">Brand Teal</x-artisanpack-button>
<x-artisanpack-button color="#ffe66d">Special Yellow</x-artisanpack-button>
```

#### Color Adjustments

Fine-tune the background appearance with color adjustments:

```php
<!-- Lighter background -->
<x-artisanpack-button color="blue-500" color-adjustment="lighter">Lighter</x-artisanpack-button>

<!-- Darker background -->
<x-artisanpack-button color="blue-500" color-adjustment="darker">Darker</x-artisanpack-button>

<!-- Transparent background -->
<x-artisanpack-button color="blue-500" color-adjustment="transparent">Transparent</x-artisanpack-button>

<!-- Subtle background -->
<x-artisanpack-button color="blue-500" color-adjustment="subtle">Subtle</x-artisanpack-button>
```

#### Hover States & Color Contrast

The button component automatically generates accessible hover states that maintain proper color contrast:

- **Automatic Hover Colors:** When you hover over a button, the background color automatically darkens for a clear visual feedback
- **Smart Text Color:** The text color automatically adjusts on hover to maintain WCAG AA compliance (4.5:1 contrast ratio)
- **Smooth Transitions:** All color changes use optimized CSS transitions for a polished user experience

```php
<!-- Light background button: text stays dark on hover -->
<x-artisanpack-button color="yellow-300">
    Hover to see contrast maintained
</x-artisanpack-button>

<!-- Dark background button: text stays white on hover -->
<x-artisanpack-button color="blue-700">
    Hover to see contrast maintained
</x-artisanpack-button>
```

This works automatically for all color types:
- **Predefined variants** (primary, secondary, accent, etc.)
- **Tailwind colors** (blue-500, red-600, etc.)
- **Custom hex colors** (#ff6b6b, #4ecdc4, etc.)
- **Color adjustments** (lighter, darker, subtle, transparent)

### Button Sizes

The button component supports five different sizes that control the overall button dimensions:

```php
<x-artisanpack-button size="xs">Extra Small</x-artisanpack-button>
<x-artisanpack-button size="sm">Small</x-artisanpack-button>
<x-artisanpack-button size="md">Medium (Default)</x-artisanpack-button>
<x-artisanpack-button size="lg">Large</x-artisanpack-button>
<x-artisanpack-button size="xl">Extra Large</x-artisanpack-button>
```

**Note:** The `xl` size is mapped to `lg` in DaisyUI. If you need a true extra-large size, you can add custom CSS classes.

You can combine sizes with any color variant:

```php
<x-artisanpack-button size="lg" color="primary">Large Primary</x-artisanpack-button>
<x-artisanpack-button size="sm" color="blue-500">Small Blue</x-artisanpack-button>
<x-artisanpack-button size="xs" color="#ff6b6b">Tiny Custom</x-artisanpack-button>
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

<!-- Active Button -->
<x-artisanpack-button class="btn-active">Active</x-artisanpack-button>
```

### Button with Loading States

The button component provides powerful loading state management with several options. The component uses a CSS-safe approach that ensures loading indicators are hidden by default and only appear during Livewire actions, preventing any flash of unstyled content.

#### Basic Spinner

Show a spinner during Livewire actions using the `spinner` prop:

```php
<!-- Auto-detect wire:click action (use spinner="1") -->
<x-artisanpack-button wire:click="save" spinner="1">
    Save
</x-artisanpack-button>

<!-- Explicit spinner target -->
<x-artisanpack-button wire:click="processData" spinner="processData">
    Process Data
</x-artisanpack-button>
```

#### Custom Loading Text (Recommended)

Replace button content with custom text during loading using the `label` and `loading` props together:

```php
<!-- Recommended: Use label prop with loading for clean loading states -->
<x-artisanpack-button
    wire:click="save"
    spinner="1"
    loading="Saving..."
    label="Save Changes"
/>

<!-- The button displays "Save Changes" normally, and "Saving..." during the action -->
<x-artisanpack-button
    wire:click="importData"
    spinner="1"
    loading="Importing..."
    label="Import Data"
/>
```

This approach is recommended because:
- Uses the component's built-in CSS-safe loading mechanism
- Automatically hides the label and shows loading text during actions
- No need for manual `wire:loading` directives in the slot
- Prevents any flash of loading content on page load

#### Custom Loading Icon

Display a custom icon during loading (replaces the spinner):

```php
<x-artisanpack-button
    wire:click="upload"
    spinner="upload"
    loading="o-arrow-up-tray"
    label="Upload File"
/>
```

The `loading` prop accepts any icon name (o-, s-, fa-, c-, heroicon-, etc.).

#### Spinner Positioning

The spinner appears on the left by default, but moves to the right when using `icon-right`:

```php
<!-- Spinner on the left -->
<x-artisanpack-button wire:click="save" spinner="1" label="Save" />

<!-- Spinner on the right (when using icon-right) -->
<x-artisanpack-button wire:click="save" spinner="1" icon-right="o-arrow-right" label="Next" />
```

#### How Loading States Work

The button component uses a CSS-safe implementation for loading states:

1. **Hidden by Default:** Loading content (spinners, loading text/icons) is hidden using `class="hidden"`
2. **Revealed During Loading:** Livewire's `wire:loading.class.remove="hidden"` removes the hidden class during actions
3. **Content Hiding:** When using `spinner` with `loading`, the label is hidden during loading via `wire:loading.class="hidden"`

This approach ensures:
- No flash of loading content on initial page render
- Works reliably regardless of JavaScript initialization timing
- Compatible with all browsers and Livewire versions

#### Livewire 4 data-loading Support

For Livewire 4 users, the button component also supports the new CSS-based `data-loading` attribute system. This provides an additional CSS variant approach alongside the traditional `wire:loading` directives:

```html
<!-- Elements use both approaches for dual compatibility -->
<span class="hidden data-loading:block" wire:loading.class.remove="hidden">
    Loading...
</span>
```

**CSS classes used:**
- `data-loading:block` - Shows element during loading state
- `data-loading:hidden` - Hides element during loading state

This dual-support approach ensures buttons work optimally on both Livewire 3 and Livewire 4, with Livewire 4 users benefiting from enhanced CSS-based state management.

See the [Livewire 4 Features Migration Guide](../migration/livewire-4-features) for more details.

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
| `color` | string | `null` | Button color - accepts predefined variants, Tailwind colors, or hex codes |
| `variant` | string | `'primary'` | Legacy variant prop (use `color` instead) - Options: `primary`, `secondary`, `accent`, `info`, `success`, `warning`, `error`, `neutral`, `ghost`, `outline` |
| `colorAdjustment` | string | `null` | Background adjustment (`lighter`, `darker`, `transparent`, `subtle`) |
| `size` | string | `'md'` | Button size (`xs`, `sm`, `md`, `lg`, `xl`) - Note: `xl` maps to `lg` |
| `label` | string | `null` | Optional text label for the button |
| `icon` | string | `null` | Icon to display before the label |
| `iconRight` | string | `null` | Icon to display after the label |
| `loading` | string | `null` | Text or icon to display during loading state (auto-detected if icon name) |
| `spinner` | string | `null` | Spinner target for loading states (use "1" to auto-detect wire:click) |
| `link` | string | `null` | URL to convert the button to a link (renders as `<a>` tag) |
| `external` | boolean | `false` | Whether the link should open in a new tab |
| `noWireNavigate` | boolean | `false` | Disable wire:navigate for links |
| `responsive` | boolean | `false` | Whether the button label should be responsive |
| `badge` | string | `null` | Badge text to display |
| `badgeClasses` | string | `null` | CSS classes for the badge |
| `tooltip` | string | `null` | Tooltip text |
| `tooltipLeft` | string | `null` | Tooltip text (left position) |
| `tooltipRight` | string | `null` | Tooltip text (right position) |
| `tooltipBottom` | string | `null` | Tooltip text (bottom position) |

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

The Button component follows accessibility best practices and includes built-in accessibility features:

### Semantic HTML
- Uses the appropriate HTML element (`<button>` or `<a>`) based on usage
- Includes proper ARIA attributes when in loading or disabled states

### Keyboard Navigation
- Maintains focus styles for keyboard navigation
- Supports standard keyboard interactions (Enter, Space)
- Focus states automatically adjust text color for contrast

### Color Contrast (WCAG AA Compliance)
- **Base State:** All button colors automatically calculate contrasting text colors to meet WCAG AA standards (4.5:1 contrast ratio for normal text)
- **Hover State:** When hovering, both background and text colors adjust together to maintain proper contrast
- **Focus State:** Focus indicators maintain the same high contrast standards

The component achieves this through:
- Automatic brightness calculation for all color inputs
- Dynamic text color selection (black or white) based on background luminance
- Hover state generation that recalculates text color for the darkened background

### Example: Color Contrast in Action

```php
<!-- Light background (yellow-300): Uses dark text on base AND hover -->
<x-artisanpack-button color="yellow-300">
    Always Readable
</x-artisanpack-button>

<!-- Medium background (blue-500): Uses white text on base AND hover -->
<x-artisanpack-button color="blue-500">
    Always Readable
</x-artisanpack-button>

<!-- Dark background (gray-800): Uses white text on base AND hover -->
<x-artisanpack-button color="gray-800">
    Always Readable
</x-artisanpack-button>
```

### Performance Optimizations
- Uses `transition-colors` instead of `transition-all` for smoother, more efficient animations
- Leverages CSS custom properties for dynamic color generation
- Minimizes layout shifts during state changes

## Related Components

- [Form](form) - Container for form elements
- [Input](input) - Text input field
- [Icon](icon) - SVG icon display
