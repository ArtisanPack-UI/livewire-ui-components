<?php

declare(strict_types=1);
/**
 * Generate Theme CSS Command
 *
 * Artisan command to generate the CSS theme file for ArtisanPack UI.
 * This command allows developers to dynamically generate a theme file with
 * CSS variables for primary, secondary, and accent colors, including full
 * Tailwind-style color palettes and DaisyUI compatibility.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use ArtisanPack\LivewireUiComponents\Styling\GlassPresets;
use ArtisanPack\LivewireUiComponents\Styling\HighContrastTheme;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

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
                            {--presets-only : Generate only the glass presets CSS file.}
                            {--high-contrast-only : Generate only the high-contrast accessibility CSS file.}
                            {--no-glass : Exclude glass tokens from the generated theme.}
                            {--no-tokens : Exclude design tokens from the generated theme.}
                            {--no-presets : Exclude glass presets from generation.}
                            {--no-high-contrast : Exclude high-contrast CSS from the generated theme.}
                            {--preset= : Apply a specific glass preset globally (e.g., glass-frosted-light).}
                            {--accessibility-preset= : Apply a specific accessibility preset (e.g., high-contrast-light).}
                            {--tint-color= : Default tint color for glass effects (hex code or Tailwind color).}
                            {--tint-intensity= : Tint intensity from 0.0 to 1.0 (default: 0.15).}
                            {--interactive : Run in interactive mode with prompts for all options.}
                            {--json : Export theme configuration to JSON file.}
                            {--preview : Generate a preview URL for the theme.}';

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
    public function handle(ColorGenerator $colorGenerator): int
    {
        // Check for interactive mode first
        if ($this->option('interactive')) {
            return $this->runInteractiveMode($colorGenerator);
        }

        $primaryColor          = $this->option('primary');
        $secondaryColor        = $this->option('secondary');
        $accentColor           = $this->option('accent');
        $glassOnly             = $this->option('glass-only');
        $tokensOnly            = $this->option('tokens-only');
        $presetsOnly           = $this->option('presets-only');
        $highContrastOnly      = $this->option('high-contrast-only');
        $noGlass               = $this->option('no-glass');
        $noTokens              = $this->option('no-tokens');
        $noPresets             = $this->option('no-presets');
        $noHighContrast        = $this->option('no-high-contrast');
        $presetName            = $this->option('preset');
        $accessibilityPreset   = $this->option('accessibility-preset');
        $tintColor             = $this->option('tint-color');
        $tintIntensity         = $this->option('tint-intensity');
        $exportJson            = $this->option('json');
        $generatePreview       = $this->option('preview');

        // Handle glass-only mode
        if ($glassOnly) {
            return $this->generateGlassTokensOnly($colorGenerator);
        }

        // Handle tokens-only mode
        if ($tokensOnly) {
            return $this->generateDesignTokensOnly($colorGenerator);
        }

        // Handle presets-only mode
        if ($presetsOnly) {
            return $this->generateGlassPresetsOnly();
        }

        // Handle high-contrast-only mode
        if ($highContrastOnly) {
            return $this->generateHighContrastOnly($accessibilityPreset);
        }

        // Build theme configuration
        $themeConfig = $this->buildThemeConfiguration(
            $primaryColor,
            $secondaryColor,
            $accentColor,
            $presetName,
            $tintColor,
            $tintIntensity,
            $accessibilityPreset,
        );

        // Export to JSON if requested
        if ($exportJson) {
            $this->exportThemeToJson($themeConfig);
        }

        $this->info('Generating CSS theme file...');

        try {
            $cssContent = $colorGenerator->generateThemeCss($primaryColor, $secondaryColor, $accentColor);

            // Apply tint color overrides if specified
            if ($tintColor || $tintIntensity) {
                $cssContent = $this->applyTintOverrides($cssContent, $tintColor, $tintIntensity);
            }

            // Use the path from the published config file.
            $outputPath = config('artisanpack.livewire-ui-components.theme_output_path');

            if (! $outputPath) {
                $this->error('❌ Output path is not defined. Please publish the configuration file.');

                return Command::FAILURE;
            }

            $directory = dirname($outputPath);

            // Ensure the directory exists in the user's application.
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ ArtisanPack UI theme CSS file generated successfully!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");

            // Generate standalone glass tokens file if enabled and not excluded
            if (! $noGlass && config('artisanpack.livewire-ui-components.glass.enabled', true)) {
                $this->generateStandaloneGlassTokens($colorGenerator, $tintColor, $tintIntensity);
            }

            // Generate standalone design tokens file if enabled and not excluded
            if (! $noTokens && config('artisanpack.livewire-ui-components.design_tokens.enabled', true)) {
                $this->generateStandaloneDesignTokens($colorGenerator);
            }

            // Generate standalone glass presets file if enabled and not excluded
            if (! $noPresets && config('artisanpack.livewire-ui-components.glass.presets.enabled', true)) {
                $this->generateStandaloneGlassPresets($presetName);
            }

            // Generate high-contrast CSS file if enabled and not excluded
            if (! $noHighContrast && config('artisanpack.livewire-ui-components.high_contrast.enabled', true)) {
                $this->generateStandaloneHighContrast($accessibilityPreset);
            }

            // Generate preview URL if requested
            if ($generatePreview) {
                $this->generatePreviewUrl($themeConfig);
            }

            $this->warn('🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).');

        } catch (Exception $e) {
            $this->error('❌ An error occurred while generating the theme file:');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Run the command in interactive mode with prompts.
     *
     * @since 2.0.0
     *
     * @param  ColorGenerator  $colorGenerator  The color generator instance.
     *
     * @return int The command exit code.
     */
    protected function runInteractiveMode(ColorGenerator $colorGenerator): int
    {
        $this->info('🎨 ArtisanPack UI Theme Builder');
        $this->newLine();

        // Available colors for selection
        $colorOptions = [
            'sky'     => 'Sky (Default Primary)',
            'blue'    => 'Blue',
            'indigo'  => 'Indigo',
            'purple'  => 'Purple',
            'pink'    => 'Pink',
            'red'     => 'Red',
            'orange'  => 'Orange',
            'amber'   => 'Amber',
            'yellow'  => 'Yellow',
            'lime'    => 'Lime',
            'green'   => 'Green',
            'emerald' => 'Emerald',
            'teal'    => 'Teal',
            'cyan'    => 'Cyan',
            'slate'   => 'Slate (Default Secondary)',
            'gray'    => 'Gray',
            'zinc'    => 'Zinc',
            'neutral' => 'Neutral',
            'stone'   => 'Stone',
            'custom'  => 'Custom hex code...',
        ];

        // Primary color selection
        $primaryColor = select(
            label: __('Select primary color'),
            options: $colorOptions,
            default: 'sky',
        );

        if ('custom' === $primaryColor) {
            $primaryColor = text(
                label: __('Enter primary color hex code'),
                placeholder: '#3b82f6',
                validate: fn ($value) => $this->validateHexColor($value),
            );
        }

        // Secondary color selection
        $secondaryColor = select(
            label: __('Select secondary color'),
            options: $colorOptions,
            default: 'slate',
        );

        if ('custom' === $secondaryColor) {
            $secondaryColor = text(
                label: __('Enter secondary color hex code'),
                placeholder: '#64748b',
                validate: fn ($value) => $this->validateHexColor($value),
            );
        }

        // Accent color selection
        $accentColor = select(
            label: __('Select accent color'),
            options: $colorOptions,
            default: 'amber',
        );

        if ('custom' === $accentColor) {
            $accentColor = text(
                label: __('Enter accent color hex code'),
                placeholder: '#f59e0b',
                validate: fn ($value) => $this->validateHexColor($value),
            );
        }

        // Glass preset selection
        $glassPresets  = new GlassPresets;
        $presetOptions = ['none' => __('No preset (use defaults)')];
        foreach ($glassPresets->getAvailablePresets() as $preset) {
            $presetOptions[$preset] = $preset.' - '.$glassPresets->getPresetDescription($preset);
        }

        $presetName = select(
            label: __('Select glass preset'),
            options: $presetOptions,
            default: 'none',
        );

        if ('none' === $presetName) {
            $presetName = null;
        }

        // Tint color configuration
        $configureTint = confirm(
            label: __('Would you like to configure a custom tint color?'),
            default: false,
        );

        $tintColor     = null;
        $tintIntensity = null;

        if ($configureTint) {
            $tintColorOptions = array_merge(
                ['transparent' => __('Transparent (no tint)')],
                array_diff_key($colorOptions, ['custom' => '']),
                ['custom' => 'Custom hex code...'],
            );

            $tintColor = select(
                label: __('Select tint color'),
                options: $tintColorOptions,
                default: 'transparent',
            );

            if ('custom' === $tintColor) {
                $tintColor = text(
                    label: __('Enter tint color hex code'),
                    placeholder: '#3b82f6',
                    validate: fn ($value) => $this->validateHexColor($value),
                );
            }

            if ('transparent' !== $tintColor) {
                $tintIntensity = text(
                    label: __('Enter tint intensity (0.0 to 1.0)'),
                    placeholder: '0.15',
                    default: '0.15',
                    validate: fn ($value) => $this->validateTintIntensity($value),
                );
            }
        }

        // Accessibility preset selection
        $highContrastTheme     = new HighContrastTheme;
        $accessibilityOptions  = ['none' => __('No accessibility preset')];
        foreach ($highContrastTheme->getAvailablePresets() as $preset) {
            $accessibilityOptions[$preset] = $preset.' - '.$highContrastTheme->getPresetDescription($preset);
        }

        $accessibilityPreset = select(
            label: __('Select accessibility preset (high-contrast themes)'),
            options: $accessibilityOptions,
            default: 'none',
        );

        if ('none' === $accessibilityPreset) {
            $accessibilityPreset = null;
        }

        // Export options
        $exportJson = confirm(
            label: __('Export theme configuration to JSON?'),
            default: false,
        );

        $generatePreview = confirm(
            label: __('Generate preview URL?'),
            default: false,
        );

        // Build and display configuration summary
        $this->newLine();
        $this->info('📋 Theme Configuration Summary:');
        $this->table(
            [__('Option'), __('Value')],
            [
                [__('Primary Color'), $primaryColor],
                [__('Secondary Color'), $secondaryColor],
                [__('Accent Color'), $accentColor],
                [__('Glass Preset'), $presetName ?? __('None')],
                [__('Tint Color'), $tintColor ?? __('Default')],
                [__('Tint Intensity'), $tintIntensity ?? __('Default (0.15)')],
                [__('Accessibility Preset'), $accessibilityPreset ?? __('None')],
            ],
        );

        $proceed = confirm(
            label: __('Proceed with theme generation?'),
            default: true,
        );

        if (! $proceed) {
            $this->warn(__('Theme generation cancelled.'));

            return Command::SUCCESS;
        }

        // Build theme configuration
        $themeConfig = $this->buildThemeConfiguration(
            $primaryColor,
            $secondaryColor,
            $accentColor,
            $presetName,
            $tintColor,
            $tintIntensity,
            $accessibilityPreset,
        );

        // Export to JSON if requested
        if ($exportJson) {
            $this->exportThemeToJson($themeConfig);
        }

        $this->newLine();
        $this->info(__('Generating CSS theme file...'));

        try {
            $cssContent = $colorGenerator->generateThemeCss($primaryColor, $secondaryColor, $accentColor);

            // Apply tint color overrides if specified
            if ($tintColor && 'transparent' !== $tintColor) {
                $cssContent = $this->applyTintOverrides($cssContent, $tintColor, $tintIntensity);
            }

            $outputPath = config('artisanpack.livewire-ui-components.theme_output_path');

            if (! $outputPath) {
                $this->error('❌ '.__('Output path is not defined. Please publish the configuration file.'));

                return Command::FAILURE;
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ '.__('ArtisanPack UI theme CSS file generated successfully!'));
            $this->line("   -> Location: <comment>{$outputPath}</comment>");

            // Generate glass tokens
            if (config('artisanpack.livewire-ui-components.glass.enabled', true)) {
                $this->generateStandaloneGlassTokens($colorGenerator, $tintColor, $tintIntensity);
            }

            // Generate design tokens
            if (config('artisanpack.livewire-ui-components.design_tokens.enabled', true)) {
                $this->generateStandaloneDesignTokens($colorGenerator);
            }

            // Generate glass presets
            if (config('artisanpack.livewire-ui-components.glass.presets.enabled', true)) {
                $this->generateStandaloneGlassPresets($presetName);
            }

            // Generate high-contrast CSS
            if (config('artisanpack.livewire-ui-components.high_contrast.enabled', true)) {
                $this->generateStandaloneHighContrast($accessibilityPreset);
            }

            // Generate preview URL if requested
            if ($generatePreview) {
                $this->generatePreviewUrl($themeConfig);
            }

            $this->warn('🚀 '.__('Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).'));

        } catch (Exception $e) {
            $this->error('❌ '.__('An error occurred while generating the theme file:'));
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Validate a hex color code.
     *
     * @since 2.0.0
     *
     * @param  string  $value  The value to validate.
     *
     * @return string|null Error message or null if valid.
     */
    protected function validateHexColor(string $value): ?string
    {
        if (! preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
            return __('Please enter a valid hex color code (e.g., #3b82f6).');
        }

        return null;
    }

    /**
     * Validate tint intensity value.
     *
     * @since 2.0.0
     *
     * @param  string  $value  The value to validate.
     *
     * @return string|null Error message or null if valid.
     */
    protected function validateTintIntensity(string $value): ?string
    {
        if (! is_numeric($value)) {
            return __('Please enter a numeric value.');
        }

        $floatValue = (float) $value;
        if ($floatValue < 0.0 || $floatValue > 1.0) {
            return __('Please enter a value between 0.0 and 1.0.');
        }

        return null;
    }

    /**
     * Build theme configuration array.
     *
     * @since 2.0.0
     *
     * @param  string  $primaryColor  The primary color.
     * @param  string  $secondaryColor  The secondary color.
     * @param  string  $accentColor  The accent color.
     * @param  string|null  $presetName  The glass preset name.
     * @param  string|null  $tintColor  The tint color.
     * @param  string|null  $tintIntensity  The tint intensity.
     * @param  string|null  $accessibilityPreset  The accessibility/high-contrast preset name.
     *
     * @return array The theme configuration.
     */
    protected function buildThemeConfiguration(
        string $primaryColor,
        string $secondaryColor,
        string $accentColor,
        ?string $presetName = null,
        ?string $tintColor = null,
        ?string $tintIntensity = null,
        ?string $accessibilityPreset = null,
    ): array {
        $config = [
            'version'    => '2.0.0',
            'generated'  => now()->toIso8601String(),
            'colors'     => [
                'primary'   => $primaryColor,
                'secondary' => $secondaryColor,
                'accent'    => $accentColor,
            ],
            'glass' => [
                'preset' => $presetName,
                'tint'   => [
                    'color'     => $tintColor ?? 'transparent',
                    'intensity' => $tintIntensity ? (float) $tintIntensity : 0.15,
                ],
            ],
            'accessibility' => [
                'preset' => $accessibilityPreset,
            ],
        ];

        // Add preset tokens if a preset is selected
        if ($presetName) {
            $glassPresets              = new GlassPresets;
            $config['glass']['tokens'] = $glassPresets->getPresetTokens($presetName);
        }

        // Add accessibility preset tokens if selected
        if ($accessibilityPreset) {
            $highContrastTheme                     = new HighContrastTheme;
            $config['accessibility']['tokens']     = $highContrastTheme->getPresetTokens($accessibilityPreset);
            $config['accessibility']['compliance'] = $highContrastTheme->getComplianceLevel($accessibilityPreset);
            $config['accessibility']['mode']       = $highContrastTheme->getPresetMode($accessibilityPreset);
        }

        return $config;
    }

    /**
     * Export theme configuration to JSON file.
     *
     * @since 2.0.0
     *
     * @param  array  $themeConfig  The theme configuration.
     */
    protected function exportThemeToJson(array $themeConfig): void
    {
        $outputPath = config('artisanpack.livewire-ui-components.theme_output_path');
        $jsonPath   = str_replace('.css', '.json', $outputPath ?? resource_path('css/artisanpack-ui-theme.json'));

        $directory = dirname($jsonPath);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        File::put($jsonPath, json_encode($themeConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('✅ '.__('Theme configuration exported to JSON!'));
        $this->line("   -> Location: <comment>{$jsonPath}</comment>");
    }

    /**
     * Generate a preview URL for the theme.
     *
     * @since 2.0.0
     *
     * @param  array  $themeConfig  The theme configuration.
     */
    protected function generatePreviewUrl(array $themeConfig): void
    {
        // Build query parameters for preview
        $params = [
            'primary'   => $themeConfig['colors']['primary'],
            'secondary' => $themeConfig['colors']['secondary'],
            'accent'    => $themeConfig['colors']['accent'],
        ];

        if (! empty($themeConfig['glass']['preset'])) {
            $params['preset'] = $themeConfig['glass']['preset'];
        }

        if ('transparent' !== $themeConfig['glass']['tint']['color']) {
            $params['tint']           = $themeConfig['glass']['tint']['color'];
            $params['tint_intensity'] = $themeConfig['glass']['tint']['intensity'];
        }

        // Build the preview URL using the application's URL
        $baseUrl    = config('app.url', 'http://localhost');
        $previewUrl = $baseUrl.'/artisanpack/theme-preview?'.http_build_query($params);

        $this->newLine();
        $this->info('🔗 '.__('Theme Preview URL:'));
        $this->line("   <comment>{$previewUrl}</comment>");
        $this->newLine();
        $this->line('   '.__('Note: The preview route must be registered in your application.'));
        $this->line('   '.__('Add the following to your routes/web.php:'));
        $this->newLine();
        $this->line("   <comment>Route::get('/artisanpack/theme-preview', function () {</comment>");
        $this->line("   <comment>    return view('artisanpack::theme-preview');</comment>");
        $this->line('   <comment>});</comment>');
    }

    /**
     * Apply tint color overrides to the CSS content.
     *
     * @since 2.0.0
     *
     * @param  string  $cssContent  The original CSS content.
     * @param  string|null  $tintColor  The tint color.
     * @param  string|null  $tintIntensity  The tint intensity.
     *
     * @return string The modified CSS content.
     */
    protected function applyTintOverrides(string $cssContent, ?string $tintColor, ?string $tintIntensity): string
    {
        $overrides = [];

        if ($tintColor && 'transparent' !== $tintColor) {
            // Convert Tailwind color names to hex if needed
            $hexColor = $tintColor;
            if (! str_starts_with($tintColor, '#')) {
                $colorGenerator = app(ColorGenerator::class);
                try {
                    $hexColor = $colorGenerator->getBaseColor($tintColor);
                } catch (Exception $e) {
                    // Keep the original value if conversion fails
                }
            }
            $overrides[] = "--glass-tint-color: {$hexColor};";
        }

        if ($tintIntensity) {
            $overrides[] = "--glass-tint-opacity: {$tintIntensity};";
        }

        if (empty($overrides)) {
            return $cssContent;
        }

        // Insert overrides after the glass token section in :root
        $overridesCss = "\n    /* --- Custom Tint Overrides --- */\n    ".implode("\n    ", $overrides)."\n";

        // Find the closing brace of :root and insert before it
        $cssContent = preg_replace(
            '/(:root\s*\{[^}]+)(})/s',
            '$1'.$overridesCss.'$2',
            $cssContent,
            1,
        );

        return $cssContent;
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
    protected function generateGlassTokensOnly(ColorGenerator $colorGenerator): int
    {
        $this->info('Generating Glass Design Tokens CSS file...');

        try {
            $overrides   = $this->getGlassTokenOverrides();
            $cssContent  = $colorGenerator->generateGlassTokensCss($overrides);
            $outputPath  = config('artisanpack.livewire-ui-components.glass.output_path');

            if (! $outputPath) {
                $outputPath = resource_path('css/artisanpack-glass-tokens.css');
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Glass Design Tokens CSS file generated successfully!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
            $this->warn('🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).');
            $this->newLine();
            $this->line('   <info>Usage:</info> Import the file in your app.css:');
            $this->line("   <comment>@import './artisanpack-glass-tokens.css';</comment>");

        } catch (Exception $e) {
            $this->error('❌ An error occurred while generating the glass tokens file:');
            $this->error($e->getMessage());

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
     * @param  string|null  $tintColor  Optional tint color override.
     * @param  string|null  $tintIntensity  Optional tint intensity override.
     */
    protected function generateStandaloneGlassTokens(
        ColorGenerator $colorGenerator,
        ?string $tintColor = null,
        ?string $tintIntensity = null,
    ): void {
        $outputPath = config('artisanpack.livewire-ui-components.glass.output_path');

        if (! $outputPath) {
            return;
        }

        try {
            $overrides = $this->getGlassTokenOverrides();

            // Apply tint color override if specified
            if ($tintColor && 'transparent' !== $tintColor) {
                // Convert Tailwind color names to hex if needed
                $hexColor = $tintColor;
                if (! str_starts_with($tintColor, '#')) {
                    try {
                        $hexColor = $colorGenerator->getBaseColor($tintColor);
                    } catch (Exception $e) {
                        // Keep the original value if conversion fails
                    }
                }
                $overrides['glass-tint-color'] = $hexColor;
            }

            // Apply tint intensity override if specified
            if ($tintIntensity) {
                $overrides['glass-tint-opacity'] = $tintIntensity;
            }

            $cssContent = $colorGenerator->generateGlassTokensCss($overrides);

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Glass Design Tokens CSS file also generated!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
        } catch (Exception $e) {
            $this->warn('⚠️ Could not generate standalone glass tokens file: '.$e->getMessage());
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
        $configTokens = config('artisanpack.livewire-ui-components.glass.tokens', []);
        $overrides    = [];

        foreach ($configTokens as $key => $value) {
            if (null !== $value) {
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
    protected function generateDesignTokensOnly(ColorGenerator $colorGenerator): int
    {
        $this->info('Generating Design Tokens CSS file...');

        try {
            $overrides   = $this->getDesignTokenOverrides();
            $cssContent  = $colorGenerator->generateDesignTokensCss($overrides);
            $outputPath  = config('artisanpack.livewire-ui-components.design_tokens.output_path');

            if (! $outputPath) {
                $outputPath = resource_path('css/artisanpack-design-tokens.css');
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Design Tokens CSS file generated successfully!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
            $this->warn('🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).');
            $this->newLine();
            $this->line('   <info>Usage:</info> Import the file in your app.css:');
            $this->line("   <comment>@import './artisanpack-design-tokens.css';</comment>");

        } catch (Exception $e) {
            $this->error('❌ An error occurred while generating the design tokens file:');
            $this->error($e->getMessage());

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
    protected function generateStandaloneDesignTokens(ColorGenerator $colorGenerator): void
    {
        $outputPath = config('artisanpack.livewire-ui-components.design_tokens.output_path');

        if (! $outputPath) {
            return;
        }

        try {
            $overrides  = $this->getDesignTokenOverrides();
            $cssContent = $colorGenerator->generateDesignTokensCss($overrides);

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Design Tokens CSS file also generated!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
        } catch (Exception $e) {
            $this->warn('⚠️ Could not generate standalone design tokens file: '.$e->getMessage());
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
        $typographyConfig = config('artisanpack.livewire-ui-components.design_tokens.typography', []);
        if (! empty($typographyConfig)) {
            $overrides['typography'] = $typographyConfig;
        }

        // Get spacing token overrides
        $spacingConfig = config('artisanpack.livewire-ui-components.design_tokens.spacing', []);
        if (! empty($spacingConfig)) {
            $overrides['spacing'] = $spacingConfig;
        }

        // Get radius token overrides
        $radiusConfig = config('artisanpack.livewire-ui-components.design_tokens.radius', []);
        if (! empty($radiusConfig)) {
            $overrides['radius'] = $radiusConfig;
        }

        // Get shadow token overrides
        $shadowConfig = config('artisanpack.livewire-ui-components.design_tokens.shadow', []);
        if (! empty($shadowConfig)) {
            $overrides['shadow'] = $shadowConfig;
        }

        // Get animation token overrides
        $animationConfig = config('artisanpack.livewire-ui-components.design_tokens.animation', []);
        if (! empty($animationConfig)) {
            $overrides['animation'] = $animationConfig;
        }

        // Get glass token overrides (reuse existing method)
        $glassOverrides = $this->getGlassTokenOverrides();
        if (! empty($glassOverrides)) {
            $overrides['glass'] = $glassOverrides;
        }

        return $overrides;
    }

    /**
     * Generate only the glass presets CSS file.
     *
     * @since 2.0.0
     *
     * @return int The command exit code.
     */
    protected function generateGlassPresetsOnly(): int
    {
        $this->info('Generating Glass Theme Presets CSS file...');

        try {
            $glassPresets = new GlassPresets;
            $presetName   = $this->option('preset');
            $cssContent   = $this->buildPresetsCss($glassPresets, $presetName);
            $outputPath   = config('artisanpack.livewire-ui-components.glass.presets.output_path');

            if (! $outputPath) {
                $outputPath = resource_path('css/artisanpack-glass-presets.css');
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Glass Theme Presets CSS file generated successfully!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
            $this->newLine();

            // Show available presets
            $this->line('   <info>Available presets:</info>');
            foreach ($glassPresets->getAvailablePresets() as $preset) {
                $description = $glassPresets->getPresetDescription($preset);
                $this->line("   - <comment>.{$preset}</comment>: {$description}");
            }

            $this->newLine();
            $this->warn('🚀 Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).');
            $this->newLine();
            $this->line('   <info>Usage:</info> Import the file in your app.css:');
            $this->line("   <comment>@import './artisanpack-glass-presets.css';</comment>");
            $this->newLine();
            $this->line('   <info>Then apply presets to your elements:</info>');
            $this->line('   <comment><div class="glass-frosted-light glass p-6 rounded-xl">Content</div></comment>');

        } catch (Exception $e) {
            $this->error('❌ An error occurred while generating the glass presets file:');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Generate standalone glass presets file alongside the main theme.
     *
     * @since 2.0.0
     *
     * @param  string|null  $presetName  Optional specific preset to highlight.
     */
    protected function generateStandaloneGlassPresets(?string $presetName = null): void
    {
        $outputPath = config('artisanpack.livewire-ui-components.glass.presets.output_path');

        if (! $outputPath) {
            return;
        }

        try {
            $glassPresets = new GlassPresets;
            $cssContent   = $this->buildPresetsCss($glassPresets, $presetName);

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ Glass Theme Presets CSS file also generated!');
            $this->line("   -> Location: <comment>{$outputPath}</comment>");

            if ($presetName && $glassPresets->isValidPreset($presetName)) {
                $this->line("   -> Default preset: <comment>{$presetName}</comment>");
            }
        } catch (Exception $e) {
            $this->warn('⚠️ Could not generate standalone glass presets file: '.$e->getMessage());
        }
    }

    /**
     * Build the CSS content for glass presets.
     *
     * @since 2.0.0
     *
     * @param  GlassPresets  $glassPresets  The glass presets instance.
     * @param  string|null  $defaultPreset  Optional default preset to apply globally.
     *
     * @return string The CSS content.
     */
    protected function buildPresetsCss(GlassPresets $glassPresets, ?string $defaultPreset = null): string
    {
        // Start with the full preset CSS
        $css = $glassPresets->generateAllPresetsCss();

        // If a default preset is specified and valid, add it to :root
        if ($defaultPreset && $glassPresets->isValidPreset($defaultPreset)) {
            $defaultTokens = $glassPresets->getPresetTokens($defaultPreset);
            $darkOverrides = $glassPresets->getDarkModeOverrides($defaultPreset);

            $css .= "\n/**\n * Default Glass Preset: {$defaultPreset}\n * Applied globally via :root\n */\n";
            $css .= ":root {\n";
            foreach ($defaultTokens as $property => $value) {
                $css .= "    --{$property}: {$value};\n";
            }
            $css .= "}\n";

            // Add dark mode defaults if available
            if (! empty($darkOverrides)) {
                $css .= "\n[data-theme=\"dark\"] {\n";
                foreach ($darkOverrides as $property => $value) {
                    $css .= "    --{$property}: {$value};\n";
                }
                $css .= "}\n";
            }
        }

        return $css;
    }

    /**
     * Generate only the high-contrast accessibility CSS file.
     *
     * @since 2.0.0
     *
     * @param  string|null  $accessibilityPreset  Optional specific preset to generate.
     *
     * @return int The command exit code.
     */
    protected function generateHighContrastOnly(?string $accessibilityPreset = null): int
    {
        $this->info(__('Generating High-Contrast Accessibility CSS file...'));

        try {
            $highContrastTheme = new HighContrastTheme;

            // Determine what to generate
            $options = [
                'include_presets'          => true,
                'include_prefers_contrast' => true,
                'include_reduced_motion'   => true,
                'include_focus_indicators' => true,
                'include_larger_text'      => true,
            ];

            $cssContent = $highContrastTheme->generateCompleteCss($options);

            // If a specific preset is requested, add it as default
            if ($accessibilityPreset && $highContrastTheme->isValidPreset($accessibilityPreset)) {
                $cssContent .= $this->buildDefaultAccessibilityPresetCss($highContrastTheme, $accessibilityPreset);
            }

            $outputPath = config('artisanpack.livewire-ui-components.high_contrast.output_path');

            if (! $outputPath) {
                $outputPath = resource_path('css/artisanpack-high-contrast.css');
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ '.__('High-Contrast Accessibility CSS file generated successfully!'));
            $this->line("   -> Location: <comment>{$outputPath}</comment>");
            $this->newLine();

            // Show available presets
            $this->line('   <info>'.__('Available accessibility presets:').'</info>');
            foreach ($highContrastTheme->getAvailablePresets() as $preset) {
                $description = $highContrastTheme->getPresetDescription($preset);
                $compliance  = $highContrastTheme->getComplianceLevel($preset);
                $this->line("   - <comment>.{$preset}</comment>: {$description} (WCAG {$compliance})");
            }

            $this->newLine();
            $this->warn('🚀 '.__('Remember to import this file into your main CSS/SCSS file and recompile your assets (e.g., npm run dev).'));
            $this->newLine();
            $this->line('   <info>'.__('Usage:').'</info> '.__('Import the file in your app.css:'));
            $this->line("   <comment>@import './artisanpack-high-contrast.css';</comment>");
            $this->newLine();
            $this->line('   <info>'.__('Features included:').'</info>');
            $this->line('   - '.__('High-contrast presets (WCAG AAA compliant)'));
            $this->line('   - '.__('prefers-contrast media query support'));
            $this->line('   - '.__('prefers-reduced-motion support'));
            $this->line('   - '.__('Enhanced focus indicators'));
            $this->line('   - '.__('Larger text options'));
            $this->newLine();
            $this->line('   <info>'.__('Then apply presets to your elements:').'</info>');
            $this->line('   <comment><html class="high-contrast-light"></comment>');
            $this->line('   <comment><div class="text-larger p-6">Larger, more readable text</div></comment>');

        } catch (Exception $e) {
            $this->error('❌ '.__('An error occurred while generating the high-contrast file:'));
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Generate standalone high-contrast CSS file alongside the main theme.
     *
     * @since 2.0.0
     *
     * @param  string|null  $accessibilityPreset  Optional specific preset to highlight.
     */
    protected function generateStandaloneHighContrast(?string $accessibilityPreset = null): void
    {
        $outputPath = config('artisanpack.livewire-ui-components.high_contrast.output_path');

        if (! $outputPath) {
            $outputPath = resource_path('css/artisanpack-high-contrast.css');
        }

        try {
            $highContrastTheme = new HighContrastTheme;

            $options = [
                'include_presets'          => true,
                'include_prefers_contrast' => true,
                'include_reduced_motion'   => true,
                'include_focus_indicators' => true,
                'include_larger_text'      => true,
            ];

            $cssContent = $highContrastTheme->generateCompleteCss($options);

            // If a specific preset is requested, add it as default
            if ($accessibilityPreset && $highContrastTheme->isValidPreset($accessibilityPreset)) {
                $cssContent .= $this->buildDefaultAccessibilityPresetCss($highContrastTheme, $accessibilityPreset);
            }

            $directory = dirname($outputPath);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            File::put($outputPath, $cssContent);

            $this->info('✅ '.__('High-Contrast Accessibility CSS file also generated!'));
            $this->line("   -> Location: <comment>{$outputPath}</comment>");

            if ($accessibilityPreset && $highContrastTheme->isValidPreset($accessibilityPreset)) {
                $compliance = $highContrastTheme->getComplianceLevel($accessibilityPreset);
                $this->line("   -> Default preset: <comment>{$accessibilityPreset}</comment> (WCAG {$compliance})");
            }
        } catch (Exception $e) {
            $this->warn('⚠️ '.__('Could not generate standalone high-contrast file: ').$e->getMessage());
        }
    }

    /**
     * Build default accessibility preset CSS to apply globally.
     *
     * @since 2.0.0
     *
     * @param  HighContrastTheme  $highContrastTheme  The high contrast theme instance.
     * @param  string  $presetName  The preset name to apply as default.
     *
     * @return string The CSS content for the default preset.
     */
    protected function buildDefaultAccessibilityPresetCss(HighContrastTheme $highContrastTheme, string $presetName): string
    {
        $tokens     = $highContrastTheme->getPresetTokens($presetName);
        $mode       = $highContrastTheme->getPresetMode($presetName);
        $compliance = $highContrastTheme->getComplianceLevel($presetName);

        if (empty($tokens)) {
            return '';
        }

        $css = "\n/**\n * Default Accessibility Preset: {$presetName}\n";
        $css .= " * Mode: {$mode}\n";
        $css .= " * WCAG {$compliance} Compliant\n";
        $css .= " * Applied globally via :root\n */\n";
        $css .= ":root {\n";

        foreach ($tokens as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }

        $css .= "}\n";

        // Add data attribute selector for toggling
        $css .= "\n[data-accessibility=\"{$presetName}\"] {\n";
        foreach ($tokens as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n";

        return $css;
    }
}
