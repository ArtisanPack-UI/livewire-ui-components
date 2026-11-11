---
title: Form Component
---

# Form Component

A native `<form>` built on the ArtisanPack UI design system: its fields stack with the field gap, and an actions row ends it, aligned to the end. Attributes (`wire:submit`, `action`, `method`, `novalidate`, `aria-label`) land on the form.

## Basic Usage

```blade
<x-artisanpack-form wire:submit="save" :aria-label="__( 'Invite member' )">
	<x-artisanpack-input wire:model="email" type="email" :label="__( 'Email' )" required />
	<x-artisanpack-textarea wire:model="message" :label="__( 'Message' )" rows="3" />

	<x-slot:actions>
		<x-artisanpack-button variant="ghost" wire:click="cancel">{{ __( 'Cancel' ) }}</x-artisanpack-button>
		<x-artisanpack-button type="submit">{{ __( 'Send invite' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-form>
```

## Sections and a summary

Fieldsets break a long form into sections, and a [ValidationSummary](validation-summary) at the top lists the errors after a failed submit. `divided` puts an edge above the actions.

```blade
<x-artisanpack-form wire:submit="publish" divided :aria-label="__( 'Listing' )">
	<x-artisanpack-validation-summary />

	<x-artisanpack-fieldset variant="divided" :title="__( 'Location' )">
		<x-artisanpack-input wire:model="postalCode" :label="__( 'Postal code' )" />
	</x-artisanpack-fieldset>
	<x-artisanpack-fieldset variant="plain" :title="__( 'Agreements' )">
		<x-artisanpack-checkbox wire:model="terms" :label="__( 'I accept the listing terms' )" />
	</x-artisanpack-fieldset>

	<x-slot:actions>
		<x-artisanpack-button type="submit">{{ __( 'Publish listing' ) }}</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-form>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `actions` | string or slot | none | The buttons at the end, primary action last. |
| `divided` | bool | `false` | Separates the actions row with an edge above it. Emitted as `data-divided`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `actions`. |

## Accessibility

- Name the form (`aria-label` or `aria-labelledby`) when a page has more than one; a named form is a landmark.
- The primary action is a submit button, so Enter in a field submits the form.

## Upgrading from 2.x

`no-separator` is gone: the actions row has no edge unless you add `divided`.
