---
title: Loading Component
---

# Loading Component

Shows that work is in progress, built on the ArtisanPack UI design system (`.apui-loading`): a spinner or three dots, inline with a label or as an overlay over the region that is loading. It waits 300ms before showing, so fast loads don't flash.

## Basic Usage

```blade
<x-artisanpack-loading />
<x-artisanpack-loading variant="dots" color="neutral" />
<x-artisanpack-loading size="sm" wire:loading wire:target="save">{{ __( 'Saving changes…' ) }}</x-artisanpack-loading>
```

On its own the indicator says "Loading" to screen readers (visually hidden). Text in the default slot shows beside it instead.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `variant` | `spinner` · `dots` | `spinner` | Emitted as `data-variant`. |
| `size` | `xs` · `sm` · `md` · `lg` · `xl` | `md` | Emitted as `data-size`. |
| `color` | semantic color | `primary` | Tints the indicator. Emitted as `data-color`. |
| `label` | string | `__( 'Loading' )` | The visually hidden label when there's no slot. |
| `overlay` | bool | `false` | Covers the nearest positioned ancestor. Emitted as `data-overlay`. |
| `delay` | int | `300` | Milliseconds before it shows, written inline as `--apui-_delay`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `indicator`, `label`. |

## Overlay

```blade
<x-artisanpack-card :title="__( 'Weekly revenue' )" style="position: relative" wire:loading.attr="aria-busy" wire:target="range">
	{{-- … --}}
	<x-artisanpack-loading overlay size="lg" wire:loading wire:target="range">{{ __( 'Loading revenue…' ) }}</x-artisanpack-loading>
</x-artisanpack-card>
```

Set `aria-busy` on the region the overlay covers, and don't block the whole page for a partial load.

## Accessibility

- `role=status` with a text label; the indicator is `aria-hidden`.
- The spinner slows and the dots stop under reduced motion.

## Upgrading from 2.x

- `type`, `icon`, `custom-svg` and `animated` are gone: use `variant` (`spinner` or `dots`). DaisyUI classes such as `loading-sm` become `size="sm"`.
- It renders a `role=status` region with a label, and waits `delay` ms before showing (`:delay="0"` to show at once).
