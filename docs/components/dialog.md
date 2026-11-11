---
title: Dialog Component
---

# Dialog Component

A modal window for a focused task or a confirmation, built on the ArtisanPack UI design system. It traps focus, locks page scroll and hides the page behind it from assistive technology. Escape, the close button and the backdrop close it, and focus returns to the trigger. Dialog replaces the deprecated [Modal](modal).

The interaction comes from the design system's behavior engine (`apuiDialog`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-dialog wire:model="showInvite" :trigger="__( 'Invite member' )" :title="__( 'Invite member' )" :description="__( 'They\'ll get an email with a link to join the project.' )">
	<x-artisanpack-input type="email" :label="__( 'Email' )" wire:model="email" />

	<x-slot:footer>
		<x-artisanpack-button variant="outline" color="neutral" wire:click="$set( 'showInvite', false )">{{ __( 'Cancel' ) }}</x-artisanpack-button>
		<x-artisanpack-button wire:click="invite">{{ __( 'Send invite' ) }}</x-artisanpack-button>
	</x-slot:footer>
</x-artisanpack-dialog>
```

`wire:model` binds the open state both ways through `$wire.entangle()`, so `$this->showInvite = true` opens the dialog and Escape sets it back to `false`. Use `wire:model.live` to send every change to the server at once. Without `wire:model`, `open` is plain Alpine state.

## Confirmation

A destructive confirmation is an `alertdialog`: the backdrop doesn't close it, and focus starts on the close button, the safe action. `color` tints the header icon. Name the confirm action ("Delete listing"), not "OK".

```blade
<x-artisanpack-dialog wire:model="confirmingDelete" role="alertdialog" size="sm" color="error" icon="trash" :title="__( 'Delete Cedarwood Estates?' )" :description="__( 'The listing and its 3 open offers will be removed. This can\'t be undone.' )">
	<x-slot:footer>
		<x-artisanpack-button variant="outline" color="neutral" wire:click="$set( 'confirmingDelete', false )">{{ __( 'Cancel' ) }}</x-artisanpack-button>
		<x-artisanpack-button color="error" wire:click="delete">{{ __( 'Delete listing' ) }}</x-artisanpack-button>
	</x-slot:footer>
</x-artisanpack-dialog>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `open` | bool | `false` | Whether it's open. `wire:model` binds it instead. Emitted as `data-state` on every part. |
| `size` | `sm` · `md` · `lg` | `md` | The panel's width: 360px, `--apui-dialog-width` (480px) or 640px. Emitted as `data-size`. |
| `role` | `dialog` · `alertdialog` | `dialog` | `alertdialog` for confirmations that interrupt. |
| `surface` | `overlay` · `glass` | `overlay` | The panel's surface. Emitted as `data-surface`. |
| `color` | semantic color | none | Tints the header icon. Emitted as `data-color`. |
| `icon` | string | none | An icon name from the registry, in a tinted circle before the title. |
| `title` | string or slot | none | The heading, linked with `aria-labelledby`. Every dialog needs one. |
| `description` | string or slot | none | Supporting text, linked with `aria-describedby`. |
| `footer` | string or slot | none | The actions row. |
| `trigger` | string or slot | none | Content for an outline Button that opens the dialog. Without it, open the dialog through `open`. |
| `modal` | bool | `true` | Traps focus, locks scroll and sets `aria-modal`. Turn it off only for a dialog that must leave the page usable. |
| `portal` | bool | `true` | Renders the dialog at the end of the body (`x-teleport`). Off renders it in place. |
| `hide-close` | bool | `false` | Hides the close button. Escape still closes the dialog. |
| `close-label` | string | `__( 'Close' )` | The close button's accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `backdrop`, `positioner`, `header`, `icon`, `title`, `description`, `closeTrigger`, `body`, `footer`. |

The default slot is the body. The `trigger`, `icon`, `title`, `description` and `footer` slots override the props of the same name.

## Attributes and events

`class` lands on the panel (`.apui-dialog`). Every other attribute lands on the element that holds the Alpine component, which is where the dialog dispatches `open-change` (`$event.detail.open`) when it opens or closes itself:

```blade
<x-artisanpack-dialog :title="__( 'Filters' )" x-on:open-change="console.log( $event.detail.open )">…</x-artisanpack-dialog>
```

Setting `open` (or the bound property) doesn't fire `open-change`.

## Ids

Every part has a stable, server-rendered id, so Livewire's morph keeps the panel and its focus trap. The base id is the `id` attribute, else one derived from the `wire:model` name, else a hash of the props that aren't text or open state. Derived ids include the Livewire component's id, and repeats in one render (a `@foreach`) get `-2`, `-3`, so they're unique on the page and a re-render keeps them. Pass your own `id` when you want a predictable one:

```blade
<x-artisanpack-dialog :id="'edit-'.$listing->id" …>
```

## Markup

```html
<div id="invite" x-data="apuiDialog({ … })" style="display: contents">
	<button class="apui-button" data-scope="dialog" data-part="trigger" …>Invite member</button>
	<div data-scope="dialog" data-part="backdrop"></div>
	<div data-scope="dialog" data-part="positioner">
		<div class="apui-dialog" data-scope="dialog" data-part="content" role="dialog" data-size="md" data-surface="overlay">
			<div data-part="header">icon · title · description · close-trigger</div>
			<div data-part="body">…</div>
			<div data-part="footer">…</div>
		</div>
	</div>
</div>
```

## Accessibility

- Focus moves into the dialog on open and is trapped there while it's modal; it returns to the trigger on close.
- The title is linked with `aria-labelledby`, the description with `aria-describedby`.
- The close button's accessible name is translatable (`__( 'Close' )`), or set with `close-label`.
- Glass falls back to the overlay surface under reduced transparency and in high contrast.
