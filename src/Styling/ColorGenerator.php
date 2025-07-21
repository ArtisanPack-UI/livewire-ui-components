<?php
/**
 * Color and Theme Generator for ArtisanPack UI.
 *
 * This class provides utilities for generating full color palettes from base colors,
 * calculating accessible contrast colors, and producing a complete CSS theme file
 * compatible with both ArtisanPack UI components and the DaisyUI framework.
 *
 * @package    ArtisanPackUI\LivewireUIComponents\Styling
 * @subpackage ArtisanPackUI\LivewireUIComponents\Styling\ColorGenerator
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Styling;

use ArtisanPackUI\Accessibility\Facades\A11y;
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
	 * @var   array
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
	 * @var   array
	 * @link  https://daisyui.com/docs/colors/
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
	 * @since 1.0.2
	 * @var   array
	 * @link  https://daisyui.com/docs/colors/
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
	 * @var   array
	 * @link  https://daisyui.com/docs/utilities/
	 */
    protected array $daisyUiUtilityDefaults = [
        '--rounded-box'    => '1rem',
        '--rounded-btn'    => '0.5rem',
        '--rounded-badge'  => '1.9rem',
        '--animation-btn'  => '0.25s',
        '--animation-input'=> '0.2s',
        '--btn-focus-scale'=> '0.95',
        '--border-btn'     => '1px',
        '--tab-border'     => '1px',
        '--tab-radius'     => '0.5rem',
    ];

    /**
     * Default DaisyUI component-specific variables.
     * These are applied to component classes, not :root.
     *
     * @since 1.0.1
     * @var array
     */
    protected array $daisyUiComponentDefaults = [
        'Alert' => [
            'selector' => '.alert',
            'variables' => [
                '--alert-color' => 'oklch(var(--b2))',
            ]
        ],
        'Badge' => [
            'selector' => '.badge',
            'variables' => [
                '--badge-color' => 'oklch(var(--b2))',
                '--size' => '1.2rem',
            ]
        ],
        'Button' => [
            'selector' => '.btn',
            'variables' => [
                '--btn-color' => 'oklch(var(--b2))',
                '--btn-fg' => 'oklch(var(--bc))',
                '--size' => '3rem',
            ]
        ],
        'Card' => [
            'selector' => '.card',
            'variables' => [
                '--card-p' => '1.5rem',
                '--card-fs' => '1rem',
                '--card-title-fs' => '1.25rem',
            ]
        ],
        'Checkbox' => [
            'selector' => '.checkbox',
            'variables' => [
                '--size' => '1.5rem',
            ]
        ],
        'Input' => [
            'selector' => '.input',
            'variables' => [
                '--input-color' => 'oklch(var(--b2))',
                '--size' => '3rem',
            ]
        ],
        'Menu' => [
            'selector' => '.menu',
            'variables' => [
                '--menu-active-fg' => 'oklch(var(--pc))',
                '--menu-active-bg' => 'oklch(var(--p))',
            ]
        ],
        'Modal' => [
            'selector' => '.modal-box',
            'variables' => [
                '--modal-tl' => '1rem',
                '--modal-tr' => '1rem',
                '--modal-bl' => '1rem',
                '--modal-br' => '1rem',
            ]
        ],
        'Toggle' => [
            'selector' => '.toggle',
            'variables' => [
                '--size' => '1.5rem',
            ]
        ],
        'Tooltip' => [
            'selector' => '.tooltip',
            'variables' => [
                '--tt-bg' => 'oklch(var(--b2))',
                '--tt-off' => '0.5rem',
            ]
        ],
    ];

	/**
	 * Retrieves the base hex color for a given Tailwind color name or validates a hex code.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $colorInput The name of the Tailwind color (e.g., 'sky') or a hex code.
	 * @return string The hex code for the base color.
	 * @throws InvalidArgumentException If the color name is not found or hex is invalid.
	 */
	public function getBaseColor( string $colorInput ): string
	{
		if ( preg_match( '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $colorInput ) ) {
			return $colorInput;
		}

		$normalizedColorName = strtolower( $colorInput );
		if ( isset( $this->tailwindColorBases[ $normalizedColorName ] ) ) {
			return $this->tailwindColorBases[ $normalizedColorName ];
		}

		throw new InvalidArgumentException( "Color '{$colorInput}' is not a valid hex code or predefined Tailwind color." );
	}

	/**
	 * Generates a full 10-stop color palette from a single base hex color.
	 *
	 * This method uses an external API to generate shades from 50 to 900.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $baseHex The base hex color (e.g., '#3B82F6').
	 * @return array An associative array of color shades (50, 100, ..., 900).
	 */
	public function generateTailwindShades( string $baseHex ): array
	{
		$shades = [];
		// The API endpoint from "Tints and Shades Generator" is used here.
		$response = Http::get( 'https://tailwind.simeongriggs.dev/api/generated-color/' . substr( $baseHex, 1 ) );

		if ( $response->successful() && isset( $response->json()['generated-color'] ) ) {
			$apiShades = $response->json()['generated-color'];
			$stops     = [ 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950 ];
			foreach ( $stops as $stop ) {
				// The API returns shades from lightest to darkest.
				$shades[ $stop ] = $apiShades[ $stop ];
			}
		} else {
			// Fallback to a simple array if the API fails
			$stops     = [ 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950 ];
			foreach ( $stops as $stop ) {
				$shades[ $stop ] = $baseHex;
			}
		}

		return $shades;
	}

	/**
	 * Generates the complete CSS theme file content.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $primaryColor  The name or hex of the primary color.
	 * @param  string $secondaryColor The name or hex of the secondary color.
	 * @param  string $accentColor   The name or hex of the accent color.
	 * @return string The full CSS content for the theme file.
	 */
	public function generateThemeCss( string $primaryColor, string $secondaryColor, string $accentColor ): string
	{
		$a11y = new A11y();

		$primaryBase     = $this->getBaseColor( $primaryColor );
		$secondaryBase   = $this->getBaseColor( $secondaryColor );
		$accentBase      = $this->getBaseColor( $accentColor );

		$primaryShades   = $this->generateTailwindShades( $primaryBase );
		$secondaryShades = $this->generateTailwindShades( $secondaryBase );
		$accentShades    = $this->generateTailwindShades( $accentBase );

		$daisyTheme                  = $this->daisyUiColorDefaults;
		$daisyTheme['primary']       = $primaryBase;
		$daisyTheme['primary-content']  = generateAccessibleTextColor( $primaryBase );
		$daisyTheme['secondary']     = $secondaryBase;
		$daisyTheme['secondary-content'] = generateAccessibleTextColor( $secondaryBase );
		$daisyTheme['accent']        = $accentBase;
		$daisyTheme['accent-content']  = generateAccessibleTextColor( $accentBase );

        $css = "/**\n * ArtisanPack UI - Generated Theme\n * \n * This file is automatically generated. Do not edit directly.\n * Use the 'artisanpack:generate-theme' command to update.\n */\n\n";

        // :root variables for colors and global utilities
        $css .= ":root {\n";

        $css .= "    /* --- ArtisanPack UI Color Palette --- */\n";
        foreach ( $primaryShades as $stop => $hex ) {
            $css .= "    --p-{$stop}: {$hex};\n";
        }
        $css .= "\n";
        foreach ( $secondaryShades as $stop => $hex ) {
            $css .= "    --s-{$stop}: {$hex};\n";
        }
        $css .= "\n";
        foreach ( $accentShades as $stop => $hex ) {
            $css .= "    --a-{$stop}: {$hex};\n";
        }

        $css .= "\n    /* --- DaisyUI Color Variables --- */\n";
        foreach ( $daisyTheme as $key => $value ) {
            $css .= "    --color-{$key}: {$value};\n";
        }

        $css .= "\n    /* --- DaisyUI Global Utility Variables --- */\n";
        foreach ( $this->daisyUiUtilityDefaults as $key => $value ) {
            $css .= "    {$key}: {$value};\n";
        }

        $css .= "}\n";

		// Dark mode overrides
		$css .= "\n[data-theme=\"dark\"] {\n";
		$css .= "    /* --- ArtisanPack UI Dark Mode Overrides --- */\n";
		foreach ( $this->daisyUiDarkColorDefaults as $key => $value ) {
			$css .= "    --color-{$key}: {$value};\n";
		}
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
}