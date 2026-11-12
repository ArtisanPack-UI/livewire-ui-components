---
title: Drawer Component
---

# Drawer Component

A panel that slides in from an edge for filters, details or secondary tasks, built on the ArtisanPack UI design system. It runs on the same dialog machine as [Dialog](dialog): a modal drawer traps focus, locks page scroll and hides the page behind it from assistive technology. Escape, the close button and the backdrop close it, and focus returns to the trigger.

The interaction comes from the design system's behavior engine (`apuiDrawer`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-drawer wire:model="showFilters" :trigger="__( 'Filters' )" :title="__( 'Filters' )" :description="__( ':count properties match', [ 'count' => $count ] )">
	<x-artisanpack-input :label="__( 'Location' )" leading-icon="search" wire:model="location" />
	<x-artisanpack-toggle :label="__( 'Only show pet friendly' )" wire:model="petFriendly" />

	<x-slot:footer>
		<x-artisanpack-button variant="ghost" color="neutral" wire:click="resetFilters">{{ __( 'Reset' ) }}</x-artisanpack-button>
		<x-artisanpack-button wire:click="$set( 'showFilters', false )">{{ __( 'Show :count results', [ 'count' => $count ] ) }}</x-artisanpack-button>
	</x-slot:footer>
</x-artisanpack-drawer>
```

`wire:model` binds the open state both ways through `$wire.entangle()`, so `$this->showFilters = true` opens the drawer and Escape sets it back to `false`. Use `wire:model.live` to send every change to the server at once. Without `wire:model`, `open` is plain Alpine state. Put the primary action in the footer, and don't open a drawer from a drawer.

## Sides

`side` is logical: `end` (the default) is on the right in left-to-right pages and on the left in right-to-left ones, and `start` is the other edge. The panel slides in from its own edge. `bottom` is a full-width sheet with a grab handle, for short choices on small screens.

```blade
<x-artisanpack-drawer wire:model="sharing" side="bottom" :trigger="__( 'Share' )" :title="__( 'Share listing' )">
	<x-artisanpack-button variant="outline" color="neutral" leading-icon="copy" wire:click="copyLink">{{ __( 'Copy link' ) }}</x-artisanpack-button>
	<x-artisanpack-button variant="outline" color="neutral" leading-icon="mail" wire:click="email">{{ __( 'Email' ) }}</x-artisanpack-button>
</x-artisanpack-drawer>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `open` | bool | `false` | Whether it's open. `wire:model` binds it instead. Emitted as `data-state` on every part. |
| `side` | `end` · `start` · `bottom` | `end` | The edge it slides in from. Emitted as `data-side` on the panel and the positioner. |
| `size` | `sm` · `md` · `lg` | `md` | The panel's width at the start or end side: 320px, `--apui-drawer-width` (400px) or 560px. A bottom sheet is full width. Emitted as `data-size`. |
| `surface` | `overlay` · `glass` | `overlay` | The panel's surface. Emitted as `data-surface`. |
| `title` | string or slot | none | The heading, linked with `aria-labelledby`. Every drawer needs one. |
| `description` | string or slot | none | Supporting text, linked with `aria-describedby`. |
| `footer` | string or slot | none | The actions row. |
| `trigger` | string or slot | none | Content for an outline Button that opens the drawer. Without it, open the drawer through `open`. |
| `modal` | bool | `true` | Traps focus, locks scroll and sets `aria-modal`. Turn it off for a drawer that leaves the page usable. |
| `portal` | bool | `true` | Renders the drawer at the end of the body (`x-teleport`). Off renders it in place. |
| `hide-close` | bool | `false` | Hides the close button. Escape still closes the drawer. |
| `close-label` | string | `__( 'Close' )` | The close button's accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `backdrop`, `positioner`, `handle`, `header`, `title`, `description`, `closeTrigger`, `body`, `footer`. |

The default slot is the body; it scrolls when it's taller than the panel. The `trigger`, `title`, `description` and `footer` slots override the props of the same name.

## Attributes and events

`class` lands on the panel (`.apui-drawer`). Every other attribute lands on the element that holds the Alpine component, which is where the drawer dispatches `open-change` (`$event.detail.open`) when it opens or closes itself. Setting `open` (or the bound property) doesn't fire it.

## Ids

Every part has a stable, server-rendered id, so Livewire's morph keeps the panel and its focus trap. The base id is the `id` attribute, else one derived from the `wire:model` name, else a hash of the props that aren't text or open state. Repeats in one render get `-2`, `-3`.

## Markup

```html
<div id="filters" x-data="apuiDrawer({ … })" style="display: contents">
	<button class="apui-button" data-scope="drawer" data-part="trigger" …>Filters</button>
	<div data-scope="drawer" data-part="backdrop"></div>
	<div data-scope="drawer" data-part="positioner" data-side="end">
		<aside class="apui-drawer" data-scope="drawer" data-part="content" role="dialog" data-side="end" data-size="md" data-surface="overlay">
			<div data-part="header">title · description · close-trigger</div>
			<div data-part="body">…</div>
			<div data-part="footer">…</div>
		</aside>
	</div>
</div>
```

## Upgrading from 2.x

The 2.x Drawer was a DaisyUI `drawer` wrapped around a Card. In 3.0:

- The panel opens at the **end** edge by default (the right in LTR). 2.x opened at the left unless `right` was set: add `side="start"` to keep a 2.x drawer that had no `right`. `right` still works, deprecated, as `side="end"`.
- `subtitle` is `description` and the `actions` slot is `footer`; both still work, deprecated.
- The close button shows by default (`hide-close` hides it), so `with-close-button` is gone, and Escape always closes the drawer, so `close-on-escape` is gone.
- `without-trap-focus` is gone: a modal drawer always traps focus (WCAG). Use `:modal="false"` for a drawer that leaves the page usable.
- `open-on` and `close-on` are gone: open it with `wire:model`, or listen for your event and set the bound property.
- `separator` and the glass tint props are gone; `surface="glass"` replaces `glass`.

## Accessibility

- Focus moves into the drawer on open and is trapped there while it's modal; it returns to the trigger on close.
- The title is linked with `aria-labelledby`, the description with `aria-describedby`.
- The side follows the reading direction, and the slide-in is off under `prefers-reduced-motion`.
- The close button's accessible name is translatable (`__( 'Close' )`), or set with `close-label`.
