---
title: Chart Component
---

# Chart Component

The Chart component provides a wrapper for Chart.js, allowing you to easily create interactive charts in your Laravel application.

## Basic Usage

```blade
<x-artisanpack-chart wire:model="chartData" />
```

## Livewire Component Example

```php
class ChartExample extends Component
{
    public $chartData;
    
    public function mount()
    {
        $this->chartData = [
            'type' => 'bar',
            'data' => [
                'labels' => ['January', 'February', 'March', 'April', 'May'],
                'datasets' => [
                    [
                        'label' => 'Sales',
                        'data' => [65, 59, 80, 81, 56],
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ]
            ]
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
    <x-artisanpack-chart wire:model="chartData" class="h-64" />
</div>
```

## Line Chart Example

```php
public function mount()
{
    $this->chartData = [
        'type' => 'line',
        'data' => [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'datasets' => [
                [
                    'label' => 'Website Visits',
                    'data' => [65, 59, 80, 81, 56],
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1
                ]
            ]
        ]
    ];
}
```

## Pie Chart Example

```php
public function mount()
{
    $this->chartData = [
        'type' => 'pie',
        'data' => [
            'labels' => ['Red', 'Blue', 'Yellow', 'Green', 'Purple'],
            'datasets' => [
                [
                    'label' => 'Dataset 1',
                    'data' => [12, 19, 3, 5, 2],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    'borderWidth' => 1
                ]
            ]
        ]
    ];
}
```

## Dynamic Chart Example

```php
class DynamicChartExample extends Component
{
    public $chartData;
    
    public function mount()
    {
        $this->refreshChartData();
    }
    
    public function refreshChartData()
    {
        $this->chartData = [
            'type' => 'bar',
            'data' => [
                'labels' => ['January', 'February', 'March', 'April', 'May'],
                'datasets' => [
                    [
                        'label' => 'Sales',
                        'data' => [
                            rand(50, 100),
                            rand(50, 100),
                            rand(50, 100),
                            rand(50, 100),
                            rand(50, 100)
                        ],
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ]
        ];
    }
    
    public function render()
    {
        return view('livewire.dynamic-chart-example');
    }
}
```

```blade
<div>
    <x-artisanpack-chart wire:model="chartData" class="h-64" />
    <x-artisanpack-button label="Refresh Data" wire:click="refreshChartData" class="mt-4" />
</div>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the chart |

## Notes

- The component requires a Livewire model binding (using `wire:model`) that contains the chart configuration.
- The chart configuration should follow the Chart.js format with `type`, `data`, and optional `options` properties.
- The component automatically refreshes when the bound model changes.
- You need to include Chart.js in your application for this component to work.
- You can customize the chart's appearance by applying CSS classes to the component.
- The chart's height is determined by its container, so make sure to set an appropriate height using CSS.
