<?php

/**
 * Select Component
 *
 * The design-system Select (#46): one value from a short list, with the
 * native select element.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;

/**
 * Select Component Class
 *
 * Renders `.apui-select`: the label, the box (icon, native select,
 * indicator), the hint and the error. `class` lands on the root; every other
 * attribute, `wire:model` included, lands on the native select.
 *
 * @since 1.0.0
 */
class Select extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'select';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $size  Control height: sm, md or lg.
     * @param  iterable<mixed>  $options  Strings, or [ 'value' => …, 'label' => …, 'disabled' => … ] entries.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the control. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $required  Marks the field required; the placeholder can't be chosen.
     * @param  string|null  $placeholder  The empty first option. Null uses the translated "Select…"; an empty string drops it.
     * @param  string|null  $value  The selected value. Livewire binds it with wire:model.
     * @param  string|null  $leadingIcon  Icon name before the value. The `icon` slot overrides it.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $size = 'md',
        public iterable $options = [],
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $leadingIcon = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The options as `value`, `label` and `disabled`, with string values.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string, disabled: bool}> The options.
     */
    public function normalizedOptions(): array
    {
        $options = [];
        foreach ($this->options as $option) {
            if ($option instanceof Arrayable) {
                $option = $option->toArray();
            } elseif (is_object($option)) {
                $option = get_object_vars($option);
            }

            if (is_array($option)) {
                $value     = (string) ($option['value'] ?? '');
                $options[] = [
                    'value'    => $value,
                    'label'    => (string) ($option['label'] ?? $value),
                    'disabled' => (bool) ($option['disabled'] ?? false),
                ];

                continue;
            }

            $options[] = ['value' => (string) $option, 'label' => (string) $option, 'disabled' => false];
        }

        return $options;
    }

    /**
     * The placeholder option's text, or null when there is none.
     *
     * @since 3.0.0
     *
     * @return string|null The text.
     */
    public function placeholderText(): ?string
    {
        if (null === $this->placeholder) {
            return __('Select…');
        }

        return '' === $this->placeholder ? null : $this->placeholder;
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
     * Whether the placeholder option is selected: the value is empty. A
     * required (disabled) placeholder then still shows, instead of the
     * browser jumping to the first enabled option, as in React.
     *
     * @since 3.0.0
     *
     * @return bool True when the value is null or empty.
     */
    public function placeholderSelected(): bool
    {
        return null === $this->value || '' === $this->value;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.select');
    }
}
