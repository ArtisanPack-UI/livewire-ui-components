<?php

/**
 * High Contrast Theme for ArtisanPack UI.
 *
 * Provides accessibility-focused high-contrast theme presets that comply with
 * WCAG AAA standards. Includes support for prefers-contrast media query,
 * enhanced focus indicators, reduced motion, and larger text options.
 *
 *
 * @since      2.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Styling;

/**
 * High Contrast Theme class.
 *
 * Generates CSS for high-contrast accessibility themes with WCAG AAA compliance.
 *
 * @since 2.0.0
 */
class HighContrastTheme
{
    /**
     * Preset name for high contrast light mode.
     *
     * @var string
     */
    public const PRESET_HIGH_CONTRAST_LIGHT = 'high-contrast-light';

    /**
     * Preset name for high contrast dark mode.
     *
     * @var string
     */
    public const PRESET_HIGH_CONTRAST_DARK = 'high-contrast-dark';

    /**
     * Preset name for enhanced contrast light mode.
     *
     * @var string
     */
    public const PRESET_ENHANCED_LIGHT = 'enhanced-contrast-light';

    /**
     * Preset name for enhanced contrast dark mode.
     *
     * @var string
     */
    public const PRESET_ENHANCED_DARK = 'enhanced-contrast-dark';

    /**
     * Minimum contrast ratio for WCAG AAA normal text (7:1).
     *
     * @var float
     */
    public const WCAG_AAA_NORMAL_TEXT = 7.0;

    /**
     * Minimum contrast ratio for WCAG AAA large text (4.5:1).
     *
     * @var float
     */
    public const WCAG_AAA_LARGE_TEXT = 4.5;

    /**
     * Minimum contrast ratio for WCAG AA normal text (4.5:1).
     *
     * @var float
     */
    public const WCAG_AA_NORMAL_TEXT = 4.5;

    /**
     * High contrast light preset tokens.
     *
     * Optimized for maximum readability on light backgrounds with WCAG AAA compliance.
     *
     * @var array<string, string>
     */
    protected array $highContrastLight = [
        // Base colors - maximum contrast
        'hc-background'           => '#ffffff',
        'hc-foreground'           => '#000000',
        'hc-foreground-muted'     => '#1a1a1a',

        // Primary colors - high contrast variants
        'hc-primary'              => '#0000cc',
        'hc-primary-foreground'   => '#ffffff',
        'hc-primary-hover'        => '#000099',
        'hc-primary-active'       => '#000066',

        // Secondary colors
        'hc-secondary'            => '#2d2d2d',
        'hc-secondary-foreground' => '#ffffff',
        'hc-secondary-hover'      => '#1a1a1a',
        'hc-secondary-active'     => '#000000',

        // Accent colors
        'hc-accent'               => '#7a4b00',
        'hc-accent-foreground'    => '#ffffff',
        'hc-accent-hover'         => '#5c3800',
        'hc-accent-active'        => '#3d2500',

        // Semantic colors
        'hc-success'              => '#005c00',
        'hc-success-foreground'   => '#ffffff',
        'hc-warning'              => '#664400',
        'hc-warning-foreground'   => '#ffffff',
        'hc-error'                => '#990000',
        'hc-error-foreground'     => '#ffffff',
        'hc-info'                 => '#003366',
        'hc-info-foreground'      => '#ffffff',

        // Border colors
        'hc-border'               => '#000000',
        'hc-border-muted'         => '#333333',
        'hc-border-focus'         => '#0000cc',

        // Surface colors
        'hc-surface'              => '#f5f5f5',
        'hc-surface-elevated'     => '#ffffff',
        'hc-surface-sunken'       => '#e5e5e5',

        // Focus indicators
        'hc-focus-ring-color'     => '#0000cc',
        'hc-focus-ring-width'     => '3px',
        'hc-focus-ring-offset'    => '2px',
        'hc-focus-ring-style'     => 'solid',

        // Text sizing
        'hc-text-scale'           => '1',
        'hc-text-base'            => '1rem',
        'hc-text-sm'              => '0.9375rem',
        'hc-text-lg'              => '1.1875rem',
        'hc-text-xl'              => '1.375rem',

        // Line height for readability
        'hc-line-height'          => '1.6',
        'hc-line-height-tight'    => '1.4',
        'hc-line-height-loose'    => '1.8',

        // Letter spacing
        'hc-letter-spacing'       => '0.01em',
        'hc-letter-spacing-wide'  => '0.025em',

        // Interactive element sizing
        'hc-min-target-size'      => '44px',
        'hc-min-tap-target'       => '48px',

        // Disabled state
        'hc-disabled-opacity'     => '0.6',
        'hc-disabled-background'  => '#cccccc',
        'hc-disabled-foreground'  => '#555555',
    ];

    /**
     * High contrast dark preset tokens.
     *
     * Optimized for maximum readability on dark backgrounds with WCAG AAA compliance.
     *
     * @var array<string, string>
     */
    protected array $highContrastDark = [
        // Base colors - maximum contrast
        'hc-background'           => '#000000',
        'hc-foreground'           => '#ffffff',
        'hc-foreground-muted'     => '#e5e5e5',

        // Primary colors - high contrast variants
        'hc-primary'              => '#6699ff',
        'hc-primary-foreground'   => '#000000',
        'hc-primary-hover'        => '#99bbff',
        'hc-primary-active'       => '#ccddff',

        // Secondary colors
        'hc-secondary'            => '#d1d1d1',
        'hc-secondary-foreground' => '#000000',
        'hc-secondary-hover'      => '#e5e5e5',
        'hc-secondary-active'     => '#ffffff',

        // Accent colors
        'hc-accent'               => '#ffcc66',
        'hc-accent-foreground'    => '#000000',
        'hc-accent-hover'         => '#ffdd99',
        'hc-accent-active'        => '#ffeebb',

        // Semantic colors
        'hc-success'              => '#66ff66',
        'hc-success-foreground'   => '#000000',
        'hc-warning'              => '#ffcc00',
        'hc-warning-foreground'   => '#000000',
        'hc-error'                => '#ff6666',
        'hc-error-foreground'     => '#000000',
        'hc-info'                 => '#66ccff',
        'hc-info-foreground'      => '#000000',

        // Border colors
        'hc-border'               => '#ffffff',
        'hc-border-muted'         => '#cccccc',
        'hc-border-focus'         => '#6699ff',

        // Surface colors
        'hc-surface'              => '#1a1a1a',
        'hc-surface-elevated'     => '#2d2d2d',
        'hc-surface-sunken'       => '#0d0d0d',

        // Focus indicators
        'hc-focus-ring-color'     => '#6699ff',
        'hc-focus-ring-width'     => '3px',
        'hc-focus-ring-offset'    => '2px',
        'hc-focus-ring-style'     => 'solid',

        // Text sizing
        'hc-text-scale'           => '1',
        'hc-text-base'            => '1rem',
        'hc-text-sm'              => '0.9375rem',
        'hc-text-lg'              => '1.1875rem',
        'hc-text-xl'              => '1.375rem',

        // Line height for readability
        'hc-line-height'          => '1.6',
        'hc-line-height-tight'    => '1.4',
        'hc-line-height-loose'    => '1.8',

        // Letter spacing
        'hc-letter-spacing'       => '0.01em',
        'hc-letter-spacing-wide'  => '0.025em',

        // Interactive element sizing
        'hc-min-target-size'      => '44px',
        'hc-min-tap-target'       => '48px',

        // Disabled state
        'hc-disabled-opacity'     => '0.6',
        'hc-disabled-background'  => '#444444',
        'hc-disabled-foreground'  => '#999999',
    ];

    /**
     * Enhanced contrast light preset tokens.
     *
     * Provides improved contrast (WCAG AA+) without full high contrast intensity.
     *
     * @var array<string, string>
     */
    protected array $enhancedContrastLight = [
        // Base colors - enhanced contrast
        'hc-background'           => '#ffffff',
        'hc-foreground'           => '#1a1a1a',
        'hc-foreground-muted'     => '#333333',

        // Primary colors
        'hc-primary'              => '#1a56db',
        'hc-primary-foreground'   => '#ffffff',
        'hc-primary-hover'        => '#1648b8',
        'hc-primary-active'       => '#123a96',

        // Secondary colors
        'hc-secondary'            => '#4b5563',
        'hc-secondary-foreground' => '#ffffff',
        'hc-secondary-hover'      => '#374151',
        'hc-secondary-active'     => '#1f2937',

        // Accent colors
        'hc-accent'               => '#b45309',
        'hc-accent-foreground'    => '#ffffff',
        'hc-accent-hover'         => '#92400e',
        'hc-accent-active'        => '#78350f',

        // Semantic colors
        'hc-success'              => '#15803d',
        'hc-success-foreground'   => '#ffffff',
        'hc-warning'              => '#a16207',
        'hc-warning-foreground'   => '#ffffff',
        'hc-error'                => '#b91c1c',
        'hc-error-foreground'     => '#ffffff',
        'hc-info'                 => '#0369a1',
        'hc-info-foreground'      => '#ffffff',

        // Border colors
        'hc-border'               => '#374151',
        'hc-border-muted'         => '#6b7280',
        'hc-border-focus'         => '#1a56db',

        // Surface colors
        'hc-surface'              => '#f9fafb',
        'hc-surface-elevated'     => '#ffffff',
        'hc-surface-sunken'       => '#f3f4f6',

        // Focus indicators
        'hc-focus-ring-color'     => '#1a56db',
        'hc-focus-ring-width'     => '2px',
        'hc-focus-ring-offset'    => '2px',
        'hc-focus-ring-style'     => 'solid',

        // Text sizing
        'hc-text-scale'           => '1',
        'hc-text-base'            => '1rem',
        'hc-text-sm'              => '0.875rem',
        'hc-text-lg'              => '1.125rem',
        'hc-text-xl'              => '1.25rem',

        // Line height
        'hc-line-height'          => '1.5',
        'hc-line-height-tight'    => '1.35',
        'hc-line-height-loose'    => '1.75',

        // Letter spacing
        'hc-letter-spacing'       => '0.005em',
        'hc-letter-spacing-wide'  => '0.02em',

        // Interactive element sizing
        'hc-min-target-size'      => '44px',
        'hc-min-tap-target'       => '44px',

        // Disabled state
        'hc-disabled-opacity'     => '0.5',
        'hc-disabled-background'  => '#e5e7eb',
        'hc-disabled-foreground'  => '#6b7280',
    ];

    /**
     * Enhanced contrast dark preset tokens.
     *
     * Provides improved contrast (WCAG AA+) for dark mode without full high contrast intensity.
     *
     * @var array<string, string>
     */
    protected array $enhancedContrastDark = [
        // Base colors - enhanced contrast
        'hc-background'           => '#111827',
        'hc-foreground'           => '#f9fafb',
        'hc-foreground-muted'     => '#e5e7eb',

        // Primary colors
        'hc-primary'              => '#60a5fa',
        'hc-primary-foreground'   => '#1e3a8a',
        'hc-primary-hover'        => '#93c5fd',
        'hc-primary-active'       => '#bfdbfe',

        // Secondary colors
        'hc-secondary'            => '#d1d5db',
        'hc-secondary-foreground' => '#1f2937',
        'hc-secondary-hover'      => '#e5e7eb',
        'hc-secondary-active'     => '#f3f4f6',

        // Accent colors
        'hc-accent'               => '#fbbf24',
        'hc-accent-foreground'    => '#78350f',
        'hc-accent-hover'         => '#fcd34d',
        'hc-accent-active'        => '#fde68a',

        // Semantic colors
        'hc-success'              => '#4ade80',
        'hc-success-foreground'   => '#14532d',
        'hc-warning'              => '#facc15',
        'hc-warning-foreground'   => '#713f12',
        'hc-error'                => '#f87171',
        'hc-error-foreground'     => '#7f1d1d',
        'hc-info'                 => '#38bdf8',
        'hc-info-foreground'      => '#0c4a6e',

        // Border colors
        'hc-border'               => '#d1d5db',
        'hc-border-muted'         => '#9ca3af',
        'hc-border-focus'         => '#60a5fa',

        // Surface colors
        'hc-surface'              => '#1f2937',
        'hc-surface-elevated'     => '#374151',
        'hc-surface-sunken'       => '#0f172a',

        // Focus indicators
        'hc-focus-ring-color'     => '#60a5fa',
        'hc-focus-ring-width'     => '2px',
        'hc-focus-ring-offset'    => '2px',
        'hc-focus-ring-style'     => 'solid',

        // Text sizing
        'hc-text-scale'           => '1',
        'hc-text-base'            => '1rem',
        'hc-text-sm'              => '0.875rem',
        'hc-text-lg'              => '1.125rem',
        'hc-text-xl'              => '1.25rem',

        // Line height
        'hc-line-height'          => '1.5',
        'hc-line-height-tight'    => '1.35',
        'hc-line-height-loose'    => '1.75',

        // Letter spacing
        'hc-letter-spacing'       => '0.005em',
        'hc-letter-spacing-wide'  => '0.02em',

        // Interactive element sizing
        'hc-min-target-size'      => '44px',
        'hc-min-tap-target'       => '44px',

        // Disabled state
        'hc-disabled-opacity'     => '0.5',
        'hc-disabled-background'  => '#374151',
        'hc-disabled-foreground'  => '#9ca3af',
    ];

    /**
     * Reduced motion tokens.
     *
     * Applied when prefers-reduced-motion is enabled.
     *
     * @var array<string, string>
     */
    protected array $reducedMotion = [
        'hc-animation-duration'   => '0.01ms',
        'hc-transition-duration'  => '0.01ms',
        'hc-animation-iteration'  => '1',
        'hc-scroll-behavior'      => 'auto',
    ];

    /**
     * Larger text tokens.
     *
     * Scaled typography for improved readability.
     *
     * @var array<string, string>
     */
    protected array $largerText = [
        'hc-text-scale'           => '1.125',
        'hc-text-base'            => '1.125rem',
        'hc-text-sm'              => '1rem',
        'hc-text-lg'              => '1.3125rem',
        'hc-text-xl'              => '1.5rem',
        'hc-text-2xl'             => '1.75rem',
        'hc-text-3xl'             => '2.125rem',
        'hc-line-height'          => '1.7',
    ];

    /**
     * Extra large text tokens.
     *
     * Even larger typography for maximum readability.
     *
     * @var array<string, string>
     */
    protected array $extraLargeText = [
        'hc-text-scale'           => '1.25',
        'hc-text-base'            => '1.25rem',
        'hc-text-sm'              => '1.125rem',
        'hc-text-lg'              => '1.5rem',
        'hc-text-xl'              => '1.75rem',
        'hc-text-2xl'             => '2rem',
        'hc-text-3xl'             => '2.5rem',
        'hc-line-height'          => '1.8',
    ];

    /**
     * Get list of all available presets.
     *
     * @since 2.0.0
     *
     * @return array<string> List of preset names.
     */
    public function getAvailablePresets(): array
    {
        return [
            self::PRESET_HIGH_CONTRAST_LIGHT,
            self::PRESET_HIGH_CONTRAST_DARK,
            self::PRESET_ENHANCED_LIGHT,
            self::PRESET_ENHANCED_DARK,
        ];
    }

    /**
     * Check if a preset name is valid.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name to validate.
     *
     * @return bool True if valid, false otherwise.
     */
    public function isValidPreset(string $presetName): bool
    {
        return in_array($presetName, $this->getAvailablePresets(), true);
    }

    /**
     * Get tokens for a specific preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return array<string, string> The preset tokens.
     */
    public function getPresetTokens(string $presetName): array
    {
        return match ($presetName) {
            self::PRESET_HIGH_CONTRAST_LIGHT => $this->highContrastLight,
            self::PRESET_HIGH_CONTRAST_DARK  => $this->highContrastDark,
            self::PRESET_ENHANCED_LIGHT      => $this->enhancedContrastLight,
            self::PRESET_ENHANCED_DARK       => $this->enhancedContrastDark,
            default                          => [],
        };
    }

    /**
     * Get the preset description.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return string The preset description.
     */
    public function getPresetDescription(string $presetName): string
    {
        return match ($presetName) {
            self::PRESET_HIGH_CONTRAST_LIGHT => __('Maximum contrast for light backgrounds (WCAG AAA compliant)'),
            self::PRESET_HIGH_CONTRAST_DARK  => __('Maximum contrast for dark backgrounds (WCAG AAA compliant)'),
            self::PRESET_ENHANCED_LIGHT      => __('Enhanced contrast for light backgrounds (WCAG AA+ compliant)'),
            self::PRESET_ENHANCED_DARK       => __('Enhanced contrast for dark backgrounds (WCAG AA+ compliant)'),
            default                          => '',
        };
    }

    /**
     * Get the WCAG compliance level for a preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return string The WCAG compliance level ('AAA', 'AA', or 'none').
     */
    public function getComplianceLevel(string $presetName): string
    {
        return match ($presetName) {
            self::PRESET_HIGH_CONTRAST_LIGHT, self::PRESET_HIGH_CONTRAST_DARK => 'AAA',
            self::PRESET_ENHANCED_LIGHT, self::PRESET_ENHANCED_DARK           => 'AA',
            default                                                           => 'none',
        };
    }

    /**
     * Get the mode (light/dark) for a preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return string The mode ('light', 'dark', or 'unknown').
     */
    public function getPresetMode(string $presetName): string
    {
        return match ($presetName) {
            self::PRESET_HIGH_CONTRAST_LIGHT, self::PRESET_ENHANCED_LIGHT => 'light',
            self::PRESET_HIGH_CONTRAST_DARK, self::PRESET_ENHANCED_DARK   => 'dark',
            default                                                       => 'unknown',
        };
    }

    /**
     * Get reduced motion tokens.
     *
     * @since 2.0.0
     *
     * @return array<string, string> Reduced motion tokens.
     */
    public function getReducedMotionTokens(): array
    {
        return $this->reducedMotion;
    }

    /**
     * Get larger text tokens.
     *
     * @since 2.0.0
     *
     * @param  string  $size  Text size ('large' or 'extra-large').
     *
     * @return array<string, string> Text sizing tokens.
     */
    public function getLargerTextTokens(string $size = 'large'): array
    {
        return match ($size) {
            'extra-large' => $this->extraLargeText,
            default       => $this->largerText,
        };
    }

    /**
     * Get metadata for all presets.
     *
     * @since 2.0.0
     *
     * @return array<string, array<string, mixed>> Preset metadata.
     */
    public function getPresetsMetadata(): array
    {
        $metadata = [];

        foreach ($this->getAvailablePresets() as $preset) {
            $metadata[$preset] = [
                'name'        => $preset,
                'description' => $this->getPresetDescription($preset),
                'mode'        => $this->getPresetMode($preset),
                'compliance'  => $this->getComplianceLevel($preset),
                'tokens'      => $this->getPresetTokens($preset),
            ];
        }

        return $metadata;
    }

    /**
     * Calculate contrast ratio between two colors.
     *
     * @since 2.0.0
     *
     * @param  string  $foreground  Foreground color (hex).
     * @param  string  $background  Background color (hex).
     *
     * @return float The contrast ratio.
     */
    public function calculateContrastRatio(string $foreground, string $background): float
    {
        $fgLuminance = $this->getRelativeLuminance($foreground);
        $bgLuminance = $this->getRelativeLuminance($background);

        $lighter = max($fgLuminance, $bgLuminance);
        $darker  = min($fgLuminance, $bgLuminance);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Verify that a color pair meets WCAG compliance.
     *
     * @since 2.0.0
     *
     * @param  string  $foreground  Foreground color (hex).
     * @param  string  $background  Background color (hex).
     * @param  string  $level  WCAG level ('AA' or 'AAA').
     * @param  bool  $isLargeText  Whether the text is large (18pt+ or 14pt+ bold).
     *
     * @return bool True if compliant, false otherwise.
     */
    public function verifyContrast(
        string $foreground,
        string $background,
        string $level = 'AAA',
        bool $isLargeText = false,
    ): bool {
        $ratio = $this->calculateContrastRatio($foreground, $background);

        if ('AAA' === $level) {
            return $isLargeText ? $ratio >= self::WCAG_AAA_LARGE_TEXT : $ratio >= self::WCAG_AAA_NORMAL_TEXT;
        }

        return $isLargeText ? $ratio >= 3.0 : $ratio >= self::WCAG_AA_NORMAL_TEXT;
    }

    /**
     * Generate CSS for a specific preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     * @param  bool  $withSelector  Whether to wrap in a CSS selector.
     *
     * @return string The generated CSS.
     */
    public function generatePresetCss(string $presetName, bool $withSelector = true): string
    {
        $tokens = $this->getPresetTokens($presetName);

        if (empty($tokens)) {
            return '';
        }

        $css = '';

        if ($withSelector) {
            $css .= ".{$presetName} {\n";
        }

        foreach ($tokens as $property => $value) {
            $indent = $withSelector ? '    ' : '';
            $css .= "{$indent}--{$property}: {$value};\n";
        }

        if ($withSelector) {
            $css .= "}\n";
        }

        return $css;
    }

    /**
     * Generate CSS for all presets.
     *
     * @since 2.0.0
     *
     * @return string The generated CSS for all presets.
     */
    public function generateAllPresetsCss(): string
    {
        $css = "/**\n * ArtisanPack UI - High Contrast Theme Presets\n *\n";
        $css .= " * Accessibility-focused themes with WCAG compliance.\n";
        $css .= " * Generated automatically.\n */\n\n";

        foreach ($this->getAvailablePresets() as $preset) {
            $description = $this->getPresetDescription($preset);
            $compliance  = $this->getComplianceLevel($preset);

            $css .= "/**\n * {$preset}\n * {$description}\n * WCAG {$compliance} Compliant\n */\n";
            $css .= $this->generatePresetCss($preset);
            $css .= "\n";
        }

        return $css;
    }

    /**
     * Generate CSS for prefers-contrast media query support.
     *
     * @since 2.0.0
     *
     * @return string The generated CSS with media queries.
     */
    public function generatePrefersContrastCss(): string
    {
        $css = "/**\n * prefers-contrast Media Query Support\n *\n";
        $css .= " * Automatically applies high contrast when user has enabled\n";
        $css .= " * high contrast mode in their system settings.\n */\n\n";

        // High contrast preference
        $css .= "@media (prefers-contrast: more) {\n";
        $css .= "    :root {\n";
        foreach ($this->highContrastLight as $property => $value) {
            $css .= "        --{$property}: {$value};\n";
        }
        $css .= "    }\n\n";

        $css .= "    [data-theme=\"dark\"], .dark {\n";
        foreach ($this->highContrastDark as $property => $value) {
            $css .= "        --{$property}: {$value};\n";
        }
        $css .= "    }\n";
        $css .= "}\n\n";

        // Less contrast preference (use enhanced)
        $css .= "@media (prefers-contrast: less) {\n";
        $css .= "    :root {\n";
        foreach ($this->enhancedContrastLight as $property => $value) {
            $css .= "        --{$property}: {$value};\n";
        }
        $css .= "    }\n\n";

        $css .= "    [data-theme=\"dark\"], .dark {\n";
        foreach ($this->enhancedContrastDark as $property => $value) {
            $css .= "        --{$property}: {$value};\n";
        }
        $css .= "    }\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Generate CSS for reduced motion support.
     *
     * @since 2.0.0
     *
     * @return string The generated CSS for reduced motion.
     */
    public function generateReducedMotionCss(): string
    {
        $css = "/**\n * prefers-reduced-motion Support\n *\n";
        $css .= " * Reduces or disables animations and transitions for users\n";
        $css .= " * who have requested reduced motion in their system settings.\n */\n\n";

        $css .= "@media (prefers-reduced-motion: reduce) {\n";
        $css .= "    :root {\n";
        foreach ($this->reducedMotion as $property => $value) {
            $css .= "        --{$property}: {$value};\n";
        }
        $css .= "    }\n\n";

        $css .= "    *,\n";
        $css .= "    *::before,\n";
        $css .= "    *::after {\n";
        $css .= "        animation-duration: 0.01ms !important;\n";
        $css .= "        animation-iteration-count: 1 !important;\n";
        $css .= "        transition-duration: 0.01ms !important;\n";
        $css .= "        scroll-behavior: auto !important;\n";
        $css .= "    }\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Generate CSS for focus indicator enhancements.
     *
     * @since 2.0.0
     *
     * @return string The generated CSS for focus indicators.
     */
    public function generateFocusIndicatorsCss(): string
    {
        $css = "/**\n * Enhanced Focus Indicators\n *\n";
        $css .= " * Provides visible, consistent focus indicators for\n";
        $css .= " * keyboard navigation and accessibility.\n */\n\n";

        $css .= ".high-contrast-focus,\n";
        $css .= "[data-high-contrast=\"true\"] {\n";
        $css .= "    /* Focus ring styling */\n";
        $css .= "    --focus-ring-color: var(--hc-focus-ring-color, #0000cc);\n";
        $css .= "    --focus-ring-width: var(--hc-focus-ring-width, 3px);\n";
        $css .= "    --focus-ring-offset: var(--hc-focus-ring-offset, 2px);\n";
        $css .= "}\n\n";

        $css .= ".high-contrast-focus :focus-visible,\n";
        $css .= "[data-high-contrast=\"true\"] :focus-visible {\n";
        $css .= "    outline: var(--focus-ring-width) solid var(--focus-ring-color);\n";
        $css .= "    outline-offset: var(--focus-ring-offset);\n";
        $css .= "}\n\n";

        $css .= ".high-contrast-focus :focus:not(:focus-visible),\n";
        $css .= "[data-high-contrast=\"true\"] :focus:not(:focus-visible) {\n";
        $css .= "    outline: none;\n";
        $css .= "}\n\n";

        // Skip link styling
        $css .= "/* Skip link for keyboard navigation */\n";
        $css .= ".skip-link {\n";
        $css .= "    position: absolute;\n";
        $css .= "    top: -100%;\n";
        $css .= "    left: 0;\n";
        $css .= "    z-index: 9999;\n";
        $css .= "    padding: 1rem 1.5rem;\n";
        $css .= "    background: var(--hc-primary, #0000cc);\n";
        $css .= "    color: var(--hc-primary-foreground, #ffffff);\n";
        $css .= "    font-weight: bold;\n";
        $css .= "    text-decoration: none;\n";
        $css .= "    transition: top 0.15s ease-in-out;\n";
        $css .= "}\n\n";

        $css .= ".skip-link:focus {\n";
        $css .= "    top: 0;\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Generate CSS for larger text options.
     *
     * @since 2.0.0
     *
     * @return string The generated CSS for larger text.
     */
    public function generateLargerTextCss(): string
    {
        $css = "/**\n * Larger Text Options\n *\n";
        $css .= " * Scaled typography for improved readability.\n */\n\n";

        // Large text class
        $css .= ".text-larger {\n";
        foreach ($this->largerText as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n\n";

        // Extra large text class
        $css .= ".text-extra-large {\n";
        foreach ($this->extraLargeText as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n\n";

        // Apply text scale to typography
        $css .= ".text-larger,\n";
        $css .= ".text-extra-large {\n";
        $css .= "    font-size: var(--hc-text-base);\n";
        $css .= "    line-height: var(--hc-line-height);\n";
        $css .= "}\n\n";

        $css .= ".text-larger p,\n";
        $css .= ".text-extra-large p,\n";
        $css .= ".text-larger li,\n";
        $css .= ".text-extra-large li {\n";
        $css .= "    font-size: var(--hc-text-base);\n";
        $css .= "    line-height: var(--hc-line-height);\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Generate complete high contrast CSS with all features.
     *
     * @since 2.0.0
     *
     * @param  array<string, mixed>  $options  Generation options.
     *
     * @return string The complete CSS.
     */
    public function generateCompleteCss(array $options = []): string
    {
        $includePresets         = $options['include_presets'] ?? true;
        $includePrefersContrast = $options['include_prefers_contrast'] ?? true;
        $includeReducedMotion   = $options['include_reduced_motion'] ?? true;
        $includeFocusIndicators = $options['include_focus_indicators'] ?? true;
        $includeLargerText      = $options['include_larger_text'] ?? true;

        $css = "/**\n * ArtisanPack UI - High Contrast Accessibility Theme\n *\n";
        $css .= " * WCAG AAA compliant themes with accessibility enhancements.\n";
        $css .= " * Generated automatically. Do not edit directly.\n */\n\n";

        if ($includePresets) {
            $css .= $this->generateAllPresetsCss();
            $css .= "\n";
        }

        if ($includePrefersContrast) {
            $css .= $this->generatePrefersContrastCss();
            $css .= "\n";
        }

        if ($includeReducedMotion) {
            $css .= $this->generateReducedMotionCss();
            $css .= "\n";
        }

        if ($includeFocusIndicators) {
            $css .= $this->generateFocusIndicatorsCss();
            $css .= "\n";
        }

        if ($includeLargerText) {
            $css .= $this->generateLargerTextCss();
        }

        return $css;
    }

    /**
     * Calculate relative luminance of a color.
     *
     * @since 2.0.0
     *
     * @param  string  $hex  The hex color code.
     *
     * @return float The relative luminance (0-1).
     */
    protected function getRelativeLuminance(string $hex): float
    {
        $hex = ltrim($hex, '#');

        if (3 === strlen($hex)) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $r = $r <= 0.04045 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.04045 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.04045 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
