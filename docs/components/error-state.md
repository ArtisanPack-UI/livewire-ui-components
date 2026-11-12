---
title: Error State Component
---

# Error State Component

Explains what failed, what's safe and how to recover, in place of content that couldn't load, built on the ArtisanPack UI design system (`.apui-error-state`). New in 3.0. It isn't [ValidationSummary](validation-summary), which lists form errors.

## Basic Usage

```blade
<x-artisanpack-error-state :title="__( 'Couldn’t load transactions' )" :description="__( 'The payments service didn’t respond. Your data is safe; try again in a moment.' )" :code="__( 'Error :status · :reference', [ 'status' => 503, 'reference' => $requestId ] )">
	<x-slot:actions>
		<x-artisanpack-button leading-icon="refresh-cw" wire:click="retry">{{ __( 'Try again' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-error-state>

<x-artisanpack-error-state variant="not-found" :heading-level="1" focus-title :title="__( 'This page doesn’t exist' )" />
```

The default slot fills the description. Named slots (`icon`, `title`, `description`, `actions`) override the props.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `variant` | `request` · `offline` · `not-found` · `forbidden` | `request` | Picks the tint and default icon (`server-crash`, `wifi-off`, `lock`); `not-found` shows `404` instead. Emitted as `data-variant`. |
| `size` | `sm` · `md` | `md` | Emitted as `data-size`. |
| `align` | `center` · `start` | `center` | Emitted as `data-align`. |
| `icon` | string or slot | the variant's | Set explicitly, it shows beside a numeral too. |
| `numeral` | string | `404` for `not-found` | A large status number in place of the default icon. |
| `title` | string or slot | none | What failed, as a heading. Don't blame the person. |
| `description` | string or slot | none | What's safe and what to do. |
| `code` | string | none | A reference people can quote to support. |
| `actions` | string or slot | none | Recovery buttons. |
| `heading-level` | int | `3` | 1 to 6. Use 1 for a full-page error. |
| `focus-title` | bool | `false` | Moves focus to the title when it renders, for a full-page error. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `numeral`, `title`, `description`, `code`, `actions`. |

## Accessibility

- `role=alert`, so an error that replaces content is announced.
- On a full-page error, `focus-title` moves focus to the title (`tabindex="-1"`), which shows the focus ring.
