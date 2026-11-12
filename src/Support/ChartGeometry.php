<?php

/**
 * Chart geometry for the design-system Chart.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use Stringable;

/**
 * The SVG geometry of a chart (#63): a port of `chartGeometry`,
 * `chartTicks` and `formatChartValue` in `@artisanpack-ui/behavior`
 * (`src/data/chart.ts`), so Blade draws the same bars, lines, areas, arcs,
 * ticks and labels as React and Vue. Keep the two in step: the shared vectors
 * in `tests/Fixtures/design-system/chart-vectors.json` (a copy of the
 * behavior package's `test/fixtures/chart-vectors.json`) check both.
 *
 * Coordinates have one decimal (two for donut arcs), as JavaScript's
 * `toFixed` writes them. Series and slices take `--apui-chart-1…6` in fixed
 * order: `series` is that number. The plot stays LTR (D-004).
 *
 * @since 3.0.0
 */
final class ChartGeometry
{
    /**
     * The chart types.
     *
     * @since 3.0.0
     */
    public const TYPES = ['bar', 'line', 'area', 'donut'];

    public const WIDTH = 520;

    public const HEIGHT = 220;

    public const DONUT_SIZE = 160;

    public const DONUT_RADIUS = 64;

    public const DONUT_INNER_RADIUS = 44;

    public const COLORS = 6;

    private const PAD_LEFT = 40;

    private const PAD_RIGHT = 8;

    private const PAD_TOP = 12;

    private const PAD_BOTTOM = 24;

    private const TICK_COUNT = 5;

    private const BAR_WIDTH = 0.56;

    private const BAR_OFFSET = 0.22;

    private const BAR_RADIUS = 4;

    private const UNITS = [[1e9, 'B'], [1e6, 'M'], [1e3, 'k']];

    /**
     * The most decimals a value or tick prints; anything smaller reads as 0.
     */
    private const MAX_DIGITS = 10;

    /**
     * Heckbert's nice number: 1, 2, 5 or 10 times a power of ten.
     *
     * @since 3.0.0
     */
    public static function niceNumber(float $value, bool $round): float
    {
        if (! ($value > 0)) {
            return 1.0;
        }

        $base     = self::pow10(self::exponentOf($value));
        $fraction = $value / $base;
        if ($round) {
            $nice = $fraction < 1.5 ? 1 : ($fraction < 3 ? 2 : ($fraction < 7 ? 5 : 10));
        } else {
            $nice = $fraction <= 1 ? 1 : ($fraction <= 2 ? 2 : ($fraction <= 5 ? 5 : 10));
        }

        return $nice * $base;
    }

    /**
     * The y axis's values: nice steps from at or below the smallest value (or
     * zero) to at or above the largest (or zero), about five intervals.
     *
     * @since 3.0.0
     *
     * @return list<float|int>
     */
    public static function ticks(int|float $min, int|float $max): array
    {
        $low  = min(0, $min);
        $high = max(0, $max);
        if ($high == $low) {
            $high = $low + 1;
        }
        $step   = self::niceNumber(self::niceNumber((float) ($high - $low), false) / self::TICK_COUNT, true);
        $digits = max(0, -self::exponentOf($step));
        $start  = self::roundTo(floor($low / $step) * $step, $digits);
        $end    = self::roundTo(ceil($high / $step) * $step, $digits);
        $ticks  = [];
        for ($index = 0; ; $index++) {
            $tick    = self::roundTo($start + $index * $step, $digits);
            $ticks[] = $tick;
            if ($tick >= $end) {
                break;
            }
        }

        return $ticks;
    }

    /**
     * The series as `[ 'name' => …, 'data' => … ]`. A plain array of values is
     * one series, named `$title`; entries that aren't arrays with keys are
     * skipped otherwise. A series' `data` takes values or ApexCharts-style
     * points (`[ 'x' => …, 'y' => … ]` or `[ x, y ]`), read as their `y`.
     * Values that aren't finite numbers are null.
     *
     * @since 3.0.0
     *
     * @return list<array{name: string, data: list<float|int|null>}>
     */
    public static function series(mixed $series, string $title = ''): array
    {
        if (! is_array($series) || [] === $series) {
            return [];
        }

        $series = array_values($series);
        $isSet  = static fn (mixed $entry): bool => is_array($entry) && ! array_is_list($entry);

        if ([] === array_filter($series, $isSet)) {
            return [['name' => $title, 'data' => array_map(self::toNumber(...), $series)]];
        }

        $normalized = [];
        foreach (array_filter($series, $isSet) as $entry) {
            $name         = $entry['name'] ?? null;
            $data         = $entry['data'] ?? null;
            $normalized[] = [
                'name' => null === $name ? '' : self::text($name),
                'data' => is_array($data) ? array_map(self::valueOf(...), array_values($data)) : [],
            ];
        }

        return $normalized;
    }

    /**
     * The categories the points carry: the `x` of each entry of the first
     * series whose `data` has an ApexCharts-style point, or `[]`. Used when
     * the chart has no labels.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public static function pointLabels(mixed $series): array
    {
        if (! is_array($series)) {
            return [];
        }

        foreach ($series as $entry) {
            if (! is_array($entry) || array_is_list($entry) || ! is_array($entry['data'] ?? null)) {
                continue;
            }
            $points = array_map(self::pointOf(...), array_values($entry['data']));
            if ([] === array_filter($points, static fn (?array $point): bool => null !== $point)) {
                continue;
            }

            return array_map(
                static fn (?array $point): string => null === $point || null === $point[0] ? '' : self::text($point[0]),
                $points,
            );
        }

        return [];
    }

    /**
     * A value as the tooltip, value label and table print it: compact values
     * keep one decimal (`$4.1k`), others up to two (`12,500`), or two
     * significant digits below 1 (`0.0012`).
     *
     * @since 3.0.0
     *
     * @param  array{prefix?: string|null, suffix?: string|null, notation?: string|null}  $options
     */
    public static function formatValue(int|float $value, array $options = []): string
    {
        $magnitude = abs($value);
        $level     = 'compact' === ($options['notation'] ?? null) ? self::compactLevel($magnitude) : -1;
        $unit      = $level < 0 ? null : array_reverse(self::UNITS)[$level];
        $digits    = null !== $unit
            ? self::plain($magnitude / $unit[0], 1, false).$unit[1]
            : self::plain($magnitude, self::valueDigits($magnitude), true);

        return self::signed($value, $digits, (string) ($options['prefix'] ?? ''), (string) ($options['suffix'] ?? ''));
    }

    /**
     * The y-axis labels; compact ones share the unit of the largest. They
     * print up to two decimals, or as many as the step between ticks needs.
     *
     * @since 3.0.0
     *
     * @param  list<float|int>  $ticks
     * @param  array{prefix?: string|null, suffix?: string|null, notation?: string|null}  $options
     *
     * @return list<string>
     */
    public static function formatTicks(array $ticks, array $options = []): array
    {
        $largest = 0;
        foreach ($ticks as $tick) {
            $largest = max($largest, abs($tick));
        }
        $unit   = 'compact' === ($options['notation'] ?? null) ? self::unitFor($largest) : null;
        $ticks  = array_values($ticks);
        $step   = count($ticks) > 1 ? abs($ticks[1] - $ticks[0]) / (null !== $unit ? $unit[0] : 1) : 0;
        $digits = $step > 0 ? min(self::MAX_DIGITS, max(2, -self::exponentOf(self::niceNumber((float) $step, true)))) : 2;

        return array_map(static function (int|float $tick) use ($unit, $digits, $options): string {
            $magnitude = abs($tick);
            $text      = null !== $unit
                ? self::plain($magnitude / $unit[0], $digits, true).$unit[1]
                : self::plain($magnitude, $digits, true);

            return self::signed($tick, $text, (string) ($options['prefix'] ?? ''), (string) ($options['suffix'] ?? ''));
        }, $ticks);
    }

    /**
     * The series color number, 1 to 6, for the series or slice at an index.
     *
     * @since 3.0.0
     */
    public static function color(int $index): int
    {
        return ($index % self::COLORS) + 1;
    }

    /**
     * Everything the chart draws: the same keys and strings as the
     * TypeScript `ChartGeometry`.
     *
     * @since 3.0.0
     *
     * @param  array{type?: string, labels?: array<int, mixed>, width?: float|int, height?: float|int, title?: string|null, prefix?: string|null, suffix?: string|null, notation?: string|null}  $options
     *
     * @return array<string, mixed>
     */
    public static function compute(mixed $series, array $options = []): array
    {
        $type   = in_array($options['type'] ?? null, self::TYPES, true) ? $options['type'] : 'bar';
        $format = [
            'prefix'   => $options['prefix'] ?? null,
            'suffix'   => $options['suffix'] ?? null,
            'notation' => $options['notation'] ?? null,
        ];
        $all    = self::series($series, (string) ($options['title'] ?? ''));
        $given  = isset($options['labels']) && is_array($options['labels']) && [] !== $options['labels']
            ? $options['labels']
            : self::pointLabels($series);
        $labels = array_map(
            static fn (mixed $label): string => null === $label ? '' : self::text($label),
            array_values($given),
        );

        if ('donut' === $type) {
            return self::donut($all, $labels, $format);
        }

        $width  = isset($options['width']) && $options['width'] > 0 ? $options['width'] : self::WIDTH;
        $height = isset($options['height']) && $options['height'] > 0 ? $options['height'] : self::HEIGHT;
        $drawn  = 'bar' === $type ? array_slice($all, 0, 1) : $all;
        $count  = count($labels);
        foreach ($drawn as $entry) {
            $count = max($count, count($entry['data']));
        }
        $label  = static fn (int $index): string => $labels[$index] ?? '';
        $min    = 0;
        $max    = 0;
        foreach ($drawn as $entry) {
            foreach ($entry['data'] as $value) {
                if (null !== $value) {
                    $min = min($min, $value);
                    $max = max($max, $value);
                }
            }
        }
        $ticks      = self::ticks($min, $max);
        $tickLabels = self::formatTicks($ticks, $format);
        $low        = $ticks[0];
        $high       = $ticks[count($ticks) - 1];
        $left       = self::PAD_LEFT;
        $right      = $width - self::PAD_RIGHT;
        $top        = self::PAD_TOP;
        $bottom     = $height - self::PAD_BOTTOM;
        $toY        = static fn (int|float $value): float => $top + (($high - $value) / ($high - $low)) * ($bottom - $top);
        $zero       = $toY(0);
        $plotWidth  = $right - $left;

        $legend = [];
        if (count($drawn) > 1) {
            foreach ($drawn as $index => $entry) {
                $legend[] = ['name' => $entry['name'], 'series' => self::color($index), 'value' => null];
            }
        }

        $rows = [];
        for ($index = 0; $index < $count; $index++) {
            $cells = [];
            foreach ($drawn as $entry) {
                $value   = $entry['data'][$index] ?? null;
                $cells[] = null === $value ? '' : self::formatValue($value, $format);
            }
            $rows[] = ['label' => $label($index), 'cells' => $cells];
        }

        $geometry = [
            'type'       => $type,
            'width'      => $width,
            'height'     => $height,
            'left'       => self::fixed($left),
            'right'      => self::fixed($right),
            'tickX'      => self::fixed($left - 8),
            'labelY'     => self::fixed($height - 6),
            'ticks'      => array_map(static fn (int|float $tick, string $text): array => [
                'y'     => self::fixed($toY($tick)),
                'textY' => self::fixed($toY($tick) + 4),
                'label' => $text,
            ], $ticks, $tickLabels),
            'categories' => [],
            'legend'     => $legend,
            'marks'      => [],
            'lines'      => [],
            'areas'      => [],
            'points'     => [],
            'value'      => null,
            'table'      => [
                'columns' => ['', ...array_map(static fn (array $entry): string => $entry['name'], $drawn)],
                'rows'    => $rows,
            ],
        ];

        $first = $drawn[0] ?? null;
        $peak  = null;
        if (null !== $first) {
            foreach ($first['data'] as $index => $value) {
                if ($index < $count && null !== $value && (null === $peak || $value > $peak[1])) {
                    $peak = [$index, $value];
                }
            }
        }

        if ('bar' === $type) {
            $slot = $count > 0 ? $plotWidth / $count : $plotWidth;
            for ($index = 0; $index < $count; $index++) {
                $center                   = $left + $index * $slot + $slot / 2;
                $geometry['categories'][] = ['x' => self::fixed($center), 'label' => $label($index)];
                $value                    = $first['data'][$index] ?? null;
                if (null === $value) {
                    continue;
                }
                $x        = $left + $index * $slot + $slot * self::BAR_OFFSET;
                $barWidth = $slot * self::BAR_WIDTH;
                $y        = $toY($value);
                $radius   = min(self::BAR_RADIUS, $barWidth / 2, abs($zero - $y));
                $toward   = $y <= $zero ? $radius : -$radius;
                $d        = 'M'.self::fixed($x).','.self::fixed($zero).' V'.self::fixed($y + $toward)
                    .' Q'.self::fixed($x).','.self::fixed($y).' '.self::fixed($x + $radius).','.self::fixed($y)
                    .' H'.self::fixed($x + $barWidth - $radius)
                    .' Q'.self::fixed($x + $barWidth).','.self::fixed($y).' '.self::fixed($x + $barWidth).','.self::fixed($y + $toward)
                    .' V'.self::fixed($zero).' Z';
                $body                = self::formatValue($value, $format);
                $geometry['marks'][] = [
                    'index'   => $index,
                    'series'  => self::color(0),
                    'shape'   => 'path',
                    'd'       => $d,
                    'x'       => self::fixed($x),
                    'y'       => self::fixed(min($y, $zero)),
                    'width'   => self::fixed($barWidth),
                    'height'  => self::fixed(abs($zero - $y)),
                    'title'   => $label($index),
                    'body'    => $body,
                    'label'   => $label($index).': '.$body,
                    'anchorX' => self::fixed($center),
                    'anchorY' => self::fixed(min($y, $zero)),
                ];
            }
            if (null !== $peak) {
                $geometry['value'] = [
                    'x'    => self::fixed($left + $peak[0] * $slot + $slot / 2),
                    'y'    => self::fixed(min($toY($peak[1]), $zero) - 8),
                    'text' => self::formatValue($peak[1], $format),
                ];
            }

            return $geometry;
        }

        $step = $count > 1 ? $plotWidth / ($count - 1) : 0;
        $xOf  = static fn (int $index): float|int => $count > 1 ? $left + $index * $step : $left + $plotWidth / 2;
        for ($index = 0; $index < $count; $index++) {
            $geometry['categories'][] = ['x' => self::fixed($xOf($index)), 'label' => $label($index)];
        }

        $segment = static function (array $points): string {
            $parts = [];
            foreach ($points as $index => $point) {
                $parts[] = (0 === $index ? 'M' : 'L').self::fixed($point[0]).','.self::fixed($point[1]);
            }

            return implode(' ', $parts);
        };

        foreach ($drawn as $seriesIndex => $entry) {
            $runs = [];
            $run  = [];
            for ($index = 0; $index < $count; $index++) {
                $value = $entry['data'][$index] ?? null;
                if (null === $value) {
                    if ([] !== $run) {
                        $runs[] = $run;
                    }
                    $run = [];

                    continue;
                }
                $run[] = [$xOf($index), $toY($value)];
            }
            if ([] !== $run) {
                $runs[] = $run;
            }
            $line = implode(' ', array_map($segment, $runs));
            if ('' === $line) {
                continue;
            }
            $geometry['lines'][] = ['series' => self::color($seriesIndex), 'd' => $line];
            if ('area' === $type) {
                $geometry['areas'][] = [
                    'series' => self::color($seriesIndex),
                    'd'      => implode(' ', array_map(static fn (array $points): string => $segment($points)
                        .' L'.self::fixed($points[count($points) - 1][0]).','.self::fixed($zero)
                        .' L'.self::fixed($points[0][0]).','.self::fixed($zero).' Z', $runs)),
                ];
            }
        }

        $many = count($drawn) > 1;
        for ($index = 0; $index < $count; $index++) {
            $x      = $xOf($index);
            $dots   = [];
            $parts  = [];
            $anchor = $bottom;
            foreach ($drawn as $seriesIndex => $entry) {
                $value = $entry['data'][$index] ?? null;
                if (null === $value) {
                    continue;
                }
                $y       = $toY($value);
                $anchor  = min($anchor, $y);
                $dots[]  = ['series' => self::color($seriesIndex), 'cx' => self::fixed($x), 'cy' => self::fixed($y)];
                $text    = self::formatValue($value, $format);
                $parts[] = $many ? $entry['name'].' '.$text : $text;
            }
            if ([] === $dots) {
                continue;
            }
            $geometry['points'][] = [
                'index'  => $index,
                'x'      => self::fixed($x),
                'top'    => self::fixed($top),
                'bottom' => self::fixed($bottom),
                'dots'   => $dots,
            ];
            $start               = $count > 1 ? max($left, $x - $step / 2) : $left;
            $end                 = $count > 1 ? min($right, $x + $step / 2) : $right;
            $geometry['marks'][] = [
                'index'   => $index,
                'series'  => self::color(0),
                'shape'   => 'rect',
                'd'       => '',
                'x'       => self::fixed($start),
                'y'       => self::fixed($top),
                'width'   => self::fixed($end - $start),
                'height'  => self::fixed($bottom - $top),
                'title'   => $label($index),
                'body'    => implode(' · ', $parts),
                'label'   => $label($index).': '.implode(', ', $parts),
                'anchorX' => self::fixed($x),
                'anchorY' => self::fixed($anchor),
            ];
        }

        if (null !== $peak) {
            $geometry['value'] = [
                'x'    => self::fixed($xOf($peak[0])),
                'y'    => self::fixed($toY($peak[1]) - 10),
                'text' => self::formatValue($peak[1], $format),
            ];
        }

        return $geometry;
    }

    /**
     * A number with fixed decimals, exactly as JavaScript's
     * `Number.prototype.toFixed` writes it: rounded from the double's exact
     * binary value (so 1.005 is `1.00` and 1.15 is `1.1`, where
     * `number_format` pre-rounds them up), ties away from zero.
     *
     * @since 3.0.0
     */
    public static function toFixed(int|float $value, int $digits): string
    {
        $value = (float) $value;
        if (! is_finite($value)) {
            return is_nan($value) ? 'NaN' : ($value > 0 ? 'Infinity' : '-Infinity');
        }

        $negative           = $value < 0;
        [$whole, $fraction] = explode('.', sprintf('%.53F', abs($value)));
        $kept               = $whole.substr($fraction, 0, $digits);
        if (($fraction[$digits] ?? '0') >= '5') {
            $index = strlen($kept) - 1;
            while ($index >= 0 && '9' === $kept[$index]) {
                $kept[$index] = '0';
                $index--;
            }
            $kept = $index < 0 ? '1'.$kept : substr_replace($kept, (string) ((int) $kept[$index] + 1), $index, 1);
        }
        $text = $digits > 0
            ? substr($kept, 0, strlen($kept) - $digits).'.'.substr($kept, -$digits)
            : $kept;

        return ($negative ? '-' : '').$text;
    }

    /**
     * The donut: one arc per positive value, from 12 o'clock, clockwise.
     *
     * @param  list<array{name: string, data: list<float|int|null>}>  $all
     * @param  list<string>  $labels
     * @param  array{prefix?: string|null, suffix?: string|null, notation?: string|null}  $format
     *
     * @return array<string, mixed>
     */
    private static function donut(array $all, array $labels, array $format): array
    {
        $size   = self::DONUT_SIZE;
        $center = $size / 2;
        $outer  = self::DONUT_RADIUS;
        $inner  = self::DONUT_INNER_RADIUS;
        $entry  = $all[0] ?? ['name' => '', 'data' => []];
        $count  = max(count($labels), count($entry['data']));
        $values = [];
        $total  = 0;
        for ($index = 0; $index < $count; $index++) {
            $value    = $entry['data'][$index] ?? null;
            $values[] = $value;
            if (null !== $value && $value > 0) {
                $total += $value;
            }
        }
        $point = static fn (int|float $radius, float $angle): string => self::fixed($center + $radius * cos($angle), 2).','.self::fixed($center + $radius * sin($angle), 2);

        $geometry = [
            'type'       => 'donut',
            'width'      => $size,
            'height'     => $size,
            'left'       => self::fixed(0),
            'right'      => self::fixed($size),
            'tickX'      => self::fixed(0),
            'labelY'     => self::fixed($size),
            'ticks'      => [],
            'categories' => [],
            'legend'     => [],
            'marks'      => [],
            'lines'      => [],
            'areas'      => [],
            'points'     => [],
            'value'      => null,
            'table'      => ['columns' => ['', $entry['name']], 'rows' => []],
        ];

        $angle = -M_PI / 2;
        foreach ($values as $index => $value) {
            $label                         = $labels[$index] ?? '';
            $amount                        = null !== $value && $value > 0 ? $value : 0;
            $percent                       = self::fixed($total > 0 ? ($amount / $total) * 100 : 0).'%';
            $geometry['legend'][]          = ['name' => $label, 'series' => self::color($index), 'value' => $percent];
            $geometry['table']['rows'][]   = [
                'label' => $label,
                'cells' => [null === $value ? '' : self::formatValue($value, $format).' ('.$percent.')'],
            ];
            if (0 == $amount || 0 == $total) {
                continue;
            }
            $sweep = ($amount / $total) * 2 * M_PI;
            $end   = $angle + $sweep;
            if ($sweep >= 2 * M_PI - 1e-9) {
                $d = 'M'.$point($outer, -M_PI / 2).' A'.$outer.','.$outer.' 0 1 1 '.$point($outer, M_PI / 2)
                    .' A'.$outer.','.$outer.' 0 1 1 '.$point($outer, -M_PI / 2)
                    .' M'.$point($inner, -M_PI / 2).' A'.$inner.','.$inner.' 0 1 0 '.$point($inner, M_PI / 2)
                    .' A'.$inner.','.$inner.' 0 1 0 '.$point($inner, -M_PI / 2).' Z';
            } else {
                $large = $sweep > M_PI ? 1 : 0;
                $d     = 'M'.$point($outer, $angle).' A'.$outer.','.$outer.' 0 '.$large.' 1 '.$point($outer, $end)
                    .' L'.$point($inner, $end).' A'.$inner.','.$inner.' 0 '.$large.' 0 '.$point($inner, $angle).' Z';
            }
            $middle              = $angle + $sweep / 2;
            $formatted           = self::formatValue($amount, $format);
            $geometry['marks'][] = [
                'index'   => $index,
                'series'  => self::color($index),
                'shape'   => 'path',
                'd'       => $d,
                'x'       => '',
                'y'       => '',
                'width'   => '',
                'height'  => '',
                'title'   => $label,
                'body'    => $formatted.' · '.$percent,
                'label'   => $label.': '.$formatted.', '.$percent,
                'anchorX' => self::fixed($center + $outer * cos($middle), 2),
                'anchorY' => self::fixed($center + $outer * sin($middle), 2),
            ];
            $angle = $end;
        }

        return $geometry;
    }

    /**
     * An ApexCharts-style point, `[ 'x' => …, 'y' => … ]` or `[ x, y ]`, as
     * `[ x, y ]`, or null.
     *
     * @return array{0: mixed, 1: mixed}|null
     */
    private static function pointOf(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }
        if (! array_is_list($value) && array_key_exists('y', $value)) {
            return [$value['x'] ?? null, $value['y']];
        }
        if (array_is_list($value) && 2 === count($value)) {
            return [$value[0], $value[1]];
        }

        return null;
    }

    /**
     * A data entry's value: the number, or a point's `y`.
     */
    private static function valueOf(mixed $value): int|float|null
    {
        $point = self::pointOf($value);

        return self::toNumber(null !== $point ? $point[1] : $value);
    }

    /**
     * A finite number, a numeric string as a number, or null.
     */
    private static function toNumber(mixed $value): int|float|null
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return is_finite($value) ? $value : null;
        }
        if (is_string($value) && '' !== trim($value) && is_numeric(trim($value))) {
            $number = (float) trim($value);

            return is_finite($number) ? $number : null;
        }

        return null;
    }

    /**
     * A label or name as JavaScript's `String()` writes it.
     */
    private static function text(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_scalar($value) || $value instanceof Stringable) {
            return (string) $value;
        }

        return '';
    }

    /**
     * 10 to a whole power, dividing for negative powers as the TypeScript does.
     */
    private static function pow10(int $exponent): float
    {
        return $exponent >= 0 ? (float) (10 ** $exponent) : 1 / (float) (10 ** -$exponent);
    }

    /**
     * The power of ten at or below a positive number.
     */
    private static function exponentOf(float $value): int
    {
        $exponent = (int) floor(log10($value));
        while (self::pow10($exponent + 1) <= $value) {
            $exponent++;
        }
        while (self::pow10($exponent) > $value) {
            $exponent--;
        }

        return $exponent;
    }

    /**
     * The decimals a standard value prints: two, or enough for two
     * significant digits below 1.
     */
    private static function valueDigits(int|float $magnitude): int
    {
        if (! ($magnitude > 0) || $magnitude >= 1) {
            return 2;
        }

        return min(self::MAX_DIGITS, max(2, 1 - self::exponentOf((float) $magnitude)));
    }

    /**
     * The compact unit for a magnitude (an index into the k, M, B ladder, or
     * -1 for none), stepped up when rounding reaches 1,000 of it.
     */
    private static function compactLevel(int|float $magnitude): int
    {
        $ladder = array_reverse(self::UNITS);
        $level  = -1;
        foreach ($ladder as $index => $unit) {
            if ($magnitude >= $unit[0]) {
                $level = $index;
            }
        }
        while ($level < count($ladder) - 1) {
            $scaled = $level < 0 ? $magnitude : $magnitude / $ladder[$level][0];
            $digits = $level < 0 ? self::valueDigits($magnitude) : 1;
            if ((float) self::toFixed($scaled, $digits) < 1000) {
                break;
            }
            $level++;
        }

        return $level;
    }

    /**
     * Rounds to the decimals, as `Number( value.toFixed( digits ) )` does.
     */
    private static function roundTo(int|float $value, int $digits): float|int
    {
        $rounded = (float) self::toFixed($value, $digits);
        if (0.0 === $rounded) {
            return 0;
        }

        return floor($rounded) === $rounded && abs($rounded) < 1e15 ? (int) $rounded : $rounded;
    }

    /**
     * Digits with thousands separators, up to `$digits` decimals.
     */
    private static function plain(int|float $value, int $digits, bool $trim): string
    {
        $text = self::toFixed($value, $digits);
        if ($trim && str_contains($text, '.')) {
            $text = (string) preg_replace('/\.?0+$/', '', $text);
        }
        $parts = explode('.', $text, 2);

        return preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $parts[0]).(isset($parts[1]) && '' !== $parts[1] ? '.'.$parts[1] : '');
    }

    /**
     * The k, M or B unit for a magnitude, or null.
     *
     * @return array{0: float, 1: string}|null
     */
    private static function unitFor(int|float $magnitude): ?array
    {
        foreach (self::UNITS as $unit) {
            if ($magnitude >= $unit[0]) {
                return $unit;
            }
        }

        return null;
    }

    /**
     * The sign, prefix, digits and suffix; no sign when the digits are zero.
     */
    private static function signed(int|float $value, string $digits, string $prefix, string $suffix): string
    {
        $sign = $value < 0 && 1 === preg_match('/[1-9]/', $digits) ? '-' : '';

        return $sign.$prefix.$digits.$suffix;
    }

    /**
     * A coordinate with fixed decimals, as JavaScript's `toFixed` writes it.
     */
    private static function fixed(int|float $value, int $digits = 1): string
    {
        $fixed = self::toFixed($value, $digits);

        return 1 === preg_match('/^-0\.0+$/', $fixed) ? substr($fixed, 1) : $fixed;
    }
}
