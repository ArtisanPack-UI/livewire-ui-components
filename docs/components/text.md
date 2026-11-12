---
title: Text Component
---

# Text Component

Body text with a size, tone and weight, built on the ArtisanPack UI design system (`.apui-text`). It can cut to one line or to a number of lines, and paragraphs keep to the reading measure.

## Basic Usage

```blade
<x-artisanpack-text size="lead">{{ __( 'Twenty-four bright apartments around a garden courtyard.' ) }}</x-artisanpack-text>
<x-artisanpack-text size="sm" tone="muted">{{ __( 'Updated 2 hours ago' ) }}</x-artisanpack-text>
<x-artisanpack-text as="span" tone="success" weight="medium">{{ __( '+10.9% vs last month' ) }}</x-artisanpack-text>
```

## Truncate and clamp

```blade
<x-artisanpack-text truncate>{{ $transaction->description }}</x-artisanpack-text>
<x-artisanpack-text :clamp="2">{{ $listing->summary }}</x-artisanpack-text>
```

Plain-text content is copied to `title`, so the full value stays available. Give rich content its own `title` or a tooltip.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `as` | `p` · `span` · `div` | `p` | The element. |
| `size` | `xs` · `sm` · `md` · `lg` · `lead` | `md` | 12/16, 13/18, 14/20, 16/24, or lead (19/28, muted unless a tone is set). Emitted as `data-size`. |
| `tone` | `default` · `muted` · `success` · `warning` · `error` | `default` | Emitted as `data-tone`. |
| `weight` | `regular` · `medium` · `semibold` | `regular` | Emitted as `data-weight`. |
| `truncate` | bool | `false` | Cuts to one line. |
| `clamp` | int | none | Cuts after this many lines (`--apui-text-clamp`). |
| `classes` | array | `[]` | Extra classes: `root`. |

## Upgrading from 2.x

- `size` takes a step, not a Tailwind class; `lead` is a size.
- `muted` is `tone="muted"`; `semibold` and `bold` are `weight`.
- `color`, `center` and `prose` are gone, and Text no longer adds a bottom margin.

## Accessibility

- Every tone keeps 4.5:1. Status tones are never the only signal; muted is not for anything people must read.
