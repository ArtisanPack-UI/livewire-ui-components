<?php

/**
 * ChoicesOffline Component
 *
 * Deprecated alias of the design-system Combobox (#54) in its multiple mode:
 * 2.x ChoicesOffline was multiple unless `single` was set, and Combobox
 * filters its options on the client by default.
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

/**
 * ChoicesOffline Component Class
 *
 * Maps the 2.x props onto Combobox: `single` is the opposite of `multiple`,
 * `icon` is `leading-icon`, `no-result-text` is `empty-label`, and
 * `hint-class` and `error-class` are the `hint` and `error` keys of
 * `classes`. Options keyed by `option-value`, `option-label` and
 * `option-sub-label` become `value`, `label` and `description` entries.
 * `searchable` is ignored, since filtering is always on, and so are the
 * Tailwind-era options; `id` stays an attribute, which Combobox uses as its
 * base id.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-combobox multiple>.
 */
class ChoicesOffline extends Combobox
{
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
     * @param  bool|null  $searchable  Ignored: filtering is always on.
     * @param  bool|null  $single  Selects one value instead of several.
     * @param  bool|null  $compact  Ignored.
     * @param  string|null  $compactText  Ignored.
     * @param  bool|null  $allowAll  Ignored.
     * @param  int|string|null  $debounce  Delay before setting search-model: `250ms`, `1s` or milliseconds.
     * @param  int|null  $minChars  Ignored.
     * @param  string|null  $allowAllText  Ignored.
     * @param  string|null  $removeAllText  Ignored.
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
     * @param  mixed  $selection  Ignored.
     * @param  mixed  $prepend  Ignored.
     * @param  mixed  $append  Ignored.
     * @param  bool  $disabled  Disables the field.
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
        mixed $selection = null,
        mixed $prepend = null,
        mixed $append = null,
        bool $disabled = false,
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
            options: Choices::legacyOptions($options, $optionValue, $optionLabel, $optionSubLabel),
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
            clearable: (bool) $clearable,
            leadingIcon: $icon,
            emptyLabel: $noResultText,
            debounce: Choices::legacyDebounce($debounce),
            errorField: $errorField,
            classes: [...Choices::legacyClasses($hintClass, $errorClass), ...$classes],
        );
    }
}
