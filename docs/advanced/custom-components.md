---
title: Custom Components
menu_order: 2
---

# Custom Components

## Wrapping a component

The simplest custom component is an anonymous Blade component that renders a package component with your defaults:

```blade
{{-- resources/views/components/save-button.blade.php --}}
<x-artisanpack-button color="primary" leading-icon="check" {{ $attributes }}>
    {{ $slot->isEmpty() ? __( 'Save' ) : $slot }}
</x-artisanpack-button>
```

```blade
<x-save-button wire:click="save" />
```

Attributes you pass through `{{ $attributes }}`, `wire:model` and `wire:click` included, reach the package component as if you'd written them on it.

## Extending a component class

To change a component's behavior in PHP, extend its class and register the subclass under a tag of your own, in a service provider's `boot()` method:

```php
namespace App\View\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Badge;

class StatusBadge extends Badge
{
    // Override the constructor or methods here.
}
```

```php
use App\View\Components\StatusBadge;
use Illuminate\Support\Facades\Blade;

Blade::component( StatusBadge::class, 'status-badge' );
```

Where you can, prefer wrapping a component, the `classes` prop or the [extension hooks](Advanced-Extension-Hooks): a subclass depends on the parent's constructor and methods, which can change between versions.

## Your own components

Components you build yourself can extend `ArtisanPack\LivewireUiComponents\View\Components\BaseComponent` instead of Laravel's `Component`. They then fire the `componentAttributes` filter on every render, and can pass a class list through `$this->getClasses( $classes )` to fire `componentClasses`.

To build an interactive widget on the design system's behavior engine (a custom dialog or listbox, with the same focus, keyboard and ARIA handling), see [Alpine and Livewire](https://docs.artisanpackui.dev/documentation/design-system/behavior/alpine-and-livewire). For the classes and data attributes the design system's CSS styles, see [Component Markup](https://docs.artisanpackui.dev/documentation/design-system/styling/component-markup).
