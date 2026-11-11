---
title: DropdownMenu Component
---

# DropdownMenu Component

A list of actions or options anchored to a trigger button, built on the ArtisanPack UI design system: action, checkbox and radio items, in groups with labels. Arrow keys move, typeahead jumps, Escape closes and returns focus to the trigger. DropdownMenu replaces the deprecated [Dropdown](dropdown).

The interaction comes from the design system's behavior engine (`apuiDropdownMenu`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-dropdown-menu :label="__( 'Options' )">
	<x-artisanpack-dropdown-menu-group :label="__( 'Listing' )">
		<x-artisanpack-dropdown-menu-item value="edit" icon="pencil" shortcut="E" wire:click="edit">{{ __( 'Edit' ) }}</x-artisanpack-dropdown-menu-item>
		<x-artisanpack-dropdown-menu-item value="duplicate" icon="copy" wire:click="duplicate">{{ __( 'Duplicate' ) }}</x-artisanpack-dropdown-menu-item>
		<x-artisanpack-dropdown-menu-item value="archive" disabled>{{ __( 'Archive' ) }}</x-artisanpack-dropdown-menu-item>
	</x-artisanpack-dropdown-menu-group>
	<x-artisanpack-dropdown-menu-separator />
	<x-artisanpack-dropdown-menu-item value="delete" icon="trash" color="error" wire:click="delete">{{ __( 'Delete' ) }}</x-artisanpack-dropdown-menu-item>
</x-artisanpack-dropdown-menu>
```

Choosing an item clicks it, so `wire:click` on an item runs from the keyboard too. Put destructive items last, after a separator.

## Checkbox and radio items

Checkbox and radio items report `checked-change`; their `checked` state stays yours to set.

```blade
<x-artisanpack-dropdown-menu :label="__( 'View' )" align="end">
	<x-artisanpack-dropdown-menu-item type="checkbox" value="grid" :checked="$showGrid" wire:click="$toggle( 'showGrid' )">{{ __( 'Show grid' ) }}</x-artisanpack-dropdown-menu-item>
	<x-artisanpack-dropdown-menu-separator />
	<x-artisanpack-dropdown-menu-group :label="__( 'Sort by' )">
		<x-artisanpack-dropdown-menu-item type="radio" value="newest" :checked="'newest' === $sort" wire:click="$set( 'sort', 'newest' )">{{ __( 'Newest' ) }}</x-artisanpack-dropdown-menu-item>
		<x-artisanpack-dropdown-menu-item type="radio" value="price" :checked="'price' === $sort" wire:click="$set( 'sort', 'price' )">{{ __( 'Price' ) }}</x-artisanpack-dropdown-menu-item>
	</x-artisanpack-dropdown-menu-group>
</x-artisanpack-dropdown-menu>
```

## Items as data

`items` renders the same children as composition:

```blade
<x-artisanpack-dropdown-menu :label="__( 'Options' )" :items="[
	[ 'type' => 'group', 'label' => __( 'Listing' ), 'items' => [
		[ 'value' => 'edit', 'label' => __( 'Edit' ), 'icon' => 'pencil', 'shortcut' => 'E' ],
		[ 'value' => 'archive', 'label' => __( 'Archive' ), 'disabled' => true ],
	] ],
	[ 'type' => 'separator' ],
	[ 'type' => 'checkbox', 'value' => 'grid', 'label' => __( 'Show grid' ), 'checked' => $showGrid ],
	[ 'value' => 'delete', 'label' => __( 'Delete' ), 'icon' => 'trash', 'color' => 'error' ],
]" x-on:select="$wire.handle( $event.detail.value )" />
```

Items from data have no `wire:click`; listen for `select` (and `checked-change`) on the menu instead.

## Props

### dropdown-menu

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string or slot | none | The trigger button's content. An icon-only trigger needs an `aria-label` in the slot. |
| `items` | array | `[]` | The items as data (see above). |
| `open` | bool | `false` | Whether it's open. `wire:model` binds it instead. |
| `align` | `start` · `center` · `end` | `start` | Which of the menu's edges lines up with the trigger's; start and end follow the text direction. Emitted as `data-align`. |
| `surface` | `overlay` · `glass` | `overlay` | The menu's surface. Emitted as `data-surface`. |
| `portal` | bool | `true` | Renders the menu at the end of the body (`x-teleport`). Off renders it in place. |
| `classes` | array | `[]` | Extra classes per part, children included: `root`, `trigger`, `positioner`, `item`, `icon`, `itemText`, `shortcut`, `itemIndicator`, `itemGroup`, `itemGroupLabel`, `separator`. |

### dropdown-menu-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | the label's text | What `select` and `checked-change` report. |
| `type` | `item` · `checkbox` · `radio` | `item` | Checkbox and radio items are `menuitemcheckbox` and `menuitemradio`. |
| `checked` | bool | `false` | A checkbox or radio item's state. |
| `icon` | string | none | An icon name from the registry. |
| `shortcut` | string | none | A keyboard shortcut shown at the end, in a `<kbd>`. |
| `color` | string | none | `error` for a danger item. |
| `disabled` | bool | `false` | The arrow keys skip it. |
| `label` | string | none | The label when there's no default slot. |

### dropdown-menu-group

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string or slot | none | The group's label; the group is labelled by it. |

`dropdown-menu-separator` takes no props.

## Attributes and events

`class` on the menu lands on the panel (`.apui-dropdown-menu`). Every other attribute lands on the element that holds the Alpine component, which is where the menu dispatches `open-change` (`$event.detail.open`), `select` (`$event.detail.value`) and `checked-change` (`$event.detail.value`, `$event.detail.checked`). Attributes on an item, `wire:click` included, land on the item.

## Ids and Livewire

Every part has a stable, server-rendered id and every item carries `wire:key`, so Livewire's morph keeps the menu (D-015). The base id is the `id` attribute, else one derived from the `wire:model` name, else a hash of the props that aren't text or open state. Derived ids include the Livewire component's id, and repeats in one render (a `@foreach`) get `-2`, `-3`, so they're unique on the page and a re-render keeps them. Repeated menus (one per table row) are numbered in render order; pass your own `id` when rows can reorder between renders:

```blade
<x-artisanpack-dropdown-menu :id="'row-'.$listing->id" :label="__( 'Actions' )">…</x-artisanpack-dropdown-menu>
```

## Accessibility

- `role=menu` with `menuitem`, `menuitemcheckbox` and `menuitemradio` items; checked items set `aria-checked`.
- The trigger sets `aria-haspopup=menu`, `aria-expanded` and `aria-controls`.
- Disabled items set `aria-disabled` and are skipped by the arrow keys.
- Groups are labelled by their label with `aria-labelledby`.
