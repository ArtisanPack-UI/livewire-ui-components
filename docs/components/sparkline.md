---
title: Sparkline Component
---

# Sparkline Component

A word-sized trend with no axes, built on the ArtisanPack UI design system (`svg.apui-sparkline`). It's drawn on the server as inline SVG, with the same coordinates React and Vue draw: no chart library and no script, so Livewire updates and `wire:poll` redraw it.

## Basic Usage

```blade
<x-artisanpack-sparkline :data="[ 8, 12, 10, 16, 15, 19, 23, 22, 26 ]" />
<x-artisanpack-sparkline :data="$visits" variant="area" :width="140" :height="48" />
<x-artisanpack-sparkline :data="$orders" variant="bar" />
```

`line` draws a dot on the latest value; `area` adds a soft fill; `bar` draws one bar per value. Values that cross zero draw a dashed baseline at zero, and bars grow from it.

## In a table

Give the sparklines in a column the same size, and keep the numbers in the table: the sparkline is a summary, not the data.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `data` | array | `[]` | The values, oldest first. Anything that isn't a number is skipped. |
| `variant` | string | `line` | `line`, `area` or `bar`. |
| `width` | int | `96` | The width in px. |
| `height` | int | `28` | The height in px. |
| `label` | string | `__( 'Trend from {first} to {latest}' )` | The accessible label; `{first}`, `{latest}`, `{min}`, `{max}` and `{count}` are filled in. |
| `classes` | array | `[]` | Extra classes per part: `root`, `area`, `line`, `dot`, `bar`, `baseline`. |

Every other attribute lands on the `<svg>`.

## Upgrading from 2.x

- The Sparkline no longer uses ApexCharts: remove the `resources/js/sparkline.js` import. Nothing replaces it.
- `type` still works, as `variant`.
- `color`, `stroke-width`, `curve` and `fill-opacity` are gone. The trend is one color, `--apui-chart-1`; set it on a parent to change it.

## Accessibility

- `role="img"` with a label stating the first and latest values.
- Put the values themselves nearby, in a table or tooltip.
- One color for the whole trend: never color it by up or down. The plot stays left to right in right-to-left layouts.
