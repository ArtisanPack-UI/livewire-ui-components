---
title: Progress Radial Component
---

# Progress Radial Component

Circular progress with the value in the center, built from Progress on the ArtisanPack UI design system (`.apui-progress-radial`). Use it for a single headline percentage; to compare values, use Progress bars.

## Basic Usage

```blade
<x-artisanpack-progress-radial :value="68" :size="80" :thickness="7" :label="__( 'complete' )" />
<x-artisanpack-progress-radial :value="68" :size="40" :thickness="4" hide-value :label="__( 'Profile complete' )" />
<x-artisanpack-progress-radial indeterminate :size="48" :aria-label="__( 'Calculating' )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | number | `0` | The progress, from 0 to `max`. Clamped. |
| `max` | number | `100` | The value at completion. |
| `indeterminate` | bool | `false` | A quarter arc spins; `aria-valuenow` is left out. |
| `size` | number | `64` | The ring's diameter in px. |
| `thickness` | number | `6` | The ring's stroke width in px. |
| `color` | semantic color | `primary` | Emitted as `data-color`. |
| `label` | string | none | A caption under the value that names the progressbar. |
| `value-text` | string | none | Shown in the center instead of the percentage, and read as `aria-valuetext`. |
| `hide-value` | bool | `false` | Leaves the center empty; the label still names the progressbar (`aria-label`). |
| `classes` | array | `[]` | Extra classes per part: `root`, `svg`, `track`, `bar`, `value`, `label`. |

## Accessibility

- The root is `role=progressbar`; without a `label`, name it with `aria-label`.
- The ring fills in the reading direction, counterclockwise in RTL.

## Upgrading from 2.x

- `unit` is gone: use `value-text` for a value other than a percentage. The DaisyUI `radial-progress` markup is replaced by an SVG ring.
