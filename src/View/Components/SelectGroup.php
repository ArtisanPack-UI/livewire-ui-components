<?php

/**
 * SelectGroup Component
 *
 * The 2.x `select-group`, now a deprecated alias of the design-system Select
 * with option groups (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-select> with `{ label, options }` groups.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * SelectGroup Class
 *
 * Renders the design-system Select from the 2.x `select-group` API: the 2.x
 * `[ 'Group label' => [ options ] ]` map becomes Select's
 * `[ 'label' => …, 'options' => [ [ 'value' => …, 'label' => … ] ] ]` groups,
 * read through `option-value` and `option-label`. `icon` is `leading-icon`;
 * `hint-class` and `error-class` become the `hint` and `error` keys of
 * `classes`. Without a `placeholder` there is no empty option, as in 2.x.
 * `icon-right`, `placeholder-value`, `inline`, `prepend`, `append`,
 * `omit-error` and `first-error-only` are accepted and ignored. An `id`
 * attribute is the select's id.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-select> with `{ label, options }` groups.
 */
class SelectGroup extends Select
{
    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $label  The visible label.
     * @param  string|null  $icon  Icon name before the value.
     * @param  string|null  $iconRight  Ignored: the indicator sits at the end.
     * @param  string|null  $hint  Help text under the control.
     * @param  string|null  $hintClass  Classes for the hint.
     * @param  string|null  $placeholder  The empty first option. None when null, as in 2.x.
     * @param  string|null  $placeholderValue  Ignored: the placeholder's value is always empty.
     * @param  bool|null  $inline  Ignored: the label always sits above the field.
     * @param  string|null  $optionValue  The option key holding the value.
     * @param  string|null  $optionLabel  The option key holding the label.
     * @param  array<int|string, mixed>|Collection<int|string, mixed>  $options  Options keyed by group label.
     * @param  mixed  $prepend  Ignored.
     * @param  mixed  $append  Ignored.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  string|null  $errorClass  Classes for the error.
     * @param  bool|null  $omitError  Ignored.
     * @param  bool|null  $firstErrorOnly  Ignored: only the first error shows.
     * @param  string  $size  Control height: sm, md or lg.
     * @param  string|null  $value  The selected value.
     * @param  string|null  $error  Validation message.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $required  Marks the field required.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $label = null,
        ?string $icon = null,
        ?string $iconRight = null,
        ?string $hint = null,
        ?string $hintClass = null,
        ?string $placeholder = null,
        ?string $placeholderValue = null,
        ?bool $inline = false,
        ?string $optionValue = 'id',
        ?string $optionLabel = 'name',
        Collection|array $options = [],
        mixed $prepend = null,
        mixed $append = null,
        ?string $errorField = null,
        ?string $errorClass = null,
        ?bool $omitError = false,
        ?bool $firstErrorOnly = false,
        string $size = 'md',
        ?string $value = null,
        ?string $error = null,
        bool $invalid = false,
        bool $disabled = false,
        bool $required = false,
        array $classes = [],
    ) {
        parent::__construct(
            size: $size,
            options: self::legacyGroups($options, $optionValue ?? 'id', $optionLabel ?? 'name'),
            label: $label,
            hint: $hint,
            error: $error,
            invalid: $invalid,
            disabled: $disabled,
            required: $required,
            placeholder: $placeholder ?? '',
            value: $value,
            leadingIcon: $icon,
            errorField: $errorField,
            classes: $classes + array_filter(
                ['hint' => $hintClass, 'error' => $errorClass],
                static fn (?string $class): bool => null !== $class && '' !== $class,
            ),
        );
    }

    /**
     * Maps the 2.x `[ 'Group label' => [ options ] ]` map onto Select's
     * `{ label, options }` groups. Entries already in that shape keep their
     * label; a string-keyed scalar or a list entry is an ungrouped option.
     *
     * @since 3.0.0
     *
     * @param  iterable<int|string, mixed>  $groups  The 2.x options.
     * @param  string  $optionValue  The option key holding the value.
     * @param  string  $optionLabel  The option key holding the label.
     *
     * @return list<array<string, mixed>|string> The options and groups.
     */
    private static function legacyGroups(iterable $groups, string $optionValue, string $optionLabel): array
    {
        $mapped = [];
        foreach ($groups as $key => $group) {
            if ($group instanceof Arrayable) {
                $group = $group->toArray();
            }

            if (is_array($group) && isset($group['options']) && is_iterable($group['options'])) {
                $mapped[] = [
                    'label'   => (string) ($group['label'] ?? $key),
                    'options' => self::legacyOptions($group['options'], $optionValue, $optionLabel),
                ];

                continue;
            }

            if (is_string($key) && is_iterable($group)) {
                $mapped[] = ['label' => $key, 'options' => self::legacyOptions($group, $optionValue, $optionLabel)];

                continue;
            }

            $mapped = [...$mapped, ...self::legacyOptions([$group], $optionValue, $optionLabel)];
        }

        return $mapped;
    }

    /**
     * Maps 2.x options, read through the `option-*` keys, onto Select's
     * `value`, `label` and `disabled` entries. Plain strings pass through.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  The 2.x options.
     * @param  string  $optionValue  The option key holding the value.
     * @param  string  $optionLabel  The option key holding the label.
     *
     * @return list<array{value: mixed, label: mixed, disabled: bool}|string> The options.
     */
    private static function legacyOptions(iterable $options, string $optionValue, string $optionLabel): array
    {
        $mapped = [];
        foreach ($options as $option) {
            if ($option instanceof Arrayable) {
                $option = $option->toArray();
            }

            if (! is_array($option) && ! is_object($option)) {
                $mapped[] = (string) $option;

                continue;
            }

            $value    = data_get($option, $optionValue) ?? data_get($option, 'value');
            $mapped[] = [
                'value'    => $value,
                'label'    => data_get($option, $optionLabel) ?? data_get($option, 'label') ?? $value,
                'disabled' => (bool) data_get($option, 'disabled', false),
            ];
        }

        return $mapped;
    }
}
