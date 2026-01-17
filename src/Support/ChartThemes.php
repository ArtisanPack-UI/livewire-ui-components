<?php

declare(strict_types=1);
/**
 * Chart Themes
 *
 * This class provides pre-styled chart themes for ApexCharts that match
 * the ArtisanPack UI glass aesthetic.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * ChartThemes Class
 *
 * Provides pre-styled chart themes for ApexCharts integration.
 * Includes light, dark, and glass-optimized themes.
 *
 * @since 2.0.0
 */
class ChartThemes
{
    /**
     * Valid theme names.
     *
     * @since 2.0.0
     *
     * @var array<string>
     */
    protected static array $validThemes = [
        'artisanpack-light',
        'artisanpack-dark',
        'artisanpack-glass',
    ];

    /**
     * Default color palette for charts.
     *
     * Based on design token colors for consistency.
     *
     * @since 2.0.0
     *
     * @var array<string>
     */
    protected static array $defaultColors = [
        '#3b82f6', // blue-500
        '#8b5cf6', // violet-500
        '#06b6d4', // cyan-500
        '#10b981', // emerald-500
        '#f59e0b', // amber-500
        '#ef4444', // red-500
        '#ec4899', // pink-500
        '#6366f1', // indigo-500
    ];

    /**
     * Get a pre-styled theme configuration.
     *
     * @since 2.0.0
     *
     * @param  string  $themeName  The theme name (artisanpack-light, artisanpack-dark, artisanpack-glass).
     *
     * @return array<string, mixed> The theme configuration array.
     */
    public static function get(string $themeName): array
    {
        return match ($themeName) {
            'artisanpack-light' => self::getLightTheme(),
            'artisanpack-dark'  => self::getDarkTheme(),
            'artisanpack-glass' => self::getGlassTheme(),
            default             => [],
        };
    }

    /**
     * Get the light theme configuration.
     *
     * Clean, minimal theme optimized for light mode backgrounds.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The light theme configuration.
     */
    public static function getLightTheme(): array
    {
        return [
            'colors' => self::$defaultColors,
            'chart'  => [
                'foreColor'  => '#374151',
                'background' => 'transparent',
                'toolbar'    => [
                    'show'  => true,
                    'tools' => [
                        'download'  => true,
                        'selection' => true,
                        'zoom'      => true,
                        'zoomin'    => true,
                        'zoomout'   => true,
                        'pan'       => true,
                        'reset'     => true,
                    ],
                ],
                'dropShadow' => [
                    'enabled' => false,
                ],
            ],
            'theme' => [
                'mode' => 'light',
            ],
            'grid' => [
                'borderColor'     => '#e5e7eb',
                'strokeDashArray' => 4,
                'xaxis'           => [
                    'lines' => [
                        'show' => false,
                    ],
                ],
            ],
            'xaxis' => [
                'axisBorder' => [
                    'show'  => true,
                    'color' => '#d1d5db',
                ],
                'axisTicks' => [
                    'show'  => true,
                    'color' => '#d1d5db',
                ],
                'labels' => [
                    'style' => [
                        'colors'   => '#6b7280',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors'   => '#6b7280',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'tooltip' => [
                'theme' => 'light',
                'style' => [
                    'fontSize' => '12px',
                ],
            ],
            'legend' => [
                'labels' => [
                    'colors' => '#374151',
                ],
                'fontSize'   => '12px',
                'fontWeight' => 500,
            ],
            'fill' => [
                'opacity' => 1,
            ],
            'stroke' => [
                'width' => 2,
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'style' => [
                    'colors' => ['#374151'],
                ],
            ],
        ];
    }

    /**
     * Get the dark theme configuration.
     *
     * Dark theme with subtle glow effects for dark mode backgrounds.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The dark theme configuration.
     */
    public static function getDarkTheme(): array
    {
        return [
            'colors' => [
                '#60a5fa', // blue-400 (brighter for dark bg)
                '#a78bfa', // violet-400
                '#22d3ee', // cyan-400
                '#34d399', // emerald-400
                '#fbbf24', // amber-400
                '#f87171', // red-400
                '#f472b6', // pink-400
                '#818cf8', // indigo-400
            ],
            'chart' => [
                'foreColor'  => '#e5e7eb',
                'background' => 'transparent',
                'toolbar'    => [
                    'show'  => true,
                    'tools' => [
                        'download'  => true,
                        'selection' => true,
                        'zoom'      => true,
                        'zoomin'    => true,
                        'zoomout'   => true,
                        'pan'       => true,
                        'reset'     => true,
                    ],
                ],
                'dropShadow' => [
                    'enabled' => true,
                    'top'     => 0,
                    'left'    => 0,
                    'blur'    => 8,
                    'opacity' => 0.3,
                    'color'   => '#60a5fa',
                ],
            ],
            'theme' => [
                'mode' => 'dark',
            ],
            'grid' => [
                'borderColor'     => '#374151',
                'strokeDashArray' => 4,
                'xaxis'           => [
                    'lines' => [
                        'show' => false,
                    ],
                ],
            ],
            'xaxis' => [
                'axisBorder' => [
                    'show'  => true,
                    'color' => '#4b5563',
                ],
                'axisTicks' => [
                    'show'  => true,
                    'color' => '#4b5563',
                ],
                'labels' => [
                    'style' => [
                        'colors'   => '#9ca3af',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors'   => '#9ca3af',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'tooltip' => [
                'theme' => 'dark',
                'style' => [
                    'fontSize' => '12px',
                ],
            ],
            'legend' => [
                'labels' => [
                    'colors' => '#e5e7eb',
                ],
                'fontSize'   => '12px',
                'fontWeight' => 500,
            ],
            'fill' => [
                'opacity' => 0.9,
            ],
            'stroke' => [
                'width' => 2,
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'style' => [
                    'colors' => ['#e5e7eb'],
                ],
            ],
        ];
    }

    /**
     * Get the glass theme configuration.
     *
     * Theme optimized for glass backgrounds with enhanced transparency
     * and subtle visual effects.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The glass theme configuration.
     */
    public static function getGlassTheme(): array
    {
        return [
            'colors' => [
                '#60a5fa', // blue-400
                '#a78bfa', // violet-400
                '#22d3ee', // cyan-400
                '#34d399', // emerald-400
                '#fbbf24', // amber-400
                '#f87171', // red-400
                '#f472b6', // pink-400
                '#818cf8', // indigo-400
            ],
            'chart' => [
                'foreColor'  => '#9ca3af',
                'background' => 'transparent',
                'toolbar'    => [
                    'show'  => true,
                    'tools' => [
                        'download'  => true,
                        'selection' => true,
                        'zoom'      => true,
                        'zoomin'    => true,
                        'zoomout'   => true,
                        'pan'       => true,
                        'reset'     => true,
                    ],
                ],
                'dropShadow' => [
                    'enabled' => true,
                    'top'     => 0,
                    'left'    => 0,
                    'blur'    => 12,
                    'opacity' => 0.15,
                    'color'   => '#000000',
                ],
            ],
            'grid' => [
                'borderColor'     => 'rgba(255, 255, 255, 0.1)',
                'strokeDashArray' => 4,
                'xaxis'           => [
                    'lines' => [
                        'show' => false,
                    ],
                ],
            ],
            'xaxis' => [
                'axisBorder' => [
                    'show'  => true,
                    'color' => 'rgba(255, 255, 255, 0.2)',
                ],
                'axisTicks' => [
                    'show'  => true,
                    'color' => 'rgba(255, 255, 255, 0.2)',
                ],
                'labels' => [
                    'style' => [
                        'colors'   => '#9ca3af',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors'   => '#9ca3af',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'tooltip' => [
                'theme'   => 'dark',
                'style'   => [
                    'fontSize' => '12px',
                ],
                'fillSeriesColor' => false,
                'custom'          => null,
            ],
            'legend' => [
                'labels' => [
                    'colors' => '#9ca3af',
                ],
                'fontSize'   => '12px',
                'fontWeight' => 500,
            ],
            'fill' => [
                'type'     => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom'    => 0.7,
                    'opacityTo'      => 0.2,
                    'stops'          => [0, 90, 100],
                ],
            ],
            'stroke' => [
                'width' => 2,
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'style' => [
                    'colors' => ['#9ca3af'],
                ],
            ],
            'markers' => [
                'size'        => 0,
                'strokeWidth' => 2,
                'hover'       => [
                    'size' => 6,
                ],
            ],
        ];
    }

    /**
     * Get the light variant of glass theme.
     *
     * Used when glass is displayed on light mode.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The light glass theme overrides.
     */
    public static function getGlassLightOverrides(): array
    {
        return [
            'chart' => [
                'foreColor' => '#374151',
            ],
            'grid' => [
                'borderColor' => 'rgba(0, 0, 0, 0.1)',
            ],
            'xaxis' => [
                'axisBorder' => [
                    'color' => 'rgba(0, 0, 0, 0.2)',
                ],
                'axisTicks' => [
                    'color' => 'rgba(0, 0, 0, 0.2)',
                ],
            ],
            'tooltip' => [
                'theme' => 'light',
            ],
        ];
    }

    /**
     * Get the dark variant of glass theme.
     *
     * Used when glass is displayed on dark mode.
     *
     * @since 2.0.0
     *
     * @return array<string, mixed> The dark glass theme overrides.
     */
    public static function getGlassDarkOverrides(): array
    {
        return [
            'chart' => [
                'foreColor' => '#e5e7eb',
            ],
            'grid' => [
                'borderColor' => 'rgba(255, 255, 255, 0.1)',
            ],
            'xaxis' => [
                'axisBorder' => [
                    'color' => 'rgba(255, 255, 255, 0.2)',
                ],
                'axisTicks' => [
                    'color' => 'rgba(255, 255, 255, 0.2)',
                ],
            ],
            'tooltip' => [
                'theme' => 'dark',
            ],
        ];
    }

    /**
     * Check if a theme name is valid.
     *
     * @since 2.0.0
     *
     * @param  string|null  $themeName  The theme name to validate.
     *
     * @return bool True if valid, false otherwise.
     */
    public static function isValidTheme(?string $themeName): bool
    {
        return null !== $themeName && in_array($themeName, self::$validThemes, true);
    }

    /**
     * Get all valid theme names.
     *
     * @since 2.0.0
     *
     * @return array<string> Array of valid theme names.
     */
    public static function getValidThemes(): array
    {
        return self::$validThemes;
    }

    /**
     * Get the default color palette.
     *
     * @since 2.0.0
     *
     * @return array<string> Array of hex color codes.
     */
    public static function getDefaultColors(): array
    {
        return self::$defaultColors;
    }
}
