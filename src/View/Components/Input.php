<?php

/**
 * Input Component
 *
 * The design-system Input (#45): single-line text entry with a label, hint
 * and error.
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
use Illuminate\Contracts\View\View;

/**
 * Input Component Class
 *
 * Renders `.apui-input`: the label, the box (icon, prefix, control, suffix,
 * iconEnd), the hint and the error. `class` lands on the root; every other
 * attribute, `wire:model` included, lands on the native input.
 *
 * @since 1.0.0
 */
class Input extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'input';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $size  Control height: sm, md or lg.
     * @param  string  $type  The native input type.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the control. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $readonly  Makes the control read-only.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Placeholder text.
     * @param  string|null  $value  The value. Livewire binds it with wire:model.
     * @param  string|null  $prefix  Text before the value. A `prefix` slot overrides it.
     * @param  string|null  $suffix  Text after the value. A `suffix` slot overrides it.
     * @param  string|null  $leadingIcon  Icon name before the value. The `icon` slot overrides it.
     * @param  string|null  $trailingIcon  Icon name after the value. The `iconEnd` slot overrides it.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $size = 'md',
        public string $type = 'text',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?string $leadingIcon = null,
        public ?string $trailingIcon = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.input');
    }
}
