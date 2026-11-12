---
title: Tooltip Component
---

# Tooltip Component

A short label on hover and keyboard focus, built on the ArtisanPack UI design system. The control in the default slot is the trigger: the tooltip describes it with `aria-describedby` while it shows. It waits `delay` before showing on hover, shows at once on keyboard focus, stays while the pointer is on it, and closes on Escape (WCAG 1.4.13). Only one tooltip shows at a time.

The interaction comes from the design system's behavior engine (`apuiTooltip`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

New in 3.0. It replaces the DaisyUI `tooltip` classes (`class="tooltip" data-tip="…"`) and the `tooltip`, `tooltip-left`, `tooltip-right` and `tooltip-bottom` props of 2.x components: wrap the control in a Tooltip instead.

## Basic Usage

```blade
<x-artisanpack-tooltip :content="__( 'Edit' )" shortcut="E">
	<x-artisanpack-button variant="outline" color="neutral" size="sm" icon-only leading-icon="pencil" :aria-label="__( 'Edit' )" wire:click="edit" />
</x-artisanpack-tooltip>
```

The first element in the slot becomes the trigger. It keeps its own id, classes and name, and gets the trigger part's attributes. Text in the slot is wrapped in a focusable `<span>`. A tooltip is never the only name of an icon button: give the button its own `aria-label`.

## Long text

`size="lg"` is for a sentence or two, at body size:

```blade
<x-artisanpack-tooltip size="lg" placement="end" :content="__( 'Conversion is completed deals divided by unique visits in the period.' )">
	<x-artisanpack-button variant="ghost" color="neutral" size="sm" icon-only leading-icon="info" :aria-label="__( 'About conversion' )" />
</x-artisanpack-tooltip>
```

Don't put links or buttons in a tooltip; use [Popover](popover).

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `content` | string or slot | none | The label. A `content` slot (text and inline formatting) overrides it. |
| `placement` | `top` · `bottom` · `start` · `end` | `top` | The trigger's side the label sits on, centered. `start` and `end` follow the reading direction. Emitted as `data-placement`; the side it settled on, after flipping, is `data-side`. |
| `delay` | int | `400` | Milliseconds the pointer rests on the trigger before it shows. Focus shows it at once. |
| `size` | `md` · `lg` | `md` | `lg` for longer text. Emitted as `data-size`. |
| `shortcut` | string | none | A keyboard shortcut shown after the label, as keys joined with `+` (`⌘+K`). Each key is a `kbd` part. |
| `open` | bool | none | Leave it out and hover and focus show the label. `true` or `false` forces it; `wire:model` binds it both ways. |
| `disabled` | bool | `false` | Never shows the label. Emitted as `data-disabled`. |
| `portal` | bool | `true` | Renders the label at the end of the body (`x-teleport`). Off renders it in place. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `positioner`, `arrow`, `kbd`. |

## Attributes and events

`class` lands on the label (`.apui-tooltip`). Every other attribute lands on the element that holds the Alpine component, which is where the tooltip dispatches `open-change` (`$event.detail.open`) when it shows or hides itself.

## Markup

```html
<span id="edit-tip" x-data="apuiTooltip({ … })" style="display: contents">
	<button class="apui-button" aria-label="Edit" data-scope="tooltip" data-part="trigger" …>…</button>
	<div data-scope="tooltip" data-part="positioner">
		<div class="apui-tooltip" data-scope="tooltip" data-part="content" role="tooltip" data-placement="top" data-size="md">
			<span data-part="arrow" aria-hidden="true"></span>Edit<kbd data-part="kbd">E</kbd>
		</div>
	</div>
</span>
```

## Upgrading from 2.x

| 2.x | 3.0 |
|---|---|
| `<div class="tooltip" data-tip="Hi">…</div>` | `<x-artisanpack-tooltip content="Hi">…</x-artisanpack-tooltip>` |
| `class="tooltip tooltip-left"` · `tooltip-right` · `tooltip-bottom` | `placement="start"` · `end` · `bottom` |
| `class="tooltip tooltip-open"` | `:open="true"` |
| `tooltip-primary` and the other colors | dropped: tooltips use the inverse surface |
| `tooltip="…"` on Button, Link, Breadcrumbs items, File or Stat | wrap the control in a Tooltip |
| `tooltip-left="…"` · `tooltip-right="…"` · `tooltip-bottom="…"` | a Tooltip with `placement="start"` · `end` · `bottom` |

## Accessibility

- The label is `role=tooltip`, linked to the trigger with `aria-describedby` while it shows.
- It shows on keyboard focus as well as hover, is hoverable, and Escape hides it without moving focus.
- `start` and `end` follow the reading direction, and the label flips to stay in the viewport.
- In forced colors the label keeps a visible edge.
