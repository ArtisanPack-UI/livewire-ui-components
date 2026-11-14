<?php

/**
 * Checkbox Component
 *
 * The design-system Checkbox (#47): one option on or off, with the
 * indeterminate (mixed) state.
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
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * Checkbox Component Class
 *
 * Renders `.apui-checkbox`: the box (native control and indicator), the
 * label, the description and the error. `class` lands on the root; every
 * other attribute, `wire:model` included, lands on the native checkbox.
 *
 * @since 1.0.0
 */
class Checkbox extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'checkbox';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $checked  Whether it's checked. Livewire binds it with wire:model.
     * @param  bool  $indeterminate  Shows the mixed state. Wins over checked.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $description  Supporting text under the label. A `description` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks it invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $required  Marks it required, such as consent to terms.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $checked = false,
        public bool $indeterminate = false,
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
     * The root's `data-state`: indeterminate wins over checked.
     *
     * @since 3.0.0
     *
     * @return string `unchecked`, `checked` or `indeterminate`.
     */
    public function state(): string
    {
        if ($this->indeterminate) {
            return 'indeterminate';
        }

        return $this->checked ? 'checked' : 'unchecked';
    }

    /**
     * The indicator's glyphs: the check, then the minus, each marked with
     * `data-icon`. CSS shows the one for the current state.
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
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.checkbox');
    }

    /**
     * Checkboxes bound to one array (`wire:model="roles" value="admin"`)
     * share a model name, so their derived ids include the value too.
     *
     * @since 3.0.0
     *
     * @return list<string> The value, when there is one.
     */
    protected function idSeed(): array
    {
        $value = $this->attributes->get('value');

        return is_scalar($value) && '' !== (string) $value ? [(string) $value] : [];
    }
}
