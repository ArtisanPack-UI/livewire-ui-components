<?php

declare( strict_types=1 );

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

describe( 'Design Token System', function () {

    beforeEach( function () {
        $this->generator = new ColorGenerator();
    } );

    /*
    |--------------------------------------------------------------------------
    | Typography Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Typography Tokens', function () {

        it( 'returns typography token defaults', function () {
            $tokens = $this->generator->getTypographyTokenDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'font-family-sans',
                    'font-family-serif',
                    'font-family-mono',
                    'font-size-xs',
                    'font-size-sm',
                    'font-size-base',
                    'font-size-lg',
                    'font-size-xl',
                    'font-size-2xl',
                    'font-size-3xl',
                    'font-size-4xl',
                    'font-size-5xl',
                    'font-size-6xl',
                    'font-weight-thin',
                    'font-weight-normal',
                    'font-weight-bold',
                    'line-height-none',
                    'line-height-normal',
                    'line-height-loose',
                    'letter-spacing-tight',
                    'letter-spacing-normal',
                    'letter-spacing-wide',
                ] );
        } );

        it( 'has correct font size values', function () {
            $tokens = $this->generator->getTypographyTokenDefaults();

            expect( $tokens['font-size-xs'] )->toBe( '0.75rem' )
                ->and( $tokens['font-size-sm'] )->toBe( '0.875rem' )
                ->and( $tokens['font-size-base'] )->toBe( '1rem' )
                ->and( $tokens['font-size-lg'] )->toBe( '1.125rem' )
                ->and( $tokens['font-size-xl'] )->toBe( '1.25rem' )
                ->and( $tokens['font-size-2xl'] )->toBe( '1.5rem' );
        } );

        it( 'has correct font weight values', function () {
            $tokens = $this->generator->getTypographyTokenDefaults();

            expect( $tokens['font-weight-thin'] )->toBe( '100' )
                ->and( $tokens['font-weight-normal'] )->toBe( '400' )
                ->and( $tokens['font-weight-medium'] )->toBe( '500' )
                ->and( $tokens['font-weight-semibold'] )->toBe( '600' )
                ->and( $tokens['font-weight-bold'] )->toBe( '700' )
                ->and( $tokens['font-weight-black'] )->toBe( '900' );
        } );

        it( 'has correct line height values', function () {
            $tokens = $this->generator->getTypographyTokenDefaults();

            expect( $tokens['line-height-none'] )->toBe( '1' )
                ->and( $tokens['line-height-tight'] )->toBe( '1.25' )
                ->and( $tokens['line-height-normal'] )->toBe( '1.5' )
                ->and( $tokens['line-height-loose'] )->toBe( '2' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | Spacing Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Spacing Tokens', function () {

        it( 'returns spacing token defaults', function () {
            $tokens = $this->generator->getSpacingTokenDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'spacing-0',
                    'spacing-px',
                    'spacing-0.5',
                    'spacing-1',
                    'spacing-2',
                    'spacing-4',
                    'spacing-8',
                    'spacing-16',
                    'spacing-32',
                    'spacing-64',
                    'spacing-96',
                ] );
        } );

        it( 'has correct spacing values based on 0.25rem increments', function () {
            $tokens = $this->generator->getSpacingTokenDefaults();

            expect( $tokens['spacing-0'] )->toBe( '0' )
                ->and( $tokens['spacing-px'] )->toBe( '1px' )
                ->and( $tokens['spacing-1'] )->toBe( '0.25rem' )
                ->and( $tokens['spacing-2'] )->toBe( '0.5rem' )
                ->and( $tokens['spacing-4'] )->toBe( '1rem' )
                ->and( $tokens['spacing-8'] )->toBe( '2rem' )
                ->and( $tokens['spacing-16'] )->toBe( '4rem' );
        } );

        it( 'includes large spacing values', function () {
            $tokens = $this->generator->getSpacingTokenDefaults();

            expect( $tokens['spacing-48'] )->toBe( '12rem' )
                ->and( $tokens['spacing-64'] )->toBe( '16rem' )
                ->and( $tokens['spacing-80'] )->toBe( '20rem' )
                ->and( $tokens['spacing-96'] )->toBe( '24rem' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | Border Radius Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Border Radius Tokens', function () {

        it( 'returns radius token defaults', function () {
            $tokens = $this->generator->getRadiusTokenDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'radius-none',
                    'radius-sm',
                    'radius-base',
                    'radius-md',
                    'radius-lg',
                    'radius-xl',
                    'radius-2xl',
                    'radius-3xl',
                    'radius-full',
                ] );
        } );

        it( 'has correct radius values', function () {
            $tokens = $this->generator->getRadiusTokenDefaults();

            expect( $tokens['radius-none'] )->toBe( '0' )
                ->and( $tokens['radius-sm'] )->toBe( '0.125rem' )
                ->and( $tokens['radius-base'] )->toBe( '0.25rem' )
                ->and( $tokens['radius-md'] )->toBe( '0.375rem' )
                ->and( $tokens['radius-lg'] )->toBe( '0.5rem' )
                ->and( $tokens['radius-xl'] )->toBe( '0.75rem' )
                ->and( $tokens['radius-full'] )->toBe( '9999px' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | Shadow Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Shadow Tokens', function () {

        it( 'returns shadow token defaults', function () {
            $tokens = $this->generator->getShadowTokenDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'shadow-none',
                    'shadow-sm',
                    'shadow-base',
                    'shadow-md',
                    'shadow-lg',
                    'shadow-xl',
                    'shadow-2xl',
                    'shadow-inner',
                ] );
        } );

        it( 'has colored shadow variants', function () {
            $tokens = $this->generator->getShadowTokenDefaults();

            expect( $tokens )->toHaveKeys( [
                'shadow-primary',
                'shadow-success',
                'shadow-warning',
                'shadow-error',
                'shadow-info',
            ] );
        } );

        it( 'has glow effect variants', function () {
            $tokens = $this->generator->getShadowTokenDefaults();

            expect( $tokens )->toHaveKeys( [
                'shadow-glow-sm',
                'shadow-glow-md',
                'shadow-glow-lg',
            ] );
        } );

        it( 'returns dark mode shadow overrides', function () {
            $tokens = $this->generator->getShadowTokenDarkDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'shadow-sm',
                    'shadow-base',
                    'shadow-md',
                    'shadow-lg',
                    'shadow-xl',
                    'shadow-2xl',
                    'shadow-inner',
                ] );
        } );

        it( 'has darker shadows for dark mode', function () {
            $lightTokens = $this->generator->getShadowTokenDefaults();
            $darkTokens  = $this->generator->getShadowTokenDarkDefaults();

            // Dark mode shadows should have higher opacity values
            expect( $darkTokens['shadow-sm'] )->toContain( '0.2' )
                ->and( $lightTokens['shadow-sm'] )->toContain( '0.05' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | Animation Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Animation Tokens', function () {

        it( 'returns animation token defaults', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens )->toBeArray()
                ->and( $tokens )->toHaveKeys( [
                    'duration-0',
                    'duration-75',
                    'duration-100',
                    'duration-150',
                    'duration-200',
                    'duration-300',
                    'duration-500',
                    'duration-700',
                    'duration-1000',
                ] );
        } );

        it( 'has standard easing functions', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens )->toHaveKeys( [
                'ease-linear',
                'ease-in',
                'ease-out',
                'ease-in-out',
            ] );
        } );

        it( 'has expressive easing functions', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens )->toHaveKeys( [
                'ease-spring',
                'ease-bounce',
                'ease-elastic',
            ] );
        } );

        it( 'has correct duration values', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens['duration-0'] )->toBe( '0ms' )
                ->and( $tokens['duration-100'] )->toBe( '100ms' )
                ->and( $tokens['duration-200'] )->toBe( '200ms' )
                ->and( $tokens['duration-500'] )->toBe( '500ms' )
                ->and( $tokens['duration-1000'] )->toBe( '1000ms' );
        } );

        it( 'has correct easing cubic bezier values', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens['ease-linear'] )->toBe( 'linear' )
                ->and( $tokens['ease-in'] )->toBe( 'cubic-bezier(0.4, 0, 1, 1)' )
                ->and( $tokens['ease-out'] )->toBe( 'cubic-bezier(0, 0, 0.2, 1)' )
                ->and( $tokens['ease-in-out'] )->toBe( 'cubic-bezier(0.4, 0, 0.2, 1)' );
        } );

        it( 'has transition presets', function () {
            $tokens = $this->generator->getAnimationTokenDefaults();

            expect( $tokens )->toHaveKeys( [
                'transition-none',
                'transition-all',
                'transition-default',
                'transition-colors',
                'transition-opacity',
                'transition-shadow',
                'transition-transform',
            ] );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | Combined Token Tests
    |--------------------------------------------------------------------------
    */

    describe( 'All Design Tokens Combined', function () {

        it( 'returns all design tokens organized by category', function () {
            $allTokens = $this->generator->getAllDesignTokenDefaults();

            expect( $allTokens )->toBeArray()
                ->and( $allTokens )->toHaveKeys( [
                    'typography',
                    'spacing',
                    'radius',
                    'shadow',
                    'animation',
                    'glass',
                ] );
        } );

        it( 'each category contains the correct tokens', function () {
            $allTokens = $this->generator->getAllDesignTokenDefaults();

            expect( $allTokens['typography'] )->toHaveKey( 'font-size-base' )
                ->and( $allTokens['spacing'] )->toHaveKey( 'spacing-4' )
                ->and( $allTokens['radius'] )->toHaveKey( 'radius-lg' )
                ->and( $allTokens['shadow'] )->toHaveKey( 'shadow-md' )
                ->and( $allTokens['animation'] )->toHaveKey( 'duration-200' )
                ->and( $allTokens['glass'] )->toHaveKey( 'glass-blur' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | CSS Generation Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Design Tokens CSS Generation', function () {

        it( 'generates CSS with all token categories', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( 'Typography Tokens' )
                ->and( $css )->toContain( 'Spacing Tokens' )
                ->and( $css )->toContain( 'Border Radius Tokens' )
                ->and( $css )->toContain( 'Shadow Tokens' )
                ->and( $css )->toContain( 'Animation Tokens' )
                ->and( $css )->toContain( 'Glass Tokens' );
        } );

        it( 'generates CSS with @theme directive', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '@theme {' )
                ->and( $css )->toContain( 'Tailwind CSS v4 @theme integration' );
        } );

        it( 'generates CSS with :root selector', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( ':root {' );
        } );

        it( 'generates CSS with dark mode overrides', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '[data-theme="dark"]' )
                ->and( $css )->toContain( 'Shadow Token Dark Mode Overrides' )
                ->and( $css )->toContain( 'Glass Token Dark Mode Overrides' );
        } );

        it( 'includes typography tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--font-family-sans:' )
                ->and( $css )->toContain( '--font-size-base:' )
                ->and( $css )->toContain( '--font-weight-bold:' )
                ->and( $css )->toContain( '--line-height-normal:' )
                ->and( $css )->toContain( '--letter-spacing-normal:' );
        } );

        it( 'includes spacing tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--spacing-0:' )
                ->and( $css )->toContain( '--spacing-4:' )
                ->and( $css )->toContain( '--spacing-16:' )
                ->and( $css )->toContain( '--spacing-96:' );
        } );

        it( 'includes radius tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--radius-none:' )
                ->and( $css )->toContain( '--radius-lg:' )
                ->and( $css )->toContain( '--radius-full:' );
        } );

        it( 'includes shadow tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--shadow-sm:' )
                ->and( $css )->toContain( '--shadow-md:' )
                ->and( $css )->toContain( '--shadow-primary:' )
                ->and( $css )->toContain( '--shadow-glow-md:' );
        } );

        it( 'includes animation tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--duration-200:' )
                ->and( $css )->toContain( '--ease-in-out:' )
                ->and( $css )->toContain( '--ease-spring:' )
                ->and( $css )->toContain( '--transition-all:' );
        } );

        it( 'includes glass tokens in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--glass-blur:' )
                ->and( $css )->toContain( '--glass-opacity:' )
                ->and( $css )->toContain( '--glass-frosted-blur:' )
                ->and( $css )->toContain( '--glass-liquid-blur:' );
        } );

        it( 'includes computed glass colors in generated CSS', function () {
            $css = $this->generator->generateDesignTokensCss();

            expect( $css )->toContain( '--glass-bg-color:' )
                ->and( $css )->toContain( '--glass-border-color:' )
                ->and( $css )->toContain( '--glass-frosted-bg-color:' );
        } );

    } );

    /*
    |--------------------------------------------------------------------------
    | CSS Generation with Overrides Tests
    |--------------------------------------------------------------------------
    */

    describe( 'Design Tokens CSS Generation with Overrides', function () {

        it( 'applies typography overrides', function () {
            $overrides = [
                'typography' => [
                    'font-size-base' => '1.125rem',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--font-size-base: 1.125rem' );
        } );

        it( 'applies spacing overrides', function () {
            $overrides = [
                'spacing' => [
                    'spacing-4' => '1.25rem',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--spacing-4: 1.25rem' );
        } );

        it( 'applies radius overrides', function () {
            $overrides = [
                'radius' => [
                    'radius-lg' => '1rem',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--radius-lg: 1rem' );
        } );

        it( 'applies shadow overrides', function () {
            $overrides = [
                'shadow' => [
                    'shadow-md' => '0 6px 10px -2px rgb(0 0 0 / 0.15)',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--shadow-md: 0 6px 10px -2px rgb(0 0 0 / 0.15)' );
        } );

        it( 'applies animation overrides', function () {
            $overrides = [
                'animation' => [
                    'duration-200' => '250ms',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--duration-200: 250ms' );
        } );

        it( 'applies glass overrides', function () {
            $overrides = [
                'glass' => [
                    'glass-blur' => '16px',
                ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--glass-blur: 16px' );
        } );

        it( 'applies multiple category overrides simultaneously', function () {
            $overrides = [
                'typography' => [ 'font-size-base' => '1.125rem' ],
                'spacing'    => [ 'spacing-4' => '1.25rem' ],
                'radius'     => [ 'radius-lg' => '1rem' ],
            ];

            $css = $this->generator->generateDesignTokensCss( $overrides );

            expect( $css )->toContain( '--font-size-base: 1.125rem' )
                ->and( $css )->toContain( '--spacing-4: 1.25rem' )
                ->and( $css )->toContain( '--radius-lg: 1rem' );
        } );

    } );

} );
