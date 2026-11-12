---
title: Chart Component
---

# Chart Component

A bar, line, area or donut chart in a Card, built on the ArtisanPack UI design system (`.apui-card.apui-chart`). It's drawn on the server as inline SVG, with the same geometry React and Vue draw: no chart library, so Livewire updates and `wire:poll` redraw it. A small Alpine component from the design system (`apuiChart`) opens the tooltip when a mark is hovered or focused.

## Basic Usage

```blade
<x-artisanpack-chart
	type="bar"
	:title="__( 'Weekly revenue' )"
	:description="__( 'The Grove at Greenfield · best week labeled' )"
	:labels="[ 'W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10' ]"
	:series="[ [ 'name' => __( 'Revenue' ), 'data' => [ 4100, 6000, 8200, 10000, 4000, 8000, 4000, 8000, 6000, 4000 ] ] ]"
	prefix="$"
	notation="compact"
/>
```

`series` is a list of `[ 'name' => …, 'data' => [ … ] ]`, or a plain array of values for one series named by the title. `data` lines up with `labels`; anything that isn't a number is a gap. The first series' best value is labeled in the plot.

## Lines and areas

Two or more series get a legend in the header and take `--apui-chart-1` to `--apui-chart-6` in order. Keep the order stable when filters change, so a series keeps its color. The `actions` slot holds header controls.

```blade
<x-artisanpack-chart
	type="area"
	:title="__( 'Analytic' )"
	:description="__( 'Sales estimation vs target' )"
	:labels="$quarters"
	:series="[ [ 'name' => __( 'Sales' ), 'data' => $sales ], [ 'name' => __( 'Target' ), 'data' => $targets ] ]"
	prefix="$"
	notation="compact"
	:width="1000"
	:height="260"
	wire:poll.30s
>
	<x-slot:actions>
		<x-artisanpack-select wire:model.live="range" :label="__( 'Range' )" :options="[ [ 'value' => '2y', 'label' => __( 'Two years' ) ], [ 'value' => '1y', 'label' => __( 'One year' ) ] ]" size="sm" />
	</x-slot:actions>
</x-artisanpack-chart>
```

`line` draws the lines alone; `area` adds a soft fill under each. Hovering or focusing a category shows a crosshair, a dot on each series and a tooltip with every series' value.

## Donut and the data table

```blade
<x-artisanpack-chart
	type="donut"
	:title="__( 'Active listings' )"
	:description="__( 'By country' )"
	:labels="[ __( 'United States' ), __( 'Mexico' ), __( 'Canada' ), __( 'Other' ) ]"
	:series="[ 1240, 990, 723, 260 ]"
	tabular
/>
```

The donut draws the first series' positive values as slices, and its legend lists every slice's share. `tabular` adds the data as a table under the plot, for any type.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `type` | string | `bar` | `bar`, `line`, `area` or `donut`. A bar chart draws the first series. |
| `series` | array\|null | `[]` | `[ [ 'name' => …, 'data' => [ … ] ] ]`, or a plain array of values. A `data` entry may also be a point, `[ 'x' => …, 'y' => … ]` or `[ x, y ]`. |
| `labels` | array | `null` | The categories: x-axis labels, or the donut's slices. Unset, the points' `x` values fill them. |
| `title` | string | `null` | The heading. It names the plot and the table, so every chart needs one. |
| `description` | string | `null` | Supporting text under the title. |
| `actions` | string | `null` | Header controls; the `actions` slot replaces it. |
| `tabular` | bool | `false` | Shows the data table under the plot. `show-table` works too. |
| `prefix` | string | `null` | Printed before every value, such as `$`. |
| `suffix` | string | `null` | Printed after every value, such as `%`. |
| `notation` | string | `standard` | `standard` (12,500) or `compact` ($12.5k; the axis shares the unit of its largest value). |
| `width` | int | `520` | The plot's viewBox width. The plot scales to its container; this sets its aspect ratio and how large the axis text reads. The donut is always 160. |
| `height` | int | `220` | The plot's viewBox height. |
| `classes` | array | `[]` | Extra classes per part: `root`, `header`, `title`, `description`, `actions`, `legend`, `swatch`, `body`, `plot`, `grid`, `axis`, `mark`, `line`, `area`, `point`, `crosshair`, `dot`, `value`, `tooltip`, `table`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- The Chart no longer uses ApexCharts: remove `apexcharts` from your `package.json` and the `ApexCharts` import or global from your JavaScript. The design-system Alpine plugin (`apuiBehavior`) is all the Chart needs.
- `series` and `labels` keep ApexCharts' shape. When `labels` or `title` aren't set, `options.xaxis.categories`, `options.labels` and `options.title.text` still fill them; every other `options` key is ignored.
- ApexCharts-style points still work: a `data` entry may be `[ 'x' => 'Jan', 'y' => 3 ]` or `[ 'Jan', 3 ]`. The `y` is the value, and when `labels` (and the `options` fallbacks) aren't set, the first pointed series' `x` values become the labels, printed as given (pass `labels` to format dates or timestamps).
- **The default `type` is now `bar`, not `line`.** A chart that relied on the 2.x default and has two or more series now draws only the first one, as bars, since a bar chart draws a single series. Add `type="line"` to keep the 2.x look and every series.
- `type` takes `bar`, `line`, `area` or `donut`; the other ApexCharts types fall back to `bar`. `pie` becomes `donut`.
- `series` may be `null` (2.x's default): it draws an empty chart instead of failing.
- `theme`, `animated`, `animation-speed`, `animation-easing`, `dynamic-animation`, `dynamic-animation-speed`, `glass`, `glass-tint` and `glass-tint-opacity` are gone, and so is `ChartThemes`. Colors come from the theme's `--apui-chart-1` to `--apui-chart-6`, in light, dark and high contrast.
- `height` and `width` are the plot's viewBox size, not CSS lengths: the plot fills its container's width.
- The `chart-update`, `chart-update-series` and `chart-append-data` events are gone: the chart renders on the server, so change the component's properties (or use `wire:poll`) and it redraws.
- Grouped and stacked bars, pie, radial bar, heatmap and the other ApexCharts types aren't available.

## Accessibility

- Every chart has a title that names the plot (`role="group"`) and the table, and a legend for two or more series.
- Every mark is focusable, with the category and its values as its accessible name. The tooltip opens on hover and keyboard focus, and Escape closes it. The tooltip is hidden from assistive technology, since the mark's name says the same.
- Light-mode `--apui-chart-2`, `-4` and `-6` sit under 3:1, so the values are always reachable as text: in the tooltip, the labeled value and the table (`tabular`). Don't rely on color alone.
- The plot stays left to right in right-to-left layouts; the header and legend mirror.
