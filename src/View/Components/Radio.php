<?php

/**
 * Radio Component
 *
 * The 2.x `radio`, now a deprecated alias of the design-system RadioGroup
 * (#145).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-radio-group>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * Radio Class
 *
 * Renders the design-system RadioGroup from the 2.x `radio` API: the
 * `option-value`, `option-label` and `option-hint` keys of each option become
 * its `value`, `label` and `description`; `inline` is
 * `orientation="horizontal"`; `card` is `variant="cards"`; `hint-class`,
 * `error-class` and `card-class` become the `hint`, `error` and `item` keys of
 * `classes`. `omit-error` and `first-error-only` are accepted and ignored: the
 * group always shows its first error. An `id` attribute is the group's id.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-radio-group>.
 */
class Radio extends RadioGroup
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
     * @param  string|null  $optionHint  The option key holding the description.
     * @param  array<int|string, mixed>|Collection<int|string, mixed>  $options  The options.
     * @param  bool|null  $inline  Lays the options out horizontally.
     * @param  bool|null  $card  Renders the options as cards.
     * @param  string|null  $cardClass  Classes for each option card.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  string|null  $errorClass  Classes for the error.
     * @param  bool|null  $omitError  Ignored.
     * @param  bool|null  $firstErrorOnly  Ignored: only the first error shows.
     * @param  string|null  $value  The selected value.
     * @param  string|null  $error  Validation message.
     * @param  bool  $invalid  Marks the group invalid without a message.
     * @param  bool  $disabled  Disables every option.
     * @param  bool  $required  Marks the group required.
     * @param  string|null  $orientation  vertical or horizontal. Overrides `inline`.
     * @param  string|null  $variant  default, cards or segmented. Overrides `card`.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        ?string $label = null,
        ?string $hint = null,
        ?string $hintClass = null,
        ?string $optionValue = 'id',
        ?string $optionLabel = 'name',
        ?string $optionHint = 'hint',
        Collection|array $options = [],
        ?bool $inline = false,
        ?bool $card = false,
        ?string $cardClass = null,
        ?string $errorField = null,
        ?string $errorClass = null,
        ?bool $omitError = false,
        ?bool $firstErrorOnly = false,
        ?string $value = null,
        ?string $error = null,
        bool $invalid = false,
        bool $disabled = false,
        bool $required = false,
        ?string $orientation = null,
        ?string $variant = null,
        array $classes = [],
    ) {
        parent::__construct(
            options: self::legacyOptions($options, $optionValue, $optionLabel, $optionHint),
            value: $value,
            label: $label,
            hint: $hint,
            error: $error,
            invalid: $invalid,
            disabled: $disabled,
            required: $required,
            orientation: $orientation ?? ($inline ? 'horizontal' : 'vertical'),
            variant: $variant ?? ($card ? 'cards' : 'default'),
            errorField: $errorField,
            classes: $classes + array_filter(
                ['hint' => $hintClass, 'error' => $errorClass, 'item' => $cardClass],
                static fn (?string $class): bool => null !== $class && '' !== $class,
            ),
        );
    }

    /**
     * Maps 2.x options, read through the `option-*` keys, onto RadioGroup's
     * `value`, `label`, `description` and `disabled` entries. Entries already
     * in that shape, and plain strings, pass through.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  The 2.x options.
     * @param  string|null  $optionValue  The option key holding the value.
     * @param  string|null  $optionLabel  The option key holding the label.
     * @param  string|null  $optionHint  The option key holding the description.
     *
     * @return list<mixed> The options.
     */
    public static function legacyOptions(iterable $options, ?string $optionValue, ?string $optionLabel, ?string $optionHint): array
    {
        $mapped = [];
        foreach ($options as $option) {
            if (! is_array($option) && ! is_object($option)) {
                $mapped[] = $option;

                continue;
            }

            $value    = self::legacyField($option, $optionValue ?? 'id') ?? self::legacyField($option, 'value');
            $mapped[] = [
                'value'       => $value,
                'label'       => self::legacyField($option, $optionLabel ?? 'name') ?? self::legacyField($option, 'label') ?? $value,
                'description' => self::legacyField($option, $optionHint ?? 'hint') ?? self::legacyField($option, 'description'),
                'meta'        => self::legacyField($option, 'meta'),
                'disabled'    => (bool) (self::legacyField($option, 'disabled') ?? false),
            ];
        }

        return $mapped;
    }

    /**
     * Reads a 2.x option field. A model is read directly rather than through
     * `toArray()`, which would drop `$hidden` attributes (often the id) and
     * accessors that aren't appended; other Arrayable objects fall back to
     * their array form.
     *
     * @since 3.0.0
     *
     * @param  array<array-key, mixed>|object  $option  The option.
     * @param  string  $key  The field, in dot notation.
     *
     * @return mixed The field's value, or null.
     */
    public static function legacyField(array|object $option, string $key): mixed
    {
        $value = data_get($option, $key);

        if (null === $value && $option instanceof Arrayable && ! $option instanceof \Illuminate\Database\Eloquent\Model) {
            $value = data_get($option->toArray(), $key);
        }

        return $value;
    }
}
