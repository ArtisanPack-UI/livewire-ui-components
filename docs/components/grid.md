---
title: Grid Component
---

# Grid Component

A layout primitive from the ArtisanPack UI design system: its children in equal columns, with the gap from the space scale. No Tailwind needed. Grid replaces the deprecated [WidgetGrid](widget-grid).

## Basic Usage

```blade
<x-artisanpack-grid :columns="4" gap="6" min-item-width="14rem">
	<x-artisanpack-stat … />
	<x-artisanpack-stat … />
	<x-artisanpack-stat … />
	<x-artisanpack-stat … />
</x-artisanpack-grid>
```

With `min-item-width`, the grid shows fewer columns rather than narrower ones, down to one, so a dashboard reflows on a phone. Without it, there are always `columns` columns. Columns run in the text direction.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `columns` | int | `1` | The number of columns, `1` to `12`; the most there are with `min-item-width`. Emitted as `data-columns`. |
| `min-item-width` | string | none | The narrowest a column may get, any CSS length. Sets `--apui-grid-min-item-width`. |
| `gap` | `0` `1` `2` `3` `4` `5` `6` `8` `10` `12` `16` | `4` | A step of the space scale (`4` is 16px). Emitted as `data-gap`. |
| `align` | `start` · `center` · `end` · `stretch` | `stretch` | Items along the block axis (`align-items`). |
| `justify` | `start` · `center` · `end` · `stretch` | `stretch` | Items along the inline axis (`justify-items`). |
