<?php

/**
 * Toggle Component
 *
 * The design-system Toggle (#53): a switch for settings that take effect
 * immediately.
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
 * Toggle Component Class
 *
 * Renders `.apui-toggle`: the track (native checkbox with role=switch and the
 * thumb), the label, the description and the error. `class` lands on the
 * root; every other attribute, `wire:model` included, lands on the native
 * checkbox.
 *
 * @since 1.0.0
 */
class Toggle extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'toggle';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $checked  Whether it's on. Livewire binds it with wire:model.
     * @param  string  $size  Track size: sm, md or lg.
     * @param  string  $color  The track color when on.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $description  Supporting text under the label. A `description` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks it invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $required  Marks it required.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $checked = false,
        public string $size = 'md',
        public string $color = 'primary',
        public ?string $label = null,
        public ?string $description = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
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
        return view('livewire-ui-components::components.toggle');
    }
}
