<?php

/**
 * MenuSeparator Component
 *
 * A rule between items of the design-system navigation Menu (#56).
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
 * MenuSeparator Component Class
 *
 * A child of `menu`: an `<li>` with the separator part, hidden from assistive technology. With the deprecated `title`, a title follows it.
 *
 * @since 1.0.0
 */
class MenuSeparator extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Menu.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $title  Deprecated: use a menu-title after it.
     */
    public function __construct(
        public ?string $title = null,
    ) {
        $this->classes = Menu::current()?->classes ?? [];
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.menu-separator');
    }
}
