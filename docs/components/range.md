---
title: Range Component
---

# Range Component

A slider for one value, or two thumbs for a from–to range, built on the ArtisanPack UI design system. Each thumb is a native `<input type="range">` laid over the drawn track, so the keyboard, touch and screen readers get the platform slider. The fill and thumbs follow text direction: under RTL the track fills from the right.

## Basic Usage

```blade
<x-artisanpack-range
	wire:model.live="radius"
	:label="__( 'Search radius' )"
	:max="50"
	format="{value} km"
	:marks="[ [ 'value' => 0, 'label' => '0 km' ], [ 'value' => 25, 'label' => '25 km' ], [ 'value' => 50, 'label' => '50 km' ] ]"
/>
```

With one thumb, `wire:model` binds the native input. `format` is a template where `{value}` is the number; it's used in the bubble and in `aria-valuetext`.

## Two thumbs

```blade
{{-- wire:model binds the [from, to] array. --}}
<x-artisanpack-range
	wire:model.live="price"
	:label="__( 'Price' )"
	dual
	:min="100"
	:max="700"
	:step="10"
	format="${value}k"
	show-value="always"
/>
```

With `dual`, the root is `x-modelable`, so `wire:model` binds an array `[from, to]`, and the thumbs can't cross. Each thumb is named by the label plus "Minimum" or "Maximum" (`thumb-labels`). Other attributes land on both inputs; pass `name="price[]"` for a plain form submit.

## Colors, sizes and states

```blade
<x-artisanpack-range :label="__( 'Volume' )" :value="80" color="success" />
<x-artisanpack-range :label="__( 'Opacity' )" :value="30" size="sm" />
<x-artisanpack-range :label="__( 'Locked' )" :value="50" disabled />
```

Pair a slider with a number input when exact values matter.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `min` · `max` · `step` | number | `0` · `100` · `1` | The range and step. |
| `value` | number, or `[from, to]` with `dual` | midpoint | The value. `wire:model` binds it instead. |
| `dual` | bool | `false` | Two thumbs. Emitted as `data-dual`. |
| `show-value` | `never` · `focus` · `always` | `focus` | When the value bubble shows. Emitted as `data-show-value`. |
| `format` | string | none | The value template, such as `{value} km`. |
| `marks` | array | `[]` | `[ [ 'value' => …, 'label' => … ] ]`, placed at their values under the track. |
| `color` | semantic color | `primary` | The fill and thumb edge. Emitted as `data-color`. |
| `size` | `sm` · `md` | `md` | Thumb size. Emitted as `data-size`. |
| `label` · `hint` | string or slot | none | As Input. |
| `error` | string | first `$errors` message | Validation message. |
| `invalid` · `disabled` | bool | `false` | As Input. |
| `thumb-labels` | array | `__( 'Minimum' )`, `__( 'Maximum' )` | With `dual`, the `start` and `end` thumb names. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `track`, `fill`, `control`, `thumb`, `bubble`, `marks`, `mark`, `hint`, `error`. |

## Accessibility

- Arrow keys step (ArrowRight follows text direction), PageUp/PageDown take larger steps, Home/End jump to the ends.
- The thumb is 18px (14px small) with a 24px hit area, and shows the focus ring while its input has `:focus-visible`.
