<?php

/**
 * Badge Component
 *
 * The design-system Badge (#61): a small, non-interactive label for a
 * status, category or count.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Badge Component Class
 *
 * Renders `.apui-badge` with data-variant, data-color and data-size and the
 * dot, icon and label parts. Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Badge extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants a badge accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const VARIANTS = ['solid', 'soft', 'outline', 'ghost'];

    /**
     * The semantic colors a badge accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    /**
     * The sizes a badge accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const SIZES = ['sm', 'md', 'lg'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  solid, soft, outline or ghost. Emitted as data-variant.
     * @param  string  $color  One of the eight semantic colors. Emitted as data-color.
     * @param  string  $size  sm, md or lg. Emitted as data-size.
     * @param  bool  $dot  A decorative dot before the label.
     * @param  string|null  $leadingIcon  Icon name before the label. An `icon` slot overrides it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $variant = 'soft',
        public string $color = 'neutral',
        public string $size = 'md',
        public bool $dot = false,
        public ?string $leadingIcon = null,
        public array $classes = [],
    ) {}

    /**
     * The variant, falling back to soft for unknown values.
     *
     * @since 3.0.0
     *
     * @return string The variant.
     */
    public function variantValue(): string
    {
        return in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'soft';
    }

    /**
     * The color, falling back to neutral for unknown values (2.x Tailwind
     * names and hex codes included).
     *
     * @since 3.0.0
     *
     * @return string The color.
     */
    public function colorValue(): string
    {
        return in_array($this->color, self::COLORS, true) ? $this->color : 'neutral';
    }

    /**
     * The size, falling back to md for unknown values.
     *
     * @since 3.0.0
     *
     * @return string The size.
     */
    public function sizeValue(): string
    {
        return in_array($this->size, self::SIZES, true) ? $this->size : 'md';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.badge');
    }
}
