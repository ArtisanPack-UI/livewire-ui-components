---
title: Extension Hooks
---

# Extension Hooks

_Added in version 2.1.0._

This package fires a small set of cross-cutting hooks — powered by [`artisanpack-ui/hooks`](https://github.com/ArtisanPack-UI/hooks) — that let applications and other packages extend rendered UI without editing individual components. All hook names follow the ecosystem convention: `ap.` + camelCase segments joined by periods.

## Available Hooks

| Hook | Type | Fires from | Payload |
| --- | --- | --- | --- |
| `ap.livewireUiComponents.componentClasses` | filter | `BaseComponent::getClasses()` (opt-in per component) | `(array $classes, string $componentName, ComponentAttributeBag $attributes)` |
| `ap.livewireUiComponents.componentAttributes` | filter | `BaseComponent::withAttributes()` — every render | `(ComponentAttributeBag $attributes, string $componentName)` |
| `ap.livewireUiComponents.iconAlias` | filter | `Icon::icon()` | `(string $iconName)` |
| `ap.livewireUiComponents.toastDispatched` | action | `Toast` trait `toast()` method | `(string $type, string $title, array $options)` |
| `ap.livewireUiComponents.tableColumns` | filter | `Table::__construct()` | `(array $columns, string $context)` — context is the table's `id` or `'default'` |
| `ap.livewireUiComponents.themeColors` | filter | `ThemeToggle::__construct()` | `(array $themes)` — `['light' => ['label','theme','class'], 'dark' => [...]]` |
| `ap.livewireUiComponents.modalWillOpen` | action | `Support\ModalBridge::willOpen()` | `(string $modalId)` |
| `ap.livewireUiComponents.modalWillClose` | action | `Support\ModalBridge::willClose()` | `(string $modalId)` |
| `ap.livewireUiComponents.spotlightCommands` | filter | `artisanpack.spotlight` route handler | `(array $commands, ?\Illuminate\Contracts\Auth\Authenticatable $user)` |

## Registering Callbacks

Register hook callbacks anywhere you would register other side effects — typically in a service provider's `boot()` method:

```php
use ArtisanPackUI\Hooks\Facades\Action;
use ArtisanPackUI\Hooks\Facades\Filter;

// Add a class to every rendered button
Filter::add('ap.livewireUiComponents.componentClasses', function (array $classes, string $name) {
    if ('Button' === $name) {
        $classes[] = 'analytics-hook';
    }

    return $classes;
});

// Swap Font Awesome names for Heroicon names package-wide
Filter::add('ap.livewireUiComponents.iconAlias', function (string $name) {
    return match ($name) {
        'fa-home' => 'o-home',
        'fa-user' => 'o-user',
        default   => $name,
    };
});

// Log toasts to your analytics pipeline
Action::add('ap.livewireUiComponents.toastDispatched', function (string $type, string $title) {
    analytics()->track('toast_shown', ['type' => $type, 'title' => $title]);
});
```

## Extending Table Columns

Use `tableColumns` to add or reshape columns for a specific table without editing the caller. The context string is the table's `id` attribute, or `'default'` when the table has no id:

```php
Filter::add('ap.livewireUiComponents.tableColumns', function (array $columns, string $context) {
    if ('users-table' === $context) {
        $columns[] = ['key' => 'last_login', 'label' => 'Last Login'];
    }

    return $columns;
});
```

## Customizing the Theme Toggle

Use `themeColors` to change the labels, daisyUI theme keys, or CSS classes exposed by `<x-artisanpack-theme-toggle>`:

```php
Filter::add('ap.livewireUiComponents.themeColors', function (array $themes) {
    $themes['dark']['theme'] = 'midnight';

    return $themes;
});
```

## Firing Modal Lifecycle Hooks

Modals open through Alpine on the client, so the server has no automatic signal. Call the bridge from your Livewire actions when you open or close a modal to invoke the `modalWillOpen` / `modalWillClose` hooks:

```php
use ArtisanPack\LivewireUiComponents\Support\ModalBridge;

public function openConfirmDialog(): void
{
    ModalBridge::willOpen('confirm-delete');
    $this->dispatch('open-modal', id: 'confirm-delete');
}

public function closeConfirmDialog(): void
{
    ModalBridge::willClose('confirm-delete');
    $this->dispatch('close-modal', id: 'confirm-delete');
}
```

## Extending Spotlight Commands

Add commands to the spotlight search from any package or application code:

```php
Filter::add('ap.livewireUiComponents.spotlightCommands', function (array $commands, ?\Illuminate\Contracts\Auth\Authenticatable $user) {
    if ($user && $user->can('view-reports')) {
        $commands[] = [
            'label' => 'Open Reports',
            'route' => route('reports.index'),
        ];
    }

    return $commands;
});
```

## Performance

Filters short-circuit inside `applyFilters()` when no callback is registered, so idle hooks add only a facade dispatch plus a hash-map lookup per render. `componentClasses` is opt-in per component (route class lists through `$this->getClasses($classes)`); `componentAttributes` fires on every render but is cheap when nothing is subscribed. The base component name is cached per subclass so it is not recomputed each render.

## Related Reading

- [`artisanpack-ui/hooks` documentation](https://github.com/ArtisanPack-UI/hooks) — full API for `Action` and `Filter` facades, priorities, and callback removal.
- [Custom Components](Custom-Components) — build your own components on top of `BaseComponent` and inherit the same extension seams.
