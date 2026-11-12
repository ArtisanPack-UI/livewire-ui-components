---
title: Popover Component
---

# Popover Component

An anchored, non-modal panel with interactive content, for a short task tied to one control, built on the ArtisanPack UI design system. The panel is a labelled `role=dialog`: focus moves in when it opens and returns to the trigger when it closes, and Escape and an outside click close it. It flips to the other side when there's no room.

The interaction comes from the design system's behavior engine (`apuiPopover`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

Text in `trigger` renders an outline Button:

```blade
<x-artisanpack-popover :trigger="__( 'Share' )" :title="__( 'Share report' )">
	<x-artisanpack-input :label="__( 'Link' )" readonly :value="$shareUrl" />

	<x-slot:actions>
		<x-artisanpack-button size="sm" leading-icon="copy" wire:click="copyLink">{{ __( 'Copy link' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-popover>
```

## Your own trigger

An element in the `trigger` slot becomes the trigger itself: it gets the trigger part's attributes (`aria-haspopup`, `aria-expanded`, `aria-controls`) and keeps its own id, classes and name. An icon-only button needs its own `aria-label`.

```blade
<x-artisanpack-popover placement="top" :title="__( 'What counts as a visit?' )">
	<x-slot:trigger>
		<x-artisanpack-button variant="ghost" color="neutral" size="sm" icon-only leading-icon="info" :aria-label="__( 'About visits' )" />
	</x-slot:trigger>

	<p>{{ __( 'A unique person who opened the listing for at least 5 seconds, counted once a day.' ) }}</p>
</x-artisanpack-popover>
```

Keep it to a short task. For links or labels that only describe a control, use [Tooltip](tooltip); for a longer form, use [Dialog](dialog) or [Drawer](drawer).

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `open` | bool | `false` | Whether it's open. `wire:model` binds it instead. Emitted as `data-state`. |
| `placement` | `top` · `bottom` · `start` · `end` | `bottom` | The trigger's side the panel sits on, lined up with the trigger's start edge. `start` and `end` follow the reading direction. Emitted as `data-placement`; the side it settled on, after flipping, is `data-side`. |
| `arrow` | bool | `true` | Draws the arrow that points at the trigger. |
| `title` | string or slot | none | The heading, linked with `aria-labelledby`. Without one, give the popover an `aria-label`. |
| `actions` | string or slot | none | A row of actions at the end of the panel. |
| `trigger` | string or slot | none | Text for an outline Button, or (slot) the element to use as the trigger. |
| `portal` | bool | `true` | Renders the panel at the end of the body (`x-teleport`). Off renders it in place. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `positioner`, `arrow`, `title`, `body`, `actions`. |

The default slot is the body.

## Attributes and events

`class` and `aria-label` land on the panel (`.apui-popover`). Every other attribute lands on the element that holds the Alpine component, which is where the popover dispatches `open-change` (`$event.detail.open`) when it opens or closes itself.

## Markup

```html
<div id="share" x-data="apuiPopover({ … })" style="display: contents">
	<button class="apui-button" data-scope="popover" data-part="trigger" aria-haspopup="dialog" …>Share</button>
	<div data-scope="popover" data-part="positioner">
		<div class="apui-popover" data-scope="popover" data-part="content" role="dialog" data-placement="bottom">
			<span data-part="arrow" aria-hidden="true"></span>
			<h3 data-part="title">…</h3>
			<div data-part="body">…</div>
			<div data-part="actions">…</div>
		</div>
	</div>
</div>
```

## Upgrading from 2.x

The 2.x Popover opened on hover with Alpine's anchor plugin and DaisyUI classes. In 3.0 it opens on click, as a non-modal dialog. For a label on hover, use [Tooltip](tooltip).

- The `content` slot is the default slot; it still works, deprecated.
- `position` is `placement`: `left` is `start` and `right` is `end`. `position` still works, deprecated.
- `offset` and the glass props are gone.

## Accessibility

- The trigger has `aria-haspopup="dialog"`, `aria-expanded` and `aria-controls`.
- The panel is `role=dialog`, labelled by its title, and isn't modal: the page stays usable.
- Focus moves into the panel when it opens and returns to the trigger on Escape. Tab past the last control moves on after the trigger.
