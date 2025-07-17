# Stat Component

The Stat component displays key metrics or statistics in a visually appealing and structured format. It's ideal for dashboards, reports, and anywhere you need to highlight important numbers or data points.

## Basic Usage

```php
<x-artisanpack-stat>
    <x-slot:title>Total Users</x-slot:title>
    <x-slot:value>2,543</x-slot:value>
    <x-slot:description>↗︎ 12% from last month</x-slot:description>
</x-artisanpack-stat>
```

## Examples

### Basic Stat

```php
<x-artisanpack-stat>
    <x-slot:title>Total Users</x-slot:title>
    <x-slot:value>2,543</x-slot:value>
    <x-slot:description>↗︎ 12% from last month</x-slot:description>
</x-artisanpack-stat>
```

### Stat with Icon

```php
<x-artisanpack-stat>
    <x-slot:figure>
        <x-artisanpack-icon name="heroicon-o-users" class="w-8 h-8 text-primary" />
    </x-slot:figure>
    <x-slot:title>Total Users</x-slot:title>
    <x-slot:value>2,543</x-slot:value>
    <x-slot:description>↗︎ 12% from last month</x-slot:description>
</x-artisanpack-stat>
```

### Stat with Actions

```php
<x-artisanpack-stat>
    <x-slot:figure>
        <x-artisanpack-icon name="heroicon-o-currency-dollar" class="w-8 h-8 text-success" />
    </x-slot:figure>
    <x-slot:title>Revenue</x-slot:title>
    <x-slot:value>$89,432</x-slot:value>
    <x-slot:description>↗︎ 8% from last month</x-slot:description>
    <x-slot:actions>
        <x-artisanpack-button size="sm">View Report</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-stat>
```

### Multiple Stats in a Grid

```php
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-artisanpack-stat>
        <x-slot:figure>
            <x-artisanpack-icon name="heroicon-o-users" class="w-8 h-8 text-primary" />
        </x-slot:figure>
        <x-slot:title>Total Users</x-slot:title>
        <x-slot:value>2,543</x-slot:value>
        <x-slot:description>↗︎ 12% from last month</x-slot:description>
    </x-artisanpack-stat>
    
    <x-artisanpack-stat>
        <x-slot:figure>
            <x-artisanpack-icon name="heroicon-o-currency-dollar" class="w-8 h-8 text-success" />
        </x-slot:figure>
        <x-slot:title>Revenue</x-slot:title>
        <x-slot:value>$89,432</x-slot:value>
        <x-slot:description>↗︎ 8% from last month</x-slot:description>
    </x-artisanpack-stat>
    
    <x-artisanpack-stat>
        <x-slot:figure>
            <x-artisanpack-icon name="heroicon-o-shopping-cart" class="w-8 h-8 text-info" />
        </x-slot:figure>
        <x-slot:title>Orders</x-slot:title>
        <x-slot:value>1,326</x-slot:value>
        <x-slot:description>↘︎ 3% from last month</x-slot:description>
    </x-artisanpack-stat>
</div>
```

### Stat with Custom Colors

```php
<x-artisanpack-stat class="bg-primary text-primary-content">
    <x-slot:title>Total Users</x-slot:title>
    <x-slot:value>2,543</x-slot:value>
    <x-slot:description>↗︎ 12% from last month</x-slot:description>
</x-artisanpack-stat>
```

### Stat with Trend Indicators

```php
<x-artisanpack-stat>
    <x-slot:title>Conversion Rate</x-slot:title>
    <x-slot:value>3.2%</x-slot:value>
    <x-slot:description class="text-success">↗︎ 0.5% from last month</x-slot:description>
</x-artisanpack-stat>

<x-artisanpack-stat>
    <x-slot:title>Bounce Rate</x-slot:title>
    <x-slot:value>42%</x-slot:value>
    <x-slot:description class="text-error">↘︎ 3% from last month</x-slot:description>
</x-artisanpack-stat>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the stat element |
| `class` | string | `null` | Additional CSS classes to apply to the stat container |

## Slots

| Slot | Description |
|------|-------------|
| `figure` | Optional icon or image to display alongside the stat |
| `title` | The title or label for the stat |
| `value` | The main value or metric to display |
| `description` | Additional context or trend information |
| `actions` | Optional action buttons or links |

## Behavior

The Stat component:

1. Displays a structured view of a single metric or data point
2. Can include visual indicators for trends (up/down arrows)
3. Supports optional icons or images to provide visual context
4. Can be grouped with other stats to create dashboards or summary views

## Styling

The Stat component uses DaisyUI's stat component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Adding custom classes to the component
2. Styling individual slots with their own classes
3. Using color utility classes for different states (success, error, etc.)

### Default Classes

- The stat container has a card-like appearance with padding and rounded corners
- The title is displayed with reduced opacity
- The value is displayed with larger, bold text
- The description is displayed with reduced opacity

## Accessibility

The Stat component follows accessibility best practices:

- Uses semantic HTML structure
- Maintains proper color contrast for readability
- Supports screen readers with appropriate text hierarchy

For better accessibility:

1. Ensure trend indicators (arrows) have appropriate text descriptions
2. Maintain sufficient color contrast, especially when using custom colors
3. Use clear, concise language for titles and descriptions

## Related Components

- [Card](card.md) - Container for content with similar styling
- [Progress](progress.md) - Visual indicator of progress
- [Chart](chart.md) - More complex data visualization