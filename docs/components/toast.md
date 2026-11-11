---
title: Toast Component
---

# Toast Component

A brief, non-blocking message about something that just happened, built on the ArtisanPack UI design system. A toaster (the region) shows toasts created from data, stacked, timed and paused on hover and focus. Rendered on its own, a Toast is static.

Toasts are never the only place an error is reported: blocking errors use [Alert](alert).

## The toaster

Put one toaster on the page, outside Livewire components (it's `wire:ignore`), such as in the layout after `@artisanpackScripts`. It's `@persist`ed, so toasts survive `wire:navigate`:

```blade
<x-artisanpack-toaster />
```

| Prop | Type | Default | Description |
|---|---|---|---|
| `placement` | `top-start` · `top` · `top-end` · `bottom-start` · `bottom` · `bottom-end` | `bottom-end` | Where the region sits; start and end follow the text direction. |
| `max` | int | `5` | At most this many show at once; the rest queue. |
| `gap` | int | `8` | Space between toasts, in px. |
| `duration` | int | `5000` | Milliseconds before a toast dismisses itself, paused while the region is hovered or focused. `0` keeps them. |
| `label` | string | `__( 'Notifications' )` | The region's accessible name. |
| `close-label` | string | `__( 'Dismiss notification' )` | The close buttons' accessible name. |

Alt+T moves focus to the region; Escape on a toast dismisses it.

## Showing toasts

### From a Livewire component

The toaster listens for the `toast` browser event, so a dispatch shows one:

```php
$this->dispatch( 'toast', title: __( 'Listing saved' ), description: __( 'Cedarwood Estates is live now.' ), color: 'success' );
```

Or use the `Toast` trait:

```php
use ArtisanPack\LivewireUiComponents\Traits\Toast;

class EditListing extends Component
{
	use Toast;

	public function save(): void
	{
		// …
		$this->toast( __( 'Listing saved' ), __( 'Cedarwood Estates is live now.' ), color: 'success' );

		// Shorthands set the color: success(), warning(), error() and info().
		$this->success( __( 'Listing saved' ) );
	}
}
```

`toast()` takes `title`, `description`, `color`, `variant`, `surface`, `icon`, `duration` (`0` keeps it) and `hideClose`. Every toast fires the `ap.livewireUiComponents.toastDispatched` action with its color, title and options.

### Across a redirect

A dispatched toast is lost when the page changes, so flash it instead. The toaster on the next page shows it once:

```php
// Flash and redirect (with wire:navigate):
return $this->toast( __( 'Listing saved' ), color: 'success', redirectTo: route( 'listings.index' ) );

// Or flash, then redirect yourself:
$this->flashToast( __( 'Listing saved' ), color: 'success' );
return $this->redirect( route( 'listings.index' ) );
```

From a controller, use `Toasts::flash()`:

```php
use ArtisanPack\LivewireUiComponents\Support\Toasts;

Toasts::flash( title: __( 'Welcome back' ), color: 'success' );

return redirect()->route( 'dashboard' );
```

Livewire drops flashed data from an action that doesn't redirect, so flash only when you redirect.

### From an exception

`ToastException` answers a Livewire request with a toast instead of the error page:

```php
use ArtisanPack\LivewireUiComponents\Exceptions\ToastException;

throw ToastException::error( __( 'Upload failed' ), __( 'The file is larger than 10 MB.' ) );
```

### From JavaScript

```js
ApuiBehavior.toast( { title: 'Listing archived', action: { label: 'Undo', onClick: () => restore() } } );
```

A toast with an action stays until it's used or closed. Actions take a JavaScript `onClick`, so they're created from JavaScript, not from the server.

## A toast on its own

`<x-artisanpack-toast>` renders a static toast, for docs or an inline status. `color` tints the status icon, `inverse` stands out, and `error` is announced as an alert:

```blade
<x-artisanpack-toast color="success" :title="__( 'Listing saved' )" :description="__( 'Cedarwood Estates is live now.' )" />
<x-artisanpack-toast color="error" :title="__( 'Upload failed' )" :description="__( 'The file is larger than 10 MB.' )" />
<x-artisanpack-toast variant="inverse" :title="__( 'Listing archived' )">
	<x-slot:actions>
		<x-artisanpack-button size="sm" variant="outline" color="neutral">{{ __( 'Undo' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-toast>
```

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string or slot | none | The message, linked with `aria-labelledby`. |
| `description` | string or slot | none | Supporting text, linked with `aria-describedby`. |
| `color` | semantic color | `neutral` | Tints the icon. `error` sets `role=alert`. Emitted as `data-color`. |
| `variant` | `default` · `inverse` | `default` | Emitted as `data-variant`. |
| `surface` | `overlay` · `glass` | `overlay` | Emitted as `data-surface`. |
| `icon` | string or slot | the color's status icon | `circle-check`, `triangle-alert`, `circle-alert` or `info`. |
| `actions` | string or slot | none | Buttons under the text. |
| `hide-close` | bool | `false` | Hides the close button. |
| `close-label` | string | `__( 'Dismiss notification' )` | The close button's accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `title`, `description`, `actions`, `closeTrigger`. |

The close button dispatches `open-change` (`{ open: false }`); hiding a static toast is the owner's job:

```blade
<div x-data="{ shown: true }" x-show="shown">
	<x-artisanpack-toast :title="__( 'Saved' )" x-on:open-change="shown = false" />
</div>
```

## Upgrading from 2.x

> **Warning: positional calls change meaning.** In 2.x the first arguments were `( $type, $title, $description )`; in 3.0 they are `( $title, $description, $color )`. Calls whose first argument is a color name and second is set raise an `E_USER_DEPRECATED` notice (in your deprecations log) so you can find them, and then:
>
> - `$this->toast( 'success', 'Saved' )` (two arguments) shows a toast titled "success" with "Saved" as its text, without an error.
> - `$this->toast( 'success', 'Saved', 'Details' )` (three arguments) throws an `InvalidArgumentException`, because "Details" is now the color and isn't one.
>
> Rewrite them with the color named: `$this->toast( 'Saved', 'Details', color: 'success' )`, or `$this->success( 'Saved', 'Details' )`.

- `<x-artisanpack-toast />` in the layout becomes `<x-artisanpack-toaster />`. `<x-artisanpack-toast>` is now the static toast; a bare one renders nothing and raises a deprecation notice.
- The trait's `toast( $type, $title, … )` becomes `toast( $title, $description, color: … )`. `position`, `css` and Heroicons names are gone: placement is the toaster's, and icons are registry names.
- `success()`, `warning()`, `error()` and `info()` take `( $title, $description, $duration, $redirectTo )`. The third argument was `$position`; passing a position string now throws a TypeError.
- `ToastException::typedMessage()` and its shorthands take `( $title, $description, $icon, $duration )`; `position`, `css` and `timeout` are gone. A 2.x position passed third is not an icon name, so the toast shows without an icon. `new ToastException( 'message' )` shows the message as an error toast.
- The `artisanpack-toast` browser event and `window.toast()` are gone: dispatch `toast`, or call `ApuiBehavior.toast()`.
