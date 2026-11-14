---
title: Toasts
menu_order: 1
---

# Toasts

A [Toast](https://docs.artisanpackui.dev/documentation/design-system/components/toast) is a brief, non-blocking message about something that just happened. The component page covers how toasts look and behave; this page covers sending them from Laravel and Livewire.

## The toaster

Toasts show in a toaster region. Put one in your layout:

```blade
<x-artisanpack-toaster />
```

| Prop | Default | Does |
|---|---|---|
| `placement` | `bottom-end` | `top-start`, `top`, `top-end`, `bottom-start`, `bottom` or `bottom-end`. Start and end follow the text direction |
| `max` | `5` | At most this many show at once; the rest wait their turn |
| `gap` | `8` | Space between toasts, in pixels |
| `duration` | `5000` | Milliseconds before a toast dismisses itself, paused on hover and focus. `0` keeps toasts until they're closed |
| `label` | `Notifications` | The region's accessible name |
| `close-label` | `Dismiss notification` | The close buttons' accessible name |

The toaster is persisted across `wire:navigate`, so toasts stay up while the page changes. Use one per page.

## From a Livewire component

Dispatch the `toast` browser event:

```php
$this->dispatch( 'toast', title: __( 'Invoice sent' ), color: 'success' );
```

Or use the `Toast` trait:

```php
use ArtisanPack\LivewireUiComponents\Traits\Toast;

class EditInvoice extends Component
{
    use Toast;

    public function send(): void
    {
        // …

        $this->success( __( 'Invoice sent' ), __( 'Your customer will get it in a minute.' ) );
    }
}
```

| Method | Shows |
|---|---|
| `toast( $title, $description, color:, variant:, surface:, icon:, duration:, hideClose:, redirectTo: )` | Any toast |
| `success( $title, $description, $duration, $redirectTo )` | A success toast |
| `warning( … )` | A warning toast |
| `error( … )` | An error toast, announced at once (`role="alert"`). Don't make a toast the only place an error appears: use an [Alert](https://docs.artisanpackui.dev/documentation/design-system/components/alert) for errors that block the user |
| `info( … )` | An info toast |
| `flashToast( … )` | Keeps the toast for the next page, for a redirect you make yourself |

The options:

| Option | Values |
|---|---|
| `title`, `description` | The message and its supporting text. A toast needs at least one |
| `color` | `primary`, `secondary`, `accent`, `neutral` (the default), `success`, `warning`, `error` or `info` |
| `variant` | `default` or `inverse` |
| `surface` | `overlay` or `glass` |
| `icon` | An icon name from the [registry](Icons). Defaults to the color's status icon |
| `duration` | Milliseconds before it dismisses itself. `0` keeps it. Defaults to the toaster's |
| `hideClose` | Hides the close button |

An invalid option, such as an unknown color, throws an `InvalidArgumentException`.

## Toasts that survive a redirect

Pass `redirectTo` and the toast is flashed to the session, then shown on the page you redirect to (with `wire:navigate`):

```php
return $this->success( __( 'Invoice created' ), redirectTo: route( 'invoices.index' ) );
```

Livewire drops flashed data from an action that doesn't redirect, so when you redirect yourself, flash the toast first:

```php
$this->flashToast( __( 'Invoice created' ), color: 'success' );

return $this->redirectRoute( 'invoices.index' );
```

## From a controller

Outside Livewire, flash toasts with the `Toasts` class. The toaster shows them on the next page load, once:

```php
use ArtisanPack\LivewireUiComponents\Support\Toasts;

Toasts::flash( title: __( 'Profile updated' ), color: 'success' );

return redirect()->route( 'profile.edit' );
```

## From JavaScript

The prebuilt script (`@artisanpackScripts`) exposes the same toasts to plain JavaScript:

```js
ApuiBehavior.toast({ title: 'Copied', color: 'success' });
```

## Upgrading from 2.x

In 2.x, `toast()` took the type first: `toast( 'success', 'Saved' )`. In 3.0 the first two arguments are the title and the description. The old call still runs, with the new meaning, and raises a deprecation notice. Use `$this->success( __( 'Saved' ) )` or `toast( __( 'Saved' ), color: 'success' )`.

## Hook

Every toast sent through the trait fires the `ap.livewireUiComponents.toastDispatched` action. See [Extension Hooks](Advanced-Extension-Hooks).
