---
title: Accordion Component
---

# Accordion Component

The Accordion component is a container that groups multiple collapse components together, allowing only one section to be expanded at a time. It's ideal for organizing content in a space-efficient way, perfect for FAQs, product details, or any content that benefits from progressive disclosure.

## Basic Usage

```php
<x-artisanpack-accordion wire:model="selectedSection">
    <x-artisanpack-collapse name="section1">
        <x-slot:heading>What is ArtisanPack UI?</x-slot:heading>
        <x-slot:content>
            ArtisanPack UI is a collection of Livewire components for Laravel applications.
            It provides a set of pre-built, customizable UI components that integrate seamlessly
            with Laravel, Livewire, and Tailwind CSS.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="section2">
        <x-slot:heading>How do I install it?</x-slot:heading>
        <x-slot:content>
            You can install ArtisanPack UI using Composer:
            <pre>composer require artisanpack-ui/livewire-ui-components</pre>
            Then run the installer:
            <pre>php artisan livewire-ui-components:install</pre>
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="section3">
        <x-slot:heading>Is it free to use?</x-slot:heading>
        <x-slot:content>
            Yes, ArtisanPack UI is open-source and free to use under the MIT license.
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

## Examples

### Simple Accordion with Arrow Icons (Default)

```php
<x-artisanpack-accordion wire:model="accordionModel">
    <x-artisanpack-collapse name="item1">
        <x-slot:heading>Section 1</x-slot:heading>
        <x-slot:content>
            This accordion uses arrow icons by default. The arrows point right when collapsed
            and down when expanded.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="item2">
        <x-slot:heading>Section 2</x-slot:heading>
        <x-slot:content>
            Only one section can be open at a time in an accordion.
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

### Accordion with Plus/Minus Icons

```php
<x-artisanpack-accordion wire:model="plusMinusModel" collapse-plus-minus>
    <x-artisanpack-collapse name="item1">
        <x-slot:heading>Section 1 (Plus/Minus)</x-slot:heading>
        <x-slot:content>
            This accordion uses plus/minus icons. Shows "+" when collapsed and "-" when expanded.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="item2">
        <x-slot:heading>Section 2 (Plus/Minus)</x-slot:heading>
        <x-slot:content>
            The plus/minus icon style is inherited by all collapse items within this accordion.
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

### Accordion with Mixed Content Types

```php
<x-artisanpack-accordion wire:model="mixedModel">
    <x-artisanpack-collapse name="text">
        <x-slot:heading>Text Content</x-slot:heading>
        <x-slot:content>
            <p>This section contains simple text content.</p>
            <p>You can include multiple paragraphs and basic HTML.</p>
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="list">
        <x-slot:heading>List Content</x-slot:heading>
        <x-slot:content>
            <ul class="list-disc list-inside">
                <li>First item</li>
                <li>Second item</li>
                <li>Third item</li>
            </ul>
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="form">
        <x-slot:heading>Interactive Content</x-slot:heading>
        <x-slot:content>
            <div class="space-y-4">
                <x-artisanpack-input label="Name" />
                <x-artisanpack-input label="Email" type="email" />
                <x-artisanpack-button>Submit</x-artisanpack-button>
            </div>
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

### Accordion with Custom Styling

```php
<x-artisanpack-accordion wire:model="styledModel" class="max-w-2xl mx-auto">
    <x-artisanpack-collapse name="styled1">
        <x-slot:heading class="text-lg font-bold text-primary">
            Custom Styled Heading
        </x-slot:heading>
        <x-slot:content class="bg-base-200 p-4 rounded">
            This content has custom styling applied to both the heading and content areas.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="styled2">
        <x-slot:heading class="text-lg font-bold text-secondary">
            Another Styled Section
        </x-slot:heading>
        <x-slot:content class="prose prose-sm">
            <p>This content uses prose styling for better typography.</p>
            <blockquote>You can include rich content like quotes, code, and more.</blockquote>
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

### Accordion with Separators

```php
<x-artisanpack-accordion wire:model="separatorModel">
    <x-artisanpack-collapse name="sep1" separator>
        <x-slot:heading>Section with Separator</x-slot:heading>
        <x-slot:content>
            This section has a separator line between the heading and content.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="sep2" separator>
        <x-slot:heading>Another Separated Section</x-slot:heading>
        <x-slot:content>
            The separator helps visually distinguish between the heading and content.
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

### Accordion without Icons

```php
<x-artisanpack-accordion wire:model="noIconModel">
    <x-artisanpack-collapse name="clean1" no-icon>
        <x-slot:heading>Clean Section (No Icon)</x-slot:heading>
        <x-slot:content>
            This section doesn't show any toggle icons for a cleaner look.
        </x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="clean2" no-icon>
        <x-slot:heading>Another Clean Section</x-slot:heading>
        <x-slot:content>
            Useful when you want a minimal design without visual indicators.
        </x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```
## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the accordion element |
| `noJoin` | boolean | `false` | Whether to disable the join styling (allows individual collapse styling) |
| `collapse-plus-minus` | boolean | `false` | Whether to use plus/minus icons instead of arrows for all child collapses |

## Required Attributes

The accordion component requires a `wire:model` attribute for proper functionality:

```php
<x-artisanpack-accordion wire:model="selectedSection">
    <!-- collapse components -->
</x-artisanpack-accordion>
```

## Livewire Integration

The accordion component is designed to work with Livewire and requires a model binding:

```php
// In your Livewire component
class MyComponent extends Component
{
    public $selectedSection = null; // or 'section1' for default open
    
    public function render()
    {
        return view('livewire.my-component');
    }
}
```

```php
<!-- In your Blade template -->
<x-artisanpack-accordion wire:model="selectedSection">
    <x-artisanpack-collapse name="section1">
        <x-slot:heading>First Section</x-slot:heading>
        <x-slot:content>Content for the first section</x-slot:content>
    </x-artisanpack-collapse>
    
    <x-artisanpack-collapse name="section2">
        <x-slot:heading>Second Section</x-slot:heading>
        <x-slot:content>Content for the second section</x-slot:content>
    </x-artisanpack-collapse>
</x-artisanpack-accordion>
```

## Icon Types

The accordion supports two icon styles:

### Arrow Icons (Default)
- Uses DaisyUI's `collapse-arrow` class
- Shows `>` when collapsed, `∨` when expanded
- Applied automatically when no icon modifier is specified

### Plus/Minus Icons
- Uses DaisyUI's `collapse-plus` class  
- Shows `+` when collapsed, `-` when expanded
- Enable by adding `collapse-plus-minus` attribute to the accordion

## Styling

The Accordion component uses DaisyUI's collapse component with join styling for a seamless appearance. You can customize the appearance by:

1. Adding custom classes to the accordion container
2. Adding custom classes to individual collapse components within the accordion
3. Using the `collapse-plus-minus` prop to change icon style

### Default Classes Applied

- `join join-vertical w-full` - Applied to the accordion container
- `join-item` - Applied to each collapse component within the accordion
- `collapse-arrow` or `collapse-plus` - Applied based on icon preference

## Accessibility

The Accordion component follows accessibility best practices:

- Uses proper radio button inputs for mutually exclusive sections
- Supports keyboard navigation
- Maintains focus management when sections are toggled
- Uses semantic HTML structure
- Provides clear visual indicators for expanded/collapsed state

## Related Components

- [Collapse](collapse.md) - Individual collapsible content container used within accordions
- [Card](card.md) - Content container that can include collapsible sections
