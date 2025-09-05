---
title: ImageGallery Component Migration Guide
---

# ImageGallery Component Migration Guide

This guide helps you migrate from the old carousel-based ImageGallery component to the new ImageSlider and grid-based ImageGallery components.

## Overview of Changes

The original ImageGallery component has been split into two specialized components:

1. **ImageSlider** - Enhanced carousel/slider for sequential image navigation
2. **ImageGallery** - Grid-based layout for displaying image collections

## Why the Change?

The original ImageGallery was essentially a basic carousel, which didn't align with the typical expectations of an "image gallery" component. The new architecture provides:

- **Better separation of concerns**: Carousel functionality vs. grid display
- **Enhanced features**: Both components now have significantly more capabilities
- **Improved performance**: Better lazy loading, touch support, and accessibility
- **Modern UX patterns**: Grid galleries are more common and expected

## Migration Scenarios

### Scenario 1: You Used ImageGallery as a Carousel

If you were using ImageGallery for carousel/slider functionality, migrate to **ImageSlider**:

#### Before (Old ImageGallery)
```blade
<x-image-gallery 
    :images="$images" 
    :with-arrows="true"
    :with-indicators="true"
/>
```

#### After (New ImageSlider)
```blade
<x-image-slider 
    :images="$images" 
    :with-arrows="true"
    :with-indicators="true"
/>
```

### Scenario 2: You Want a True Image Gallery Grid

If you want a proper grid-based image gallery, use the new **ImageGallery**:

#### Before (Old ImageGallery)
```blade
<x-image-gallery :images="$images" />
```

#### After (New ImageGallery)
```blade
<x-image-gallery :images="$images" />
```

## Breaking Changes

### Removed Props

The following props from the old ImageGallery are **no longer available** in the new ImageGallery:

- `withArrows` - Not applicable to grid layout
- `withIndicators` - Not applicable to grid layout

These props are now available in the ImageSlider component.

### New Props

The new ImageGallery introduces many new props:

- `columns` - Responsive column configuration
- `aspectRatio` - Control image aspect ratios
- `gap` - Control spacing between images
- `showCaptions` - Display image captions
- `layout` - Grid or masonry layout
- `lazyLoad` - Lazy loading control
- `filters` - Category filtering
- `itemsPerPage` - Pagination
- `loadingStyle` - Loading animation style

## Migration Steps

### Step 1: Identify Your Use Case

Determine what you were using the old ImageGallery for:

**Use ImageSlider if you need:**
- Sequential image navigation
- Carousel/slideshow functionality  
- Auto-play features
- Arrow navigation
- Dot indicators

**Use ImageGallery if you need:**
- Grid display of images
- Multiple images visible at once
- Filtering capabilities
- Pagination
- Masonry layouts

### Step 2: Update Component Tags

Replace your existing ImageGallery components:

```blade
<!-- For carousel functionality -->
<x-image-gallery :images="$images" :with-arrows="true" />
<!-- Replace with -->
<x-image-slider :images="$images" :with-arrows="true" />

<!-- For grid display -->
<x-image-gallery :images="$images" />
<!-- Keep as is, but consider new props -->
<x-image-gallery :images="$images" />
```

### Step 3: Update Props

#### ImageSlider Migration

```blade
<!-- Old ImageGallery props -->
<x-image-gallery 
    :images="$images"
    id="hero-gallery"
    :with-arrows="true"
    :with-indicators="true"
/>

<!-- New ImageSlider equivalent -->
<x-image-slider 
    :images="$images"
    id="hero-gallery"
    :with-arrows="true"
    :with-indicators="true"
    :auto-play="false"
    :enable-lightbox="true"
    aspect-ratio="16:9"
/>
```

#### ImageGallery Migration

```blade
<!-- Old ImageGallery -->
<x-image-gallery :images="$images" />

<!-- New ImageGallery with enhanced features -->
<x-image-gallery 
    :images="$images"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4
    ]"
    aspect-ratio="square"
    gap="md"
    :enable-lightbox="true"
/>
```

### Step 4: Update Image Data Structure

Both new components support enhanced image objects:

#### Old Format (Still Supported)
```php
$images = [
    'https://example.com/image1.jpg',
    'https://example.com/image2.jpg'
];
```

#### New Enhanced Format
```php
$images = [
    [
        'url' => 'https://example.com/image1.jpg',
        'alt' => 'Beautiful landscape',
        'caption' => 'Mountain sunrise',
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
];
```

## Common Migration Examples

### Example 1: Product Gallery

#### Before
```blade
<x-image-gallery 
    :images="$product->images"
    :with-arrows="true"
    class="w-full h-64"
/>
```

#### After (Carousel Style)
```blade
<x-image-slider 
    :images="$product->images"
    :with-arrows="true"
    :with-indicators="true"
    aspect-ratio="4:3"
    :enable-lightbox="true"
    class="w-full h-64"
/>
```

#### After (Grid Style)
```blade
<x-image-gallery 
    :images="$product->images"
    :columns="[
        'default' => 2,
        'md' => 4
    ]"
    aspect-ratio="square"
    gap="sm"
    :enable-lightbox="true"
    class="w-full"
/>
```

### Example 2: Hero Slideshow

#### Before
```blade
<x-image-gallery 
    :images="$heroImages"
    :with-arrows="true"
    :with-indicators="true"
    class="h-96 w-full"
/>
```

#### After
```blade
<x-image-slider 
    :images="$heroImages"
    :with-arrows="true"
    :with-indicators="true"
    :auto-play="true"
    :auto-play-interval="5000"
    aspect-ratio="21:9"
    transition="fade"
    :show-counter="true"
    class="h-96 w-full"
/>
```

### Example 3: Portfolio Gallery

#### Before
```blade
<x-image-gallery 
    :images="$portfolioImages"
    class="grid grid-cols-3 gap-4"
/>
```

#### After
```blade
<x-image-gallery 
    :images="$portfolioImages"
    :columns="[
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4
    ]"
    aspect-ratio="landscape"
    gap="lg"
    :show-captions="true"
    :filters="['web', 'mobile', 'design']"
    :items-per-page="12"
/>
```

## Feature Comparison

| Feature | Old ImageGallery | New ImageSlider | New ImageGallery |
|---------|------------------|-----------------|------------------|
| Carousel Navigation | ✅ Basic | ✅ Enhanced | ❌ |
| Grid Display | ❌ | ❌ | ✅ |
| Auto-play | ❌ | ✅ | ❌ |
| Touch/Swipe | ❌ | ✅ | ❌ |
| Keyboard Navigation | ❌ | ✅ | ❌ |
| Indicators | ⚠️ Not Implemented | ✅ | ❌ |
| Arrows | ⚠️ Not Implemented | ✅ | ❌ |
| PhotoSwipe | ✅ Basic | ✅ Enhanced | ✅ Enhanced |
| Responsive Design | ❌ | ✅ | ✅ |
| Lazy Loading | ❌ | ✅ | ✅ |
| Filtering | ❌ | ❌ | ✅ |
| Pagination | ❌ | ❌ | ✅ |
| Captions | ❌ | ❌ | ✅ |
| Aspect Ratio Control | ❌ | ✅ | ✅ |
| Loading States | ❌ | ✅ | ✅ |
| Dark Mode | ❌ | ✅ | ✅ |
| Accessibility | ⚠️ Basic | ✅ Full | ✅ Full |

## Testing Your Migration

After migrating, test the following:

### For ImageSlider
1. **Navigation**: Arrows and indicators work correctly
2. **Auto-play**: Starts/stops as configured
3. **Touch/Swipe**: Works on mobile devices
4. **Keyboard**: Arrow keys navigate images
5. **Lightbox**: PhotoSwipe opens correctly
6. **Responsive**: Looks good on all screen sizes

### For ImageGallery
1. **Grid Layout**: Columns respond to screen size
2. **Aspect Ratios**: Images display with correct proportions
3. **Filtering**: Filter buttons work if enabled
4. **Pagination**: Page navigation works if enabled
5. **Lazy Loading**: Images load as you scroll
6. **Lightbox**: PhotoSwipe opens correctly
7. **Captions**: Display correctly if enabled

## Troubleshooting

### PhotoSwipe Not Working

Ensure PhotoSwipe is properly included:

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.css">
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.esm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe-lightbox.esm.js"></script>
```

### Styling Issues

The new components use different CSS classes:

#### Old Classes (Remove These)
- `carousel`
- `carousel-item`
- `pswp-gallery--single-column`

#### New Classes (Automatic)
- Grid-based layout classes
- Responsive column classes
- Tailwind aspect ratio classes

### JavaScript Errors

Common issues and solutions:

1. **Alpine.js not loaded**: Ensure Alpine.js is included
2. **PhotoSwipe errors**: Check PhotoSwipe is loaded correctly
3. **Touch events not working**: Update to latest Alpine.js version

## Performance Considerations

### Image Optimization

Both new components support better image optimization:

```php
// Provide width/height for better performance
$images = [
    [
        'url' => 'image1.jpg',
        'width' => 1200,
        'height' => 800,
        'alt' => 'Description'
    ]
];
```

### Lazy Loading

The new components include smart lazy loading:
- ImageSlider: Preloads next/previous images
- ImageGallery: Uses Intersection Observer for efficient loading

## Support and Help

If you encounter issues during migration:

1. Check the component documentation:
   - [ImageSlider Documentation](../components/image-slider)
   - [ImageGallery Documentation](../components/image-gallery)

2. Review your PhotoSwipe integration
3. Test with simple examples before complex implementations
4. Verify your Alpine.js and Tailwind CSS setup

## Timeline for Migration

- **Immediate**: Both old and new components work simultaneously
- **Recommended**: Migrate new projects to new components immediately
- **Existing Projects**: Plan migration during next maintenance window
- **Deprecation**: Old ImageGallery may be deprecated in future versions

The new components provide significantly more functionality and better user experience, making the migration effort worthwhile for most applications.
