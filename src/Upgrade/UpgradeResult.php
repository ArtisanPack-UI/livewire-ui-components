<?php

/**
 * The result of upgrading one Blade file.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * The upgraded source of one Blade file and what was found in it.
 *
 * @since 3.0.0
 */
final class UpgradeResult
{
    /**
     * Creates the result.
     *
     * @since 3.0.0
     *
     * @param  string  $original  The source as it was.
     * @param  string  $code  The upgraded source.
     * @param  array<int, Finding>  $findings  Ordered by position.
     */
    public function __construct(
        public readonly string $original,
        public readonly string $code,
        public readonly array $findings,
    ) {}

    /**
     * Whether the upgrade changed the source.
     *
     * @since 3.0.0
     */
    public function changed(): bool
    {
        return $this->original !== $this->code;
    }

    /**
     * The findings of one kind.
     *
     * @since 3.0.0
     *
     * @param  string  ...$kinds  Finding kinds.
     *
     * @return array<int, Finding>
     */
    public function findings(string ...$kinds): array
    {
        return array_values(array_filter($this->findings, fn (Finding $finding): bool => in_array($finding->kind, $kinds, true)));
    }
}
