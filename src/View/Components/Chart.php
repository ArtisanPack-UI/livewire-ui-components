<?php

declare(strict_types=1);
/**
 * Chart
 *
 * This file contains the Chart class for the ArtisanPack UI Livewire UI Components package.
 * Uses ApexCharts for rendering interactive charts with glass effect support.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\ChartThemes;
use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Chart Class
 *
 * Provides functionality for the Chart component using ApexCharts.
 * Supports all major chart types: area, bar, line, donut, radialBar, heatmap.
 *
 * @since 1.0.0
 * @since 2.0.0 Switched to ApexCharts, added glass effect support
 */
class Chart extends Component
{
    public string $uuid;

    /**
     * Valid chart types supported by ApexCharts.
     *
     * @var array<string>
     */
    protected static array $validTypes = [
        'area',
        'bar',
        'line',
        'donut',
        'pie',
        'radialBar',
        'heatmap',
        'scatter',
        'bubble',
        'candlestick',
        'boxPlot',
        'radar',
        'polarArea',
        'rangeBar',
        'rangeArea',
        'treemap',
    ];

    public function __construct(
        public ?string $id = null,

        // ApexCharts props
        public ?array $options = null,
        public ?array $series = null,
        public string $type = 'line',
        public string|int $height = 350,
        public string|int|null $width = null,

        // Theme prop
        public ?string $theme = null,

        // Animation props for real-time updates
        public bool $animated = true,
        public int $animationSpeed = 800,
        public string $animationEasing = 'easeinout',
        public bool $dynamicAnimation = true,
        public int $dynamicAnimationSpeed = 350,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,
    ) {
        // Use a stable identifier: provided id (as-is) or a prefixed unique id
        // When an explicit id is provided, use it directly to allow event matching
        // Avoid serialize($this) as it includes mutable state (options, series)
        $this->uuid = $id ?? 'artisanpack-chart-'.uniqid('', true);
    }

    /**
     * Get the validated chart type.
     *
     * @since 2.0.0
     *
     * @return string The validated chart type.
     */
    public function chartType(): string
    {
        if (in_array($this->type, self::$validTypes, true)) {
            return $this->type;
        }

        return 'line';
    }

    /**
     * Get the chart height as a string.
     *
     * @since 2.0.0
     *
     * @return string The height value (e.g., "350" or "100%").
     */
    public function chartHeight(): string
    {
        if (is_int($this->height)) {
            return (string) $this->height;
        }

        return $this->height;
    }

    /**
     * Get the chart width as a string.
     *
     * @since 2.0.0
     *
     * @return string|null The width value or null for auto.
     */
    public function chartWidth(): ?string
    {
        if (null === $this->width) {
            return null;
        }

        if (is_int($this->width)) {
            return (string) $this->width;
        }

        return $this->width;
    }

    /**
     * Get the animation configuration for smooth real-time updates.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> Animation configuration array.
     */
    public function animationConfig(): array
    {
        return [
            'enabled'          => $this->animated,
            'speed'            => $this->animationSpeed,
            'easing'           => $this->animationEasing,
            'dynamicAnimation' => [
                'enabled' => $this->dynamicAnimation,
                'speed'   => $this->dynamicAnimationSpeed,
            ],
        ];
    }

    /**
     * Get the merged ApexCharts options.
     *
     * Merges user-provided options with required base options.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The merged options array.
     */
    public function chartOptions(): array
    {
        $baseOptions = [
            'chart' => [
                'type'       => $this->chartType(),
                'height'     => $this->chartHeight(),
                'background' => 'transparent',
                'animations' => $this->animationConfig(),
            ],
        ];

        // Add width if specified
        if (null !== $this->chartWidth()) {
            $baseOptions['chart']['width'] = $this->chartWidth();
        }

        // Merge with user options (user options take precedence for nested values)
        if (null !== $this->options) {
            return $this->mergeOptions($baseOptions, $this->options);
        }

        return $baseOptions;
    }

    /**
     * Get the chart series data.
     *
     * @since 2.0.0
     *
     * @return array<mixed> The series data.
     */
    public function chartSeries(): array
    {
        return $this->series ?? [];
    }

    /**
     * Get the validated theme name.
     *
     * @since 2.0.0
     *
     * @return string|null The validated theme name or null.
     */
    public function chartTheme(): ?string
    {
        if (ChartThemes::isValidTheme($this->theme)) {
            return $this->theme;
        }

        return null;
    }

    /**
     * Get the theme configuration.
     *
     * Returns the full theme configuration array for the specified theme.
     * If no theme is specified, returns an empty array.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The theme configuration.
     */
    public function themeConfig(): array
    {
        $themeName = $this->chartTheme();

        if (null === $themeName) {
            return [];
        }

        return ChartThemes::get($themeName);
    }

    /**
     * Check if the chart is using the glass theme.
     *
     * @since 2.0.0
     *
     * @return bool True if using glass theme.
     */
    public function isGlassTheme(): bool
    {
        return 'artisanpack-glass' === $this->chartTheme();
    }

    /**
     * Get glass theme light mode overrides.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> Light mode overrides for glass theme.
     */
    public function glassLightOverrides(): array
    {
        return ChartThemes::getGlassLightOverrides();
    }

    /**
     * Get glass theme dark mode overrides.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> Dark mode overrides for glass theme.
     */
    public function glassDarkOverrides(): array
    {
        return ChartThemes::getGlassDarkOverrides();
    }

    /**
     * Get the glass effect CSS classes.
     *
     * @since 2.0.0
     *
     * @return string Space-separated CSS classes.
     */
    public function glassClasses(): string
    {
        return GlassHelper::getClasses($this->glass, $this->glassTint, $this->glassTintOpacity);
    }

    /**
     * Get the glass effect inline styles including accessible text color.
     *
     * @since 2.0.0
     *
     * @return string Inline style string.
     */
    public function glassStyle(): string
    {
        return GlassHelper::getFullInlineStyle($this->glassTint);
    }

    /**
     * Get all valid chart types.
     *
     * @since 2.0.0
     *
     * @return array<string> Array of valid chart types.
     */
    public static function getValidTypes(): array
    {
        return self::$validTypes;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.chart');
    }

    /**
     * Recursively merge options arrays.
     *
     * @since 2.0.0
     *
     * @param  array<string, mixed>  $base  Base options.
     * @param  array<string, mixed>  $override  Override options.
     *
     * @return array<string, mixed> Merged options.
     */
    protected function mergeOptions(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = $this->mergeOptions($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }

        return $base;
    }
}
