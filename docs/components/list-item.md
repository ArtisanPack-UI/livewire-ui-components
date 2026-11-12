---
title: ListItem Component
---

# ListItem Component

A row with leading, title, description and trailing parts, built on the ArtisanPack UI design system (`.apui-list-item`). Rows go in a List (`.apui-list`), which frames them (`bordered`) or rules them (`divided`).

## Basic Usage

```blade
<x-artisanpack-list variant="bordered">
	@foreach ( $members as $member )
		<x-artisanpack-list-item wire:key="member-{{ $member->id }}" :title="$member->name" :description="$member->email" :href="route( 'members.show', $member )">
			<x-slot:leading><x-artisanpack-avatar :name="$member->name" :src="$member->avatar_url" /></x-slot:leading>
			<x-slot:trailing><x-artisanpack-badge>{{ $member->role }}</x-artisanpack-badge></x-slot:trailing>
		</x-artisanpack-list-item>
	@endforeach
</x-artisanpack-list>
```

## Interactive rows

`href` makes the title a link and `interactive` a button; either covers the whole row, so the row is one target, and controls in `trailing` stay clickable above it. `wire:click` on an interactive row runs when its button is pressed. `selected` marks the current row.

```blade
<x-artisanpack-list variant="divided">
	<x-artisanpack-list-item interactive selected wire:click="open( 1 )" leading-icon="mail" :title="__( 'Offer received' )" description="Lakeview Lofts · $248,000" trailing="2m" />
	<x-artisanpack-list-item interactive wire:click="open( 2 )" leading-icon="calendar" :title="__( 'Viewing booked' )" description="Thu, Nov 12 · 11:00" trailing="1h" />
</x-artisanpack-list>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string | none | The main text. The `title` slot (or the default slot) overrides it. |
| `description` | string | none | Secondary text. The `description` slot overrides it. |
| `href` | string | none | Makes the title a link covering the row. |
| `interactive` | bool | `false` | Without `href`, makes the title a button covering the row. |
| `selected` | bool | `false` | `data-selected`, and `aria-current` on the row's control. |
| `disabled` | bool | `false` | Dims the row and disables its control. |
| `leading-icon` | string | none | An icon name in the leading part. The `leading` slot overrides it. |
| `trailing` | string | none | Short text at the end. The `trailing` slot overrides it. |
| `navigate` | bool | `true` | Adds `wire:navigate` to an internal `href`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `leading`, `title`, `description`, `trailing`. |

`<x-artisanpack-list>` takes `variant` (`plain` · `bordered` · `divided`, default `plain`) and `ordered` (an `ol`). Rows inside it render as `li`; on their own, as `div`.

## Upgrading from 2.x

- `item`, `value`, `sub-value` and `avatar` still fill the row from an item, deprecated: pass `title`, `description` and a `leading` slot.
- `link` is `href`. The separator and hover are the List's `variant` and the row's `interactive`; `no-separator` and `no-hover` are gone.
- The icon props (`icon`, `icon-type`, `icon-status`, `icon-class`, `no-icon`) are `leading-icon` or the `leading` slot.
- The `actions` slot is `trailing`.

## Accessibility

- Interactive rows are links or buttons, never a clickable `li`; selection sets `aria-current`.
- The focus ring is drawn inside the row. Disabled rows set `aria-disabled`.
