---
title: Badge Component
---

# Badge Component

A small, non-interactive label for a status, category or count, built on the ArtisanPack UI design system (`.apui-badge`). The text is always there; a dot or an icon can lead it.

## Basic Usage

```blade
<x-artisanpack-badge color="success">{{ __( 'Paid' ) }}</x-artisanpack-badge>
<x-artisanpack-badge color="info" variant="solid">{{ __( 'New' ) }}</x-artisanpack-badge>
<x-artisanpack-badge color="warning" variant="outline">{{ __( 'Pending' ) }}</x-artisanpack-badge>
<x-artisanpack-badge color="error" variant="ghost" dot>{{ __( 'Overdue' ) }}</x-artisanpack-badge>
```

Use the same color for the same status everywhere. `soft` is the default; `solid` is for counts and emphasis, `outline` and `ghost` for quieter labels.

## Dot, icon and size

```blade
<x-artisanpack-badge color="success" dot>{{ __( 'Active' ) }}</x-artisanpack-badge>
<x-artisanpack-badge color="success" leading-icon="arrow-right" size="sm">+11.01%</x-artisanpack-badge>
<x-artisanpack-badge color="primary" size="lg">{{ __( 'Pro' ) }}</x-artisanpack-badge>
```

The dot and the icon are decorative. Arrow and chevron icons mirror in right-to-left layouts. The `icon` slot overrides `leading-icon`.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `variant` | `solid` · `soft` · `outline` · `ghost` | `soft` | Visual treatment. Emitted as `data-variant`. |
| `color` | `primary` · `secondary` · `accent` · `neutral` · `success` · `warning` · `error` · `info` | `neutral` | Semantic color. Emitted as `data-color`. |
| `size` | `sm` · `md` · `lg` | `md` | Height: 18, 20 or 24px. Emitted as `data-size`. |
| `dot` | bool | `false` | A decorative dot before the label. |
| `leading-icon` | string | none | An icon name before the label. |
| `classes` | array | `[]` | Extra classes per part: `root`, `dot`, `icon`, `label`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- Put the text in the slot. `value` still renders as the label when the slot is empty, but is deprecated.
- `color` takes the eight semantic names only. Tailwind names and hex codes fall back to `neutral`: set a one-off color through `classes` or a custom property instead.
- `color-adjustment`, `glass`, `glass-tint` and `glass-tint-opacity` are gone.

## Accessibility

- Never interactive: use a Button or a filter chip for an action.
- The label is always present; the dot and the icon are `aria-hidden`, so color is never the only signal.
- In high contrast, soft badges gain an edge; in forced colors every badge keeps one.
