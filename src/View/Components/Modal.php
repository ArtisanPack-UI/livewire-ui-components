<?php

/**
 * Modal Component
 *
 * The 2.x `modal`, now a deprecated alias of the design-system Dialog
 * (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-dialog>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Modal Component Class
 *
 * Renders the design-system Dialog from the 2.x `modal` API: `subtitle` is
 * the description, `boxClass` lands on the panel (the `root` class), the
 * `actions` attribute is the footer and `glass` picks the glass surface.
 * `persistent` renders an alertdialog without a close button, so the
 * backdrop doesn't close it (Escape still does). `wire:model` binds the open
 * state, as in 2.x, and `id` stays an attribute, so it becomes the dialog's
 * id. The Tailwind-era options (`separator`, `withoutTrapFocus`,
 * `glassTint`, `glassTintOpacity`) are accepted and ignored.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-dialog>.
 */
class Modal extends Dialog
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $title  The heading and the dialog's accessible name.
     * @param  string|null  $subtitle  The description, when `description` isn't set.
     * @param  string|null  $boxClass  Extra classes on the panel.
     * @param  bool|null  $separator  Unused: the dialog draws its own header and footer.
     * @param  bool|null  $persistent  Renders an alertdialog without a close button.
     * @param  bool|null  $withoutTrapFocus  Unused: the dialog always traps focus.
     * @param  bool|string|null  $glass  Any value picks the glass surface.
     * @param  string|null  $glassTint  Unused: glass surface tokens replace it.
     * @param  int|null  $glassTintOpacity  Unused: glass surface tokens replace it.
     * @param  string|null  $actions  The footer, when `footer` isn't set.
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string  $size  The panel's width: sm, md or lg.
     * @param  string|null  $role  dialog or alertdialog. Overrides `persistent`.
     * @param  string  $surface  overlay or glass. `glass` overrides it.
     * @param  string|null  $description  Supporting text under the title.
     * @param  string|null  $footer  The actions row.
     * @param  string|null  $trigger  Content for a trigger button that opens the dialog.
     * @param  bool  $portal  Renders the dialog at the end of the body.
     * @param  bool  $hideClose  Hides the close button.
     * @param  string|null  $closeLabel  The close button's accessible name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $title = null,
        public ?string $subtitle = null,
        ?string $boxClass = null,
        ?bool $separator = false,
        ?bool $persistent = false,
        ?bool $withoutTrapFocus = false,
        string|bool|null $glass = null,
        ?string $glassTint = null,
        ?int $glassTintOpacity = null,
        public ?string $actions = null,
        bool $open = false,
        string $size = 'md',
        ?string $role = null,
        string $surface = 'overlay',
        ?string $description = null,
        ?string $footer = null,
        ?string $trigger = null,
        bool $portal = true,
        bool $hideClose = false,
        ?string $closeLabel = null,
        array $classes = [],
    ) {
        if (null !== $boxClass && '' !== trim($boxClass)) {
            $classes['root'] = trim(($classes['root'] ?? '').' '.$boxClass);
        }

        parent::__construct(
            open: $open,
            size: $size,
            role: $role ?? ($persistent ? 'alertdialog' : 'dialog'),
            surface: null !== $glass && false !== $glass && '' !== $glass ? 'glass' : $surface,
            title: $title,
            description: $description,
            footer: $footer,
            trigger: $trigger,
            portal: $portal,
            hideClose: $hideClose || (bool) $persistent,
            closeLabel: $closeLabel,
            classes: $classes,
        );
    }
}
