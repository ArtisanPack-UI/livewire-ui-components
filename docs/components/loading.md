# Loading Component

The Loading component provides a way to display loading spinners or indicators in your application. It's useful for indicating that content is being loaded or an action is in progress.

## Basic Usage

```php
<x-artisanpack-loading />
```

## Examples

### Default Loading Spinner

```php
<x-artisanpack-loading />
```

### Different Types of Spinners

```php
<!-- Spinner (default) -->
<x-artisanpack-loading class="loading-spinner" />

<!-- Dots -->
<x-artisanpack-loading class="loading-dots" />

<!-- Ring -->
<x-artisanpack-loading class="loading-ring" />

<!-- Ball -->
<x-artisanpack-loading class="loading-ball" />

<!-- Bars -->
<x-artisanpack-loading class="loading-bars" />

<!-- Infinity -->
<x-artisanpack-loading class="loading-infinity" />
```

### Different Sizes

```php
<!-- Extra Small -->
<x-artisanpack-loading class="loading-xs" />

<!-- Small -->
<x-artisanpack-loading class="loading-sm" />

<!-- Medium (default) -->
<x-artisanpack-loading class="loading-md" />

<!-- Large -->
<x-artisanpack-loading class="loading-lg" />
```

### Different Colors

```php
<!-- Primary Color -->
<x-artisanpack-loading class="text-primary" />

<!-- Secondary Color -->
<x-artisanpack-loading class="text-secondary" />

<!-- Accent Color -->
<x-artisanpack-loading class="text-accent" />

<!-- Success Color -->
<x-artisanpack-loading class="text-success" />

<!-- Warning Color -->
<x-artisanpack-loading class="text-warning" />

<!-- Error Color -->
<x-artisanpack-loading class="text-error" />
```

### Combined Customizations

```php
<x-artisanpack-loading class="loading-spinner loading-lg text-primary" />
```

### In a Button

```php
<x-artisanpack-button disabled>
    <x-artisanpack-loading class="loading-spinner loading-sm mr-2" />
    Loading...
</x-artisanpack-button>
```

### With Livewire Loading States

```php
<div>
    <x-artisanpack-button wire:click="loadData">
        <x-artisanpack-loading class="loading-spinner loading-sm mr-2" wire:loading wire:target="loadData" />
        Load Data
    </x-artisanpack-button>
    
    <div class="mt-4">
        <div wire:loading.flex wire:target="loadData" class="items-center justify-center">
            <x-artisanpack-loading class="loading-spinner loading-lg" />
            <span class="ml-2">Loading data...</span>
        </div>
        
        <div wire:loading.remove wire:target="loadData">
            <!-- Content when not loading -->
        </div>
    </div>
</div>
```

### Centered in Container

```php
<div class="flex items-center justify-center h-64">
    <x-artisanpack-loading class="loading-spinner loading-lg" />
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the loading element |

## Styling

The Loading component uses DaisyUI's loading component under the hood, which provides a variety of spinner types and sizes.

### Spinner Types

- `loading-spinner` - A circular spinner (default)
- `loading-dots` - Three dots that pulse
- `loading-ring` - A circular ring
- `loading-ball` - A bouncing ball
- `loading-bars` - Vertical bars
- `loading-infinity` - An infinity symbol

### Sizes

- `loading-xs` - Extra small
- `loading-sm` - Small
- `loading-md` - Medium (default)
- `loading-lg` - Large

### Colors

You can use Tailwind's text color utilities to change the color of the loading spinner:

- `text-primary` - Primary color
- `text-secondary` - Secondary color
- `text-accent` - Accent color
- `text-success` - Success color
- `text-warning` - Warning color
- `text-error` - Error color
- `text-info` - Info color

## Accessibility

The Loading component follows accessibility best practices:

- Uses appropriate ARIA attributes for loading states
- Can be combined with text descriptions for screen readers
- Provides visual indication of loading states
- Can be used with Livewire's loading states for dynamic feedback

When using loading indicators, it's recommended to:

1. Provide text context when possible (e.g., "Loading data...")
2. Use appropriate sizes based on the importance of the loading action
3. Consider using the loading indicator in combination with disabled states for buttons or forms

## Related Components

- [Button](button.md) - Can contain loading indicators
- [Hr](hr.md) - Horizontal rule with loading indicator
- [Progress](progress.md) - Progress bar for determinate loading states