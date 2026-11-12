<?php

/**
 * ItemList Component
 *
 * The design-system List (#58), the container of ListItem rows. The class is
 * ItemList because `List` is reserved in PHP; the tag is `list`.
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

use ArtisanPack\LivewireUiComponents\Support\NavigationContext;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * ItemList Component Class
 *
 * Renders `.apui-list`, a ul (or ol) with data-variant. list-item children
 * render as li while it renders them.
 *
 * @since 3.0.0
 */
class ItemList extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants a list accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const VARIANTS = ['plain', 'bordered', 'divided'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  plain, bordered (a framed surface) or divided (rules between rows). Emitted as data-variant.
     * @param  bool  $ordered  Renders an ol instead of a ul.
     * @param  array<string, string>  $classes  Extra classes: `root`.
     */
    public function __construct(
        public string $variant = 'plain',
        public bool $ordered = false,
        public array $classes = [],
    ) {
        app(NavigationContext::class)->enter($this);
    }

    /**
     * The list whose rows are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The list.
     */
    public static function current(): ?self
    {
        return app(NavigationContext::class)->nearest(self::class);
    }

    /**
     * Leaves the context once the view has rendered the rows.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(NavigationContext::class)->leave($this);
    }

    /**
     * The variant, falling back to plain.
     *
     * @since 3.0.0
     *
     * @return string The variant.
     */
    public function variantValue(): string
    {
        return in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'plain';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.list');
    }
}
