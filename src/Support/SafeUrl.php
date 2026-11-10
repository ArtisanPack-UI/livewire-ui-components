<?php

/**
 * URL scheme checks for the design-system components.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * Drops URLs that would run script when followed or loaded: `javascript:`,
 * `vbscript:` and, for links, `data:` (markup contract, review amendment 2).
 * The check ignores case, whitespace and control characters, the way
 * browsers read a scheme.
 *
 * @since 3.0.0
 */
final class SafeUrl
{
    /**
     * A link target, or null when it's empty or unsafe.
     *
     * @since 3.0.0
     *
     * @param  string|null  $url  The href.
     *
     * @return string|null The href to render.
     */
    public static function href(?string $url): ?string
    {
        return self::allowed($url, ['javascript', 'vbscript', 'data']) ? $url : null;
    }

    /**
     * A media source, or null when it's empty or unsafe. `data:` images stay
     * allowed.
     *
     * @since 3.0.0
     *
     * @param  string|null  $url  The src.
     *
     * @return string|null The src to render.
     */
    public static function media(?string $url): ?string
    {
        return self::allowed($url, ['javascript', 'vbscript']) ? $url : null;
    }

    /**
     * Whether a URL is non-empty and doesn't use a blocked scheme.
     *
     * @param  string|null  $url  The URL.
     * @param  list<string>  $blocked  Blocked schemes.
     *
     * @return bool True when it can render.
     */
    private static function allowed(?string $url, array $blocked): bool
    {
        if (null === $url || '' === trim($url)) {
            return false;
        }

        $normalized = strtolower((string) preg_replace('/[\x00-\x20\x7f]+/', '', $url));

        foreach ($blocked as $scheme) {
            if (str_starts_with($normalized, $scheme.':')) {
                return false;
            }
        }

        return true;
    }
}
