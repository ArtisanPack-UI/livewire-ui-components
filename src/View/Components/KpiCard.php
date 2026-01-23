<?php

declare(strict_types=1);
/**
 * KpiCard
 *
 * This file contains the KpiCard class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * KpiCard Class
 *
 * Provides a dedicated KPI card component optimized for dashboards.
 * Combines stat, sparkline, and trend indicator functionality in one component.
 *
 * @since 2.0.0
 */
class KpiCard extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $value = null,
        public ?string $icon = null,

        // Trend indicator props
        public ?float $change = null,
        public ?string $changeLabel = null,

        // Sparkline props
        public ?array $sparklineData = null,
        public string $sparklineType = 'area',
        public ?string $sparklineColor = null,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,

        // Slots
        public mixed $footer = null,
    ) {
        // Use a stable identifier: provided id or a unique id
        $this->uuid = 'artisanpack-kpi-card-'.($id ?? uniqid('', true));
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
     * Combines custom tint color CSS variable with accessible text color
     * to ensure WCAG 2.0 AA compliance on tinted glass backgrounds.
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
     * Check if sparkline should be rendered.
     *
     * @since 2.0.0
     *
     * @return bool True if sparkline data is provided and not empty.
     */
    public function hasSparkline(): bool
    {
        return $this->sparklineData !== null && count($this->sparklineData) > 0;
    }

    /**
     * Check if trend indicator should be rendered.
     *
     * @since 2.0.0
     *
     * @return bool True if change value is provided.
     */
    public function hasChange(): bool
    {
        return $this->change !== null;
    }

    /**
     * Check if the change is positive.
     *
     * @since 2.0.0
     *
     * @return bool True if change is positive or zero.
     */
    public function isPositiveChange(): bool
    {
        return $this->change !== null && $this->change >= 0;
    }

    /**
     * Get the formatted change percentage string.
     *
     * Returns the change value with a sign prefix and percent symbol.
     *
     * @since 2.0.0
     *
     * @return string The formatted change string (e.g., "+12.5%" or "-8.3%").
     */
    public function formattedChange(): string
    {
        if ($this->change === null) {
            return '';
        }

        $sign = $this->change >= 0 ? '+' : '';

        return $sign.number_format($this->change, 1).'%';
    }

    /**
     * Get the CSS color classes for the trend indicator.
     *
     * Returns green classes for positive changes, red for negative.
     *
     * @since 2.0.0
     *
     * @return string Space-separated CSS classes.
     */
    public function changeColorClasses(): string
    {
        if ($this->change === null) {
            return '';
        }

        return $this->change >= 0
            ? 'text-success'
            : 'text-error';
    }

    /**
     * Get the icon name for the trend indicator arrow.
     *
     * Returns an up arrow for positive changes, down arrow for negative.
     *
     * @since 2.0.0
     *
     * @return string The icon name.
     */
    public function changeIcon(): string
    {
        if ($this->change === null) {
            return '';
        }

        return $this->change >= 0
            ? 'o-arrow-trending-up'
            : 'o-arrow-trending-down';
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.kpi-card');
    }
}
