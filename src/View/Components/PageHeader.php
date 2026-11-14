<?php

/**
 * PageHeader Component
 *
 * The design-system PageHeader (#58): the title block at the top of a page.
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
use Closure;
use Illuminate\Contracts\View\View;

/**
 * PageHeader Component Class
 *
 * Renders the `.apui-page-header` block: the breadcrumbs, then a row with
 * the meta, title (a heading at `level`, the page's h1 by default) and
 * description beside the actions, then the tabs. Every part is a prop or a
 * named slot; a slot wins. Every attribute lands on the root.
 *
 * @since 3.0.0
 */
class PageHeader extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  mixed  $title  The page's title: a string, or the `title` slot.
     * @param  mixed  $description  Supporting text: a string, or the `description` slot.
     * @param  int  $level  The title's heading level, 1 to 6.
     * @param  mixed  $breadcrumbs  Breadcrumbs above the title: a string, or the `breadcrumbs` slot.
     * @param  mixed  $meta  Status above the title: a string, or the `meta` slot.
     * @param  mixed  $actions  Buttons at the inline end: a string, or the `actions` slot.
     * @param  mixed  $tabs  Tabs under the row: a string, or the `tabs` slot.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public mixed $title = null,
        public mixed $description = null,
        public int $level = 1,
        public mixed $breadcrumbs = null,
        public mixed $meta = null,
        public mixed $actions = null,
        public mixed $tabs = null,
        public array $classes = [],
    ) {
        $this->level = max(1, min(6, $this->level));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.page-header');
    }
}
