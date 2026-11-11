---
title: Breadcrumbs Component
---

# Breadcrumbs Component

Shows where the current page sits in the hierarchy, built on the ArtisanPack UI design system (`.apui-breadcrumbs`): a navigation landmark holding an ordered list. The last item is the current page; it has `aria-current="page"` and is never a link.

## Basic Usage

```blade
<x-artisanpack-breadcrumbs :items="[
	[ 'label' => __( 'Dashboard' ), 'href' => route( 'dashboard' ) ],
	[ 'label' => __( 'Properties' ), 'href' => route( 'properties.index' ) ],
	[ 'label' => $property->name ],
]" />
```

Start the trail at the section, not always at Home. Don't use Breadcrumbs for steps or history.

## Home icon and overflow

`icon` shows a registry icon before the label; `hideLabel` keeps the label for screen readers only. Past `max-items` (4 by default) the middle levels collapse behind an overflow button. Choosing it reveals them in place and moves focus to the first one. `:max-items="0"` never collapses.

```blade
<x-artisanpack-breadcrumbs :max-items="4" :items="[
	[ 'label' => __( 'Home' ), 'href' => '/', 'icon' => 'house', 'hideLabel' => true ],
	[ 'label' => __( 'Properties' ), 'href' => '/properties' ],
	[ 'label' => __( 'Lakeview Lofts' ), 'href' => '/properties/lakeview-lofts' ],
	[ 'label' => __( 'Analytics' ), 'href' => '/properties/lakeview-lofts/analytics' ],
	[ 'label' => __( 'Visitor' ) ],
]" />
```

## Composed

`breadcrumbs-item` children render the same levels as `items`, after them, so a level can hold markup or a `wire:click`. Attributes on an item land on its link. The 2.x form, `<x-artisanpack-link>` children in the slot, still renders as levels.

```blade
<x-artisanpack-breadcrumbs>
	<x-artisanpack-breadcrumbs-item href="#" wire:click.prevent="setFolder( null )" :label="__( 'All media' )" />
	<x-artisanpack-breadcrumbs-item :label="$folder->name" />
</x-artisanpack-breadcrumbs>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `items` | array | `[]` | The trail: `[ 'label' => …, 'href' => …, 'icon' => …, 'hideLabel' => … ]`. The 2.x `link` key still works for `href`. |
| `max-items` | int | `4` | How many entries show before the middle collapses: the first item, the overflow button and the last `max-items - 2`. |
| `label` | string | `__( 'Breadcrumb' )` | The navigation landmark's name. |
| `overflow-label` | string | `__( 'Show {count} more levels' )` | The overflow button's name; `{count}` is the number of hidden levels. |
| `navigate` | bool | `true` | Adds `wire:navigate` to every internal link. Off-site, `#` and `mailto:` links load normally. |
| `classes` | array | `[]` | Extra classes per part: `root`, `list`, `item`, `link`, `separator`, `current`, `overflow`. |

### breadcrumbs-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string | none | The level's name. The default slot overrides it. |
| `href` | string | none | Where it links. Ignored on the last level. |
| `icon` | string | none | An icon name before the label. |
| `hide-label` | bool | `false` | Keeps the label for screen readers only. |

Every other attribute on Breadcrumbs lands on the `<nav>`.

## Upgrading from 2.x

- Internal links still add `wire:navigate` by default. `no-wire-navigate` still turns it off but is deprecated: use `:navigate="false"`.
- `separator`, `link-item-class`, `text-item-class`, `icon-class` and `separator-class` are gone; use `classes`. Item tooltips are gone.
- `<x-artisanpack-link>` children in the slot still work; prefer `breadcrumbs-item`.

## Accessibility

- A `nav` landmark named "Breadcrumb", with an ordered list.
- The current page has `aria-current="page"` and isn't a link.
- Separators are `aria-hidden` and mirror in right-to-left layouts.
- The overflow button names how many levels it hides and sets `aria-expanded`.
