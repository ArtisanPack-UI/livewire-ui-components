<?php

/**
 * Header Component
 *
 * The 2.x `header`, now a deprecated alias of the design-system PageHeader
 * (#58).
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

/**
 * Header Class
 *
 * Renders the design-system PageHeader from the 2.x `header` API:
 * `subtitle` is the description, the title keeps the 2.x default level 2,
 * and the `middle` slot renders in the row before the actions. The
 * Tailwind-era options (`separator`, `progressIndicator`,
 * `progressIndicatorClass`, `withAnchor`, `size`, `icon`, `iconClasses`)
 * are accepted and ignored.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use `<x-artisanpack-page-header>`. Removed in a later major release.
 */
class Header extends PageHeader
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  mixed  $title  The title.
     * @param  mixed  $subtitle  The description.
     * @param  bool|null  $separator  Ignored.
     * @param  bool|string|null  $progressIndicator  Ignored.
     * @param  string  $progressIndicatorClass  Ignored.
     * @param  bool|null  $withAnchor  Ignored.
     * @param  string|null  $size  Ignored.
     * @param  int  $level  The title's heading level. Default 2, as in 2.x.
     * @param  string|null  $icon  Ignored.
     * @param  string|null  $iconClasses  Ignored.
     * @param  mixed  $middle  Content in the row before the actions.
     * @param  mixed  $actions  The actions.
     * @param  mixed  $description  The description, when `subtitle` isn't given.
     */
    public function __construct(
        mixed $title = null,
        public mixed $subtitle = null,
        public ?bool $separator = false,
        public string|bool|null $progressIndicator = null,
        public string $progressIndicatorClass = '',
        public ?bool $withAnchor = false,
        public ?string $size = null,
        int $level = 2,
        public ?string $icon = null,
        public ?string $iconClasses = null,
        public mixed $middle = null,
        mixed $actions = null,
        mixed $description = null,
    ) {
        parent::__construct(
            title: $title,
            description: $description ?? $subtitle,
            level: $level,
            actions: $actions,
        );
    }
}
