<?php

/**
 * Chart Component
 *
 * The design-system Chart (#63): a bar, line, area or donut chart in a Card,
 * drawn as inline SVG.
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

use ArtisanPack\LivewireUiComponents\Support\ChartGeometry;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Chart Component Class
 *
 * Renders `.apui-card.apui-chart` with data-type: the header (title,
 * description, the legend for two or more series, actions), the plot (an
 * SVG computed on the server by ChartGeometry, the same geometry React and
 * Vue draw, with no chart library), the tooltip, and the data table when
 * `tabular` is set. `apuiChart` from the behavior package opens the tooltip
 * on hover and keyboard focus of each mark; the marks are rendered here, so
 * `wire:poll` and Livewire updates redraw the chart. Every attribute lands on
 * the root.
 *
 * ApexCharts is gone (D-038). The 2.x `series` and `labels` keep their shape,
 * and `options.xaxis.categories`, `options.labels` and `options.title.text`
 * still fill labels and title when those aren't set. The other 2.x props
 * (options, theme, the animation and glass props) are read from the
 * attributes and dropped; `show-table` still works, as `tabular`.
 *
 * @since 1.0.0
 * @since 3.0.0 Rebuilt on the design system: own SVG, no ApexCharts.
 */
class Chart extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x props read from the attributes, then left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = [
        'options',
        'theme',
        'animated',
        'animation-speed',
        'animationSpeed',
        'animation-easing',
        'animationEasing',
        'dynamic-animation',
        'dynamicAnimation',
        'dynamic-animation-speed',
        'dynamicAnimationSpeed',
        'glass',
        'glass-tint',
        'glassTint',
        'glass-tint-opacity',
        'glassTintOpacity',
        'show-table',
        'showTable',
    ];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string  $type  bar, line, area or donut.
     * @param  array<int, mixed>|null  $series  [ [ 'name' => …, 'data' => [ … ] ] ], or a plain array of values. Each `data` entry is a value or an ApexCharts-style point (`[ 'x' => …, 'y' => … ]` or `[ x, y ]`). Null draws an empty chart.
     * @param  array<int, mixed>|null  $labels  The categories: x-axis labels or donut slices. Missing, the points' `x` fill them.
     * @param  string|null  $title  The heading, which names the plot and the table.
     * @param  string|null  $description  Supporting text under the title.
     * @param  string|null  $actions  Header controls. The `actions` slot replaces it.
     * @param  bool  $tabular  Shows the data table under the plot.
     * @param  string|null  $prefix  Printed before every value.
     * @param  string|null  $suffix  Printed after every value.
     * @param  string  $notation  standard or compact.
     * @param  float|int|string|null  $width  The plot's viewBox width.
     * @param  float|int|string|null  $height  The plot's viewBox height.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $type = 'bar',
        public ?array $series = [],
        public ?array $labels = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $actions = null,
        public bool $tabular = false,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public string $notation = 'standard',
        public int|float|string|null $width = null,
        public int|float|string|null $height = null,
        public array $classes = [],
    ) {}

    /**
     * The type: 2.x `pie` draws a donut, anything else unknown a bar.
     *
     * @since 3.0.0
     */
    public function typeValue(): string
    {
        if ('pie' === $this->type) {
            return 'donut';
        }

        return in_array($this->type, ChartGeometry::TYPES, true) ? $this->type : 'bar';
    }

    /**
     * The title, with the 2.x `options.title.text` as a fallback.
     *
     * @since 3.0.0
     */
    public function titleText(): string
    {
        if (null !== $this->title && '' !== $this->title) {
            return $this->title;
        }

        $text = $this->legacyOptions()['title']['text'] ?? null;

        return is_scalar($text) ? (string) $text : '';
    }

    /**
     * Whether the data table shows: `tabular`, or the spec's and 2.x's
     * `show-table`.
     *
     * @since 3.0.0
     */
    public function showsTable(): bool
    {
        if ($this->tabular) {
            return true;
        }

        $legacy = $this->attributes->get('show-table', $this->attributes->get('showTable'));

        return true === $legacy || 'true' === $legacy || '' === $legacy || 'show-table' === $legacy || 'showTable' === $legacy;
    }

    /**
     * Everything the chart draws.
     *
     * @since 3.0.0
     *
     * @return array<string, mixed>
     */
    public function geometry(): array
    {
        $options = $this->legacyOptions();
        $labels  = $this->labels ?? [];
        foreach ([$options['xaxis']['categories'] ?? null, $options['labels'] ?? null] as $legacy) {
            if ([] === $labels && is_array($legacy)) {
                $labels = $legacy;
            }
        }

        return ChartGeometry::compute($this->series ?? [], [
            'type'     => $this->typeValue(),
            'labels'   => $labels,
            'width'    => is_numeric($this->width) ? $this->width + 0 : null,
            'height'   => is_numeric($this->height) ? $this->height + 0 : null,
            'title'    => $this->titleText(),
            'prefix'   => $this->prefix,
            'suffix'   => $this->suffix,
            'notation' => 'compact' === $this->notation ? 'compact' : 'standard',
        ]);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.chart');
    }

    /**
     * The 2.x ApexCharts `options`, when an app still passes them.
     *
     * @return array<string, mixed>
     */
    private function legacyOptions(): array
    {
        $options = $this->attributes?->get('options');

        return is_array($options) ? $options : [];
    }
}
