---
title: Alert Component
---

# Alert Component

An inline message about the state of a page or section, built on the ArtisanPack UI design system (`.apui-alert`). Say what happened and what to do next. The tint and the status icon carry the meaning, so it never relies on color alone.

## Basic Usage

```blade
<x-artisanpack-alert color="info" :title="__( 'New reporting period' )" :description="__( 'Q4 numbers are now available in Analytics.' )" />

<x-artisanpack-alert color="error" :title="__( 'Payment failed' )">
	{{ __( 'We couldn’t charge the card ending in 4242. Update it to keep your listings active.' ) }}

	<x-slot:actions>
		<x-artisanpack-button size="xs" color="error" wire:click="updateCard">{{ __( 'Update card' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-alert>
```

The default slot fills the description. Named slots (`icon`, `title`, `description`, `actions`) override the props.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string or slot | none | The headline. |
| `description` | string or slot | none | Supporting text. The default slot fills it too. |
| `color` | semantic color | `neutral` | The status. `error` makes the alert `role=alert`; every other color is `role=status`. Emitted as `data-color`. |
| `variant` | `soft` · `outline` · `solid` | `soft` | `outline` suits dense forms; `solid` is for page-level failures that block work. Emitted as `data-variant`. |
| `surface` | `default` · `glass` | `default` | Emitted as `data-surface`. Glass keeps default text and tints only the icon. |
| `icon` | string or slot | the color's status icon | `circle-check`, `triangle-alert`, `circle-alert` or `info`. |
| `actions` | string or slot | none | Buttons under the text. |
| `dismissible` | bool | `false` | Adds a close button. |
| `close-label` | string | `__( 'Dismiss' )` | The close button's accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `title`, `description`, `actions`, `closeTrigger`. |

## Dismissing

A dismissible alert hides itself (the `hidden` attribute) when its close button is pressed, and dispatches `open-change` (`{ open: false }`):

```blade
<x-artisanpack-alert color="success" :title="__( 'Listing published' )" dismissible wire:open-change="markSeen" />
```

To show it again after a server render, give it a new `wire:key`.

## Accessibility

- `role=alert` for errors, announced at once; `role=status` otherwise.
- The icon is decorative (`aria-hidden`); the title and description carry the meaning.
- The close button is icon-only, so `close-label` is its only name. Translate it.

## Upgrading from 2.x

- `color` takes the eight semantic names only; Tailwind names, hex values and `color-adjustment` are gone.
- `glass`, `glass-tint` and `glass-tint-opacity` become `surface="glass"`. `shadow`, `uuid` and `id` (as a prop) are gone; `id` is an ordinary attribute.
- The default icon comes from the color; icons are registry names (`circle-check`), not Heroicons.
- The default slot is now the description, not a replacement for the title block.
- The close button's name is `__( 'Dismiss' )` (was "Close"), set with `close-label`.
