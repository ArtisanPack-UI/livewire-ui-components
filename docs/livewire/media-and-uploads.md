---
title: Media and Uploads
menu_order: 5
---

# Media and Uploads

Three components upload files: [File](https://docs.artisanpackui.dev/documentation/design-system/components/file) and [ImageLibrary](https://docs.artisanpackui.dev/documentation/design-system/components/image-library) through Livewire's file uploads, and [RichTextEditor](https://docs.artisanpackui.dev/documentation/design-system/components/rich-text-editor) through the package's upload route.

## File

`wire:model` lands on the native file input, so File uploads like any Livewire file input. Use Livewire's `WithFileUploads` trait and validate the files in your component:

```php
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    #[Validate( 'image|max:2048' )]
    public $photo;
}
```

```blade
<x-artisanpack-file :label="__( 'Photo' )" wire:model="photo" accept="image/*" />
```

Without a `value`, the file list reads the `wire:model` property of the Livewire component that renders it.

## ImageLibrary

ImageLibrary is a media manager: search, a grid or list view, single or multiple selection, uploads and a details panel with editable alt text. It exists only in Livewire UI Components.

### With `WithMediaSync`

The `WithMediaSync` trait keeps a library collection in sync with uploads and saves it to a model. `wire:model` names the uploads property, `wire:library` the library collection, and `preview` passes the collection in:

```php
use ArtisanPack\LivewireUiComponents\Traits\WithMediaSync;
use Illuminate\Support\Collection;
use Livewire\WithFileUploads;

class EditListing extends Component
{
    use WithFileUploads;
    use WithMediaSync;

    public Listing $listing;

    public array $files = [];

    public Collection $library;

    public function mount(): void
    {
        $this->library = $this->listing->library ?? collect();
    }

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
    droppable
/>
```

The library's actions call the trait:

| Action | Calls |
|---|---|
| Upload, or drop files on a `droppable` library | `refreshMediaSources()` |
| Replace (the 2.x "Change") | `refreshMediaSources()` for the replaced item |
| Remove, after a confirmation | `removeMedia()` |
| Drag to reorder, when SortableJS is on the page | `refreshMediaOrder()` |
| Crop, when Cropper.js is on the page | A crop dialog; `crop-config` passes Cropper.js options |
| Editing the alt text | Sets `library.<key>.alt` |

`syncMedia( $model, $library = 'library', $files = 'files', $storage_subpath = '', $model_field = 'library', $visibility = 'public', $disk = 'public' )` stores the uploads on the disk, deletes the files removed from the library, and saves the library (each item with its `uuid`, `url`, `path` and alt text) to the model's field. Cast that field to a collection on the model.

### Picking assets

Without `wire:model`, the library is read-only: no upload, replace or remove. Pass `assets` and listen for `insert` or `selection-change`, whose `$event.detail.value` is a list of ids:

```blade
<x-artisanpack-image-library
    :assets="$media"
    selectable="single"
    x-on:insert="$wire.choose( $event.detail.value[0] )"
/>
```

Each asset takes an `id`, `src`, `name`, `alt`, `size`, `width`, `height` and `type`.

## RichTextEditor image uploads

RichTextEditor runs on TinyMCE when the page has loaded it, and on a built-in editor otherwise. The package includes a copy of TinyMCE; publish it to `public/vendor/artisanpack-ui/js` with:

```bash
php artisan vendor:publish --tag=artisanpack-assets
```

With TinyMCE, images upload to the package's `artisanpack.upload` route (`POST /artisanpack/upload`), which returns the stored file's URL. The route:

- requires an authenticated user;
- accepts images only: the types in the `uploads.mimes` config, never SVG, up to `uploads.max_size` kilobytes;
- stores on the editor's `disk`, which must be one of the `uploads.disks` config (the first is the default), in its `folder` (`editor` by default).

```blade
<x-artisanpack-rich-text-editor :label="__( 'Description' )" wire:model="description" disk="s3" folder="listings" />
```

See [Configuration](Getting-Started-Configuration) for the `uploads` settings. The built-in editor inserts images by URL and doesn't upload.
