<?php

/**
 * Nav Component
 *
 * The 2.x `nav`, now a deprecated alias of the design-system Navbar (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-navbar>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Nav Component Class
 *
 * Renders the design-system Navbar from the 2.x `nav` API. The `brand` and
 * `actions` slots carry over unchanged, and `sticky` keeps its 2.x default
 * of off. `fullWidth` is accepted and ignored: the bar is always full width.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-navbar>.
 */
class Nav extends Navbar
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  bool|null  $sticky  Sticks to the top of the scrolling container.
     * @param  bool|null  $fullWidth  Unused: the bar is always full width.
     * @param  mixed  $brand  The brand: a string, or the `brand` slot.
     * @param  mixed  $actions  Controls at the end: a string, or the `actions` slot.
     * @param  string  $layout  bar (full width) or island (rounded).
     * @param  string  $surface  default or glass.
     * @param  array<int, array<string, mixed>>  $links  The links as data: `{ label, href, current }`.
     * @param  mixed  $search  A search field: a string, or the `search` slot.
     * @param  string|null  $label  Names the links' nav landmark.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?bool $sticky = false,
        ?bool $fullWidth = false,
        mixed $brand = null,
        mixed $actions = null,
        string $layout = 'bar',
        string $surface = 'default',
        array $links = [],
        mixed $search = null,
        ?string $label = null,
        array $classes = [],
    ) {
        parent::__construct(
            layout: $layout,
            surface: $surface,
            sticky: (bool) $sticky,
            brand: $brand,
            links: $links,
            search: $search,
            actions: $actions,
            label: $label,
            classes: $classes,
        );
    }
}
