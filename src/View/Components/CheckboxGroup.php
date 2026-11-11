<?php

/**
 * CheckboxGroup Component
 *
 * The design-system CheckboxGroup (#53): a labeled set of related
 * checkboxes whose value is the array of checked values.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\NormalizesChoiceOptions;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * CheckboxGroup Component Class
 *
 * Renders `fieldset.apui-checkbox-group`: the legend, an optional
 * select-all Checkbox, the options as Checkbox markup (`.apui-checkbox`),
 * the hint and the error. `class` lands on the root; every other attribute,
 * `wire:model` included, lands on every option's native checkbox, so an
 * array property binds natively.
 *
 * @since 1.0.0
 */
class CheckboxGroup extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use NormalizesChoiceOptions;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'checkbox-group';

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'checkbox-group';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'label', 'hint', 'error', 'invalid', 'selectAllLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  Strings, or [ 'value', 'label', 'description', 'disabled' ] entries.
     * @param  iterable<mixed>|null  $value  The checked values. Livewire binds them with wire:model.
     * @param  string|null  $label  The legend. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the options. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the group invalid without a message.
     * @param  bool  $disabled  Disables every option and select all.
     * @param  string  $orientation  vertical or horizontal.
     * @param  string  $variant  default or cards.
     * @param  int  $columns  1, 2 or 3 columns.
     * @param  bool  $selectAll  Adds a select-all checkbox.
     * @param  string|null  $selectAllLabel  Its label. Null uses the translated "Select all".
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public iterable $options = [],
        public ?iterable $value = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public string $orientation = 'vertical',
        public string $variant = 'default',
        public int $columns = 1,
        public bool $selectAll = false,
        public ?string $selectAllLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * Whether an option value is checked. Values compare as strings.
     *
     * @since 3.0.0
     *
     * @param  string  $optionValue  The option's value.
     *
     * @return bool True when checked.
     */
    public function isChecked(string $optionValue): bool
    {
        return in_array($optionValue, $this->checkedValues(), true);
    }

    /**
     * The select-all checkbox's state: checked when every enabled option is,
     * indeterminate when some are.
     *
     * @since 3.0.0
     *
     * @return string `unchecked`, `checked` or `indeterminate`.
     */
    public function selectAllState(): string
    {
        $enabled = array_filter($this->normalizedOptions(), static fn (array $option): bool => ! $option['disabled']);
        $checked = array_filter($enabled, fn (array $option): bool => $this->isChecked($option['value']));

        if ([] === $checked) {
            return 'unchecked';
        }

        return count($checked) === count($enabled) ? 'checked' : 'indeterminate';
    }

    /**
     * The select-all label: the prop, else the translated default.
     *
     * @since 3.0.0
     *
     * @return string The label.
     */
    public function selectAllText(): string
    {
        return null === $this->selectAllLabel || '' === $this->selectAllLabel ? __('Select all') : $this->selectAllLabel;
    }

    /**
     * The Checkbox indicator's glyphs, as the pilot Checkbox renders them.
     *
     * @since 3.0.0
     *
     * @return HtmlString The markup.
     */
    public function indicatorIcons(): HtmlString
    {
        return new HtmlString(
            IconRegistry::svg('check', ['data-icon' => 'check']).IconRegistry::svg('minus', ['data-icon' => 'minus']),
        );
    }

    /**
     * The control's id: the `id` attribute, else a base id that is stable
     * across Livewire re-renders and unique in the render (numbered when
     * the same group repeats), so repeated groups never share radio names
     * or label targets.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        return $this->baseId();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.checkbox-group');
    }

    /**
     * The checked values as strings.
     *
     * @since 3.0.0
     *
     * @return list<string> The values.
     */
    protected function checkedValues(): array
    {
        $value = $this->value instanceof Arrayable ? $this->value->toArray() : $this->value;
        if (null === $value) {
            return [];
        }

        $values = [];
        foreach ($value as $item) {
            if (is_scalar($item)) {
                $values[] = (string) $item;
            }
        }

        return $values;
    }
}
