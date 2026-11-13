<?php

/**
 * One thing the upgrade command did or found.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * A change made to a Blade file, or something in it a person has to migrate.
 *
 * @since 3.0.0
 */
final class Finding
{
    /**
     * Changed, nothing to do.
     */
    public const REWROTE = 'rewrote';

    /**
     * Changed, and worth a look.
     */
    public const REVIEW = 'review';

    /**
     * An attribute was deleted.
     */
    public const REMOVED = 'removed';

    /**
     * Left alone: a person has to migrate it.
     */
    public const MANUAL = 'manual';

    /**
     * A raw DaisyUI class or theme reference.
     */
    public const DAISY_CLASS = 'class';

    /**
     * Creates the finding.
     *
     * @since 3.0.0
     *
     * @param  string  $kind  One of the class constants.
     * @param  int  $line  The 1-based line.
     * @param  int  $column  The 1-based column.
     * @param  string  $message  What happened, or what to do.
     * @param  string|null  $component  The 2.x tag, without `x-artisanpack-`.
     * @param  string|null  $member  The attribute, slot or class.
     */
    public function __construct(
        public readonly string $kind,
        public readonly int $line,
        public readonly int $column,
        public readonly string $message,
        public readonly ?string $component = null,
        public readonly ?string $member = null,
    ) {}

    /**
     * The finding as an array, for JSON output.
     *
     * @since 3.0.0
     *
     * @return array{kind: string, line: int, column: int, message: string, component: string|null, member: string|null}
     */
    public function toArray(): array
    {
        return [
            'kind'      => $this->kind,
            'line'      => $this->line,
            'column'    => $this->column,
            'message'   => $this->message,
            'component' => $this->component,
            'member'    => $this->member,
        ];
    }
}
