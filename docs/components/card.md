# Card Component

The Card component is a versatile container for displaying content in a clean, organized format. It supports headers, footers, and various styling options.

## Basic Usage

```php
<x-artisanpack-card>
    This is a basic card with simple content.
</x-artisanpack-card>
```

## Examples

### Card with Header and Footer

```php
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">Card Title</h3>
    </x-slot:header>
    
    <p>This is the main content of the card.</p>
    
    <x-slot:footer>
        <div class="flex justify-end">
            <x-artisanpack-button>Action</x-artisanpack-button>
        </div>
    </x-slot:footer>
</x-artisanpack-card>
```

### Card with Image

```php
<x-artisanpack-card>
    <img src="https://example.com/image.jpg" alt="Card image" class="w-full h-48 object-cover" />
    
    <div class="p-4">
        <h3 class="text-lg font-bold">Card with Image</h3>
        <p class="mt-2">This card includes an image at the top.</p>
    </div>
</x-artisanpack-card>
```

### Card with Border

```php
<x-artisanpack-card bordered>
    <h3 class="text-lg font-bold">Bordered Card</h3>
    <p class="mt-2">This card has a border around it.</p>
</x-artisanpack-card>
```

### Card with Shadow

```php
<x-artisanpack-card shadow="lg">
    <h3 class="text-lg font-bold">Card with Shadow</h3>
    <p class="mt-2">This card has a large shadow effect.</p>
</x-artisanpack-card>
```

### Compact Card

```php
<x-artisanpack-card compact>
    <h3 class="text-lg font-bold">Compact Card</h3>
    <p class="mt-2">This card has reduced padding for a more compact appearance.</p>
</x-artisanpack-card>
```

### Card with Background Color

```php
<x-artisanpack-card class="bg-primary text-primary-content">
    <h3 class="text-lg font-bold">Colored Card</h3>
    <p class="mt-2">This card has a primary color background.</p>
</x-artisanpack-card>
```

### Side-by-Side Card

```php
<x-artisanpack-card side>
    <img src="https://example.com/image.jpg" alt="Card image" class="w-1/3 object-cover" />
    
    <div class="p-4 w-2/3">
        <h3 class="text-lg font-bold">Side-by-Side Card</h3>
        <p class="mt-2">This card displays the image on the side instead of at the top.</p>
    </div>
</x-artisanpack-card>
```

### Card with Actions in Header

```php
<x-artisanpack-card>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Card with Actions</h3>
            <div>
                <x-artisanpack-button size="sm">Edit</x-artisanpack-button>
                <x-artisanpack-button size="sm" color="error">Delete</x-artisanpack-button>
            </div>
        </div>
    </x-slot:header>
    
    <p>This card has action buttons in the header.</p>
</x-artisanpack-card>
```

### Card with Tabs

```php
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">Card with Tabs</h3>
    </x-slot:header>
    
    <x-artisanpack-tabs>
        <x-artisanpack-tab name="tab1" label="Tab 1" active>
            <div class="p-4">Content for Tab 1</div>
        </x-artisanpack-tab>
        
        <x-artisanpack-tab name="tab2" label="Tab 2">
            <div class="p-4">Content for Tab 2</div>
        </x-artisanpack-tab>
        
        <x-artisanpack-tab name="tab3" label="Tab 3">
            <div class="p-4">Content for Tab 3</div>
        </x-artisanpack-tab>
    </x-artisanpack-tabs>
</x-artisanpack-card>
```

### Card with Loading State

```php
<x-artisanpack-card loading>
    <h3 class="text-lg font-bold">Loading Card</h3>
    <p class="mt-2">This card is in a loading state.</p>
</x-artisanpack-card>
```

### Card Grid

```php
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <x-artisanpack-card>
        <h3 class="text-lg font-bold">Card 1</h3>
        <p class="mt-2">This is the first card in a grid.</p>
    </x-artisanpack-card>
    
    <x-artisanpack-card>
        <h3 class="text-lg font-bold">Card 2</h3>
        <p class="mt-2">This is the second card in a grid.</p>
    </x-artisanpack-card>
    
    <x-artisanpack-card>
        <h3 class="text-lg font-bold">Card 3</h3>
        <p class="mt-2">This is the third card in a grid.</p>
    </x-artisanpack-card>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `bordered` | boolean | `false` | Whether to display a border around the card |
| `compact` | boolean | `false` | Whether to use reduced padding for a more compact appearance |
| `side` | boolean | `false` | Whether to display content side-by-side (horizontal layout) |
| `shadow` | string | `null` | Shadow size (`sm`, `md`, `lg`, `xl`, `2xl`) |
| `loading` | boolean | `false` | Whether to display the card in a loading state |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The main content of the card |
| `header` | Content for the card header |
| `footer` | Content for the card footer |

## Styling

The Card component uses DaisyUI's card component under the hood, which provides a consistent styling with other components. You can customize the appearance of cards by:

1. Using the provided props (`bordered`, `compact`, `shadow`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-card class="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
    <h3 class="text-lg font-bold">Custom Styled Card</h3>
    <p class="mt-2">This card has a custom gradient background.</p>
</x-artisanpack-card>
```

### DaisyUI Variables

You can customize the Card component by modifying these DaisyUI variables in your theme file:

```css
:root {
  --card-p: 1.5rem;        /* Card padding */
  --card-fs: 1rem;         /* Card font size */
  --card-title-fs: 1.25rem; /* Card title font size */
}
```

## Accessibility

The Card component follows accessibility best practices:

- Uses semantic HTML structure
- Maintains proper heading hierarchy
- Ensures adequate color contrast
- Provides appropriate ARIA attributes when in loading state

## Related Components

- [Button](button.md) - Interactive button element
- [Tabs](tabs.md) - Tabbed interface
- [Modal](modal.md) - Modal dialog
- [Collapse](collapse.md) - Collapsible content
