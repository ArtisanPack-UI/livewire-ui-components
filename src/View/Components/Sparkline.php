<?php

/**
 * Sparkline Component
 *
 * The design-system Sparkline (#61): a word-sized trend drawn as inline SVG.
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

use ArtisanPack\LivewireUiComponents\Support\SparklineGeometry;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Sparkline Component Class
 *
 * Renders `svg.apui-sparkline` (role=img) with the area, line, dot, bar and
 * baseline parts, computed on the server by SparklineGeometry: the same
 * coordinates React and Vue draw, with no chart library and no script, so
 * Livewire updates and `wire:poll` redraw it. Every attribute lands on the
 * root.
 *
 * The 2.x `type` still works, as `variant`. The 2.x color, stroke, curve and
 * fill props are gone: the line takes `--apui-chart-1`.
 *
 * @since 2.0.0
 */
class Sparkline extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants the markup contract knows.
     *
     * @since 3.0.0
     */
    public const VARIANTS = ['line', 'area', 'bar'];

    /**
     * The 2.x props read from the attributes, then left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = ['type', 'color', 'stroke-width', 'strokeWidth', 'curve', 'fill-opacity', 'fillOpacity'];

    /**
     * Create a new component instance.
     *
     * @since 2.0.0
     *
     * @param  array<int, mixed>  $data  The values, oldest first.
     * @param  string  $variant  line, area or bar.
     * @param  float|int  $width  The width in px.
     * @param  float|int  $height  The height in px.
     * @param  string|null  $label  The accessible label template.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $data = [],
        public string $variant = 'line',
        public int|float $width = 96,
        public int|float $height = 28,
        public ?string $label = null,
        public array $classes = [],
    ) {
        $this->label ??= __('Trend from {first} to {latest}');
    }

    /**
     * The variant, with the 2.x `type` as a fallback, then `line`.
     *
     * @since 3.0.0
     */
    public function variantValue(): string
    {
        $type = $this->attributes->get('type');
        if ('line' === $this->variant && is_string($type) && in_array($type, self::VARIANTS, true)) {
            return $type;
        }

        return in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'line';
    }

    /**
     * The marks to draw.
     *
     * @since 3.0.0
     *
     * @return array{line: string, area: string, dot: array{cx: string, cy: string}|null, bars: list<array{x: string, y: string, width: string, height: string}>, baseline: string|null}
     */
    public function geometry(): array
    {
        return SparklineGeometry::compute($this->data, $this->variantValue(), $this->width, $this->height);
    }

    /**
     * The accessible label, filled in from the data.
     *
     * @since 3.0.0
     */
    public function accessibleLabel(): string
    {
        return SparklineGeometry::label((string) $this->label, $this->data);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 2.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.sparkline');
    }
}
