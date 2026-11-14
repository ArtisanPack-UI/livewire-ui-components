<?php

/**
 * Pin Component
 *
 * The design-system Pin (#53): a row of single-character cells for
 * one-time codes, driven by one native input.
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
 * Pin Component Class
 *
 * Renders `.apui-pin`: the label, the cells (one native input with
 * autocomplete=one-time-code laid over the aria-hidden cells and
 * separators), the hint and the error. `class` lands on the root; every
 * other attribute, `wire:model` included, lands on the native input. Alpine
 * mirrors the value into the cells, drops characters the type doesn't allow
 * and dispatches `complete` once every cell is filled.
 *
 * @since 1.0.0
 */
class Pin extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'pin';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  int  $length  The number of cells.
     * @param  string  $type  numeric or alphanumeric.
     * @param  bool  $mask  Shows a dot for each character.
     * @param  int|null  $groupSize  Draws a separator after every groupSize cells.
     * @param  string  $size  Cell size: sm or md.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the cells. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the code invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $required  Marks the code required.
     * @param  string|null  $value  The code. Livewire binds it with wire:model.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int $length = 6,
        public string $type = 'numeric',
        public bool $mask = false,
        public ?int $groupSize = null,
        public string $size = 'md',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $value = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {
        $this->length = max(1, $this->length);
    }

    /**
     * The characters each cell shows for the initial value: the character,
     * a dot when masked, or an empty string.
     *
     * @since 3.0.0
     *
     * @return list<string> One entry per cell.
     */
    public function cells(): array
    {
        $characters = mb_str_split(mb_substr($this->value ?? '', 0, $this->length));
        $cells      = [];
        for ($i = 0; $i < $this->length; $i++) {
            $character = $characters[$i] ?? '';
            $cells[]   = '' !== $character && $this->mask ? '•' : $character;
        }

        return $cells;
    }

    /**
     * Whether a separator follows the cell at an index.
     *
     * @since 3.0.0
     *
     * @param  int  $index  The cell index, from 0.
     *
     * @return bool True after every groupSize cells, except the last.
     */
    public function separatorAfter(int $index): bool
    {
        return null !== $this->groupSize && $this->groupSize > 0 && $index < $this->length - 1 && 0 === ($index + 1) % $this->groupSize;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.pin');
    }
}
