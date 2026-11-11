---
title: CheckboxGroup Component
---

# CheckboxGroup Component

A labeled set of related checkboxes with a shared hint and error, built on the ArtisanPack UI design system. Each option is the [Checkbox](checkbox) markup; the value is the array of checked values. An optional select-all box shows the mixed state while some options are checked.

## Basic Usage

```blade
<x-artisanpack-checkbox-group
	wire:model="notifications"
	select-all
	:label="__( 'Notify me about' )"
	:options="[
		[ 'value' => 'offers', 'label' => __( 'New offers' ), 'description' => __( 'Within an hour of submission' ) ],
		[ 'value' => 'prices', 'label' => __( 'Price changes in saved searches' ) ],
		[ 'value' => 'digest', 'label' => __( 'Weekly analytics digest' ) ],
	]"
/>
```

`wire:model` (an array property) lands on every option's native checkbox, with the option's `value`, so Livewire binds the array natively. Select all checks or clears every enabled option and fires a `change` event on each, which `wire:model` reads.

## Columns and cards

```blade
<x-artisanpack-checkbox-group wire:model="amenities" :columns="3" :label="__( 'Amenities' )" :options="$amenities" />

<x-artisanpack-checkbox-group wire:model="channels" variant="cards" :columns="3" :label="__( 'Channels' )" :options="$channels" />
```

Cards are clickable anywhere.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `options` | array | `[]` | Strings, or `[ 'value', 'label', 'description', 'disabled' ]` entries. |
| `value` | array | none | The checked values. `wire:model` binds them instead. |
| `label` | string or slot | none | The legend. |
| `hint` | string or slot | none | Help text, linked to the fieldset. |
| `error` | string | `$errors` | Validation message. Defaults to the first message for `error-field`. |
| `invalid` | bool | `false` | Marks every option invalid. Emitted as `data-invalid`. |
| `disabled` | bool | `false` | Disables the fieldset, every option and select all. |
| `orientation` | `vertical` · `horizontal` | `vertical` | Emitted as `data-orientation`. |
| `variant` | `default` · `cards` | `default` | Emitted as `data-variant`. |
| `columns` | int | `1` | 1, 2 or 3 columns. Emitted as `data-columns`. |
| `select-all` | bool | `false` | Adds the select-all checkbox. |
| `select-all-label` | string | `__( 'Select all' )` | Its label. |
| `error-field` | string | `wire:model` name | The `$errors` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `selectAll`, `items`, `hint`, `error`. |

## Accessibility

- A fieldset and legend group native checkboxes; each has its own label.
- Hint and error are linked to the fieldset; `invalid` sets `aria-invalid` on every checkbox.
- Select all is a real checkbox with the native mixed (indeterminate) state.
- Use [RadioGroup](radio-group) for mutually exclusive options.
