<?php

/**
 * Textarea Component
 *
 * The design-system Textarea (#53): multi-line text entry with a label,
 * hint, error and an optional character counter.
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
 * Textarea Component Class
 *
 * Renders `.apui-textarea`: the label, the box (control and counter), the
 * hint and the error. `class` lands on the root; every other attribute,
 * `wire:model` included, lands on the native textarea.
 *
 * @since 1.0.0
 */
class Textarea extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'textarea';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  int  $rows  Visible lines of text.
     * @param  int|null  $maxLength  The most characters allowed. Shows the counter.
     * @param  bool  $autoResize  Grows the box with its content.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the box. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $readonly  Makes the control read-only.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Placeholder text.
     * @param  string|null  $value  The value. Livewire binds it with wire:model.
     * @param  string|null  $remainingLabel  The counter's announcement near the limit; `{count}` is the characters left. Defaults to a translated "Characters left: {count}".
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int $rows = 4,
        public ?int $maxLength = null,
        public bool $autoResize = false,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $remainingLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {
        $this->remainingLabel ??= __('Characters left: {count}');
    }

    /**
     * The characters left within which the counter is announced: 10% of the
     * limit, and at least 10.
     *
     * @since 3.0.0
     *
     * @return int The threshold, or 0 without a limit.
     */
    public function announceWithin(): int
    {
        return null === $this->maxLength ? 0 : max(10, (int) ceil($this->maxLength * 0.1));
    }

    /**
     * The live region's text for a value: the remaining label near the
     * limit, else empty.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The current value.
     *
     * @return string The announcement.
     */
    public function announcement(string $value): string
    {
        if (null === $this->maxLength) {
            return '';
        }

        $remaining = max(0, $this->maxLength - mb_strlen($value));

        return $remaining <= $this->announceWithin() ? str_replace('{count}', (string) $remaining, (string) $this->remainingLabel) : '';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.textarea');
    }
}
