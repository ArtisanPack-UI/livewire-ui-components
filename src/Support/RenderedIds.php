<?php

/**
 * RenderedIds
 *
 * Keeps the ids the design-system overlays render unique within a render.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use ArtisanPack\LivewireUiComponents\View\Components\DropdownMenu;

/**
 * RenderedIds Class
 *
 * A scoped (per-request, Octane-safe) record of the ids rendered so far,
 * per Livewire component: the first claim of an id keeps it, repeats get
 * `-2`, `-3` in render order. A Livewire component's scope resets when it
 * starts rendering, so the same component renders the same ids on every
 * re-render. It also holds the DropdownMenu being rendered, for its children.
 *
 * @since 3.0.0
 */
final class RenderedIds
{
    /**
     * How many times each id was claimed, per scope.
     *
     * @since 3.0.0
     *
     * @var array<string, array<string, int>>
     */
    private array $claimed = [];

    /**
     * The menus being rendered, innermost last.
     *
     * @since 3.0.0
     *
     * @var list<DropdownMenu>
     */
    private array $menus = [];

    /**
     * Claims an id in a scope: the id itself the first time, then `-2`, `-3`.
     *
     * @since 3.0.0
     *
     * @param  string  $scope  The Livewire component id, or '' outside one.
     * @param  string  $id  The id.
     *
     * @return string The unique id.
     */
    public function claim(string $scope, string $id): string
    {
        $count                       = ($this->claimed[$scope][$id] ?? 0) + 1;
        $this->claimed[$scope][$id]  = $count;

        return 1 === $count ? $id : $id.'-'.$count;
    }

    /**
     * Forgets a scope's ids, when its Livewire component starts rendering.
     *
     * @since 3.0.0
     *
     * @param  string  $scope  The Livewire component id.
     */
    public function reset(string $scope): void
    {
        unset($this->claimed[$scope]);
    }

    /**
     * Enters a menu: its children render next.
     *
     * @since 3.0.0
     *
     * @param  DropdownMenu  $menu  The menu.
     */
    public function enterMenu(DropdownMenu $menu): void
    {
        $this->menus[] = $menu;
    }

    /**
     * Leaves a menu once its view has rendered.
     *
     * @since 3.0.0
     *
     * @param  DropdownMenu  $menu  The menu.
     */
    public function leaveMenu(DropdownMenu $menu): void
    {
        $index = array_search($menu, $this->menus, true);
        if (false !== $index) {
            array_splice($this->menus, $index, 1);
        }
    }

    /**
     * The menu whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return DropdownMenu|null The menu.
     */
    public function currentMenu(): ?DropdownMenu
    {
        return [] === $this->menus ? null : $this->menus[array_key_last($this->menus)];
    }
}
