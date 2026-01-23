<?php

declare(strict_types=1);
/**
 * Livewire Helper
 *
 * This class provides utility methods for Livewire version detection and feature support.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Support;

use Livewire\Livewire;
use OutOfBoundsException;

/**
 * LivewireHelper Class
 *
 * Provides utility methods for Livewire version detection and feature compatibility checks.
 *
 * @since 2.0.0
 */
class LivewireHelper
{
    /**
     * Cached Livewire version.
     *
     * @since 2.0.0
     */
    protected static ?string $cachedVersion = null;

    /**
     * Get the installed Livewire version.
     *
     * @since 2.0.0
     *
     * @return string The Livewire version string (e.g., "3.5.6" or "4.0.0").
     */
    public static function getVersion(): string
    {
        if (null !== self::$cachedVersion) {
            return self::$cachedVersion;
        }

        // Try to get version from Livewire class constant
        if (defined('Livewire\Livewire::VERSION')) {
            self::$cachedVersion = Livewire::VERSION;

            return self::$cachedVersion;
        }

        // Try to get version from InstalledVersions (Composer runtime API)
        if (class_exists(\Composer\InstalledVersions::class)) {
            try {
                $version             = \Composer\InstalledVersions::getVersion('livewire/livewire');
                self::$cachedVersion = ltrim($version ?? '3.0.0', 'v');

                return self::$cachedVersion;
            } catch (OutOfBoundsException $e) {
                // Package not installed, continue to fallback
            }
        }

        // Fallback: check composer.lock file
        if (function_exists('base_path')) {
            $composerLock = base_path('composer.lock');
            if (file_exists($composerLock)) {
                $lock = json_decode(file_get_contents($composerLock), true);

                // Validate JSON decode was successful and returned an array
                if (is_array($lock) && JSON_ERROR_NONE === json_last_error()) {
                    $packages = array_merge($lock['packages'] ?? [], $lock['packages-dev'] ?? []);

                    foreach ($packages as $package) {
                        if ('livewire/livewire' === $package['name']) {
                            self::$cachedVersion = ltrim($package['version'], 'v');

                            return self::$cachedVersion;
                        }
                    }
                }
            }
        }

        // Default to 3.0.0 if we can't determine
        self::$cachedVersion = '3.0.0';

        return self::$cachedVersion;
    }

    /**
     * Get the major version number.
     *
     * @since 2.0.0
     *
     * @return int The major version number (e.g., 3 or 4).
     */
    public static function getMajorVersion(): int
    {
        $version = self::getVersion();

        return (int) explode('.', $version)[0];
    }

    /**
     * Check if Livewire 4 or higher is installed.
     *
     * @since 2.0.0
     *
     * @return bool True if Livewire 4+, false otherwise.
     */
    public static function isLivewire4(): bool
    {
        return self::getMajorVersion() >= 4;
    }

    /**
     * Check if Livewire 3 is installed.
     *
     * @since 2.0.0
     *
     * @return bool True if Livewire 3.x, false otherwise.
     */
    public static function isLivewire3(): bool
    {
        return 3 === self::getMajorVersion();
    }

    /**
     * Check if wire:sort feature is supported.
     *
     * wire:sort was introduced in Livewire 4.
     *
     * @since 2.0.0
     *
     * @return bool True if wire:sort is supported.
     */
    public static function supportsWireSort(): bool
    {
        return self::isLivewire4();
    }

    /**
     * Check if wire:intersect feature is supported.
     *
     * wire:intersect was introduced in Livewire 4.
     *
     * @since 2.0.0
     *
     * @return bool True if wire:intersect is supported.
     */
    public static function supportsWireIntersect(): bool
    {
        return self::isLivewire4();
    }

    /**
     * Check if #[Computed] attribute is supported.
     *
     * The #[Computed] attribute was introduced in Livewire 3 and is available in both Livewire 3 and 4.
     *
     * @since 2.0.0
     *
     * @return bool True if #[Computed] attribute is supported.
     */
    public static function supportsComputedAttribute(): bool
    {
        return class_exists(\Livewire\Attributes\Computed::class);
    }

    /**
     * Check if #[Lazy] attribute is supported.
     *
     * The #[Lazy] attribute was introduced in Livewire 3 and is available in both Livewire 3 and 4.
     * It enables deferred loading of components, showing a placeholder until they become visible.
     *
     * @since 2.0.0
     *
     * @return bool True if #[Lazy] attribute is supported.
     */
    public static function supportsLazyAttribute(): bool
    {
        return class_exists(\Livewire\Attributes\Lazy::class);
    }

    /**
     * Check if #[Renderless] attribute is supported.
     *
     * The #[Renderless] attribute was introduced in Livewire 3 and is available in both Livewire 3 and 4.
     * It allows methods to execute without triggering a component re-render, useful for
     * analytics tracking, logging, and other non-UI operations.
     *
     * @since 2.0.0
     *
     * @return bool True if #[Renderless] attribute is supported.
     */
    public static function supportsRenderlessAttribute(): bool
    {
        return class_exists(\Livewire\Attributes\Renderless::class);
    }

    /**
     * Check if async actions are supported.
     *
     * Async actions allow non-blocking UI operations. This is achieved through
     * different mechanisms depending on Livewire version:
     * - Livewire 4+: Native async support
     * - Livewire 3: Use wire:click with loading states or queue jobs
     *
     * @since 2.0.0
     *
     * @return bool True if native async is supported (Livewire 4+).
     */
    public static function supportsAsyncActions(): bool
    {
        return self::isLivewire4();
    }

    /**
     * Check if Islands architecture is supported.
     *
     * Islands allow isolated updates within complex components. Different parts
     * of a component can update independently without affecting other regions.
     * This is implemented using wire:key and Alpine.js isolation patterns.
     *
     * - Livewire 4+: Full Islands support with optimized partial updates
     * - Livewire 3: Falls back to normal rendering (still works, just less optimized)
     *
     * @since 2.0.0
     *
     * @return bool True if Islands are fully supported (Livewire 4+).
     */
    public static function supportsIslands(): bool
    {
        return self::isLivewire4();
    }

    /**
     * Check if wire:stream directive is supported.
     *
     * wire:stream enables real-time content streaming, particularly useful for
     * AI-generated content that streams character by character or chunk by chunk.
     *
     * - Livewire 4+: Native wire:stream support with $this->stream() method
     * - Livewire 3: Not supported (content must be sent all at once)
     *
     * @since 2.0.0
     *
     * @return bool True if wire:stream is supported (Livewire 4+).
     */
    public static function supportsWireStream(): bool
    {
        return self::isLivewire4();
    }

    /**
     * Clear the cached version (useful for testing).
     *
     * @since 2.0.0
     */
    public static function clearCache(): void
    {
        self::$cachedVersion = null;
    }
}
