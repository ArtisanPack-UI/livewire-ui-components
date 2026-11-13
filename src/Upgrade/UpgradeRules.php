<?php

/**
 * The 2.x to 3.0 upgrade rules.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

use RuntimeException;

/**
 * Reads `resources/design-system/upgrade-rules.php`: what every 2.x Blade
 * component, attribute and slot becomes in 3.0, and the DaisyUI classes to
 * report. The design system generates the file from its component manifests
 * (`@artisanpack-ui/codemods`); `scripts/build-assets.mjs` copies it here.
 *
 * @since 3.0.0
 */
final class UpgradeRules
{
    /**
     * The prefix of the library's Blade tags.
     */
    public const PREFIX = 'artisanpack-';

    /**
     * Creates the rules from their data.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $data  The contents of the rules file.
     */
    public function __construct(private readonly array $data) {}

    /**
     * Loads the rules the package ships, or another rules file.
     *
     * @since 3.0.0
     *
     * @param  string|null  $path  A rules file; the shipped one by default.
     *
     * @throws RuntimeException When the file is missing or isn't a rules file.
     */
    public static function load(?string $path = null): self
    {
        $path ??= dirname(__DIR__, 2).'/resources/design-system/upgrade-rules.php';

        if (! is_file($path)) {
            throw new RuntimeException("The upgrade rules are missing: {$path}");
        }

        $data = require $path;

        if (! is_array($data) || ! is_array($data['components'] ?? null)) {
            throw new RuntimeException("Not an upgrade rules file: {$path}");
        }

        return new self($data);
    }

    /**
     * The rules for one 2.x component, or null when the tag isn't the library's.
     *
     * Tags that packages register under the same prefix, such as
     * `x-artisanpack-ec-*`, have no rules, so the upgrade leaves them alone.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The tag without `x-artisanpack-`, such as `modal`.
     *
     * @return array<string, mixed>|null
     */
    public function component(string $name): ?array
    {
        return $this->data['components'][$name] ?? null;
    }

    /**
     * Every 2.x component with rules.
     *
     * @since 3.0.0
     *
     * @return array<string, array<string, mixed>>
     */
    public function components(): array
    {
        return $this->data['components'];
    }

    /**
     * The DaisyUI class lists and patterns.
     *
     * @since 3.0.0
     *
     * @return array<string, mixed>
     */
    public function classes(): array
    {
        return $this->data['classes'] ?? [];
    }

    /**
     * The versions the rules migrate between.
     *
     * @since 3.0.0
     *
     * @return array{from: string, to: string}
     */
    public function versions(): array
    {
        return ['from' => (string) ($this->data['from'] ?? '2.x'), 'to' => (string) ($this->data['to'] ?? '3.0')];
    }

    /**
     * `leadingIcon` as Blade writes it: `leading-icon`.
     *
     * @since 3.0.0
     */
    public static function kebab(string $name): string
    {
        return strtolower((string) preg_replace('/([a-z0-9])([A-Z])/', '$1-$2', $name));
    }

    /**
     * `leading-icon` as the rules key it: `leadingIcon`.
     *
     * @since 3.0.0
     */
    public static function camel(string $name): string
    {
        return (string) preg_replace_callback('/-([a-z0-9])/', fn (array $match): string => strtoupper($match[1]), $name);
    }
}
