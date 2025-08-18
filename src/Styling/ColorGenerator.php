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

	/**
	 * Component Color Resolution Methods
	 * 
	 * The following methods provide color resolution and CSS class generation
	 * for individual components, supporting predefined variants, Tailwind colors,
	 * and custom hex codes with background adjustments.
	 * 
	 * @since 1.1.0
	 */

	/**
	 * Resolves a color input to appropriate CSS classes for component usage.
	 *
	 * @since 1.1.0
	 *
	 * @param  string|null $color The color input (variant, tailwind color, or hex)
	 * @param  string|null $adjustment Background adjustment (lighter, darker, transparent, subtle)
	 * @param  string $component The component name for context
	 * @return array Array of CSS classes [background, border, text]
	 */
	public function resolveComponentColor(?string $color, ?string $adjustment = null, string $component = 'generic'): array
	{
		if (!$color) {
			return [];
		}

		// DatePicker-specific color handling
		if ($component === 'datepicker') {
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
			return $this->getTailwindClasses($color . '-500', $adjustment);
		}

		// Check if it's a hex color
		if ($this->isHexColor($color)) {
			return $this->getHexClasses($color, $adjustment);
		}

		// Invalid color, return empty array
		return [];
	}

	/**
	 * Checks if a color input is a predefined variant.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color
	 * @return bool
	 */
	protected function isVariant(string $color): bool
	{
		$variants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral', 'ghost', 'outline'];
		return in_array($color, $variants);
	}

	/**
	 * Checks if a color input is a Tailwind color with intensity (e.g., 'red-500').
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color
	 * @return bool
	 */
	protected function isTailwindColorWithIntensity(string $color): bool
	{
		$pattern = '/^(' . implode('|', array_keys($this->tailwindColorBases)) . ')-(50|100|200|300|400|500|600|700|800|900|950)$/';
		return preg_match($pattern, $color);
	}

	/**
	 * Checks if a color input is a Tailwind color name (e.g., 'red').
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color
	 * @return bool
	 */
	protected function isTailwindColorName(string $color): bool
	{
		return array_key_exists($color, $this->tailwindColorBases);
	}

	/**
	 * Checks if a color input is a valid hex color.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color
	 * @return bool
	 */
	protected function isHexColor(string $color): bool
	{
		return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color);
	}

	/**
	 * Gets CSS classes for predefined variants.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $variant
	 * @param  string|null $adjustment
	 * @param  string $component
	 * @return array
	 */
	protected function getVariantClasses(string $variant, ?string $adjustment, string $component): array
	{
		$classes = [];

		// Map variants to DaisyUI classes or Tailwind equivalents
		$variantMap = [
			'primary' => ['bg' => 'bg-primary', 'border' => 'border-primary', 'text' => 'text-primary-content'],
			'secondary' => ['bg' => 'bg-secondary', 'border' => 'border-secondary', 'text' => 'text-secondary-content'],
			'accent' => ['bg' => 'bg-accent', 'border' => 'border-accent', 'text' => 'text-accent-content'],
			'success' => ['bg' => 'bg-success', 'border' => 'border-success', 'text' => 'text-success-content'],
			'warning' => ['bg' => 'bg-warning', 'border' => 'border-warning', 'text' => 'text-warning-content'],
			'error' => ['bg' => 'bg-error', 'border' => 'border-error', 'text' => 'text-error-content'],
			'info' => ['bg' => 'bg-info', 'border' => 'border-info', 'text' => 'text-info-content'],
			'neutral' => ['bg' => 'bg-neutral', 'border' => 'border-neutral', 'text' => 'text-neutral-content'],
			'ghost' => ['bg' => 'bg-transparent', 'border' => 'border-transparent', 'text' => 'text-current'],
			'outline' => ['bg' => 'bg-transparent', 'border' => 'border-current', 'text' => 'text-current'],
		];

		if (!isset($variantMap[$variant])) {
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
	 * @since 1.1.0
	 *
	 * @param  array $classes
	 * @param  string $variant
	 * @return void
	 */
	protected function addVariantHoverFocusStates(array &$classes, string $variant): void
	{
		// Map variants to approximate Tailwind colors for hover/focus generation
		$variantToTailwind = [
			'primary' => 'blue-500',
			'secondary' => 'slate-500',
			'accent' => 'amber-500',
			'success' => 'green-500',
			'warning' => 'orange-500',
			'error' => 'red-500',
			'info' => 'sky-500',
			'neutral' => 'gray-500',
		];

		// Skip hover/focus for transparent variants
		if (in_array($variant, ['ghost', 'outline'])) {
			return;
		}

		if (!isset($variantToTailwind[$variant])) {
			return;
		}

		[$colorName, $intensity] = explode('-', $variantToTailwind[$variant]);
		
		// Generate darker versions for hover/focus (same logic as getTailwindClasses)
		$hoverIntensity = min(950, (int)$intensity + 100);
		$focusIntensity = min(950, (int)$intensity + 100);
		
		$hoverHex = $this->tailwindColorToHex($colorName, $hoverIntensity);
		$focusHex = $this->tailwindColorToHex($colorName, $focusIntensity);
		
		$hoverProperty = '--artisanpack-variant-hover-color';
		$focusProperty = '--artisanpack-variant-focus-color';
		
		if ($hoverHex) {
			$classes['style'] = isset($classes['style']) 
				? $classes['style'] . " {$hoverProperty}: {$hoverHex};" 
				: "{$hoverProperty}: {$hoverHex};";
			$classes['hover'] = 'hover:[background-color:var(--artisanpack-variant-hover-color)]';
		}
		
		if ($focusHex) {
			$classes['style'] = isset($classes['style']) 
				? $classes['style'] . " {$focusProperty}: {$focusHex};" 
				: (isset($classes['style']) ? $classes['style'] : '') . " {$focusProperty}: {$focusHex};";
			$classes['focus'] = 'focus:[background-color:var(--artisanpack-variant-focus-color)]';
		}
	}

	/**
	 * Gets CSS classes for Tailwind colors using JIT-compatible approach.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $colorWithIntensity
	 * @param  string|null $adjustment
	 * @return array
	 */
	protected function getTailwindClasses(string $colorWithIntensity, ?string $adjustment): array
	{
		[$colorName, $intensity] = explode('-', $colorWithIntensity);
		
		// Convert Tailwind color to hex for JIT compatibility
		$hexColor = $this->tailwindColorToHex($colorName, (int)$intensity);
		
		if (!$hexColor) {
			// Fallback to original dynamic classes if color conversion fails
			return [
				'bg' => "bg-{$colorWithIntensity}",
				'border' => "border-{$colorWithIntensity}",
				'text' => $this->getContrastingTextClass($colorName, (int)$intensity),
			];
		}
		
		$classes = [];
		
		// Use CSS custom properties for JIT compatibility
		$customProperty = '--artisanpack-tailwind-color';
		$hoverProperty = '--artisanpack-tailwind-hover-color';
		$focusProperty = '--artisanpack-tailwind-focus-color';
		
		$classes['style'] = "{$customProperty}: {$hexColor};";
		$classes['bg'] = '[background-color:var(--artisanpack-tailwind-color)]';
		$classes['border'] = '[border-color:var(--artisanpack-tailwind-color)]';
		$classes['text'] = $this->getContrastingTextForHex($hexColor);

		// Generate hover and focus states (slightly darker)
		$hoverIntensity = min(950, (int)$intensity + 100);
		$focusIntensity = min(950, (int)$intensity + 100);
		
		$hoverHex = $this->tailwindColorToHex($colorName, $hoverIntensity);
		$focusHex = $this->tailwindColorToHex($colorName, $focusIntensity);
		
		if ($hoverHex) {
			$classes['style'] .= " {$hoverProperty}: {$hoverHex};";
			$classes['hover'] = 'hover:[background-color:var(--artisanpack-tailwind-hover-color)]';
		}
		
		if ($focusHex) {
			$classes['style'] .= " {$focusProperty}: {$focusHex};";
			$classes['focus'] = 'focus:[background-color:var(--artisanpack-tailwind-focus-color)]';
		}

		// Apply adjustments if specified
		if ($adjustment) {
			$classes = $this->applyTailwindAdjustment($classes, $colorName, (int)$intensity, $adjustment);
		}

		return $classes;
	}

	/**
	 * Gets CSS classes for hex colors using custom properties.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $hex
	 * @param  string|null $adjustment
	 * @return array
	 */
	protected function getHexClasses(string $hex, ?string $adjustment): array
	{
		$classes = [];
		
		// Use CSS custom properties for hex colors
		$customProperty = '--artisanpack-custom-color';
		$hoverProperty = '--artisanpack-custom-hover-color';
		$focusProperty = '--artisanpack-custom-focus-color';
		
		$classes['style'] = "{$customProperty}: {$hex};";
		$classes['bg'] = '[background-color:var(--artisanpack-custom-color)]';
		$classes['border'] = '[border-color:var(--artisanpack-custom-color)]';
		$classes['text'] = $this->getContrastingTextForHex($hex);

		// Generate hover and focus states (slightly darker)
		$hoverHex = $this->adjustHexBrightness($hex, -0.2); // 20% darker
		$focusHex = $this->adjustHexBrightness($hex, -0.2); // 20% darker
		
		if ($hoverHex) {
			$classes['style'] .= " {$hoverProperty}: {$hoverHex};";
			$classes['hover'] = 'hover:[background-color:var(--artisanpack-custom-hover-color)]';
		}
		
		if ($focusHex) {
			$classes['style'] .= " {$focusProperty}: {$focusHex};";
			$classes['focus'] = 'focus:[background-color:var(--artisanpack-custom-focus-color)]';
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
	 * @since 1.1.0
	 *
	 * @param  array $classes
	 * @param  string $variant
	 * @param  string $adjustment
	 * @return array
	 */
	protected function applyVariantAdjustment(array $classes, string $variant, string $adjustment): array
	{
		// Map variants to approximate Tailwind colors for adjustments
		$variantToTailwind = [
			'primary' => 'blue-500',
			'secondary' => 'slate-500',
			'accent' => 'amber-500',
			'success' => 'green-500',
			'warning' => 'orange-500',
			'error' => 'red-500',
			'info' => 'sky-500',
			'neutral' => 'gray-500',
		];

		if (!isset($variantToTailwind[$variant])) {
			return $classes;
		}

		[$colorName, $intensity] = explode('-', $variantToTailwind[$variant]);
		return $this->applyTailwindAdjustment($classes, $colorName, (int)$intensity, $adjustment);
	}

	/**
	 * Applies adjustment to Tailwind-based classes using JIT-compatible approach.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $classes
	 * @param  string $colorName
	 * @param  int $intensity
	 * @param  string $adjustment
	 * @return array
	 */
	protected function applyTailwindAdjustment(array $classes, string $colorName, int $intensity, string $adjustment): array
	{
		switch ($adjustment) {
			case 'lighter':
				$bgIntensity = max(50, $intensity - 400);
				$adjustedHex = $this->tailwindColorToHex($colorName, $bgIntensity);
				if ($adjustedHex) {
					$classes['style'] = "--artisanpack-tailwind-color: {$adjustedHex};";
					$classes['bg'] = '[background-color:var(--artisanpack-tailwind-color)]';
					$classes['text'] = $this->getContrastingTextForHex($adjustedHex);
				}
				break;

			case 'darker':
				$bgIntensity = min(900, $intensity + 200);
				$adjustedHex = $this->tailwindColorToHex($colorName, $bgIntensity);
				if ($adjustedHex) {
					$classes['style'] = "--artisanpack-tailwind-color: {$adjustedHex};";
					$classes['bg'] = '[background-color:var(--artisanpack-tailwind-color)]';
					$classes['text'] = $this->getContrastingTextForHex($adjustedHex);
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
					$classes['bg'] = '[background-color:var(--artisanpack-tailwind-color)]';
					$classes['text'] = $this->getContrastingTextForHex($adjustedHex);
				}
				break;
		}

		return $classes;
	}

	/**
	 * Applies adjustment to hex-based classes.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $classes
	 * @param  string $hex
	 * @param  string $adjustment
	 * @return array
	 */
	protected function applyHexAdjustment(array $classes, string $hex, string $adjustment): array
	{
		switch ($adjustment) {
			case 'lighter':
				$lightHex = $this->adjustHexBrightness($hex, 0.3);
				$classes['style'] = "--artisanpack-custom-color: {$lightHex};";
				break;

			case 'darker':
				$darkHex = $this->adjustHexBrightness($hex, -0.3);
				$classes['style'] = "--artisanpack-custom-color: {$darkHex};";
				break;

			case 'transparent':
				$classes['bg'] = 'bg-transparent';
				unset($classes['style']);
				break;

			case 'subtle':
				$subtleHex = $this->adjustHexBrightness($hex, 0.7);
				$classes['style'] = "--artisanpack-custom-color: {$subtleHex};";
				break;
		}

		return $classes;
	}

	/**
	 * Gets contrasting text class for Tailwind colors using JIT-compatible approach.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $colorName
	 * @param  int $intensity
	 * @return string
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
	 * @since 1.1.0
	 *
	 * @param  string $hex
	 * @return string
	 */
	protected function getContrastingTextForHex(string $hex): string
	{
		// Simple brightness calculation
		$hex = ltrim($hex, '#');
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
		
		$brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
		
		return $brightness > 128 ? 'text-black' : 'text-white';
	}

	/**
	 * Converts Tailwind color name and intensity to hex value.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $colorName
	 * @param  int $intensity
	 * @return string|null
	 */
	protected function tailwindColorToHex(string $colorName, int $intensity): ?string
	{
		$tailwindColors = [
			'slate' => [
				50 => '#f8fafc', 100 => '#f1f5f9', 200 => '#e2e8f0', 300 => '#cbd5e1',
				400 => '#94a3b8', 500 => '#64748b', 600 => '#475569', 700 => '#334155',
				800 => '#1e293b', 900 => '#0f172a', 950 => '#020617'
			],
			'gray' => [
				50 => '#f9fafb', 100 => '#f3f4f6', 200 => '#e5e7eb', 300 => '#d1d5db',
				400 => '#9ca3af', 500 => '#6b7280', 600 => '#4b5563', 700 => '#374151',
				800 => '#1f2937', 900 => '#111827', 950 => '#030712'
			],
			'zinc' => [
				50 => '#fafafa', 100 => '#f4f4f5', 200 => '#e4e4e7', 300 => '#d4d4d8',
				400 => '#a1a1aa', 500 => '#71717a', 600 => '#52525b', 700 => '#3f3f46',
				800 => '#27272a', 900 => '#18181b', 950 => '#09090b'
			],
			'neutral' => [
				50 => '#fafafa', 100 => '#f5f5f5', 200 => '#e5e5e5', 300 => '#d4d4d4',
				400 => '#a3a3a3', 500 => '#737373', 600 => '#525252', 700 => '#404040',
				800 => '#262626', 900 => '#171717', 950 => '#0a0a0a'
			],
			'stone' => [
				50 => '#fafaf9', 100 => '#f5f5f4', 200 => '#e7e5e4', 300 => '#d6d3d1',
				400 => '#a8a29e', 500 => '#78716c', 600 => '#57534e', 700 => '#44403c',
				800 => '#292524', 900 => '#1c1917', 950 => '#0c0a09'
			],
			'red' => [
				50 => '#fef2f2', 100 => '#fee2e2', 200 => '#fecaca', 300 => '#fca5a5',
				400 => '#f87171', 500 => '#ef4444', 600 => '#dc2626', 700 => '#b91c1c',
				800 => '#991b1b', 900 => '#7f1d1d', 950 => '#450a0a'
			],
			'orange' => [
				50 => '#fff7ed', 100 => '#ffedd5', 200 => '#fed7aa', 300 => '#fdba74',
				400 => '#fb923c', 500 => '#f97316', 600 => '#ea580c', 700 => '#c2410c',
				800 => '#9a3412', 900 => '#7c2d12', 950 => '#431407'
			],
			'amber' => [
				50 => '#fffbeb', 100 => '#fef3c7', 200 => '#fde68a', 300 => '#fcd34d',
				400 => '#fbbf24', 500 => '#f59e0b', 600 => '#d97706', 700 => '#b45309',
				800 => '#92400e', 900 => '#78350f', 950 => '#451a03'
			],
			'yellow' => [
				50 => '#fefce8', 100 => '#fef9c3', 200 => '#fef08a', 300 => '#fde047',
				400 => '#facc15', 500 => '#eab308', 600 => '#ca8a04', 700 => '#a16207',
				800 => '#854d0e', 900 => '#713f12', 950 => '#422006'
			],
			'lime' => [
				50 => '#f7fee7', 100 => '#ecfccb', 200 => '#d9f99d', 300 => '#bef264',
				400 => '#a3e635', 500 => '#84cc16', 600 => '#65a30d', 700 => '#4d7c0f',
				800 => '#3f6212', 900 => '#365314', 950 => '#1a2e05'
			],
			'green' => [
				50 => '#f0fdf4', 100 => '#dcfce7', 200 => '#bbf7d0', 300 => '#86efac',
				400 => '#4ade80', 500 => '#22c55e', 600 => '#16a34a', 700 => '#15803d',
				800 => '#166534', 900 => '#14532d', 950 => '#052e16'
			],
			'emerald' => [
				50 => '#ecfdf5', 100 => '#d1fae5', 200 => '#a7f3d0', 300 => '#6ee7b7',
				400 => '#34d399', 500 => '#10b981', 600 => '#059669', 700 => '#047857',
				800 => '#065f46', 900 => '#064e3b', 950 => '#022c22'
			],
			'teal' => [
				50 => '#f0fdfa', 100 => '#ccfbf1', 200 => '#99f6e4', 300 => '#5eead4',
				400 => '#2dd4bf', 500 => '#14b8a6', 600 => '#0d9488', 700 => '#0f766e',
				800 => '#115e59', 900 => '#134e4a', 950 => '#042f2e'
			],
			'cyan' => [
				50 => '#ecfeff', 100 => '#cffafe', 200 => '#a5f3fc', 300 => '#67e8f9',
				400 => '#22d3ee', 500 => '#06b6d4', 600 => '#0891b2', 700 => '#0e7490',
				800 => '#155e75', 900 => '#164e63', 950 => '#083344'
			],
			'sky' => [
				50 => '#f0f9ff', 100 => '#e0f2fe', 200 => '#bae6fd', 300 => '#7dd3fc',
				400 => '#38bdf8', 500 => '#0ea5e9', 600 => '#0284c7', 700 => '#0369a1',
				800 => '#075985', 900 => '#0c4a6e', 950 => '#082f49'
			],
			'blue' => [
				50 => '#eff6ff', 100 => '#dbeafe', 200 => '#bfdbfe', 300 => '#93c5fd',
				400 => '#60a5fa', 500 => '#3b82f6', 600 => '#2563eb', 700 => '#1d4ed8',
				800 => '#1e40af', 900 => '#1e3a8a', 950 => '#172554'
			],
			'indigo' => [
				50 => '#eef2ff', 100 => '#e0e7ff', 200 => '#c7d2fe', 300 => '#a5b4fc',
				400 => '#818cf8', 500 => '#6366f1', 600 => '#4f46e5', 700 => '#4338ca',
				800 => '#3730a3', 900 => '#312e81', 950 => '#1e1b4b'
			],
			'violet' => [
				50 => '#f5f3ff', 100 => '#ede9fe', 200 => '#ddd6fe', 300 => '#c4b5fd',
				400 => '#a78bfa', 500 => '#8b5cf6', 600 => '#7c3aed', 700 => '#6d28d9',
				800 => '#5b21b6', 900 => '#4c1d95', 950 => '#2e1065'
			],
			'purple' => [
				50 => '#faf5ff', 100 => '#f3e8ff', 200 => '#e9d5ff', 300 => '#d8b4fe',
				400 => '#c084fc', 500 => '#a855f7', 600 => '#9333ea', 700 => '#7e22ce',
				800 => '#6b21a8', 900 => '#581c87', 950 => '#3b0764'
			],
			'fuchsia' => [
				50 => '#fdf4ff', 100 => '#fae8ff', 200 => '#f5d0fe', 300 => '#f0abfc',
				400 => '#e879f9', 500 => '#d946ef', 600 => '#c026d3', 700 => '#a21caf',
				800 => '#86198f', 900 => '#701a75', 950 => '#4a044e'
			],
			'pink' => [
				50 => '#fdf2f8', 100 => '#fce7f3', 200 => '#fbcfe8', 300 => '#f9a8d4',
				400 => '#f472b6', 500 => '#ec4899', 600 => '#db2777', 700 => '#be185d',
				800 => '#9d174d', 900 => '#831843', 950 => '#500724'
			],
			'rose' => [
				50 => '#fff1f2', 100 => '#ffe4e6', 200 => '#fecdd3', 300 => '#fda4af',
				400 => '#fb7185', 500 => '#f43f5e', 600 => '#e11d48', 700 => '#be123c',
				800 => '#9f1239', 900 => '#881337', 950 => '#4c0519'
			],
		];

		return $tailwindColors[$colorName][$intensity] ?? null;
	}

	/**
	 * Adjusts hex color brightness.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $hex
	 * @param  float $percent
	 * @return string
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
	 * Resolves DatePicker-specific colors and generates appropriate CSS custom properties.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color The color input (variant, tailwind color, or hex)
	 * @param  string|null $adjustment Background adjustment (lighter, darker, transparent, subtle)
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
			$baseColor = $this->tailwindColorToHex(...explode('-', $color));
		} elseif ($this->isTailwindColorName($color)) {
			$baseColor = $this->tailwindColorToHex($color, 500);
		} elseif ($this->isHexColor($color)) {
			$baseColor = $color;
		}

		if (!$baseColor) {
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
			'--artisanpack-text-color' => $textColor,
		];

		// Return as style property
		return [
			'style' => implode(' ', array_map(fn($key, $value) => "{$key}: {$value};", array_keys($cssVariables), $cssVariables))
		];
	}

	/**
	 * Gets the base hex color for a variant.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $variant
	 * @return string|null
	 */
	protected function getVariantBaseColor(string $variant): ?string
	{
		$variantColors = [
			'primary' => '#3b82f6',    // Blue 500
			'secondary' => '#6b7280',  // Gray 500
			'accent' => '#06b6d4',     // Cyan 500
			'success' => '#10b981',    // Emerald 500
			'warning' => '#f59e0b',    // Amber 500
			'error' => '#ef4444',      // Red 500
			'info' => '#0ea5e9',       // Sky 500
			'neutral' => '#737373',    // Neutral 500
		];

		return $variantColors[$variant] ?? null;
	}

	/**
	 * Applies DatePicker-specific color adjustments.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $color Base hex color
	 * @param  string $adjustment Adjustment type
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