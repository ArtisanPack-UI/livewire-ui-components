---
title: Separator Component
---

# Separator Component

The Separator component provides both horizontal and vertical dividers with optional loading indicators, color customization, and image integration. It's useful for separating content sections and can serve as a visual indicator for loading states.

## Basic Usage

```php
<x-artisanpack-separator />
```

## Examples

### Basic Horizontal Separator

```php
<x-artisanpack-separator />
```

### Vertical Separator

```php
<div class="flex items-center h-32">
    <div>Left content</div>
    <x-artisanpack-separator vertical class="mx-4" />
    <div>Right content</div>
</div>
```

### Separator with Color

```php
<!-- Predefined colors -->
<x-artisanpack-separator color="primary" />
<x-artisanpack-separator color="secondary" />
<x-artisanpack-separator color="accent" />
<x-artisanpack-separator color="success" />
<x-artisanpack-separator color="warning" />
<x-artisanpack-separator color="error" />

<!-- Hex color -->
<x-artisanpack-separator color="#ff6b35" />

<!-- Tailwind class -->
<x-artisanpack-separator color="border-blue-500" />
```

### Separator with Image

```php
<!-- Horizontal separator with centered image -->
<x-artisanpack-separator image="/path/to/icon.png" />

<!-- Vertical separator with centered image -->
<x-artisanpack-separator vertical image="/path/to/icon.png" />
```

### Separator with Progress Indicator

```php
<x-artisanpack-separator progress target="loadData" />

<!-- Button that triggers the loading state -->
<x-artisanpack-button wire:click="loadData">Load Data</x-artisanpack-button>
```

### Combined Features

```php
<!-- Separator with color, image, and progress -->
<x-artisanpack-separator 
    color="primary" 
    image="/path/to/logo.png" 
    progress 
    target="processData" 
/>
```

### Separator in a Card

```php
<x-artisanpack-card title="User Profile">
    <div>
        <h3>Personal Information</h3>
        <p>Name: John Doe</p>
        <p>Email: john@example.com</p>
    </div>
    
    <x-artisanpack-separator color="accent" />
    
    <div>
        <h3>Account Settings</h3>
        <p>Last login: Yesterday</p>
        <p>Two-factor authentication: Enabled</p>
    </div>
</x-artisanpack-card>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the separator element |
| `target` | string\|null | `null` | Optional target for the loading indicator |
| `progress` | bool | `false` | Whether to show progress indicator during loading |
| `color` | string\|null | `null` | Color for the separator (predefined, Tailwind class, or hex code) |
| `image` | string\|null | `null` | URL/path to image to display in the center of separator |
| `vertical` | bool | `false` | Whether to display as vertical separator |

## Color Options

The `color` prop supports three types of values:

### Predefined Colors
- `primary` - Primary theme color
- `secondary` - Secondary theme color  
- `accent` - Accent theme color
- `success` - Success theme color
- `warning` - Warning theme color
- `error` - Error theme color

### Hex Colors
Any valid hex color code (e.g., `#ff6b35`, `#3b82f6`)

### Tailwind Classes
Any Tailwind border color class (e.g., `border-blue-500`, `border-red-300`)

## Styling

The Separator component uses responsive design and adapts its styling based on orientation:

### Horizontal Separator Classes
- `h-[2px]` - Height of the separator line
- `border-t-[length:var(--border)]` - Top border using theme variable
- `my-5` - Vertical margin for spacing

### Vertical Separator Classes  
- `w-[2px]` - Width of the separator line
- `border-l-[length:var(--border)]` - Left border using theme variable
- `h-full` - Full height of container

### Image Integration
- Images are automatically centered within the separator
- Horizontal separators show lines on both sides of the image
- Vertical separators show lines above and below the image
- Images are sized to `w-8 h-8` with `object-contain` for proper scaling

## Loading Indicator

The progress indicator only appears when the `progress` prop is set to `true`. This differs from the previous Hr component which showed the indicator by default.

To use the loading feature:

1. Set the `progress` prop to `true`
2. Optionally set the `target` prop to a specific Livewire method
3. The separator will show a progress bar when the target method is running

## Accessibility

The Separator component follows accessibility best practices:

- Uses appropriate semantic HTML structure
- Provides sufficient color contrast for visibility
- Includes visual feedback for loading states when enabled
- Maintains proper spacing for content separation
- Images include empty alt attributes as they are decorative

## Related Components

- [Card](card) - Content container that often uses separators
- [Loading](loading) - Dedicated loading indicator component
- [Progress](progress) - Standalone progress indicator
