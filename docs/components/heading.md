---
title: Heading Component
---

# Heading Component

A title from the type scale, built on the ArtisanPack UI design system (`.apui-heading`). `level` sets the semantic `h1`–`h6` and `size` the visual size, so the outline stays right whatever the design calls for.

## Basic Usage

```blade
<x-artisanpack-heading level="1">{{ __( 'Properties' ) }}</x-artisanpack-heading>
<x-artisanpack-heading level="3" size="5">{{ __( 'Recent activity' ) }}</x-artisanpack-heading>
```

## Eyebrow

```blade
<x-artisanpack-heading :eyebrow="__( 'Portfolio' )">{{ __( 'Every property, one calm place' ) }}</x-artisanpack-heading>
<x-artisanpack-text size="lead">{{ __( 'Track visits, deals and revenue across your listings.' ) }}</x-artisanpack-text>
```

The eyebrow renders as a paragraph just before the heading, so it never adds a level. The `eyebrow` slot takes rich content.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `level` | 1–6 | `2` | The semantic level. Emitted as `data-level`. |
| `size` | 1–6 | `level` | The visual size: 34/40, 28/34, 23/30, 19/26, 16/24 or 14/20. Emitted as `data-size`. |
| `eyebrow` | string | none | A short uppercase section label. |
| `classes` | array | `[]` | Extra classes per part: `root`, `eyebrow`. |

Every other attribute lands on the heading.

## Upgrading from 2.x

- The default level is now 2, not 1: keep one `h1` per page and set `level="1"` on it.
- `size` takes a step from the type scale, not a Tailwind class.
- `color`, `semibold`, `bold`, `extrabold` and `center` are gone; headings are 600 weight. Use `classes` for one-offs.
- `subheading` is a deprecated alias of `<x-artisanpack-text size="lead">`.

## Accessibility

- A native heading element at `level`; `size` only changes how it looks.
- Keep one `h1` per page and don't skip levels.
