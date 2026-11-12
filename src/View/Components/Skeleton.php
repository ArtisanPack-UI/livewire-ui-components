<?php

/**
 * Skeleton Component
 *
 * The design-system Skeleton (#60): a placeholder shape that matches the
 * layout while content loads.
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

use Illuminate\Contracts\View\View;

/**
 * Skeleton Component Class
 *
 * Renders `.apui-skeleton`, hidden from assistive technology, with
 * data-shape and its width and height inline. The container that is loading
 * sets aria-busy.
 *
 * @since 3.0.0
 */
class Skeleton extends BaseComponent
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $shape  block, text or circle.
     * @param  string|null  $width  A CSS length.
     * @param  string|null  $height  A CSS length.
     */
    public function __construct(
        public string $shape = 'block',
        public ?string $width = null,
        public ?string $height = null,
    ) {}

    /**
     * The inline size declarations, merged with a `style` attribute.
     *
     * @since 3.0.0
     *
     * @param  string|null  $style  The `style` attribute.
     *
     * @return string The style, or an empty string.
     */
    public function sizeStyle(?string $style = null): string
    {
        $declarations = [];
        if (null !== $this->width && '' !== $this->width) {
            $declarations[] = 'width: '.$this->width;
        }
        if (null !== $this->height && '' !== $this->height) {
            $declarations[] = 'height: '.$this->height;
        }
        $style = trim((string) $style, " ;\t\n");
        if ('' !== $style) {
            $declarations[] = $style;
        }

        return implode('; ', $declarations);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.skeleton');
    }
}
