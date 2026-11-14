<?php

/**
 * Stack Component
 *
 * The design-system Stack (#58): a token-driven layout primitive.
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
 * Stack Component Class
 *
 * Renders `.apui-stack`: its children in a column or a row (which runs in
 * the text direction), with the gap from the space scale, alignment and
 * optional wrapping. No Tailwind. Every attribute lands on the root.
 *
 * @since 3.0.0
 */
class Stack extends BaseComponent
{
    use RendersDesignSystemParts;

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
     * @param  string  $orientation  vertical or horizontal.
     * @param  int|string  $gap  A step of the space scale.
     * @param  string  $align  start, center, end, stretch or baseline (align-items).
     * @param  string  $justify  start, center, end, between, around or evenly (justify-content).
     * @param  bool  $wrap  Lets a row wrap onto more lines.
     */
    public function __construct(
        public string $orientation = 'vertical',
        public int|string $gap = '2',
        public string $align = 'stretch',
        public string $justify = 'start',
        public bool $wrap = false,
    ) {
        $this->orientation = 'horizontal' === $this->orientation ? 'horizontal' : 'vertical';
        $this->gap         = in_array((string) $this->gap, Grid::GAPS, true) ? (string) $this->gap : '2';
        $this->align       = in_array($this->align, ['start', 'center', 'end', 'stretch', 'baseline'], true) ? $this->align : 'stretch';
        $this->justify     = in_array($this->justify, ['start', 'center', 'end', 'between', 'around', 'evenly'], true) ? $this->justify : 'start';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.stack');
    }
}
