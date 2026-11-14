<?php

/**
 * Group Component
 *
 * The 2.x `group`, now a deprecated alias of the design-system RadioGroup
 * with variant segmented (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-radio-group variant="segmented">.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Support\Collection;

/**
 * Group Class
 *
 * Renders `.apui-radio-group` with variant segmented, laid out horizontally
 * like the 2.x joined buttons: the same markup as `<x-artisanpack-radio-group
 * variant="segmented" orientation="horizontal">`. The `option-value` and
 * `option-label` keys of each option become its `value` and `label`;
 * `hint-class` and `error-class` become the `hint` and `error` keys of
 * `classes`. `omit-error` and `first-error-only` are accepted and ignored: the
 * group always shows its first error. An `id` attribute is the group's id.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-radio-group variant="segmented">.
 */
class Group extends RadioGroup
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $label  The legend.
     * @param  string|null  $hint  Help text under the options.
     * @param  string|null  $hintClass  Classes for the hint.
     * @param  string|null  $optionValue  The option key holding the value.
     * @param  string|null  $optionLabel  The option key holding the label.
     * @param  array<int|string, mixed>|Collection<int|string, mixed>  $options  The options.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  string|null  $errorClass  Classes for the error.
     * @param  bool|null  $omitError  Ignored.
     * @param  bool|null  $firstErrorOnly  Ignored: only the first error shows.
     * @param  string|null  $value  The selected value.
     * @param  string|null  $error  Validation message.
     * @param  bool  $invalid  Marks the group invalid without a message.
     * @param  bool  $disabled  Disables every option.
     * @param  bool  $required  Marks the group required.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $label = null,
        ?string $hint = null,
        ?string $hintClass = null,
        ?string $optionValue = 'id',
        ?string $optionLabel = 'name',
        Collection|array $options = [],
        ?string $errorField = null,
        ?string $errorClass = null,
        ?bool $omitError = false,
        ?bool $firstErrorOnly = false,
        ?string $value = null,
        ?string $error = null,
        bool $invalid = false,
        bool $disabled = false,
        bool $required = false,
        array $classes = [],
    ) {
        parent::__construct(
            options: Radio::legacyOptions($options, $optionValue, $optionLabel, null),
            value: $value,
            label: $label,
            hint: $hint,
            error: $error,
            invalid: $invalid,
            disabled: $disabled,
            required: $required,
            orientation: 'horizontal',
            variant: 'segmented',
            errorField: $errorField,
            classes: $classes + array_filter(
                ['hint' => $hintClass, 'error' => $errorClass],
                static fn (?string $class): bool => null !== $class && '' !== $class,
            ),
        );
    }
}
