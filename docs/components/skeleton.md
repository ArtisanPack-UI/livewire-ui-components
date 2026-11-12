---
title: Skeleton Component
---

# Skeleton Component

A placeholder shape that matches the layout while content loads, built on the ArtisanPack UI design system (`.apui-skeleton`). New in 3.0.

## Basic Usage

```blade
<div aria-busy="true" style="display: grid; gap: 14px">
	@foreach ( range( 1, 3 ) as $row )
		<div style="display: flex; gap: 12px; align-items: center">
			<x-artisanpack-skeleton shape="circle" width="40px" height="40px" />
			<div style="flex: 1">
				<x-artisanpack-skeleton shape="text" width="50%" />
				<x-artisanpack-skeleton shape="text" width="80%" />
			</div>
		</div>
	@endforeach
</div>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `shape` | `block` · `text` · `circle` | `block` | Emitted as `data-shape`. A text line is 10px with line spacing. |
| `width` | string | none | A CSS length, written inline. |
| `height` | string | none | A CSS length, written inline. |

## Accessibility

- Skeletons are `aria-hidden`; set `aria-busy="true"` on the container while it loads and drop it when the content arrives.
- The shimmer stops under reduced motion. Don't show skeletons for more than a few seconds; switch to a status.
