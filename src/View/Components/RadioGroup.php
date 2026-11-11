<?php

/**
 * RadioGroup Component
 *
 * The design-system RadioGroup (#53): one option from a small set, with
 * native radios in a fieldset. Replaces `radio`, `radio-group` and `group`.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\NormalizesChoiceOptions;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * RadioGroup Component Class
 *
 * Renders `fieldset.apui-radio-group`: the legend, the items (each a label
 * holding the native radio, its dot, text, description and meta), the hint
 * and the error. `class` lands on the root; every other attribute,
 * `wire:model` included, lands on every radio.
 *
 * @since 1.0.0
 */
class RadioGroup extends BaseComponent
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
    protected string $fieldName = 'radio-group';

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'radio-group';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'label', 'hint', 'error', 'invalid'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  iterable<mixed>  $options  Strings, or [ 'value', 'label', 'description', 'meta', 'disabled' ] entries.
     * @param  string|null  $value  The selected value. Livewire binds it with wire:model.
     * @param  string|null  $label  The legend. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the options. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the group invalid without a message.
     * @param  bool  $disabled  Disables every option.
     * @param  bool  $required  Marks the group required.
     * @param  string  $orientation  vertical or horizontal.
     * @param  string  $variant  default, cards or segmented.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public iterable $options = [],
        public ?string $value = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public string $orientation = 'vertical',
        public string $variant = 'default',
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The radios' shared name: the `name` attribute, else the group id, so
     * arrow keys move between the options.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function groupName(): string
    {
        $name = $this->attributes->get('name');

        return is_string($name) && '' !== $name ? $name : $this->controlId();
    }

    /**
     * Whether an option value is the selected one. Values compare as strings.
     *
     * @since 3.0.0
     *
     * @param  string  $optionValue  The option's value.
     *
     * @return bool True when selected.
     */
    public function isSelected(string $optionValue): bool
    {
        return null !== $this->value && (string) $this->value === $optionValue;
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
        return view('livewire-ui-components::components.radio-group');
    }
}
