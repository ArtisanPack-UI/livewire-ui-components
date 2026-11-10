<?php

/**
 * The glass surface from the design tokens.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;

/**
 * Glass is a surface, not a theme or preset (D-006): `data-surface="glass"` on
 * a container component. Its look comes from the glass tokens, with an 80%
 * opacity floor and its own muted text color so text keeps 4.5:1 over any
 * backdrop. It falls back to the raised surface under reduced transparency,
 * without backdrop-filter support and in high contrast.
 *
 * This class replaces GlassPresets, whose named presets set arbitrary blur,
 * tint and opacity values that couldn't guarantee contrast.
 *
 * @since 3.0.0
 */
final class GlassSurface
{
    /**
     * The components that accept the glass surface (D-006), in kebab-case.
     */
    public const COMPONENTS = ['card', 'dialog', 'dropdown-menu', 'toast', 'navbar', 'alert', 'table'];

    /**
     * The tokens the glass surface reads.
     */
    public const TOKENS = [
        'surface.glass',
        'surface.glass-border',
        'surface.glass-blur',
        'surface.glass-blur-sm',
        'surface.glass-saturation',
        'text.muted-glass',
        'elevation.glass',
    ];

    /**
     * Creates the helper.
     *
     * @since 3.0.0
     *
     * @param  DesignTokens  $tokens  The design tokens.
     */
    public function __construct(private readonly DesignTokens $tokens) {}

    /**
     * Creates the helper for the token map shipped with the package.
     *
     * @since 3.0.0
     */
    public static function make(): self
    {
        return new self(DesignTokens::load());
    }

    /**
     * Whether a component accepts the glass surface.
     *
     * @since 3.0.0
     */
    public function supports(string $component): bool
    {
        return in_array($component, self::COMPONENTS, true);
    }

    /**
     * The attribute that puts a supported component on glass.
     *
     * @since 3.0.0
     *
     * @throws ThemeGeneratorException When the component has no glass surface.
     *
     * @return array{data-surface: string}
     */
    public function attributes(string $component): array
    {
        if (! $this->supports($component)) {
            throw new ThemeGeneratorException(__(':component has no glass surface. Glass works on: :components.', [
                'component'  => $component,
                'components' => implode(', ', self::COMPONENTS),
            ]));
        }

        return ['data-surface' => 'glass'];
    }

    /**
     * The glass tokens as custom property => resolved value, in one theme.
     *
     * @since 3.0.0
     *
     * @return array<string, string>
     */
    public function tokens(string $theme = 'light'): array
    {
        $values = [];
        foreach (self::TOKENS as $path) {
            if ($this->tokens->has($path)) {
                $values[$this->tokens->get($path)['cssVar']] = $this->tokens->resolved($path, ['theme' => $theme]);
            }
        }

        return $values;
    }
}
