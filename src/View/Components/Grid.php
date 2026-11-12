<?php

/**
 * Grid Component
 *
 * The design-system Grid (#58): a token-driven layout primitive.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * Grid Component Class
 *
 * Renders `.apui-grid`: its children in `columns` equal columns, with the
 * gap from the space scale and `align`/`justify` for the items. With
 * `minItemWidth` the grid shows fewer columns rather than narrower ones.
 * No Tailwind. Every attribute lands on the root.
 *
 * @since 3.0.0
 */
class Grid extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The gap steps, from the space scale.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const GAPS = ['0', '1', '2', '3', '4', '5', '6', '8', '10', '12', '16'];

    /**
     * Extra classes per part. A layout primitive has only its root, so
     * this stays empty: `class` styles it.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  int|string  $columns  The number of columns, 1 to 12.
     * @param  string|null  $minItemWidth  The narrowest a column may get, any CSS length.
     * @param  int|string  $gap  A step of the space scale.
     * @param  string  $align  start, center, end or stretch (align-items).
     * @param  string  $justify  start, center, end or stretch (justify-items).
     */
    public function __construct(
        public int|string $columns = 1,
        public ?string $minItemWidth = null,
        public int|string $gap = '4',
        public string $align = 'stretch',
        public string $justify = 'stretch',
    ) {
        $this->columns = max(1, min(12, (int) $this->columns));
        $this->gap     = in_array((string) $this->gap, self::GAPS, true) ? (string) $this->gap : '4';
        $this->align   = in_array($this->align, ['start', 'center', 'end', 'stretch'], true) ? $this->align : 'stretch';
        $this->justify = in_array($this->justify, ['start', 'center', 'end', 'stretch'], true) ? $this->justify : 'stretch';
    }

    /**
     * The root's inline style: the minimum item width, when set.
     *
     * @since 3.0.0
     *
     * @return string|null The style, or null.
     */
    public function rootStyle(): ?string
    {
        $width = null !== $this->minItemWidth ? trim((string) preg_replace('/[;{}<>"]/', '', $this->minItemWidth)) : '';

        return '' === $width ? null : '--apui-grid-min-item-width: '.$width;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.grid');
    }
}
