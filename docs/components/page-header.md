---
title: PageHeader Component
---

# PageHeader Component

The title block at the top of a page, built on the ArtisanPack UI design system: breadcrumbs, then the title with its description and meta beside the actions, then tabs. PageHeader replaces the deprecated [Header](header).

## Basic Usage

```blade
<x-artisanpack-page-header :title="__( 'Dashboard' )" :description="__( 'Income, profit and transactions across all stores.' )">
	<x-slot:breadcrumbs>
		<x-artisanpack-breadcrumbs :items="[ [ 'label' => 'Apexify', 'href' => route( 'home' ) ], [ 'label' => __( 'Dashboard' ) ] ]" />
	</x-slot:breadcrumbs>

	<x-slot:actions>
		<x-artisanpack-button variant="outline" color="neutral" size="sm" leading-icon="calendar">{{ __( 'This month' ) }}</x-artisanpack-button>
		<x-artisanpack-button size="sm">{{ __( 'Share' ) }}</x-artisanpack-button>
	</x-slot:actions>

	<x-slot:tabs>
		<x-artisanpack-tabs variant="underline" wire:model="section" :tabs="$tabs" />
	</x-slot:tabs>
</x-artisanpack-page-header>
```

The title is the page's `h1`; set `level` when the header heads a section. `meta` sits above the title, for status such as a badge and an edited time. Keep to three actions, the primary one last, and overflow into a DropdownMenu. Every part is a prop or a named slot.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string or slot | none | The page's title. |
| `description` | string or slot | none | Supporting text, kept to the reading measure. |
| `level` | int | `1` | The title's heading level, `1` to `6`. |
| `breadcrumbs` | string or slot | none | Breadcrumbs above the title. |
| `meta` | string or slot | none | Status above the title. |
| `actions` | string or slot | none | Buttons at the inline end of the title row. |
| `tabs` | string or slot | none | Tabs under the row. |
| `classes` | array | `[]` | Extra classes per part: `root`, `breadcrumbs`, `row`, `meta`, `title`, `description`, `actions`, `tabs`. |

## Accessibility

- One `h1` per page: the page header's title.
- The row wraps rather than overflowing at narrow widths, and the actions keep the order they're read in.
