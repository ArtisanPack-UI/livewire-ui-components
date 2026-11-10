<?php

/**
 * Color math for the token-based theme generator.
 *
 * A port of the design system's `packages/tokens/scripts/lib/generator.mjs`
 * and `contrast.mjs`: sRGB and OKLCH conversion, WCAG luminance and contrast,
 * and luminance matching. It follows the JavaScript step for step so both
 * produce the same colors; the golden fixtures in tests/Fixtures check that.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;

/**
 * Color conversions and contrast.
 *
 * Colors are arrays of gamma-encoded sRGB channels from 0 to 1, optionally
 * with an alpha as the fourth item.
 *
 * @since 3.0.0
 */
final class ColorMath
{
    /**
     * Below this OKLCH chroma a color counts as gray and has no hue.
     */
    private const ACHROMATIC = 1e-4;

    /**
     * Tolerance for the sRGB gamut check.
     */
    private const EPSILON = 1e-7;

    /**
     * Parses `#rgb` or `#rrggbb`, with or without the hash.
     *
     * @since 3.0.0
     *
     * @param  string  $input  The hex color.
     *
     * @throws ThemeGeneratorException When the input is not a hex color.
     *
     * @return array{0: float, 1: float, 2: float} Channels from 0 to 1.
     */
    public static function parseHex(string $input): array
    {
        if (! preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', trim($input), $match)) {
            throw new ThemeGeneratorException(
                __(':input is not a hex color (#rgb or #rrggbb)', ['input' => '"'.$input.'"']),
            );
        }

        $hex = 3 === strlen($match[1])
            ? implode('', array_map(fn (string $c): string => $c.$c, str_split($match[1])))
            : $match[1];

        return [
            hexdec(substr($hex, 0, 2)) / 255,
            hexdec(substr($hex, 2, 2)) / 255,
            hexdec(substr($hex, 4, 2)) / 255,
        ];
    }

    /**
     * Formats channels as a lowercase `#rrggbb`.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $rgb  Channels from 0 to 1.
     */
    public static function toHex(array $rgb): string
    {
        return '#'.implode('', array_map(
            fn (float $c): string => str_pad(dechex((int) round($c * 255)), 2, '0', STR_PAD_LEFT),
            array_slice($rgb, 0, 3),
        ));
    }

    /**
     * Converts gamma-encoded sRGB to OKLCH.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $rgb  Channels from 0 to 1.
     *
     * @return array{0: float, 1: float, 2: float} Lightness, chroma and hue in degrees.
     */
    public static function srgbToOklch(array $rgb): array
    {
        [$r, $g, $b] = array_map(self::srgbToLinear(...), array_slice($rgb, 0, 3));
        $l           = self::cbrt(0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b);
        $m           = self::cbrt(0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b);
        $s           = self::cbrt(0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b);
        $lightness   = 0.2104542553 * $l + 0.793617785 * $m - 0.0040720468 * $s;
        $a           = 1.9779984951 * $l - 2.428592205 * $m + 0.4505937099 * $s;
        $bb          = 0.0259040371 * $l + 0.7827717662 * $m - 0.808675766 * $s;
        $chroma      = hypot($a, $bb);
        $hue         = $chroma < self::ACHROMATIC ? 0.0 : fmod((atan2($bb, $a) * 180 / M_PI) + 360, 360);

        return [$lightness, $chroma, $hue];
    }

    /**
     * Converts OKLCH to gamma-encoded sRGB, clamped to the gamut.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $lch  Lightness, chroma and hue.
     *
     * @return array{0: float, 1: float, 2: float}
     */
    public static function oklchToSrgb(array $lch): array
    {
        return array_map(
            fn (float $c): float => self::clamp(self::linearToSrgb($c)),
            self::oklchToLinear($lch),
        );
    }

    /**
     * WCAG relative luminance of gamma-encoded sRGB.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $rgb  Channels from 0 to 1.
     */
    public static function luminance(array $rgb): float
    {
        return self::luminanceOfLinear(array_map(self::srgbToLinear(...), array_slice($rgb, 0, 3)));
    }

    /**
     * The WCAG contrast ratio of two opaque colors.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     */
    public static function contrast(array $a, array $b): float
    {
        $first  = self::luminance($a);
        $second = self::luminance($b);

        return (max($first, $second) + 0.05) / (min($first, $second) + 0.05);
    }

    /**
     * Composites a translucent color over an opaque backdrop.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $top  Channels and alpha.
     * @param  array<int, float>  $backdrop  Opaque channels.
     *
     * @return array{0: float, 1: float, 2: float, 3: float}
     */
    public static function over(array $top, array $backdrop): array
    {
        $alpha = $top[3] ?? 1.0;

        return [
            $top[0] * $alpha + $backdrop[0] * (1 - $alpha),
            $top[1] * $alpha + $backdrop[1] * (1 - $alpha),
            $top[2] * $alpha + $backdrop[2] * (1 - $alpha),
            1.0,
        ];
    }

    /**
     * The 8-bit sRGB color with hue `$hue` and at most chroma `$chroma` whose
     * relative luminance is closest to `$target`.
     *
     * @since 3.0.0
     *
     * @return array{0: float, 1: float, 2: float}
     */
    public static function matchLuminance(float $target, float $chroma, float $hue): array
    {
        $low  = 0.0;
        $high = 1.0;
        for ($i = 0; $i < 50; $i++) {
            $mid = ($low + $high) / 2;
            if (self::luminanceOfLinear(self::fitGamut($mid, $chroma, $hue)) < $target) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        $encoded = array_map(
            fn (float $x): float => self::linearToSrgb(max(0.0, $x)) * 255,
            self::fitGamut(($low + $high) / 2, $chroma, $hue),
        );

        // A gray rounds all three channels together, so it stays a pure gray.
        $gray      = $chroma < 1e-6;
        $best      = null;
        $bestError = INF;
        for ($mask = 0; $mask < ($gray ? 2 : 8); $mask++) {
            $rgb = [];
            foreach ($encoded as $i => $x) {
                $bit     = $gray ? 1 : 1 << $i;
                $rounded = ($mask & $bit) ? ceil($x) : floor($x);
                $rgb[]   = min(255.0, max(0.0, $rounded)) / 255;
            }
            $error = abs(self::luminance($rgb) - $target);
            if (null === $best || $error < $bestError) {
                $best      = $rgb;
                $bestError = $error;
            }
        }

        return $best;
    }

    /**
     * Parses a color as the token build writes it: `#rrggbb`, `rgb(r g b / a)`
     * or `oklch(l% c h)` with an optional `/ a`.
     *
     * @since 3.0.0
     *
     * @param  string  $css  The CSS color.
     *
     * @throws ThemeGeneratorException When the color has another form.
     *
     * @return array{0: float, 1: float, 2: float, 3: float} Channels and alpha.
     */
    public static function parseCss(string $css): array
    {
        $css = trim($css);
        if (preg_match('/^#[0-9a-f]{6}$/i', $css)) {
            return [...self::parseHex($css), 1.0];
        }
        if (preg_match('/^rgb\((\d+) (\d+) (\d+)(?: \/ ([\d.]+))?\)$/', $css, $m)) {
            return [(float) $m[1] / 255, (float) $m[2] / 255, (float) $m[3] / 255, isset($m[4]) ? (float) $m[4] : 1.0];
        }
        if (preg_match('/^oklch\(([\d.]+)% ([\d.]+) ([\d.]+)(?: \/ ([\d.]+))?\)$/', $css, $m)) {
            return [...self::oklchToSrgb([(float) $m[1] / 100, (float) $m[2], (float) $m[3]]), isset($m[4]) ? (float) $m[4] : 1.0];
        }

        throw new ThemeGeneratorException(__('Unsupported color :color', ['color' => $css]));
    }

    /**
     * Formats a number as the token build does: at most four decimals, no
     * trailing zeros.
     *
     * @since 3.0.0
     */
    public static function formatNumber(float $value, int $digits = 4): string
    {
        $text = rtrim(rtrim(number_format(round($value, $digits), $digits, '.', ''), '0'), '.');

        return '-0' === $text ? '0' : $text;
    }

    /**
     * The color at lightness `$lightness` and hue `$hue`, with chroma reduced
     * until it fits sRGB. Returns linear channels.
     *
     * @return array{0: float, 1: float, 2: float}
     */
    private static function fitGamut(float $lightness, float $chroma, float $hue): array
    {
        $linear = self::oklchToLinear([$lightness, $chroma, $hue]);
        if (self::inGamut($linear)) {
            return $linear;
        }

        $low  = 0.0;
        $high = $chroma;
        for ($i = 0; $i < 30; $i++) {
            $mid = ($low + $high) / 2;
            if (self::inGamut(self::oklchToLinear([$lightness, $mid, $hue]))) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return array_map(
            fn (float $x): float => min(1.0, max(0.0, $x)),
            self::oklchToLinear([$lightness, $low, $hue]),
        );
    }

    /**
     * OKLCH to linear sRGB, unclamped.
     *
     * @param  array<int, float>  $lch
     *
     * @return array{0: float, 1: float, 2: float}
     */
    private static function oklchToLinear(array $lch): array
    {
        [$lightness, $chroma, $hue] = $lch;
        $rad                        = $hue * M_PI / 180;
        $a                          = $chroma * cos($rad);
        $b                          = $chroma * sin($rad);
        $l                          = ($lightness + 0.3963377774 * $a + 0.2158037573 * $b) ** 3;
        $m                          = ($lightness - 0.1055613458 * $a - 0.0638541728 * $b) ** 3;
        $s                          = ($lightness - 0.0894841775 * $a - 1.291485548 * $b) ** 3;

        return [
            4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $s,
            -1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $s,
            -0.0041960863 * $l - 0.7034186147 * $m + 1.707614701 * $s,
        ];
    }

    /**
     * @param  array<int, float>  $linear
     */
    private static function inGamut(array $linear): bool
    {
        foreach ($linear as $c) {
            if ($c < -self::EPSILON || $c > 1 + self::EPSILON) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<int, float>  $linear
     */
    private static function luminanceOfLinear(array $linear): float
    {
        return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
    }

    private static function srgbToLinear(float $c): float
    {
        return $c <= 0.04045 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;
    }

    private static function linearToSrgb(float $c): float
    {
        return $c <= 0.0031308 ? 12.92 * $c : 1.055 * $c ** (1 / 2.4) - 0.055;
    }

    private static function clamp(float $c): float
    {
        return min(1.0, max(0.0, $c));
    }

    /**
     * Cube root, refined with one Newton step so it matches JavaScript's
     * Math.cbrt to the last bit in practice.
     */
    private static function cbrt(float $x): float
    {
        if (0.0 === $x) {
            return 0.0;
        }
        $sign = $x < 0 ? -1.0 : 1.0;
        $abs  = abs($x);
        $y    = $abs ** (1 / 3);
        $y -= ($y * $y * $y - $abs) / (3 * $y * $y);

        return $sign * $y;
    }
}
