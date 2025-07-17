# Customization

ArtisanPack UI Livewire Components is designed to be highly customizable, allowing you to adapt the components to match your application's design requirements. This guide covers the various ways you can customize the package.

## Configuration Options

### Publishing the Configuration File

To customize the package's configuration, you first need to publish the configuration file:

```bash
php artisan vendor:publish --tag=livewire-ui-components.config
```

This will create a `livewire-ui-components.php` file in your application's `config` directory.

### Available Configuration Options

The configuration file contains several options that you can customize:

```php
<?php

return [
    /**
     * Default component prefix.
     */
    'prefix' => 'artisanpack-',

    /**
     * Default route prefix.
     */
    'route_prefix' => '',

    /**
     * Components settings
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ]
    ],
    
    /**
     * Theme Generation Settings
     */
    'theme_output_path' => resource_path('css/artisanpack-ui-theme.css'),
];
```

### Component Prefixing

The `prefix` option allows you to add a prefix to all component names to avoid naming collisions with other packages or your own components.

For example, with the default prefix of `'artisanpack-'`, you would use components like this:

```php
<x-artisanpack-button>Click Me</x-artisanpack-button>
<x-artisanpack-card>Card content</x-artisanpack-card>
```

If you change the prefix to `'ui-'`, you would use components like this:

```php
<x-ui-button>Click Me</x-ui-button>
<x-ui-card>Card content</x-ui-card>
```

To remove the prefix entirely, set it to an empty string:

```php
'prefix' => '',
```

Then you can use components without a prefix:

```php
<x-button>Click Me</x-button>
<x-card>Card content</x-card>
```

> **Note**: After changing the prefix, you need to clear the view cache:
> ```bash
> php artisan view:clear
> ```

### Route Prefixing

Some components like Spotlight and Editor make network requests to internal routes. The `route_prefix` option allows you to add a prefix to these routes.

For example, with the default empty prefix, the routes would be:

- Spotlight: `/livewire-ui-components/spotlight`
- Editor: `/livewire-ui-components/upload`

If you set the prefix to `'my-components'`:

```php
'route_prefix' => 'my-components',
```

The routes would become:

- Spotlight: `/my-components/livewire-ui-components/spotlight`
- Editor: `/my-components/livewire-ui-components/upload`

This is useful when you need to avoid route conflicts with other packages or your own routes.

## Styling Customization

### Working with DaisyUI Themes

ArtisanPack UI Livewire Components is built on top of DaisyUI, which provides a powerful theming system. You can customize the appearance of components by modifying the DaisyUI theme variables.

#### Using the Theme Generator

The easiest way to customize the styling is to use the theme generator command:

```bash
php artisan artisanpack:generate-theme --primary=blue --secondary=slate --accent=amber
```

See the [Generating Color Themes](generating-themes.md) guide for more details.

#### Customizing DaisyUI Theme Variables

You can also directly modify the DaisyUI theme variables in your CSS:

```css
:root {
  /* Primary colors */
  --primary: #3b82f6;
  --primary-content: #ffffff;
  
  /* Secondary colors */
  --secondary: #64748b;
  --secondary-content: #ffffff;
  
  /* Accent colors */
  --accent: #f59e0b;
  --accent-content: #ffffff;
  
  /* Neutral colors */
  --neutral: #737373;
  --neutral-content: #ffffff;
  
  /* Base colors */
  --base-100: #ffffff;
  --base-200: #f3f4f6;
  --base-300: #e5e7eb;
  --base-content: #1f2937;
  
  /* State colors */
  --info: #0ea5e9;
  --info-content: #ffffff;
  --success: #22c55e;
  --success-content: #ffffff;
  --warning: #f97316;
  --warning-content: #ffffff;
  --error: #ef4444;
  --error-content: #ffffff;
}
```

### Overriding Default Styles

You can override the default styles of components by adding your own CSS rules. It's recommended to add these rules after importing the ArtisanPack UI theme:

```css
/* resources/css/app.css */
@import './artisanpack-ui-theme.css';

@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
.artisanpack-button {
  @apply rounded-full; /* Make all buttons rounded */
}

.artisanpack-card {
  @apply shadow-xl; /* Add extra shadow to cards */
}
```

### Using Utility Variables

DaisyUI provides several utility variables that control the appearance of components:

```css
:root {
  --rounded-box: 1rem;      /* Border radius for cards and other large elements */
  --rounded-btn: 0.5rem;    /* Border radius for buttons */
  --rounded-badge: 1.9rem;  /* Border radius for badges */
  --animation-btn: 0.25s;   /* Button click animation duration */
  --animation-input: 0.2s;  /* Input focus animation duration */
  --btn-focus-scale: 0.95;  /* Button scale when focused */
  --border-btn: 1px;        /* Button border width */
  --tab-border: 1px;        /* Tab border width */
  --tab-radius: 0.5rem;     /* Tab border radius */
}
```

Modifying these variables will affect all components that use them.

## Component Customization

### Extending Components

You can extend ArtisanPack UI components to add your own functionality or modify their behavior. To extend a component, create a new component class that extends the original component:

```php
<?php

namespace App\View\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Button as BaseButton;

class Button extends BaseButton
{
    public function render()
    {
        // Add custom logic here
        return parent::render();
    }
}
```

Then register your component in your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\View\Components\Button;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('button', Button::class);
    }
}
```

### Slot Usage

Most ArtisanPack UI components support slots, which allow you to insert custom content into specific parts of the component. For example, the Card component has `header`, `footer`, and default slots:

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

Refer to the documentation for each component to see which slots are available.

### Attribute Passing

ArtisanPack UI components automatically merge attributes passed to them with their default attributes. This allows you to add classes, data attributes, or event listeners to components:

```php
<x-artisanpack-button 
    class="my-custom-class" 
    data-custom="value" 
    @click="console.log('Clicked!')"
>
    Click Me
</x-artisanpack-button>
```

You can also override default attributes by passing your own values:

```php
<x-artisanpack-button type="submit" color="secondary">
    Submit
</x-artisanpack-button>
```

## Advanced Customization

### Creating Custom Components

You can create your own components that integrate with ArtisanPack UI. Here's an example of creating a custom card component:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CustomCard extends Component
{
    public $title;
    public $subtitle;
    
    public function __construct($title = null, $subtitle = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }
    
    public function render()
    {
        return view('components.custom-card');
    }
}
```

```php
<!-- resources/views/components/custom-card.blade.php -->
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </x-slot:header>
    
    {{ $slot }}
</x-artisanpack-card>
```

Register your component in your `AppServiceProvider`:

```php
Blade::component('custom-card', CustomCard::class);
```

Then use it in your views:

```php
<x-custom-card title="My Card" subtitle="With a subtitle">
    <p>This is a custom card component that uses ArtisanPack UI Card internally.</p>
</x-custom-card>
```

### Integrating with Existing UI Systems

If you're integrating ArtisanPack UI with an existing UI system, you may need to adapt the components to match your design system. Here are some strategies:

1. **Use component prefixing** to avoid naming collisions
2. **Create wrapper components** that adapt ArtisanPack UI components to your design system
3. **Extend the base components** to modify their behavior or appearance
4. **Use CSS to override styles** and make components match your design system

For example, to create a wrapper for the Button component:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppButton extends Component
{
    public $variant;
    public $size;
    
    public function __construct($variant = 'primary', $size = 'md')
    {
        $this->variant = $variant;
        $this->size = $size;
    }
    
    public function render()
    {
        // Map your design system's variants to ArtisanPack UI colors
        $colorMap = [
            'primary' => 'primary',
            'secondary' => 'secondary',
            'danger' => 'error',
            'warning' => 'warning',
            'success' => 'success',
        ];
        
        // Map your design system's sizes to ArtisanPack UI sizes
        $sizeMap = [
            'sm' => 'sm',
            'md' => 'md',
            'lg' => 'lg',
        ];
        
        $color = $colorMap[$this->variant] ?? 'primary';
        $btnSize = $sizeMap[$this->size] ?? 'md';
        
        return view('components.app-button', [
            'color' => $color,
            'size' => $btnSize,
        ]);
    }
}
```

```php
<!-- resources/views/components/app-button.blade.php -->
<x-artisanpack-button :color="$color" :size="$size" {{ $attributes }}>
    {{ $slot }}
</x-artisanpack-button>
```

This allows you to use your own component API while leveraging ArtisanPack UI components internally:

```php
<x-app-button variant="danger" size="lg">
    Delete
</x-app-button>
```
