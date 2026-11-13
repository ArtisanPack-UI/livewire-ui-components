<?php

/**
 * Errors Component
 *
 * The 2.x `errors`, now a deprecated alias of the design-system
 * ValidationSummary (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-validation-summary>. Removed in a later major release.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Errors Class
 *
 * Renders `.apui-validation-summary`, the same markup as
 * `<x-artisanpack-validation-summary>`: `title` and `only` carry over.
 * `description` and `icon` are accepted and ignored: the summary shows only a
 * title and the errors, and its icon follows `color`. An `id` attribute lands
 * on the root.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-validation-summary>. Removed in a later major release.
 */
class Errors extends ValidationSummary
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The heading. Defaults to a translated count.
     * @param  string|null  $description  Ignored.
     * @param  string|null  $icon  Ignored: the icon follows `color`.
     * @param  list<string>|null  $only  The fields to list, in order. Defaults to every one.
     * @param  string  $color  error or warning.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $icon = 'o-x-circle',
        ?array $only = [],
        string $color = 'error',
        array $classes = [],
    ) {
        parent::__construct(
            only: array_values($only ?? []),
            color: $color,
            title: $title,
            classes: $classes,
        );
    }
}
