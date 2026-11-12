<?php

/**
 * Main Component
 *
 * The 2.x `main` frame, now a deprecated alias of the design-system
 * AppShell (#58).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\View\View;

/**
 * Main Class
 *
 * Renders `<x-artisanpack-app-shell>` from the 2.x `main` API: the
 * `content` slot is the content, the `sidebar` slot the sidebar (its
 * `collapsible`, `collapse-text` attributes still work), and the 2.x
 * `footer` slot, a page footer, renders after the content in the main
 * column. `fullWidth`, `withNav`, `collapseIcon` and the sidebar's
 * `drawer`, `right` and `right-mobile` attributes no longer do anything:
 * the frame is the design-system AppShell.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use `<x-artisanpack-app-shell>`. Removed in a later major release.
 */
class Main extends AppShell
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  mixed  $sidebar  The sidebar slot.
     * @param  mixed  $content  The content slot.
     * @param  mixed  $footer  A page footer under the content.
     * @param  bool|null  $fullWidth  Ignored.
     * @param  bool|null  $withNav  Ignored.
     * @param  string|null  $collapseText  Names the collapse button.
     * @param  string|null  $collapseIcon  Ignored.
     * @param  bool|null  $collapsible  Adds the collapse button.
     * @param  bool  $collapsed  Starts collapsed.
     */
    public function __construct(
        mixed $sidebar = null,
        public mixed $content = null,
        mixed $footer = null,
        public ?bool $fullWidth = false,
        public ?bool $withNav = false,
        public ?string $collapseText = null,
        public ?string $collapseIcon = null,
        ?bool $collapsible = false,
        bool $collapsed = false,
    ) {
        parent::__construct(
            collapsed: $collapsed,
            collapsible: (bool) $collapsible,
            sidebar: $sidebar,
            footer: $footer,
            collapseLabel: $collapseText,
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.main');
    }
}
