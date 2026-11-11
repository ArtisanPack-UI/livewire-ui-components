---
title: File Component
---

# File Component

Uploads files by dropping or browsing, built on the ArtisanPack UI design system. The native file input is transparent over the drop zone, so a click browses and a drop chooses with no script, and `wire:model` uploads through Livewire natively. The list under it shows each upload's size, progress and errors, with a remove button labelled with the file name.

## Basic Usage

```blade
<x-artisanpack-file
	wire:model="photos"
	multiple
	accept="image/jpeg,image/png"
	:label="__( 'Listing photos' )"
	:title="__( 'Drop photos here or browse' )"
	:hint="__( 'JPG or PNG, up to 20 MB each' )"
/>
```

The Livewire component uses `WithFileUploads`. Without `value`, the list reads the `wire:model` property of the Livewire component rendering it, and each temporary upload's remove button calls `$wire.$removeUpload()`. While Livewire uploads, a pending row shows its progress.

## Listing other files

Pass `value` to list stored files, or to show your own progress and errors. Entries are uploads, or arrays with `name`, `size`, `progress` and `error`. Their remove buttons dispatch a `remove` event with `{ index, name }`:

```blade
<x-artisanpack-file wire:model="documents" :value="$documentEntries" x-on:remove="$wire.removeDocument( $event.detail.index )" />
```

## Compact

```blade
<x-artisanpack-file wire:model="contract" variant="compact" accept="application/pdf" :title="__( 'Upload a contract' )" :hint="__( 'PDF up to 10 MB' )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `label` | string or slot | none | The visible label, for the input. Without it, the title names the input. |
| `title` | string | `__( 'Drop files here or browse' )` | The drop zone's main line. |
| `hint` | string or slot | none | The limits, linked with `aria-describedby`. |
| `error` | string | `$errors` | Validation message. |
| `invalid` | bool | `false` | Marks the field invalid. |
| `disabled` | bool | `false` | Disables the input. |
| `required` | bool | `false` | Sets `required` and shows the required mark. |
| `accept` | string | none | The accepted types. |
| `multiple` | bool | `false` | Allows several files. |
| `max-size` | int | none | The largest file in bytes, emitted as `data-max-size`. Validate on the server (`max:`). |
| `variant` | `dropzone` · `compact` | `dropzone` | Emitted as `data-variant`. |
| `value` | mixed | `wire:model` property | The files to list. |
| `remove-label` | string | `__( 'Remove :name' )` | The remove buttons' name, with `:name`. |
| `error-field` | string | `wire:model` name | The `$errors` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `dropzone`, `control`, `icon`, `title`, `hint`, `list`, `item`, `thumb`, `name`, `meta`, `progress`, `actions`, `error`. |

## Migrating

The image preview slot and cropping (`crop-after-change`, `crop-config`, …), `with-drag-drop` (the drop zone is always on) and `hide-progress` are gone.

## Accessibility

- The native input stays in the DOM, so it is keyboard and screen-reader operable; drag and drop is an enhancement.
- Remove buttons are labelled with the file name; errors show as text with an icon, not color alone.
- State the limits before upload, and never reject a whole batch for one bad file.
