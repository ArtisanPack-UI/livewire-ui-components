# ImageGallery Component

The ImageGallery component provides a way to display a collection of images in a gallery format with lightbox functionality. When a user clicks on an image, it opens in a full-screen lightbox view powered by PhotoSwipe.

## Basic Usage

```php
@php
$images = [
    'https://example.com/image1.jpg',
    'https://example.com/image2.jpg',
    'https://example.com/image3.jpg'
];
@endphp

<x-artisanpack-image-gallery :images="$images" />
```

## Examples

### Basic Image Gallery

```php
@php
$images = [
    'https://example.com/image1.jpg',
    'https://example.com/image2.jpg',
    'https://example.com/image3.jpg'
];
@endphp

<x-artisanpack-image-gallery :images="$images" />
```

### Gallery with Navigation Arrows

```php
<x-artisanpack-image-gallery 
    :images="$images" 
    :withArrows="true"
/>
```

### Gallery with Indicators

```php
<x-artisanpack-image-gallery 
    :images="$images" 
    :withIndicators="true"
/>
```

### Gallery with Custom ID

```php
<x-artisanpack-image-gallery 
    :images="$images" 
    id="product-gallery"
/>
```

### Gallery with Custom Styling

```php
<x-artisanpack-image-gallery 
    :images="$images" 
    class="rounded-lg shadow-lg"
/>
```

### Gallery with Dynamic Images from Model

```php
<x-artisanpack-image-gallery 
    :images="$product->getMediaUrls()"
/>
```

### Gallery in a Grid Layout

```php
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-artisanpack-image-gallery 
        :images="$product->getMediaUrls('front')"
        class="h-64"
    />
    
    <x-artisanpack-image-gallery 
        :images="$product->getMediaUrls('back')"
        class="h-64"
    />
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `images` | array | - | Array of image URLs to display in the gallery (required) |
| `id` | string\|null | `null` | Optional ID for the gallery element |
| `withArrows` | boolean\|null | `false` | Whether to show navigation arrows |
| `withIndicators` | boolean\|null | `false` | Whether to show indicators for the images |

## Lightbox Functionality

The ImageGallery component uses PhotoSwipe for the lightbox functionality. When a user clicks on an image in the gallery, it opens in a full-screen lightbox view with the following features:

- Zoom in/out
- Swipe navigation between images
- Close button
- Full-screen mode
- Image information display
- Keyboard navigation

## Styling

The ImageGallery component uses DaisyUI's carousel component for the gallery display and PhotoSwipe for the lightbox functionality.

### Default Classes

- `pswp-gallery` - Base PhotoSwipe gallery class
- `pswp-gallery--single-column` - Single column layout for the gallery
- `carousel` - DaisyUI carousel class
- `carousel-item` - Applied to each image container
- `object-cover` - Makes images cover their container
- `hover:opacity-70` - Reduces opacity on hover for better user feedback

## Accessibility

The ImageGallery component follows accessibility best practices:

- Uses semantic HTML for proper structure
- Provides keyboard navigation in the lightbox view
- Includes proper image attributes for screen readers
- Maintains focus management when opening and closing the lightbox

## Related Components

- [Carousel](carousel.md) - Slideshow component for cycling through content
- [ImageLibrary](image-library.md) - Component for managing and selecting images
- [File](file.md) - File upload component that can be used for image uploads