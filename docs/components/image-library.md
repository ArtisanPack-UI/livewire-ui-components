---
title: ImageLibrary Component
---

# ImageLibrary Component

The ImageLibrary component provides a comprehensive solution for managing multiple images with features like uploading, cropping, reordering, and removing images. It's ideal for product galleries, article attachments, or any scenario where multiple image management is needed.

## Basic Usage

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
/>
```

## Examples

### Basic Image Library

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
/>
```

### Image Library with Hint

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    hint="Upload up to 5 images. Recommended size: 1200x800px."
/>
```

### Image Library with Custom Text

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    addFilesText="Upload Images"
    changeText="Replace"
    cropText="Edit"
    removeText="Delete"
    cropTitleText="Edit Image"
    cropCancelText="Discard"
    cropSaveText="Apply Changes"
/>
```

### Image Library with Custom Crop Configuration

```php
@php
$cropConfig = [
    'aspectRatio' => 16/9,
    'viewMode' => 2,
    'guides' => true,
    'rotatable' => true,
];
@endphp

<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :cropConfig="$cropConfig"
/>
```

### Image Library with Hidden Progress Bar

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :hideProgress="true"
/>
```

### Image Library with Hidden Errors

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :hideErrors="true"
/>
```

### Image Library with Custom MIME Types

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    accept="image/png, image/jpeg, image/gif"
/>
```

### Image Library with Drag and Drop

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :with-drag-drop="true"
/>
```

### Image Library with Custom Drag and Drop Text

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :with-drag-drop="true"
    drag-drop-text="Drop your images here"
    drag-drop-multiple-text="Drop {count} images here"
/>
```

### Image Library with Custom Drag and Drop Styling

```php
<x-artisanpack-image-library 
    wire:model="images" 
    wire:library="productImages" 
    label="Product Images"
    :with-drag-drop="true"
    drag-drop-text="Drop images to upload"
    drag-drop-class="border-info bg-info/10"
/>
```

## Livewire Component Integration

To use the ImageLibrary component, you need to implement several methods in your Livewire component:

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;

class ProductForm extends Component
{
    use WithFileUploads;

    public $images = [];
    public $productImages;
    
    public function mount($product = null)
    {
        if ($product) {
            $this->productImages = $product->getMedia('images')->map(function ($media) {
                return [
                    'uuid' => $media->uuid,
                    'url' => $media->getUrl(),
                ];
            });
        } else {
            $this->productImages = new Collection();
        }
    }
    
    // Required method to remove media
    public function removeMedia($uuid, $model, $library, $url)
    {
        // Logic to remove the media with the given UUID
        // Example: Media::where('uuid', $uuid)->delete();
        
        // Update the preview collection
        $this->$library = $this->$library->filter(function ($item) use ($uuid) {
            return $item['uuid'] !== $uuid;
        });
    }
    
    // Required method to refresh media order
    public function refreshMediaOrder($order, $library)
    {
        // Logic to update the order of media items
        // $order contains an array of UUIDs in the new order
        
        // Example: Update the order in your database
    }
    
    // Required method to refresh media sources
    public function refreshMediaSources($model, $library)
    {
        // Logic to refresh the media sources after uploads or changes
        // This should update the preview collection with the latest media items
        
        // Example: Reload media from database and update the preview collection
    }
    
    public function render()
    {
        return view('livewire.product-form');
    }
}
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the component |
| `label` | string\|null | `null` | Optional label for the component |
| `hint` | string\|null | `null` | Optional hint text displayed below the component |
| `hideErrors` | boolean\|null | `false` | Whether to hide error messages |
| `hideProgress` | boolean\|null | `false` | Whether to hide the progress bar |
| `changeText` | string\|null | `"Change"` | Text for the change image tooltip |
| `cropText` | string\|null | `"Crop"` | Text for the crop image tooltip |
| `removeText` | string\|null | `"Remove"` | Text for the remove image tooltip |
| `cropTitleText` | string\|null | `"Crop image"` | Title for the crop modal |
| `cropCancelText` | string\|null | `"Cancel"` | Text for the cancel button in the crop modal |
| `cropSaveText` | string\|null | `"Crop"` | Text for the save button in the crop modal |
| `addFilesText` | string\|null | `"Add images"` | Text for the add files button |
| `cropConfig` | array\|null | `[]` | Configuration options for the cropper |
| `preview` | Collection | `new Collection()` | Collection of images to display in the preview area |
| `withDragDrop` | boolean\|null | `false` | Whether to enable drag and drop functionality |
| `dragDropText` | string\|null | `"Drop images here"` | Text displayed in the drag and drop overlay |
| `dragDropMultipleText` | string\|null | `"Drop {count} images here"` | Text for multiple files with {count} placeholder |
| `dragDropClass` | string\|null | `null` | Additional CSS classes for drag and drop styling |

## Wire Directives

| Directive | Description |
|-----------|-------------|
| `wire:model` | The Livewire property to bind the uploaded files to |
| `wire:library` | The Livewire property that contains the preview collection |

## Crop Configuration

The `cropConfig` prop accepts an array of options for the Cropper.js library. Some common options include:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `aspectRatio` | number | `null` | The aspect ratio of the crop box |
| `viewMode` | number | `1` | The view mode of the cropper |
| `guides` | boolean | `true` | Whether to show the grid lines in the crop box |
| `rotatable` | boolean | `false` | Whether to allow rotation of the image |
| `zoomable` | boolean | `true` | Whether to allow zooming of the image |

For a complete list of options, refer to the [Cropper.js documentation](https://github.com/fengyuanchen/cropperjs#options).

## Styling

The ImageLibrary component uses a combination of DaisyUI and custom styling for a clean, modern appearance.

### Default Classes

- `fieldset` - Container for the component
- `fieldset-legend` - Applied to the label
- `fieldset-label` - Applied to the hint text
- `border-[length:var(--border)] border-base-content/10 border-dotted rounded-lg` - Styling for the preview area
- `progress progress-primary` - Styling for the progress bar
- `btn btn-block` - Styling for the add files button

## Accessibility

The ImageLibrary component follows accessibility best practices:

- Uses semantic HTML with proper fieldset and legend elements
- Provides clear visual feedback for actions
- Includes proper labeling for interactive elements
- Maintains focus management when opening and closing the crop modal
- Supports keyboard navigation

## Related Components

- [ImageGallery](image-gallery.md) - Component for displaying images in a gallery format
- [File](file.md) - Single file upload component
- [Modal](modal.md) - Used for the crop dialog
