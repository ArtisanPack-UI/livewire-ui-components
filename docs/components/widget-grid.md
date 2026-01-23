---
title: WidgetGrid Component
---

# WidgetGrid Component

The WidgetGrid component provides a responsive grid helper optimized for dashboard layouts. It works seamlessly with KPI cards, stats, and other widget components to create clean, responsive dashboard interfaces.

**Note:** This component was added in version 2.0.0.

## Basic Usage

```php
<x-artisanpack-widget-grid>
    <x-artisanpack-kpi-card title="Revenue" value="$45,231" />
    <x-artisanpack-kpi-card title="Users" value="2,345" />
    <x-artisanpack-kpi-card title="Orders" value="1,234" />
    <x-artisanpack-kpi-card title="Conversion" value="3.24%" />
</x-artisanpack-widget-grid>
```

## Examples

### Custom Column Count

Specify the number of columns at the largest breakpoint:

```php
{{-- 2 columns on large screens --}}
<x-artisanpack-widget-grid :cols="2">
    <x-artisanpack-kpi-card title="Total Sales" value="$125,000" />
    <x-artisanpack-kpi-card title="Total Expenses" value="$45,000" />
</x-artisanpack-widget-grid>

{{-- 3 columns on large screens --}}
<x-artisanpack-widget-grid :cols="3">
    <x-artisanpack-stat title="Users" value="8,453" />
    <x-artisanpack-stat title="Sessions" value="24,123" />
    <x-artisanpack-stat title="Page Views" value="128,456" />
</x-artisanpack-widget-grid>

{{-- 6 columns on large screens --}}
<x-artisanpack-widget-grid :cols="6">
    <!-- 6 smaller widgets -->
</x-artisanpack-widget-grid>
```

### Custom Gap Spacing

Control the spacing between grid items:

```php
{{-- No gap --}}
<x-artisanpack-widget-grid :gap="0">
    <!-- widgets -->
</x-artisanpack-widget-grid>

{{-- Small gap --}}
<x-artisanpack-widget-grid :gap="2">
    <!-- widgets -->
</x-artisanpack-widget-grid>

{{-- Large gap --}}
<x-artisanpack-widget-grid :gap="8">
    <!-- widgets -->
</x-artisanpack-widget-grid>
```

### Dashboard Layout Example

```php
<?php
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'revenueData' => [1200, 1350, 1100, 1500, 1400, 1600, 1800],
            'usersData' => [100, 120, 115, 140, 155, 180, 210],
            'ordersData' => [45, 52, 48, 61, 58, 72, 85],
            'conversionData' => [2.1, 2.3, 2.2, 2.5, 2.8, 3.0, 3.2],
        ];
    }
}; ?>

<div class="space-y-6">
    {{-- KPI Cards Row --}}
    <x-artisanpack-widget-grid :cols="4" :gap="4">
        <x-artisanpack-kpi-card
            title="Total Revenue"
            value="$45,231"
            icon="o-currency-dollar"
            :change="12.5"
            change-label="vs last month"
            :sparkline-data="$revenueData" />

        <x-artisanpack-kpi-card
            title="Active Users"
            value="2,345"
            icon="o-users"
            :change="8.2"
            change-label="vs last month"
            :sparkline-data="$usersData" />

        <x-artisanpack-kpi-card
            title="Total Orders"
            value="1,234"
            icon="o-shopping-cart"
            :change="-2.4"
            change-label="vs last month"
            :sparkline-data="$ordersData" />

        <x-artisanpack-kpi-card
            title="Conversion Rate"
            value="3.24%"
            icon="o-arrow-path"
            :change="0.8"
            change-label="improvement"
            :sparkline-data="$conversionData" />
    </x-artisanpack-widget-grid>

    {{-- Secondary Stats Row --}}
    <x-artisanpack-widget-grid :cols="3" :gap="4">
        <x-artisanpack-card>
            <h3 class="font-semibold mb-2">Recent Activity</h3>
            <!-- content -->
        </x-artisanpack-card>

        <x-artisanpack-card>
            <h3 class="font-semibold mb-2">Top Products</h3>
            <!-- content -->
        </x-artisanpack-card>

        <x-artisanpack-card>
            <h3 class="font-semibold mb-2">Notifications</h3>
            <!-- content -->
        </x-artisanpack-card>
    </x-artisanpack-widget-grid>
</div>
```

### Mixed Widget Types

The grid works with any components, not just KpiCards:

```php
<x-artisanpack-widget-grid :cols="4" :gap="4">
    <x-artisanpack-kpi-card title="Revenue" value="$45,231" />

    <x-artisanpack-stat title="Users" value="8,453" />

    <x-artisanpack-card class="bg-primary text-primary-content">
        <div class="text-center py-4">
            <p class="text-2xl font-bold">Premium</p>
            <p class="text-sm opacity-80">Plan Active</p>
        </div>
    </x-artisanpack-card>

    <div class="rounded-lg bg-gradient-to-br from-secondary to-accent p-5 text-white">
        <p class="font-bold">Custom Widget</p>
        <p class="text-sm opacity-80">Any content works</p>
    </div>
</x-artisanpack-widget-grid>
```

### With Additional Classes

Add custom classes to the grid container:

```php
<x-artisanpack-widget-grid :cols="4" :gap="4" class="p-4 bg-base-200 rounded-xl">
    <!-- widgets -->
</x-artisanpack-widget-grid>
```

## Responsive Behavior

The grid automatically adjusts columns based on screen size:

| Columns (`cols`) | Mobile (< 640px) | Small (640px+) | Medium (768px+) | Large (1024px+) |
|------------------|------------------|----------------|-----------------|-----------------|
| 1 | 1 column | 1 column | 1 column | 1 column |
| 2 | 1 column | 2 columns | 2 columns | 2 columns |
| 3 | 1 column | 2 columns | 2 columns | 3 columns |
| 4 | 1 column | 2 columns | 2 columns | 4 columns |
| 5 | 1 column | 2 columns | 3 columns | 5 columns |
| 6 | 1 column | 2 columns | 3 columns | 6 columns |

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the component |
| `cols` | int | `4` | Number of columns at the largest breakpoint (1-6) |
| `gap` | int | `6` | Gap spacing between items (0, 1, 2, 3, 4, 5, 6, 8, 10, 12) |

## Available Gap Values

| Gap Value | Tailwind Class | Pixel Size |
|-----------|----------------|------------|
| 0 | `gap-0` | 0px |
| 1 | `gap-1` | 4px |
| 2 | `gap-2` | 8px |
| 3 | `gap-3` | 12px |
| 4 | `gap-4` | 16px |
| 5 | `gap-5` | 20px |
| 6 | `gap-6` | 24px |
| 8 | `gap-8` | 32px |
| 10 | `gap-10` | 40px |
| 12 | `gap-12` | 48px |

## Notes

- The grid uses CSS Grid (`display: grid`) for layout
- All direct children of the grid are automatically placed in the grid cells
- The component is fully responsive and mobile-first
- Grid items automatically stretch to fill available height when in the same row

## Related Components

- [KpiCard](kpi-card) - KPI card for dashboards
- [Stat](stat) - Simple statistic display
- [Card](card) - Generic card container
- [Grid](grid) - More flexible grid component
