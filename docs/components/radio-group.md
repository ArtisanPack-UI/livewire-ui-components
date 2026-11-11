---
title: RadioGroup Component
---

# RadioGroup Component

Chooses exactly one option from a small set, built on the ArtisanPack UI design system. It renders native radios in a `fieldset` with a `legend`, so the platform supplies the radio group role, roving focus and arrow keys (which follow the text direction under RTL). RadioGroup replaces the deprecated [Radio](radio) and [Group](group) components; Group's joined buttons are the `segmented` variant.

## Basic Usage

```blade
<x-artisanpack-radio-group
	wire:model="visibility"
	:label="__( 'Listing visibility' )"
	:options="[
		[ 'value' => 'public', 'label' => __( 'Public' ), 'description' => __( 'Anyone can find it in search.' ) ],
		[ 'value' => 'unlisted', 'label' => __( 'Unlisted' ), 'description' => __( 'Only people with the link.' ) ],
		[ 'value' => 'private', 'label' => __( 'Private' ), 'description' => __( 'Only you and co-owners.' ) ],
	]"
/>
```

`wire:model` lands on every native radio, so it binds natively, with its modifiers (`.live`, `.blur`). Options are strings, or arrays (or objects, or `Arrayable`s) with `value`, `label`, `description`, `meta` and `disabled`.

## Cards

Use cards when options need a description or a price to compare. The whole card is the radio's label, so it's clickable anywhere.

```blade
<x-artisanpack-radio-group
	wire:model="plan"
	variant="cards"
	orientation="horizontal"
	:label="__( 'Plan' )"
	:options="[
		[ 'value' => 'starter', 'label' => __( 'Starter' ), 'description' => __( 'Up to 5 listings' ), 'meta' => __( '$0 / month' ) ],
		[ 'value' => 'growth', 'label' => __( 'Growth' ), 'description' => __( 'Up to 50 listings' ), 'meta' => __( '$39 / month' ) ],
	]"
/>
```

## Segmented

Joined buttons for two to four short options.

```blade
<x-artisanpack-radio-group wire:model.live="period" variant="segmented" orientation="horizontal" :label="__( 'Billing period' )" :options="[ __( 'Monthly' ), __( 'Yearly' ) ]" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `options` | array | `[]` | Strings, or `[ 'value', 'label', 'description', 'meta', 'disabled' ]` entries. |
| `value` | string | none | The selected value. `wire:model` binds it instead. |
| `label` | string or slot | none | The legend. |
| `hint` | string or slot | none | Help text under the options, linked to the fieldset. |
| `error` | string | `$errors` | Validation message. Defaults to the first message for `error-field`. |
| `invalid` | bool | `false` | Marks the group invalid without a message. Emitted as `data-invalid`. |
| `disabled` | bool | `false` | Disables the fieldset and every option. |
| `required` | bool | `false` | Sets `required` on the radios and shows the required mark. |
| `orientation` | `vertical` · `horizontal` | `vertical` | Emitted as `data-orientation`. |
| `variant` | `default` · `cards` · `segmented` | `default` | Emitted as `data-variant`. |
| `error-field` | string | `wire:model` name | The `$errors` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `items`, `item`, `box`, `control`, `indicator`, `itemText`, `itemDescription`, `itemMeta`, `hint`, `error`. |

## Attributes and ids

`class` lands on the fieldset. Every other attribute lands on every radio. The radios share one `name` (the `name` attribute, else the group id) so arrow keys move between them. The group id is the `id` attribute, else one derived from the `wire:model` name; each radio's id adds `-0`, `-1`, ….

## Markup

```html
<fieldset class="apui-radio-group" data-orientation="vertical" data-variant="default" aria-describedby="…-hint">
	<legend data-scope="radio-group" data-part="label">Plan</legend>
	<div data-scope="radio-group" data-part="items">
		<label data-scope="radio-group" data-part="item">
			<span data-part="box"><input type="radio" data-part="control" name="…" value="starter" /><span data-part="indicator"></span></span>
			<span data-part="item-text">Starter</span>
			<span data-part="item-description">Up to 5 listings</span>
			<span data-part="item-meta">$0 / month</span>
		</label>
	</div>
	<p data-scope="radio-group" data-part="hint">…</p>
</fieldset>
```

## Accessibility

- Native radios in a fieldset and legend; arrow keys follow the text direction.
- Hint and error are linked to the fieldset; `invalid` sets `aria-invalid` on every radio; an option's description is linked to its radio.
- The dot follows the native `:checked` state, so it updates before a deferred `wire:model` round trip.
- Use [Select](select) for more than about six options.
