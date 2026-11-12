<?php

/**
 * Sparkline geometry for the design-system Sparkline.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * The SVG coordinates of a sparkline (#61): a port of `sparklineGeometry`
 * and `sparklineLabel` in `@artisanpack-ui/behavior` (`src/data/sparkline.ts`),
 * so Blade draws the same marks as React and Vue. Keep the two in step.
 *
 * Values sit between 3px from the top and bottom edges and 2px from the
 * sides; the area closes to the bottom edge; bars fill 60% of their slot and
 * grow from zero when the values cross it. Coordinates have one decimal.
 *
 * @since 3.0.0
 */
final class SparklineGeometry
{
    private const PAD_X = 2;

    private const PAD_Y = 3;

    /**
     * The finite numbers among the values, in order. Numeric strings, such as
     * database decimals and aggregates ("12.50"), count as numbers.
     *
     * @since 3.0.0
     *
     * @return list<float|int>
     */
    public static function values(array $data): array
    {
        $numbers = array_map(
            static fn (mixed $value): mixed => is_string($value) && is_numeric($value) ? (float) $value : $value,
            array_values($data),
        );

        return array_values(array_filter(
            $numbers,
            static fn (mixed $value): bool => (is_int($value) || is_float($value)) && is_finite((float) $value),
        ));
    }

    /**
     * The marks for the data drawn as the variant in a width × height box.
     *
     * @since 3.0.0
     *
     * @return array{line: string, area: string, dot: array{cx: string, cy: string}|null, bars: list<array{x: string, y: string, width: string, height: string}>, baseline: string|null}
     */
    public static function compute(array $data, string $variant = 'line', int|float $width = 96, int|float $height = 28): array
    {
        $values = self::values($data);
        $empty  = ['line' => '', 'area' => '', 'dot' => null, 'bars' => [], 'baseline' => null];

        if ([] === $values) {
            return $empty;
        }

        $min   = min($values);
        $max   = max($values);
        $range = $max - $min;
        $toY   = static fn (int|float $value): float => 0 == $range
            ? $height / 2
            : self::PAD_Y + (($max - $value) / $range) * ($height - 2 * self::PAD_Y);
        $zero     = $min < 0 && $max > 0 ? $toY(0) : null;
        $baseline = null === $zero ? null : self::fixed($zero);
        $count    = count($values);

        if ('bar' === $variant) {
            $slot = ($width - 2 * self::PAD_X) / $count;
            $bars = [];
            foreach ($values as $index => $value) {
                $top    = $toY($value);
                $bottom = $zero ?? $height;
                $bars[] = [
                    'x'      => self::fixed(self::PAD_X + $index * $slot + $slot * 0.2),
                    'y'      => self::fixed(min($top, $bottom)),
                    'width'  => self::fixed($slot * 0.6),
                    'height' => self::fixed(max(abs($bottom - $top), 1)),
                ];
            }

            return ['bars' => $bars, 'baseline' => $baseline] + $empty;
        }

        $step   = $count > 1 ? ($width - 2 * self::PAD_X) / ($count - 1) : 0;
        $points = [];
        foreach ($values as $index => $value) {
            $points[] = [
                'x' => $count > 1 ? self::PAD_X + $index * $step : $width / 2,
                'y' => $toY($value),
            ];
        }

        $segments = [];
        foreach ($points as $index => $point) {
            $segments[] = (0 === $index ? 'M' : 'L').self::fixed($point['x']).','.self::fixed($point['y']);
        }
        $line  = implode(' ', $segments);
        $first = $points[0];
        $last  = $points[$count - 1];
        $area  = 'area' === $variant
            ? $line.' L'.self::fixed($last['x']).','.self::number($height).' L'.self::fixed($first['x']).','.self::number($height).' Z'
            : '';

        return [
            'line'     => $line,
            'area'     => $area,
            'dot'      => ['cx' => self::fixed($last['x']), 'cy' => self::fixed($last['y'])],
            'bars'     => [],
            'baseline' => $baseline,
        ];
    }

    /**
     * The accessible label: the template with `{first}`, `{latest}`, `{min}`,
     * `{max}` and `{count}` filled in.
     *
     * @since 3.0.0
     */
    public static function label(string $template, array $data): string
    {
        $values = self::values($data);
        $fill   = [
            '{first}'  => [] === $values ? '' : self::number($values[0]),
            '{latest}' => [] === $values ? '' : self::number($values[count($values) - 1]),
            '{min}'    => [] === $values ? '' : self::number(min($values)),
            '{max}'    => [] === $values ? '' : self::number(max($values)),
            '{count}'  => (string) count($values),
        ];

        return strtr($template, $fill);
    }

    /**
     * A coordinate with one decimal, as JavaScript's `toFixed(1)` writes it.
     *
     * @since 3.0.0
     */
    private static function fixed(int|float $value): string
    {
        $fixed = number_format((float) $value, 1, '.', '');

        return '-0.0' === $fixed ? '0.0' : $fixed;
    }

    /**
     * A number as JavaScript's `String()` writes it: no trailing `.0`.
     *
     * @since 3.0.0
     */
    private static function number(int|float $value): string
    {
        if (is_int($value) || floor($value) === $value && abs($value) < 1e15) {
            return (string) (int) $value;
        }

        return (string) $value;
    }
}
