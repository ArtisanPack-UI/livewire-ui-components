<?php

/**
 * Toast Trait
 *
 * Shows design-system toasts from a Livewire component.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Traits;

use ArtisanPack\LivewireUiComponents\Support\Toasts;

/**
 * Toast Trait
 *
 * `toast()` dispatches the `toast` browser event the page's
 * `<x-artisanpack-toaster>` listens for, the same as
 * `$this->dispatch('toast', title: …)`. With `redirectTo`, or through
 * `flashToast()`, the toast is flashed to the session instead and shows on
 * the next page. Every toast fires the `ap.livewireUiComponents.toastDispatched`
 * action with its color, title and options.
 *
 * @since 1.0.0
 */
trait Toast
{
    /**
     * Shows a toast.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string  $color  A semantic color. error makes the toast an alert.
     * @param  string  $variant  default or inverse.
     * @param  string  $surface  overlay or glass.
     * @param  string|null  $icon  An icon name from the registry. Defaults to the color's status icon.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it. Defaults to the toaster's.
     * @param  bool  $hideClose  Hides the close button.
     * @param  string|null  $redirectTo  Flashes the toast and redirects here (with wire:navigate).
     *
     * @return mixed The redirect, when there is one.
     */
    public function toast(
        ?string $title = null,
        ?string $description = null,
        string $color = 'neutral',
        string $variant = 'default',
        string $surface = 'overlay',
        ?string $icon = null,
        ?int $duration = null,
        bool $hideClose = false,
        ?string $redirectTo = null,
    ): mixed {
        $this->warnAboutLegacyToastCall($title, $description);

        $toast = Toasts::make(
            title: $title,
            description: $description,
            color: $color,
            variant: $variant,
            surface: $surface,
            icon: $icon,
            duration: $duration,
            hideClose: $hideClose,
        );

        doAction('ap.livewireUiComponents.toastDispatched', $color, (string) $title, $toast);

        if (null !== $redirectTo) {
            Toasts::push($toast);

            return $this->redirect($redirectTo, navigate: true);
        }

        $this->dispatch('toast', ...$toast);

        return null;
    }

    /**
     * Keeps a toast for the next page, such as one you redirect to yourself.
     * Livewire drops flashed data from an action that doesn't redirect.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string  $color  A semantic color.
     * @param  string  $variant  default or inverse.
     * @param  string  $surface  overlay or glass.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  bool  $hideClose  Hides the close button.
     */
    public function flashToast(
        ?string $title = null,
        ?string $description = null,
        string $color = 'neutral',
        string $variant = 'default',
        string $surface = 'overlay',
        ?string $icon = null,
        ?int $duration = null,
        bool $hideClose = false,
    ): void {
        $toast = Toasts::flash(
            title: $title,
            description: $description,
            color: $color,
            variant: $variant,
            surface: $surface,
            icon: $icon,
            duration: $duration,
            hideClose: $hideClose,
        );

        doAction('ap.livewireUiComponents.toastDispatched', $color, (string) $title, $toast);
    }

    /**
     * Shows a success toast.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  string|null  $redirectTo  Flashes the toast and redirects here.
     *
     * @return mixed The redirect, when there is one.
     */
    public function success(string $title, ?string $description = null, ?int $duration = null, ?string $redirectTo = null): mixed
    {
        return $this->toast($title, $description, color: 'success', duration: $duration, redirectTo: $redirectTo);
    }

    /**
     * Shows a warning toast.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  string|null  $redirectTo  Flashes the toast and redirects here.
     *
     * @return mixed The redirect, when there is one.
     */
    public function warning(string $title, ?string $description = null, ?int $duration = null, ?string $redirectTo = null): mixed
    {
        return $this->toast($title, $description, color: 'warning', duration: $duration, redirectTo: $redirectTo);
    }

    /**
     * Shows an error toast, announced at once (role=alert). Never the only
     * place an error is reported: blocking errors use Alert.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  string|null  $redirectTo  Flashes the toast and redirects here.
     *
     * @return mixed The redirect, when there is one.
     */
    public function error(string $title, ?string $description = null, ?int $duration = null, ?string $redirectTo = null): mixed
    {
        return $this->toast($title, $description, color: 'error', duration: $duration, redirectTo: $redirectTo);
    }

    /**
     * Shows an info toast.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  string|null  $redirectTo  Flashes the toast and redirects here.
     *
     * @return mixed The redirect, when there is one.
     */
    public function info(string $title, ?string $description = null, ?int $duration = null, ?string $redirectTo = null): mixed
    {
        return $this->toast($title, $description, color: 'info', duration: $duration, redirectTo: $redirectTo);
    }

    /**
     * Raises a deprecation for a 2.x call, `toast( 'success', 'Saved' )`:
     * the first two arguments were the type and the title, and are now the
     * title and the description. The call still runs with the new meaning.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The first argument.
     * @param  string|null  $description  The second argument.
     */
    private function warnAboutLegacyToastCall(?string $title, ?string $description): void
    {
        if (null === $description || ! in_array($title, Toasts::COLORS, true)) {
            return;
        }

        trigger_error(
            sprintf(
                'toast( \'%s\', … ) looks like the 2.x toast( $type, $title ) call. In 3.0 the arguments are toast( $title, $description, color: … ), so "%s" is shown as the title. Use toast( $title, color: \'%s\' ) or $this->%s( $title ).',
                $title,
                $title,
                $title,
                in_array($title, ['success', 'warning', 'error', 'info'], true) ? $title : 'toast',
            ),
            E_USER_DEPRECATED,
        );
    }
}
