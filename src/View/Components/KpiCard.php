<?php

/**
 * KpiCard Component
 *
 * The design-system KpiCard (#61): a headline metric in a Card, with its
 * trend and progress toward a target.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      2.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * KpiCard Component Class
 *
 * Renders `.apui-card.apui-kpi-card`, built from Stat: the top row (label,
 * period, icon), the row (summary of value and foot, beside a Sparkline) and
 * the target (a Progress and its text). The `period` slot holds a control,
 * such as a Select. Every attribute lands on the root.
 *
 * 2.x props still work: `title`, `value`, `icon`, `change` and `change-label`
 * fill `stat`, and `sparkline-data` fills `sparkline`. The 2.x glass,
 * sparkline type and color props and the footer slot are gone.
 *
 * @since 2.0.0
 */
class KpiCard extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x props read from the attributes, then left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = [
        'title', 'value', 'icon', 'change', 'change-label', 'changeLabel',
        'sparkline-data', 'sparklineData', 'sparkline-type', 'sparklineType', 'sparkline-color', 'sparklineColor',
        'glass', 'glass-tint', 'glassTint', 'glass-tint-opacity', 'glassTintOpacity',
    ];

    /**
     * Create a new component instance.
     *
     * @since 2.0.0
     *
     * @param  array<string, mixed>  $stat  The metric, with Stat's props.
     * @param  array<int, float|int>  $sparkline  The recent values for the trend.
     * @param  string|null  $sparklineLabel  The sparkline's accessible label template.
     * @param  array{value?: float|int, max?: float|int, label?: string}  $target  Progress toward a goal.
     * @param  string|null  $targetLabel  The target row's end text template.
     * @param  string|null  $period  A period control. The `period` slot replaces it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $stat = [],
        public array $sparkline = [],
        public ?string $sparklineLabel = null,
        public array $target = [],
        public ?string $targetLabel = null,
        public ?string $period = null,
        public array $classes = [],
    ) {
        $this->sparklineLabel ??= __('Trend from {first} to {latest}');
        $this->targetLabel ??= __('{percent}% of goal');
    }

    /**
     * The metric with the 2.x props folded in, and its derived values.
     *
     * @since 3.0.0
     *
     * @return array<string, mixed>
     */
    public function metric(): array
    {
        $legacy = array_filter([
            'label'       => $this->attributes->get('title'),
            'value'       => $this->attributes->get('value'),
            'icon'        => $this->attributes->get('icon'),
            'change'      => $this->attributes->get('change'),
            'description' => $this->attributes->get('change-label') ?? $this->attributes->get('changeLabel'),
        ], static fn (mixed $value): bool => null !== $value && '' !== $value);
        $stat   = $this->stat + $legacy;
        $change = isset($stat['change']) && is_numeric($stat['change']) ? (float) $stat['change'] : null;
        $trend  = Stat::resolveTrend(is_string($stat['trend'] ?? null) ? $stat['trend'] : null, $change);
        $color  = in_array($stat['color'] ?? null, Stat::COLORS, true) ? $stat['color'] : 'neutral';

        return [
            'label'       => self::text($stat['label'] ?? null),
            'value'       => self::text($stat['value'] ?? null) ?? '',
            'unit'        => self::text($stat['unit'] ?? null),
            'icon'        => self::text($stat['icon'] ?? null),
            'description' => self::text($stat['description'] ?? null),
            'color'       => $color,
            'delta'       => Stat::formatDelta(self::text($stat['delta'] ?? null), $change),
            'trend'       => $trend,
            'trendIcon'   => Stat::trendIcon($trend),
            'trendLabel'  => match ($trend) {
                'up'    => self::text($stat['upLabel'] ?? null) ?? __('Increase'),
                'down'  => self::text($stat['downLabel'] ?? null) ?? __('Decrease'),
                'flat'  => self::text($stat['flatLabel'] ?? null) ?? __('No change'),
                default => null,
            },
        ];
    }

    /**
     * The sparkline's values, with the 2.x `sparkline-data` as a fallback.
     *
     * @since 3.0.0
     *
     * @return array<int, mixed>
     */
    public function sparklineData(): array
    {
        if ([] !== $this->sparkline) {
            return $this->sparkline;
        }

        $legacy = $this->attributes->get('sparkline-data') ?? $this->attributes->get('sparklineData');

        return is_array($legacy) ? $legacy : [];
    }

    /**
     * The target, or null without a numeric value: its value, max, percent,
     * label and end text.
     *
     * @since 3.0.0
     *
     * @return array{value: float, max: float, percent: int, label: string|null, text: string}|null
     */
    public function targetData(): ?array
    {
        if (! isset($this->target['value']) || ! is_numeric($this->target['value'])) {
            return null;
        }

        $value   = (float) $this->target['value'];
        $max     = isset($this->target['max']) && is_numeric($this->target['max']) && (float) $this->target['max'] > 0 ? (float) $this->target['max'] : 100.0;
        $percent = (int) round(max(0, min(1, $value / $max)) * 100);

        return [
            'value'   => $value,
            'max'     => $max,
            'percent' => $percent,
            'label'   => self::text($this->target['label'] ?? null),
            'text'    => strtr((string) $this->targetLabel, [
                '{percent}' => (string) $percent,
                '{value}'   => self::number($value),
                '{max}'     => self::number($max),
            ]),
        ];
    }

    /**
     * Get the view that represents the component.
     *
     * @since 2.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.kpi-card');
    }

    private static function text(mixed $value): ?string
    {
        return is_scalar($value) && '' !== (string) $value ? (string) $value : null;
    }

    private static function number(float $value): string
    {
        return floor($value) === $value ? (string) (int) $value : (string) $value;
    }
}
