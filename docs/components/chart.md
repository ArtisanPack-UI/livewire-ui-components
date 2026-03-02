---
title: Chart Component
---

# Chart Component

The Chart component provides a powerful wrapper for ApexCharts, allowing you to easily create interactive, animated charts in your Laravel application with support for theming, glass effects, and real-time updates.

> **Note:** In version 2.0, the Chart component was migrated from Chart.js to ApexCharts. If you're upgrading from 1.x, see the [Chart Migration Guide](Migration-Chart).

## Installation

The Chart component requires ApexCharts. Install it via npm:

```bash
npm install apexcharts
```

Then import it in your JavaScript:

```javascript
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;
```

## Basic Usage

```blade
<x-artisanpack-chart
    type="line"
    :series="$series"
    :options="$options"
/>
```

## Livewire Component Example

```php
class ChartExample extends Component
{
    public array $series = [];
    public array $options = [];

    public function mount()
    {
        $this->series = [
            [
                'name' => 'Sales',
                'data' => [30, 40, 35, 50, 49, 60, 70],
            ],
        ];

        $this->options = [
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.chart-example');
    }
}
```

```blade
<div>
    <x-artisanpack-chart
        type="line"
        :series="$series"
        :options="$options"
        height="350"
    />
</div>
```

## Chart Types

The component supports all ApexCharts chart types:

### Line Chart

```blade
<x-artisanpack-chart
    type="line"
    :series="[
        ['name' => 'Sales', 'data' => [30, 40, 35, 50, 49, 60, 70]]
    ]"
    :options="[
        'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']]
    ]"
/>
```

### Area Chart

```blade
<x-artisanpack-chart
    type="area"
    :series="[
        ['name' => 'Revenue', 'data' => [31, 40, 28, 51, 42, 109, 100]]
    ]"
    :options="[
        'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']],
        'fill' => ['type' => 'gradient']
    ]"
/>
```

### Bar Chart

```blade
<x-artisanpack-chart
    type="bar"
    :series="[
        ['name' => 'Sales', 'data' => [44, 55, 57, 56, 61, 58, 63]]
    ]"
    :options="[
        'xaxis' => ['categories' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']],
        'plotOptions' => ['bar' => ['horizontal' => false, 'columnWidth' => '55%']]
    ]"
/>
```

### Pie/Donut Chart

```blade
{{-- Pie Chart --}}
<x-artisanpack-chart
    type="pie"
    :series="[44, 55, 13, 43, 22]"
    :options="[
        'labels' => ['Team A', 'Team B', 'Team C', 'Team D', 'Team E']
    ]"
/>

{{-- Donut Chart --}}
<x-artisanpack-chart
    type="donut"
    :series="[44, 55, 41, 17, 15]"
    :options="[
        'labels' => ['Apple', 'Mango', 'Orange', 'Watermelon', 'Grapes']
    ]"
/>
```

### Radial Bar Chart

```blade
<x-artisanpack-chart
    type="radialBar"
    :series="[70, 55, 40]"
    :options="[
        'labels' => ['Progress', 'Sales', 'Growth'],
        'plotOptions' => [
            'radialBar' => [
                'dataLabels' => [
                    'total' => ['show' => true, 'label' => 'Total']
                ]
            ]
        ]
    ]"
/>
```

### Heatmap

```blade
<x-artisanpack-chart
    type="heatmap"
    :series="$heatmapSeries"
    :options="[
        'plotOptions' => [
            'heatmap' => ['colorScale' => ['ranges' => [
                ['from' => 0, 'to' => 50, 'color' => '#00A100'],
                ['from' => 51, 'to' => 100, 'color' => '#128FD9']
            ]]]
        ]
    ]"
/>
```

## Theming

The component includes built-in themes that automatically adapt to light/dark mode:

### Available Themes

```blade
{{-- Light theme --}}
<x-artisanpack-chart theme="artisanpack-light" ... />

{{-- Dark theme --}}
<x-artisanpack-chart theme="artisanpack-dark" ... />

{{-- Glass theme (for glass effect containers) --}}
<x-artisanpack-chart theme="artisanpack-glass" ... />
```

### Glass Effect

Combine with glass effects for modern UI:

```blade
<x-artisanpack-chart
    type="area"
    :series="$series"
    :options="$options"
    glass="frosted"
    glass-tint="blue-500"
    glass-tint-opacity="40"
    theme="artisanpack-glass"
/>
```

## Animation Configuration

Control chart animations for smooth transitions:

```blade
<x-artisanpack-chart
    type="line"
    :series="$series"
    :options="$options"
    :animated="true"
    :animation-speed="800"
    animation-easing="easeinout"
    :dynamic-animation="true"
    :dynamic-animation-speed="350"
/>
```

### Animation Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| animated | bool | true | Enable/disable initial animations |
| animationSpeed | int | 800 | Initial animation duration in ms |
| animationEasing | string | 'easeinout' | Easing function (linear, easein, easeout, easeinout) |
| dynamicAnimation | bool | true | Enable animations on data updates |
| dynamicAnimationSpeed | int | 350 | Update animation duration in ms |

## Real-Time Updates

The Chart component supports multiple methods for real-time data updates.

### Using wire:poll

```blade
{{-- Updates every 5 seconds --}}
<x-artisanpack-chart
    wire:poll.5s
    type="line"
    :series="$series"
    :options="$options"
/>
```

### Using Livewire Events

Dispatch events from your Livewire component to update charts:

```php
// Full update (series and options)
$this->dispatch('chart-update', [
    'chartId' => 'my-chart',
    'series' => $newSeries,
    'options' => $newOptions,
    'animate' => true,
]);

// Series-only update (more efficient)
$this->dispatch('chart-update-series', [
    'chartId' => 'my-chart',
    'series' => $newSeries,
    'animate' => true,
]);

// Append data (for streaming)
$this->dispatch('chart-append-data', [
    'chartId' => 'my-chart',
    'data' => [['data' => [newDataPoint]]],
]);
```

```blade
<x-artisanpack-chart
    id="my-chart"
    type="line"
    :series="$series"
    :options="$options"
/>
```

### Live Dashboard Example

```php
class LiveDashboard extends Component
{
    public array $series = [];
    public array $options = [];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        // Query data with both dates and counts
        $data = User::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(7)
            ->get();

        // Extract dates for x-axis categories and counts for series data
        $dates = $data->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray();
        $counts = $data->pluck('count')->toArray();

        // Set series data
        $this->series = [
            [
                'name' => 'Active Users',
                'data' => $counts,
            ],
        ];

        // Set options with x-axis categories
        $this->options = [
            'xaxis' => [
                'categories' => $dates,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.live-dashboard');
    }
}
```

```blade
<div>
    <x-artisanpack-chart
        wire:poll.10s="refreshData"
        type="area"
        :series="$series"
        :options="$options"
        :dynamic-animation-speed="500"
    />
</div>
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Unique identifier for the chart |
| type | string | 'line' | Chart type (line, area, bar, pie, donut, radialBar, heatmap, scatter, bubble, candlestick, boxPlot, radar, polarArea, rangeBar, rangeArea, treemap) |
| series | array | [] | Chart series data |
| options | array | null | ApexCharts options object |
| height | string\|int | 350 | Chart height |
| width | string\|int | null | Chart width (auto if null) |
| theme | string | null | Theme name (artisanpack-light, artisanpack-dark, artisanpack-glass) |
| animated | bool | true | Enable animations |
| animationSpeed | int | 800 | Initial animation speed (ms) |
| animationEasing | string | 'easeinout' | Animation easing function |
| dynamicAnimation | bool | true | Enable update animations |
| dynamicAnimationSpeed | int | 350 | Update animation speed (ms) |
| glass | string | null | Glass effect variant (frosted, transparent, liquid) |
| glassTint | string | null | Glass tint color (Tailwind color or hex) |
| glassTintOpacity | int | null | Glass tint opacity (0-100) |

## Customization

### Custom Colors

```blade
<x-artisanpack-chart
    type="bar"
    :series="$series"
    :options="[
        'colors' => ['#3b82f6', '#ef4444', '#10b981', '#f59e0b'],
        'xaxis' => ['categories' => $categories]
    ]"
/>
```

### Toolbar and Zoom

```blade
<x-artisanpack-chart
    type="line"
    :series="$series"
    :options="[
        'chart' => [
            'toolbar' => ['show' => true],
            'zoom' => ['enabled' => true]
        ]
    ]"
/>
```

### Data Labels

```blade
<x-artisanpack-chart
    type="bar"
    :series="$series"
    :options="[
        'dataLabels' => ['enabled' => true],
        'plotOptions' => ['bar' => ['dataLabels' => ['position' => 'top']]]
    ]"
/>
```

## Notes

- The component requires ApexCharts to be loaded globally (`window.ApexCharts`)
- Charts automatically respond to theme changes (light/dark mode)
- Use `wire:poll` for simple polling updates
- Use Livewire events for more control over updates
- The `chart-update-series` event is more efficient than full updates for real-time data
- All ApexCharts configuration options are supported via the `options` prop

## See Also

- [Sparkline Component](Sparkline) - Lightweight inline charts
- [Stat Component](Stat) - Display statistics with optional sparklines
- [ApexCharts Documentation](https://apexcharts.com/docs/)
