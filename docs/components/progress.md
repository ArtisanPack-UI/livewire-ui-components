---
title: Progress Component
---

# Progress Component

Linear progress, built on the ArtisanPack UI design system (`.apui-progress`): determinate, indeterminate or segmented into steps, with an optional label and value above the track.

## Basic Usage

```blade
<x-artisanpack-progress :value="$uploaded / 12 * 100" :label="__( 'Uploading 12 photos' )" :value-text="__( ':done of :total', [ 'done' => $uploaded, 'total' => 12 ] )" />
<x-artisanpack-progress :value="40" size="sm" :aria-label="__( 'Profile completeness' )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | number | `0` | The progress, from 0 to `max`. Clamped. |
| `max` | number | `100` | The value at completion; with `segmented`, the number of segments. |
| `indeterminate` | bool | `false` | The amount isn't known: a short bar slides along the track. `aria-valuenow` is left out. |
| `segmented` | bool | `false` | One bar per segment, `value` of them done. |
| `size` | `sm` · `md` · `lg` | `md` | The track's height. Emitted as `data-size`. |
| `color` | semantic color | `primary` | Emitted as `data-color`. Use `error` only when something is wrong. |
| `label` | string | none | Shown above the track; names the progressbar (`aria-labelledby`). |
| `value-text` | string | none | The value in words, such as "8 of 12": shown in the head and read as `aria-valuetext`. Without it a labeled bar shows the percentage. |
| `classes` | array | `[]` | Extra classes per part: `root`, `head`, `label`, `value`, `track`, `bar`. |

## Indeterminate and segmented

```blade
<x-artisanpack-progress indeterminate :label="__( 'Preparing export' )" :value-text="__( 'Estimating…' )" />
<x-artisanpack-progress segmented :value="3" :max="5" :label="__( 'Listing setup' )" />
```

## Accessibility

- The root is `role=progressbar` with `aria-valuemin`, `aria-valuemax` and `aria-valuenow`. Without a `label`, name it with `aria-label`.
- The track has an edge so it shows at 3:1; the animation stops under reduced motion.

## Upgrading from 2.x

- It no longer renders a native `<progress>`; DaisyUI classes such as `progress-primary` become `color="primary"`.
