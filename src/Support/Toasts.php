<?php

/**
 * Toasts
 *
 * Builds, validates and flashes the toasts the design-system toaster shows.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use InvalidArgumentException;

/**
 * Toast options as the toaster takes them (D-019): the same in every stack.
 * `make()` validates them, `flash()` keeps them for the next request (a
 * redirect), and `<x-artisanpack-toaster>` shows the flashed ones with
 * `pull()`. From a Livewire component, `$this->dispatch('toast', …)` shows
 * one at once.
 *
 * @since 3.0.0
 */
final class Toasts
{
    /**
     * The session key flashed toasts are kept under.
     *
     * @since 3.0.0
     *
     * @var string
     */
    public const SESSION_KEY = 'artisanpack.toasts';

    /**
     * The semantic colors a toast takes.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    /**
     * The status icon each color shows when a toast names none (the behavior
     * package's TOAST_ICONS).
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const STATUS_ICONS = [
        'success' => 'circle-check',
        'warning' => 'triangle-alert',
        'error'   => 'circle-alert',
    ];

    /**
     * Builds a toast's options, leaving out the ones not given.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $color  A semantic color. error makes the toast an alert.
     * @param  string|null  $variant  default or inverse.
     * @param  string|null  $surface  overlay or glass.
     * @param  string|null  $icon  An icon name from the registry. Defaults to the color's status icon.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it. Defaults to the toaster's.
     * @param  bool|null  $hideClose  Hides the close button.
     * @param  string|null  $id  Reusing an id updates that toast instead of adding one.
     *
     * @throws InvalidArgumentException When an option isn't one the toaster takes.
     *
     * @return array<string, bool|int|string> The options.
     */
    public static function make(
        ?string $title = null,
        ?string $description = null,
        ?string $color = null,
        ?string $variant = null,
        ?string $surface = null,
        ?string $icon = null,
        ?int $duration = null,
        ?bool $hideClose = null,
        ?string $id = null,
    ): array {
        if (null === $title && null === $description) {
            throw new InvalidArgumentException('A toast needs a title or a description.');
        }
        self::assertOneOf('color', $color, self::COLORS);
        self::assertOneOf('variant', $variant, ['default', 'inverse']);
        self::assertOneOf('surface', $surface, ['overlay', 'glass']);
        if (null !== $icon && 1 !== preg_match('/^[a-z0-9][a-z0-9-]*$/i', $icon)) {
            throw new InvalidArgumentException(sprintf('"%s" is not an icon name. Icon names hold letters, digits and hyphens.', $icon));
        }
        if (null !== $duration && $duration < 0) {
            throw new InvalidArgumentException('A toast\'s duration is 0 (keep it) or more milliseconds.');
        }

        return array_filter(
            [
                'id'          => $id,
                'title'       => $title,
                'description' => $description,
                'color'       => $color,
                'variant'     => $variant,
                'surface'     => $surface,
                'icon'        => $icon,
                'duration'    => $duration,
                'hideClose'   => true === $hideClose ? true : null,
            ],
            static fn (mixed $value): bool => null !== $value,
        );
    }

    /**
     * Keeps a toast for the next request, such as the page a redirect lands
     * on. Takes the same options as make().
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The message.
     * @param  string|null  $description  Supporting text.
     * @param  string|null  $color  A semantic color.
     * @param  string|null  $variant  default or inverse.
     * @param  string|null  $surface  overlay or glass.
     * @param  string|null  $icon  An icon name from the registry.
     * @param  int|null  $duration  Milliseconds before it dismisses itself. 0 keeps it.
     * @param  bool|null  $hideClose  Hides the close button.
     * @param  string|null  $id  An id, to update a toast instead of adding one.
     *
     * @return array<string, bool|int|string> The options flashed.
     */
    public static function flash(
        ?string $title = null,
        ?string $description = null,
        ?string $color = null,
        ?string $variant = null,
        ?string $surface = null,
        ?string $icon = null,
        ?int $duration = null,
        ?bool $hideClose = null,
        ?string $id = null,
    ): array {
        $toast = self::make($title, $description, $color, $variant, $surface, $icon, $duration, $hideClose, $id);
        self::push($toast);

        return $toast;
    }

    /**
     * Keeps already-built options for the next request.
     *
     * @since 3.0.0
     *
     * @param  array<string, bool|int|string>  $toast  Options from make().
     */
    public static function push(array $toast): void
    {
        $flashed   = session()->get(self::SESSION_KEY, []);
        $flashed   = is_array($flashed) ? array_values($flashed) : [];
        $flashed[] = $toast;

        session()->flash(self::SESSION_KEY, $flashed);
    }

    /**
     * Takes the flashed toasts out of the session, so each shows once.
     *
     * @since 3.0.0
     *
     * @return list<array<string, bool|int|string>> The toasts.
     */
    public static function pull(): array
    {
        if (! app()->bound('session')) {
            return [];
        }

        $flashed = session()->pull(self::SESSION_KEY, []);

        return is_array($flashed) ? array_values(array_filter($flashed, 'is_array')) : [];
    }

    /**
     * The status icon for a color.
     *
     * @since 3.0.0
     *
     * @param  string  $color  The color.
     *
     * @return string The icon name: circle-check, triangle-alert, circle-alert or info.
     */
    public static function statusIcon(string $color): string
    {
        return self::STATUS_ICONS[$color] ?? 'info';
    }

    /**
     * Zag's toast type for a color, which sets its queue priority.
     *
     * @since 3.0.0
     *
     * @param  string  $color  The color.
     *
     * @return string success, warning, error or info.
     */
    public static function type(string $color): string
    {
        return in_array($color, ['success', 'warning', 'error'], true) ? $color : 'info';
    }

    /**
     * Throws unless the value is null or one of the allowed values.
     *
     * @since 3.0.0
     *
     * @param  string  $option  The option's name.
     * @param  string|null  $value  The value.
     * @param  list<string>  $allowed  The allowed values.
     *
     * @throws InvalidArgumentException When it isn't allowed.
     */
    private static function assertOneOf(string $option, ?string $value, array $allowed): void
    {
        if (null !== $value && ! in_array($value, $allowed, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a toast %s. Use one of: %s.', $value, $option, implode(', ', $allowed)));
        }
    }
}
