<?php

/**
 * Tags Component
 *
 * Deprecated alias of the design-system Combobox (#54) in its multiple and
 * allow-create modes: a 2.x tag input picked several values and created new
 * ones from typed text.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-combobox multiple allow-create>.
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\CallsLegacySearchFunction;

/**
 * Tags Component Class
 *
 * Maps the 2.x props onto Combobox: it's `multiple` unless `single` is set,
 * `allow-custom-tags` is `allow-create`, `custom-tags-text` is
 * `create-label`, `icon` is `leading-icon`, `no-result-text` is
 * `empty-label`, and `hint-class` and `error-class` are the `hint` and
 * `error` keys of `classes`. Options keyed by `option-value`,
 * `option-label` and `option-sub-label` become `value`, `label` and
 * `description` entries, whose value is the label: 2.x bound the tags'
 * labels, not their ids. `searchable` searches on the server, calling
 * `search-function` with the typed text after `min-chars`. The
 * Tailwind-era options are ignored; `id` stays an attribute, which Combobox
 * uses as its base id.
 *
 * @since      1.0.0
 * @deprecated 3.0.0 Use <x-artisanpack-combobox multiple allow-create>.
 */
class Tags extends Combobox
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
     * @param  bool|null  $searchable  The server filters the options (`remote`), calling `search-function`.
     * @param  int|string|null  $debounce  Delay before setting search-model: `250ms`, `1s` or milliseconds.
     * @param  int|null  $minChars  Ignored.
     * @param  string|null  $searchFunction  Ignored.
     * @param  string|null  $optionValue  The options' value key.
     * @param  string|null  $optionLabel  The options' label key.
     * @param  string|null  $optionSubLabel  The options' description key.
     * @param  string|null  $optionAvatar  Ignored.
     * @param  string|null  $height  Ignored.
     * @param  iterable<mixed>  $options  The options.
     * @param  string|null  $noResultText  The empty text (`empty-label`).
     * @param  bool|null  $allowCustomTags  Offers "Create …" for typed text (`allow-create`).
     * @param  string|null  $customTagsText  The create option's text (`create-label`).
     * @param  mixed  $prepend  Ignored.
     * @param  mixed  $append  Ignored.
     * @param  string|null  $errorField  The `$errors` key.
     * @param  string|null  $errorClass  The `error` key of `classes`.
     * @param  bool|null  $omitError  Ignored.
     * @param  bool|null  $firstErrorOnly  Ignored.
     * @param  bool|null  $single  Selects one value instead of several.
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
        string|int|null $debounce = '250ms',
        ?int $minChars = 0,
        ?string $searchFunction = 'search',
        ?string $optionValue = 'id',
        ?string $optionLabel = 'name',
        ?string $optionSubLabel = '',
        ?string $optionAvatar = 'avatar',
        ?string $height = null,
        iterable $options = [],
        ?string $noResultText = null,
        ?bool $allowCustomTags = true,
        ?string $customTagsText = null,
        mixed $prepend = null,
        mixed $append = null,
        ?string $errorField = null,
        ?string $errorClass = null,
        ?bool $omitError = false,
        ?bool $firstErrorOnly = false,
        ?bool $single = false,
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
            options: static::legacyTagOptions($options, $optionValue, $optionLabel, $optionSubLabel),
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
            allowCreate: (bool) $allowCustomTags,
            clearable: (bool) $clearable,
            leadingIcon: $icon,
            emptyLabel: $noResultText,
            createLabel: $customTagsText,
            debounce: Choices::legacyDebounce($debounce),
            errorField: $errorField,
            classes: [...Choices::legacyClasses($hintClass, $errorClass), ...$classes],
        );

        $this->useLegacySearchFunction($searchable, $searchFunction, $minChars);
    }

    /**
     * Maps 2.x tag options like Choices does, but with each option's label as
     * its value: 2.x appended the label to the bound array, so a string-tag
     * field keeps receiving `'Bug'`, not the option's id.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  The 2.x options.
     * @param  string|null  $optionValue  The option key holding the 2.x value.
     * @param  string|null  $optionLabel  The option key holding the label.
     * @param  string|null  $optionSubLabel  The option key holding the description.
     *
     * @return list<mixed> The Combobox options.
     */
    public static function legacyTagOptions(iterable $options, ?string $optionValue = 'id', ?string $optionLabel = 'name', ?string $optionSubLabel = ''): array
    {
        return array_map(
            static fn (mixed $option): mixed => is_array($option) && isset($option['label']) ? [...$option, 'value' => $option['label']] : $option,
            Choices::legacyOptions($options, $optionValue, $optionLabel, $optionSubLabel),
        );
    }
}
