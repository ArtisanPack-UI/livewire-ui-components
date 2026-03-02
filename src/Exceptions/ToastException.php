<?php

declare(strict_types=1);
/**
 * ToastException
 *
 * This file contains the ToastException class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;

/**
 * ToastException Class
 *
 * Provides an exception type that can display toast notifications to users.
 *
 * @since 1.0.0
 */
class ToastException extends Exception
{
    /**
     * The type of toast (info, success, error, warning).
     *
     * @since 1.0.0
     */
    protected string $type = 'info';

    /**
     * The toast title.
     *
     * @since 1.0.0
     */
    protected ?string $title = null;

    /**
     * The toast description.
     *
     * @since 1.0.0
     */
    protected ?string $description = null;

    /**
     * The position of the toast on screen.
     *
     * @since 1.0.0
     */
    protected string $position = 'toast-top toast-end';

    /**
     * The icon to display in the toast.
     *
     * @since 1.0.0
     */
    protected string $icon = 'o-information-circle';

    /**
     * The CSS classes for styling the toast.
     *
     * @since 1.0.0
     */
    protected string $css = 'alert-info';

    /**
     * The timeout duration in milliseconds.
     *
     * @since 1.0.0
     */
    protected int $timeout = 3000;

    /**
     * Whether to prevent the default exception handling.
     *
     * @since 1.0.0
     */
    protected bool $preventDefault = true;

    /**
     * Create a typed toast message exception.
     *
     * @since 1.0.0
     *
     * @param  string  $type  The toast type (info, success, error, warning).
     * @param  string  $title  The toast title.
     * @param  string|null  $description  Optional. The toast description. Default null.
     * @param  string  $position  Optional. The toast position on screen. Default 'toast-top toast-end'.
     * @param  string  $icon  Optional. The icon to display. Default 'o-information-circle'.
     * @param  string  $css  Optional. The CSS classes for styling. Default 'alert-info'.
     * @param  int  $timeout  Optional. The timeout duration in milliseconds. Default 3000.
     */
    public static function typedMessage(
        string $type,
        string $title,
        ?string $description = null,
        string $position = 'toast-top toast-end',
        string $icon = 'o-information-circle',
        string $css = 'alert-info',
        int $timeout = 3000,
    ): self {
        $instance = new self(message: $title, code: 500);

        $instance->type        = $type;
        $instance->title       = $title;
        $instance->description = $description;
        $instance->position    = $position;
        $instance->icon        = $icon;
        $instance->css         = $css;
        $instance->timeout     = $timeout;

        return $instance;
    }

    /**
     * Create an info toast message exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The toast title.
     * @param  string|null  $description  Optional. The toast description. Default null.
     * @param  string  $position  Optional. The toast position on screen. Default 'toast-top toast-end'.
     * @param  string  $icon  Optional. The icon to display. Default 'o-information-circle'.
     * @param  string  $css  Optional. The CSS classes for styling. Default 'alert-info'.
     * @param  int  $timeout  Optional. The timeout duration in milliseconds. Default 3000.
     */
    public static function info(
        string $title,
        ?string $description = null,
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
            timeout: $timeout,
        );
    }

    /**
     * Create a success toast message exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The toast title.
     * @param  string|null  $description  Optional. The toast description. Default null.
     * @param  string  $position  Optional. The toast position on screen. Default 'toast-top toast-end'.
     * @param  string  $icon  Optional. The icon to display. Default 'o-check-circle'.
     * @param  string  $css  Optional. The CSS classes for styling. Default 'alert-success'.
     * @param  int  $timeout  Optional. The timeout duration in milliseconds. Default 3000.
     */
    public static function success(
        string $title,
        ?string $description = null,
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
            timeout: $timeout,
        );
    }

    /**
     * Create an error toast message exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The toast title.
     * @param  string|null  $description  Optional. The toast description. Default null.
     * @param  string  $position  Optional. The toast position on screen. Default 'toast-top toast-end'.
     * @param  string  $icon  Optional. The icon to display. Default 'o-x-circle'.
     * @param  string  $css  Optional. The CSS classes for styling. Default 'alert-error'.
     * @param  int  $timeout  Optional. The timeout duration in milliseconds. Default 3000.
     */
    public static function error(
        string $title,
        ?string $description = null,
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
            timeout: $timeout,
        );
    }

    /**
     * Create a warning toast message exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The toast title.
     * @param  string|null  $description  Optional. The toast description. Default null.
     * @param  string  $position  Optional. The toast position on screen. Default 'toast-top toast-end'.
     * @param  string  $icon  Optional. The icon to display. Default 'o-exclamation-triangle'.
     * @param  string  $css  Optional. The CSS classes for styling. Default 'alert-warning'.
     * @param  int  $timeout  Optional. The timeout duration in milliseconds. Default 3000.
     */
    public static function warning(
        string $title,
        ?string $description = null,
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
            timeout: $timeout,
        );
    }

    /**
     * Allow default exception handling to proceed.
     *
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
     * @since 1.0.0
     *
     * @param  Request  $request  The incoming request.
     *
     * @return false|JsonResponse JSON response for Livewire requests, false otherwise.
     */
    public function render(Request $request): JsonResponse|false
    {
        if ($request->hasHeader('x-livewire')) {
            $validatedIcon = $this->validateIconName($this->icon);

            return response()->json([
                'toast' => [
                    'type'        => $this->type,
                    'title'       => $this->title,
                    'description' => $this->description,

                    'position' => $this->position,
                    'icon'     => Blade::render("<x-artisanpack-icon class='w-7 h-7' :name=\"\$iconName\" />", ['iconName' => $validatedIcon]),
                    'css'      => $this->css,
                    'timeout'  => $this->timeout,
                ],
                'prevent_default' => $this->preventDefault,
            ], $this->getCode());
        }

        return false;
    }

    /**
     * Validate and sanitize icon name to prevent XSS.
     *
     * @since 1.0.0
     *
     * @param  string  $icon  The icon name to validate.
     *
     * @throws InvalidArgumentException If the icon name is invalid.
     *
     * @return string The validated icon name.
     */
    private function validateIconName(string $icon): string
    {
        // Allow only alphanumeric characters, hyphens, and underscores
        if (! preg_match('/^[a-zA-Z0-9_-]+$/', $icon)) {
            throw new InvalidArgumentException(
                "Invalid icon name: '{$icon}'. Icon names must contain only alphanumeric characters, hyphens, and underscores.",
            );
        }

        return $icon;
    }
}
