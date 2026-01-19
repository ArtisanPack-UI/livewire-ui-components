<?php

declare( strict_types=1 );
/**
 * Glass Theme Presets for ArtisanPack UI.
 *
 * This class provides pre-built glass theme configurations that can be applied
 * globally or per-component. Each preset offers a unique visual style based
 * on the glassmorphism design system.
 *
 * @since 2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Styling;

/**
 * Provides pre-built glass theme presets for the glassmorphism design system.
 *
 * Available presets:
 * - glass-frosted-light: Light mode frosted glass theme
 * - glass-frosted-dark: Dark mode frosted glass theme
 * - glass-liquid-light: Light mode liquid glass theme
 * - glass-liquid-dark: Dark mode liquid glass theme
 * - glass-minimal: Subtle glass effects for understated designs
 * - glass-bold: Strong glass effects for prominent UI elements
 *
 * @since 2.0.0
 */
class GlassPresets
{
    /**
     * Available preset names.
     *
     * @since 2.0.0
     */
    public const PRESETS = [
        'glass-frosted-light',
        'glass-frosted-dark',
        'glass-liquid-light',
        'glass-liquid-dark',
        'glass-minimal',
        'glass-bold',
    ];

    /**
     * Frosted Light preset configuration.
     *
     * A clean, professional frosted glass effect optimized for light backgrounds.
     * Features high saturation and moderate blur for a premium feel.
     *
     * @since 2.0.0
     */
    protected array $frostedLightPreset = [
        // Base tokens
        'glass-blur'           => '14px',
        'glass-opacity'        => '0.75',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.25',
        'glass-shadow-opacity' => '0.08',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.1',

        // Frosted variant (primary for this preset)
        'glass-frosted-blur'       => '18px',
        'glass-frosted-opacity'    => '0.85',
        'glass-frosted-saturation' => '200%',

        // Liquid variant
        'glass-liquid-blur'        => '22px',
        'glass-liquid-opacity'     => '0.65',
        'glass-liquid-refraction'  => '0.4',
        'glass-liquid-border-glow' => '0.25',

        // Transparent variant
        'glass-transparent-blur'    => '10px',
        'glass-transparent-opacity' => '0.35',

        // Computed colors
        'glass-bg-color'           => 'rgba(255, 255, 255, 0.75)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.25)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.08)',
        'glass-frosted-bg-color'   => 'rgba(255, 255, 255, 0.85)',
        'glass-liquid-bg-color'    => 'rgba(255, 255, 255, 0.65)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.25)',
        'glass-transparent-bg-color' => 'rgba(255, 255, 255, 0.35)',
    ];

    /**
     * Frosted Dark preset configuration.
     *
     * A sophisticated frosted glass effect optimized for dark backgrounds.
     * Features enhanced blur and border glow for visibility.
     *
     * @since 2.0.0
     */
    protected array $frostedDarkPreset = [
        // Base tokens
        'glass-blur'           => '16px',
        'glass-opacity'        => '0.8',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.3',
        'glass-shadow-opacity' => '0.2',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.15',

        // Frosted variant (primary for this preset)
        'glass-frosted-blur'       => '22px',
        'glass-frosted-opacity'    => '0.9',
        'glass-frosted-saturation' => '220%',

        // Liquid variant
        'glass-liquid-blur'        => '26px',
        'glass-liquid-opacity'     => '0.7',
        'glass-liquid-refraction'  => '0.5',
        'glass-liquid-border-glow' => '0.4',

        // Transparent variant
        'glass-transparent-blur'    => '14px',
        'glass-transparent-opacity' => '0.4',

        // Computed colors
        'glass-bg-color'           => 'rgba(20, 20, 25, 0.8)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.15)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.2)',
        'glass-frosted-bg-color'   => 'rgba(20, 20, 25, 0.9)',
        'glass-liquid-bg-color'    => 'rgba(20, 20, 25, 0.7)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.2)',
        'glass-transparent-bg-color' => 'rgba(20, 20, 25, 0.4)',
    ];

    /**
     * Liquid Light preset configuration.
     *
     * A premium liquid glass effect for light backgrounds with smooth,
     * flowing visuals and enhanced refraction effects.
     *
     * @since 2.0.0
     */
    protected array $liquidLightPreset = [
        // Base tokens
        'glass-blur'           => '16px',
        'glass-opacity'        => '0.6',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.35',
        'glass-shadow-opacity' => '0.12',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.12',

        // Frosted variant
        'glass-frosted-blur'       => '20px',
        'glass-frosted-opacity'    => '0.7',
        'glass-frosted-saturation' => '170%',

        // Liquid variant (primary for this preset)
        'glass-liquid-blur'        => '28px',
        'glass-liquid-opacity'     => '0.55',
        'glass-liquid-refraction'  => '0.6',
        'glass-liquid-border-glow' => '0.35',

        // Transparent variant
        'glass-transparent-blur'    => '12px',
        'glass-transparent-opacity' => '0.25',

        // Computed colors
        'glass-bg-color'           => 'rgba(255, 255, 255, 0.6)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.35)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.12)',
        'glass-frosted-bg-color'   => 'rgba(255, 255, 255, 0.7)',
        'glass-liquid-bg-color'    => 'rgba(255, 255, 255, 0.55)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.35)',
        'glass-transparent-bg-color' => 'rgba(255, 255, 255, 0.25)',
    ];

    /**
     * Liquid Dark preset configuration.
     *
     * A premium liquid glass effect for dark backgrounds with enhanced
     * glow effects and deeper transparency.
     *
     * @since 2.0.0
     */
    protected array $liquidDarkPreset = [
        // Base tokens
        'glass-blur'           => '18px',
        'glass-opacity'        => '0.55',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.4',
        'glass-shadow-opacity' => '0.25',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.18',

        // Frosted variant
        'glass-frosted-blur'       => '22px',
        'glass-frosted-opacity'    => '0.65',
        'glass-frosted-saturation' => '190%',

        // Liquid variant (primary for this preset)
        'glass-liquid-blur'        => '32px',
        'glass-liquid-opacity'     => '0.5',
        'glass-liquid-refraction'  => '0.65',
        'glass-liquid-border-glow' => '0.45',

        // Transparent variant
        'glass-transparent-blur'    => '16px',
        'glass-transparent-opacity' => '0.3',

        // Computed colors
        'glass-bg-color'           => 'rgba(15, 15, 20, 0.55)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.2)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.25)',
        'glass-frosted-bg-color'   => 'rgba(15, 15, 20, 0.65)',
        'glass-liquid-bg-color'    => 'rgba(15, 15, 20, 0.5)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.25)',
        'glass-transparent-bg-color' => 'rgba(15, 15, 20, 0.3)',
    ];

    /**
     * Minimal preset configuration.
     *
     * A subtle, understated glass effect for designs that need
     * glass aesthetics without overwhelming visual presence.
     *
     * @since 2.0.0
     */
    protected array $minimalPreset = [
        // Base tokens
        'glass-blur'           => '8px',
        'glass-opacity'        => '0.5',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.1',
        'glass-shadow-opacity' => '0.05',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.05',

        // Frosted variant
        'glass-frosted-blur'       => '10px',
        'glass-frosted-opacity'    => '0.55',
        'glass-frosted-saturation' => '120%',

        // Liquid variant
        'glass-liquid-blur'        => '14px',
        'glass-liquid-opacity'     => '0.4',
        'glass-liquid-refraction'  => '0.3',
        'glass-liquid-border-glow' => '0.15',

        // Transparent variant
        'glass-transparent-blur'    => '6px',
        'glass-transparent-opacity' => '0.2',

        // Computed colors (light mode)
        'glass-bg-color'           => 'rgba(255, 255, 255, 0.5)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.1)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.05)',
        'glass-frosted-bg-color'   => 'rgba(255, 255, 255, 0.55)',
        'glass-liquid-bg-color'    => 'rgba(255, 255, 255, 0.4)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.15)',
        'glass-transparent-bg-color' => 'rgba(255, 255, 255, 0.2)',
    ];

    /**
     * Minimal preset dark mode overrides.
     *
     * @since 2.0.0
     */
    protected array $minimalDarkOverrides = [
        'glass-opacity'        => '0.55',
        'glass-border-opacity' => '0.12',
        'glass-shadow-opacity' => '0.1',
        'glass-tint-opacity'   => '0.08',

        'glass-frosted-opacity' => '0.6',
        'glass-frosted-blur'    => '12px',

        'glass-liquid-opacity'     => '0.45',
        'glass-liquid-border-glow' => '0.2',

        'glass-transparent-opacity' => '0.25',
        'glass-transparent-blur'    => '8px',

        // Computed colors (dark mode)
        'glass-bg-color'           => 'rgba(30, 30, 35, 0.55)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.08)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.1)',
        'glass-frosted-bg-color'   => 'rgba(30, 30, 35, 0.6)',
        'glass-liquid-bg-color'    => 'rgba(30, 30, 35, 0.45)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.12)',
        'glass-transparent-bg-color' => 'rgba(30, 30, 35, 0.25)',
    ];

    /**
     * Bold preset configuration.
     *
     * A strong, prominent glass effect for elements that need
     * to stand out with dramatic visual impact.
     *
     * @since 2.0.0
     */
    protected array $boldPreset = [
        // Base tokens
        'glass-blur'           => '20px',
        'glass-opacity'        => '0.85',
        'glass-border-width'   => '2px',
        'glass-border-opacity' => '0.4',
        'glass-shadow-opacity' => '0.2',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.25',

        // Frosted variant
        'glass-frosted-blur'       => '26px',
        'glass-frosted-opacity'    => '0.9',
        'glass-frosted-saturation' => '250%',

        // Liquid variant
        'glass-liquid-blur'        => '36px',
        'glass-liquid-opacity'     => '0.75',
        'glass-liquid-refraction'  => '0.7',
        'glass-liquid-border-glow' => '0.5',

        // Transparent variant
        'glass-transparent-blur'    => '16px',
        'glass-transparent-opacity' => '0.45',

        // Computed colors (light mode)
        'glass-bg-color'           => 'rgba(255, 255, 255, 0.85)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.4)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.2)',
        'glass-frosted-bg-color'   => 'rgba(255, 255, 255, 0.9)',
        'glass-liquid-bg-color'    => 'rgba(255, 255, 255, 0.75)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.5)',
        'glass-transparent-bg-color' => 'rgba(255, 255, 255, 0.45)',
    ];

    /**
     * Bold preset dark mode overrides.
     *
     * @since 2.0.0
     */
    protected array $boldDarkOverrides = [
        'glass-opacity'        => '0.9',
        'glass-border-opacity' => '0.45',
        'glass-shadow-opacity' => '0.3',
        'glass-tint-opacity'   => '0.3',

        'glass-frosted-opacity'    => '0.92',
        'glass-frosted-blur'       => '30px',
        'glass-frosted-saturation' => '280%',

        'glass-liquid-opacity'     => '0.8',
        'glass-liquid-blur'        => '40px',
        'glass-liquid-border-glow' => '0.55',

        'glass-transparent-opacity' => '0.5',
        'glass-transparent-blur'    => '20px',

        // Computed colors (dark mode)
        'glass-bg-color'           => 'rgba(10, 10, 15, 0.9)',
        'glass-border-color'       => 'rgba(255, 255, 255, 0.25)',
        'glass-shadow-color'       => 'rgba(0, 0, 0, 0.3)',
        'glass-frosted-bg-color'   => 'rgba(10, 10, 15, 0.92)',
        'glass-liquid-bg-color'    => 'rgba(10, 10, 15, 0.8)',
        'glass-liquid-border-glow-color' => 'rgba(255, 255, 255, 0.3)',
        'glass-transparent-bg-color' => 'rgba(10, 10, 15, 0.5)',
    ];

    /**
     * Get all available preset names.
     *
     * @since 2.0.0
     *
     * @return array List of available preset names.
     */
    public function getAvailablePresets(): array
    {
        return self::PRESETS;
    }

    /**
     * Check if a preset name is valid.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name to validate.
     *
     * @return bool True if the preset exists.
     */
    public function isValidPreset( string $presetName ): bool
    {
        return in_array( $presetName, self::PRESETS, true );
    }

    /**
     * Get the token configuration for a specific preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The name of the preset.
     *
     * @return array The token configuration for the preset.
     *
     * @throws \InvalidArgumentException If the preset name is not valid.
     */
    public function getPresetTokens( string $presetName ): array
    {
        if ( ! $this->isValidPreset( $presetName ) ) {
            throw new \InvalidArgumentException(
                sprintf( "Invalid glass preset '%s'. Available presets: %s", $presetName, implode( ', ', self::PRESETS ) )
            );
        }

        return match ( $presetName ) {
            'glass-frosted-light' => $this->frostedLightPreset,
            'glass-frosted-dark'  => $this->frostedDarkPreset,
            'glass-liquid-light'  => $this->liquidLightPreset,
            'glass-liquid-dark'   => $this->liquidDarkPreset,
            'glass-minimal'       => $this->minimalPreset,
            'glass-bold'          => $this->boldPreset,
        };
    }

    /**
     * Get the dark mode overrides for a specific preset.
     *
     * Some presets (minimal, bold) have separate dark mode overrides.
     * Mode-specific presets (frosted-light, frosted-dark, etc.) return empty arrays.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The name of the preset.
     *
     * @return array The dark mode override tokens.
     */
    public function getDarkModeOverrides( string $presetName ): array
    {
        return match ( $presetName ) {
            'glass-minimal' => $this->minimalDarkOverrides,
            'glass-bold'    => $this->boldDarkOverrides,
            default         => [],
        };
    }

    /**
     * Get the description for a specific preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The name of the preset.
     *
     * @return string The human-readable description.
     */
    public function getPresetDescription( string $presetName ): string
    {
        $descriptions = [
            'glass-frosted-light' => __( 'Clean, professional frosted glass effect optimized for light backgrounds with high saturation and moderate blur.' ),
            'glass-frosted-dark'  => __( 'Sophisticated frosted glass effect optimized for dark backgrounds with enhanced blur and border glow.' ),
            'glass-liquid-light'  => __( 'Premium liquid glass effect for light backgrounds with smooth, flowing visuals and enhanced refraction.' ),
            'glass-liquid-dark'   => __( 'Premium liquid glass effect for dark backgrounds with enhanced glow effects and deeper transparency.' ),
            'glass-minimal'       => __( 'Subtle, understated glass effect for designs that need glass aesthetics without overwhelming visual presence.' ),
            'glass-bold'          => __( 'Strong, prominent glass effect for elements that need to stand out with dramatic visual impact.' ),
        ];

        return $descriptions[$presetName] ?? '';
    }

    /**
     * Generate CSS custom properties for a preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The name of the preset.
     * @param  bool  $includeSelector  Whether to wrap in a CSS selector.
     *
     * @return string The CSS custom properties.
     */
    public function generatePresetCss( string $presetName, bool $includeSelector = true ): string
    {
        $tokens = $this->getPresetTokens( $presetName );
        $css    = '';

        if ( $includeSelector ) {
            $css .= ".{$presetName} {\n";
        }

        foreach ( $tokens as $property => $value ) {
            $indent = $includeSelector ? '    ' : '';
            $css .= "{$indent}--{$property}: {$value};\n";
        }

        if ( $includeSelector ) {
            $css .= "}\n";
        }

        // Add dark mode overrides if available
        $darkOverrides = $this->getDarkModeOverrides( $presetName );
        if ( ! empty( $darkOverrides ) && $includeSelector ) {
            $css .= "\n[data-theme=\"dark\"] .{$presetName} {\n";
            foreach ( $darkOverrides as $property => $value ) {
                $css .= "    --{$property}: {$value};\n";
            }
            $css .= "}\n";
        }

        return $css;
    }

    /**
     * Generate CSS for all presets.
     *
     * @since 2.0.0
     *
     * @return string The complete CSS for all presets.
     */
    public function generateAllPresetsCss(): string
    {
        $css = "/**\n * ArtisanPack UI - Glass Theme Presets\n * \n * Pre-built glass theme configurations for common use cases.\n * Apply these classes to elements or containers to use preset glass styles.\n *\n * Available Presets:\n";

        foreach ( self::PRESETS as $preset ) {
            $description = $this->getPresetDescription( $preset );
            $css .= " * - .{$preset}: {$description}\n";
        }

        $css .= " *\n * @since 2.0.0\n */\n\n";

        foreach ( self::PRESETS as $preset ) {
            $css .= "/* {$preset} */\n";
            $css .= $this->generatePresetCss( $preset );
            $css .= "\n";
        }

        return $css;
    }

    /**
     * Get preset metadata for documentation or UI.
     *
     * @since 2.0.0
     *
     * @return array Array of preset metadata.
     */
    public function getPresetsMetadata(): array
    {
        $metadata = [];

        foreach ( self::PRESETS as $preset ) {
            $metadata[$preset] = [
                'name'        => $preset,
                'description' => $this->getPresetDescription( $preset ),
                'mode'        => $this->getPresetMode( $preset ),
                'intensity'   => $this->getPresetIntensity( $preset ),
                'tokens'      => $this->getPresetTokens( $preset ),
                'has_dark_overrides' => ! empty( $this->getDarkModeOverrides( $preset ) ),
            ];
        }

        return $metadata;
    }

    /**
     * Get the mode (light/dark/adaptive) for a preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return string The mode: 'light', 'dark', or 'adaptive'.
     */
    protected function getPresetMode( string $presetName ): string
    {
        if ( str_ends_with( $presetName, '-light' ) ) {
            return 'light';
        }

        if ( str_ends_with( $presetName, '-dark' ) ) {
            return 'dark';
        }

        return 'adaptive';
    }

    /**
     * Get the intensity level for a preset.
     *
     * @since 2.0.0
     *
     * @param  string  $presetName  The preset name.
     *
     * @return string The intensity: 'subtle', 'normal', or 'bold'.
     */
    protected function getPresetIntensity( string $presetName ): string
    {
        if ( 'glass-minimal' === $presetName ) {
            return 'subtle';
        }

        if ( 'glass-bold' === $presetName ) {
            return 'bold';
        }

        return 'normal';
    }
}
