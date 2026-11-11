<?php

/**
 * URL scheme checks for the design-system components.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * Allowlists the URLs the design-system components render (markup contract,
 * review amendment 2). Anything else is treated as absent.
 *
 * - Links (`href`): relative and scheme-less URLs (`/`, `./`, `?`, `#`,
 *   `//host`), http, https, mailto, tel and sms.
 * - Media (`src`): relative URLs, http, https, blob and `data:image/…`.
 *
 * The scheme is read the way browsers read it: ignoring case, whitespace and
 * control characters.
 *
 * @since 3.0.0
 */
final class SafeUrl
{
    /**
     * Schemes a link may use.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const LINK_SCHEMES = ['http', 'https', 'mailto', 'tel', 'sms'];

    /**
     * Schemes a media source may use, besides `data:image/`.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const MEDIA_SCHEMES = ['http', 'https', 'blob'];

    /**
     * A link target, or null when it's empty or not allowed.
     *
     * @since 3.0.0
     *
     * @param  string|null  $url  The href.
     *
     * @return string|null The href to render.
     */
    public static function href(?string $url): ?string
    {
        $scheme = self::scheme($url);

        if (false === $scheme) {
            return null;
        }

        return null === $scheme || in_array($scheme, self::LINK_SCHEMES, true) ? $url : null;
    }

    /**
     * Whether a link should load through Livewire's `wire:navigate`: a
     * relative URL or an http(s) URL on this host. A fragment (`#…`), a
     * protocol-relative or off-site URL, and other schemes (`mailto:`,
     * `tel:`) load normally.
     *
     * @since 3.0.0
     *
     * @param  string|null  $url  The href.
     *
     * @return bool True when the link may use `wire:navigate`.
     */
    public static function navigable(?string $url): bool
    {
        if (null === $url) {
            return false;
        }

        $url = trim($url);
        if ('' === $url || str_starts_with($url, '#') || str_starts_with($url, '//')) {
            return false;
        }

        $scheme = self::scheme($url);
        if (false === $scheme) {
            return false;
        }

        if (null === $scheme) {
            return true;
        }

        if (! in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) && 0 === strcasecmp($host, request()->getHost());
    }

    /**
     * A media source, or null when it's empty or not allowed.
     *
     * @since 3.0.0
     *
     * @param  string|null  $url  The src.
     *
     * @return string|null The src to render.
     */
    public static function media(?string $url): ?string
    {
        $scheme = self::scheme($url);

        if (false === $scheme) {
            return null;
        }

        if (null === $scheme || in_array($scheme, self::MEDIA_SCHEMES, true)) {
            return $url;
        }

        return 'data' === $scheme && str_starts_with(self::normalize((string) $url), 'data:image/') ? $url : null;
    }

    /**
     * The URL's scheme, lowercased; null for a relative or scheme-less URL;
     * false for an empty one.
     *
     * @param  string|null  $url  The URL.
     *
     * @return false|string|null The scheme.
     */
    private static function scheme(?string $url): string|false|null
    {
        if (null === $url) {
            return false;
        }

        $normalized = self::normalize($url);
        if ('' === $normalized) {
            return false;
        }

        return 1 === preg_match('/^([a-z][a-z0-9+.\-]*):/', $normalized, $match) ? $match[1] : null;
    }

    /**
     * The URL without whitespace or control characters, lowercased.
     *
     * @param  string  $url  The URL.
     *
     * @return string The normalized URL.
     */
    private static function normalize(string $url): string
    {
        return strtolower((string) preg_replace('/[\x00-\x20\x7f]+/', '', $url));
    }
}
