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

## Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `toast` | `$type`, `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo` | Base method for displaying toast notifications |
| `success` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo` | Display a success toast notification |
| `warning` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo` | Display a warning toast notification |
| `error` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo` | Display an error toast notification |
| `info` | `$title`, `$description`, `$position`, `$icon`, `$css`, `$timeout`, `$redirectTo` | Display an info toast notification |

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