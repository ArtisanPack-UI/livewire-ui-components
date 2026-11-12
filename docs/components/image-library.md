---
title: ImageLibrary Component
---

# ImageLibrary Component

A media manager, built on the ArtisanPack UI design system (`.apui-image-library`): search, a grid or list view, single or multiple selection, uploads, and a details panel with editable alt text. ImageLibrary is Livewire only (design-system decision D-003): it uploads through Livewire and syncs with the `WithMediaSync` trait. React and Vue apps use the `media-library` package's MediaModal and MediaGrid.

## With WithMediaSync

`wire:model` names the uploads property and `wire:library` the library collection, as the trait expects; pass the collection as `preview`.

```php
use ArtisanPack\LivewireUiComponents\Traits\WithMediaSync;
use Livewire\WithFileUploads;

class EditListing extends Component
{
    use WithFileUploads;
    use WithMediaSync;

    public array $files = [];

    public Collection $library;

    public function save(): void
    {
        $this->syncMedia( $this->listing );
    }
}
```

```blade
<x-artisanpack-image-library
	wire:model="files"
	wire:library="library"
	:preview="$library"
	:label="__( 'Listing photos' )"
	:hint="__( 'PNG or JPEG, up to 10 MB.' )"
	droppable
/>
```

Upload adds files (or drop them on a `droppable` library); Replace swaps the active file; Remove asks for confirmation, then calls `removeMedia()`; dragging reorders through `refreshMediaOrder()` when SortableJS is on the page; Crop opens a crop dialog when Cropper.js is on the page (`crop-config` passes its options). Editing the alt text sets `library.<key>.alt`, which `syncMedia()` saves with the rest of the item.

## Picking assets

Without `wire:model` the library is read-only: no upload, replace or remove. Pass `assets` and listen for `insert` (and `selection-change`), both with `$event.detail.value`, a list of ids.

```blade
<x-artisanpack-image-library
	:assets="$media->map( fn ( $item ) => [
		'id'   => (string) $item->id,
		'src'  => $item->url(),
		'name' => $item->file_name,
		'alt'  => $item->alt_text,
		'size' => $item->humanFileSize(),
	] )->all()"
	selectable="single"
	view="list"
	x-on:insert="$wire.choose( $event.detail.value[0] )"
/>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `assets` | array | `[]` | `[ 'id' => …, 'src' => …, 'name' => …, 'alt' => …, 'size' => …, 'width' => …, 'height' => …, 'type' => … ]`. |
| `preview` | Collection | none | A WithMediaSync library collection (`uuid`, `url`, `path`, `alt`), used when `assets` is empty. |
| `view` | string | `grid` | `grid` or `list`; the toolbar switches it. |
| `selectable` | string | `multiple` | `single` or `multiple`. |
| `accept` | string | `image/png, image/jpeg` | The file types uploads accept. |
| `droppable` | bool | `false` | Files dropped on the library upload. |
| `label` | string | `__( 'Media library' )` | Names the listbox. |
| `hint` | string | none | Help text under the grid. |
| `hide-errors` | bool | `false` | Hides validation errors for the library and its uploads. |
| `hide-progress` | bool | `false` | Hides the upload progress bar. |
| `crop-config` | array | `[]` | Cropper.js options, merged over the defaults. |
| `classes` | array | `[]` | Extra classes per part: `root`, `main`, `toolbar`, `search`, `view`, `bulk`, `count`, `progress`, `grid`, `asset`, `thumb`, `check`, `type`, `name`, `meta`, `empty`, `hint`, `error`, `details`, `preview`, `title`, `metadata`, `actions`, `dropzone`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- The `wire:model` / `wire:library` / `preview` contract with WithMediaSync is unchanged.
- The layout is new: a toolbar, a selectable grid and a details panel replace the sortable list with per-image buttons. "Change" is now Replace in the details panel.
- `change-text`, `crop-text`, `remove-text`, `crop-title-text`, `crop-cancel-text`, `crop-save-text`, `add-files-text` and `drag-drop-text` still override their strings. `with-drag-drop` still works but is deprecated: use `droppable`. `drag-drop-class` is deprecated: use `classes['dropzone']`. `drag-drop-multiple-text` is ignored.
- `accept` is a prop now; as an attribute it still worked in 2.x and still does.

## Accessibility

- The grid is a listbox (`aria-multiselectable` with multiple selection); each asset is an option with `aria-selected`, named by its file name, and only one is in the Tab order.
- Arrow keys move between assets (ArrowLeft and ArrowRight follow the text direction; Up and Down move a row), Home and End go to the ends, Space selects, Ctrl/Cmd+A selects every asset shown and Escape clears the selection.
- The selection count is announced through a status region.
- The alt text field is labelled and described in the details panel; Remove asks for confirmation.
