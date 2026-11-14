<?php

/**
 * Design System Assets
 *
 * Prints the tags for the prebuilt design-system CSS and JS this package
 * ships in `dist/` (#42, D-014): the bundler-free path for apps without Vite.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use RuntimeException;

/**
 * Reads `dist/manifest.json` and prints the `<link>` and `<script>` tags for
 * the published assets, with a content hash on each URL for cache-busting.
 *
 * The files are served from the public directory after
 * `php artisan vendor:publish --tag=artisanpack-design-system-assets`.
 *
 * @since 3.0.0
 */
class DesignSystemAssets
{
    /**
     * The publish tag for the prebuilt assets.
     *
     * @since 3.0.0
     */
    public const PUBLISH_TAG = 'artisanpack-design-system-assets';

    /**
     * Where the assets are published, relative to the public directory.
     *
     * @since 3.0.0
     */
    public const DEFAULT_PUBLIC_PATH = 'vendor/artisanpack-ui/design-system';

    /**
     * The decoded manifest, once read.
     *
     * @var array{styles: array<string, string>, scripts: array<string, string>, files: array<string, string>}|null
     */
    protected ?array $manifest = null;

    /**
     * The published manifest's file hashes, once read.
     *
     * @since 3.0.0
     *
     * @var array<string, string>|null
     */
    protected ?array $publishedHashes = null;

    /**
     * Creates the asset helper.
     *
     * @since 3.0.0
     *
     * @param  string|null  $distPath  The directory holding `manifest.json`. Defaults to the package's `dist/`.
     */
    public function __construct(protected ?string $distPath = null)
    {
        $this->distPath ??= static::packageDistPath();
    }

    /**
     * The package's `dist/` directory.
     *
     * @since 3.0.0
     */
    public static function packageDistPath(): string
    {
        return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'dist';
    }

    /**
     * Prints the stylesheet `<link>` tag.
     *
     * @since 3.0.0
     *
     * @param  string|null  $entry  `default` or `headless`. Defaults to the `assets.stylesheet` config value.
     *
     * @throws RuntimeException When the entry is not in the manifest.
     */
    public function styles(?string $entry = null): string
    {
        $entry ??= (string) config('artisanpack.livewire-ui-components.assets.stylesheet', 'default');
        $styles = $this->manifest()['styles'];

        if (! isset($styles[$entry])) {
            throw new RuntimeException(__('Unknown design-system stylesheet ":entry". Use one of: :entries.', [
                'entry'   => $entry,
                'entries' => implode(', ', array_keys($styles)),
            ]));
        }

        return '<link rel="stylesheet" href="'.e($this->url($styles[$entry])).'" data-navigate-track>';
    }

    /**
     * Prints the `<script>` tag for the behavior engine's Alpine adapter.
     *
     * It is a classic, render-blocking script on purpose: it has to run before
     * Livewire's script so it can register on `alpine:init`. Put it in `<head>`.
     *
     * @since 3.0.0
     */
    public function scripts(): string
    {
        return '<script src="'.e($this->url($this->manifest()['scripts']['alpine'])).'" data-navigate-track></script>';
    }

    /**
     * Where the assets are published, relative to the public directory: the
     * `assets.path` config, or `vendor/artisanpack-ui/design-system`.
     *
     * @since 3.0.0
     */
    public static function publicPath(): string
    {
        return trim((string) config('artisanpack.livewire-ui-components.assets.path', self::DEFAULT_PUBLIC_PATH), '/');
    }

    /**
     * The public URL of a file in `dist/`, with its content hash as `?id=`.
     *
     * The hash comes from the published copy of the manifest when there is
     * one, so it always describes the file the browser gets: after a package
     * update, the URL changes only once the new files are published.
     *
     * @since 3.0.0
     *
     * @param  string  $file  A path relative to `dist/`, as the manifest lists it.
     */
    public function url(string $file): string
    {
        $base = static::publicPath();
        $url  = asset($base.'/'.$file);
        $hash = $this->publishedHashes()[$file] ?? $this->manifest()['files'][$file] ?? null;

        return null === $hash ? $url : $url.'?id='.$hash;
    }

    /**
     * The stylesheet entries the manifest lists, keyed by name.
     *
     * @since 3.0.0
     *
     * @return array<string, string>
     */
    public function stylesheets(): array
    {
        return $this->manifest()['styles'];
    }

    /**
     * Reads and caches `manifest.json`.
     *
     * @since 3.0.0
     *
     * @throws RuntimeException When the manifest is missing or invalid.
     *
     * @return array{styles: array<string, string>, scripts: array<string, string>, files: array<string, string>}
     */
    public function manifest(): array
    {
        if (null !== $this->manifest) {
            return $this->manifest;
        }

        $path     = $this->distPath.DIRECTORY_SEPARATOR.'manifest.json';
        $manifest = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;

        if (! is_array($manifest) || ! isset($manifest['styles'], $manifest['scripts']['alpine'], $manifest['files'])) {
            throw new RuntimeException(__('The design-system asset manifest is missing or invalid: :path', ['path' => $path]));
        }

        return $this->manifest = $manifest;
    }

    /**
     * The file hashes from the published manifest, or none when it isn't published.
     *
     * @since 3.0.0
     *
     * @return array<string, string>
     */
    protected function publishedHashes(): array
    {
        if (null !== $this->publishedHashes) {
            return $this->publishedHashes;
        }

        $path     = public_path(static::publicPath().'/manifest.json');
        $manifest = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;

        return $this->publishedHashes = is_array($manifest['files'] ?? null) ? $manifest['files'] : [];
    }
}
