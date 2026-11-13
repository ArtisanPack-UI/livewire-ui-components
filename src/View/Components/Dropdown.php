<?php

/**
 * Dropdown Component
 *
 * The 2.x `dropdown`, now a deprecated alias of the design-system
 * DropdownMenu (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-dropdown-menu>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Dropdown Component Class
 *
 * Renders the design-system DropdownMenu from the 2.x `dropdown` API:
 * `label` is the trigger's label, `right` aligns the menu to the end and
 * `glass` picks the glass surface. A string `trigger` attribute is the label
 * when `label` isn't set. `id` stays an attribute, so it becomes the menu's
 * id. The default slot renders inside the menu, as in 2.x. The Tailwind-era
 * options (`icon`, `top`, `noXAnchor`, `glassTint`, `glassTintOpacity`) are
 * accepted and ignored. The menu no longer cancels clicks, so links inside
 * it open (#134).
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-dropdown-menu>.
 */
class Dropdown extends DropdownMenu
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $label  The trigger's label.
     * @param  string|null  $icon  Unused: the trigger always shows a chevron.
     * @param  bool|null  $right  Aligns the menu to the trigger's end.
     * @param  bool|null  $top  Unused: the menu has no vertical placement prop.
     * @param  bool|null  $noXAnchor  Unused: the behavior engine positions the menu.
     * @param  bool|string|null  $glass  Any value picks the glass surface.
     * @param  string|null  $glassTint  Unused: glass surface tokens replace it.
     * @param  int|null  $glassTintOpacity  Unused: glass surface tokens replace it.
     * @param  mixed  $trigger  A string is the label when `label` isn't set.
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  array<int, array<string, mixed>>  $items  The items as data.
     * @param  string|null  $align  start, center or end. Overrides `right`.
     * @param  string  $surface  overlay or glass. `glass` overrides it.
     * @param  bool  $portal  Renders the menu at the end of the body.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $label = null,
        ?string $icon = 'o-chevron-down',
        ?bool $right = false,
        ?bool $top = false,
        ?bool $noXAnchor = false,
        string|bool|null $glass = null,
        ?string $glassTint = null,
        ?int $glassTintOpacity = null,
        mixed $trigger = null,
        bool $open = false,
        array $items = [],
        ?string $align = null,
        string $surface = 'overlay',
        bool $portal = true,
        array $classes = [],
    ) {
        parent::__construct(
            open: $open,
            label: $label ?? (is_string($trigger) && '' !== $trigger ? $trigger : null),
            items: $items,
            align: $align ?? ($right ? 'end' : 'start'),
            surface: null !== $glass && false !== $glass && '' !== $glass ? 'glass' : $surface,
            portal: $portal,
            classes: $classes,
        );
    }
}
