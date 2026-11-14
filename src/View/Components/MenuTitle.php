<?php

/**
 * MenuTitle Component
 *
 * A section title between items of the design-system navigation Menu (#56).
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
 * MenuTitle Component Class
 *
 * A child of `menu`: an `<li>` with the title part, from the slot or `label`. Every attribute lands on it.
 *
 * @since 1.0.0
 */
class MenuTitle extends BaseComponent
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
     * @param  string|null  $label  The title's text. The slot overrides it.
     * @param  string|null  $title  Deprecated: use `label` or the slot.
     */
    public function __construct(
        public ?string $label = null,
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
        return view('livewire-ui-components::components.menu-title');
    }
}
