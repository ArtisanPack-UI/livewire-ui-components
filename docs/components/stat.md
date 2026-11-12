---
title: Stat Component
---

# Stat Component

A single headline number with its label, its change and the context for it, built on the ArtisanPack UI design system (`.apui-stat`). The change is an arrow and text, never color alone.

## Basic Usage

```blade
<x-artisanpack-stat
	:label="__( 'Total revenue' )"
	value="7,415,644"
	icon="wallet"
	color="info"
	:change="-5.4"
	:description="__( 'vs last month' )"
/>
```

`change` is a percentage: it shows as `5.4%` with a down arrow and sets the trend from its sign. For other changes, pass the text as `delta` and the direction as `trend`:

```blade
<x-artisanpack-stat :label="__( 'Hours listened' )" value="34.6" delta="6.2 hrs" trend="up" :description="__( 'this week' )" size="sm" bare />
```

## A row of stats

Put stats side by side with a layout component, and emphasize at most one:

```blade
<x-artisanpack-grid :columns="4">
	<x-artisanpack-stat :label="__( 'Total income' )" value="$348,261" icon="wallet" color="primary" :change="10.9" :description="__( 'vs last month' )" emphasis />
	<x-artisanpack-stat :label="__( 'Profit' )" value="$15,708.98" icon="trending-up" color="success" :change="8.1" :description="__( 'vs last month' )" />
</x-artisanpack-grid>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string | none | What the number measures. |
| `value` | string | none | The number, already formatted. The default slot replaces it. |
| `unit` | string | none | A unit after the value, smaller and muted. |
| `icon` | string | none | A registry icon for the tile at the end of the label row. The `icon` slot replaces it. |
| `delta` | string | none | The change as text. Takes precedence over `change`. |
| `change` | float | none | The change as a percentage, shown with one decimal; sets `trend` from its sign. |
| `trend` | string | none | `up`, `down` or `flat`. |
| `description` | string | none | Context after the delta. |
| `size` | string | `md` | `sm`, `md` or `lg`: the value's type style. |
| `color` | string | `neutral` | Tints the icon tile and the emphasis fill: one of the eight semantic colors. |
| `emphasis` | bool | `false` | A soft fill in `color`, for the one stat that matters. |
| `bare` | bool | `false` | No container, for a stat inside a Card. |
| `up-label` | string | `__( 'Increase' )` | Hidden text before an up delta. |
| `down-label` | string | `__( 'Decrease' )` | Hidden text before a down delta. |
| `flat-label` | string | `__( 'No change' )` | Hidden text before a flat delta. |
| `classes` | array | `[]` | Extra classes per part: `root`, `top`, `label`, `icon`, `value`, `unit`, `foot`, `delta`, `description`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- `title` still works, as `label`, and `change-label` as `description`.
- `change` now shows without a sign (`5.4%`): the arrow and hidden text say the direction.
- The tooltip, `icon-position`, `title-position`, `content-align`, glass, sparkline and count-up animation props are gone. Use Tooltip, Grid, Card's `surface` and KpiCard (for a sparkline).
- `color` takes the eight semantic names only; it tints the icon tile, not the value.

## Accessibility

- The delta has visible text, an arrow, and hidden direction text (`up-label`, `down-label`, `flat-label`).
- The value uses tabular figures; size carries the hierarchy, never bold.
- The icon tile is decorative and hidden from assistive technology.
