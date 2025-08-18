---
title: Component Prefixing
---

# Component Prefixing

Component prefixing is a powerful feature that allows you to avoid naming collisions between ArtisanPack UI components and other components in your application.

## Understanding Component Prefixing

When you use multiple UI libraries or create your own components, you may encounter naming collisions. For example, both ArtisanPack UI and another library might have a `Button` component. Component prefixing solves this problem by adding a prefix to all component names.

By default, ArtisanPack UI components use the `artisanpack-` prefix, so you would use them like this:

```php
<x-artisanpack-button>Click Me</x-artisanpack-button>
<x-artisanpack-card>Card content</x-artisanpack-card>
```

## When to Use Prefixing

You should consider using component prefixing in the following scenarios:

1. **When using multiple UI libraries**: If you're using ArtisanPack UI alongside other UI libraries like Jetstream, Breeze, or custom component libraries.

2. **When building a package**: If you're building a package that includes ArtisanPack UI components, using a prefix ensures your components won't conflict with the user's existing components.

3. **When you have custom components with the same names**: If you've created your own components with names that match ArtisanPack UI components (like Button, Card, etc.).

## Automatic Prefix Detection

The ArtisanPack UI installer automatically detects if you're using Jetstream, Breeze, or Livewire Flux and adds a prefix to avoid naming collisions. If detected, you'll see a message like this during installation:

```
---------------------------------------------
🚨`jetstream` was detected.🚨
---------------------------------------------
A global prefix on Livewire UI Components components was added to avoid name collision.

 * Example: x-artisanpack-button, x-artisanpack-card ...
 * See config/livewire-ui-components.php
---------------------------------------------
```

## Configuring Prefixes

You can customize the prefix by publishing the configuration file and modifying the `prefix` option:

```bash
php artisan vendor:publish --tag=livewire-ui-components.config
```

This will create a `livewire-ui-components.php` file in your application's `config` directory. Open this file and modify the `prefix` option:

```php
return [
    /**
     * Default component prefix.
     */
    'prefix' => 'artisanpack-',
    
    // Other configuration options...
];
```

### Changing the Prefix

You can change the prefix to anything you want:

```php
'prefix' => 'ui-', // Components will be used as <x-ui-button>, <x-ui-card>, etc.
```

### Removing the Prefix

If you want to use the components without a prefix, you can set the prefix to an empty string:

```php
'prefix' => '', // Components will be used as <x-button>, <x-card>, etc.
```

> **Warning**: Removing the prefix may cause naming collisions with other components in your application. Only do this if you're sure there are no conflicts.

### After Changing the Prefix

After changing the prefix, you need to clear the view cache:

```bash
php artisan view:clear
```

## Handling Prefix Changes

When you change the prefix, you'll need to update all your component references in your application. Here are some strategies to handle this:

### 1. Search and Replace

You can use your editor's search and replace functionality to update all component references:

- Search for: `<x-old-prefix-`
- Replace with: `<x-new-prefix-`

### 2. Create Aliases

If you want to support both the old and new prefixes during a transition period, you can create aliases in your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Create aliases for components with the old prefix
        Blade::component('old-prefix-button', \ArtisanPack\LivewireUiComponents\View\Components\Button::class);
        Blade::component('old-prefix-card', \ArtisanPack\LivewireUiComponents\View\Components\Card::class);
        // Add more aliases as needed
    }
}
```

### 3. Use a Blade Directive

You can create a Blade directive to handle the prefix dynamically:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('ui', function ($expression) {
            $prefix = config('livewire-ui-components.prefix');
            return "<?php echo '<x-{$prefix}' . {$expression}; ?>";
        });
    }
}
```

Then in your Blade templates:

```php
@ui('button>Click Me</x-' . $prefix . 'button')
```

## Best Practices

### 1. Be Consistent

Choose a prefix and use it consistently throughout your application. Mixing prefixed and non-prefixed components can lead to confusion.

### 2. Document Your Prefix

Document the prefix you're using in your project's README or documentation to help other developers understand how to use the components.

### 3. Consider Namespace Collisions

When choosing a prefix, consider potential namespace collisions with other libraries or future components. Choose a prefix that is unlikely to be used by other libraries.

### 4. Use Short, Descriptive Prefixes

Choose a prefix that is short but descriptive. For example, `ui-` or `ap-` (for ArtisanPack) are good choices because they're short but still indicate the source of the components.

## Examples

### Default Prefix

```php
<x-artisanpack-button>Click Me</x-artisanpack-button>
<x-artisanpack-card>
    <x-slot:header>Card Title</x-slot:header>
    Card content
</x-artisanpack-card>
```

### Custom Prefix

```php
<!-- With prefix set to 'ui-' -->
<x-ui-button>Click Me</x-ui-button>
<x-ui-card>
    <x-slot:header>Card Title</x-slot:header>
    Card content
</x-ui-card>
```

### No Prefix

```php
<!-- With prefix set to '' -->
<x-button>Click Me</x-button>
<x-card>
    <x-slot:header>Card Title</x-slot:header>
    Card content
</x-card>
```

## Troubleshooting

### Components Not Found

If you get an error like "Component not found: [component-name]" after changing the prefix, make sure you've cleared the view cache:

```bash
php artisan view:clear
```

### Prefix Not Applied

If the prefix isn't being applied, check that you've modified the correct configuration file and that you're not overriding the prefix elsewhere in your application.

### Multiple Prefixes

If you're seeing multiple prefixes (e.g., `<x-prefix1-prefix2-button>`), you might have configured the prefix in multiple places. Check your configuration and make sure you're only setting the prefix once.

## Related Topics

- [Custom Components](custom-components.md) - Learn how to create your own components that integrate with ArtisanPack UI
- [Customization](../customization.md) - General customization options for ArtisanPack UI components
