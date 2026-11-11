---
title: Fieldset Component
---

# Fieldset Component

Groups related fields under a legend, built on the ArtisanPack UI design system. It's a native `<fieldset>` and `<legend>`, so the group's name is announced with each field inside.

## Basic Usage

```blade
<x-artisanpack-fieldset :title="__( 'Contact' )" :description="__( 'Who buyers and renters should reach.' )">
	<div data-scope="fieldset" data-part="row">
		<x-artisanpack-input wire:model="name" :label="__( 'Full name' )" />
		<x-artisanpack-input wire:model="email" type="email" :label="__( 'Email' )" leading-icon="mail" />
	</div>
	<x-artisanpack-input wire:model="phone" type="tel" :label="__( 'Phone' )" />
</x-artisanpack-fieldset>
```

Fields side by side go in a row: `<div data-scope="fieldset" data-part="row">`, a grid that wraps on narrow screens.

## Divided and plain

```blade
<x-artisanpack-fieldset variant="divided" :title="__( 'Billing address' )">
	<x-artisanpack-checkbox wire:model="sameAddress" :label="__( 'Same as property address' )" />
</x-artisanpack-fieldset>
<x-artisanpack-fieldset variant="plain" :title="__( 'Payout account' )" :description="__( 'Locked while a payout is processing.' )" disabled>
	<x-artisanpack-input :label="__( 'IBAN' )" value="ES91 •••• •••• 1332" />
</x-artisanpack-fieldset>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `variant` | `panel` · `divided` · `plain` | `panel` | A bordered panel, a section with an edge under it, or no chrome. Emitted as `data-variant`. |
| `title` | string or slot | none | The group's name, in the legend. |
| `description` | string or slot | none | Supporting text in the legend. |
| `disabled` | bool | `false` | Disables every control inside (native `disabled`). |
| `classes` | array | `[]` | Extra classes per part: `root`, `legend`, `title`, `description`. |

Attributes land on the `<fieldset>`. Don't nest fieldsets more than one level.

## Upgrading from 2.x

`subtitle` is `description`. `icon`, `separator`, `bg-color`, `text-color`, `border`, `middle` and `actions` are gone; use `variant`, `classes` and your own content.
