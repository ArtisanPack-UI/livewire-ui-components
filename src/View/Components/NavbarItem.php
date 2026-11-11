<?php

/**
 * NavbarItem Component
 *
 * One primary link of the design-system Navbar (#56).
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
use Illuminate\Contracts\View\View;

/**
 * NavbarItem Component Class
 *
 * A child of `navbar`, in its default slot: an `<a>` with the link part,
 * aria-current="page" when it's the current page. The label is the slot,
 * or `label`. Every attribute lands on the link.
 *
 * @since 3.0.0
 */
class NavbarItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Navbar.
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
     * @param  string|null  $href  The link's URL.
     * @param  bool  $current  Whether it's the current page.
     * @param  string|null  $label  The label. The slot overrides it.
     * @param  bool  $navigate  Adds wire:navigate to an internal link (default true). Off-site, `#` and `mailto:` links never get it.
     */
    public function __construct(
        public ?string $href = null,
        public bool $current = false,
        public ?string $label = null,
        public bool $navigate = true,
    ) {
        $this->classes = Navbar::current()?->classes ?? [];
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.navbar-item');
    }
}
