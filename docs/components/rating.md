---
title: Rating Component
---

# Rating Component

Shows or collects a star score, built on the ArtisanPack UI design system. Read-only, it is one image with a text alternative ("4.2 out of 5") and supports half stars. As an input, it is a group of native radios, one per star, so arrow keys change the score and follow the text direction; the stars mirror under RTL.

## Display

```blade
<x-artisanpack-rating readonly :value="4.2" :count="17" />
```

## Input

```blade
<x-artisanpack-rating wire:model.live="score" size="lg" :label="__( 'Rate the viewing' )" />
```

`wire:model` lands on every radio and binds natively. The filled stars follow the native `:checked` state, so they update before a deferred round trip.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | number | none | The score. Read-only, it rounds to the nearest half star. |
| `max` | int | `5` | The number of stars. |
| `readonly` | bool | `false` | Shows the score instead of collecting it. Emitted as `data-readonly`. |
| `disabled` | bool | `false` | Disables the radios. |
| `size` | `sm` · `md` · `lg` | `md` | Emitted as `data-size`. |
| `count` | int | none | The number of reviews, shown after the value. |
| `label` | string | `__( 'Rating' )` | The input's accessible name. |
| `value-label` | string | `__( ':value out of :max' )` | The text alternative and each radio's name, with `:value` and `:max`. |
| `count-label` | string | `(:count review)` / `(:count reviews)` | The count text, with `:count`. Translated with `trans_choice()` by default. |
| `classes` | array | `[]` | Extra classes per part: `root`, `item`, `control`, `star`, `half`, `value`, `count`. |

## Accessibility

- Read-only: `role="img"` with the value label; the stars are decorative.
- Input: `role="radiogroup"` of native radios sharing one `name`; each radio is named "3 out of 5".
- Every label is translatable.
