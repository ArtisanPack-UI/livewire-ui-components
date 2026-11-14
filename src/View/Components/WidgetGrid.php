<?php

/**
 * Widget Grid Component
 *
 * The 2.x `widget-grid`, now a deprecated alias of the design-system Grid
 * (#58).
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

/**
 * WidgetGrid Class
 *
 * Renders the design-system Grid from the 2.x `widget-grid` API: `cols`
 * (default 4) is `columns`, `gap` keeps its 2.x default of 6, and the grid
 * reflows by `minItemWidth` (default 14rem) instead of Tailwind
 * breakpoints. An `id` still lands on the root.
 *
 * @since 2.0.0
 * @deprecated 3.0.0 Use `<x-artisanpack-grid>`. Removed in a later major release.
 */
class WidgetGrid extends Grid
{
    /**
     * Creates the component.
     *
     * @since 2.0.0
     *
     * @param  string|null  $id  The root's id.
     * @param  int|string  $cols  The most columns, 1 to 12.
     * @param  int|string  $gap  A step of the space scale.
     * @param  string|null  $minItemWidth  The narrowest a column may get.
     */
    public function __construct(
        public ?string $id = null,
        public int|string $cols = 4,
        int|string $gap = 6,
        ?string $minItemWidth = '14rem',
    ) {
        parent::__construct(columns: $cols, minItemWidth: $minItemWidth, gap: $gap);
    }
}
