---
title: Combobox Component
---

# Combobox Component

A text input that filters a listbox, built on the ArtisanPack UI design system: pick one match, or several shown as removable chips, and optionally create a value that isn't listed. Combobox replaces the deprecated [Choices](choices), [ChoicesOffline](choices-offline) and [Tags](tags): filtering is local by default (ChoicesOffline), `remote` hands the search to the server (Choices), and `multiple` with `allow-create` is the tags input.

It shares the field anatomy of [Input](input) and [Select](select): label, box, hint and error. For a short list (fewer than about eight options) with no search, use Select.

The interaction comes from the design system's behavior engine (`apuiCombobox`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-combobox
	wire:model="propertyId"
	:label="__( 'Property' )"
	leading-icon="search"
	:options="[
		[ 'value' => 'lakeview', 'label' => 'Lakeview Lofts', 'description' => __( 'United States, Chicago' ) ],
		[ 'value' => 'lakeside', 'label' => 'Lakeside Commons', 'description' => __( 'Canada, Toronto' ) ],
		[ 'value' => 'silver-lake', 'label' => 'Silver Lake Bungalows', 'description' => __( 'United States, Los Angeles' ) ],
	]"
/>
```

Typing filters the options by label, ignoring case and accents, and marks the match in bold with an underline. `options` takes strings, arrays or objects with `value`, `label`, `description`, `icon`, `color` and `disabled`; a collection works too. Values are strings; an option given an integer value (an Eloquent id) binds back as that integer, so an `?int` property still works.

## Tags: multiple with create

```blade
<x-artisanpack-combobox
	wire:model="tags"
	wire:create="addTag( $event.detail.value )"
	multiple
	allow-create
	:max="8"
	:label="__( 'Listing tags' )"
	:placeholder="__( 'Add a tag…' )"
	:hint="__( 'Press Enter to add, Backspace to remove the last tag.' )"
	:options="$this->tagOptions"
/>
```

`multiple` binds an array and shows each value as a chip with a remove button. An option's `color` tints its chip. `allow-create` offers “Create …” for text that names no option or chosen value (ignoring case and accents, so “Rooftop” and “rooftop” don't both exist); choosing it adds the text to the value and dispatches `create`. Chosen values that aren't options keep rendering, labelled by their value, so persist new tags or add them to `options`. Backspace in the empty input removes the last chip, and the removal is announced. `max` caps the number of values and disables the other options once reached.

## Remote search

```blade
{{-- public ?string $ownerId = null; public string $ownerSearch = ''; --}}
{{-- #[Computed] public function owners(): array { … where name like $this->ownerSearch … } --}}
<x-artisanpack-combobox
	wire:model="ownerId"
	search-model="ownerSearch"
	:debounce="300"
	remote
	:label="__( 'Owner' )"
	:placeholder="__( 'Search people…' )"
	:options="$this->owners"
>
	<x-slot:empty>
		{{ __( 'No people match. Check the spelling or' ) }}
		<a href="{{ route( 'people.create' ) }}">{{ __( 'add a person' ) }}</a>.
	</x-slot:empty>
</x-artisanpack-combobox>
```

With `remote`, the combobox shows the options it's given without filtering them. `search-model` names the property the typed text is set on, `debounce` milliseconds after the last keystroke (default 250); the spinner shows until the round trip ends, and the morph brings the new options. Without `search-model`, listen for `search-change` yourself: `wire:search-change.debounce.300ms="search( $event.detail.search )"`.

## Validation, clearing and sizes

```blade
<x-artisanpack-combobox wire:model="ownerId" required clearable :label="__( 'Owner' )" :options="$people" />
<x-artisanpack-combobox size="sm" :label="__( 'Small' )" :options="$people" />
<x-artisanpack-combobox size="lg" :label="__( 'Large' )" :options="$people" disabled />
```

The error is read from `$errors` for the `wire:model` name (or `error-field`), shown under the field and linked to the input. `clearable` adds a button that clears the value while there is one.

## Custom option content

```blade
<x-artisanpack-combobox wire:model="userId" :label="__( 'Assignee' )" :options="$users">
	@scope( 'item', $option )
		<strong>{{ $option['label'] }}</strong> <span>{{ $option['description'] }}</span>
	@endscope
</x-artisanpack-combobox>
```

The scoped `item` slot receives each normalized option (`value`, `label`, `description`, `icon`, `color`, `disabled`). Matches aren't marked inside custom content.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string or slot | none | The visible label, for the input. |
| `options` | array or collection | `[]` | Strings, or `value`, `label`, `description`, `icon`, `color`, `disabled` entries. |
| `size` | `sm` · `md` · `lg` | `md` | Control height. Emitted as `data-size`. |
| `hint` | string or slot | none | Help text under the field, linked with `aria-describedby`. |
| `error` | string | from `$errors` | Validation message. Marks the field invalid. |
| `error-field` | string | the `wire:model` name | The `$errors` key the error is read from. |
| `invalid` | bool | `false` | Marks the field invalid without a message. |
| `disabled` | bool | `false` | Disables the input, the buttons and the chips. |
| `required` | bool | `false` | Shows the required mark and sets `required` on the input. |
| `placeholder` | string | `Search…` (translated) | Shown in the empty input. |
| `name` | string | none | Submits the value with a native form: a hidden input per value (use `tags[]` for an array), none while empty. Works alongside `wire:model`. |
| `value` | string or array | none | The value. `wire:model` binds it instead. |
| `multiple` | bool | `false` | Several values, as chips. Emitted as `data-multiple`. |
| `max` | int | none | With `multiple`, the most values that can be chosen. |
| `allow-create` | bool | `false` | Offers “Create …” for unmatched text and dispatches `create`. |
| `remote` | bool | `false` | The server filters the options. |
| `search` | string | the `search-model` value | The text in the input when it renders: it filters (unless remote) and marks the matches. |
| `search-model` | string | none | The Livewire property the search text is set on, after `debounce`. |
| `debounce` | int | `250` | Milliseconds after typing before setting `search-model`. |
| `loading` | bool | `false` | Shows the spinner and the loading text. Emitted as `data-loading`. |
| `clearable` | bool | `false` | Adds the clear button. |
| `open` | bool | `false` | Whether the listbox is open when it renders. |
| `portal` | bool | `true` | Renders the listbox at the end of the body (`x-teleport`). Off renders it in place. |
| `leading-icon` | string | none | Icon name before the input. The `icon` slot overrides it. |
| `empty-label` | string | `No matches for “:search”.` | The empty text. The `empty` slot overrides it. |
| `loading-label` | string | `Loading…` | The loading text. |
| `create-label` | string | `Create “:value”` | The create option's text. |
| `remove-label` | string | `Remove :label` | Each chip's remove button's accessible name. |
| `removed-label` | string | `Removed :label.` | Announced when a chip is removed. |
| `clear-label` | string | `Clear` | The clear button's accessible name. |
| `trigger-label` | string | `Show options` | The chevron button's accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `box`, `icon`, `chip`, `chipRemove`, `control`, `spinner`, `clearTrigger`, `trigger`, `hint`, `error`, `positioner`, `content`, `item`, `itemText`, `itemDescription`, `itemIndicator`, `empty`, `status`. |

Every built-in string goes through `__()`, including the result count the listbox announces (`1 result`, `:count results`, `No results`).

## Attributes and events

`class` lands on the root (`.apui-combobox`), and so does every other attribute, since that's where the combobox dispatches its events: `value-change` (`$event.detail.value`), `search-change` (`$event.detail.search`), `open-change` (`$event.detail.open`) and `create` (`$event.detail.value`). Listen with `x-on:` or `wire:` (`wire:create="addTag( $event.detail.value )"`). `wire:model` binds the value through `$wire.entangle()`, `.live` included.

## Ids and Livewire

Every part has a stable, server-rendered id and every option carries `wire:key`, so Livewire's morph keeps the combobox (D-015). The base id is the `id` attribute, else one derived from the `wire:model` name. Chips are client state inside a `wire:ignore` wrapper: the server's render shows them until Alpine takes over.

## Accessibility

- The input is `role=combobox` with `aria-expanded`, `aria-controls`, `aria-autocomplete=list` and `aria-activedescendant` on the highlighted option.
- The listbox is labelled by the label; with `multiple` it's `aria-multiselectable`, and chosen options set `aria-selected`.
- Matches are shown with weight and an underline, never color alone.
- The result count is announced as the list changes; the empty state says what to do next.
- Each chip's remove button is labelled “Remove …”; removing one with Backspace is announced.
- Arrow keys move through the options, Enter chooses, Escape closes; the listbox opens under the field at its start edge and mirrors in RTL.

## Upgrading from Choices, ChoicesOffline and Tags

| Old | New |
|---|---|
| `<x-artisanpack-choices-offline searchable>` | `<x-artisanpack-combobox multiple>` (filtering is always on) |
| `<x-artisanpack-choices searchable search-function="search">` | `<x-artisanpack-combobox remote search-model="…">` or `wire:search-change="search( $event.detail.search )"` |
| `single` | drop it; Combobox is single unless `multiple` (Choices was multiple unless `single`) |
| `option-value`, `option-label`, `option-sub-label` | map the options to `value`, `label`, `description` |
| `no-result-text` | `empty-label` or the `empty` slot |
| `debounce="250ms"` | `:debounce="250"` |
| `icon` | `leading-icon` |
| `clearable` | `clearable` |
| `omit-error`, `error-class`, `hint-class`, `compact`, `allow-all`, `height`, `inline`, `prefix`, `suffix`, `option-avatar`, `values-as-string`, `lazy-load` | removed |
| `<x-artisanpack-tags>` | `<x-artisanpack-combobox multiple allow-create>`; `allow-custom-tags` becomes `allow-create`, `custom-tags-text` becomes `create-label` |
