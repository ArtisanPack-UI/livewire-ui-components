---
title: Menu Component
---

# Menu Component

A navigation menu built on the ArtisanPack UI design system: links with icons and badges, section titles, separators and collapsible sub-menus, in a nav landmark, with the current page marked. Menu is for navigation; for actions, use [DropdownMenu](dropdown-menu).

Menu isn't an ARIA menu and needs no behavior widget: links are links, and a sub-menu is a disclosure, a button with `aria-expanded` that opens and closes its list with plain Alpine state.

## Basic Usage

```blade
<x-artisanpack-menu :label="__( 'Main' )" activate-by-route>
	<x-artisanpack-menu-item :href="route( 'dashboard' )" icon="list">{{ __( 'Dashboard' ) }}</x-artisanpack-menu-item>
	<x-artisanpack-menu-sub :label="__( 'Properties' )" icon="settings">
		<x-artisanpack-menu-item :href="route( 'properties.index' )">{{ __( 'All properties' ) }}</x-artisanpack-menu-item>
		<x-artisanpack-menu-item :href="route( 'properties.drafts' )" badge="3">{{ __( 'Drafts' ) }}</x-artisanpack-menu-item>
	</x-artisanpack-menu-sub>
	<x-artisanpack-menu-item :href="route( 'inbox' )" icon="mail" :badge="$unread">{{ __( 'Inbox' ) }}</x-artisanpack-menu-item>
	<x-artisanpack-menu-title>{{ __( 'Settings' ) }}</x-artisanpack-menu-title>
	<x-artisanpack-menu-item :href="route( 'team' )" icon="user">{{ __( 'Team' ) }}</x-artisanpack-menu-item>
	<x-artisanpack-menu-separator />
	<x-artisanpack-menu-item wire:click="logout">{{ __( 'Log out' ) }}</x-artisanpack-menu-item>
</x-artisanpack-menu>
```

The current page's item sets `aria-current="page"`: pass `current`, or put `activate-by-route` on the menu (or an item) to match each item's `href` or `route` against the URL, as a prefix or, with `exact`, exactly. A sub-menu holding the current page renders open. An item without `href` is a button, for `wire:click`.

## Items as data

`items` renders the same children as composition, before any children:

```blade
<x-artisanpack-menu :label="__( 'Settings' )" :collapsible="false" :items="[
	[ 'label' => __( 'Account' ), 'items' => [
		[ 'label' => __( 'Profile' ), 'href' => route( 'settings.profile' ), 'current' => request()->routeIs( 'settings.profile' ) ],
		[ 'label' => __( 'Security' ), 'href' => route( 'settings.security' ) ],
	] ],
	[ 'type' => 'separator' ],
	[ 'label' => __( 'Billing' ), 'href' => route( 'settings.billing' ), 'icon' => 'lock' ],
]" />
```

With `:collapsible="false"`, every sub-menu shows, under its item as a plain heading.

## Props

### menu

| Prop | Type | Default | Description |
|---|---|---|---|
| `items` | array | `[]` | The menu as data: `{ label, href, icon, badge, current, disabled }`, `{ label, icon, open, items }` for a sub-menu, `{ type: 'title', label }` and `{ type: 'separator' }`. |
| `collapsible` | bool | `true` | Sub-menus open and close. Emitted as `data-collapsible`. |
| `label` | string | none | Names the nav landmark. Name every menu when a page has more than one nav. |
| `classes` | array | `[]` | Extra classes per part, children included: `root`, `list`, `item`, `icon`, `itemText`, `badge`, `chevron`, `sub`, `title`, `separator`. |

`activate-by-route` is an attribute on the menu, read by its items.

### menu-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `href` | string | none | The link. Without it, the item is a button. |
| `route` | string | none | A route name, for `href` and for matching the URL. |
| `label` | string | none | The label when there's no slot. |
| `icon` | string | none | An icon name from the registry. |
| `badge` | string or int | none | A count or status at the end. |
| `current` | bool | unset | The current page. Unset, `activate-by-route` decides. |
| `disabled` | bool | `false` | No href, `aria-disabled`, out of the Tab order. |
| `external` | bool | `false` | Opens in a new tab. |
| `navigate` | bool | `true` | Adds `wire:navigate` to internal links. |
| `exact` | bool | `false` | Match the URL exactly with `activate-by-route`. |

### menu-sub

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string | none | The toggle's label. |
| `icon` | string | none | An icon name before the label. |
| `open` | bool | `false` | Renders it open. |
| `disabled` | bool | `false` | Disables the toggle. |

### menu-title and menu-separator

`menu-title` takes its text from the slot or `label`. `menu-separator` takes no props and is hidden from assistive technology.

## Livewire

A sub-menu's toggle and list keep the state a person left them in through re-renders: Alpine owns their `aria-expanded` and `hidden` (`wire:ignore.self`), and the list's id is stable (`<menu id>-sub-<n>`).

## Accessibility

- A nav landmark, named with `label`; the current page sets `aria-current="page"` and is shown by surface, not color alone.
- Sub-menus use the disclosure pattern: a button with `aria-expanded` and `aria-controls`. Tab moves through links and toggles; Enter and Space toggle.
- The chevron mirrors in right-to-left pages and is hidden from assistive technology.
- Keep nesting to two levels.

## Upgrading from 2.x

- `title` (items, subs, titles), `link` and `active` still work; prefer `label` or the slot, `href` and `current`. `no-wire-navigate` still works; prefer `:navigate="false"`.
- The menu's own `title`, `icon` and `separator` props are gone: add a `menu-title` or `menu-separator` child.
- The DaisyUI and color props (`bg-color`, `active-bg-color`, `icon-classes`, `badge-classes`, glass) are gone: use `classes` or the design tokens.
- `spinner` is gone; `role="menuitem"` is no longer rendered, since this isn't an ARIA menu.

## Related Components

- [DropdownMenu](dropdown-menu) for actions
- [Navbar](navbar) for the top bar
