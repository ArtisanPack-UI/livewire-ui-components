---
title: Steps Component
---

# Steps Component

Shows progress through a sequence, built on the ArtisanPack UI design system (`.apui-steps`): an ordered list of steps, each complete, current, upcoming or in error. Compose it from `step` children, or pass the same structure as `steps`.

## Basic Usage

`wire:model` binds the current step, from 1: steps before it are complete and steps after it upcoming. Without Livewire, pass `:value`.

```blade
<x-artisanpack-steps wire:model="step" :label="__( 'Listing progress' )">
	<x-artisanpack-step :title="__( 'Basics' )" :description="__( 'Title and type' )" />
	<x-artisanpack-step :title="__( 'Location' )" :description="__( 'Address and map' )" />
	<x-artisanpack-step :title="__( 'Pricing' )" :description="__( 'Price and rent' )" />
	<x-artisanpack-step :title="__( 'Photos' )" :description="__( 'At least 6' )" />
	<x-artisanpack-step :title="__( 'Review' )" :description="__( 'Publish' )" />
</x-artisanpack-steps>
```

Use 3 to 6 steps, and don't use Steps as tabs.

## Vertical, with an error

A step's own `state` overrides the current step:

```blade
<x-artisanpack-steps :value="3" orientation="vertical">
	<x-artisanpack-step :title="__( 'Account created' )" :description="__( 'Nov 2 · Olivia Rhye' )" />
	<x-artisanpack-step :title="__( 'Identity verified' )" :description="__( 'Nov 3' )" />
	<x-artisanpack-step state="error" :title="__( 'Payout account' )" :description="__( 'IBAN rejected by the bank. Update it to continue.' )" />
	<x-artisanpack-step :title="__( 'Go live' )" :description="__( 'Listings become public' )" />
</x-artisanpack-steps>
```

## Steps as data, clickable

`steps` renders the same children. With `clickable` and `wire:model`, completed steps' titles become buttons that set the property, so people can go back but not skip ahead. Use steps or children, not both.

```blade
<x-artisanpack-steps wire:model.live="step" clickable :label="__( 'Checkout progress' )" :steps="[
	[ 'title' => __( 'Cart' ) ],
	[ 'title' => __( 'Shipping' ) ],
	[ 'title' => __( 'Payment' ) ],
	[ 'title' => __( 'Confirm' ) ],
]" />
```

## Props

### steps

| Prop | Type | Default | Description |
|---|---|---|---|
| `steps` | array | `[]` | The steps as data: `[ 'title' => …, 'description' => …, 'state' => …, 'icon' => … ]`. |
| `value` | int | none | The current step, from 1. `wire:model` binds it instead. |
| `orientation` | `horizontal` · `vertical` | `horizontal` | Emitted as `data-orientation`. |
| `clickable` | bool | `false` | With `wire:model`, completed steps' titles are buttons that set it. |
| `label` | string | none | The list's accessible name. |
| `complete-label` | string | `__( 'Completed' )` | Hidden text in a complete step's marker. |
| `error-label` | string | `__( 'Error' )` | Hidden text in an error step's marker. |
| `classes` | array | `[]` | Extra classes per part, children included: `root`, `step`, `marker`, `title`, `connector`, `description`. |

### step

| Prop | Type | Default | Description |
|---|---|---|---|
| `title` | string | none | The step's name. The default slot overrides it. |
| `description` | string | none | Supporting text under the title. |
| `state` | `complete` · `current` · `upcoming` · `error` | none | The step's own state; without it, the current step decides. |
| `icon` | string | none | An icon name that replaces the marker's number or check. |
| `step` | int | its position | The step's number. |

Attributes on a step, `wire:key` included, land on its `<li>`.

## Upgrading from 2.x

- `<x-artisanpack-step step="2" text="Shipping" />` still works: `text` is the title and `step` the number. Steps no longer show a panel for the current step; render your step's content yourself (`@if ( 2 === $step )`).
- `vertical` is `orientation="vertical"`; `steps-color`, `stepper-classes`, `step-classes`, `data-content` and the glass props are gone.

## Accessibility

- An ordered list; the current step has `aria-current="step"`.
- Complete and error steps carry visually hidden text, not only an icon.
- Only completed steps are buttons when clickable, so upcoming steps can't be reached.
