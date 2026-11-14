---
title: Extension Hooks
menu_order: 1
---

# Extension Hooks

The package fires a small set of hooks, powered by [`artisanpack-ui/hooks`](https://github.com/ArtisanPack-UI/hooks), that let applications and other packages change rendered UI without editing components. Hook names follow the ecosystem convention: `ap.` plus camelCase segments joined by periods.

## Available hooks

| Hook | Type | Fires from | Arguments |
|---|---|---|---|
| `ap.livewireUiComponents.componentAttributes` | filter | Every component render | `(ComponentAttributeBag $attributes, string $componentName)` |
| `ap.livewireUiComponents.componentClasses` | filter | Components that pass a class list through `getClasses()` | `(array $classes, string $componentName, ComponentAttributeBag $attributes)` |
| `ap.livewireUiComponents.iconAlias` | filter | An icon name the registry doesn't know, before the Blade Icons fallback | `(string $iconName)` |
| `ap.livewireUiComponents.tableColumns` | filter | Table, when it resolves its columns | `(array $columns, string $context)`: the context is the table's `id`, or `'default'` |
| `ap.livewireUiComponents.themeColors` | filter | ThemeToggle, when it builds its choices | `(array $themes)`: `[ 'light' => [ 'label' => … ], 'dark' => […], 'high-contrast' => […], 'system' => […] ]` |
| `ap.livewireUiComponents.spotlightCommands` | filter | The `artisanpack.spotlight` route | `(array $commands, ?Authenticatable $user)` |
| `ap.livewireUiComponents.toastDispatched` | action | The `Toast` trait's `toast()` (and so `success()`, `error()` and the rest) and `flashToast()` | `(string $color, string $title, array $toast)`: the toast's options |
| `ap.livewireUiComponents.modalWillOpen` | action | `ModalBridge::willOpen()` | `(string $modalId)` |
| `ap.livewireUiComponents.modalWillClose` | action | `ModalBridge::willClose()` | `(string $modalId)` |

`$componentName` is the short name of the component's PHP class: `Button`, `Dialog`, `Table`. The deprecated aliases have their own classes, so `<x-artisanpack-modal>` reports `Modal`.

None of the built-in components pass their classes through `getClasses()` in 3.0, so `componentClasses` only fires for your own components that do. See [Custom Components](Advanced-Custom-Components).

## Registering callbacks

Register callbacks where you register other side effects, usually in a service provider's `boot()` method:

```php
use ArtisanPackUI\Hooks\Facades\Action;
use ArtisanPackUI\Hooks\Facades\Filter;
use Illuminate\View\ComponentAttributeBag;

// Tag every button for your analytics.
Filter::add( 'ap.livewireUiComponents.componentAttributes', function ( ComponentAttributeBag $attributes, string $name ) {
    if ( 'Button' === $name ) {
        return $attributes->merge( [ 'data-analytics' => 'button' ] );
    }

    return $attributes;
} );

// Draw Font Awesome's house as a Heroicon.
Filter::add( 'ap.livewireUiComponents.iconAlias', function ( string $name ) {
    return match ( $name ) {
        'fas.house' => 'o-home',
        default     => $name,
    };
} );

// Log toasts.
Action::add( 'ap.livewireUiComponents.toastDispatched', function ( string $color, string $title ) {
    logger()->info( 'Toast shown', [ 'color' => $color, 'title' => $title ] );
} );
```

`componentAttributes` runs on every render, so keep its callbacks cheap.

## Extending table columns

Use `tableColumns` to add or reshape columns of one table without editing the view that renders it. The context is the table's `id` attribute, or `'default'` when it has none:

```php
Filter::add( 'ap.livewireUiComponents.tableColumns', function ( array $columns, string $context ) {
    if ( 'users-table' === $context ) {
        $columns[] = [ 'key' => 'last_login', 'label' => __( 'Last login' ) ];
    }

    return $columns;
} );
```

Columns without a `key` are dropped after the filter runs.

## Renaming the theme choices

Use `themeColors` to rename the choices every `<x-artisanpack-theme-toggle>` offers. The themes themselves are the design system's `light`, `dark` and `high-contrast`, plus `system`:

```php
Filter::add( 'ap.livewireUiComponents.themeColors', function ( array $themes ) {
    $themes['dark']['label'] = __( 'Night' );

    return $themes;
} );
```

## Adding Spotlight commands

Add commands to the results of the package's Spotlight route from any package or app. Each command is an item (`label`, `href`, `description`, `icon`); the filter receives the items your Spotlight search class returned, and the current user:

```php
use Illuminate\Contracts\Auth\Authenticatable;

Filter::add( 'ap.livewireUiComponents.spotlightCommands', function ( array $commands, ?Authenticatable $user ) {
    if ( $user?->can( 'view-reports' ) ) {
        $commands[] = [
            'label' => __( 'Open reports' ),
            'href'  => route( 'reports.index' ),
            'icon'  => 'file-text',
        ];
    }

    return $commands;
} );
```

This only applies when Spotlight uses the package's route. See [Spotlight](Livewire-Spotlight).

## Dialog lifecycle hooks

Dialogs open and close in the browser, so the server gets no automatic signal. To run the `modalWillOpen` and `modalWillClose` actions, call the bridge from the Livewire action that opens or closes the dialog:

```php
use ArtisanPack\LivewireUiComponents\Support\ModalBridge;

public function confirmDelete(): void
{
    ModalBridge::willOpen( 'confirm-delete' );
    $this->confirmingDelete = true;
}

public function cancelDelete(): void
{
    ModalBridge::willClose( 'confirm-delete' );
    $this->confirmingDelete = false;
}
```

```blade
<x-artisanpack-dialog id="confirm-delete" wire:model="confirmingDelete" :title="__( 'Delete this order?' )">
    …
</x-artisanpack-dialog>
```

The id you pass is up to you; using the dialog's `id` keeps them easy to match. A dialog closed on the client (Escape, the close button, the backdrop) doesn't call the bridge.

## Performance

When nothing is registered for a filter, `applyFilters()` returns the value straight away, so idle hooks cost a lookup per call.
