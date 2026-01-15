<?php

declare( strict_types=1 );

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

beforeEach( function (): void {
	$this->colorGenerator = new ColorGenerator;
} );

describe( 'Glass Token Defaults', function (): void {
	test( 'returns all base glass token defaults', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		// Base glass tokens
		expect( $defaults )->toHaveKey( 'glass-blur' );
		expect( $defaults )->toHaveKey( 'glass-opacity' );
		expect( $defaults )->toHaveKey( 'glass-border-width' );
		expect( $defaults )->toHaveKey( 'glass-border-opacity' );
		expect( $defaults )->toHaveKey( 'glass-shadow-opacity' );

		// Tint tokens
		expect( $defaults )->toHaveKey( 'glass-tint-color' );
		expect( $defaults )->toHaveKey( 'glass-tint-opacity' );

		// Frosted variant tokens
		expect( $defaults )->toHaveKey( 'glass-frosted-blur' );
		expect( $defaults )->toHaveKey( 'glass-frosted-opacity' );
		expect( $defaults )->toHaveKey( 'glass-frosted-saturation' );

		// Liquid variant tokens
		expect( $defaults )->toHaveKey( 'glass-liquid-blur' );
		expect( $defaults )->toHaveKey( 'glass-liquid-opacity' );
		expect( $defaults )->toHaveKey( 'glass-liquid-refraction' );
		expect( $defaults )->toHaveKey( 'glass-liquid-border-glow' );

		// Transparent variant tokens
		expect( $defaults )->toHaveKey( 'glass-transparent-blur' );
		expect( $defaults )->toHaveKey( 'glass-transparent-opacity' );
	} );

	test( 'has correct default values for base tokens', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		expect( $defaults['glass-blur'] )->toBe( '12px' );
		expect( $defaults['glass-opacity'] )->toBe( '0.7' );
		expect( $defaults['glass-border-width'] )->toBe( '1px' );
		expect( $defaults['glass-border-opacity'] )->toBe( '0.2' );
		expect( $defaults['glass-shadow-opacity'] )->toBe( '0.1' );
	} );

	test( 'has correct default values for tint tokens', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		expect( $defaults['glass-tint-color'] )->toBe( 'transparent' );
		expect( $defaults['glass-tint-opacity'] )->toBe( '0.15' );
	} );

	test( 'has correct default values for frosted variant', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		expect( $defaults['glass-frosted-blur'] )->toBe( '16px' );
		expect( $defaults['glass-frosted-opacity'] )->toBe( '0.8' );
		expect( $defaults['glass-frosted-saturation'] )->toBe( '180%' );
	} );

	test( 'has correct default values for liquid variant', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		expect( $defaults['glass-liquid-blur'] )->toBe( '24px' );
		expect( $defaults['glass-liquid-opacity'] )->toBe( '0.6' );
		expect( $defaults['glass-liquid-refraction'] )->toBe( '0.5' );
		expect( $defaults['glass-liquid-border-glow'] )->toBe( '0.3' );
	} );

	test( 'has correct default values for transparent variant', function (): void {
		$defaults = $this->colorGenerator->getGlassTokenDefaults();

		expect( $defaults['glass-transparent-blur'] )->toBe( '8px' );
		expect( $defaults['glass-transparent-opacity'] )->toBe( '0.3' );
	} );
} );

describe( 'Glass Token Dark Mode Defaults', function (): void {
	test( 'returns dark mode overrides', function (): void {
		$darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

		expect( $darkDefaults )->toHaveKey( 'glass-frosted-opacity' );
		expect( $darkDefaults )->toHaveKey( 'glass-border-opacity' );
		expect( $darkDefaults )->toHaveKey( 'glass-shadow-opacity' );
	} );

	test( 'has correct dark mode override values', function (): void {
		$darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

		expect( $darkDefaults['glass-frosted-opacity'] )->toBe( '0.7' );
		expect( $darkDefaults['glass-border-opacity'] )->toBe( '0.15' );
		expect( $darkDefaults['glass-shadow-opacity'] )->toBe( '0.2' );
	} );
} );

describe( 'Generate Glass Tokens CSS', function (): void {
	test( 'generates valid CSS output', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toBeString();
		expect( $css )->not->toBeEmpty();
	} );

	test( 'includes header comment', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( 'ArtisanPack UI - Glass Design Tokens' );
		expect( $css )->toContain( '@since 2.0.0' );
	} );

	test( 'includes @theme directive for Tailwind CSS v4', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( '@theme {' );
		expect( $css )->toContain( '--glass-blur:' );
	} );

	test( 'includes :root section with all tokens', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( ':root {' );
		expect( $css )->toContain( '--glass-blur: 12px;' );
		expect( $css )->toContain( '--glass-opacity: 0.7;' );
		expect( $css )->toContain( '--glass-border-width: 1px;' );
	} );

	test( 'includes computed glass colors for light mode', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( '--glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));' );
		expect( $css )->toContain( '--glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));' );
		expect( $css )->toContain( '--glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));' );
	} );

	test( 'includes dark mode overrides', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( '[data-theme="dark"] {' );
		expect( $css )->toContain( '--glass-frosted-opacity: 0.7;' );
		expect( $css )->toContain( '--glass-border-opacity: 0.15;' );
		expect( $css )->toContain( '--glass-shadow-opacity: 0.2;' );
	} );

	test( 'includes computed glass colors for dark mode', function (): void {
		$css = $this->colorGenerator->generateGlassTokensCss();

		expect( $css )->toContain( '--glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));' );
	} );

	test( 'accepts custom overrides', function (): void {
		$overrides = [
			'glass-blur'    => '20px',
			'glass-opacity' => '0.9',
		];

		$css = $this->colorGenerator->generateGlassTokensCss( $overrides );

		expect( $css )->toContain( '--glass-blur: 20px;' );
		expect( $css )->toContain( '--glass-opacity: 0.9;' );
	} );

	test( 'merges overrides with defaults', function (): void {
		$overrides = [
			'glass-blur' => '20px',
		];

		$css = $this->colorGenerator->generateGlassTokensCss( $overrides );

		// Custom value
		expect( $css )->toContain( '--glass-blur: 20px;' );
		// Default values still present
		expect( $css )->toContain( '--glass-opacity: 0.7;' );
		expect( $css )->toContain( '--glass-frosted-blur: 16px;' );
	} );
} );

describe( 'Generate Theme CSS with Glass Tokens', function (): void {
	test( 'includes glass tokens in generated theme', function (): void {
		$css = $this->colorGenerator->generateThemeCss( 'blue', 'slate', 'amber' );

		expect( $css )->toContain( '/* --- Glass Design Tokens --- */' );
		expect( $css )->toContain( '--glass-blur: 12px;' );
		expect( $css )->toContain( '--glass-opacity: 0.7;' );
	} );

	test( 'includes computed glass colors in light mode section', function (): void {
		$css = $this->colorGenerator->generateThemeCss( 'blue', 'slate', 'amber' );

		expect( $css )->toContain( '/* --- Computed Glass Colors (Light Mode) --- */' );
		expect( $css )->toContain( '--glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));' );
	} );

	test( 'includes glass token dark mode overrides', function (): void {
		$css = $this->colorGenerator->generateThemeCss( 'blue', 'slate', 'amber' );

		expect( $css )->toContain( '/* --- Glass Design Token Dark Mode Overrides --- */' );
		expect( $css )->toContain( '--glass-frosted-opacity: 0.7;' );
	} );

	test( 'includes computed glass colors in dark mode section', function (): void {
		$css = $this->colorGenerator->generateThemeCss( 'blue', 'slate', 'amber' );

		expect( $css )->toContain( '/* --- Computed Glass Colors (Dark Mode) --- */' );
		expect( $css )->toContain( '--glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));' );
	} );

	test( 'includes all glass variant tokens', function (): void {
		$css = $this->colorGenerator->generateThemeCss( 'blue', 'slate', 'amber' );

		// Frosted variant
		expect( $css )->toContain( '--glass-frosted-blur: 16px;' );
		expect( $css )->toContain( '--glass-frosted-opacity: 0.8;' );
		expect( $css )->toContain( '--glass-frosted-saturation: 180%;' );

		// Liquid variant
		expect( $css )->toContain( '--glass-liquid-blur: 24px;' );
		expect( $css )->toContain( '--glass-liquid-opacity: 0.6;' );
		expect( $css )->toContain( '--glass-liquid-refraction: 0.5;' );
		expect( $css )->toContain( '--glass-liquid-border-glow: 0.3;' );

		// Transparent variant
		expect( $css )->toContain( '--glass-transparent-blur: 8px;' );
		expect( $css )->toContain( '--glass-transparent-opacity: 0.3;' );
	} );
} );
