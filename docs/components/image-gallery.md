# ImageGallery Component

The ImageGallery component provides a responsive grid-based layout for displaying collections of images with advanced features like filtering, pagination, lazy loading, and PhotoSwipe lightbox integration.

## Features

- **Responsive Grid Layout**: Configurable columns for different screen sizes
- **Aspect Ratio Control**: Consistent image sizing options (square, landscape, portrait, auto)
- **Gap Control**: Configurable spacing between images
- **PhotoSwipe Integration**: Full-screen lightbox viewing with zoom and navigation
- **Filtering System**: Category/tag-based image filtering
- **Lazy Loading**: Performance optimization with intersection observer
- **Pagination**: Optional pagination for large image sets
- **Loading States**: Multiple loading animation styles (skeleton, spinner, fade)
- **Captions**: Optional image captions with overlay display
- **Dark Mode**: Full dark mode compatibility
- **Accessibility**: WCAG 2.1 AA compliance

## Basic Usage

### Simple Image Array

```blade
<x-image-gallery 
    :images="[
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
        'https://example.com/image3.jpg',
        'https://example.com/image4.jpg'
    ]"
/>
```

### Image Objects with Metadata

```blade
<x-image-gallery 
    :images="[
        [
            'url' => 'https://example.com/image1.jpg',
            'alt' => 'Beautiful landscape',
            'caption' => 'Mountain sunrise in the Alps',
            'category' => 'nature',
            'width' => 1200,
            'height' => 800
        ],
        [
            'src' => 'https://example.com/image2.jpg',
            'alt' => 'City skyline',
            'caption' => 'Downtown at night',
            'tags' => ['urban', 'architecture'],
            'width' => 1600,
            'height' => 900
        ]
    ]"
    :show-captions="true"
/>
```

## Component Props

### Required Props

| Prop | Type | Description |
|------|------|-------------|
| `images` | `array` | Array of image URLs or image objects |

### Optional Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | `null` | Custom ID for the gallery |
| `columns` | `array` | `['default' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4, 'xl' => 5]` | Responsive column configuration |
| `aspectRatio` | `string` | `'square'` | Image aspect ratio: `square`, `landscape`, `portrait`, `auto` |
| `gap` | `string` | `'md'` | Gap between images: `xs`, `sm`, `md`, `lg`, `xl` |
| `enableLightbox` | `bool` | `true` | Enable PhotoSwipe lightbox |
| `showCaptions` | `bool` | `false` | Show image captions |
| `layout` | `string` | `'grid'` | Layout type: `grid`, `masonry` |
| `lazyLoad` | `bool` | `true` | Enable lazy loading |
| `filters` | `array\|null` | `null` | Category filters array |
| `itemsPerPage` | `int` | `0` | Items per page (0 = no pagination) |
| `loadingStyle` | `string` | `'skeleton'` | Loading style: `skeleton`, `spinner`, `fade` |

## Responsive Columns

Configure different column counts for various screen sizes:

```blade
<!-- Default responsive columns -->
<x-image-gallery :images="$images" />

<!-- Custom responsive configuration -->
<x-image-gallery 
    :images="$images"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'md' => 4,
        'lg' => 6,
        'xl' => 8
    ]"
/>

<!-- Simple 3-column grid -->
<x-image-gallery 
    :images="$images"
    :columns="['default' => 3]"
/>
```

## Aspect Ratios

Control the aspect ratio of image containers:

```blade
<!-- Square format (default) -->
<x-image-gallery :images="$images" aspect-ratio="square" />

<!-- Landscape format -->
<x-image-gallery :images="$images" aspect-ratio="landscape" />

<!-- Portrait format -->
<x-image-gallery :images="$images" aspect-ratio="portrait" />

<!-- Auto height (maintains original proportions) -->
<x-image-gallery :images="$images" aspect-ratio="auto" />
```

## Gap Control

Configure spacing between images:

```blade
<!-- Extra small gaps -->
<x-image-gallery :images="$images" gap="xs" />

<!-- Large gaps -->
<x-image-gallery :images="$images" gap="lg" />

<!-- Extra large gaps -->
<x-image-gallery :images="$images" gap="xl" />
```

## Filtering System

Enable category-based filtering:

```blade
<x-image-gallery 
    :images="$images"
    :filters="['nature', 'urban', 'architecture', 'people']"
/>
```

The filtering works with image objects that have `category` or `tags` properties:

```php
$images = [
    [
        'url' => 'image1.jpg',
        'category' => 'nature',
        // ...
    ],
    [
        'url' => 'image2.jpg',
        'tags' => ['urban', 'architecture'],
        // ...
    ]
];
```

## Pagination

Enable pagination for large image sets:

```blade
<!-- Show 12 images per page -->
<x-image-gallery 
    :images="$images"
    :items-per-page="12"
/>

<!-- Show 24 images per page with spinner loading -->
<x-image-gallery 
    :images="$images"
    :items-per-page="24"
    loading-style="spinner"
/>
```

## Captions

Display image captions with overlay:

```blade
<x-image-gallery 
    :images="$imagesWithCaptions"
    :show-captions="true"
/>
```

## Loading Styles

Choose from different loading animations:

```blade
<!-- Skeleton loading (default) -->
<x-image-gallery :images="$images" loading-style="skeleton" />

<!-- Spinner loading -->
<x-image-gallery :images="$images" loading-style="spinner" />

<!-- Fade loading -->
<x-image-gallery :images="$images" loading-style="fade" />
```

## Disable Features

```blade
<!-- Disable lightbox -->
<x-image-gallery 
    :images="$images"
    :enable-lightbox="false"
/>

<!-- Disable lazy loading -->
<x-image-gallery 
    :images="$images"
    :lazy-load="false"
/>
```

## Advanced Configuration

```blade
<!-- Fully customized gallery -->
<x-image-gallery 
    :images="$images"
    id="product-gallery"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6
    ]"
    aspect-ratio="landscape"
    gap="lg"
    :enable-lightbox="true"
    :show-captions="true"
    layout="grid"
    :lazy-load="true"
    :filters="$categories"
    :items-per-page="20"
    loading-style="skeleton"
    class="rounded-lg shadow-lg"
/>
```

## Image Object Structure

When using image objects, you can include rich metadata:

```php
$images = [
    [
        'url' => 'https://example.com/image1.jpg',      // Image URL (required)
        'alt' => 'Alternative text',                    // Alt text for accessibility
        'caption' => 'Image caption text',              // Caption for overlay
        'category' => 'nature',                         // Category for filtering
        'tags' => ['landscape', 'mountains'],           // Tags for filtering
        'width' => 1200,                                // Image width (for lightbox)
        'height' => 800,                                // Image height (for lightbox)
    ],
    [
        'src' => 'https://example.com/image2.jpg',      // Alternative to 'url'
        'alt' => 'Another image',
        'caption' => 'Another caption',
        'category' => 'urban',
        'width' => 1600,
        'height' => 900,
    ]
];
```

## Masonry Layout

Enable masonry (Pinterest-style) layout:

```blade
<x-image-gallery 
    :images="$images"
    layout="masonry"
    aspect-ratio="auto"
    :columns="[
        'default' => 2,
        'md' => 3,
        'lg' => 4
    ]"
/>
```

## Examples

### Photo Portfolio

```blade
<x-image-gallery 
    :images="$portfolioImages"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'lg' => 3
    ]"
    aspect-ratio="landscape"
    gap="lg"
    :show-captions="true"
    :enable-lightbox="true"
    class="max-w-6xl mx-auto"
/>
```

### Product Gallery with Filtering

```blade
<x-image-gallery 
    :images="$productImages"
    :columns="[
        'default' => 2,
        'md' => 4,
        'lg' => 6
    ]"
    aspect-ratio="square"
    gap="sm"
    :filters="['front', 'back', 'side', 'detail']"
    :items-per-page="24"
/>
```

### Blog Image Grid

```blade
<x-image-gallery 
    :images="$blogImages"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'md' => 3
    ]"
    aspect-ratio="landscape"
    :show-captions="true"
    :lazy-load="true"
    class="mb-8"
/>
```

### Instagram-style Feed

```blade
<x-image-gallery 
    :images="$feedImages"
    :columns="[
        'default' => 3,
        'md' => 3,
        'lg' => 3
    ]"
    aspect-ratio="square"
    gap="xs"
    :lazy-load="true"
    :items-per-page="21"
/>
```

## Performance Considerations

### Lazy Loading

The component uses Intersection Observer for efficient lazy loading:
- First 12 images load immediately
- Remaining images load when they come into view
- 50px root margin for smooth loading experience

### Optimization Tips

- Use appropriately sized images (recommended max width: 1200px for grid items)
- Consider WebP format for better compression
- Provide width/height in image objects for better layout stability
- Use consistent aspect ratios within the same gallery for better visual flow

## PhotoSwipe Integration

The component includes PhotoSwipe lightbox integration with:
- Full-screen viewing
- Zoom and pan support
- Touch/swipe navigation
- Keyboard navigation (ESC, arrows)
- Image information display

### Requirements

PhotoSwipe CSS and JavaScript must be included in your project:

```html
<!-- Add to your layout -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.css">
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.esm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe-lightbox.esm.js"></script>
```

## Accessibility Features

The ImageGallery component follows WCAG 2.1 AA guidelines:
- Proper ARIA labels and semantic HTML structure
- Keyboard navigation support in lightbox
- Screen reader announcements for filtering and pagination
- Focus management and high contrast support
- Alternative text for all images

## Styling and Customization

### Custom CSS Classes

```blade
<x-image-gallery 
    :images="$images"
    class="max-w-4xl mx-auto shadow-lg rounded-lg p-6"
/>
```

### Dark Mode

The component fully supports dark mode with automatic theme detection:
- Dark mode loading states
- Dark mode filter buttons
- Dark mode pagination controls

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- iOS Safari 12+
- Android Chrome 60+

## Related Components

- [ImageSlider](image-slider.md) - Carousel/slider for sequential image navigation
- [ImageLibrary](image-library.md) - Component for managing and selecting images
- [File](file.md) - File upload component for image uploads
- [Modal](modal.md) - Modal dialogs for image viewing

## Migration from Old ImageGallery

The old carousel-based ImageGallery has been replaced with this grid-based version. For carousel functionality, use the new [ImageSlider](image-slider.md) component:

```blade
<!-- Old carousel-style ImageGallery -->
<x-image-gallery :images="$images" :with-arrows="true" />

<!-- New grid-based ImageGallery -->
<x-image-gallery :images="$images" />

<!-- For carousel/slider functionality -->
<x-image-slider :images="$images" :with-arrows="true" />
```

See the [Migration Guide](../migration/image-gallery.md) for detailed migration instructions.
