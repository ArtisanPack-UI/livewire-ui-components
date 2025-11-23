<?php

declare(strict_types=1);
/**
 * Textarea
 *
 * This file contains the Textarea class for the ArtisanPack UI Livewire UI Components package.
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
 * Textarea Class
 *
 * Provides functionality for the Textarea component.
 *
 * @since 1.0.0
 */
class Textarea extends Component
{
    /**
     * Unique identifier for the textarea instance.
     *
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Textarea component.
     *
     * @param  string|null  $id  Optional ID for the textarea.
     * @param  string|null  $label  Label text for the textarea.
     * @param  string|null  $hint  Hint text displayed below the textarea.
     * @param  string|null  $hintClass  CSS class for the hint text.
     * @param  bool|null  $inline  Whether to display the label inline with the textarea.
     * @param  string|null  $errorField  Field name for error messages.
     * @param  string|null  $errorClass  CSS class for error messages.
     * @param  bool|null  $omitError  Whether to hide error messages.
     * @param  bool|null  $firstErrorOnly  Whether to show only the first error message.
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?bool $inline = false,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Get the model name from the wire:model attribute.
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
     * Get the field name for error messages.
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
     * Renders the textarea component.
     *
     * @return View The rendered component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.textarea');
    }
}
