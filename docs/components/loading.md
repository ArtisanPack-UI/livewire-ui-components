---
title: Loading Component
---

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

### SVG Icon Examples

#### SVG-based Loading with Custom Icon

```php
<!-- SVG loading with custom Heroicon -->
<x-artisanpack-loading type="svg" icon="heroicon-o-arrow-path" class="w-6 h-6 text-primary" />

<!-- SVG loading with different icon -->
<x-artisanpack-loading type="svg" icon="heroicon-o-cog-6-tooth" class="w-8 h-8 text-secondary" />

<!-- Non-animated SVG icon -->
<x-artisanpack-loading type="svg" icon="heroicon-o-clock" :animated="false" class="w-5 h-5" />
```

#### Custom SVG Content

```php
<!-- Custom SVG spinner -->
<x-artisanpack-loading 
    type="custom" 
    customSvg='<svg class="w-5 h-5 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>'
/>

<!-- Static custom SVG -->
<x-artisanpack-loading 
    type="custom"
    :animated="false"
    customSvg='<svg class="w-6 h-6 text-warning" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
    </svg>'
/>
```

#### Fallback to CSS Loading

```php
<!-- Explicitly use CSS type -->
<x-artisanpack-loading type="css" class="loading-dots loading-lg" />

<!-- Default behavior (CSS) -->
<x-artisanpack-loading class="loading-ring loading-md text-accent" />
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
| `type` | string\|null | `null` | Loading type ('css', 'svg', 'custom') |
| `icon` | string\|null | `null` | Custom icon name for SVG type (e.g., 'heroicon-o-arrow-path') |
| `customSvg` | string\|null | `null` | Custom SVG content as string |
| `animated` | boolean\|null | `true` | Whether to enable animation for SVG icons |

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

## Backward Compatibility

The Loading component maintains full backward compatibility with existing CSS-based loading indicators. All existing code will continue to work unchanged:

```php
<!-- These continue to work as before -->
<x-artisanpack-loading class="loading-spinner loading-lg" />
<x-artisanpack-loading class="loading-dots text-primary" />
<x-artisanpack-loading class="loading-ring loading-sm" />
```

### Migration Guide

To take advantage of the new SVG icons while maintaining existing functionality:

1. **Keep existing CSS loading**: No changes needed for current implementations
2. **Opt-in to SVG loading**: Add `type="svg"` and specify an `icon`
3. **Mix both approaches**: Use CSS loading for simple cases, SVG for custom needs

```php
<!-- Before (still works) -->
<x-artisanpack-loading class="loading-spinner" />

<!-- After (enhanced with SVG) -->
<x-artisanpack-loading type="svg" icon="heroicon-o-arrow-path" class="w-5 h-5 animate-spin" />
```

## Configuration

You can customize the default icons used by the Loading component by publishing and modifying the configuration file:

```bash
php artisan vendor:publish --tag=artisanpack-config
```

Then edit `config/livewire-ui-components.php`:

```php
'icons' => [
    'loading' => [
        // Default SVG loading icon
        'spinner' => 'heroicon-o-arrow-path',
        
        // Default loading type ('css' or 'svg')
        'default_type' => 'css',
        
        // You can change to 'svg' to use SVG by default
        // 'default_type' => 'svg',
    ]
]
```

### Configuration Examples

```php
// Use a different default spinner
'spinner' => 'heroicon-o-cog-6-tooth',

// Change default to SVG loading
'default_type' => 'svg',

// Use custom icons from other icon sets
'spinner' => 'phosphor-spinner',
```

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

- [Button](button) - Can contain loading indicators
- [Hr](hr) - Horizontal rule with loading indicator
- [Progress](progress) - Progress bar for determinate loading states