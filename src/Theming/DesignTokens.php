<?php

/**
 * The design-system token map.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;

/**
 * Reads the token map that `@artisanpack-ui/tokens` builds as a PHP array
 * (`generated/tokens.php`), and resolves token values per mode.
 *
 * The package ships a copy in `resources/design-system/tokens.php`. Pass
 * another path to read a newer build, for example from node_modules.
 *
 * @since 3.0.0
 */
final class DesignTokens
{
    /**
     * Matches a `var(--apui-…)` reference.
     */
    private const VAR_PATTERN = '/var\((--[a-z0-9-]+)\)/';

    /**
     * Tokens keyed by path.
     *
     * @var array<string, array{cssVar: string, type: string, tier: string, axis: ?string, varies: ?string, value: array<string, string>|string, resolved: array<string, string>|string}>
     */
    private array $tokens;

    /**
     * Mode axes keyed by name.
     *
     * @var array<string, array{attribute: string, default: string, modes: array<int, string>}>
     */
    private array $axes;

    /**
     * Paths keyed by custom property.
     *
     * @var array<string, string>
     */
    private array $paths = [];

    /**
     * Creates the map from the array the token build writes.
     *
     * @since 3.0.0
     *
     * @param  array{prefix?: string, layer?: string, axes: array<string, array{attribute: string, default: string, modes: array<int, string>}>, tokens: array<string, array<string, mixed>>}  $data
     *
     * @throws ThemeGeneratorException When the array is not a token map.
     */
    public function __construct(array $data)
    {
        if (! isset($data['tokens'], $data['axes']) || ! is_array($data['tokens'])) {
            throw new ThemeGeneratorException(__('The design token map is missing its tokens or axes.'));
        }

        $this->tokens = $data['tokens'];
        $this->axes   = $data['axes'];
        foreach ($this->tokens as $path => $token) {
            $this->paths[$token['cssVar']] = $path;
        }
    }

    /**
     * Loads the map from a PHP file, by default the copy shipped with the package.
     *
     * @since 3.0.0
     *
     * @param  string|null  $path  Path to a `tokens.php` build output.
     *
     * @throws ThemeGeneratorException When the file is missing.
     */
    public static function load(?string $path = null): self
    {
        $path ??= self::defaultPath();
        if (! is_file($path)) {
            throw new ThemeGeneratorException(__('Design token map not found at :path.', ['path' => $path]));
        }

        return new self(require $path);
    }

    /**
     * The token map shipped with the package.
     *
     * @since 3.0.0
     */
    public static function defaultPath(): string
    {
        return dirname(__DIR__, 2).'/resources/design-system/tokens.php';
    }

    /**
     * Every token, keyed by path, in build order.
     *
     * @since 3.0.0
     *
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->tokens;
    }

    /**
     * Whether a token exists.
     *
     * @since 3.0.0
     */
    public function has(string $path): bool
    {
        return isset($this->tokens[$path]);
    }

    /**
     * One token's definition.
     *
     * @since 3.0.0
     *
     * @throws ThemeGeneratorException When the token does not exist.
     *
     * @return array<string, mixed>
     */
    public function get(string $path): array
    {
        if (! isset($this->tokens[$path])) {
            throw new ThemeGeneratorException(__('Unknown design token :path.', ['path' => $path]));
        }

        return $this->tokens[$path];
    }

    /**
     * The mode axes, such as theme and density.
     *
     * @since 3.0.0
     *
     * @return array<string, array{attribute: string, default: string, modes: array<int, string>}>
     */
    public function axes(): array
    {
        return $this->axes;
    }

    /**
     * The modes of a token's axis, or `[null]` for a token with one value.
     *
     * @since 3.0.0
     *
     * @return array<int, string|null>
     */
    public function modesOf(string $path): array
    {
        $axis = $this->get($path)['axis'];

        return null === $axis ? [null] : $this->axes[$axis]['modes'];
    }

    /**
     * The token path for a custom property, such as `color.primary` for
     * `--apui-color-primary`.
     *
     * @since 3.0.0
     */
    public function pathFor(string $cssVar): ?string
    {
        return $this->paths[$cssVar] ?? null;
    }

    /**
     * A token's CSS value in a mode, with aliases as `var()`.
     *
     * @since 3.0.0
     */
    public function cssValue(string $path, ?string $mode = null): string
    {
        $value = $this->get($path)['value'];
        if (is_array($value)) {
            return $value[$mode ?? $this->axes[$this->get($path)['axis']]['default']];
        }

        return $value;
    }

    /**
     * The paths a CSS value references through `var()`.
     *
     * @since 3.0.0
     *
     * @return array<int, string>
     */
    public function referencesIn(string $css): array
    {
        preg_match_all(self::VAR_PATTERN, $css, $matches);

        return array_values(array_filter(array_map($this->pathFor(...), $matches[1])));
    }

    /**
     * Resolves a CSS value with `var()` references to a literal, in a mode
     * context such as `['theme' => 'dark']`. Missing axes use their default.
     *
     * @since 3.0.0
     *
     * @param  callable(string, string|null): string  $valueOf  Supplies a token's CSS value in a mode.
     * @param  array<string, string>  $context
     */
    public function resolveCss(string $css, callable $valueOf, array $context = []): string
    {
        return preg_replace_callback(self::VAR_PATTERN, function (array $match) use ($valueOf, $context): string {
            $path = $this->pathFor($match[1]);
            if (null === $path) {
                return $match[0];
            }
            $axis = $this->tokens[$path]['axis'];
            $mode = null === $axis ? null : ($context[$axis] ?? $this->axes[$axis]['default']);

            return $this->resolveCss($valueOf($path, $mode), $valueOf, $context);
        }, $css);
    }

    /**
     * A token's resolved CSS value in a mode context.
     *
     * @since 3.0.0
     *
     * @param  array<string, string>  $context
     */
    public function resolved(string $path, array $context = []): string
    {
        $axis = $this->get($path)['axis'];
        $mode = null === $axis ? null : ($context[$axis] ?? $this->axes[$axis]['default']);

        return $this->resolveCss($this->cssValue($path, $mode), $this->cssValue(...), $context);
    }
}
