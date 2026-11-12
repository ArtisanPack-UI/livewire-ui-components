<?php

/**
 * Profile Component
 *
 * Deprecated alias of the design-system UserMenu (#58): it is an avatar
 * dropdown, not a profile page.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-user-menu>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Profile Component Class
 *
 * Maps the 2.x props onto UserMenu: `title` is the name, `subtitle` the role
 * line and `image` the avatar. `right` aligns the menu to the end. The slot
 * renders as the menu's items; the theme control is hidden, as 2.x had none.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-user-menu>.
 */
class Profile extends UserMenu
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $image  The avatar image URL.
     * @param  string|null  $alt  Unused: the trigger is named after the person.
     * @param  string|null  $placeholder  The name, when title isn't set.
     * @param  string|null  $title  The person's name.
     * @param  string|null  $subtitle  The line under the name.
     * @param  bool|null  $right  Aligns the menu to the trigger's end.
     * @param  string|null  $logoutAction  A URL Log out posts to.
     */
    public function __construct(
        ?string $image = null,
        ?string $alt = null,
        ?string $placeholder = null,
        ?string $title = null,
        ?string $subtitle = null,
        ?bool $right = false,
        ?string $logoutAction = null,
    ) {
        parent::__construct(
            user: [
                'name'   => $title ?? $placeholder ?? $alt ?? '',
                'avatar' => $image,
                'role'   => $subtitle,
            ],
            hideTheme: true,
            align: $right ? 'end' : 'start',
            logoutAction: $logoutAction,
        );
    }
}
