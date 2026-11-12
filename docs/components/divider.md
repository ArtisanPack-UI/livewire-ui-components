---
title: Divider Component
---

# Divider Component

A rule that separates content, built on the ArtisanPack UI design system: horizontal, vertical or with a label in the middle. Prefer spacing; add a divider only when groups blur together. Divider replaces the deprecated [Separator](separator).

## Basic Usage

```blade
<x-artisanpack-divider />

<x-artisanpack-divider :label="__( 'or continue with' )" />

<x-artisanpack-divider align="start">{{ __( 'Older activity' ) }}</x-artisanpack-divider>
```

In a toolbar, a vertical divider stretches to the row's height:

```blade
<div style="display: flex; gap: 8px">
	<x-artisanpack-button variant="ghost" size="sm">{{ __( 'Edit' ) }}</x-artisanpack-button>
	<x-artisanpack-divider orientation="vertical" />
	<x-artisanpack-button variant="ghost" size="sm">{{ __( 'Duplicate' ) }}</x-artisanpack-button>
</div>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `orientation` | `horizontal` · `vertical` | `horizontal` | Emitted as `data-orientation`. |
| `label` | string or default slot | none | Text in the middle of the rule. |
| `align` | `start` · `center` · `end` | `center` | Where a label sits. Emitted as `data-align`. |
| `strength` | `subtle` · `strong` | `subtle` | The muted or the default border. Emitted as `data-strength`. |
| `decorative` | bool | `false` | Hides the divider from assistive technology. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`. |

## Accessibility

- A plain rule is an `<hr>`; a vertical or labelled one is `role="separator"` with `aria-orientation`, named by its label.
- A decorative divider is `aria-hidden`; a decorative labelled one is `role="none"`, so its text reads as text.
