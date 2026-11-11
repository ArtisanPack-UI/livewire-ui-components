<?php

/**
 * DropdownMenuSeparator Component
 *
 * A rule between items in the design-system DropdownMenu (#50).
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
 * DropdownMenuSeparator Component Class
 *
 * Renders the `separator` part, an `<hr>` with a stable id: the `id`
 * attribute, else the menu's id and the separator's place in it.
 *
 * @since 3.0.0
 */
class DropdownMenuSeparator extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part: the menu's.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The separator's id, resolved once.
     *
     * @since 3.0.0
     */
    private ?string $resolvedId = null;

    /**
     * The menu this separator renders in, if any.
     *
     * @since 3.0.0
     */
    private ?DropdownMenu $menu;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        $this->menu    = DropdownMenu::current();
        $this->classes = $this->menu?->classes ?? [];
    }

    /**
     * The separator's id.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function separatorId(): string
    {
        if (null !== $this->resolvedId) {
            return $this->resolvedId;
        }

        $id = $this->attributes->get('id');
        if (is_string($id) && '' !== $id) {
            return $this->resolvedId = $id;
        }

        return $this->resolvedId = $this->menu?->nextChildId('separator') ?? 'apui-dropdown-menu-separator';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.dropdown-menu-separator');
    }
}
