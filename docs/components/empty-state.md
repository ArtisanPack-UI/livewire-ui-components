---
title: Empty State Component
---

# Empty State Component

Explains why there's nothing here and what to do next, built on the ArtisanPack UI design system (`.apui-empty-state`). New in 3.0.

## Basic Usage

```blade
<x-artisanpack-empty-state :title="__( 'No properties yet' )" :description="__( 'Add your first property to start tracking visits, offers and revenue.' )">
	<x-slot:actions>
		<x-artisanpack-button leading-icon="plus" href="{{ route( 'properties.create' ) }}">{{ __( 'Add property' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-empty-state>

<x-artisanpack-empty-state variant="no-results" :title="__( 'No results for “:search”', [ 'search' => $search ] )" :description="__( 'Check the spelling, or clear filters.' )" />
```

The default slot fills the description. Named slots (`icon`, `title`, `description`, `actions`) override the props.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `variant` | `first-use` · `no-results` · `cleared` | `first-use` | Picks the tile's tint and default icon (`inbox`, `search-x`, `circle-check`). Emitted as `data-variant`. |
| `size` | `sm` · `md` | `md` | `sm` for a panel. Emitted as `data-size`. |
| `align` | `center` · `start` | `center` | Emitted as `data-align`. |
| `icon` | string or slot | the variant's | An icon name from the registry. |
| `title` | string or slot | none | Why it's empty, as a heading. |
| `description` | string or slot | none | What to do next. |
| `actions` | string or slot | none | One primary action, maybe a secondary one. |
| `heading-level` | int | `3` | The title's heading level, 2 to 6. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `title`, `description`, `actions`. |

## Accessibility

- The title is a heading; set `heading-level` to fit the page outline.
- The icon is decorative; the text carries the meaning.
