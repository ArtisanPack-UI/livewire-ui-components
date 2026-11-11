---
title: Tabs Component
---

# Tabs Component

Switches between views of the same content, built on the ArtisanPack UI design system: a tablist of triggers and one panel per tab. The arrow keys move between tabs and follow the text direction, so in a right-to-left page ArrowLeft moves to the next tab. Activation is automatic (the arrow keys select) or manual (they move focus; Enter or Space selects).

The interaction comes from the design system's behavior engine (`apuiTabs`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-tabs wire:model.live="section" :label="__( 'Account' )">
	<x-artisanpack-tab value="profile" :label="__( 'Profile' )" icon="user">
		<livewire:account.profile />
	</x-artisanpack-tab>
	<x-artisanpack-tab value="notifications" :label="__( 'Notifications' )" icon="mail">
		<livewire:account.notifications />
	</x-artisanpack-tab>
	<x-artisanpack-tab value="security" :label="__( 'Security' )" icon="lock">
		<livewire:account.security />
	</x-artisanpack-tab>
</x-artisanpack-tabs>
```

Each `tab` renders its panel and registers its trigger, so the tablist renders before the panels. `wire:model` binds the selected value both ways; without it, `value` sets it, and without either the first enabled tab is selected.

## Underline, counts and manual activation

```blade
<x-artisanpack-tabs variant="underline" activation-mode="manual" wire:model.live="view">
	<x-artisanpack-tab value="overview" :label="__( 'Overview' )">…</x-artisanpack-tab>
	<x-artisanpack-tab value="notifications" :label="__( 'Notifications' )" :count="$unread">…</x-artisanpack-tab>
	<x-artisanpack-tab value="archived" :label="__( 'Archived' )" disabled />
</x-artisanpack-tabs>
```

Use segmented tabs for filters and small view switches, and underline tabs under a PageHeader. Use manual activation when showing a panel is slow, such as a server round trip. Don't use Tabs for the steps of a process; use Steps.

## Tabs as data

`tabs` renders the same children as composition, before any `tab` children:

```blade
<x-artisanpack-tabs size="sm" wire:model.live="range" :label="__( 'Range' )" :tabs="[
	[ 'value' => 'day', 'label' => __( 'Day' ) ],
	[ 'value' => 'week', 'label' => __( 'Week' ) ],
	[ 'value' => 'month', 'label' => __( 'Month' ), 'count' => 3, 'content' => __( 'This month' ) ],
]" />
```

A data tab's `content` is text; use `tab` children for rich panels.

## Props

### tabs

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | first enabled tab | The selected tab's value. `wire:model` binds it instead. |
| `tabs` | array | `[]` | The tabs as data: `value`, `label`, `icon`, `count`, `disabled`, `content`. |
| `variant` | `segmented` · `underline` | `segmented` | Emitted as `data-variant`. |
| `size` | `sm` · `md` | `md` | Emitted as `data-size`. |
| `orientation` | `horizontal` · `vertical` | `horizontal` | A row or a column of tabs; picks ArrowLeft/ArrowRight or ArrowUp/ArrowDown. Emitted as `data-orientation`. |
| `activation-mode` | `automatic` · `manual` | `automatic` | Emitted as `data-activation-mode`. |
| `label` | string | none | Names the tablist. Needed when a page has more than one set of tabs. |
| `classes` | array | `[]` | Extra classes per part: `root`, `list`, `trigger`, `icon`, `count`, `content`. |

### tab

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | the label | The tab's value, unique in the tabs. |
| `label` | string | none | The trigger's label. |
| `icon` | string | none | An icon name from the registry, before the label. |
| `count` | string or int | none | A count at the end of the trigger, announced with its name. |
| `disabled` | bool | `false` | The arrow keys skip it. |

Attributes on a `tab`, `class` included, land on its panel.

## Events

The tabs dispatch `value-change` (`$event.detail.value`) on the root when a person selects another tab. Setting the value from the server doesn't dispatch it.

## Ids and Livewire

Every part has a stable, server-rendered id: the root's is the `id` attribute (or one derived from `wire:model` or the props), the list's is `<id>-list`, and triggers and panels are named per value (`<id>-trigger-<value>`, `<id>-content-<value>`), so Livewire's morph keeps the selection and focus (D-015).

## Accessibility

- `role=tablist` with `tab` triggers and `tabpanel` panels; the selected trigger sets `aria-selected` and `aria-controls`, and each panel is labelled by its trigger.
- Only the selected tab is in the Tab order; Tab moves on into its panel.
- Arrow keys follow the orientation and the text direction; Home and End move to the first and last tab. Disabled tabs are skipped.
- The selected tab is shown by surface and edge, not by color alone.

## Upgrading from 2.x

- `name` on `tab` still works; prefer `value`. `selected` on `tabs` still works; prefer `value` or `wire:model`.
- `orientation="vertical-left"` and `vertical-right` render as `vertical`.
- The DaisyUI class props (`label-class`, `active-class`, `tabs-class`, …), `gap` and the glass props are gone: use `classes`, or the design tokens (`--apui-tabs-radius`). The `rounded` and `pill` variants became `segmented`.
- `hidden` on `tab` and `content-classes` still work but are deprecated: leave the tab out, and put `class` on the tab.

## Related Components

- [Steps](steps) for the steps of a process
- [Menu](menu) for navigation
