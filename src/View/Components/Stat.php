<?php

/**
 * Stat Component
 *
 * The design-system Stat (#61): a headline number with its label, change
 * and context.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Stat Component Class
 *
 * Renders `.apui-stat` with the top (label, icon), value (unit), foot
 * (delta, description) parts. The delta is hidden direction text, an arrow
 * and the change, so color is never the only signal. The default slot
 * replaces `value` and the `icon` slot the named icon. Every attribute lands
 * on the root.
 *
 * 2.x props still work: `title` as `label`, and `change-label` as
 * `description`. The 2.x tooltip, layout, glass, sparkline and animation
 * props are gone: use Tooltip, Grid, Card's surface and KpiCard.
 *
 * @since 1.0.0
 */
class Stat extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The sizes, colors and trends the markup contract knows.
     *
     * @since 3.0.0
     */
    public const SIZES = ['sm', 'md', 'lg'];

    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    public const TRENDS = ['up', 'down', 'flat'];

    /**
     * The 2.x props read from the attributes, then left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = ['title', 'change-label', 'changeLabel'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $label  What the number measures.
     * @param  string|null  $value  The formatted number. The default slot replaces it.
     * @param  string|null  $unit  A unit after the value.
     * @param  string|null  $icon  A registry icon name for the tile. The `icon` slot replaces it.
     * @param  string|null  $delta  The change as text; takes precedence over `change`.
     * @param  float|null  $change  The change as a percentage; sets the trend from its sign.
     * @param  string|null  $trend  up, down or flat.
     * @param  string|null  $description  Context after the delta.
     * @param  string  $size  sm, md or lg.
     * @param  string  $color  Tints the icon tile and the emphasis fill.
     * @param  bool  $emphasis  A soft fill in `color`.
     * @param  bool  $bare  No container.
     * @param  string|null  $upLabel  Hidden text before an up delta.
     * @param  string|null  $downLabel  Hidden text before a down delta.
     * @param  string|null  $flatLabel  Hidden text before a flat delta.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $label = null,
        public ?string $value = null,
        public ?string $unit = null,
        public ?string $icon = null,
        public ?string $delta = null,
        public ?float $change = null,
        public ?string $trend = null,
        public ?string $description = null,
        public string $size = 'md',
        public string $color = 'neutral',
        public bool $emphasis = false,
        public bool $bare = false,
        public ?string $upLabel = null,
        public ?string $downLabel = null,
        public ?string $flatLabel = null,
        public array $classes = [],
    ) {
        $this->upLabel ??= __('Increase');
        $this->downLabel ??= __('Decrease');
        $this->flatLabel ??= __('No change');
    }

    /**
     * The size, falling back to `md`.
     *
     * @since 3.0.0
     */
    public function sizeValue(): string
    {
        return in_array($this->size, self::SIZES, true) ? $this->size : 'md';
    }

    /**
     * The color, falling back to `neutral`.
     *
     * @since 3.0.0
     */
    public function colorValue(): string
    {
        return in_array($this->color, self::COLORS, true) ? $this->color : 'neutral';
    }

    /**
     * The delta's text: `delta`, or `change` as a percentage with one decimal.
     *
     * @since 3.0.0
     */
    public function deltaText(): ?string
    {
        return self::formatDelta($this->delta, $this->change);
    }

    /**
     * The trend: `trend`, or the sign of `change`.
     *
     * @since 3.0.0
     */
    public function trendValue(): ?string
    {
        return self::resolveTrend($this->trend, $this->change);
    }

    /**
     * The hidden text before the delta, for its trend.
     *
     * @since 3.0.0
     */
    public function trendLabel(): ?string
    {
        return match ($this->trendValue()) {
            'up'    => $this->upLabel,
            'down'  => $this->downLabel,
            'flat'  => $this->flatLabel,
            default => null,
        };
    }

    /**
     * A delta's text from `delta` or `change` (shared with KpiCard).
     *
     * @since 3.0.0
     */
    public static function formatDelta(?string $delta, int|float|null $change): ?string
    {
        if (null !== $delta && '' !== $delta) {
            return $delta;
        }

        return null === $change ? null : number_format(abs((float) $change), 1, '.', '').'%';
    }

    /**
     * A trend from `trend` or the sign of `change` (shared with KpiCard).
     *
     * @since 3.0.0
     */
    public static function resolveTrend(?string $trend, int|float|null $change): ?string
    {
        if (in_array($trend, self::TRENDS, true)) {
            return $trend;
        }

        if (null === $change) {
            return null;
        }

        return $change > 0 ? 'up' : ($change < 0 ? 'down' : 'flat');
    }

    /**
     * The arrow for a trend: it shows the direction, not only the color.
     *
     * @since 3.0.0
     */
    public static function trendIcon(?string $trend): ?string
    {
        return match ($trend) {
            'up'    => 'arrow-up-right',
            'down'  => 'arrow-down-right',
            'flat'  => 'minus',
            default => null,
        };
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.stat');
    }
}
