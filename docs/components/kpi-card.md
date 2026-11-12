---
title: KpiCard Component
---

# KpiCard Component

A headline metric in a Card, built from Stat (`.apui-card.apui-kpi-card`): the label, value and change, a sparkline of the trend beside the value and, when there is one, progress toward a target.

## Basic Usage

```blade
<x-artisanpack-kpi-card
	:stat="[
		'label' => __( 'Profit' ),
		'value' => '$15,708.98',
		'icon' => 'trending-up',
		'color' => 'success',
		'change' => 8.1,
		'description' => __( 'vs last month' ),
	]"
	:sparkline="$profitByWeek"
	:target="[ 'value' => 15708.98, 'max' => 25000, 'label' => __( 'Goal $25,000' ) ]"
/>
```

`stat` takes Stat's props. `sparkline` draws an area Sparkline beside the value; give the cards in a row the same scale. `target` adds a Progress toward `max` (100 by default), named by its `label`, with "63% of goal" at the end of the row. Show a target only when one exists.

## With a period control

The `period` slot sits in the label row, before the icon:

```blade
<x-artisanpack-kpi-card :stat="[ 'label' => __( 'Total income' ), 'value' => $income, 'change' => $change ]" :sparkline="$trend">
	<x-slot:period>
		<x-artisanpack-select wire:model.live="period" :label="__( 'Period' )" :options="$periods" size="sm" />
	</x-slot:period>
</x-artisanpack-kpi-card>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `stat` | array | `[]` | `label`, `value`, `unit`, `icon`, `color`, `delta`, `change`, `trend`, `description`, `upLabel`, `downLabel`, `flatLabel`, as on Stat. `color` tints the icon tile and the target bar. |
| `sparkline` | array | `[]` | The recent values, oldest first. |
| `sparkline-label` | string | `__( 'Trend from {first} to {latest}' )` | The sparkline's accessible label. |
| `target` | array | `[]` | `[ 'value' => …, 'max' => 100, 'label' => … ]`. |
| `target-label` | string | `__( '{percent}% of goal' )` | The end of the target row; `{percent}`, `{value}` and `{max}` are filled in. |
| `period` | string | none | A period control. The `period` slot replaces it. |
| `classes` | array | `[]` | Extra classes per part: `root`, `top`, `label`, `period`, `icon`, `row`, `summary`, `value`, `unit`, `foot`, `delta`, `description`, `sparkline`, `target`, `targetText`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- `title`, `value`, `icon`, `change` and `change-label` still work and fill `stat`; `sparkline-data` fills `sparkline`. Move them into `stat` and `sparkline`.
- `sparkline-type`, `sparkline-color`, the glass props and the `footer` slot are gone. The sparkline is always an area in `--apui-chart-1`.

## Accessibility

- The sparkline is an image labeled with its first and latest values; the target is a labeled progress bar with its percentage as text beside it.
- The change follows Stat: visible text, an arrow and hidden direction text.
