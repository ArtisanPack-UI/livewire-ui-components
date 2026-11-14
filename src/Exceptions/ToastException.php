<?php

/**
 * ToastException
 *
 * An exception that answers a Livewire request with a design-system toast.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Exceptions;

use ArtisanPack\LivewireUiComponents\Support\Toasts;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * ToastException Class
 *
 * Thrown in a Livewire action, it answers with `{ toast: { … },
 * prevent_default: true }`, which `<x-artisanpack-toaster>` shows as a
 * toast (the options `Support\Toasts::make()` builds).
 *
 * @since 1.0.0
 */
class ToastException extends Exception
{
    /**
     * The toast's options. An exception built with only a message shows
     * that message as an error toast.
     *
     * @since 3.0.0
     *
     * @var array<string, bool|int|string>
     */
    protected array $toast = [];

    /**
     * Whether to stop Livewire's default error handling.
     *
     * @since 1.0.0
     */
    protected bool $preventDefault = true;

    /**
     * Creates a toast exception with a color.
     *
     * @since 3.0.0
     *
     * @param  string  $color  A semantic color.
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $icon  An icon name from the registry. Defaults to the color's status icon.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     */
    public static function typedMessage(
        string $color,
        string $title,
        ?string $description = null,
        ?string $icon = null,
        ?int $duration = null,
    ): self {
        $instance = new self(message: $title, code: 500);

        try {
            $instance->toast = Toasts::make(title: $title, description: $description, color: $color, icon: $icon, duration: $duration);
        } catch (InvalidArgumentException) {
            // A bad icon (such as a 2.x position passed third) or color must
            // not turn the toast into a 500: show it plainly.
            $instance->toast = Toasts::make(
                title: $title,
                description: $description,
                color: in_array($color, Toasts::COLORS, true) ? $color : null,
                duration: null !== $duration && $duration >= 0 ? $duration : null,
            );
        }

        return $instance;
    }

    /**
     * Creates an info toast exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     */
    public static function info(string $title, ?string $description = null, ?string $icon = null, ?int $duration = null): self
    {
        return self::typedMessage('info', $title, $description, $icon, $duration);
    }

    /**
     * Creates a success toast exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     */
    public static function success(string $title, ?string $description = null, ?string $icon = null, ?int $duration = null): self
    {
        return self::typedMessage('success', $title, $description, $icon, $duration);
    }

    /**
     * Creates an error toast exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     */
    public static function error(string $title, ?string $description = null, ?string $icon = null, ?int $duration = null): self
    {
        return self::typedMessage('error', $title, $description, $icon, $duration);
    }

    /**
     * Creates a warning toast exception.
     *
     * @since 1.0.0
     *
     * @param  string  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     */
    public static function warning(string $title, ?string $description = null, ?string $icon = null, ?int $duration = null): self
    {
        return self::typedMessage('warning', $title, $description, $icon, $duration);
    }

    /**
     * Lets Livewire's default error handling run too.
     *
     * @since 1.0.0
     */
    public function permitDefault(): self
    {
        $this->preventDefault = false;

        return $this;
    }

    /**
     * The toast's options. An exception built with only a message shows
     * that message as an error toast.
     *
     * @since 3.0.0
     *
     * @return array<string, bool|int|string> The options.
     */
    public function toast(): array
    {
        if ([] !== $this->toast) {
            return $this->toast;
        }

        // Built with `new ToastException( 'message' )`: the message is the title.
        $message = $this->getMessage();

        return Toasts::make(title: '' !== $message ? $message : __('Something went wrong.'), color: 'error');
    }

    /**
     * Renders the toast as JSON for Livewire requests.
     *
     * @since 1.0.0
     *
     * @param  Request  $request  The incoming request.
     *
     * @return false|JsonResponse JSON for Livewire requests, false otherwise.
     */
    public function render(Request $request): JsonResponse|false
    {
        if (! $request->hasHeader('x-livewire')) {
            return false;
        }

        $status = $this->getCode();

        return response()->json([
            'toast'           => $this->toast(),
            'prevent_default' => $this->preventDefault,
        ], is_int($status) && $status >= 400 && $status < 600 ? $status : 500);
    }
}
