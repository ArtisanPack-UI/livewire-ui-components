# Toast Component

The Toast component provides a way to display brief, non-disruptive notifications to users. Toasts are useful for success messages, warnings, errors, and general information.

## Basic Usage

```php
// In a Livewire component
public function save()
{
    // Save logic here...
    
    $this->success('Saved successfully!', 'Your changes have been saved.');
}
```

## Examples

### Toast Types

```php
// Success toast
$this->success('Success!', 'Operation completed successfully.');

// Warning toast
$this->warning('Warning!', 'This action cannot be undone.');

// Error toast
$this->error('Error!', 'Something went wrong.');

// Info toast
$this->info('Information', 'Your session will expire in 5 minutes.');
```

### Custom Position

```php
// Position options: 'top', 'top-left', 'top-right', 'bottom', 'bottom-left', 'bottom-right'
$this->success('Saved!', 'Your changes have been saved.', 'top-right');
```

### Custom Icon

```php
$this->success('Uploaded!', 'File uploaded successfully.', null, 'o-cloud-arrow-up');
```

### Custom Timeout

```php
// Set a 5-second timeout (5000ms)
$this->info('Processing', 'Your request is being processed...', null, 'o-cog', 'alert-info', 5000);
```

### Toast with Redirect

```php
// Redirect after showing the toast
$this->success('Created!', 'New record created successfully.', null, 'o-check-circle', 'alert-success', 3000, '/records');
```

### Custom CSS Classes

```php
$this->toast('custom', 'Custom Toast', 'With custom styling', null, 'o-sparkles', 'bg-purple-500 text-white');
```

## Color System

The toast component now supports flexible color customization using the color system:

### Using Color Parameter

```php
// Predefined variants
$this->toast('custom', 'Custom Toast', 'Using predefined variant', null, 'o-sparkles', null, 3000, null, 'primary');
$this->toast('custom', 'Custom Toast', 'Using predefined variant', null, 'o-sparkles', null, 3000, null, 'secondary');

// Tailwind colors
$this->toast('custom', 'Custom Toast', 'Using Tailwind color', null, 'o-sparkles', null, 3000, null, 'blue-500');
$this->toast('custom', 'Custom Toast', 'Using Tailwind color', null, 'o-sparkles', null, 3000, null, 'purple-600');

// Custom hex colors
$this->toast('custom', 'Custom Toast', 'Using hex color', null, 'o-sparkles', null, 3000, null, '#ff6b6b');
$this->toast('custom', 'Custom Toast', 'Using hex color', null, 'o-sparkles', null, 3000, null, '#4ecdc4');
```

### Using Color Adjustments

```php
// Lighter background
$this->toast('custom', 'Lighter Toast', 'With lighter background', null, 'o-sparkles', null, 3000, null, 'blue-500', 'lighter');

// Darker background
$this->toast('custom', 'Darker Toast', 'With darker background', null, 'o-sparkles', null, 3000, null, 'blue-500', 'darker');

// Transparent background
$this->toast('custom', 'Transparent Toast', 'With transparent background', null, 'o-sparkles', null, 3000, null, 'blue-500', 'transparent');

// Subtle background
$this->toast('custom', 'Subtle Toast', 'With subtle background', null, 'o-sparkles', null, 3000, null, 'blue-500', 'subtle');
```

### Enhanced Convenience Methods

The convenience methods also support the new color system:

```php
// Success toast with custom color
$this->success('Success!', 'Operation completed', null, 'o-check-circle', null, 3000, null, 'green-600');

// Warning toast with color adjustment
$this->warning('Warning!', 'Action cannot be undone', null, 'o-exclamation-triangle', null, 3000, null, 'orange-500', 'subtle');

// Error toast with hex color
$this->error('Error!', 'Something went wrong', null, 'o-x-circle', null, 3000, null, '#ff4757');

// Info toast with brand color
$this->info('Information', 'Session expires soon', null, 'o-information-circle', null, 5000, null, '#4ecdc4', 'lighter');
```

## Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `toast` | `$type`, `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo`, `$color`, `$colorAdjustment` | Base method for displaying toast notifications |
| `success` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo`, `$color`, `$colorAdjustment` | Display a success toast notification |
| `warning` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo`, `$color`, `$colorAdjustment` | Display a warning toast notification |
| `error` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo`, `$color`, `$colorAdjustment` | Display an error toast notification |
| `info` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo`, `$color`, `$colorAdjustment` | Display an info toast notification |

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$type` | string | - | The type of toast notification (`success`, `warning`, `error`, `info`, or custom) |
| `$title` | string | - | The title of the toast notification |
| `$description` | string\|null | `null` | Optional description for the toast notification |
| `$position` | string\|null | `null` | Optional position for the toast notification (`top`, `top-left`, `top-right`, `bottom`, `bottom-left`, `bottom-right`) |
| `$icon` | string | Varies by type | The icon to display in the toast notification |
| `$css` | string | Varies by type | CSS classes for the toast notification |
| `$timeout` | int | `3000` | Timeout in milliseconds before the toast automatically dismisses |
| `$redirectTo` | string\|null | `null` | Optional URL to redirect to after displaying the toast |
| `$color` | string\|null | `null` | Color variant, Tailwind color (e.g., 'red-500'), or hex code (e.g., '#ff0000') |
| `$colorAdjustment` | string\|null | `null` | Background adjustment: 'lighter', 'darker', 'transparent', or 'subtle' |

## Usage in Livewire Components

To use Toast notifications in your Livewire components, add the `Toast` trait:

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use ArtisanPack\LivewireUiComponents\Traits\Toast;

class MyComponent extends Component
{
    use Toast;
    
    public function save()
    {
        // Save logic...
        
        $this->success('Saved!', 'Your changes have been saved.');
    }
}
```

## Styling

The Toast component uses DaisyUI's alert component under the hood, which provides a wide range of styling options. You can customize the appearance of toasts by:

1. Using the provided parameters (`$css`, `$icon`, etc.)
2. Modifying the DaisyUI variables in your theme file

### Default CSS Classes

- Success: `alert-success`
- Warning: `alert-warning`
- Error: `alert-error`
- Info: `alert-info`

## Accessibility

The Toast component follows accessibility best practices:

- Uses appropriate ARIA roles for alerts
- Provides sufficient color contrast for all toast types
- Includes both visual and textual information
- Automatically dismisses after a configurable timeout

## Related Components

- [Alert](alert.md) - Static alert messages
- [Modal](modal.md) - Dialog boxes for important interactions
- [Icon](icon.md) - SVG icon display