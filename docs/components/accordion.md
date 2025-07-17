# Accordion Component

The Accordion component is a layout element that displays collapsible content panels for presenting information in a limited space. It allows users to expand and collapse sections of content, making it ideal for FAQs, product details, or any content that benefits from progressive disclosure.

## Basic Usage

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item title="Section 1">
        Content for section 1
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        Content for section 2
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 3">
        Content for section 3
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

## Examples

### Simple Accordion

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item title="What is ArtisanPack UI?">
        ArtisanPack UI is a collection of Livewire components for Laravel applications.
        It provides a set of pre-built, customizable UI components that integrate seamlessly
        with Laravel, Livewire, and Tailwind CSS.
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="How do I install it?">
        You can install ArtisanPack UI using Composer:
        <pre>composer require artisanpack-ui/livewire-ui-components</pre>
        Then run the installer:
        <pre>php artisan livewire-ui-components:install</pre>
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Is it free to use?">
        Yes, ArtisanPack UI is open-source and free to use under the MIT license.
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Default Open Item

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item title="Section 1" open>
        This section is open by default.
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        This section is closed by default.
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Custom Icons

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item 
        title="Section 1" 
        icon="heroicon-o-information-circle"
    >
        Content with custom icon
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item 
        title="Section 2" 
        icon="heroicon-o-question-mark-circle"
    >
        Content with custom icon
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Custom Chevron Icons

```php
<x-artisanpack-accordion 
    chevron-open-icon="heroicon-o-chevron-up" 
    chevron-closed-icon="heroicon-o-chevron-down"
>
    <x-artisanpack-accordion.item title="Section 1">
        Content with custom chevron icons
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        Content with custom chevron icons
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Custom Title Component

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item>
        <x-slot:title>
            <div class="flex items-center">
                <x-artisanpack-icon name="heroicon-o-star" class="w-5 h-5 mr-2" />
                <span class="font-bold">Featured Section</span>
            </div>
        </x-slot:title>
        
        Content with custom title component
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Multiple Open Items

```php
<x-artisanpack-accordion :multiple="true">
    <x-artisanpack-accordion.item title="Section 1" open>
        This section can be open at the same time as other sections.
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2" open>
        This section can also be open at the same time.
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 3">
        This section is closed by default.
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Custom Colors

```php
<x-artisanpack-accordion>
    <x-artisanpack-accordion.item 
        title="Primary Section" 
        color="primary"
    >
        Content with primary color
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item 
        title="Secondary Section" 
        color="secondary"
    >
        Content with secondary color
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item 
        title="Accent Section" 
        color="accent"
    >
        Content with accent color
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Bordered Style

```php
<x-artisanpack-accordion bordered>
    <x-artisanpack-accordion.item title="Section 1">
        Content with bordered style
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        Content with bordered style
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Arrow Style

```php
<x-artisanpack-accordion arrow>
    <x-artisanpack-accordion.item title="Section 1">
        Content with arrow style
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        Content with arrow style
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Join Style

```php
<x-artisanpack-accordion join>
    <x-artisanpack-accordion.item title="Section 1">
        Content with join style
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2">
        Content with join style
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Custom Classes

```php
<x-artisanpack-accordion class="border-2 border-primary rounded-lg">
    <x-artisanpack-accordion.item 
        title="Section 1" 
        title-class="font-bold text-primary"
        content-class="bg-base-200 p-4"
    >
        Content with custom classes
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

### Accordion with Livewire Integration

```php
<x-artisanpack-accordion wire:model="openSections">
    <x-artisanpack-accordion.item title="Section 1" id="section1">
        Content controlled by Livewire
    </x-artisanpack-accordion.item>
    
    <x-artisanpack-accordion.item title="Section 2" id="section2">
        Content controlled by Livewire
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

## Props

### Accordion Component Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `multiple` | boolean | `false` | Whether multiple items can be open simultaneously |
| `bordered` | boolean | `false` | Whether to display a border around the accordion |
| `arrow` | boolean | `false` | Whether to use the arrow style for the accordion |
| `join` | boolean | `false` | Whether to join the accordion items together |
| `chevron-open-icon` | string | `'heroicon-o-chevron-up'` | The icon to display when an item is open |
| `chevron-closed-icon` | string | `'heroicon-o-chevron-down'` | The icon to display when an item is closed |

### Accordion Item Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | string | `null` | The title of the accordion item |
| `id` | string | `null` | The ID of the accordion item (auto-generated if not provided) |
| `open` | boolean | `false` | Whether the accordion item is open by default |
| `icon` | string | `null` | The icon to display next to the title |
| `color` | string | `null` | The color of the accordion item (`primary`, `secondary`, `accent`, etc.) |
| `title-class` | string | `null` | Additional classes for the title element |
| `content-class` | string | `null` | Additional classes for the content element |
| `disabled` | boolean | `false` | Whether the accordion item is disabled |

## Slots

### Accordion Component Slots

| Slot | Description |
|------|-------------|
| `default` | The accordion items |

### Accordion Item Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the accordion item |
| `title` | Custom title content (overrides the `title` prop) |
| `icon` | Custom icon content (overrides the `icon` prop) |

## Events

The Accordion component supports the following events:

- `open` - Triggered when an accordion item is opened
- `close` - Triggered when an accordion item is closed

It also supports Livewire model binding for tracking open items:

- `wire:model` - Binds the open items to a Livewire property

## Styling

The Accordion component uses DaisyUI's collapse component under the hood, which provides a consistent styling with other components. You can customize the appearance by:

1. Using the provided props (`bordered`, `arrow`, `join`, etc.)
2. Adding custom classes via the `class`, `title-class`, and `content-class` attributes
3. Using the `color` prop to change the color scheme

### Custom Styling Example

```php
<x-artisanpack-accordion class="max-w-md mx-auto">
    <x-artisanpack-accordion.item 
        title="Custom Styled Item" 
        title-class="font-bold text-primary text-lg"
        content-class="bg-base-200 p-4 rounded-b-lg"
    >
        Content with custom styling
    </x-artisanpack-accordion.item>
</x-artisanpack-accordion>
```

## Accessibility

The Accordion component follows accessibility best practices:

- Uses appropriate ARIA attributes for accordion functionality
- Supports keyboard navigation (Tab to focus, Enter/Space to toggle)
- Maintains focus management
- Ensures adequate color contrast
- Provides clear visual indication of the current state

## Related Components

- [Collapse](collapse.md) - Simple collapsible content container
- [Tabs](tabs.md) - Tabbed interface for organizing content
- [Card](card.md) - Content container with optional header and footer
