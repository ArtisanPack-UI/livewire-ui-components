<?php
/**
 * ToastException
 *
 * This file contains the ToastException class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\Exceptions
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
/**
 * ToastException Class
 *
 * Provides functionality for the ToastException component.
 *
 * @since 1.0.0
 */

class ToastException extends Exception
{
    /**
     * The type of toast (info, success, error, warning).
     *
     * @var string
     */
    protected string $type = 'info';

    /**
     * The toast title.
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * The toast description.
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * The position of the toast on screen.
     *
     * @var string
     */
    protected string $position = 'toast-top toast-end';

    /**
     * The icon to display in the toast.
     *
     * @var string
     */
    protected string $icon = 'o-information-circle';

    /**
     * The CSS classes for styling the toast.
     *
     * @var string
     */
    protected string $css = 'alert-info';

    /**
     * The timeout duration in milliseconds.
     *
     * @var int
     */
    protected int $timeout = 3000;

    /**
     * Whether to prevent the default exception handling.
     *
     * @var bool
     */
    protected bool $preventDefault = true;

    /**
     * Create a typed toast message exception.
     *
     * @param string $type The toast type (info, success, error, warning)
     * @param string $title The toast title
     * @param string|null $description The toast description
     * @param string $position The toast position on screen
     * @param string $icon The icon to display
     * @param string $css The CSS classes for styling
     * @param int $timeout The timeout duration in milliseconds
     * @return self
     * @since 1.0.0
     */
    public static function typedMessage(
        string $type,
        string $title,
        string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-information-circle',
        string $css = 'alert-info',
        int $timeout = 3000
    ): self {
        $instance = new self(message: $title, code: 500);

        $instance->type = $type;
        $instance->title = $title;
        $instance->description = $description;
        $instance->position = $position;
        $instance->icon = $icon;
        $instance->css = $css;
        $instance->timeout = $timeout;

        return $instance;
    }

    /**
     * Create an info toast message exception.
     *
     * @param string $title The toast title
     * @param string|null $description The toast description
     * @param string $position The toast position on screen
     * @param string $icon The icon to display
     * @param string $css The CSS classes for styling
     * @param int $timeout The timeout duration in milliseconds
     * @return self
     * @since 1.0.0
     */
    public static function info(
        string $title,
        string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-information-circle',
        string $css = 'alert-info',
        int $timeout = 3000,
    ): self {
        return self::typedMessage(
            type: 'info',
            title: $title,
            description: $description,
            position: $position,
            icon: $icon,
            css: $css,
            timeout: $timeout
        );
    }

    /**
     * Create a success toast message exception.
     *
     * @param string $title The toast title
     * @param string|null $description The toast description
     * @param string $position The toast position on screen
     * @param string $icon The icon to display
     * @param string $css The CSS classes for styling
     * @param int $timeout The timeout duration in milliseconds
     * @return self
     * @since 1.0.0
     */
    public static function success(
        string $title,
        string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-check-circle',
        string $css = 'alert-success',
        int $timeout = 3000,
    ): self {
        return self::typedMessage(
            type: 'success',
            title: $title,
            description: $description,
            position: $position,
            icon: $icon,
            css: $css,
            timeout: $timeout
        );
    }

    /**
     * Create an error toast message exception.
     *
     * @param string $title The toast title
     * @param string|null $description The toast description
     * @param string $position The toast position on screen
     * @param string $icon The icon to display
     * @param string $css The CSS classes for styling
     * @param int $timeout The timeout duration in milliseconds
     * @return self
     * @since 1.0.0
     */
    public static function error(
        string $title,
        string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-x-circle',
        string $css = 'alert-error',
        int $timeout = 3000,
    ): self {
        return self::typedMessage(
            type: 'error',
            title: $title,
            description: $description,
            position: $position,
            icon: $icon,
            css: $css,
            timeout: $timeout
        );
    }

    /**
     * Create a warning toast message exception.
     *
     * @param string $title The toast title
     * @param string|null $description The toast description
     * @param string $position The toast position on screen
     * @param string $icon The icon to display
     * @param string $css The CSS classes for styling
     * @param int $timeout The timeout duration in milliseconds
     * @return self
     * @since 1.0.0
     */
    public static function warning(
        string $title,
        string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-exclamation-triangle',
        string $css = 'alert-warning',
        int $timeout = 3000,
    ): self {
        return self::typedMessage(
            type: 'warning',
            title: $title,
            description: $description,
            position: $position,
            icon: $icon,
            css: $css,
            timeout: $timeout
        );
    }

    /**
     * Allow default exception handling to proceed.
     *
     * @return self
     * @since 1.0.0
     */
    public function permitDefault(): self
    {
        $this->preventDefault = false;

        return $this;
    }

    /**
     * Render the exception as a JSON response for Livewire requests.
     *
     * @param Request $request The incoming request
     * @return JsonResponse|false JSON response for Livewire requests, false otherwise
     * @since 1.0.0
     */
    public function render(Request $request): JsonResponse|false
    {
        if ($request->hasHeader('x-livewire')) {
            return response()->json([
                'toast' => [
                    'type' => $this->type,
                    'title' => $this->title,
                    'description' => $this->description,

                    'position' => $this->position,
                    'icon' => Blade::render("<x-artisanpack-icon class='w-7 h-7' name='" . $this->icon . "' />"),
                    'css' => $this->css,
                    'timeout' => $this->timeout,
                ],
                'prevent_default' => $this->preventDefault
            ], $this->getCode());
        }

        return false;
    }
}
