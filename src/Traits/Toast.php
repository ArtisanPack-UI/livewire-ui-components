<?php
/**
 * Toast Trait
 *
 * This trait provides functionality for displaying toast notifications.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage Traits
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Traits;

use Illuminate\Support\Facades\Blade;

/**
 * Toast Trait
 *
 * Provides methods for displaying various types of toast notifications.
 *
 * @since 1.0.0
 */
trait Toast
{
    /**
     * Display a toast notification.
     *
     * @param string      $type        The type of toast notification.
     * @param string      $title       The title of the toast notification.
     * @param string|null $description Optional description for the toast notification.
     * @param string|null $position    Optional position for the toast notification.
     * @param string      $icon        The icon to display in the toast notification. Default: 'o-information-circle'.
     * @param string      $css         CSS classes for the toast notification. Default: 'alert-info'.
     * @param int         $timeout     Timeout in milliseconds. Default: 3000.
     * @param string|null $redirectTo  Optional URL to redirect to after displaying the toast.
     * @return mixed
     * @since 1.0.0
     */
    public function toast(
        string $type,
        string $title,
        ?string $description = null,
        ?string $position = null,
        string $icon = 'o-information-circle',
        string $css = 'alert-info',
        int $timeout = 3000,
        ?string $redirectTo = null
    ) {
        $toast = [
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'position' => $position,
            'icon' => Blade::render("<x-artisanpack-icon class='w-7 h-7' name='".$icon."' />"),
            'css' => $css,
            'timeout' => $timeout,
        ];

        $this->js('toast('.json_encode(['toast' => $toast]).')');

        session()->flash('mary.toast.title', $title);
        session()->flash('mary.toast.description', $description);

        if ($redirectTo) {
            return $this->redirect($redirectTo, navigate: true);
        }
    }

    /**
     * Display a success toast notification.
     *
     * @param string      $title       The title of the toast notification.
     * @param string|null $description Optional description for the toast notification.
     * @param string|null $position    Optional position for the toast notification.
     * @param string      $icon        The icon to display in the toast notification. Default: 'o-check-circle'.
     * @param string      $css         CSS classes for the toast notification. Default: 'alert-success'.
     * @param int         $timeout     Timeout in milliseconds. Default: 3000.
     * @param string|null $redirectTo  Optional URL to redirect to after displaying the toast.
     * @return mixed
     * @since 1.0.0
     */
    public function success(
        string $title,
        ?string $description = null,
        ?string $position = null,
        string $icon = 'o-check-circle',
        string $css = 'alert-success',
        int $timeout = 3000,
        ?string $redirectTo = null
    ) {
        return $this->toast('success', $title, $description, $position, $icon, $css, $timeout, $redirectTo);
    }

    /**
     * Display a warning toast notification.
     *
     * @param string      $title       The title of the toast notification.
     * @param string|null $description Optional description for the toast notification.
     * @param string|null $position    Optional position for the toast notification.
     * @param string      $icon        The icon to display in the toast notification. Default: 'o-exclamation-triangle'.
     * @param string      $css         CSS classes for the toast notification. Default: 'alert-warning'.
     * @param int         $timeout     Timeout in milliseconds. Default: 3000.
     * @param string|null $redirectTo  Optional URL to redirect to after displaying the toast.
     * @return mixed
     * @since 1.0.0
     */
    public function warning(
        string $title,
        ?string $description = null,
        ?string $position = null,
        string $icon = 'o-exclamation-triangle',
        string $css = 'alert-warning',
        int $timeout = 3000,
        ?string $redirectTo = null
    ) {
        return $this->toast('warning', $title, $description, $position, $icon, $css, $timeout, $redirectTo);
    }

    /**
     * Display an error toast notification.
     *
     * @param string      $title       The title of the toast notification.
     * @param string|null $description Optional description for the toast notification.
     * @param string|null $position    Optional position for the toast notification.
     * @param string      $icon        The icon to display in the toast notification. Default: 'o-x-circle'.
     * @param string      $css         CSS classes for the toast notification. Default: 'alert-error'.
     * @param int         $timeout     Timeout in milliseconds. Default: 3000.
     * @param string|null $redirectTo  Optional URL to redirect to after displaying the toast.
     * @return mixed
     * @since 1.0.0
     */
    public function error(
        string $title,
        ?string $description = null,
        ?string $position = null,
        string $icon = 'o-x-circle',
        string $css = 'alert-error',
        int $timeout = 3000,
        ?string $redirectTo = null
    ) {
        return $this->toast('error', $title, $description, $position, $icon, $css, $timeout, $redirectTo);
    }

    /**
     * Display an info toast notification.
     *
     * @param string      $title       The title of the toast notification.
     * @param string|null $description Optional description for the toast notification.
     * @param string|null $position    Optional position for the toast notification.
     * @param string      $icon        The icon to display in the toast notification. Default: 'o-information-circle'.
     * @param string      $css         CSS classes for the toast notification. Default: 'alert-info'.
     * @param int         $timeout     Timeout in milliseconds. Default: 3000.
     * @param string|null $redirectTo  Optional URL to redirect to after displaying the toast.
     * @return mixed
     * @since 1.0.0
     */
    public function info(
        string $title,
        ?string $description = null,
        ?string $position = null,
        string $icon = 'o-information-circle',
        string $css = 'alert-info',
        int $timeout = 3000,
        ?string $redirectTo = null
    ) {
        return $this->toast('info', $title, $description, $position, $icon, $css, $timeout, $redirectTo);
    }
}
