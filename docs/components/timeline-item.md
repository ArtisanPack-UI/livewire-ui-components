# TimelineItem Component

The TimelineItem component represents a single event or milestone within a chronological sequence. It's designed to be used within a timeline layout to display a series of events in order.

## Basic Usage

```php
<div class="timeline">
    <x-artisanpack-timeline-item>
        <x-slot:time>2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <p>Our company was established with a mission to create innovative solutions.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:time>2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <p>We launched our flagship product to great acclaim.</p>
    </x-artisanpack-timeline-item>
</div>
```

## Examples

### Basic Timeline Items

```php
<div class="timeline">
    <x-artisanpack-timeline-item>
        <x-slot:time>2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <p>Our company was established with a mission to create innovative solutions.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:time>2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <p>We launched our flagship product to great acclaim.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:time>2025</x-slot:time>
        <x-slot:title>International Expansion</x-slot:title>
        <p>We expanded our operations to international markets.</p>
    </x-artisanpack-timeline-item>
</div>
```

### Timeline Items with Icons

```php
<div class="timeline">
    <x-artisanpack-timeline-item>
        <x-slot:icon>
            <x-artisanpack-icon name="heroicon-o-building-office" class="w-5 h-5" />
        </x-slot:icon>
        <x-slot:time>2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <p>Our company was established with a mission to create innovative solutions.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:icon>
            <x-artisanpack-icon name="heroicon-o-rocket-launch" class="w-5 h-5" />
        </x-slot:icon>
        <x-slot:time>2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <p>We launched our flagship product to great acclaim.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:icon>
            <x-artisanpack-icon name="heroicon-o-globe-alt" class="w-5 h-5" />
        </x-slot:icon>
        <x-slot:time>2025</x-slot:time>
        <x-slot:title>International Expansion</x-slot:title>
        <p>We expanded our operations to international markets.</p>
    </x-artisanpack-timeline-item>
</div>
```

### Timeline Items with Different Colors

```php
<div class="timeline">
    <x-artisanpack-timeline-item color="primary">
        <x-slot:time>2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <p>Our company was established with a mission to create innovative solutions.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item color="secondary">
        <x-slot:time>2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <p>We launched our flagship product to great acclaim.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item color="accent">
        <x-slot:time>2025</x-slot:time>
        <x-slot:title>International Expansion</x-slot:title>
        <p>We expanded our operations to international markets.</p>
    </x-artisanpack-timeline-item>
</div>
```

### Timeline Items with End Positions

```php
<div class="timeline">
    <x-artisanpack-timeline-item>
        <x-slot:time>2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <p>Our company was established with a mission to create innovative solutions.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item end="right">
        <x-slot:time>2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <p>We launched our flagship product to great acclaim.</p>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:time>2025</x-slot:time>
        <x-slot:title>International Expansion</x-slot:title>
        <p>We expanded our operations to international markets.</p>
    </x-artisanpack-timeline-item>
</div>
```

### Timeline Items with Rich Content

```php
<div class="timeline">
    <x-artisanpack-timeline-item>
        <x-slot:time>January 2023</x-slot:time>
        <x-slot:title>Company Founded</x-slot:title>
        <div class="prose">
            <p>Our company was established with a mission to create innovative solutions.</p>
            <ul>
                <li>Initial team of 5 members</li>
                <li>Secured seed funding</li>
                <li>Established headquarters</li>
            </ul>
            <x-artisanpack-button size="sm">Read More</x-artisanpack-button>
        </div>
    </x-artisanpack-timeline-item>
    
    <x-artisanpack-timeline-item>
        <x-slot:time>June 2024</x-slot:time>
        <x-slot:title>Product Launch</x-slot:title>
        <div>
            <p class="mb-3">We launched our flagship product to great acclaim.</p>
            <img src="https://example.com/product-launch.jpg" alt="Product Launch" class="rounded-lg mb-3">
            <div class="flex gap-2">
                <x-artisanpack-button size="sm">View Gallery</x-artisanpack-button>
                <x-artisanpack-button size="sm" outline>Press Release</x-artisanpack-button>
            </div>
        </div>
    </x-artisanpack-timeline-item>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `color` | string | `null` | The color of the timeline item (`primary`, `secondary`, `accent`, `info`, `success`, `warning`, `error`) |
| `end` | string | `null` | The position of the timeline item (`left`, `right`) |
| `id` | string | `null` | Optional ID for the timeline item element |

## Slots

| Slot | Description |
|------|-------------|
| `icon` | Optional icon to display in the timeline dot |
| `time` | The time or date of the timeline event |
| `title` | The title or heading of the timeline event |
| `default` | The main content or description of the timeline event |

## Behavior

The TimelineItem component:

1. Represents a single event within a chronological sequence
2. Displays a dot or icon on the timeline to mark the event
3. Shows the time, title, and content of the event
4. Can be positioned on the left or right side of the timeline
5. Is designed to be used within a container with the `timeline` class

## Styling

The TimelineItem component uses DaisyUI's timeline component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`color`, `end`)
2. Adding custom classes via the `class` attribute
3. Customizing the icon, time, title, and content through the slots

### Default Classes

- The timeline item has a dot or icon that connects to the timeline line
- The time is displayed with reduced opacity
- The title is displayed with larger, bold text
- The content is displayed with normal text styling
- Items alternate between left and right sides by default in horizontal layouts

## Accessibility

The TimelineItem component follows accessibility best practices:

- Uses semantic HTML structure
- Maintains proper color contrast for readability
- Supports screen readers with appropriate text hierarchy

For better accessibility:

1. Ensure time and date information is clear and consistent
2. Maintain sufficient color contrast, especially when using custom colors
3. Use clear, descriptive titles for each timeline item
4. Consider adding additional context for screen readers about the chronological relationship between items

## Related Components

- There is no specific container component for timelines; use a `<div class="timeline">` as the container
- [Icon](icon.md) - Component for displaying SVG icons in the timeline dot
- [Card](card.md) - Can be used within timeline items for more structured content