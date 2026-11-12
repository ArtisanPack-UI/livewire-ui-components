---
title: Timeline Component
---

# Timeline Component

Events in order, each with an icon or dot marker, built on the ArtisanPack UI design system (`ol.apui-timeline`). Compose it from `timeline-item` children, or pass the same events as `items`. For steps still to come, use Steps.

## Basic Usage

```blade
<x-artisanpack-timeline :label="__( 'Activity' )">
	<x-artisanpack-timeline-item icon="circle-check" color="success" :time="__( '2h ago' )" datetime="2026-11-12T08:00" :description="__( 'Phoenix Baker accepted $248,000.' )">
		<strong>{{ __( 'Offer accepted' ) }}</strong> {{ __( 'on Lakeview Lofts' ) }}
	</x-artisanpack-timeline-item>
	<x-artisanpack-timeline-item icon="message-square" :time="__( '5h ago' )">
		<strong>Olivia Rhye</strong> {{ __( 'commented' ) }}
		<x-slot:card>{{ $comment->body }}</x-slot:card>
	</x-artisanpack-timeline-item>
</x-artisanpack-timeline>
```

An item's default slot is its head (or pass `title`), `description` is the muted body, and the `card` slot or prop a sunken box, such as a comment.

## Dot markers, as data

`variant="dot"` draws small dots for a compact status history. `items` renders the same children, before any composed ones:

```blade
<x-artisanpack-timeline variant="dot" :items="[
	[ 'title' => __( 'Delivered' ), 'time' => 'Nov 7, 14:02', 'datetime' => '2026-11-07T14:02', 'color' => 'success' ],
	[ 'title' => __( 'Out for delivery' ), 'time' => 'Nov 7, 08:15', 'color' => 'primary' ],
	[ 'title' => __( 'Shipped from Madrid' ), 'time' => 'Nov 6, 09:10' ],
]" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `items` | array | `[]` | The events as data: `title`, `time`, `datetime`, `icon`, `color`, `description`, `card`. |
| `variant` | string | `icon` | `icon` or `dot`. Dots leave the icons out. |
| `label` | string | none | The list's accessible name. |
| `classes` | array | `[]` | Extra classes per part, its items' included: `root`, `item`, `marker`, `head`, `time`, `body`, `card`. |

### timeline-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string | none | What happened. The default slot replaces it. |
| `time` | string | none | When, as shown ("2h ago"). |
| `datetime` | string | none | When, machine-readable (ISO 8601), for the `time` element. |
| `icon` | string | none | A registry icon for the marker. |
| `color` | string | none | Tints the marker: one of the eight semantic colors. |
| `description` | string | none | Muted detail under the head. |
| `card` | string | none | A sunken box under the head. The `card` slot replaces it. |

Every other attribute on Timeline lands on the `<ol>`, and on an item on its `<li>`.

## Upgrading from 2.x

- Wrap `timeline-item` children in `<x-artisanpack-timeline>`: an item renders an `<li>`.
- `subtitle` still works, as `time`. `first`, `last` and `pending` are ignored: the list draws its own line.

## Accessibility

- An ordered list, so the count and position are announced.
- Times are `time` elements, with `datetime` when given.
- Markers are decorative and hidden: say what happened in the head, not only with an icon or color.
