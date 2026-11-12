---
title: AppShell Component
---

# AppShell Component

The application frame, built on the ArtisanPack UI design system: a sidebar and a main column as islands separated by a gutter. The sidebar holds a brand, the navigation (a [Menu](menu)) and a footer, and can collapse to icons. The main column holds a top bar (usually a [Navbar](navbar) island) and the page. AppShell replaces the deprecated [Main](main).

## Basic Usage

```blade
<x-artisanpack-app-shell collapsible>
	<x-slot:brand>
		<a href="{{ route( 'dashboard' ) }}">Apexify</a>
	</x-slot:brand>

	<x-slot:sidebar>
		<x-artisanpack-menu :label="__( 'Main' )">
			<x-artisanpack-menu-item :title="__( 'Dashboard' )" icon="house" :href="route( 'dashboard' )" />
			<x-artisanpack-menu-item :title="__( 'Settings' )" icon="settings" :href="route( 'settings' )" />
		</x-artisanpack-menu>
	</x-slot:sidebar>

	<x-slot:footer>
		<x-artisanpack-avatar :title="auth()->user()->name" size="sm" />
	</x-slot:footer>

	<x-slot:header>
		<x-artisanpack-navbar layout="island" :sticky="false">…</x-artisanpack-navbar>
	</x-slot:header>

	{{ $slot }}
</x-artisanpack-app-shell>
```

Give the frame a full-height parent (`min-height: 100dvh` on the body or a wrapper): the root fills its parent's height.

## Collapsing

`collapsible` adds a button at the foot of the sidebar. Collapsed, a Menu in the sidebar shows only its icons and keeps the labels for screen readers. `wire:model` binds `collapsed` to a Livewire property; without it, the state is kept in the session through the `artisanpack.toogle-sidebar` route, as 2.x did. Each click dispatches `collapsed-change` (`$event.detail.collapsed`).

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `collapsed` | bool | `false` | Shrinks the sidebar to its icons. Emitted as `data-collapsed`. |
| `collapsible` | bool | `false` | Adds the collapse button. Emitted as `data-collapsible`. |
| `sidebar-width` | string | `248px` | Sets `--apui-sidebar-width`. |
| `gutter` | string | `12px` | Sets `--apui-shell-gutter`. |
| `brand` | string or slot | none | The brand at the top of the sidebar. |
| `sidebar` | string or slot | none | The navigation, usually a Menu. |
| `header` | string or slot | none | The top bar of the main column. |
| `footer` | string or slot | none | The foot of the sidebar. |
| `label` | string | `__( 'Sidebar' )` | Names the sidebar's landmark. |
| `skip-label` | string | `__( 'Skip to main content' )` | The skip link's text. |
| `collapse-label` | string | `__( 'Collapse sidebar' )` | Names the collapse button. |
| `classes` | array | `[]` | Extra classes per part: `root`, `skip-link`, `sidebar`, `brand`, `footer`, `collapse-trigger`, `main`, `content`. |

## Accessibility

- The sidebar is an `aside` named with `label`; the page is a `main` landmark. A skip link to it is the first focusable element and shows while focused.
- The collapse button is `aria-pressed` while collapsed.
- Logical properties put the sidebar at the inline end in RTL; on a narrow viewport the islands stack.
- Keep 5 to 8 top-level items; group the rest under section titles.
