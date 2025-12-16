<?php

declare(strict_types=1);
/**
 * Checkbox
 *
 * This file contains the Checkbox class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Checkbox Class
 *
 * Provides functionality for the Checkbox component.
 *
 * @since 1.0.0
 */
class Checkbox extends Component
{
    /**
     * The component's UUID.
     *
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Creates a new component instance.
     *
     * @param  string|null  $id  The component's ID.
     * @param  string|null  $label  The component's label.
     * @param  bool|null  $right  Whether to position the label on the right.
     * @param  string|null  $hint  A hint message to display.
     * @param  string|null  $hintClass  The CSS class for the hint.
     * @param  bool|null  $card  Whether to display as a card.
     * @param  string|null  $cardClass  The CSS class for the card.
     * @param  string|null  $errorField  The validation error field name.
     * @param  string|null  $errorClass  The CSS class for the error.
     * @param  bool|null  $omitError  Whether to omit the error display.
     * @param  bool|null  $firstErrorOnly  Whether to show only the first error.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?bool $right = false,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?bool $card = false, // New card variant
        public ?string $cardClass = 'card card-compact border-2 border-base-300 hover:border-primary cursor-pointer transition-colors', // Card styling with proper color variables

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Gets the model name from attributes.
     *
     * @return string|null The model name.
     *
     * @since 1.0.0
     */
    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    /**
     * Gets the error field name.
     *
     * @return string|null The error field name.
     *
     * @since 1.0.0
     */
    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.checkbox');
    }
}
