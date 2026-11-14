<?php

/**
 * DropdownMenuGroup Component
 *
 * A labelled group of items in the design-system DropdownMenu (#50).
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
 * DropdownMenuGroup Component Class
 *
 * Renders an `item-group` part labelled by its `item-group-label`. Both keep
 * a stable, server-rendered id, which the adapter hands to the machine
 * (D-015): the `id` attribute, else the menu's id and the group's place in it.
 *
 * @since 3.0.0
 */
class DropdownMenuGroup extends BaseComponent
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
     * The group's id, resolved once.
     *
     * @since 3.0.0
     */
    private ?string $resolvedId = null;

    /**
     * The menu this group renders in, if any.
     *
     * @since 3.0.0
     */
    private ?DropdownMenu $menu;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $label  The group's label. A `label` slot overrides it.
     */
    public function __construct(
        public ?string $label = null,
    ) {
        $this->menu    = DropdownMenu::current();
        $this->classes = $this->menu?->classes ?? [];
    }

    /**
     * The group's id, which is also the key the machine knows it by.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function groupId(): string
    {
        if (null !== $this->resolvedId) {
            return $this->resolvedId;
        }

        $id = $this->attributes->get('id');
        if (is_string($id) && '' !== $id) {
            return $this->resolvedId = $id;
        }

        return $this->resolvedId = $this->menu?->nextChildId('group')
            ?? 'apui-dropdown-menu-group-'.substr(md5((string) $this->label), 0, 12);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.dropdown-menu-group');
    }
}
