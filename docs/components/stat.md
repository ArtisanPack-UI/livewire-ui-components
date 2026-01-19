---
title: Stat Component
---

# Stat Component

The Stat component displays key metrics or statistics in a visually appealing and structured format. It's ideal for dashboards, reports, and anywhere you need to highlight important numbers or data points.

## Basic Usage

```php
<x-artisanpack-stat 
    title="Total Users"
    value="2,543"
    description="↗︎ 12% from last month"
/>
```

## New Features

The Stat component now supports:

- **Size variants**: `xs`, `sm`, `md`, `lg`, `xl`
- **Icon positioning**: `left`, `right`, `top`, `bottom`
- **Title positioning**: `top`, `bottom`  
- **Content alignment**: `left`, `center`, `right`

## Examples

### Basic Stat

```php
<x-artisanpack-stat 
    title="Total Users"
    value="2,543"
    description="↗︎ 12% from last month"
/>
```

### Stat with Icon

```php
<x-artisanpack-stat 
    title="Total Users"
    value="2,543"
    description="↗︎ 12% from last month"
    icon="heroicon-o-users"
    color="text-primary"
/>
```

### Different Sizes

```php
{{-- Extra Small --}}
<x-artisanpack-stat 
    size="xs"
    title="Small Stat"
    value="123"
    icon="heroicon-o-users"
/>

{{-- Large --}}
<x-artisanpack-stat 
    size="lg"
    title="Large Stat"
    value="456"
    icon="heroicon-o-currency-dollar"
    color="text-success"
/>

{{-- Extra Large --}}
<x-artisanpack-stat 
    size="xl"
    title="Extra Large"
    value="789"
    description="With description"
    icon="heroicon-o-star"
/>
```

### Icon Positioning

```php
{{-- Icon on Right --}}
<x-artisanpack-stat 
    icon-position="right"
    title="Revenue"
    value="$89,432"
    icon="heroicon-o-currency-dollar"
/>

{{-- Icon on Top --}}
<x-artisanpack-stat 
    icon-position="top"
    title="Users"
    value="2,543"
    icon="heroicon-o-users"
/>

{{-- Icon on Bottom --}}
<x-artisanpack-stat 
    icon-position="bottom"
    title="Orders"
    value="1,326"
    icon="heroicon-o-shopping-cart"
/>
```

### Content Alignment

```php
{{-- Center Aligned --}}
<x-artisanpack-stat 
    content-align="center"
    title="Centered"
    value="789"
    icon="heroicon-o-star"
/>

{{-- Right Aligned --}}
<x-artisanpack-stat 
    content-align="right"
    title="Right Aligned"
    value="456"
    description="Right side content"
/>
```

### Title Positioning

```php
{{-- Title Below Value --}}
<x-artisanpack-stat 
    title-position="bottom"
    title="Sales"
    value="1,234"
    icon="heroicon-o-shopping-cart"
/>
```

### Multiple Stats in a Grid

```php
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-artisanpack-stat
        title="Total Users"
        value="2,543"
        description="↗︎ 12% from last month"
        icon="heroicon-o-users"
        color="text-primary"
        size="md"
    />
    
    <x-artisanpack-stat
        title="Revenue"
        value="$89,432"
        description="↗︎ 8% from last month"
        icon="heroicon-o-currency-dollar"
        color="text-success"
        size="md"
    />
    
    <x-artisanpack-stat
        title="Orders"
        value="1,326"
        description="↘︎ 3% from last month"
        icon="heroicon-o-shopping-cart"
        color="text-info"
        size="md"
    />
</div>
```

### Advanced Combinations

```php
{{-- Large stat with icon on top and centered content --}}
<x-artisanpack-stat
    size="lg"
    icon-position="top"
    content-align="center"
    title="Featured Metric"
    value="12,543"
    description="With special highlighting"
    icon="heroicon-o-star"
    color="text-warning"
/>

{{-- Small stat with right-aligned icon and bottom title --}}
<x-artisanpack-stat
    size="sm"
    icon-position="right"
    title-position="bottom"
    title="Compact View"
    value="789"
    icon="heroicon-o-chart-bar"
/>
```

### Stat with Custom Colors

```php
<x-artisanpack-stat 
    class="bg-primary text-primary-content"
    title="Total Users"
    value="2,543"
    description="↗︎ 12% from last month"
/>
```

### Stat with Trend Indicators

```php
<x-artisanpack-stat
    title="Conversion Rate"
    value="3.2%"
    description="↗︎ 0.5% from last month"
    icon="heroicon-o-trending-up"
    color="text-success"
/>

<x-artisanpack-stat
    title="Bounce Rate"
    value="42%"
    description="↘︎ 3% from last month"
    icon="heroicon-o-trending-down"
    color="text-error"
/>
```

### Tooltips

```php
<x-artisanpack-stat
    title="Active Users"
    value="1,234"
    tooltip="Users active in the last 24 hours"
    icon="heroicon-o-users"
/>

<x-artisanpack-stat
    title="Revenue"
    value="$12,345"
    tooltip-right="Monthly recurring revenue"
    icon="heroicon-o-currency-dollar"
/>
```

## Animated Value Transitions

The Stat component supports smooth number counting animations when values change. This is especially useful for dashboard widgets that update in real-time via Livewire.

### Basic Animation

Animation is enabled by default for numeric values:

```php
{{-- Values automatically animate when updated via Livewire --}}
<x-artisanpack-stat
    title="Active Users"
    :value="$activeUsers"
    icon="heroicon-o-users"
/>
```

### Disabling Animation

You can disable animation when needed:

```php
<x-artisanpack-stat
    title="Status Code"
    value="200"
    :animate="false"
/>
```

### Custom Animation Duration

Adjust the animation duration (in milliseconds):

```php
{{-- Faster animation (500ms) --}}
<x-artisanpack-stat
    title="Quick Counter"
    :value="$count"
    :animate-duration="500"
/>

{{-- Slower animation (2000ms) --}}
<x-artisanpack-stat
    title="Gradual Update"
    :value="$total"
    :animate-duration="2000"
/>
```

### Supported Value Formats

The animation system automatically handles various numeric formats:

```php
{{-- Currency with commas and decimals --}}
<x-artisanpack-stat title="Revenue" value="$1,234.56" />

{{-- Percentages --}}
<x-artisanpack-stat title="Conversion" value="45.5%" />

{{-- Abbreviated numbers --}}
<x-artisanpack-stat title="Users" value="2.5K" />
<x-artisanpack-stat title="Views" value="1.2M" />
<x-artisanpack-stat title="Value" value="3B" />

{{-- Plain numbers with thousands separators --}}
<x-artisanpack-stat title="Total" value="1,234,567" />

{{-- Euro currency --}}
<x-artisanpack-stat title="Price" value="€2.500,00" />
```

### Non-Numeric Values

Values that cannot be parsed as numbers will display without animation:

```php
{{-- These will not animate (displayed as-is) --}}
<x-artisanpack-stat title="Status" value="Active" />
<x-artisanpack-stat title="Rating" value="★★★★☆" />
```

### Reduced Motion Support

The animation automatically respects the user's `prefers-reduced-motion` preference. When reduced motion is enabled, values will update instantly without counting animation.

### Animation with Other Features

Animation works seamlessly with other Stat features:

```php
{{-- Animation with glass effect, sparkline, and trend indicator --}}
<x-artisanpack-stat
    title="Monthly Revenue"
    :value="$revenue"
    glass="frosted"
    glass-tint="emerald-500"
    :change="$revenueChange"
    change-label="vs last month"
    :sparkline-data="$sparklineData"
    :animate-duration="1200"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the stat element |
| `value` | string | `null` | The main value or metric to display |
| `icon` | string | `null` | Icon name to display (e.g., "heroicon-o-users") |
| `color` | string | `''` | CSS color class for the icon |
| `title` | string | `null` | The title or label for the stat |
| `description` | string | `null` | Additional context or trend information |
| `tooltip` | string | `null` | Tooltip text to display on hover |
| `tooltipLeft` | string | `null` | Tooltip text positioned to the left |
| `tooltipRight` | string | `null` | Tooltip text positioned to the right |
| `tooltipBottom` | string | `null` | Tooltip text positioned at the bottom |
| **Size & Position Props** | | | |
| `size` | string | `'md'` | Component size: `xs`, `sm`, `md`, `lg`, `xl` |
| `iconPosition` | string | `'left'` | Icon position: `left`, `right`, `top`, `bottom` |
| `titlePosition` | string | `'top'` | Title position relative to value: `top`, `bottom` |
| `contentAlign` | string | `'left'` | Content alignment: `left`, `center`, `right` |
| **Animation Props** | | | |
| `animate` | bool | `true` | Enable/disable value animation |
| `animateDuration` | int | `1000` | Animation duration in milliseconds |
| `class` | string | `null` | Additional CSS classes to apply to the stat container |

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

## Migration Guide

If you're upgrading from a previous version that used slot-based API, here's how to migrate:

### Before (Slot-based)
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

### After (Prop-based)
```php
<x-artisanpack-stat
    title="Total Users"
    value="2,543"
    description="↗︎ 12% from last month"
    icon="heroicon-o-users"
    color="text-primary"
/>
```

### Benefits of the New API
- **Cleaner syntax**: Props are more concise than slots
- **Better IDE support**: Props have better autocomplete and type hints
- **New features**: Size variants, positioning options, and alignment controls
- **Backward compatibility**: Existing implementations continue to work
- **Better performance**: Less template parsing overhead

## Size Reference

| Size | Use Case | Icon Size | Typical Context |
|------|----------|-----------|-----------------|
| `xs` | Compact dashboards, mobile views | 24x24px | Dense information layouts |
| `sm` | Sidebar stats, small widgets | 28x28px | Secondary metrics |
| `md` | Standard dashboard cards | 36x36px | Primary metrics (default) |
| `lg` | Featured statistics, main KPIs | 44x44px | Hero sections, key metrics |
| `xl` | Prominent displays, presentations | 56x56px | Executive dashboards |

## Related Components

- [Card](card) - Container for content with similar styling
- [Progress](progress) - Visual indicator of progress
- [Chart](chart) - More complex data visualization