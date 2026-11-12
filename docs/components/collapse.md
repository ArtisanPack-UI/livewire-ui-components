---
title: Collapse Component
---

# Collapse Component

A single disclosure, built on the ArtisanPack UI design system and the behavior engine's collapsible machine: a button that shows and hides one region, such as advanced settings, or a read-more that clamps long text behind a fade.

## Basic Usage

```blade
<x-artisanpack-collapse :label="__( 'Advanced settings' )" wire:model="showAdvanced">
	<x-artisanpack-input :label="__( 'Custom URL' )" wire:model="slug" />
	<x-artisanpack-toggle :label="__( 'Hide from search engines' )" wire:model="noindex" />
</x-artisanpack-collapse>
```

Say what will appear in the label. `wire:model` binds `open`; without it, `open` sets the initial state. A `trigger` slot replaces the label when it needs markup.

## Read more

```blade
<x-artisanpack-collapse variant="read-more">
	<p>{{ $listing->description }}</p>
</x-artisanpack-collapse>
```

A read-more shows the first lines (`--apui-collapse-clamp-height`, 64px) behind a fade, with the trigger after them. The trigger reads "Read more", then "Show less" while open (translated; change them with `label` and `open-label`). The clamped text stays in the accessibility tree. Don't clamp text that holds the main point.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `open` | bool | `false` | Whether the region shows. `wire:model` binds it instead. |
| `variant` | `section` · `read-more` | `section` | Emitted as `data-variant`. |
| `label` | string or `trigger` slot | none | The trigger's text. Read-more defaults it to `__( 'Read more' )`. |
| `open-label` | string | none | The trigger's text while open. Read-more defaults it to `__( 'Show less' )`. |
| `disabled` | bool | `false` | The trigger can't be focused or toggled. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `icon`, `content`, `fade`. |

## Events

`open-change` fires on the root when the trigger opens or closes the region (`$event.detail.open`). Setting the bound property doesn't fire it.

## Accessibility

- The trigger is a button with `aria-expanded` and `aria-controls`; a section's region follows it and is `hidden` while closed.
- The chevron and the fade are `aria-hidden`.

## Upgrading from 2.x

- The `heading` and `content` slots still render, deprecated: use `label` (or the `trigger` slot) and the default slot.
- Inside an accordion, use `accordion-item` instead of `collapse` with `name`.
- `name`, `separator`, `no-icon`, `collapse-plus-minus` and the glass props are gone.
