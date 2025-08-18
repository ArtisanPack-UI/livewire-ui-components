---
title: ImageSlider Component
---

# ImageSlider Component

The ImageSlider component provides a powerful carousel/slider for sequential image navigation with full touch support, accessibility features, and PhotoSwipe lightbox integration.

## Features

- **Navigation Controls**: Previous/Next arrows with proper accessibility
- **Indicators**: Dot indicators showing current position and total count
- **Auto-play**: Optional automatic progression with configurable timing
- **Touch/Swipe Support**: Mobile-friendly touch navigation
- **Keyboard Navigation**: Arrow key support for accessibility
- **Lazy Loading**: Performance optimization for large image sets
- **PhotoSwipe Integration**: Full-screen lightbox viewing
- **Responsive Design**: Adapts to different screen sizes
- **Multiple Transitions**: Slide and fade effects
- **Accessibility**: Full WCAG 2.1 AA compliance

## Basic Usage

### Simple Image Array

```blade
<x-image-slider 
    :images="[
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
        'https://example.com/image3.jpg'
    ]"
/>
```

### Image Objects with Metadata

```blade
<x-image-slider 
    :images="[
        [
            'url' => 'https://example.com/image1.jpg',
            'alt' => 'Beautiful landscape',
            'width' => 1200,
            'height' => 800
        ],
        [
            'src' => 'https://example.com/image2.jpg',
            'alt' => 'City skyline',
            'width' => 1600,
            'height' => 900
        ]
    ]"
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
| `id` | `string\|null` | `null` | Custom ID for the slider |
| `withArrows` | `bool` | `true` | Show navigation arrows |
| `withIndicators` | `bool` | `true` | Show position indicators |
| `autoPlay` | `bool` | `false` | Enable auto-progression |
| `autoPlayInterval` | `int` | `5000` | Auto-play interval in milliseconds |
| `pauseOnHover` | `bool` | `true` | Pause auto-play on hover |
| `loop` | `bool` | `true` | Loop back to first after last |
| `transition` | `string` | `'slide'` | Transition type: `slide` or `fade` |
| `transitionDuration` | `int` | `300` | Transition duration in milliseconds |
| `showCounter` | `bool` | `false` | Show "X of Y" counter |
| `enableLightbox` | `bool` | `true` | Enable PhotoSwipe lightbox |
| `aspectRatio` | `string` | `'16:9'` | Container aspect ratio |

## Aspect Ratios

The component supports various aspect ratios:

- `'16:9'` - Standard widescreen (default)
- `'square'` or `'1:1'` - Square format
- `'4:3'` - Traditional landscape
- `'3:4'` - Portrait orientation
- `'21:9'` - Ultra-wide
- `'2:1'` - Panoramic

```blade
<!-- Square aspect ratio -->
<x-image-slider :images="$images" aspect-ratio="square" />

<!-- Ultra-wide format -->
<x-image-slider :images="$images" aspect-ratio="21:9" />
```

## Auto-play Configuration

```blade
<!-- Auto-play every 3 seconds, don't pause on hover -->
<x-image-slider 
    :images="$images"
    :auto-play="true"
    :auto-play-interval="3000"
    :pause-on-hover="false"
/>
```

## Navigation Options

```blade
<!-- No arrows, show indicators and counter -->
<x-image-slider 
    :images="$images"
    :with-arrows="false"
    :with-indicators="true"
    :show-counter="true"
/>
```

## Transition Effects

```blade
<!-- Fade transition -->
<x-image-slider 
    :images="$images"
    transition="fade"
    :transition-duration="500"
/>

<!-- Slide transition (default) -->
<x-image-slider 
    :images="$images"
    transition="slide"
    :transition-duration="300"
/>
```

## Disable Lightbox

```blade
<!-- Disable PhotoSwipe lightbox -->
<x-image-slider 
    :images="$images"
    :enable-lightbox="false"
/>
```

## Advanced Configuration

```blade
<!-- Fully customized slider -->
<x-image-slider 
    :images="$images"
    id="hero-slider"
    :with-arrows="true"
    :with-indicators="true"
    :auto-play="true"
    :auto-play-interval="4000"
    :pause-on-hover="true"
    :loop="true"
    transition="slide"
    :transition-duration="400"
    :show-counter="true"
    :enable-lightbox="true"
    aspect-ratio="21:9"
    class="shadow-lg rounded-xl"
/>
```

## Image Object Structure

When using image objects instead of simple URLs, you can include additional metadata:

```php
$images = [
    [
        'url' => 'https://example.com/image1.jpg',      // Image URL (required)
        'alt' => 'Alternative text',                    // Alt text for accessibility
        'width' => 1200,                                // Image width (for lightbox)
        'height' => 800,                                // Image height (for lightbox)
    ],
    [
        'src' => 'https://example.com/image2.jpg',      // Alternative to 'url'
        'alt' => 'Another image',
        'width' => 1600,
        'height' => 900,
    ]
];
```

## Keyboard Navigation

The slider automatically supports keyboard navigation:
- **Left Arrow**: Previous image
- **Right Arrow**: Next image
- **Tab**: Focus navigation controls
- **Enter/Space**: Activate focused control

## Touch/Swipe Support

The component includes full touch and swipe support:
- **Swipe Left**: Next image
- **Swipe Right**: Previous image
- Configurable swipe threshold (50px by default)
- Smooth touch animations

## Accessibility Features

The ImageSlider component follows WCAG 2.1 AA guidelines:

- Proper ARIA labels and roles
- Keyboard navigation support
- Screen reader announcements
- Focus management
- High contrast support
- Semantic HTML structure

## Styling and Customization

### Custom CSS Classes

```blade
<x-image-slider 
    :images="$images"
    class="max-w-4xl mx-auto shadow-2xl rounded-2xl"
/>
```

### Custom Styling with Tailwind

The component uses Tailwind CSS classes and supports dark mode:

```blade
<!-- Custom width and shadow -->
<x-image-slider 
    :images="$images"
    class="w-full max-w-6xl mx-auto shadow-xl"
/>
```

## Performance Considerations

### Lazy Loading

The component automatically lazy loads images after the first one:
- First image loads immediately
- Subsequent images load as needed
- Preloading of next/previous images for smooth transitions

### Optimization Tips

- Use appropriately sized images (recommended max width: 1920px)
- Consider WebP format for better compression
- Provide width/height in image objects for better layout stability
- Use consistent aspect ratios for smoother transitions

## PhotoSwipe Integration

The component includes PhotoSwipe lightbox integration:

### Features
- Full-screen image viewing
- Zoom and pan support
- Touch/swipe navigation in lightbox
- Keyboard navigation (ESC, arrows)
- Sharing capabilities

### Requirements
- PhotoSwipe CSS and JavaScript must be included in your project
- Component automatically initializes PhotoSwipe when available

```html
<!-- Add to your layout -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.css">
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.esm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe-lightbox.esm.js"></script>
```

## Browser Support

The ImageSlider component supports:
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- iOS Safari 12+
- Android Chrome 60+

### Fallback Behavior
- Graceful degradation when JavaScript is disabled
- Touch events fallback for older browsers
- PhotoSwipe fallback to regular image links

## Examples

### Hero Slider

```blade
<x-image-slider 
    :images="$heroImages"
    id="hero-slider"
    :auto-play="true"
    :auto-play-interval="6000"
    aspect-ratio="21:9"
    transition="fade"
    :transition-duration="800"
    :show-counter="true"
    class="w-full h-96 md:h-[500px] rounded-lg overflow-hidden"
/>
```

### Product Gallery

```blade
<x-image-slider 
    :images="$productImages"
    id="product-gallery"
    :with-arrows="true"
    :with-indicators="true"
    :auto-play="false"
    aspect-ratio="square"
    :enable-lightbox="true"
    class="max-w-md mx-auto"
/>
```

### Testimonial Carousel

```blade
<x-image-slider 
    :images="$testimonialImages"
    :with-arrows="false"
    :with-indicators="true"
    :auto-play="true"
    :auto-play-interval="8000"
    transition="fade"
    :show-counter="false"
    aspect-ratio="4:3"
    class="max-w-2xl mx-auto"
/>
```

## Related Components

- [ImageGallery](image-gallery.md) - Grid-based image display
- [Carousel](carousel.md) - Generic content carousel
- [Modal](modal.md) - Modal dialogs for image viewing

## Migration from ImageGallery

If you're migrating from the old ImageGallery component that was carousel-based:

```blade
<!-- Old ImageGallery (carousel-style) -->
<x-image-gallery :images="$images" :with-arrows="true" />

<!-- New ImageSlider (enhanced carousel) -->
<x-image-slider :images="$images" :with-arrows="true" :with-indicators="true" />
```

See the [Migration Guide](../migration/image-gallery.md) for detailed migration instructions.
