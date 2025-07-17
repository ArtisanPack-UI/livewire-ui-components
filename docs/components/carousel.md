# Carousel Component

The Carousel component provides a slideshow for cycling through a series of images or content. It supports navigation arrows, indicators, autoplay, and touch gestures for swiping.

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

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `slides` | array | - | Array of slide data (required) |
| `id` | string\|null | `null` | Optional ID for the carousel element |
| `withoutIndicators` | boolean\|null | `false` | Whether to hide the slide indicators |
| `withoutArrows` | boolean\|null | `false` | Whether to hide the navigation arrows |
| `autoplay` | boolean\|null | `false` | Whether to automatically advance slides |
| `interval` | int\|null | `2000` | Time between slides in milliseconds when autoplay is enabled |

## Slots

| Slot | Description |
|------|-------------|
| `content` | Custom content for each slide, receives the current slide data as a parameter |

## Slide Format

Each slide in the `slides` array can have the following properties:

```php
[
    'image' => 'https://example.com/image.jpg', // URL of the image to display (required)
    'title' => 'Slide Title', // Title text to display
    'description' => 'Slide description text', // Description text to display
    'url' => '/some-page', // URL to navigate to when the slide is clicked
    'urlText' => 'Learn More' // Text for the button that links to the URL
]
```

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

The Carousel component follows accessibility best practices:

- Uses appropriate ARIA attributes for the slide indicators
- Provides keyboard navigation support
- Includes visible controls for navigating between slides
- Supports touch gestures for mobile devices

## Related Components

- [ImageGallery](image-gallery.md) - Grid display of images
- [ImageLibrary](image-library.md) - Image management component