<?php

/**
 * Subheading Component
 *
 * Deprecated alias of the design-system Text with size lead (#58): a 2.x
 * subheading was a paragraph under a heading, not a heading.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-text size="lead">.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Subheading Component Class
 *
 * Renders `.apui-text` with size lead, the same markup as
 * `<x-artisanpack-text size="lead">`.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-text size="lead">.
 */
class Subheading extends Text
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $as  The element: p, span or div.
     * @param  string  $size  The Text size, lead by default.
     * @param  string  $tone  The Text tone.
     * @param  string  $weight  The Text weight.
     * @param  array<string, string>  $classes  Extra classes: `root`.
     */
    public function __construct(
        string $as = 'p',
        string $size = 'lead',
        string $tone = 'default',
        string $weight = 'regular',
        array $classes = [],
    ) {
        parent::__construct(as: $as, size: $size, tone: $tone, weight: $weight, classes: $classes);
    }
}
