---
title: Navbar Component
---

# Navbar Component

The top bar, built on the ArtisanPack UI design system: a brand, primary links or a search, and actions at the end, as a full-width bar or a rounded island. Navbar replaces the deprecated [Nav](nav).

## Basic Usage

```blade
<x-artisanpack-navbar>
	<x-slot:brand>
		<a href="{{ route( 'home' ) }}">Horizon</a>
	</x-slot:brand>

	<x-artisanpack-navbar-item :href="route( 'properties' )" :current="request()->routeIs( 'properties' )">{{ __( 'Properties' ) }}</x-artisanpack-navbar-item>
	<x-artisanpack-navbar-item :href="route( 'analytics' )" :current="request()->routeIs( 'analytics' )">{{ __( 'Analytics' ) }}</x-artisanpack-navbar-item>

	<x-slot:actions>
		<x-artisanpack-button variant="ghost" color="neutral" size="sm" :href="route( 'login' )">{{ __( 'Sign in' ) }}</x-artisanpack-button>
		<x-artisanpack-button size="sm" :href="route( 'register' )">{{ __( 'Get started' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-navbar>
```

`navbar-item` children in the default slot sit in a nav landmark named "Main" (translated; change it with `label`). `links` renders the same links from data, before any children: `:links="[ [ 'label' => __( 'Properties' ), 'href' => route( 'properties' ), 'current' => true ] ]"`.

## Island on glass

```blade
<x-artisanpack-navbar layout="island" surface="glass">
	<x-slot:brand>Socials</x-slot:brand>
	<x-slot:search>
		<x-artisanpack-input type="search" size="sm" leading-icon="search" :placeholder="__( 'Search' )" :aria-label="__( 'Search' )" wire:model.live.debounce="query" />
	</x-slot:search>
	<x-slot:actions>
		<x-artisanpack-button size="sm" leading-icon="plus" wire:click="create">{{ __( 'New page' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-navbar>
```

Use the island inside an app shell and the bar on marketing pages. Glass needs a backdrop worth showing; it falls back to an opaque surface under reduced transparency, without `backdrop-filter` and in high contrast.

## Props

### navbar

| Prop | Type | Default | Description |
|---|---|---|---|
| `layout` | `bar` · `island` | `bar` | Emitted as `data-layout`. |
| `surface` | `default` · `glass` | `default` | Emitted as `data-surface`. |
| `sticky` | bool | `true` | Sticks to the top of the scrolling container. Emitted as `data-sticky`. |
| `brand` | string or slot | none | The brand at the start. |
| `links` | array | `[]` | The links as data: `{ label, href, current }`. |
| `search` | string or slot | none | A search field that takes the free space. |
| `actions` | string or slot | none | Controls at the end. |
| `label` | string | `__( 'Main' )` | Names the links' nav landmark. |
| `classes` | array | `[]` | Extra classes per part: `root`, `brand`, `links`, `link`, `search`, `actions`. |

### navbar-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `href` | string | none | The link. |
| `current` | bool | `false` | The current page: `aria-current="page"`. |
| `label` | string | none | The label when there's no slot. |
| `navigate` | bool | `true` | Adds `wire:navigate` to internal links. Off-site, `#` and `mailto:` links load normally. |

## Responsive behavior

On a narrow bar, the links scroll along the inline axis rather than wrapping or hiding, so every link stays reachable; the search shrinks and the brand and actions keep their size. Keep to about six links.

## Accessibility

- A `header` element, with the links in a nav landmark; the current link sets `aria-current="page"` and is shown by surface, not color alone.
- Name icon-only actions with `aria-label`, and put a notification count in that name ("Notifications, 3 unread").
- Links show the focus ring on `:focus-visible`.

## Upgrading from Nav

`nav` still renders and is deprecated. Its `brand` and `actions` slots carry over; `full-width` is gone (put the bar in your own container), and `sticky` now defaults to true.

## Related Components

- [Menu](menu) for side navigation
- [DropdownMenu](dropdown-menu) for an account menu in the actions
