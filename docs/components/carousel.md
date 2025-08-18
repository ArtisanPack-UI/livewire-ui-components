# Carousel Component

The Carousel component provides a slideshow for cycling through a series of images or content. It supports navigation arrows, indicators, autoplay, touch gestures for swiping, and customizable icons for navigation controls.

## Basic Usage

```php
@php
$slides = [
    [
        'image' => 'https://example.com/image1.jpg',
        'title' => 'Slide 1',
        'description' => 'This is the first slide'
    ],
    [
        'image' => 'https://example.com/image2.jpg',
        'title' => 'Slide 2',
        'description' => 'This is the second slide'
    ],
    [
        'image' => 'https://example.com/image3.jpg',
        'title' => 'Slide 3',
        'description' => 'This is the third slide'
    ]
];
@endphp

<x-artisanpack-carousel :slides="$slides" />
```

## Examples

### Carousel with Navigation Links

```php
@php
$slides = [
    [
        'image' => 'https://example.com/image1.jpg',
        'title' => 'Product Showcase',
        'description' => 'Check out our latest products',
        'url' => '/products',
        'urlText' => 'View Products'
    ],
    [
        'image' => 'https://example.com/image2.jpg',
        'title' => 'Special Offers',
        'description' => 'Limited time discounts',
        'url' => '/offers',
        'urlText' => 'See Offers'
    ]
];
@endphp

<x-artisanpack-carousel :slides="$slides" />
```

### Carousel without Indicators

```php
<x-artisanpack-carousel :slides="$slides" :withoutIndicators="true" />
```

### Carousel without Navigation Arrows

```php
<x-artisanpack-carousel :slides="$slides" :withoutArrows="true" />
```

### Autoplay Carousel

```php
<x-artisanpack-carousel :slides="$slides" :autoplay="true" :interval="5000" />
```

### Carousel with Custom Content

```php
<x-artisanpack-carousel :slides="$slides">
    <x-slot:content>
        @php($slide)
        <div class="absolute inset-0 flex items-center justify-center bg-black/50">
            <div class="text-center text-white">
                <h2 class="text-3xl font-bold">{{ $slide['title'] }}</h2>
                <p class="mt-2">{{ $slide['description'] }}</p>
                @if(isset($slide['url']))
                    <a href="{{ $slide['url'] }}" class="mt-4 btn btn-primary">
                        {{ $slide['urlText'] ?? 'Learn More' }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot:content>
</x-artisanpack-carousel>
```

### Carousel with Custom Styling

```php
<x-artisanpack-carousel :slides="$slides" class="h-96 rounded-none" />
```

## Icon Customization

The Carousel component allows you to customize the navigation arrows and dot indicators using either icon names or raw SVG content. This provides complete flexibility over the appearance of carousel controls.

### Custom Icon Names

You can use any Heroicon name for the navigation arrows and dot indicators:

```php
<x-artisanpack-carousel 
    :slides="$slides" 
    nextArrow="o-arrow-right"
    previousArrow="o-arrow-left"
    dots="o-star"
/>
```

### Raw SVG Icons

For complete control over the icon appearance, you can provide raw SVG content:

```php
<x-artisanpack-carousel 
    :slides="$slides"
    nextArrow="<svg viewBox='0 0 24 24' fill='currentColor'><path d='M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z'/></svg>"
    previousArrow="<svg viewBox='0 0 24 24' fill='currentColor'><path d='M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z'/></svg>"
    dots="<svg viewBox='0 0 8 8' fill='currentColor'><circle cx='4' cy='4' r='3'/></svg>"
/>
```

### Mixed Usage

You can also mix icon names and raw SVG content as needed:

```php
<x-artisanpack-carousel 
    :slides="$slides"
    nextArrow="<svg viewBox='0 0 24 24' stroke='currentColor' fill='none'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 18l6-6-6-6'/></svg>"
    previousArrow="<svg viewBox='0 0 24 24' stroke='currentColor' fill='none'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 18l-6-6 6-6'/></svg>"
    dots="o-stop-circle"
/>
```

### Default Behavior

When no custom icons are specified, the carousel uses default chevron icons for navigation arrows and CSS-styled dots for indicators:

- **Default Next Arrow**: `o-chevron-right`
- **Default Previous Arrow**: `o-chevron-left` 
- **Default Dots**: CSS-styled circular buttons

This ensures complete backward compatibility with existing implementations.

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `slides` | array | - | Array of slide data (required) |
| `id` | string\|null | `null` | Optional ID for the carousel element |
| `withoutIndicators` | boolean\|null | `false` | Whether to hide the slide indicators |
| `withoutArrows` | boolean\|null | `false` | Whether to hide the navigation arrows |
| `autoplay` | boolean\|null | `false` | Whether to automatically advance slides |
| `interval` | int\|null | `2000` | Time between slides in milliseconds when autoplay is enabled |
| `nextArrow` | mixed\|null | `null` | Custom icon for the next arrow (icon name or raw SVG) |
| `previousArrow` | mixed\|null | `null` | Custom icon for the previous arrow (icon name or raw SVG) |
| `dots` | mixed\|null | `null` | Custom icon for the dot indicators (icon name or raw SVG) |
| `ariaLabel` | string\|null | `null` | Accessible label for the carousel (overrides default) |
| `ariaLabelledBy` | string\|null | `null` | ID of element that labels the carousel |
| `respectsReducedMotion` | boolean\|null | `true` | Whether to respect user's reduced motion preference |

## Slots

| Slot | Description |
|------|-------------|
| `content` | Custom content for each slide, receives the current slide data as a parameter |

## Slide Format

Each slide in the `slides` array can have the following properties:

```php
[
    'image' => 'https://example.com/image.jpg', // URL of the image to display (required)
    'alt' => 'Descriptive alt text for the image', // Alt text for accessibility (recommended)
    'title' => 'Slide Title', // Title text to display
    'description' => 'Slide description text', // Description text to display
    'url' => '/some-page', // URL to navigate to when the slide is clicked
    'urlText' => 'Learn More' // Text for the button that links to the URL
]
```

**Note**: If no `alt` text is provided, the component will fallback to using the `title` property, or "Slide image" as a default. For optimal accessibility, always provide descriptive alt text.

## Styling

The Carousel component uses a combination of Tailwind CSS and Alpine.js for styling and interactivity. You can customize the appearance by:

1. Adding custom classes via the `class` attribute
2. Customizing the slide content using the `content` slot
3. Styling individual slides through the slide data

### Default Classes

- `h-64` - Default height for the carousel
- `rounded-box` - Rounded corners
- `overflow-hidden` - Ensures content doesn't overflow the container

## Accessibility

The Carousel component includes comprehensive accessibility features to ensure it's usable by all users:

### ARIA Support
- Uses `role="region"` with proper labeling for the main carousel container
- Implements `aria-live="polite"` to announce slide changes to screen readers
- Provides `aria-current="true"` for the active slide
- Labels all interactive elements with descriptive `aria-label` attributes

### Keyboard Navigation
- **Arrow Keys**: Use Left/Right arrow keys to navigate between slides
- **Home/End Keys**: Jump to first/last slide
- **Tab Navigation**: All interactive elements are keyboard accessible
- **Focus Management**: Clear visual focus indicators on all controls

### Screen Reader Support
- Images include meaningful alt text (uses slide 'alt', 'title', or defaults)
- Slide position announced ("Slide X of Y")
- Navigation buttons clearly labeled ("Previous slide", "Next slide")
- Indicator buttons labeled ("Go to slide X")

### Motion and Animation
- Respects `prefers-reduced-motion` setting
- Disables autoplay when users prefer reduced motion
- Maintains functionality without relying on animations

### Implementation Guidelines
To ensure your carousel is fully accessible:

1. **Always provide alt text for images**:
```php
$slides = [
    [
        'image' => 'path/to/image.jpg',
        'alt' => 'Descriptive text about the image content',
        'title' => 'Slide Title'
    ]
];
```

2. **Add a descriptive label for the carousel**:
```php
<x-artisanpack-carousel :slides="$slides" aria-label="Product showcase carousel" />
```

3. **Test with keyboard navigation and screen readers** to ensure proper functionality.

## Related Components

- [ImageGallery](image-gallery.md) - Grid display of images
- [ImageLibrary](image-library.md) - Image management component