---
title: Stack Component
---

# Stack Component

A layout primitive from the ArtisanPack UI design system: its children in a column or a row, with the gap from the space scale, alignment and optional wrapping. No Tailwind needed.

## Basic Usage

```blade
<x-artisanpack-stack gap="4">
	<x-artisanpack-input :label="__( 'Name' )" wire:model="name" />
	<x-artisanpack-input :label="__( 'Email' )" wire:model="email" type="email" />

	<x-artisanpack-stack orientation="horizontal" justify="end" gap="2" wrap>
		<x-artisanpack-button variant="ghost">{{ __( 'Cancel' ) }}</x-artisanpack-button>
		<x-artisanpack-button wire:click="save">{{ __( 'Save' ) }}</x-artisanpack-button>
	</x-artisanpack-stack>
</x-artisanpack-stack>
```

A horizontal stack runs in the text direction, so `start` and `end` follow it in RTL. Set `wrap` on a row that may not fit at narrow widths.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `orientation` | `vertical` · `horizontal` | `vertical` | A column or a row. Emitted as `data-orientation`. |
| `gap` | `0` `1` `2` `3` `4` `5` `6` `8` `10` `12` `16` | `2` | A step of the space scale (`2` is 8px). Emitted as `data-gap`. |
| `align` | `start` · `center` · `end` · `stretch` · `baseline` | `stretch` | Items across the axis (`align-items`). |
| `justify` | `start` · `center` · `end` · `between` · `around` · `evenly` | `start` | Items along the axis (`justify-content`). |
| `wrap` | bool | `false` | Lets a row wrap. Emitted as `data-wrap`. |
