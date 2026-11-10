<?php

/**
 * The design-system icon registry.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

/**
 * Resolves string icon names (`leading-icon="plus"`) to SVG, the same way
 * `@artisanpack-ui/behavior/icons` does in React and Vue (D-017).
 *
 * The default set is the Lucide subset the asset build copies into
 * `resources/design-system/icons/lucide.json`. Apps add names, or replace
 * one, with `IconRegistry::register()` or the `registered_icons` config key
 * (name => inner SVG markup of a 24x24 icon drawn with `currentColor`).
 *
 * Icon bodies are rendered unescaped, so they must be trusted, static SVG
 * shapes. `register()` and the config reject bodies that could run script
 * (the same check as `registerIcons` in `@artisanpack-ui/behavior/icons`):
 * script, style, foreignObject, iframe, object, embed, image, use and a
 * elements, `on*=` handlers, and javascript:, vbscript: or data: URLs.
 *
 * Names outside the registry fall back to blade-icons, so heroicon and
 * Font Awesome names such as `o-plus` keep rendering during the migration.
 *
 * @since 3.0.0
 */
final class IconRegistry
{
    /**
     * The attributes every registry icon's `<svg>` carries, in order. They
     * match `ICON_SVG_ATTRIBUTES` in `@artisanpack-ui/behavior/icons`.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const SVG_ATTRIBUTES = [
        'xmlns'           => 'http://www.w3.org/2000/svg',
        'viewBox'         => '0 0 24 24',
        'fill'            => 'none',
        'stroke'          => 'currentColor',
        'stroke-width'    => '1.5',
        'stroke-linecap'  => 'round',
        'stroke-linejoin' => 'round',
        'aria-hidden'     => 'true',
        'focusable'       => 'false',
    ];

    /**
     * Directional icons, which mirror under RTL (`data-mirror` on their part).
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const DIRECTIONAL = ['arrow-right', 'arrow-left', 'chevron-right', 'chevron-left'];

    /**
     * Markup an icon body may not contain. Mirrors `UNSAFE_MARKUP` in
     * `@artisanpack-ui/behavior/icons`.
     *
     * @since 3.0.0
     *
     * @var string
     */
    public const UNSAFE_MARKUP = '/<\s*\/?\s*(script|style|foreignObject|iframe|object|embed|image|use|a)\b|\son[a-z]+\s*=|(javascript|vbscript|data)\s*:/i';

    /**
     * Icons registered at runtime, name => inner SVG markup.
     *
     * @var array<string, string>
     */
    private static array $registered = [];

    /**
     * The default set, loaded once.
     *
     * @var array<string, string>|null
     */
    private static ?array $defaults = null;

    /**
     * Adds icons to the registry, or replaces ones with the same name.
     *
     * Bodies render unescaped: pass only trusted, static SVG shapes. Nothing
     * is registered when any body fails the safety check.
     *
     * @since 3.0.0
     *
     * @param  array<string, string>  $icons  Name => inner SVG markup.
     *
     * @throws InvalidArgumentException When a body could run script.
     */
    public static function register(array $icons): void
    {
        foreach ($icons as $name => $body) {
            self::assertSafe((string) $name, $body);
        }

        foreach ($icons as $name => $body) {
            self::$registered[(string) $name] = (string) $body;
        }
    }

    /**
     * Whether a body passes the safety check.
     *
     * @since 3.0.0
     *
     * @param  mixed  $body  The inner SVG markup.
     *
     * @return bool True when it is a string without unsafe markup.
     */
    public static function isSafe(mixed $body): bool
    {
        return is_string($body) && 1 !== preg_match(self::UNSAFE_MARKUP, $body);
    }

    /**
     * Restores the registry to the default set. For tests.
     *
     * @since 3.0.0
     */
    public static function reset(): void
    {
        self::$registered = [];
    }

    /**
     * The inner SVG markup for a registered name, or null.
     *
     * Runtime registrations win over the config, which wins over the defaults.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The icon name, such as `plus`.
     *
     * @return string|null The markup.
     */
    public static function resolve(string $name): ?string
    {
        if (array_key_exists($name, self::$registered)) {
            return self::$registered[$name];
        }

        $configured = self::configured();
        if (isset($configured[$name])) {
            return $configured[$name];
        }

        return self::defaults()[$name] ?? null;
    }

    /**
     * Whether a name is in the registry.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The icon name.
     *
     * @return bool True when registered.
     */
    public static function has(string $name): bool
    {
        return null !== self::resolve($name);
    }

    /**
     * Every registered name.
     *
     * @since 3.0.0
     *
     * @return list<string> The names.
     */
    public static function names(): array
    {
        return array_values(array_unique([
            ...array_keys(self::defaults()),
            ...array_keys(self::configured()),
            ...array_keys(self::$registered),
        ]));
    }

    /**
     * The full `<svg>` for a registered name, or an empty string.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The icon name.
     * @param  array<string, string>  $extra  Attributes added after the standard ones, such as `data-icon`.
     *
     * @return string The markup.
     */
    public static function svg(string $name, array $extra = []): string
    {
        $body = self::resolve($name);
        if (null === $body) {
            return '';
        }

        $attributes = [];
        foreach (self::SVG_ATTRIBUTES as $key => $value) {
            $attributes[] = $key.'="'.$value.'"';
        }
        foreach ($extra as $key => $value) {
            $attributes[] = e((string) $key).'="'.e($value).'"';
        }

        return '<svg '.implode(' ', $attributes).'>'.$body.'</svg>';
    }

    /**
     * The `<svg>` for any icon name: the registry first, then blade-icons
     * (with `aria-hidden="true"`), else an empty string.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The icon name, such as `plus` or `o-plus`.
     *
     * @return string The markup.
     */
    public static function render(?string $name): string
    {
        if (null === $name || '' === $name) {
            return '';
        }

        $svg = self::svg($name);
        if ('' !== $svg) {
            return $svg;
        }

        return self::bladeIcon($name);
    }

    /**
     * Whether a name mirrors under RTL.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The icon name.
     *
     * @return bool True for arrow and chevron left/right.
     */
    public static function isDirectional(?string $name): bool
    {
        return null !== $name && in_array($name, self::DIRECTIONAL, true);
    }

    /**
     * Renders a name through blade-icons, mapping it the way the Icon
     * component does (`o-plus` => `heroicon-o-plus`, `fas.user` => `fas-user`).
     *
     * @param  string  $name  The icon name.
     *
     * @return string The markup, or an empty string when no set has it.
     */
    private static function bladeIcon(string $name): string
    {
        if (! function_exists('svg')) {
            return '';
        }

        $alias  = function_exists('applyFilters') ? applyFilters('ap.livewireUiComponents.iconAlias', $name) : $name;
        $mapped = Str::of((string) $alias);
        $mapped = $mapped->contains('.') ? (string) $mapped->replace('.', '-') : 'heroicon-'.$mapped;

        try {
            return svg($mapped, '', ['aria-hidden' => 'true'])->toHtml();
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * The `registered_icons` config, checked.
     *
     * @return array<string, string> Name => inner SVG markup.
     *
     * @throws InvalidArgumentException When a configured body could run script.
     */
    private static function configured(): array
    {
        $configured = function_exists('config') ? config('artisanpack.livewire-ui-components.registered_icons', []) : [];

        $icons = [];
        foreach (is_array($configured) ? $configured : [] as $name => $body) {
            self::assertSafe((string) $name, $body);
            $icons[(string) $name] = $body;
        }

        return $icons;
    }

    /**
     * Throws when a body could run script.
     *
     * @param  string  $name  The icon name.
     * @param  mixed  $body  The inner SVG markup.
     *
     * @throws InvalidArgumentException When the body is unsafe.
     */
    private static function assertSafe(string $name, mixed $body): void
    {
        if (! self::isSafe($body)) {
            throw new InvalidArgumentException(sprintf('Icon "%s" was not registered: icon bodies must be static SVG shapes (D-017).', $name));
        }
    }

    /**
     * The default set, without the JSON's `$comment`.
     *
     * @return array<string, string> Name => inner SVG markup.
     */
    private static function defaults(): array
    {
        if (null !== self::$defaults) {
            return self::$defaults;
        }

        $file = dirname(__DIR__, 2).'/resources/design-system/icons/lucide.json';
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;

        $icons = [];
        foreach (is_array($data) ? $data : [] as $name => $body) {
            if (is_string($body) && ! str_starts_with((string) $name, '$')) {
                $icons[(string) $name] = $body;
            }
        }

        return self::$defaults = $icons;
    }
}
