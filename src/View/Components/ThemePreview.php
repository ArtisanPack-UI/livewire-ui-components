<?php

declare(strict_types=1);

/**
 * Theme Preview Component.
 *
 * A browser-based theme preview tool with real-time color adjustment,
 * component preview gallery, glass effect customization, and export functionality.
 *
 *
 * @author     Jacob Martella <me@jacobmartella.com>
 *
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use ArtisanPack\LivewireUiComponents\Styling\GlassPresets;
use ArtisanPack\LivewireUiComponents\Styling\HighContrastTheme;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Theme Preview Livewire Component.
 *
 * Provides an interactive interface for customizing and previewing themes.
 *
 * @since 2.0.0
 */
class ThemePreview extends Component
{
    /**
     * Primary color selection.
     */
    #[Url(as: 'primary')]
    public string $primaryColor = 'sky';

    /**
     * Secondary color selection.
     */
    #[Url(as: 'secondary')]
    public string $secondaryColor = 'slate';

    /**
     * Accent color selection.
     */
    #[Url(as: 'accent')]
    public string $accentColor = 'amber';

    /**
     * Custom primary hex color.
     */
    #[Url(as: 'primary_hex')]
    public string $primaryHex = '';

    /**
     * Custom secondary hex color.
     */
    #[Url(as: 'secondary_hex')]
    public string $secondaryHex = '';

    /**
     * Custom accent hex color.
     */
    #[Url(as: 'accent_hex')]
    public string $accentHex = '';

    /**
     * Use custom hex colors instead of Tailwind colors.
     */
    public bool $useCustomColors = false;

    /**
     * Selected glass preset.
     */
    #[Url(as: 'preset')]
    public string $glassPreset = '';

    /**
     * Glass tint color.
     */
    #[Url(as: 'tint')]
    public string $tintColor = 'transparent';

    /**
     * Glass tint intensity (0-1).
     */
    #[Url(as: 'tint_intensity')]
    public float $tintIntensity = 0.15;

    /**
     * Selected accessibility preset.
     */
    #[Url(as: 'accessibility')]
    public string $accessibilityPreset = '';

    /**
     * Dark mode toggle.
     */
    #[Url(as: 'dark')]
    public bool $darkMode = false;

    /**
     * Currently active preview section.
     */
    public string $activeSection = 'colors';

    /**
     * Currently selected component for preview.
     */
    public string $selectedComponent = 'button';

    /**
     * Available Tailwind colors with their 500-shade hex values.
     *
     * These are reference hex values used for the theme preview tool's
     * color calculations and display. While Tailwind v4 uses OKLCH
     * color variables internally, hex values are needed here for:
     * - Color picker integration
     * - Contrast ratio calculations
     * - CSS variable generation
     * - Shareable URL parameters
     *
     * The hex values correspond to each color's 500 shade and are
     * compatible with both Tailwind v3 and v4 color palettes.
     *
     * @since 2.0.0
     */
    public array $tailwindColors = [
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
        'slate'   => '#64748b',
        'gray'    => '#6b7280',
        'zinc'    => '#71717a',
        'neutral' => '#737373',
        'stone'   => '#78716c',
    ];

    /**
     * Preview component categories.
     */
    public array $componentCategories = [
        'form'     => ['button', 'input', 'checkbox', 'select', 'toggle'],
        'feedback' => ['alert', 'badge', 'progress'],
        'layout'   => ['card', 'modal'],
        'data'     => ['table', 'stat'],
    ];

    /**
     * Mount the component with URL parameters.
     */
    public function mount(): void
    {
        // Check if custom hex colors are provided
        if (! empty($this->primaryHex) || ! empty($this->secondaryHex) || ! empty($this->accentHex)) {
            $this->useCustomColors = true;
        }
    }

    /**
     * Get the effective primary color (hex).
     */
    #[Computed]
    public function effectivePrimaryColor(): string
    {
        if ($this->useCustomColors && ! empty($this->primaryHex)) {
            return $this->primaryHex;
        }

        return $this->tailwindColors[$this->primaryColor] ?? '#0ea5e9';
    }

    /**
     * Get the effective secondary color (hex).
     */
    #[Computed]
    public function effectiveSecondaryColor(): string
    {
        if ($this->useCustomColors && ! empty($this->secondaryHex)) {
            return $this->secondaryHex;
        }

        return $this->tailwindColors[$this->secondaryColor] ?? '#64748b';
    }

    /**
     * Get the effective accent color (hex).
     */
    #[Computed]
    public function effectiveAccentColor(): string
    {
        if ($this->useCustomColors && ! empty($this->accentHex)) {
            return $this->accentHex;
        }

        return $this->tailwindColors[$this->accentColor] ?? '#f59e0b';
    }

    /**
     * Get available glass presets.
     */
    #[Computed]
    public function glassPresets(): array
    {
        $presets     = new GlassPresets;
        $presetNames = $presets->getAvailablePresets();
        $result      = ['' => __('None')];

        foreach ($presetNames as $name) {
            $result[$name] = $name.' - '.$presets->getPresetDescription($name);
        }

        return $result;
    }

    /**
     * Get available accessibility presets.
     */
    #[Computed]
    public function accessibilityPresets(): array
    {
        $theme       = new HighContrastTheme;
        $presetNames = $theme->getAvailablePresets();
        $result      = ['' => __('None')];

        foreach ($presetNames as $name) {
            $result[$name] = $name.' ('.$theme->getComplianceLevel($name).')';
        }

        return $result;
    }

    /**
     * Get the shareable preview URL.
     */
    #[Computed]
    public function shareableUrl(): string
    {
        $params = [];

        if ($this->useCustomColors) {
            if (! empty($this->primaryHex)) {
                $params['primary_hex'] = ltrim($this->primaryHex, '#');
            }
            if (! empty($this->secondaryHex)) {
                $params['secondary_hex'] = ltrim($this->secondaryHex, '#');
            }
            if (! empty($this->accentHex)) {
                $params['accent_hex'] = ltrim($this->accentHex, '#');
            }
        } else {
            $params['primary']   = $this->primaryColor;
            $params['secondary'] = $this->secondaryColor;
            $params['accent']    = $this->accentColor;
        }

        if (! empty($this->glassPreset)) {
            $params['preset'] = $this->glassPreset;
        }

        if ('transparent' !== $this->tintColor) {
            $params['tint']           = $this->tintColor;
            $params['tint_intensity'] = $this->tintIntensity;
        }

        if (! empty($this->accessibilityPreset)) {
            $params['accessibility'] = $this->accessibilityPreset;
        }

        if ($this->darkMode) {
            $params['dark'] = '1';
        }

        $baseUrl = config('app.url', request()->getSchemeAndHttpHost());

        return $baseUrl.'/artisanpack/theme-preview?'.http_build_query($params);
    }

    /**
     * Generate CSS variables for the current theme.
     */
    #[Computed]
    public function generatedCss(): string
    {
        $colorGenerator = app(ColorGenerator::class);

        try {
            $primaryColor   = $this->useCustomColors && ! empty($this->primaryHex)
                ? $this->primaryHex
                : $this->primaryColor;
            $secondaryColor = $this->useCustomColors && ! empty($this->secondaryHex)
                ? $this->secondaryHex
                : $this->secondaryColor;
            $accentColor    = $this->useCustomColors && ! empty($this->accentHex)
                ? $this->accentHex
                : $this->accentColor;

            return $colorGenerator->generateThemeCss($primaryColor, $secondaryColor, $accentColor);
        } catch (Exception $e) {
            return "/* Error generating CSS: {$e->getMessage()} */";
        }
    }

    /**
     * Generate inline CSS variables for live preview.
     */
    #[Computed]
    public function previewCssVariables(): string
    {
        $css = ':root {';

        // Primary color
        $css .= "--primary: {$this->effectivePrimaryColor};";
        $css .= '--primary-content: #ffffff;';

        // Secondary color
        $css .= "--secondary: {$this->effectiveSecondaryColor};";
        $css .= '--secondary-content: #ffffff;';

        // Accent color
        $css .= "--accent: {$this->effectiveAccentColor};";
        $css .= '--accent-content: #ffffff;';

        // Glass tint
        if ('transparent' !== $this->tintColor && ! empty($this->tintColor)) {
            $tintHex = $this->tailwindColors[$this->tintColor] ?? $this->tintColor;
            $css .= "--glass-tint-color: {$tintHex};";
            $css .= "--glass-tint-opacity: {$this->tintIntensity};";
        }

        $css .= '}';

        return $css;
    }

    /**
     * Export theme configuration as JSON.
     */
    public function exportJson(): array
    {
        $config = [
            'version'   => '2.0.0',
            'generated' => now()->toIso8601String(),
            'colors'    => [
                'primary'   => $this->useCustomColors ? $this->primaryHex : $this->primaryColor,
                'secondary' => $this->useCustomColors ? $this->secondaryHex : $this->secondaryColor,
                'accent'    => $this->useCustomColors ? $this->accentHex : $this->accentColor,
            ],
            'glass' => [
                'preset' => $this->glassPreset ?: null,
                'tint'   => [
                    'color'     => $this->tintColor,
                    'intensity' => $this->tintIntensity,
                ],
            ],
            'accessibility' => [
                'preset' => $this->accessibilityPreset ?: null,
            ],
            'preview_url' => $this->shareableUrl,
        ];

        // Add glass preset tokens if selected
        if (! empty($this->glassPreset)) {
            $glassPresets                 = new GlassPresets;
            $config['glass']['tokens']    = $glassPresets->getPresetTokens($this->glassPreset);
        }

        // Add accessibility preset tokens if selected
        if (! empty($this->accessibilityPreset)) {
            $highContrastTheme                     = new HighContrastTheme;
            $config['accessibility']['tokens']     = $highContrastTheme->getPresetTokens($this->accessibilityPreset);
            $config['accessibility']['compliance'] = $highContrastTheme->getComplianceLevel($this->accessibilityPreset);
        }

        return $config;
    }

    /**
     * Download the theme configuration as JSON.
     */
    public function downloadJson(): mixed
    {
        $json     = json_encode($this->exportJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = 'artisanpack-theme-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(function () use ($json): void {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Download the generated CSS.
     */
    public function downloadCss(): mixed
    {
        $css      = $this->generatedCss;
        $filename = 'artisanpack-theme-'.now()->format('Y-m-d-His').'.css';

        return response()->streamDownload(function () use ($css): void {
            echo $css;
        }, $filename, [
            'Content-Type' => 'text/css',
        ]);
    }

    /**
     * Copy the shareable URL to clipboard.
     */
    public function copyShareableUrl(): void
    {
        $this->dispatch('copy-to-clipboard', text: $this->shareableUrl);
        $this->dispatch('notify', message: __('URL copied to clipboard!'), type: 'success');
    }

    /**
     * Reset to default theme.
     */
    public function resetTheme(): void
    {
        $this->primaryColor        = 'sky';
        $this->secondaryColor      = 'slate';
        $this->accentColor         = 'amber';
        $this->primaryHex          = '';
        $this->secondaryHex        = '';
        $this->accentHex           = '';
        $this->useCustomColors     = false;
        $this->glassPreset         = '';
        $this->tintColor           = 'transparent';
        $this->tintIntensity       = 0.15;
        $this->accessibilityPreset = '';
        $this->darkMode            = false;
    }

    /**
     * Generate the artisan command for this theme.
     */
    #[Computed]
    public function artisanCommand(): string
    {
        $command = 'php artisan artisanpack:generate-theme';

        if ($this->useCustomColors) {
            if (! empty($this->primaryHex)) {
                $command .= " --primary={$this->primaryHex}";
            }
            if (! empty($this->secondaryHex)) {
                $command .= " --secondary={$this->secondaryHex}";
            }
            if (! empty($this->accentHex)) {
                $command .= " --accent={$this->accentHex}";
            }
        } else {
            $command .= " --primary={$this->primaryColor}";
            $command .= " --secondary={$this->secondaryColor}";
            $command .= " --accent={$this->accentColor}";
        }

        if (! empty($this->glassPreset)) {
            $command .= " --preset={$this->glassPreset}";
        }

        if ('transparent' !== $this->tintColor && ! empty($this->tintColor)) {
            $command .= " --tint-color={$this->tintColor}";
            $command .= " --tint-intensity={$this->tintIntensity}";
        }

        if (! empty($this->accessibilityPreset)) {
            $command .= " --accessibility-preset={$this->accessibilityPreset}";
        }

        return $command;
    }

    /**
     * Get the color options for select dropdowns.
     */
    #[Computed]
    public function colorOptions(): array
    {
        $options = [];

        foreach ($this->tailwindColors as $name => $hex) {
            $options[] = [
                'value' => $name,
                'label' => ucfirst($name),
                'color' => $hex,
            ];
        }

        return $options;
    }

    /**
     * Get the tint color options.
     */
    #[Computed]
    public function tintColorOptions(): array
    {
        $options = [
            ['value' => 'transparent', 'label' => __('No Tint'), 'color' => 'transparent'],
        ];

        foreach ($this->tailwindColors as $name => $hex) {
            $options[] = [
                'value' => $name,
                'label' => ucfirst($name),
                'color' => $hex,
            ];
        }

        return $options;
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.theme-preview');
    }
}
