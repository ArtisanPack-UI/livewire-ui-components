<?php

/**
 * Choices Component
 *
 * Deprecated alias of the design-system Combobox (#54) in its multiple mode:
 * 2.x Choices was multiple unless `single` was set.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-combobox multiple>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\CallsLegacySearchFunction;

/**
 * Choices Component Class
 *
 * Maps the 2.x props onto Combobox: `single` is the opposite of `multiple`,
 * `searchable` is `remote` (the server search still calls `search-function`
 * with the typed text, after `debounce` and `min-chars`), `icon` is
 * `leading-icon`, `no-result-text` is `empty-label`, and `hint-class` and
 * `error-class` are the `hint` and `error` keys of `classes`. Options keyed
 * by `option-value`, `option-label` and `option-sub-label` become `value`,
 * `label` and `description` entries. The Tailwind-era options are accepted
 * and ignored; `id` stays an attribute, which Combobox uses as its base id.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-combobox multiple>.
 */
class Choices extends Combobox
{
    use CallsLegacySearchFunction;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $label  The visible label.
     * @param  string|null  $hint  Help text under the control.
     * @param  string|null  $hintClass  The `hint` key of `classes`.
     * @param  string|null  $icon  The leading icon.
     * @param  string|null  $iconRight  Ignored.
     * @param  bool|null  $inline  Ignored.
     * @param  bool|null  $clearable  Adds a button that clears the value.
     * @param  string|null  $prefix  Ignored.
     * @param  string|null  $suffix  Ignored.
     * @param  bool|null  $searchable  The server filters the options (`remote`).
     * @param  bool|null  $single  Selects one value instead of several.
     * @param  bool|null  $compact  Ignored.
     * @param  string|null  $compactText  Ignored.
     * @param  bool|null  $allowAll  Ignored.
     * @param  int|string|null  $debounce  Delay before searching: `250ms`, `1s` or milliseconds.
     * @param  int|null  $minChars  The fewest characters that call search-function.
     * @param  string|null  $allowAllText  Ignored.
     * @param  string|null  $removeAllText  Ignored.
     * @param  string|null  $searchFunction  The Livewire method called with the typed text, when searchable.
     * @param  string|null  $optionValue  The options' value key.
     * @param  string|null  $optionLabel  The options' label key.
     * @param  string|null  $optionSubLabel  The options' description key.
     * @param  string|null  $optionAvatar  Ignored.
     * @param  bool|null  $valuesAsString  Ignored.
     * @param  string|null  $height  Ignored.
     * @param  iterable<mixed>  $options  The options.
     * @param  string|null  $noResultText  The empty text (`empty-label`).
     * @param  string|null  $errorField  The `$errors` key.
     * @param  string|null  $errorClass  The `error` key of `classes`.
     * @param  bool|null  $omitError  Ignored.
     * @param  bool|null  $firstErrorOnly  Ignored.
     * @param  bool  $lazyLoad  Ignored.
     * @param  string  $lazyLoadMethod  Ignored.
     * @param  string|null  $lazyLoadModifier  Ignored.
     * @param  bool  $hasMoreOptions  Ignored.
     * @param  mixed  $selection  Ignored.
     * @param  mixed  $prepend  Ignored.
     * @param  mixed  $append  Ignored.
     * @param  bool  $disabled  Disables the field.
     * @param  bool|null  $readonly  Ignored for now: Combobox has no read-only state yet (ArtisanPack-UI/design-system#120). Not mapped to `disabled`, which would change its meaning.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Shown in the empty input.
     * @param  string  $size  Combobox: control height, sm, md or lg.
     * @param  string|null  $error  Combobox: the validation message.
     * @param  bool  $invalid  Combobox: marks the field invalid without a message.
     * @param  string|null  $name  Combobox: submits the value with a native form.
     * @param  array<int, mixed>|string|null  $value  Combobox: the value, when there's no wire:model.
     * @param  int|null  $max  Combobox: the most values that can be chosen.
     * @param  string|null  $search  Combobox: the text in the input when it renders.
     * @param  bool  $loading  Combobox: shows the spinner and the loading text.
     * @param  bool  $open  Combobox: whether the listbox is open when it renders.
     * @param  bool  $portal  Combobox: renders the listbox at the end of the body.
     * @param  string|null  $searchModel  Combobox: the Livewire property the search text is set on.
     * @param  array<string, string>  $classes  Combobox: extra classes per part, over hint-class and error-class.
     */
    public function __construct(
        ?string $label = null,
        ?string $hint = null,
        ?string $hintClass = null,
        ?string $icon = null,
        ?string $iconRight = null,
        ?bool $inline = false,
        ?bool $clearable = false,
        ?string $prefix = null,
        ?string $suffix = null,
        ?bool $searchable = false,
        ?bool $single = false,
        ?bool $compact = false,
        ?string $compactText = null,
        ?bool $allowAll = false,
        string|int|null $debounce = '250ms',
        ?int $minChars = 0,
        ?string $allowAllText = null,
        ?string $removeAllText = null,
        ?string $searchFunction = 'search',
        ?string $optionValue = 'id',
        ?string $optionLabel = 'name',
        ?string $optionSubLabel = '',
        ?string $optionAvatar = 'avatar',
        ?bool $valuesAsString = false,
        ?string $height = null,
        iterable $options = [],
        ?string $noResultText = null,
        ?string $errorField = null,
        ?string $errorClass = null,
        ?bool $omitError = false,
        ?bool $firstErrorOnly = false,
        bool $lazyLoad = false,
        string $lazyLoadMethod = 'loadMoreOptions',
        ?string $lazyLoadModifier = null,
        bool $hasMoreOptions = true,
        mixed $selection = null,
        mixed $prepend = null,
        mixed $append = null,
        bool $disabled = false,
        ?bool $readonly = false,
        bool $required = false,
        ?string $placeholder = null,
        string $size = 'md',
        ?string $error = null,
        bool $invalid = false,
        ?string $name = null,
        string|array|null $value = null,
        ?int $max = null,
        ?string $search = null,
        bool $loading = false,
        bool $open = false,
        bool $portal = true,
        ?string $searchModel = null,
        array $classes = [],
    ) {
        parent::__construct(
            options: static::legacyOptions($options, $optionValue, $optionLabel, $optionSubLabel),
            label: $label,
            hint: $hint,
            disabled: $disabled,
            required: $required,
            placeholder: $placeholder,
            size: $size,
            error: $error,
            invalid: $invalid,
            name: $name,
            value: $value,
            max: $max,
            search: $search,
            loading: $loading,
            open: $open,
            portal: $portal,
            searchModel: $searchModel,
            multiple: ! $single,
            remote: (bool) $searchable,
            clearable: (bool) $clearable,
            leadingIcon: $icon,
            emptyLabel: $noResultText,
            debounce: static::legacyDebounce($debounce),
            errorField: $errorField,
            classes: [...static::legacyClasses($hintClass, $errorClass), ...$classes],
        );

        $this->useLegacySearchFunction($searchable, $searchFunction, $minChars);
    }

    /**
     * Turns 2.x options, keyed by option-value, option-label and
     * option-sub-label, into Combobox `value`, `label` and `description`
     * entries. Entries that already have `value` and `label` keep them, and
     * scalars pass through.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  The 2.x options.
     * @param  string|null  $optionValue  The value key.
     * @param  string|null  $optionLabel  The label key.
     * @param  string|null  $optionSubLabel  The description key, or '' for none.
     *
     * @return list<mixed> The Combobox options.
     */
    public static function legacyOptions(iterable $options, ?string $optionValue = 'id', ?string $optionLabel = 'name', ?string $optionSubLabel = ''): array
    {
        $mapped = [];
        foreach ($options as $option) {
            if (is_scalar($option) || null === $option) {
                $mapped[] = $option;

                continue;
            }

            // Read through Radio::legacyField(), not toArray(): a model's
            // hidden id and unappended accessors don't survive toArray().
            $value = Radio::legacyField($option, $optionValue ?? 'id') ?? Radio::legacyField($option, 'value');
            $label = Radio::legacyField($option, $optionLabel ?? 'name') ?? Radio::legacyField($option, 'label') ?? $value;
            $sub   = null !== $optionSubLabel && '' !== $optionSubLabel ? Radio::legacyField($option, $optionSubLabel) : null;

            $mapped[] = array_filter([
                'value'       => $value,
                'label'       => null !== $label ? (string) $label : null,
                'description' => $sub ?? Radio::legacyField($option, 'description'),
                'icon'        => Radio::legacyField($option, 'icon'),
                'color'       => Radio::legacyField($option, 'color'),
                'disabled'    => (bool) (Radio::legacyField($option, 'disabled') ?? false),
            ], static fn (mixed $entry): bool => null !== $entry);
        }

        return $mapped;
    }

    /**
     * A 2.x debounce (`250ms`, `1s`, or a number of milliseconds) as
     * integer milliseconds.
     *
     * @since 3.0.0
     *
     * @param  int|string|null  $debounce  The 2.x debounce.
     *
     * @return int The milliseconds, 250 when it can't be read.
     */
    public static function legacyDebounce(string|int|null $debounce): int
    {
        if (is_int($debounce)) {
            return max(0, $debounce);
        }

        if (null === $debounce || 1 !== preg_match('/^\s*(\d+(?:\.\d+)?)\s*(ms|s)?\s*$/i', $debounce, $matches)) {
            return 250;
        }

        $amount = (float) $matches[1];

        return (int) round('s' === strtolower($matches[2] ?? '') ? $amount * 1000 : $amount);
    }

    /**
     * The `classes` from the 2.x `hint-class` and `error-class`.
     *
     * @since 3.0.0
     *
     * @param  string|null  $hintClass  The hint's classes.
     * @param  string|null  $errorClass  The error's classes.
     *
     * @return array<string, string> The classes.
     */
    public static function legacyClasses(?string $hintClass, ?string $errorClass): array
    {
        return array_filter(
            ['hint' => $hintClass, 'error' => $errorClass],
            static fn (?string $class): bool => null !== $class && '' !== trim($class),
        );
    }
}
