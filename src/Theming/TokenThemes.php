<?php

/**
 * Themes from the design tokens.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;

/**
 * Light, dark and high-contrast themes from the design tokens (#23).
 *
 * The themes are part of the token stylesheet and switch with `data-theme` on
 * any element, so there is no theme CSS to generate. This class replaces
 * HighContrastTheme: it gives the attributes, checks contrast, and writes the
 * one thing the attribute can't do on its own, following the operating
 * system's preference when the page sets no theme.
 *
 * @since 3.0.0
 */
final class TokenThemes
{
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
     * The theme modes, such as light, dark and high-contrast.
     *
     * @since 3.0.0
     *
     * @return array<int, string>
     */
    public function themes(): array
    {
        return $this->tokens->axes()['theme']['modes'];
    }

    /**
     * The attributes that switch theme, density and preset on an element.
     *
     * @since 3.0.0
     *
     * @throws ThemeGeneratorException When a theme or density does not exist.
     *
     * @return array<string, string>
     */
    public function attributes(?string $theme = null, ?string $density = null, ?string $preset = null): array
    {
        $attributes = [];
        foreach (['theme' => $theme, 'density' => $density] as $axis => $mode) {
            if (null === $mode) {
                continue;
            }
            $config = $this->tokens->axes()[$axis];
            if (! in_array($mode, $config['modes'], true)) {
                throw new ThemeGeneratorException(__('Unknown :axis :mode. Use one of: :modes.', [
                    'axis'  => $axis,
                    'mode'  => $mode,
                    'modes' => implode(', ', $config['modes']),
                ]));
            }
            $attributes[$config['attribute']] = $mode;
        }
        if (null !== $preset) {
            $attributes[GeneratedPreset::ATTRIBUTE] = $preset;
        }

        return $attributes;
    }

    /**
     * Contrast failures for one theme or all of them. The design tokens are
     * tested to pass, so this is empty unless the token map was edited.
     *
     * @since 3.0.0
     *
     * @return array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>
     */
    public function failures(?string $theme = null): array
    {
        return ContrastChecker::check(
            fn (string $path, string $mode): string => $this->tokens->resolved($path, ['theme' => $mode]),
            null === $theme ? $this->themes() : [$theme],
        );
    }

    /**
     * CSS that applies a theme when a media query matches and the page has
     * set no theme of its own, for example the high-contrast theme under
     * `prefers-contrast: more`. An explicit `data-theme` always wins.
     *
     * @since 3.0.0
     *
     * @throws ThemeGeneratorException When the theme does not exist.
     */
    public function mediaCss(string $theme, string $media): string
    {
        $this->attributes($theme);
        $declarations = [];
        foreach ($this->tokens->all() as $path => $token) {
            if ('theme' === $token['axis']) {
                $declarations[] = $token['cssVar'].': '.$this->tokens->cssValue($path, $theme).';';
            } elseif (null === $token['axis'] && 'theme' === $token['varies']) {
                $declarations[] = $token['cssVar'].': '.$this->tokens->cssValue($path).';';
            }
        }

        return "@layer apui.tokens {\n  @media {$media} {\n    :root:not([data-theme]) {\n"
            .implode("\n", array_map(fn (string $line): string => '      '.$line, $declarations))
            ."\n    }\n  }\n}\n";
    }

    /**
     * The high-contrast theme for people whose system asks for more contrast.
     *
     * @since 3.0.0
     */
    public function prefersContrastCss(): string
    {
        return $this->mediaCss('high-contrast', '(prefers-contrast: more)');
    }

    /**
     * The dark theme for people whose system prefers dark.
     *
     * @since 3.0.0
     */
    public function prefersDarkCss(): string
    {
        return $this->mediaCss('dark', '(prefers-color-scheme: dark)');
    }
}
