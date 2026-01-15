<?php

declare(strict_types=1);
/**
 * Artisan command to generate the CSS theme file for ArtisanPack UI.
 *
 * This command allows developers to dynamically generate a theme file with
 * CSS variables for primary, secondary, and accent colors, including full
 * Tailwind-style color palettes and DaisyUI compatibility.
 *
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Generates the CSS theme file based on selected colors.
 *
 * @since 1.0.0
 */
class GenerateThemeCss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisanpack:generate-theme
                            {--primary=sky : The primary color name (e.g., sky) or a hex code.}
                            {--secondary=slate : The secondary color name (e.g., slate) or a hex code.}
                            {--accent=amber : The accent color name (e.g., amber) or a hex code.}
                            {--glass-only : Generate only the glass tokens CSS file.}
                            {--tokens-only : Generate only the comprehensive design tokens CSS file.}
                            {--no-glass : Exclude glass tokens from the generated theme.}
                            {--no-tokens : Exclude design tokens from the generated theme.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a CSS theme file with variables for ArtisanPack UI and DaisyUI.';

    /**
     * Execute the console command.
     *
     * @param  \ArtisanPack\LivewireUiComponents\Styling\ColorGenerator  $colorGenerator  The color generator instance.
     *
     * @since  1.0.0
     */
    public function handle( ColorGenerator $colorGenerator ): int
    {
        $primaryColor   = $this->option( 'primary' );
        $secondaryColor = $this->option( 'secondary' );
        $accentColor    = $this->option( 'accent' );
        $glassOnly      = $this->option( 'glass-only' );
        $tokensOnly     = $this->option( 'tokens-only' );
        $noGlass        = $this->option( 'no-glass' );
        $noTokens       = $this->option( 'no-tokens' );

        // Handle glass-only mode
        if ( $glassOnly ) {
            return $this->generateGlassTokensOnly( $colorGenerator );
        }

        // Handle tokens-only mode
        if ( $tokensOnly ) {
            return $this->generateDesignTokensOnly( $colorGenerator );
        }

        $this->info( 'Generating CSS theme file...' );

        try {
            $cssContent = $colorGenerator->generateThemeCss( $primaryColor, $secondaryColor, $accentColor );

            // Use the path from the published config file.
            $outputPath = config( 'artisanpack.livewire-ui-components.theme_output_path' );

            if ( ! $outputPath ) {
                $this->error( '❌ Output path is not defined. Please publish the configuration file.' );

                return Command::FAILURE;
            }

            $directory = dirname( $outputPath );

            // Ensure the directory exists in the user's application.
            if ( ! File::isDirectory( $directory ) ) {
                File::makeDirectory( $directory, 0755, true, true );
            }

            File::put( $outputPath, $cssContent );

            $this->info( '✅ ArtisanPack UI theme CSS file generated successfully!' );
            $this->line( "   -> Location: <comment>{$outputPath}</comment>" );

            // Generate standalone glass tokens file if enabled and not excluded
            if ( ! $noGlass && config( 'artisanpack.livewire-ui-components.glass.enabled', true ) ) {
                $this->generateStandaloneGlassTokens( $colorGenerator );
            }

            // Generate standalone design tokens file if enabled and not excluded
            if ( ! $noTokens && config( 'artisanpack.livewire-ui-components.design_tokens.enabled', true ) ) {
                $this->generateStandaloneDesignTokens( $colorGenerator );
            }

            $this->warn( '🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).' );

        } catch ( Exception $e ) {
            $this->error( '❌ An error occurred while generating the theme file:' );
            $this->error( $e->getMessage() );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Generate only the glass tokens CSS file.
     *
     * @since 2.0.0
     *
     * @param  \ArtisanPack\LivewireUiComponents\Styling\ColorGenerator  $colorGenerator  The color generator instance.
     *
     * @return int The command exit code.
     */
    protected function generateGlassTokensOnly( ColorGenerator $colorGenerator ): int
    {
        $this->info( 'Generating Glass Design Tokens CSS file...' );

        try {
            $overrides   = $this->getGlassTokenOverrides();
            $cssContent  = $colorGenerator->generateGlassTokensCss( $overrides );
            $outputPath  = config( 'artisanpack.livewire-ui-components.glass.output_path' );

            if ( ! $outputPath ) {
                $outputPath = resource_path( 'css/artisanpack-glass-tokens.css' );
            }

            $directory = dirname( $outputPath );

            if ( ! File::isDirectory( $directory ) ) {
                File::makeDirectory( $directory, 0755, true, true );
            }

            File::put( $outputPath, $cssContent );

            $this->info( '✅ Glass Design Tokens CSS file generated successfully!' );
            $this->line( "   -> Location: <comment>{$outputPath}</comment>" );
            $this->warn( '🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).' );
            $this->newLine();
            $this->line( '   <info>Usage:</info> Import the file in your app.css:' );
            $this->line( "   <comment>@import './artisanpack-glass-tokens.css';</comment>" );

        } catch ( Exception $e ) {
            $this->error( '❌ An error occurred while generating the glass tokens file:' );
            $this->error( $e->getMessage() );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Generate standalone glass tokens file alongside the main theme.
     *
     * @since 2.0.0
     *
     * @param  \ArtisanPack\LivewireUiComponents\Styling\ColorGenerator  $colorGenerator  The color generator instance.
     */
    protected function generateStandaloneGlassTokens( ColorGenerator $colorGenerator ): void
    {
        $outputPath = config( 'artisanpack.livewire-ui-components.glass.output_path' );

        if ( ! $outputPath ) {
            return;
        }

        try {
            $overrides  = $this->getGlassTokenOverrides();
            $cssContent = $colorGenerator->generateGlassTokensCss( $overrides );

            $directory = dirname( $outputPath );

            if ( ! File::isDirectory( $directory ) ) {
                File::makeDirectory( $directory, 0755, true, true );
            }

            File::put( $outputPath, $cssContent );

            $this->info( '✅ Glass Design Tokens CSS file also generated!' );
            $this->line( "   -> Location: <comment>{$outputPath}</comment>" );
        } catch ( Exception $e ) {
            $this->warn( '⚠️ Could not generate standalone glass tokens file: '.$e->getMessage() );
        }
    }

    /**
     * Get glass token overrides from configuration.
     *
     * @since 2.0.0
     *
     * @return array The glass token overrides.
     */
    protected function getGlassTokenOverrides(): array
    {
        $configTokens = config( 'artisanpack.livewire-ui-components.glass.tokens', [] );
        $overrides    = [];

        foreach ( $configTokens as $key => $value ) {
            if ( null !== $value ) {
                $overrides['glass-'.$key] = $value;
            }
        }

        return $overrides;
    }

    /**
     * Generate only the comprehensive design tokens CSS file.
     *
     * @since 2.0.0
     *
     * @param  \ArtisanPack\LivewireUiComponents\Styling\ColorGenerator  $colorGenerator  The color generator instance.
     *
     * @return int The command exit code.
     */
    protected function generateDesignTokensOnly( ColorGenerator $colorGenerator ): int
    {
        $this->info( 'Generating Design Tokens CSS file...' );

        try {
            $overrides   = $this->getDesignTokenOverrides();
            $cssContent  = $colorGenerator->generateDesignTokensCss( $overrides );
            $outputPath  = config( 'artisanpack.livewire-ui-components.design_tokens.output_path' );

            if ( ! $outputPath ) {
                $outputPath = resource_path( 'css/artisanpack-design-tokens.css' );
            }

            $directory = dirname( $outputPath );

            if ( ! File::isDirectory( $directory ) ) {
                File::makeDirectory( $directory, 0755, true, true );
            }

            File::put( $outputPath, $cssContent );

            $this->info( '✅ Design Tokens CSS file generated successfully!' );
            $this->line( "   -> Location: <comment>{$outputPath}</comment>" );
            $this->warn( '🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).' );
            $this->newLine();
            $this->line( '   <info>Usage:</info> Import the file in your app.css:' );
            $this->line( "   <comment>@import './artisanpack-design-tokens.css';</comment>" );

        } catch ( Exception $e ) {
            $this->error( '❌ An error occurred while generating the design tokens file:' );
            $this->error( $e->getMessage() );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Generate standalone design tokens file alongside the main theme.
     *
     * @since 2.0.0
     *
     * @param  \ArtisanPack\LivewireUiComponents\Styling\ColorGenerator  $colorGenerator  The color generator instance.
     */
    protected function generateStandaloneDesignTokens( ColorGenerator $colorGenerator ): void
    {
        $outputPath = config( 'artisanpack.livewire-ui-components.design_tokens.output_path' );

        if ( ! $outputPath ) {
            return;
        }

        try {
            $overrides  = $this->getDesignTokenOverrides();
            $cssContent = $colorGenerator->generateDesignTokensCss( $overrides );

            $directory = dirname( $outputPath );

            if ( ! File::isDirectory( $directory ) ) {
                File::makeDirectory( $directory, 0755, true, true );
            }

            File::put( $outputPath, $cssContent );

            $this->info( '✅ Design Tokens CSS file also generated!' );
            $this->line( "   -> Location: <comment>{$outputPath}</comment>" );
        } catch ( Exception $e ) {
            $this->warn( '⚠️ Could not generate standalone design tokens file: '.$e->getMessage() );
        }
    }

    /**
     * Get design token overrides from configuration.
     *
     * @since 2.0.0
     *
     * @return array The design token overrides organized by category.
     */
    protected function getDesignTokenOverrides(): array
    {
        $overrides = [];

        // Get typography token overrides
        $typographyConfig = config( 'artisanpack.livewire-ui-components.design_tokens.typography', [] );
        if ( ! empty( $typographyConfig ) ) {
            $overrides['typography'] = $typographyConfig;
        }

        // Get spacing token overrides
        $spacingConfig = config( 'artisanpack.livewire-ui-components.design_tokens.spacing', [] );
        if ( ! empty( $spacingConfig ) ) {
            $overrides['spacing'] = $spacingConfig;
        }

        // Get radius token overrides
        $radiusConfig = config( 'artisanpack.livewire-ui-components.design_tokens.radius', [] );
        if ( ! empty( $radiusConfig ) ) {
            $overrides['radius'] = $radiusConfig;
        }

        // Get shadow token overrides
        $shadowConfig = config( 'artisanpack.livewire-ui-components.design_tokens.shadow', [] );
        if ( ! empty( $shadowConfig ) ) {
            $overrides['shadow'] = $shadowConfig;
        }

        // Get animation token overrides
        $animationConfig = config( 'artisanpack.livewire-ui-components.design_tokens.animation', [] );
        if ( ! empty( $animationConfig ) ) {
            $overrides['animation'] = $animationConfig;
        }

        // Get glass token overrides (reuse existing method)
        $glassOverrides = $this->getGlassTokenOverrides();
        if ( ! empty( $glassOverrides ) ) {
            $overrides['glass'] = $glassOverrides;
        }

        return $overrides;
    }
}
