---
title: Accordion Component
---

# Accordion Component

Stacked sections that expand and collapse, built on the ArtisanPack UI design system and the behavior engine's accordion machine. Each item is a heading holding a button that shows or hides its panel. One item is open at a time, or several with `multiple`.

## Basic Usage

```blade
<x-artisanpack-accordion wire:model="openFaq">
	<x-artisanpack-accordion-item value="live" :title="__( 'How long does a listing stay live?' )">
		{{ __( 'Listings stay live until you archive them or mark them as sold.' ) }}
	</x-artisanpack-accordion-item>
	<x-artisanpack-accordion-item value="owners" :title="__( 'Can co-owners edit a listing?' )">
		{{ __( 'Yes. Invite them from the listing\'s Sharing tab.' ) }}
	</x-artisanpack-accordion-item>
	<x-artisanpack-accordion-item value="files" :title="__( 'Which file types can I upload?' )" disabled>
		{{ __( 'Images, PDFs and floor plans up to 20 MB each.' ) }}
	</x-artisanpack-accordion-item>
</x-artisanpack-accordion>
```

`wire:model` binds the open items: an array (`public array $openFaq = [ 'live' ];`), or a string or null for a single accordion (`public ?string $openFaq = 'live';`). Without it, `value` sets the open items. `items` renders the same children from data, before any composed ones: `:items="[ [ 'value' => 'live', 'title' => __( 'How long…' ), 'content' => __( '…' ) ] ]"`.

A title with markup goes in a `title` slot:

```blade
<x-artisanpack-accordion-item value="pricing">
	<x-slot:title>{{ __( 'Pricing' ) }} <x-artisanpack-badge size="sm">{{ __( 'New' ) }}</x-artisanpack-badge></x-slot:title>
	…
</x-artisanpack-accordion-item>
```

## Variants

```blade
<x-artisanpack-accordion variant="separated" multiple>…</x-artisanpack-accordion>
<x-artisanpack-accordion variant="flush">…</x-artisanpack-accordion>
```

`default` is one container with an edge between items, `separated` gives each item its own container, and `flush` drops the container for use inside a card or page.

## Props

### accordion

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | array or string | none | The open items' values. `wire:model` binds it instead. |
| `items` | array | `[]` | The items as data: `{ value, title, content, disabled }`. |
| `multiple` | bool | `false` | Several items open at once. Emitted as `data-multiple`. |
| `collapsible` | bool | `true` | The open item of a single accordion can close. Emitted as `data-collapsible`. |
| `variant` | `default` · `separated` · `flush` | `default` | Emitted as `data-variant`. |
| `heading-level` | int | `3` | The items' heading level, `2` to `6`. Pick the level below the heading the accordion sits under. |
| `disabled` | bool | `false` | Disables every item. |
| `classes` | array | `[]` | Extra classes per part: `root`, `item`, `title`, `trigger`, `chevron`, `content`. |

### accordion-item

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | the title | The item's value, unique in the accordion. |
| `title` | string or slot | none | The trigger's text. |
| `disabled` | bool | `false` | The trigger can't be focused or toggled; the arrow keys skip it. |

## Events

`value-change` fires on the root when an item opens or closes from a click or the keyboard (`x-on:value-change="…"`; `$event.detail.value` is the open items). Setting the bound property doesn't fire it.

## Accessibility

- Each trigger is a button inside a heading, with `aria-expanded` and `aria-controls`; each panel is `role="region"`, labelled by its trigger and `hidden` while closed.
- ArrowDown and ArrowUp move between triggers, skipping disabled ones; Home and End go to the first and last. Enter and Space toggle.
- The chevron is `aria-hidden`; the open state is in `aria-expanded`.
- Don't hide required form fields in collapsed items.

## Upgrading from 2.x

- Items are `accordion-item` children, not `collapse` children with a `name`. Move each `<x-slot:heading>` to `title` and the `<x-slot:content>` to the item's slot.
- `no-join`, `collapse-plus-minus` and the glass props are gone: use `variant` and `class`.
