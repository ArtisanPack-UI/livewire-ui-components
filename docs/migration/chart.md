---
title: Chart Component Migration (Chart.js to ApexCharts)
---

# Chart Component Migration Guide

This guide helps you migrate from the Chart.js-based Chart component (v1.x) to the new ApexCharts-based implementation (v2.0).

## Overview

In version 2.0, the Chart component was completely rebuilt using ApexCharts instead of Chart.js. This change provides:

- Better performance and smaller bundle size
- Built-in theming with light/dark mode support
- Glass effect integration
- Smooth real-time update animations
- More chart types (16+ vs 8)
- Better Livewire integration

## Breaking Changes

1. **Configuration format changed** - Chart.js config format is not compatible with ApexCharts
2. **wire:model removed** - Use `:series` and `:options` props instead
3. **JavaScript dependency changed** - Replace Chart.js with ApexCharts
4. **Props renamed/changed** - See mapping table below

## Installation Changes

### Before (v1.x)

```bash
npm install chart.js
```

```javascript
import Chart from 'chart.js/auto';
window.Chart = Chart;
```

### After (v2.0)

```bash
npm install apexcharts
```

```javascript
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;
```

## Configuration Migration

### Basic Line Chart

**Before (Chart.js format):**

```blade
@php
$chartData = [
    'type' => 'line',
    'data' => [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        'datasets' => [
            [
                'label' => 'Sales',
                'data' => [65, 59, 80, 81, 56],
                'borderColor' => 'rgb(75, 192, 192)',
                'tension' => 0.1
            ]
        ]
    ],
    'options' => [
        'scales' => [
            'y' => ['beginAtZero' => true]
        ]
    ]
];
@endphp

<x-artisanpack-chart wire:model="chartData" />
```

**After (ApexCharts format):**

```blade
@php
$series = [
    [
        'name' => 'Sales',
        'data' => [65, 59, 80, 81, 56]
    ]
];

$options = [
    'xaxis' => [
        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May']
    ],
    'stroke' => [
        'curve' => 'smooth'
    ],
    'yaxis' => [
        'min' => 0
    ]
];
@endphp

<x-artisanpack-chart
    type="line"
    :series="$series"
    :options="$options"
/>
```

### Bar Chart

**Before:**

```php
$chartData = [
    'type' => 'bar',
    'data' => [
        'labels' => ['Red', 'Blue', 'Yellow'],
        'datasets' => [
            [
                'label' => 'Votes',
                'data' => [12, 19, 3],
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                'borderColor' => [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                'borderWidth' => 1
            ]
        ]
    ]
];
```

**After:**

```php
$series = [
    [
        'name' => 'Votes',
        'data' => [12, 19, 3]
    ]
];

$options = [
    'xaxis' => [
        'categories' => ['Red', 'Blue', 'Yellow']
    ],
    'colors' => ['#ef4444', '#3b82f6', '#eab308'],
    'plotOptions' => [
        'bar' => [
            'distributed' => true // Different color per bar
        ]
    ]
];
```

### Pie/Donut Chart

**Before:**

```php
$chartData = [
    'type' => 'pie', // or 'doughnut'
    'data' => [
        'labels' => ['Red', 'Blue', 'Yellow'],
        'datasets' => [
            [
                'data' => [300, 50, 100],
                'backgroundColor' => [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            ]
        ]
    ]
];
```

**After:**

```php
// Pie chart
$series = [300, 50, 100]; // Just the values, not nested in datasets

$options = [
    'labels' => ['Red', 'Blue', 'Yellow'],
    'colors' => ['#ef4444', '#3b82f6', '#eab308']
];
```

```blade
<x-artisanpack-chart
    type="pie"
    :series="$series"
    :options="$options"
/>

{{-- For donut, just change type --}}
<x-artisanpack-chart
    type="donut"
    :series="$series"
    :options="$options"
/>
```

## Props Migration

| v1.x (Chart.js) | v2.0 (ApexCharts) | Notes |
|-----------------|-------------------|-------|
| `wire:model="chartData"` | `:series="$series"` `:options="$options"` | Split into separate props |
| `id` | `id` | Unchanged |
| - | `type` | New: specify chart type |
| - | `height` | New: chart height |
| - | `width` | New: chart width |
| - | `theme` | New: built-in theming |
| - | `animated` | New: animation control |
| - | `glass` | New: glass effect support |

## Data Format Changes

### Series Data

**Chart.js (datasets array):**
```php
'datasets' => [
    [
        'label' => 'Series 1',
        'data' => [10, 20, 30],
        'borderColor' => '#3b82f6'
    ],
    [
        'label' => 'Series 2',
        'data' => [15, 25, 35],
        'borderColor' => '#ef4444'
    ]
]
```

**ApexCharts (series array):**
```php
$series = [
    [
        'name' => 'Series 1',
        'data' => [10, 20, 30]
    ],
    [
        'name' => 'Series 2',
        'data' => [15, 25, 35]
    ]
];

$options = [
    'colors' => ['#3b82f6', '#ef4444']
];
```

### Labels/Categories

**Chart.js:**
```php
'data' => [
    'labels' => ['Jan', 'Feb', 'Mar']
]
```

**ApexCharts:**
```php
$options = [
    'xaxis' => [
        'categories' => ['Jan', 'Feb', 'Mar']
    ]
];
```

## Dynamic Updates

### Before (v1.x)

```php
class ChartComponent extends Component
{
    public $chartData;

    public function refreshData()
    {
        $this->chartData = [
            'type' => 'bar',
            'data' => [
                'labels' => ['A', 'B', 'C'],
                'datasets' => [[
                    'data' => [rand(1, 100), rand(1, 100), rand(1, 100)]
                ]]
            ]
        ];
    }
}
```

### After (v2.0)

### Option 1: Update props directly

```php
class ChartComponent extends Component
{
    public array $series = [];
    public array $options = [];

    public function refreshData()
    {
        $this->series = [
            ['name' => 'Data', 'data' => [rand(1, 100), rand(1, 100), rand(1, 100)]]
        ];
    }
}
```

### Option 2: Use Livewire events (more efficient)

```php
public function refreshData()
{
    $newSeries = [
        ['name' => 'Data', 'data' => [rand(1, 100), rand(1, 100), rand(1, 100)]]
    ];

    $this->dispatch('chart-update-series', [
        'chartId' => 'my-chart',
        'series' => $newSeries
    ]);
}
```

## Common Migration Scenarios

### Scenario 1: Simple Line Chart

**Before:**
```blade
<x-artisanpack-chart wire:model="lineChart" class="h-64" />
```

**After:**
```blade
<x-artisanpack-chart
    type="line"
    :series="$series"
    :options="$options"
    height="256"
/>
```

### Scenario 2: Chart with Custom Styling

**Before:**
```php
$chartData = [
    'type' => 'line',
    'data' => [...],
    'options' => [
        'plugins' => [
            'legend' => ['display' => false]
        ],
        'elements' => [
            'line' => ['tension' => 0.4]
        ]
    ]
];
```

**After:**
```php
$options = [
    'legend' => ['show' => false],
    'stroke' => ['curve' => 'smooth']
];
```

### Scenario 3: Multiple Datasets

**Before:**
```php
'datasets' => [
    ['label' => 'Sales', 'data' => [65, 59, 80]],
    ['label' => 'Revenue', 'data' => [28, 48, 40]]
]
```

**After:**
```php
$series = [
    ['name' => 'Sales', 'data' => [65, 59, 80]],
    ['name' => 'Revenue', 'data' => [28, 48, 40]]
];
```

## New Features in v2.0

### Theming

```blade
{{-- Auto light/dark mode --}}
<x-artisanpack-chart theme="artisanpack-light" ... />
<x-artisanpack-chart theme="artisanpack-dark" ... />
```

### Glass Effects

```blade
<x-artisanpack-chart
    glass="frosted"
    glass-tint="blue-500"
    theme="artisanpack-glass"
    ...
/>
```

### Animation Control

```blade
<x-artisanpack-chart
    :animated="true"
    :animation-speed="800"
    :dynamic-animation="true"
    :dynamic-animation-speed="350"
    ...
/>
```

### Real-Time Updates

```blade
{{-- Polling --}}
<x-artisanpack-chart wire:poll.5s ... />

{{-- Events --}}
$this->dispatch('chart-update-series', [...]);
```

### Sparkline Component

New lightweight component for inline charts:

```blade
<x-artisanpack-sparkline
    :data="[10, 20, 15, 30, 25]"
    type="area"
    color="emerald-500"
/>
```

## Troubleshooting

### Chart not rendering

1. Ensure ApexCharts is installed and imported correctly
2. Check browser console for errors
3. Verify `window.ApexCharts` is defined

### Data not updating

1. Use `wire:poll` or dispatch events for updates
2. Check that series/options are reactive Livewire properties
3. Ensure chart has a unique `id` when using events

### Styling differences

ApexCharts and Chart.js have different default styles. You may need to adjust:
- Colors via the `colors` option
- Grid lines via `grid` option
- Tooltips via `tooltip` option

### Chart.js specific features

Some Chart.js features don't have direct equivalents:
- `tension` → Use `stroke.curve: 'smooth'`
- `plugins.legend.display` → Use `legend.show`
- `scales.y.beginAtZero` → Use `yaxis.min: 0`

## Need Help?

- Review the [Chart Component Documentation](/components/chart)
- Check the [ApexCharts Documentation](https://apexcharts.com/docs/)
- Open an issue on [GitLab](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues)
