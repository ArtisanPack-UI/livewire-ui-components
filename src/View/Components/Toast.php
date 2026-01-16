<?php

declare(strict_types=1);
/**
 * Toast
 *
 * This file contains the Toast class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Toast Class
 *
 * Provides functionality for the Toast component.
 *
 * @since 1.0.0
 */
class Toast extends Component
{
    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string  $position  The position of the toast. Default: 'toast-top toast-end'.
     * @param  string|null  $color  The color of the toast.
     * @param  string|null  $colorAdjustment  The color adjustment (e.g., 'darken', 'lighten').
     * @param  int  $duration  The default duration in milliseconds. Default: 3000.
     * @param  string|null  $glass  Glass effect variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name or hex code for glass tint.
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     */
    public function __construct(
        public string $position = 'toast-top toast-end',
        public ?string $color = null,
        public ?string $colorAdjustment = null,
        public int $duration = 3000,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,
    ) {}

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @since 1.0.0
     */
    public function getColorClasses(): array
    {
        if (! $this->color) {
            return [];
        }

        $colorGenerator = new ColorGenerator;

        // Use ColorGenerator for color resolution
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->color,
            $this->colorAdjustment,
            'toast',
        );

        return $colorClasses;
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
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.toast');
    }
}
