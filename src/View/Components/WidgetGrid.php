<?php

declare(strict_types=1);
/**
 * WidgetGrid
 *
 * This file contains the WidgetGrid class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * WidgetGrid Class
 *
 * Provides a responsive grid helper for dashboard layouts.
 * Works with KPI cards, stats, and other widget components.
 *
 * @since 2.0.0
 */
class WidgetGrid extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public int|string $cols = 4,
        public int|string $gap = 6,
    ) {
        // Use a stable identifier: provided id or a unique id
        $this->uuid = 'artisanpack-widget-grid-'.($id ?? uniqid('', true));

        // Ensure cols and gap are integers
        $this->cols = (int) $this->cols;
        $this->gap = (int) $this->gap;
    }

    /**
     * Get the responsive grid column classes.
     *
     * Returns Tailwind CSS grid classes for responsive column layout.
     * Mobile starts at 1 column and scales up to the specified cols value.
     *
     * @since 2.0.0
     *
     * @return string Space-separated CSS classes.
     */
    public function gridColsClasses(): string
    {
        return match ($this->cols) {
            1 => 'grid-cols-1',
            2 => 'grid-cols-1 sm:grid-cols-2',
            3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
            4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
            5 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5',
            6 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
            default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        };
    }

    /**
     * Get the gap spacing class.
     *
     * Returns Tailwind CSS gap class for grid spacing.
     *
     * @since 2.0.0
     *
     * @return string The gap class.
     */
    public function gapClass(): string
    {
        return match ($this->gap) {
            0 => 'gap-0',
            1 => 'gap-1',
            2 => 'gap-2',
            3 => 'gap-3',
            4 => 'gap-4',
            5 => 'gap-5',
            6 => 'gap-6',
            8 => 'gap-8',
            10 => 'gap-10',
            12 => 'gap-12',
            default => 'gap-6',
        };
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.widget-grid');
    }
}
