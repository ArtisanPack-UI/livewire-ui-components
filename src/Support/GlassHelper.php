<?php

declare(strict_types=1);
/**
 * Glass Helper
 *
 * This class provides utility methods for generating glass effect CSS classes.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Support;

use function generateAccessibleTextColor;

/**
 * GlassHelper Class
 *
 * Provides utility methods for generating glass effect CSS classes and inline styles.
 *
 * @since 2.0.0
 */
class GlassHelper
{
    /**
     * Valid glass variant types.
     *
     * @since 2.0.0
     *
     * @var array<string>
     */
    protected static array $validVariants = ['frosted', 'liquid', 'transparent'];

    /**
     * Tailwind color names that have corresponding glass-tint-{color} classes.
     *
     * @since 2.0.0
     *
     * @var array<string>
     */
    protected static array $tailwindColors = [
        'primary',
        'secondary',
        'accent',
        'success',
        'warning',
        'error',
        'info',
        'slate',
        'gray',
        'zinc',
        'neutral',
        'stone',
        'red',
        'orange',
        'amber',
        'yellow',
        'lime',
        'green',
        'emerald',
        'teal',
        'cyan',
        'sky',
        'blue',
        'indigo',
        'violet',
        'purple',
        'fuchsia',
        'pink',
        'rose',
    ];

    /**
     * Generate CSS classes for glass effect.
     *
     * @since 2.0.0
     *
     * @param  string|null  $glass  The glass variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name (e.g., 'blue-500') or hex code (e.g., '#8b5cf6').
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     *
     * @return string Space-separated CSS classes.
     */
    public static function getClasses(
        ?string $glass = null,
        ?string $glassTint = null,
        ?int $glassTintOpacity = null,
    ): string {
        if (null === $glass) {
            return '';
        }

        $classes = [];

        // Add the main glass variant class
        if (in_array($glass, self::$validVariants, true)) {
            $classes[] = 'glass-'.$glass;
        }

        // Add tint class if it's a Tailwind color name
        if (null !== $glassTint && ! self::isHexColor($glassTint)) {
            $classes[] = 'glass-tint-'.$glassTint;
        }

        // Add custom tint class if it's a hex color
        if (null !== $glassTint && self::isHexColor($glassTint)) {
            $classes[] = 'glass-tint-custom';
        }

        // Add tint opacity class
        if (null !== $glassTintOpacity) {
            $opacity   = self::normalizeOpacity($glassTintOpacity);
            $classes[] = 'glass-tint-opacity-'.$opacity;
        }

        return implode(' ', $classes);
    }

    /**
     * Generate inline styles for custom tint colors.
     *
     * @since 2.0.0
     *
     * @param  string|null  $glassTint  Tailwind color name or hex code.
     *
     * @return string Inline style string or empty string.
     */
    public static function getInlineStyle(?string $glassTint = null): string
    {
        if (null === $glassTint || ! self::isHexColor($glassTint)) {
            return '';
        }

        return '--glass-tint-color: '.$glassTint.';';
    }

    /**
     * Check if a color value is a hex color.
     *
     * @since 2.0.0
     *
     * @param  string  $color  The color value to check.
     *
     * @return bool True if hex color, false otherwise.
     */
    public static function isHexColor(string $color): bool
    {
        return 1 === preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color);
    }

    /**
     * Normalize opacity value to valid increments (10, 20, 30, etc.).
     *
     * @since 2.0.0
     *
     * @param  int  $opacity  The opacity value (10-100).
     *
     * @return int Normalized opacity value.
     */
    public static function normalizeOpacity(int $opacity): int
    {
        // Clamp to valid range
        $opacity = max(10, min(100, $opacity));

        // Round to nearest 10
        return (int) (round($opacity / 10) * 10);
    }

    /**
     * Validate if a glass variant is valid.
     *
     * @since 2.0.0
     *
     * @param  string|null  $variant  The variant to validate.
     *
     * @return bool True if valid, false otherwise.
     */
    public static function isValidVariant(?string $variant): bool
    {
        return null !== $variant && in_array($variant, self::$validVariants, true);
    }

    /**
     * Get all valid glass variants.
     *
     * @since 2.0.0
     *
     * @return array<string> Array of valid variant names.
     */
    public static function getValidVariants(): array
    {
        return self::$validVariants;
    }

    /**
     * Generate accessible text color for tinted glass backgrounds.
     *
     * Uses the accessibility package to ensure text remains readable on tinted
     * glass backgrounds while maintaining WCAG 2.0 AA compliance (4.5:1 contrast ratio).
     *
     * @since 2.0.0
     *
     * @param  string|null  $glassTint  Tailwind color name (e.g., 'blue-500') or hex code (e.g., '#8b5cf6').
     * @param  bool  $useTintedVariant  Whether to use a tinted variant of the color (true) or black/white (false).
     *
     * @return string The accessible text color as a hex code, or empty string if no tint.
     */
    public static function getAccessibleTextColor(?string $glassTint = null, bool $useTintedVariant = true): string
    {
        if (null === $glassTint) {
            return '';
        }

        // Check if the function exists (accessibility package must be installed)
        if (! function_exists('generateAccessibleTextColor')) {
            return '';
        }

        return generateAccessibleTextColor($glassTint, $useTintedVariant);
    }

    /**
     * Generate inline style for accessible text color on tinted glass.
     *
     * Returns a CSS color property with an accessible text color calculated from
     * the glass tint color using the accessibility package.
     *
     * @since 2.0.0
     *
     * @param  string|null  $glassTint  Tailwind color name or hex code.
     * @param  bool  $useTintedVariant  Whether to use a tinted variant of the color.
     *
     * @return string Inline style string (e.g., "color: #000000;") or empty string.
     */
    public static function getAccessibleTextStyle(?string $glassTint = null, bool $useTintedVariant = true): string
    {
        $textColor = self::getAccessibleTextColor($glassTint, $useTintedVariant);

        if ('' === $textColor) {
            return '';
        }

        return 'color: '.$textColor.';';
    }

    /**
     * Get combined inline styles for glass effect including accessible text color.
     *
     * Combines the custom tint color CSS variable with accessible text color styling.
     *
     * @since 2.0.0
     *
     * @param  string|null  $glassTint  Tailwind color name or hex code.
     * @param  bool  $useTintedVariant  Whether to use a tinted variant for text color.
     *
     * @return string Combined inline styles or empty string.
     */
    public static function getFullInlineStyle(?string $glassTint = null, bool $useTintedVariant = true): string
    {
        $styles = [];

        // Add custom tint color variable for hex colors
        $tintStyle = self::getInlineStyle($glassTint);
        if ('' !== $tintStyle) {
            $styles[] = $tintStyle;
        }

        // Add accessible text color
        $textStyle = self::getAccessibleTextStyle($glassTint, $useTintedVariant);
        if ('' !== $textStyle) {
            $styles[] = $textStyle;
        }

        return implode(' ', $styles);
    }
}
