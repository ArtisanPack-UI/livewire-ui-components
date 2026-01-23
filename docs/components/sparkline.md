---
title: Sparkline Component
---

# Sparkline Component

The Sparkline component provides lightweight inline charts for use in stats, dashboards, and compact visualizations. Built on ApexCharts sparkline mode, it offers minimal overhead while maintaining smooth animations.

## Installation

The Sparkline component requires ApexCharts and the sparkline Alpine component.

### 1. Install ApexCharts

```bash
npm install apexcharts
```

### 2. Configure Your JavaScript

Add these imports to your `resources/js/app.js`:

```javascript
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

// Import sparkline Alpine component
import '../../vendor/artisanpack-ui/livewire-ui-components/resources/js/sparkline.js';
```

**Note:** The path to `sparkline.js` may vary depending on your setup:
- **Symlinked package (development)**: `../../vendor/artisanpack-ui/livewire-ui-components/resources/js/sparkline.js`
- **Production installation**: Adjust the path based on your vendor directory structure

### 3. Build Assets

```bash
npm run dev
# or for production
npm run build
```

## Basic Usage

```blade
<x-artisanpack-sparkline :data="[10, 20, 15, 30, 25, 35, 40]" />
```

## Types

### Line Sparkline (Default)

```blade
<x-artisanpack-sparkline
    type="line"
    :data="[10, 20, 15, 30, 25, 35, 40]"
    color="blue-500"
/>
```

### Area Sparkline

```blade
<x-artisanpack-sparkline
    type="area"
    :data="[10, 20, 15, 30, 25, 35, 40]"
    color="emerald-500"
    :fill-opacity="0.3"
/>
```

### Bar Sparkline

```blade
<x-artisanpack-sparkline
    type="bar"
    :data="[10, 20, 15, 30, 25, 35, 40]"
    color="amber-500"
/>
```

## With Stat Component

Sparklines work great inside stat cards. Pass sparkline props directly to the stat component:

```blade
<x-artisanpack-stat
    title="Revenue"
    value="$12,450"
    description="+12% from last month"
    :sparkline-data="[1200, 1400, 1100, 1600, 1450, 1800, 2100]"
    sparkline-type="area"
    sparkline-color="emerald-500"
/>
```

## Color Support

### Tailwind Colors

Use any Tailwind color name with optional shade:

```blade
{{-- With shade --}}
<x-artisanpack-sparkline :data="$data" color="blue-500" />
<x-artisanpack-sparkline :data="$data" color="emerald-600" />
<x-artisanpack-sparkline :data="$data" color="red-400" />

{{-- Without shade (defaults to 500) --}}
<x-artisanpack-sparkline :data="$data" color="blue" />
<x-artisanpack-sparkline :data="$data" color="emerald" />
```

### Hex Colors

Use custom hex colors:

```blade
<x-artisanpack-sparkline :data="$data" color="#8b5cf6" />
<x-artisanpack-sparkline :data="$data" color="#f97316" />
```

### Available Tailwind Colors

All standard Tailwind colors are supported:
- slate, gray, zinc, neutral, stone
- red, orange, amber, yellow, lime
- green, emerald, teal, cyan, sky
- blue, indigo, violet, purple, fuchsia
- pink, rose

## Sizing

### Height

```blade
{{-- Default height (40px) --}}
<x-artisanpack-sparkline :data="$data" />

{{-- Custom height --}}
<x-artisanpack-sparkline :data="$data" :height="60" />
<x-artisanpack-sparkline :data="$data" height="80" />
```

### Width

```blade
{{-- Auto width (fills container) --}}
<x-artisanpack-sparkline :data="$data" />

{{-- Fixed width --}}
<x-artisanpack-sparkline :data="$data" :width="100" />
<x-artisanpack-sparkline :data="$data" width="150" />
```

## Stroke Customization

### Stroke Width

```blade
{{-- Default stroke width (2) --}}
<x-artisanpack-sparkline :data="$data" />

{{-- Thicker stroke --}}
<x-artisanpack-sparkline :data="$data" :stroke-width="3" />

{{-- Thinner stroke --}}
<x-artisanpack-sparkline :data="$data" :stroke-width="1" />
```

### Curve Style

```blade
{{-- Smooth curves (default) --}}
<x-artisanpack-sparkline :data="$data" curve="smooth" />

{{-- Straight lines --}}
<x-artisanpack-sparkline :data="$data" curve="straight" />

{{-- Stepline --}}
<x-artisanpack-sparkline :data="$data" curve="stepline" />
```

## Area Fill Options

For area sparklines, customize the gradient fill:

```blade
{{-- Default fill opacity (0.3) --}}
<x-artisanpack-sparkline type="area" :data="$data" />

{{-- Higher opacity --}}
<x-artisanpack-sparkline type="area" :data="$data" :fill-opacity="0.5" />

{{-- Lower opacity --}}
<x-artisanpack-sparkline type="area" :data="$data" :fill-opacity="0.1" />
```

## Livewire Integration

### Basic Binding

```php
class Dashboard extends Component
{
    public array $revenueData = [];

    public function mount()
    {
        $this->revenueData = [100, 120, 115, 130, 125, 140, 150];
    }
}
```

```blade
<x-artisanpack-sparkline :data="$revenueData" color="emerald-500" />
```

### Real-Time Updates

Sparklines update smoothly when data changes:

```blade
<div wire:poll.5s>
    <x-artisanpack-sparkline :data="$liveData" color="blue-500" />
</div>
```

## Dashboard Example

```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Revenue --}}
    <x-artisanpack-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-base-content/60">Revenue</p>
                <p class="text-2xl font-bold">$24,500</p>
            </div>
            <x-artisanpack-sparkline
                type="area"
                :data="[12, 14, 11, 16, 15, 18, 21]"
                color="emerald-500"
                :height="40"
                :width="80"
            />
        </div>
    </x-artisanpack-card>

    {{-- Users --}}
    <x-artisanpack-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-base-content/60">Users</p>
                <p class="text-2xl font-bold">1,234</p>
            </div>
            <x-artisanpack-sparkline
                type="line"
                :data="[50, 65, 55, 70, 80, 75, 90]"
                color="blue-500"
                :height="40"
                :width="80"
            />
        </div>
    </x-artisanpack-card>

    {{-- Orders --}}
    <x-artisanpack-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-base-content/60">Orders</p>
                <p class="text-2xl font-bold">456</p>
            </div>
            <x-artisanpack-sparkline
                type="bar"
                :data="[20, 25, 18, 30, 28, 35, 32]"
                color="amber-500"
                :height="40"
                :width="80"
            />
        </div>
    </x-artisanpack-card>
</div>
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Unique identifier |
| data | array | [] | Array of numeric values |
| type | string | 'line' | Chart type (line, area, bar) |
| height | string\|int | 40 | Chart height in pixels |
| width | string\|int | null | Chart width (auto if null) |
| color | string | null | Color (Tailwind name or hex, defaults to emerald-500) |
| strokeWidth | int | 2 | Line/area stroke width |
| curve | string | 'smooth' | Curve style (smooth, straight, stepline) |
| fillOpacity | float | 0.3 | Area fill gradient opacity (0-1) |

## Notes

- Sparklines are designed to be compact; avoid using them for detailed data visualization
- The component automatically disables tooltips and legends for a cleaner look
- Use the Chart component for more complex charting needs
- All numeric data should be passed as an array of numbers
- Sparklines inherit the container's width when no width is specified

## See Also

- [Chart Component](chart) - Full-featured charts with all ApexCharts options
- [Stat Component](stat) - Statistics display with sparkline support
