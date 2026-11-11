<?php

/**
 * BreadcrumbsContext
 *
 * Holds the Breadcrumbs whose composed children are rendering.
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

use ArtisanPack\LivewireUiComponents\View\Components\Breadcrumbs;

/**
 * BreadcrumbsContext Class
 *
 * A scoped (per-request, Octane-safe) stack of the Breadcrumbs being
 * rendered, so a `breadcrumbs-item` or `link` child registers its level with
 * the trail instead of rendering on its own.
 *
 * @since 3.0.0
 */
final class BreadcrumbsContext
{
    /**
     * The Breadcrumbs being rendered, innermost last.
     *
     * @since 3.0.0
     *
     * @var list<Breadcrumbs>
     */
    private array $stack = [];

    /**
     * Enters a Breadcrumbs: its children render next.
     *
     * @since 3.0.0
     *
     * @param  Breadcrumbs  $breadcrumbs  The Breadcrumbs.
     */
    public function enter(Breadcrumbs $breadcrumbs): void
    {
        $this->stack[] = $breadcrumbs;
    }

    /**
     * Leaves a Breadcrumbs once its view has collected the children.
     *
     * @since 3.0.0
     *
     * @param  Breadcrumbs  $breadcrumbs  The Breadcrumbs.
     */
    public function leave(Breadcrumbs $breadcrumbs): void
    {
        $index = array_search($breadcrumbs, $this->stack, true);
        if (false !== $index) {
            array_splice($this->stack, $index, 1);
        }
    }

    /**
     * The Breadcrumbs whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return Breadcrumbs|null The Breadcrumbs.
     */
    public function current(): ?Breadcrumbs
    {
        return [] === $this->stack ? null : $this->stack[array_key_last($this->stack)];
    }
}
