<?php

declare(strict_types=1);
/**
 * Color and Theme Generator for ArtisanPack UI.
 *
 * This class provides utilities for generating full color palettes from base colors,
 * calculating accessible contrast colors, and producing a complete CSS theme file
 * compatible with both ArtisanPack UI components and the DaisyUI framework.
 *
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Styling;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

/**
 * Generates dynamic CSS theme files based on specified base colors.
 *
 * @since 1.0.0
 */
class ColorGenerator
{
    /**
     * Predefined Tailwind CSS-like color palettes (base 500 hex value).
     *
     * @since 1.0.0
     */
    protected array $tailwindColorBases = [
        'sky'     => '#0ea5e9',
        'blue'    => '#3b82f6',
        'indigo'  => '#6366f1',
        'purple'  => '#a855f7',
        'pink'    => '#ec4899',
        'red'     => '#ef4444',
        'orange'  => '#f97316',
        'amber'   => '#f59e0b',
        'yellow'  => '#eab308',
        'lime'    => '#84cc16',
        'green'   => '#22c55e',
        'emerald' => '#10b981',
        'teal'    => '#14b8a6',
        'cyan'    => '#06b6d4',
        'fuchsia' => '#d946ef',
        'rose'    => '#f43f5e',
        'stone'   => '#78716c',
        'slate'   => '#64748b',
        'zinc'    => '#71717a',
        'neutral' => '#737373',
        'gray'    => '#6b7280',
        'white'   => '#ffffff',
        'black'   => '#000000',
    ];

    /**
     * Default DaisyUI color theme variables.
     *
     * @since 1.0.0
     * @link https://daisyui.com/docs/colors/
     */
    protected array $daisyUiColorDefaults = [
        'primary'           => '#3b82f6', // blue-500
        'primary-content'   => '#ffffff',
        'secondary'         => '#64748b', // slate-500
        'secondary-content' => '#ffffff',
        'accent'            => '#f59e0b', // amber-500
        'accent-content'    => '#ffffff',
        'neutral'           => '#737373', // neutral-500
        'neutral-content'   => '#ffffff',
        'base-100'          => '#ffffff',
        'base-200'          => '#f3f4f6', // gray-100
        'base-300'          => '#e5e7eb', // gray-200
        'base-content'      => '#1f2937', // gray-800
        'info'              => '#0ea5e9', // sky-500
        'info-content'      => '#ffffff',
        'success'           => '#22c55e', // green-500
        'success-content'   => '#ffffff',
        'warning'           => '#f97316', // orange-500
        'warning-content'   => '#ffffff',
        'error'             => '#ef4444', // red-500
        'error-content'     => '#ffffff',
    ];

    /**
     * Default DaisyUI dark mode color theme variables.
     *
     * @since 1.0.0
     * @link https://daisyui.com/docs/colors/
     */
    protected array $daisyUiDarkColorDefaults = [
        'neutral'           => '#191D24',
        'neutral-content'   => '#A6ADBB',
        'base-100'          => '#2A303C',
        'base-200'          => '#242933',
        'base-300'          => '#20252E',
        'base-content'      => '#A6ADBB',
        'info'              => '#0ea5e9', // sky-500
        'info-content'      => '#ffffff',
        'success'           => '#22c55e', // green-500
        'success-content'   => '#ffffff',
        'warning'           => '#f97316', // orange-500
        'warning-content'   => '#ffffff',
        'error'             => '#ef4444', // red-500
        'error-content'     => '#ffffff',
    ];

    /**
     * Default DaisyUI utility and component variables.
     *
     * @since 1.0.0
     * @link https://daisyui.com/docs/utilities/
     */
    protected array $daisyUiUtilityDefaults = [
        '--rounded-box'     => '1rem',
        '--rounded-btn'     => '0.5rem',
        '--rounded-badge'   => '1.9rem',
        '--animation-btn'   => '0.25s',
        '--animation-input' => '0.2s',
        '--btn-focus-scale' => '0.95',
        '--border-btn'      => '1px',
        '--tab-border'      => '1px',
        '--tab-radius'      => '0.5rem',
    ];

    /**
     * Default glass design token values.
     *
     * These tokens form the foundation of the glassmorphism design system.
     *
     * @since 2.0.0
     */
    protected array $glassTokenDefaults = [
        // Base glass tokens
        'glass-blur'           => '12px',
        'glass-opacity'        => '0.7',
        'glass-border-width'   => '1px',
        'glass-border-opacity' => '0.2',
        'glass-shadow-opacity' => '0.1',

        // Tint tokens
        'glass-tint-color'   => 'transparent',
        'glass-tint-opacity' => '0.15',

        // Frosted variant
        'glass-frosted-blur'       => '16px',
        'glass-frosted-opacity'    => '0.8',
        'glass-frosted-saturation' => '180%',

        // Liquid variant
        'glass-liquid-blur'        => '24px',
        'glass-liquid-opacity'     => '0.6',
        'glass-liquid-refraction'  => '0.5',
        'glass-liquid-border-glow' => '0.3',

        // Transparent variant
        'glass-transparent-blur'    => '8px',
        'glass-transparent-opacity' => '0.3',
    ];

    /**
     * Dark mode overrides for glass tokens.
     *
     * @since 2.0.0
     */
    protected array $glassTokenDarkDefaults = [
        'glass-frosted-opacity' => '0.7',
        'glass-border-opacity'  => '0.15',
        'glass-shadow-opacity'  => '0.2',
    ];

    /**
     * Default typography design token values.
     *
     * These tokens define the typographic scale and font properties.
     *
     * @since 2.0.0
     */
    protected array $typographyTokenDefaults = [
        // Font families
        'font-family-sans'  => 'ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
        'font-family-serif' => 'ui-serif, Georgia, Cambria, "Times New Roman", Times, serif',
        'font-family-mono'  => 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',

        // Font sizes (based on a modular scale with 1rem = 16px)
        'font-size-xs'   => '0.75rem',   // 12px
        'font-size-sm'   => '0.875rem',  // 14px
        'font-size-base' => '1rem',      // 16px
        'font-size-lg'   => '1.125rem',  // 18px
        'font-size-xl'   => '1.25rem',   // 20px
        'font-size-2xl'  => '1.5rem',    // 24px
        'font-size-3xl'  => '1.875rem',  // 30px
        'font-size-4xl'  => '2.25rem',   // 36px
        'font-size-5xl'  => '3rem',      // 48px
        'font-size-6xl'  => '3.75rem',   // 60px

        // Font weights
        'font-weight-thin'       => '100',
        'font-weight-extralight' => '200',
        'font-weight-light'      => '300',
        'font-weight-normal'     => '400',
        'font-weight-medium'     => '500',
        'font-weight-semibold'   => '600',
        'font-weight-bold'       => '700',
        'font-weight-extrabold'  => '800',
        'font-weight-black'      => '900',

        // Line heights
        'line-height-none'    => '1',
        'line-height-tight'   => '1.25',
        'line-height-snug'    => '1.375',
        'line-height-normal'  => '1.5',
        'line-height-relaxed' => '1.625',
        'line-height-loose'   => '2',

        // Letter spacing
        'letter-spacing-tighter' => '-0.05em',
        'letter-spacing-tight'   => '-0.025em',
        'letter-spacing-normal'  => '0em',
        'letter-spacing-wide'    => '0.025em',
        'letter-spacing-wider'   => '0.05em',
        'letter-spacing-widest'  => '0.1em',
    ];

    /**
     * Default spacing design token values.
     *
     * These tokens define a consistent spacing scale based on 0.25rem (4px) increments.
     *
     * @since 2.0.0
     */
    protected array $spacingTokenDefaults = [
        'spacing-0'    => '0',
        'spacing-px'   => '1px',
        'spacing-0-5'  => '0.125rem',  // 2px
        'spacing-1'    => '0.25rem',   // 4px
        'spacing-1-5'  => '0.375rem',  // 6px
        'spacing-2'    => '0.5rem',    // 8px
        'spacing-2-5'  => '0.625rem',  // 10px
        'spacing-3'    => '0.75rem',   // 12px
        'spacing-3-5'  => '0.875rem',  // 14px
        'spacing-4'    => '1rem',      // 16px
        'spacing-5'    => '1.25rem',   // 20px
        'spacing-6'    => '1.5rem',    // 24px
        'spacing-7'    => '1.75rem',   // 28px
        'spacing-8'    => '2rem',      // 32px
        'spacing-9'    => '2.25rem',   // 36px
        'spacing-10'   => '2.5rem',    // 40px
        'spacing-11'   => '2.75rem',   // 44px
        'spacing-12'   => '3rem',      // 48px
        'spacing-14'   => '3.5rem',    // 56px
        'spacing-16'   => '4rem',      // 64px
        'spacing-20'   => '5rem',      // 80px
        'spacing-24'   => '6rem',      // 96px
        'spacing-28'   => '7rem',      // 112px
        'spacing-32'   => '8rem',      // 128px
        'spacing-36'   => '9rem',      // 144px
        'spacing-40'   => '10rem',     // 160px
        'spacing-44'   => '11rem',     // 176px
        'spacing-48'   => '12rem',     // 192px
        'spacing-52'   => '13rem',     // 208px
        'spacing-56'   => '14rem',     // 224px
        'spacing-60'   => '15rem',     // 240px
        'spacing-64'   => '16rem',     // 256px
        'spacing-72'   => '18rem',     // 288px
        'spacing-80'   => '20rem',     // 320px
        'spacing-96'   => '24rem',     // 384px
    ];

    /**
     * Default border radius design token values.
     *
     * These tokens define the rounded corner system.
     *
     * @since 2.0.0
     */
    protected array $radiusTokenDefaults = [
        'radius-none' => '0',
        'radius-sm'   => '0.125rem',  // 2px
        'radius-base' => '0.25rem',   // 4px
        'radius-md'   => '0.375rem',  // 6px
        'radius-lg'   => '0.5rem',    // 8px
        'radius-xl'   => '0.75rem',   // 12px
        'radius-2xl'  => '1rem',      // 16px
        'radius-3xl'  => '1.5rem',    // 24px
        'radius-full' => '9999px',    // Fully rounded (pill shape)
    ];

    /**
     * Default shadow/elevation design token values.
     *
     * These tokens define the elevation system for depth effects.
     *
     * @since 2.0.0
     */
    protected array $shadowTokenDefaults = [
        'shadow-none' => 'none',
        'shadow-sm'   => '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'shadow-base' => '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
        'shadow-md'   => '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
        'shadow-lg'   => '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
        'shadow-xl'   => '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
        'shadow-2xl'  => '0 25px 50px -12px rgb(0 0 0 / 0.25)',
        'shadow-inner' => 'inset 0 2px 4px 0 rgb(0 0 0 / 0.05)',

        // Colored shadows for semantic feedback
        'shadow-primary' => '0 4px 14px 0 rgb(59 130 246 / 0.3)',
        'shadow-success' => '0 4px 14px 0 rgb(34 197 94 / 0.3)',
        'shadow-warning' => '0 4px 14px 0 rgb(249 115 22 / 0.3)',
        'shadow-error'   => '0 4px 14px 0 rgb(239 68 68 / 0.3)',
        'shadow-info'    => '0 4px 14px 0 rgb(14 165 233 / 0.3)',

        // Glow effects for interactive states
        'shadow-glow-sm' => '0 0 8px 0 rgb(59 130 246 / 0.4)',
        'shadow-glow-md' => '0 0 16px 0 rgb(59 130 246 / 0.4)',
        'shadow-glow-lg' => '0 0 24px 0 rgb(59 130 246 / 0.4)',
    ];

    /**
     * Dark mode overrides for shadow tokens.
     *
     * @since 2.0.0
     */
    protected array $shadowTokenDarkDefaults = [
        'shadow-sm'   => '0 1px 2px 0 rgb(0 0 0 / 0.2)',
        'shadow-base' => '0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3)',
        'shadow-md'   => '0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.3)',
        'shadow-lg'   => '0 10px 15px -3px rgb(0 0 0 / 0.3), 0 4px 6px -4px rgb(0 0 0 / 0.3)',
        'shadow-xl'   => '0 20px 25px -5px rgb(0 0 0 / 0.3), 0 8px 10px -6px rgb(0 0 0 / 0.3)',
        'shadow-2xl'  => '0 25px 50px -12px rgb(0 0 0 / 0.5)',
        'shadow-inner' => 'inset 0 2px 4px 0 rgb(0 0 0 / 0.2)',
    ];

    /**
     * Default animation design token values.
     *
     * These tokens define animation durations, easings, and timing.
     *
     * @since 2.0.0
     */
    protected array $animationTokenDefaults = [
        // Durations
        'duration-0'       => '0ms',
        'duration-75'      => '75ms',
        'duration-100'     => '100ms',
        'duration-150'     => '150ms',
        'duration-200'     => '200ms',
        'duration-300'     => '300ms',
        'duration-500'     => '500ms',
        'duration-700'     => '700ms',
        'duration-1000'    => '1000ms',

        // Standard easing functions
        'ease-linear'      => 'linear',
        'ease-in'          => 'cubic-bezier(0.4, 0, 1, 1)',
        'ease-out'         => 'cubic-bezier(0, 0, 0.2, 1)',
        'ease-in-out'      => 'cubic-bezier(0.4, 0, 0.2, 1)',

        // Expressive easing functions
        'ease-spring'      => 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
        'ease-bounce'      => 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'ease-elastic'     => 'cubic-bezier(0.68, -0.6, 0.32, 1.6)',

        // Common animation presets
        'transition-none'     => 'none',
        'transition-all'      => 'all var(--duration-200) var(--ease-in-out)',
        'transition-default'  => 'color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter',
        'transition-colors'   => 'color, background-color, border-color, text-decoration-color, fill, stroke',
        'transition-opacity'  => 'opacity',
        'transition-shadow'   => 'box-shadow',
        'transition-transform' => 'transform',
    ];

    /**
     * Default DaisyUI component-specific variables.
     *
     * These are applied to component classes, not :root.
     *
     * @since 1.0.0
     */
    protected array $daisyUiComponentDefaults = [
        'Alert' => [
            'selector'  => '.alert',
            'variables' => [
                '--alert-color' => 'oklch(var(--b2))',
            ],
        ],
        'Badge' => [
            'selector'  => '.badge',
            'variables' => [
                '--badge-color' => 'oklch(var(--b2))',
                '--size'        => '1.2rem',
            ],
        ],
        'Button' => [
            'selector'  => '.btn',
            'variables' => [
                '--btn-color' => 'oklch(var(--b2))',
                '--btn-fg'    => 'oklch(var(--bc))',
                '--size'      => '3rem',
            ],
        ],
        'Card' => [
            'selector'  => '.card',
            'variables' => [
                '--card-p'        => '1.5rem',
                '--card-fs'       => '1rem',
                '--card-title-fs' => '1.25rem',
            ],
        ],
        'Checkbox' => [
            'selector'  => '.checkbox',
            'variables' => [
                '--size' => '1.5rem',
            ],
        ],
        'Input' => [
            'selector'  => '.input',
            'variables' => [
                '--input-color' => 'oklch(var(--b2))',
                '--size'        => '3rem',
            ],
        ],
        'Menu' => [
            'selector'  => '.menu',
            'variables' => [
                '--menu-active-fg' => 'oklch(var(--pc))',
                '--menu-active-bg' => 'oklch(var(--p))',
            ],
        ],
        'Modal' => [
            'selector'  => '.modal-box',
            'variables' => [
                '--modal-tl' => '1rem',
                '--modal-tr' => '1rem',
                '--modal-bl' => '1rem',
                '--modal-br' => '1rem',
            ],
        ],
        'Toggle' => [
            'selector'  => '.toggle',
            'variables' => [
                '--size' => '1.5rem',
            ],
        ],
        'Tooltip' => [
            'selector'  => '.tooltip',
            'variables' => [
                '--tt-bg'  => 'oklch(var(--b2))',
                '--tt-off' => '0.5rem',
            ],
        ],
    ];

    /**
     * Retrieves the base hex color for a given Tailwind color name or validates a hex code.
     *
     * @since 1.0.0
     *
     * @param  string  $colorInput  The name of the Tailwind color (e.g., 'sky') or a hex code.
     *
     * @throws InvalidArgumentException If the color name is not found or hex is invalid.
     *
     * @return string The hex code for the base color.
     */
    public function getBaseColor(string $colorInput): string
    {
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $colorInput)) {
            return $colorInput;
        }

        $normalizedColorName = strtolower($colorInput);
        if (isset($this->tailwindColorBases[$normalizedColorName])) {
            return $this->tailwindColorBases[$normalizedColorName];
        }

        throw new InvalidArgumentException("Color '{$colorInput}' is not a valid hex code or predefined Tailwind color.");
    }

    /**
     * Generates a full 10-stop color palette from a single base hex color.
     *
     * This method uses an external API to generate shades from 50 to 900.
     *
     * @since 1.0.0
     *
     * @param  string  $baseHex  The base hex color (e.g., '#3B82F6').
     *
     * @return array An associative array of color shades (50, 100, ..., 900).
     */
    public function generateTailwindShades(string $baseHex): array
    {
        $shades = [];
        // The API endpoint from "Tints and Shades Generator" is used here.
        $response = Http::get('https://tailwind.simeongriggs.dev/api/generated-color/'.substr($baseHex, 1));

        if ($response->successful() && isset($response->json()['generated-color'])) {
            $apiShades = $response->json()['generated-color'];
            $stops     = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
            foreach ($stops as $stop) {
                // The API returns shades from lightest to darkest.
                $shades[$stop] = $apiShades[$stop];
            }
        } else {
            // Fallback to a simple array if the API fails
            $stops     = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
            foreach ($stops as $stop) {
                $shades[$stop] = $baseHex;
            }
        }

        return $shades;
    }

    /**
     * Generates the complete CSS theme file content.
     *
     * @since 1.0.0
     *
     * @param  string  $primaryColor  The name or hex of the primary color.
     * @param  string  $secondaryColor  The name or hex of the secondary color.
     * @param  string  $accentColor  The name or hex of the accent color.
     *
     * @return string The full CSS content for the theme file.
     */
    public function generateThemeCss(string $primaryColor, string $secondaryColor, string $accentColor): string
    {
        $primaryBase     = $this->getBaseColor($primaryColor);
        $secondaryBase   = $this->getBaseColor($secondaryColor);
        $accentBase      = $this->getBaseColor($accentColor);

        $primaryShades   = $this->generateTailwindShades($primaryBase);
        $secondaryShades = $this->generateTailwindShades($secondaryBase);
        $accentShades    = $this->generateTailwindShades($accentBase);

        $daisyTheme                      = $this->daisyUiColorDefaults;
        $daisyTheme['primary']           = $primaryBase;
        $daisyTheme['primary-content']   = generateAccessibleTextColor($primaryBase);
        $daisyTheme['secondary']         = $secondaryBase;
        $daisyTheme['secondary-content'] = generateAccessibleTextColor($secondaryBase);
        $daisyTheme['accent']            = $accentBase;
        $daisyTheme['accent-content']    = generateAccessibleTextColor($accentBase);

        $css = "/**\n * ArtisanPack UI - Generated Theme\n * \n * This file is automatically generated. Do not edit directly.\n * Use the 'artisanpack:generate-theme' command to update.\n */\n\n";

        // :root variables for colors and global utilities
        $css .= ":root {\n";

        $css .= "    /* --- ArtisanPack UI Color Palette --- */\n";
        foreach ($primaryShades as $stop => $hex) {
            $css .= "    --p-{$stop}: {$hex};\n";
        }
        $css .= "\n";
        foreach ($secondaryShades as $stop => $hex) {
            $css .= "    --s-{$stop}: {$hex};\n";
        }
        $css .= "\n";
        foreach ($accentShades as $stop => $hex) {
            $css .= "    --a-{$stop}: {$hex};\n";
        }

        $css .= "\n    /* --- DaisyUI Color Variables --- */\n";
        foreach ($daisyTheme as $key => $value) {
            $css .= "    --color-{$key}: {$value};\n";
        }

        $css .= "\n    /* --- DaisyUI Global Utility Variables --- */\n";
        foreach ($this->daisyUiUtilityDefaults as $key => $value) {
            $css .= "    {$key}: {$value};\n";
        }

        $css .= "\n    /* --- Glass Design Tokens --- */\n";
        foreach ( $this->glassTokenDefaults as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Computed Glass Colors (Light Mode) --- */\n";
        $css .= "    --glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(255, 255, 255, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(255, 255, 255, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, var(--glass-liquid-border-glow));\n";
        $css .= "    --glass-transparent-bg-color: rgba(255, 255, 255, var(--glass-transparent-opacity));\n";

        $css .= "}\n";

        // Dark mode overrides
        $css .= "\n[data-theme=\"dark\"] {\n";
        $css .= "    /* --- ArtisanPack UI Dark Mode Overrides --- */\n";
        foreach ($this->daisyUiDarkColorDefaults as $key => $value) {
            $css .= "    --color-{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Glass Design Token Dark Mode Overrides --- */\n";
        foreach ( $this->glassTokenDarkDefaults as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Computed Glass Colors (Dark Mode) --- */\n";
        $css .= "    --glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(30, 30, 30, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(30, 30, 30, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, calc(var(--glass-liquid-border-glow) * 0.7));\n";
        $css .= "    --glass-transparent-bg-color: rgba(30, 30, 30, var(--glass-transparent-opacity));\n";

        $css .= "}\n";

        // Component-specific variables
        $css .= "\n/**\n * ===================================================================================\n * Component CSS Variables\n * ===================================================================================\n * \n * Uncomment the blocks below to override the default values for specific components.\n * These are scoped to the component class for precision.\n */\n";

        foreach ($this->daisyUiComponentDefaults as $componentName => $data) {
            $css .= "\n/* --- {$componentName} --- */\n";
            $css .= "/*\n{$data['selector']} {\n";
            foreach ($data['variables'] as $key => $value) {
                $css .= "    {$key}: {$value};\n";
            }
            $css .= "}\n*/\n";
        }

        return $css;
    }

    /**
     * Generates the glass design tokens CSS content.
     *
     * This method creates CSS custom properties for the glassmorphism design system,
     * including base tokens, variant-specific tokens, and dark mode overrides.
     *
     * @since 2.0.0
     *
     * @param  array  $overrides  Optional. Custom token values to override defaults.
     *
     * @return string The CSS content for glass tokens.
     */
    public function generateGlassTokensCss( array $overrides = [] ): string
    {
        $tokens     = array_merge( $this->glassTokenDefaults, $overrides );
        $darkTokens = $this->glassTokenDarkDefaults;

        $css = "/**\n * ArtisanPack UI - Glass Design Tokens\n * \n * This file is automatically generated. Do not edit directly.\n * Use the 'artisanpack:generate-theme' command to update.\n *\n * @since 2.0.0\n */\n\n";

        // Tailwind CSS v4 @theme directive
        $css .= "/**\n * Tailwind CSS v4 @theme integration\n * These tokens can be extended or overridden in your app.css using @theme\n */\n";
        $css .= "@theme {\n";
        $css .= "    /* --- Base Glass Tokens --- */\n";
        foreach ( $tokens as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }
        $css .= "}\n\n";

        // Root-level tokens
        $css .= ":root {\n";
        $css .= "    /* --- Base Glass Tokens --- */\n";
        foreach ( $tokens as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Computed Glass Colors (Light Mode) --- */\n";
        $css .= "    --glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(255, 255, 255, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(255, 255, 255, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, var(--glass-liquid-border-glow));\n";
        $css .= "    --glass-transparent-bg-color: rgba(255, 255, 255, var(--glass-transparent-opacity));\n";
        $css .= "}\n";

        // Dark mode overrides
        $css .= "\n[data-theme=\"dark\"] {\n";
        $css .= "    /* --- Dark Mode Token Overrides --- */\n";
        foreach ( $darkTokens as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Computed Glass Colors (Dark Mode) --- */\n";
        $css .= "    --glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(30, 30, 30, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(30, 30, 30, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, calc(var(--glass-liquid-border-glow) * 0.7));\n";
        $css .= "    --glass-transparent-bg-color: rgba(30, 30, 30, var(--glass-transparent-opacity));\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Gets the default glass token values.
     *
     * @since 2.0.0
     *
     * @return array The default glass token values.
     */
    public function getGlassTokenDefaults(): array
    {
        return $this->glassTokenDefaults;
    }

    /**
     * Gets the dark mode glass token overrides.
     *
     * @since 2.0.0
     *
     * @return array The dark mode glass token overrides.
     */
    public function getGlassTokenDarkDefaults(): array
    {
        return $this->glassTokenDarkDefaults;
    }

    /**
     * Gets the default typography token values.
     *
     * @since 2.0.0
     *
     * @return array The default typography token values.
     */
    public function getTypographyTokenDefaults(): array
    {
        return $this->typographyTokenDefaults;
    }

    /**
     * Gets the default spacing token values.
     *
     * @since 2.0.0
     *
     * @return array The default spacing token values.
     */
    public function getSpacingTokenDefaults(): array
    {
        return $this->spacingTokenDefaults;
    }

    /**
     * Gets the default border radius token values.
     *
     * @since 2.0.0
     *
     * @return array The default radius token values.
     */
    public function getRadiusTokenDefaults(): array
    {
        return $this->radiusTokenDefaults;
    }

    /**
     * Gets the default shadow token values.
     *
     * @since 2.0.0
     *
     * @return array The default shadow token values.
     */
    public function getShadowTokenDefaults(): array
    {
        return $this->shadowTokenDefaults;
    }

    /**
     * Gets the dark mode shadow token overrides.
     *
     * @since 2.0.0
     *
     * @return array The dark mode shadow token overrides.
     */
    public function getShadowTokenDarkDefaults(): array
    {
        return $this->shadowTokenDarkDefaults;
    }

    /**
     * Gets the default animation token values.
     *
     * @since 2.0.0
     *
     * @return array The default animation token values.
     */
    public function getAnimationTokenDefaults(): array
    {
        return $this->animationTokenDefaults;
    }

    /**
     * Gets all design token defaults combined.
     *
     * @since 2.0.0
     *
     * @return array All design token defaults organized by category.
     */
    public function getAllDesignTokenDefaults(): array
    {
        return [
            'typography' => $this->typographyTokenDefaults,
            'spacing'    => $this->spacingTokenDefaults,
            'radius'     => $this->radiusTokenDefaults,
            'shadow'     => $this->shadowTokenDefaults,
            'animation'  => $this->animationTokenDefaults,
            'glass'      => $this->glassTokenDefaults,
        ];
    }

    /**
     * Generates the comprehensive design tokens CSS content.
     *
     * This method creates CSS custom properties for all design token categories:
     * typography, spacing, radius, shadow, animation, and glass effects.
     *
     * @since 2.0.0
     *
     * @param  array  $overrides  Optional. Custom token values to override defaults by category.
     *
     * @return string The CSS content for all design tokens.
     */
    public function generateDesignTokensCss( array $overrides = [] ): string
    {
        $css = "/**\n * ArtisanPack UI - Design Tokens\n * \n * This file is automatically generated. Do not edit directly.\n * Use the 'artisanpack:generate-theme' command to update.\n *\n * Token Categories:\n * - Typography (fonts, sizes, weights, line heights)\n * - Spacing (consistent spacing scale)\n * - Border Radius (rounded corners)\n * - Shadows (elevation system)\n * - Animation (durations, easings)\n * - Glass (glassmorphism effects)\n *\n * @since 2.0.0\n */\n\n";

        // Merge overrides with defaults
        $typography = array_merge( $this->typographyTokenDefaults, $overrides['typography'] ?? [] );
        $spacing    = array_merge( $this->spacingTokenDefaults, $overrides['spacing'] ?? [] );
        $radius     = array_merge( $this->radiusTokenDefaults, $overrides['radius'] ?? [] );
        $shadow     = array_merge( $this->shadowTokenDefaults, $overrides['shadow'] ?? [] );
        $animation  = array_merge( $this->animationTokenDefaults, $overrides['animation'] ?? [] );
        $glass      = array_merge( $this->glassTokenDefaults, $overrides['glass'] ?? [] );

        // Tailwind CSS v4 @theme directive
        $css .= "/**\n * Tailwind CSS v4 @theme integration\n * These tokens can be extended or overridden in your app.css using @theme\n */\n";
        $css .= "@theme {\n";

        $css .= "    /* --- Typography Tokens --- */\n";
        foreach ( $typography as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Spacing Tokens --- */\n";
        foreach ( $spacing as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Border Radius Tokens --- */\n";
        foreach ( $radius as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Shadow Tokens --- */\n";
        foreach ( $shadow as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Animation Tokens --- */\n";
        foreach ( $animation as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Glass Tokens --- */\n";
        foreach ( $glass as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "}\n\n";

        // Root-level tokens
        $css .= ":root {\n";

        $css .= "    /* =========================================================================\n";
        $css .= "     * Typography Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $typography as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Spacing Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $spacing as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Border Radius Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $radius as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Shadow Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $shadow as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Animation Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $animation as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Glass Tokens\n";
        $css .= "     * ========================================================================= */\n";
        foreach ( $glass as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* =========================================================================\n";
        $css .= "     * Computed Glass Colors (Light Mode)\n";
        $css .= "     * ========================================================================= */\n";
        $css .= "    --glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(255, 255, 255, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(255, 255, 255, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, var(--glass-liquid-border-glow));\n";
        $css .= "    --glass-transparent-bg-color: rgba(255, 255, 255, var(--glass-transparent-opacity));\n";

        $css .= "}\n";

        // Dark mode overrides
        $shadowDark = array_merge( $this->shadowTokenDarkDefaults, $overrides['shadow_dark'] ?? [] );
        $glassDark  = $this->glassTokenDarkDefaults;

        $css .= "\n[data-theme=\"dark\"] {\n";

        $css .= "    /* --- Shadow Token Dark Mode Overrides --- */\n";
        foreach ( $shadowDark as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Glass Token Dark Mode Overrides --- */\n";
        foreach ( $glassDark as $key => $value ) {
            $css .= "    --{$key}: {$value};\n";
        }

        $css .= "\n    /* --- Computed Glass Colors (Dark Mode) --- */\n";
        $css .= "    --glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));\n";
        $css .= "    --glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));\n";
        $css .= "    --glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));\n";
        $css .= "    --glass-frosted-bg-color: rgba(30, 30, 30, var(--glass-frosted-opacity));\n";
        $css .= "    --glass-liquid-bg-color: rgba(30, 30, 30, var(--glass-liquid-opacity));\n";
        $css .= "    --glass-liquid-border-glow-color: rgba(255, 255, 255, calc(var(--glass-liquid-border-glow) * 0.7));\n";
        $css .= "    --glass-transparent-bg-color: rgba(30, 30, 30, var(--glass-transparent-opacity));\n";

        $css .= "}\n";

        return $css;
    }

    /**
     * Component Color Resolution Methods
     *
     * The following methods provide color resolution and CSS class generation
     * for individual components, supporting predefined variants, Tailwind colors,
     * and custom hex codes with background adjustments.
     *
     * @since 1.0.0
     */

    /**
     * Resolves a color input to appropriate CSS classes for component usage.
     *
     * @since 1.0.0
     *
     * @param  string|null  $color  The color input (variant, tailwind color, or hex).
     * @param  string|null  $adjustment  Optional. Background adjustment (lighter, darker, transparent, subtle). Default null.
     * @param  string  $component  Optional. The component name for context. Default 'generic'.
     *
     * @return array Array of CSS classes [background, border, text].
     */
    public function resolveComponentColor(?string $color, ?string $adjustment = null, string $component = 'generic'): array
    {
        if (! $color) {
            return [];
        }

        // DatePicker-specific color handling
        if ('datepicker' === $component) {
            return $this->resolveDatePickerColors($color, $adjustment);
        }

        // Check if it's a predefined variant
        if ($this->isVariant($color)) {
            return $this->getVariantClasses($color, $adjustment, $component);
        }

        // Check if it's a Tailwind color with intensity
        if ($this->isTailwindColorWithIntensity($color)) {
            return $this->getTailwindClasses($color, $adjustment);
        }

        // Check if it's a Tailwind color name without intensity
        if ($this->isTailwindColorName($color)) {
            return $this->getTailwindClasses($color.'-500', $adjustment);
        }

        // Check if it's a hex color
        if ($this->isHexColor($color)) {
            return $this->getHexClasses($color, $adjustment);
        }

        // Invalid color, return empty array
        return [];
    }

    /**
     * Resolves DatePicker-specific colors and generates appropriate CSS custom properties.
     *
     * @since 1.0.0
     *
     * @param  string  $color  The color input (variant, tailwind color, or hex)
     * @param  string|null  $adjustment  Background adjustment (lighter, darker, transparent, subtle)
     *
     * @return array Array containing style CSS custom properties
     */
    public function resolveDatePickerColors(string $color, ?string $adjustment = null): array
    {
        $baseColor = null;
        $textColor = null;

        // Resolve the base color based on type
        if ($this->isVariant($color)) {
            $baseColor = $this->getVariantBaseColor($color);
        } elseif ($this->isTailwindColorWithIntensity($color)) {
            [$colorName, $intensity] = explode('-', $color);
            $baseColor               = $this->tailwindColorToHex($colorName, (int) $intensity);
        } elseif ($this->isTailwindColorName($color)) {
            $baseColor = $this->tailwindColorToHex($color, 500);
        } elseif ($this->isHexColor($color)) {
            $baseColor = $color;
        }

        if (! $baseColor) {
            return [];
        }

        // Generate contrasting text color
        $textColor = $this->getContrastingTextForHex($baseColor);

        // Apply adjustments to base color
        if ($adjustment) {
            $baseColor = $this->applyDatePickerAdjustment($baseColor, $adjustment);
        }

        // Generate CSS custom properties for DatePicker theming
        $cssVariables = [
            '--artisanpack-custom-color' => $baseColor,
            '--artisanpack-text-color'   => $textColor,
        ];

        // Return as style property
        return [
            'style' => implode(' ', array_map(fn ($key, $value) => "{$key}: {$value};", array_keys($cssVariables), $cssVariables)),
        ];
    }

    /**
     * Checks if a color input is a predefined variant.
     *
     * @since 1.0.0
     */
    protected function isVariant(string $color): bool
    {
        $variants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral', 'ghost', 'outline'];

        return in_array($color, $variants);
    }

    /**
     * Checks if a color input is a Tailwind color with intensity (e.g., 'red-500').
     *
     * @since 1.0.0
     */
    protected function isTailwindColorWithIntensity(string $color): bool
    {
        $pattern = '/^('.implode('|', array_keys($this->tailwindColorBases)).')-(50|100|200|300|400|500|600|700|800|900|950)$/';

        return (bool) preg_match($pattern, $color);
    }

    /**
     * Checks if a color input is a Tailwind color name (e.g., 'red').
     *
     * @since 1.0.0
     */
    protected function isTailwindColorName(string $color): bool
    {
        return array_key_exists($color, $this->tailwindColorBases);
    }

    /**
     * Checks if a color input is a valid hex color.
     *
     * @since 1.0.0
     */
    protected function isHexColor(string $color): bool
    {
        return (bool) preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color);
    }

    /**
     * Gets CSS classes for predefined variants.
     *
     * @since 1.0.0
     */
    protected function getVariantClasses(string $variant, ?string $adjustment, string $component): array
    {
        $classes = [];

        // Map variants to DaisyUI classes or Tailwind equivalents
        $variantMap = [
            'primary'   => ['bg' => 'bg-primary', 'border' => 'border-primary', 'text' => 'text-primary-content'],
            'secondary' => ['bg' => 'bg-secondary', 'border' => 'border-secondary', 'text' => 'text-secondary-content'],
            'accent'    => ['bg' => 'bg-accent', 'border' => 'border-accent', 'text' => 'text-accent-content'],
            'success'   => ['bg' => 'bg-success', 'border' => 'border-success', 'text' => 'text-success-content'],
            'warning'   => ['bg' => 'bg-warning', 'border' => 'border-warning', 'text' => 'text-warning-content'],
            'error'     => ['bg' => 'bg-error', 'border' => 'border-error', 'text' => 'text-error-content'],
            'info'      => ['bg' => 'bg-info', 'border' => 'border-info', 'text' => 'text-info-content'],
            'neutral'   => ['bg' => 'bg-neutral', 'border' => 'border-neutral', 'text' => 'text-neutral-content'],
            'ghost'     => ['bg' => 'bg-transparent', 'border' => 'border-transparent', 'text' => 'text-current'],
            'outline'   => ['bg' => 'bg-transparent', 'border' => 'border-current', 'text' => 'text-current'],
        ];

        if (! isset($variantMap[$variant])) {
            return [];
        }

        $baseClasses = $variantMap[$variant];

        // Add hover and focus states for variants (using custom darker versions)
        $this->addVariantHoverFocusStates($baseClasses, $variant);

        // Apply adjustments if specified
        if ($adjustment) {
            $baseClasses = $this->applyVariantAdjustment($baseClasses, $variant, $adjustment);
        }

        return $baseClasses;
    }

    /**
     * Adds hover and focus states for variant colors.
     *
     * @since 1.0.0
     */
    protected function addVariantHoverFocusStates(array &$classes, string $variant): void
    {
        // Map variants to approximate Tailwind colors for hover/focus generation
        $variantToTailwind = [
            'primary'   => 'blue-500',
            'secondary' => 'slate-500',
            'accent'    => 'amber-500',
            'success'   => 'green-500',
            'warning'   => 'orange-500',
            'error'     => 'red-500',
            'info'      => 'sky-500',
            'neutral'   => 'gray-500',
        ];

        // Skip hover/focus for transparent variants
        if (in_array($variant, ['ghost', 'outline'])) {
            return;
        }

        if (! isset($variantToTailwind[$variant])) {
            return;
        }

        [$colorName, $intensity] = explode('-', $variantToTailwind[$variant]);

        // Generate darker versions for hover/focus (same logic as getTailwindClasses)
        $hoverIntensity = min(950, (int) $intensity + 100);
        $focusIntensity = min(950, (int) $intensity + 100);

        $hoverHex = $this->tailwindColorToHex($colorName, $hoverIntensity);
        $focusHex = $this->tailwindColorToHex($colorName, $focusIntensity);

        $hoverProperty     = '--artisanpack-variant-hover-color';
        $focusProperty     = '--artisanpack-variant-focus-color';
        $hoverTextProperty = '--artisanpack-variant-hover-text';
        $focusTextProperty = '--artisanpack-variant-focus-text';

        if ($hoverHex) {
            // Calculate contrasting text color for hover state
            $hoverTextColor = $this->getContrastingTextForHex($hoverHex);
            $hoverTextHex   = 'text-black' === $hoverTextColor ? '#000000' : '#ffffff';

            $classes['style'] = isset($classes['style'])
                ? $classes['style']." {$hoverProperty}: {$hoverHex}; {$hoverTextProperty}: {$hoverTextHex};"
                : "{$hoverProperty}: {$hoverHex}; {$hoverTextProperty}: {$hoverTextHex};";
            $classes['hover'] = 'hover:bg-[var(--artisanpack-variant-hover-color)] hover:text-[var(--artisanpack-variant-hover-text)]';
        }

        if ($focusHex) {
            // Calculate contrasting text color for focus state
            $focusTextColor = $this->getContrastingTextForHex($focusHex);
            $focusTextHex   = 'text-black' === $focusTextColor ? '#000000' : '#ffffff';

            $classes['style'] = isset($classes['style'])
                ? $classes['style']." {$focusProperty}: {$focusHex}; {$focusTextProperty}: {$focusTextHex};"
                : "{$focusProperty}: {$focusHex}; {$focusTextProperty}: {$focusTextHex};";
            $classes['focus'] = 'focus:bg-[var(--artisanpack-variant-focus-color)] focus:text-[var(--artisanpack-variant-focus-text)]';
        }
    }

    /**
     * Gets CSS classes for Tailwind colors using JIT-compatible approach.
     *
     * @since 1.0.0
     */
    protected function getTailwindClasses(string $colorWithIntensity, ?string $adjustment): array
    {
        [$colorName, $intensity] = explode('-', $colorWithIntensity);

        // Convert Tailwind color to hex for JIT compatibility
        $hexColor = $this->tailwindColorToHex($colorName, (int) $intensity);

        if (! $hexColor) {
            // Fallback to original dynamic classes if color conversion fails
            return [
                'bg'     => "bg-{$colorWithIntensity}",
                'border' => "border-{$colorWithIntensity}",
                'text'   => $this->getContrastingTextClass($colorName, (int) $intensity),
            ];
        }

        $classes = [];

        // Use CSS custom properties for JIT compatibility
        $customProperty    = '--artisanpack-tailwind-color';
        $hoverProperty     = '--artisanpack-tailwind-hover-color';
        $focusProperty     = '--artisanpack-tailwind-focus-color';
        $hoverTextProperty = '--artisanpack-tailwind-hover-text';
        $focusTextProperty = '--artisanpack-tailwind-focus-text';

        $classes['style']  = "{$customProperty}: {$hexColor};";
        $classes['bg']     = 'bg-[var(--artisanpack-tailwind-color)]';
        $classes['border'] = 'border-[var(--artisanpack-tailwind-color)]';
        $classes['text']   = $this->getContrastingTextForHex($hexColor);

        // Generate hover and focus states (slightly darker)
        $hoverIntensity = min(950, (int) $intensity + 100);
        $focusIntensity = min(950, (int) $intensity + 100);

        $hoverHex = $this->tailwindColorToHex($colorName, $hoverIntensity);
        $focusHex = $this->tailwindColorToHex($colorName, $focusIntensity);

        if ($hoverHex) {
            // Calculate contrasting text color for hover state
            $hoverTextColor = $this->getContrastingTextForHex($hoverHex);
            $hoverTextHex   = 'text-black' === $hoverTextColor ? '#000000' : '#ffffff';

            $classes['style'] .= " {$hoverProperty}: {$hoverHex}; {$hoverTextProperty}: {$hoverTextHex};";
            $classes['hover'] = 'hover:bg-[var(--artisanpack-tailwind-hover-color)] hover:text-[var(--artisanpack-tailwind-hover-text)]';
        }

        if ($focusHex) {
            // Calculate contrasting text color for focus state
            $focusTextColor = $this->getContrastingTextForHex($focusHex);
            $focusTextHex   = 'text-black' === $focusTextColor ? '#000000' : '#ffffff';

            $classes['style'] .= " {$focusProperty}: {$focusHex}; {$focusTextProperty}: {$focusTextHex};";
            $classes['focus'] = 'focus:bg-[var(--artisanpack-tailwind-focus-color)] focus:text-[var(--artisanpack-tailwind-focus-text)]';
        }

        // Apply adjustments if specified
        if ($adjustment) {
            $classes = $this->applyTailwindAdjustment($classes, $colorName, (int) $intensity, $adjustment);
        }

        return $classes;
    }

    /**
     * Gets CSS classes for hex colors using custom properties.
     *
     * @since 1.0.0
     */
    protected function getHexClasses(string $hex, ?string $adjustment): array
    {
        $classes = [];

        // Use CSS custom properties for hex colors
        $customProperty    = '--artisanpack-custom-color';
        $hoverProperty     = '--artisanpack-custom-hover-color';
        $focusProperty     = '--artisanpack-custom-focus-color';
        $hoverTextProperty = '--artisanpack-custom-hover-text';
        $focusTextProperty = '--artisanpack-custom-focus-text';

        $classes['style']  = "{$customProperty}: {$hex};";
        $classes['bg']     = 'bg-[var(--artisanpack-custom-color)]';
        $classes['border'] = 'border-[var(--artisanpack-custom-color)]';
        $classes['text']   = $this->getContrastingTextForHex($hex);

        // Generate hover and focus states (slightly darker)
        $hoverHex = $this->adjustHexBrightness($hex, -0.2); // 20% darker
        $focusHex = $this->adjustHexBrightness($hex, -0.2); // 20% darker

        if ($hoverHex) {
            // Calculate contrasting text color for hover state
            $hoverTextColor = $this->getContrastingTextForHex($hoverHex);
            $hoverTextHex   = 'text-black' === $hoverTextColor ? '#000000' : '#ffffff';

            $classes['style'] .= " {$hoverProperty}: {$hoverHex}; {$hoverTextProperty}: {$hoverTextHex};";
            $classes['hover'] = 'hover:bg-[var(--artisanpack-custom-hover-color)] hover:text-[var(--artisanpack-custom-hover-text)]';
        }

        if ($focusHex) {
            // Calculate contrasting text color for focus state
            $focusTextColor = $this->getContrastingTextForHex($focusHex);
            $focusTextHex   = 'text-black' === $focusTextColor ? '#000000' : '#ffffff';

            $classes['style'] .= " {$focusProperty}: {$focusHex}; {$focusTextProperty}: {$focusTextHex};";
            $classes['focus'] = 'focus:bg-[var(--artisanpack-custom-focus-color)] focus:text-[var(--artisanpack-custom-focus-text)]';
        }

        // Apply adjustments if specified
        if ($adjustment) {
            $classes = $this->applyHexAdjustment($classes, $hex, $adjustment);
        }

        return $classes;
    }

    /**
     * Applies adjustment to variant-based classes.
     *
     * @since 1.0.0
     */
    protected function applyVariantAdjustment(array $classes, string $variant, string $adjustment): array
    {
        // Map variants to approximate Tailwind colors for adjustments
        $variantToTailwind = [
            'primary'   => 'blue-500',
            'secondary' => 'slate-500',
            'accent'    => 'amber-500',
            'success'   => 'green-500',
            'warning'   => 'orange-500',
            'error'     => 'red-500',
            'info'      => 'sky-500',
            'neutral'   => 'gray-500',
        ];

        if (! isset($variantToTailwind[$variant])) {
            return $classes;
        }

        [$colorName, $intensity] = explode('-', $variantToTailwind[$variant]);

        return $this->applyTailwindAdjustment($classes, $colorName, (int) $intensity, $adjustment);
    }

    /**
     * Applies adjustment to Tailwind-based classes using JIT-compatible approach.
     *
     * @since 1.0.0
     */
    protected function applyTailwindAdjustment(array $classes, string $colorName, int $intensity, string $adjustment): array
    {
        switch ($adjustment) {
            case 'lighter':
                $bgIntensity = max(50, $intensity - 400);
                $adjustedHex = $this->tailwindColorToHex($colorName, $bgIntensity);
                if ($adjustedHex) {
                    $classes['style'] = "--artisanpack-tailwind-color: {$adjustedHex};";
                    $classes['bg']    = 'bg-[var(--artisanpack-tailwind-color)]';
                    $classes['text']  = $this->getContrastingTextForHex($adjustedHex);
                }
                break;

            case 'darker':
                $bgIntensity = min(900, $intensity + 200);
                $adjustedHex = $this->tailwindColorToHex($colorName, $bgIntensity);
                if ($adjustedHex) {
                    $classes['style'] = "--artisanpack-tailwind-color: {$adjustedHex};";
                    $classes['bg']    = 'bg-[var(--artisanpack-tailwind-color)]';
                    $classes['text']  = $this->getContrastingTextForHex($adjustedHex);
                }
                break;

            case 'transparent':
                $classes['bg'] = 'bg-transparent';
                // Remove custom properties for transparent background
                if (isset($classes['style'])) {
                    unset($classes['style']);
                }
                break;

            case 'subtle':
                $adjustedHex = $this->tailwindColorToHex($colorName, 50);
                if ($adjustedHex) {
                    $classes['style'] = "--artisanpack-tailwind-color: {$adjustedHex};";
                    $classes['bg']    = 'bg-[var(--artisanpack-tailwind-color)]';
                    $classes['text']  = $this->getContrastingTextForHex($adjustedHex);
                }
                break;
        }

        return $classes;
    }

    /**
     * Applies adjustment to hex-based classes.
     *
     * @since 1.0.0
     */
    protected function applyHexAdjustment(array $classes, string $hex, string $adjustment): array
    {
        switch ($adjustment) {
            case 'lighter':
                $lightHex         = $this->adjustHexBrightness($hex, 0.3);
                $classes['style'] = "--artisanpack-custom-color: {$lightHex};";
                break;

            case 'darker':
                $darkHex          = $this->adjustHexBrightness($hex, -0.3);
                $classes['style'] = "--artisanpack-custom-color: {$darkHex};";
                break;

            case 'transparent':
                $classes['bg'] = 'bg-transparent';
                unset($classes['style']);
                break;

            case 'subtle':
                $subtleHex        = $this->adjustHexBrightness($hex, 0.7);
                $classes['style'] = "--artisanpack-custom-color: {$subtleHex};";
                break;
        }

        return $classes;
    }

    /**
     * Gets contrasting text class for Tailwind colors using JIT-compatible approach.
     *
     * @since 1.0.0
     */
    protected function getContrastingTextClass(string $colorName, int $intensity): string
    {
        // Convert to hex and use the hex-based method for JIT compatibility
        $hexColor = $this->tailwindColorToHex($colorName, $intensity);

        if ($hexColor) {
            return $this->getContrastingTextForHex($hexColor);
        }

        // Fallback to static classes for JIT compatibility
        if ($intensity <= 400) {
            return 'text-gray-900';
        }

        return 'text-white';
    }

    /**
     * Gets contrasting text for hex colors.
     *
     * @since 1.0.0
     */
    protected function getContrastingTextForHex(string $hex): string
    {
        // Simple brightness calculation
        $hex = ltrim($hex, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));

        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $brightness > 128 ? 'text-black' : 'text-white';
    }

    /**
     * Converts Tailwind color name and intensity to hex value.
     *
     * @since 1.0.0
     */
    protected function tailwindColorToHex(string $colorName, int $intensity): ?string
    {
        $tailwindColors = [
            'slate' => [
                50  => '#f8fafc', 100 => '#f1f5f9', 200 => '#e2e8f0', 300 => '#cbd5e1',
                400 => '#94a3b8', 500 => '#64748b', 600 => '#475569', 700 => '#334155',
                800 => '#1e293b', 900 => '#0f172a', 950 => '#020617',
            ],
            'gray' => [
                50  => '#f9fafb', 100 => '#f3f4f6', 200 => '#e5e7eb', 300 => '#d1d5db',
                400 => '#9ca3af', 500 => '#6b7280', 600 => '#4b5563', 700 => '#374151',
                800 => '#1f2937', 900 => '#111827', 950 => '#030712',
            ],
            'zinc' => [
                50  => '#fafafa', 100 => '#f4f4f5', 200 => '#e4e4e7', 300 => '#d4d4d8',
                400 => '#a1a1aa', 500 => '#71717a', 600 => '#52525b', 700 => '#3f3f46',
                800 => '#27272a', 900 => '#18181b', 950 => '#09090b',
            ],
            'neutral' => [
                50  => '#fafafa', 100 => '#f5f5f5', 200 => '#e5e5e5', 300 => '#d4d4d4',
                400 => '#a3a3a3', 500 => '#737373', 600 => '#525252', 700 => '#404040',
                800 => '#262626', 900 => '#171717', 950 => '#0a0a0a',
            ],
            'stone' => [
                50  => '#fafaf9', 100 => '#f5f5f4', 200 => '#e7e5e4', 300 => '#d6d3d1',
                400 => '#a8a29e', 500 => '#78716c', 600 => '#57534e', 700 => '#44403c',
                800 => '#292524', 900 => '#1c1917', 950 => '#0c0a09',
            ],
            'red' => [
                50  => '#fef2f2', 100 => '#fee2e2', 200 => '#fecaca', 300 => '#fca5a5',
                400 => '#f87171', 500 => '#ef4444', 600 => '#dc2626', 700 => '#b91c1c',
                800 => '#991b1b', 900 => '#7f1d1d', 950 => '#450a0a',
            ],
            'orange' => [
                50  => '#fff7ed', 100 => '#ffedd5', 200 => '#fed7aa', 300 => '#fdba74',
                400 => '#fb923c', 500 => '#f97316', 600 => '#ea580c', 700 => '#c2410c',
                800 => '#9a3412', 900 => '#7c2d12', 950 => '#431407',
            ],
            'amber' => [
                50  => '#fffbeb', 100 => '#fef3c7', 200 => '#fde68a', 300 => '#fcd34d',
                400 => '#fbbf24', 500 => '#f59e0b', 600 => '#d97706', 700 => '#b45309',
                800 => '#92400e', 900 => '#78350f', 950 => '#451a03',
            ],
            'yellow' => [
                50  => '#fefce8', 100 => '#fef9c3', 200 => '#fef08a', 300 => '#fde047',
                400 => '#facc15', 500 => '#eab308', 600 => '#ca8a04', 700 => '#a16207',
                800 => '#854d0e', 900 => '#713f12', 950 => '#422006',
            ],
            'lime' => [
                50  => '#f7fee7', 100 => '#ecfccb', 200 => '#d9f99d', 300 => '#bef264',
                400 => '#a3e635', 500 => '#84cc16', 600 => '#65a30d', 700 => '#4d7c0f',
                800 => '#3f6212', 900 => '#365314', 950 => '#1a2e05',
            ],
            'green' => [
                50  => '#f0fdf4', 100 => '#dcfce7', 200 => '#bbf7d0', 300 => '#86efac',
                400 => '#4ade80', 500 => '#22c55e', 600 => '#16a34a', 700 => '#15803d',
                800 => '#166534', 900 => '#14532d', 950 => '#052e16',
            ],
            'emerald' => [
                50  => '#ecfdf5', 100 => '#d1fae5', 200 => '#a7f3d0', 300 => '#6ee7b7',
                400 => '#34d399', 500 => '#10b981', 600 => '#059669', 700 => '#047857',
                800 => '#065f46', 900 => '#064e3b', 950 => '#022c22',
            ],
            'teal' => [
                50  => '#f0fdfa', 100 => '#ccfbf1', 200 => '#99f6e4', 300 => '#5eead4',
                400 => '#2dd4bf', 500 => '#14b8a6', 600 => '#0d9488', 700 => '#0f766e',
                800 => '#115e59', 900 => '#134e4a', 950 => '#042f2e',
            ],
            'cyan' => [
                50  => '#ecfeff', 100 => '#cffafe', 200 => '#a5f3fc', 300 => '#67e8f9',
                400 => '#22d3ee', 500 => '#06b6d4', 600 => '#0891b2', 700 => '#0e7490',
                800 => '#155e75', 900 => '#164e63', 950 => '#083344',
            ],
            'sky' => [
                50  => '#f0f9ff', 100 => '#e0f2fe', 200 => '#bae6fd', 300 => '#7dd3fc',
                400 => '#38bdf8', 500 => '#0ea5e9', 600 => '#0284c7', 700 => '#0369a1',
                800 => '#075985', 900 => '#0c4a6e', 950 => '#082f49',
            ],
            'blue' => [
                50  => '#eff6ff', 100 => '#dbeafe', 200 => '#bfdbfe', 300 => '#93c5fd',
                400 => '#60a5fa', 500 => '#3b82f6', 600 => '#2563eb', 700 => '#1d4ed8',
                800 => '#1e40af', 900 => '#1e3a8a', 950 => '#172554',
            ],
            'indigo' => [
                50  => '#eef2ff', 100 => '#e0e7ff', 200 => '#c7d2fe', 300 => '#a5b4fc',
                400 => '#818cf8', 500 => '#6366f1', 600 => '#4f46e5', 700 => '#4338ca',
                800 => '#3730a3', 900 => '#312e81', 950 => '#1e1b4b',
            ],
            'violet' => [
                50  => '#f5f3ff', 100 => '#ede9fe', 200 => '#ddd6fe', 300 => '#c4b5fd',
                400 => '#a78bfa', 500 => '#8b5cf6', 600 => '#7c3aed', 700 => '#6d28d9',
                800 => '#5b21b6', 900 => '#4c1d95', 950 => '#2e1065',
            ],
            'purple' => [
                50  => '#faf5ff', 100 => '#f3e8ff', 200 => '#e9d5ff', 300 => '#d8b4fe',
                400 => '#c084fc', 500 => '#a855f7', 600 => '#9333ea', 700 => '#7e22ce',
                800 => '#6b21a8', 900 => '#581c87', 950 => '#3b0764',
            ],
            'fuchsia' => [
                50  => '#fdf4ff', 100 => '#fae8ff', 200 => '#f5d0fe', 300 => '#f0abfc',
                400 => '#e879f9', 500 => '#d946ef', 600 => '#c026d3', 700 => '#a21caf',
                800 => '#86198f', 900 => '#701a75', 950 => '#4a044e',
            ],
            'pink' => [
                50  => '#fdf2f8', 100 => '#fce7f3', 200 => '#fbcfe8', 300 => '#f9a8d4',
                400 => '#f472b6', 500 => '#ec4899', 600 => '#db2777', 700 => '#be185d',
                800 => '#9d174d', 900 => '#831843', 950 => '#500724',
            ],
            'rose' => [
                50  => '#fff1f2', 100 => '#ffe4e6', 200 => '#fecdd3', 300 => '#fda4af',
                400 => '#fb7185', 500 => '#f43f5e', 600 => '#e11d48', 700 => '#be123c',
                800 => '#9f1239', 900 => '#881337', 950 => '#4c0519',
            ],
        ];

        return $tailwindColors[$colorName][$intensity] ?? null;
    }

    /**
     * Adjusts hex color brightness.
     *
     * @since 1.0.0
     */
    protected function adjustHexBrightness(string $hex, float $percent): string
    {
        $hex = ltrim($hex, '#');

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, max(0, $r + ($r * $percent)));
        $g = min(255, max(0, $g + ($g * $percent)));
        $b = min(255, max(0, $b + ($b * $percent)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Gets the base hex color for a variant.
     *
     * @since 1.0.0
     */
    protected function getVariantBaseColor(string $variant): ?string
    {
        $variantColors = [
            'primary'   => '#3b82f6',    // Blue 500
            'secondary' => '#6b7280',  // Gray 500
            'accent'    => '#06b6d4',     // Cyan 500
            'success'   => '#10b981',    // Emerald 500
            'warning'   => '#f59e0b',    // Amber 500
            'error'     => '#ef4444',      // Red 500
            'info'      => '#0ea5e9',       // Sky 500
            'neutral'   => '#737373',    // Neutral 500
        ];

        return $variantColors[$variant] ?? null;
    }

    /**
     * Applies DatePicker-specific color adjustments.
     *
     * @since 1.0.0
     *
     * @param  string  $color  Base hex color
     * @param  string  $adjustment  Adjustment type
     *
     * @return string Adjusted hex color
     */
    protected function applyDatePickerAdjustment(string $color, string $adjustment): string
    {
        switch ($adjustment) {
            case 'lighter':
                return $this->adjustHexBrightness($color, 0.3);
            case 'darker':
                return $this->adjustHexBrightness($color, -0.3);
            case 'subtle':
                return $this->adjustHexBrightness($color, 0.7);
            case 'transparent':
                // For transparent, we'll still return the color but the CSS will handle transparency
                return $color;
            default:
                return $color;
        }
    }
}
