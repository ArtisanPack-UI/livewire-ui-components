---
title: KpiCard Component
---

# KpiCard Component

The KpiCard component provides a dedicated KPI (Key Performance Indicator) card optimized for dashboards. It combines stat display, sparkline visualization, and trend indicators in one cohesive component.

**Note:** This component was added in version 2.0.0.

## Basic Usage

```php
<x-artisanpack-kpi-card
    title="Total Revenue"
    value="$45,231"
    icon="o-currency-dollar" />
```

## Examples

### KpiCard with Trend Indicator

Display a change percentage with automatic color coding (green for positive, red for negative):

```php
<x-artisanpack-kpi-card
    title="Monthly Sales"
    value="$12,450"
    icon="o-shopping-cart"
    :change="12.5"
    change-label="vs last month" />
```

### KpiCard with Negative Trend

```php
<x-artisanpack-kpi-card
    title="Bounce Rate"
    value="32.4%"
    icon="o-arrow-trending-down"
    :change="-8.3"
    change-label="from last week" />
```

### KpiCard with Sparkline

Add a sparkline visualization to show historical data:

```php
<?php
$revenueHistory = [1200, 1350, 1100, 1500, 1400, 1600, 1800];
?>

<x-artisanpack-kpi-card
    title="Revenue"
    value="$1,800"
    icon="o-chart-bar"
    :change="12.5"
    change-label="vs last week"
    :sparkline-data="$revenueHistory"
    sparkline-type="area" />
```

### Sparkline Types

The sparkline supports different visualization types:

```php
{{-- Area chart (default) --}}
<x-artisanpack-kpi-card
    title="Page Views"
    value="24,312"
    :sparkline-data="$viewsData"
    sparkline-type="area" />

{{-- Line chart --}}
<x-artisanpack-kpi-card
    title="Active Users"
    value="1,234"
    :sparkline-data="$userData"
    sparkline-type="line" />

{{-- Bar chart --}}
<x-artisanpack-kpi-card
    title="Orders"
    value="456"
    :sparkline-data="$ordersData"
    sparkline-type="bar" />
```

### Custom Sparkline Color

```php
<x-artisanpack-kpi-card
    title="Custom Metric"
    value="789"
    :sparkline-data="$data"
    sparkline-color="#8b5cf6" />
```

### KpiCard with Glass Effect

Apply a frosted glass effect for modern UI aesthetics:

```php
<x-artisanpack-kpi-card
    title="Total Users"
    value="8,453"
    icon="o-users"
    glass="frost" />
```

Available glass effects:
- `frost` - Standard frosted glass
- `blur` - Stronger blur effect
- `subtle` - Light, subtle effect

### Glass with Custom Tint

```php
<x-artisanpack-kpi-card
    title="Premium Users"
    value="1,234"
    icon="o-star"
    glass="frost"
    glass-tint="#8b5cf6"
    :glass-tint-opacity="20" />
```

### KpiCard with Footer

Add custom footer content using the slot:

```php
<x-artisanpack-kpi-card
    title="Conversion Rate"
    value="3.24%"
    icon="o-arrow-path"
    :change="0.8"
    change-label="improvement">
    <x-slot:footer>
        <a href="/analytics" class="text-sm text-primary hover:underline">
            View detailed analytics
        </a>
    </x-slot:footer>
</x-artisanpack-kpi-card>
```

### KpiCard with Value in Slot

You can also pass the value through the default slot for more complex formatting:

```php
<x-artisanpack-kpi-card
    title="Revenue Goal"
    icon="o-trophy">
    <span class="text-success">$45,231</span>
    <span class="text-sm text-base-content/50">/ $50,000</span>
</x-artisanpack-kpi-card>
```

### Complete Dashboard Example

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

<x-artisanpack-widget-grid :cols="4" :gap="4">
    <x-artisanpack-kpi-card
        title="Total Revenue"
        value="$45,231"
        icon="o-currency-dollar"
        :change="12.5"
        change-label="vs last month"
        :sparkline-data="$revenueData"
        sparkline-type="area" />

    <x-artisanpack-kpi-card
        title="Active Users"
        value="2,345"
        icon="o-users"
        :change="8.2"
        change-label="vs last month"
        :sparkline-data="$usersData"
        sparkline-type="line" />

    <x-artisanpack-kpi-card
        title="Total Orders"
        value="1,234"
        icon="o-shopping-cart"
        :change="-2.4"
        change-label="vs last month"
        :sparkline-data="$ordersData"
        sparkline-type="bar" />

    <x-artisanpack-kpi-card
        title="Conversion Rate"
        value="3.24%"
        icon="o-arrow-path"
        :change="0.8"
        change-label="improvement"
        :sparkline-data="$conversionData"
        sparkline-type="area" />
</x-artisanpack-widget-grid>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the component |
| `title` | string\|null | `null` | The KPI title/label |
| `value` | string\|null | `null` | The main KPI value to display |
| `icon` | string\|null | `null` | Icon name to display alongside the title |
| `change` | float\|null | `null` | Percentage change (positive or negative) |
| `change-label` | string\|null | `null` | Label text shown after the change value |
| `sparkline-data` | array\|null | `null` | Array of numeric values for the sparkline |
| `sparkline-type` | string | `'area'` | Sparkline chart type: `area`, `line`, or `bar` |
| `sparkline-color` | string\|null | `null` | Custom color for the sparkline (hex code) |
| `glass` | string\|null | `null` | Glass effect type: `frost`, `blur`, or `subtle` |
| `glass-tint` | string\|null | `null` | Custom tint color for glass effect (hex code) |
| `glass-tint-opacity` | int\|null | `null` | Opacity percentage for glass tint (0-100) |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Alternative way to provide the value with custom formatting |
| `footer` | Optional footer content displayed below the sparkline |

## Trend Indicator Behavior

The change value automatically determines:

- **Color**: Positive values display in green (`text-success`), negative in red (`text-error`)
- **Icon**: Positive values show an up arrow, negative values show a down arrow
- **Format**: Values are formatted with a sign prefix and percent symbol (e.g., "+12.5%" or "-8.3%")

## Notes

- The KpiCard is designed to work seamlessly with the [WidgetGrid](widget-grid) component for dashboard layouts
- Sparkline visualization requires numeric data; non-numeric values will be ignored
- Glass effects work best on backgrounds with content visible beneath them
- The component automatically handles WCAG 2.0 AA compliant text colors when using glass effects with custom tints

## Related Components

- [WidgetGrid](widget-grid) - Grid layout for dashboard widgets
- [Sparkline](sparkline) - Standalone sparkline chart
- [Stat](stat) - Simple statistic display
- [Card](card) - Generic card container
