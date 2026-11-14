<?php

/**
 * WCAG contrast checks for themes and presets.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

/**
 * The WCAG 2.2 AA pairs every theme and preset must pass, ported from the
 * design system's `contrast-pairs.mjs`. Text pairs need 4.5:1; control edges,
 * focus rings and non-text marks need 3:1. Glass text is checked over black,
 * white, red and blue backdrops.
 *
 * @since 3.0.0
 */
final class ContrastChecker
{
    /**
     * Surfaces text and controls sit on.
     */
    public const SURFACES = ['surface.sunken', 'surface.page', 'surface.default', 'surface.raised', 'surface.overlay'];

    /**
     * The semantic color roles.
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    /**
     * Syntax highlighting roles, checked on the sunken surface.
     */
    public const SYNTAX = ['keyword', 'string', 'number', 'function', 'property', 'comment'];

    /**
     * Backdrops glass text is checked over.
     */
    public const BACKDROPS = [
        'black' => [0.0, 0.0, 0.0, 1.0],
        'white' => [1.0, 1.0, 1.0, 1.0],
        'red'   => [1.0, 0.0, 0.0, 1.0],
        'blue'  => [0.0, 0.0, 1.0, 1.0],
    ];

    /**
     * Text on `surface.glass`, with its minimum ratio.
     */
    public const GLASS_PAIRS = [['text.default', 4.5], ['text.muted-glass', 4.5]];

    /**
     * Every opaque pair as `[foreground, background, minimum ratio]`.
     *
     * @since 3.0.0
     *
     * @return array<int, array{0: string, 1: string, 2: float}>
     */
    public static function pairs(): array
    {
        $pairs = [];
        foreach (self::SURFACES as $surface) {
            $pairs[] = ['text.default', $surface, 4.5];
            $pairs[] = ['text.muted', $surface, 4.5];
            $pairs[] = ['border.default', $surface, 3.0];
            $pairs[] = ['focus-ring.color', $surface, 3.0];
            foreach (self::COLORS as $color) {
                $pairs[] = ["color.{$color}-text", $surface, 4.5];
                $pairs[] = ["color.{$color}-border", $surface, 3.0];
            }
        }
        $pairs[] = ['text.inverse', 'surface.inverse', 4.5];
        $pairs[] = ['text.default', 'selected.bg', 4.5];
        foreach (self::COLORS as $color) {
            $pairs[] = ["color.{$color}-foreground", "color.{$color}", 4.5];
            $pairs[] = ["color.{$color}-soft-foreground", "color.{$color}-soft", 4.5];
        }
        foreach (self::SYNTAX as $token) {
            $pairs[] = ["syntax.{$token}", 'surface.sunken', 4.5];
        }

        return $pairs;
    }

    /**
     * Every failing pair in every theme. An empty list means everything passes.
     *
     * @since 3.0.0
     *
     * @param  callable(string, string): string  $resolve  Resolves a token path to a CSS color in a theme.
     * @param  array<int, string>  $themes  The theme modes to check.
     *
     * @return array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>
     */
    public static function check(callable $resolve, array $themes): array
    {
        $failures = [];
        foreach ($themes as $theme) {
            $color = fn (string $path): array => ColorMath::parseCss($resolve($path, $theme));
            foreach (self::pairs() as [$fg, $bg, $min]) {
                $ratio = self::ratio($color($fg), $color($bg), self::BACKDROPS['white']);
                if ($ratio < $min) {
                    $failures[] = ['theme' => $theme, 'fg' => $fg, 'bg' => $bg, 'backdrop' => null, 'ratio' => $ratio, 'min' => $min];
                }
            }
            foreach (self::BACKDROPS as $name => $backdrop) {
                foreach (self::GLASS_PAIRS as [$fg, $min]) {
                    $ratio = self::ratio($color($fg), $color('surface.glass'), $backdrop);
                    if ($ratio < $min) {
                        $failures[] = ['theme' => $theme, 'fg' => $fg, 'bg' => 'surface.glass', 'backdrop' => $name, 'ratio' => $ratio, 'min' => $min];
                    }
                }
            }
        }

        return $failures;
    }

    /**
     * One line per failure, for messages.
     *
     * @since 3.0.0
     *
     * @param  array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>  $failures
     *
     * @return array<int, string>
     */
    public static function describe(array $failures): array
    {
        return array_map(fn (array $f): string => __(':theme: :fg on :bg:backdrop is :ratio:1, needs :min:1', [
            'theme'    => $f['theme'],
            'fg'       => $f['fg'],
            'bg'       => $f['bg'],
            'backdrop' => null === $f['backdrop'] ? '' : ' '.__('over :backdrop', ['backdrop' => $f['backdrop']]),
            'ratio'    => number_format($f['ratio'], 2),
            'min'      => $f['min'],
        ]), $failures);
    }

    /**
     * Contrast of a foreground on a background, compositing translucent
     * colors over what sits below them.
     *
     * @param  array<int, float>  $fg
     * @param  array<int, float>  $bg
     * @param  array<int, float>  $backdrop
     */
    private static function ratio(array $fg, array $bg, array $backdrop): float
    {
        $background = ColorMath::over($bg, $backdrop);

        return ColorMath::contrast(ColorMath::over($fg, $background), $background);
    }
}
