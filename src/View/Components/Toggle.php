<?php

declare(strict_types=1);
/**
 * Toggle
 *
 * This file contains the Toggle class for the ArtisanPack UI Livewire UI Components package.
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

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Toggle Class
 *
 * Provides functionality for the Toggle component.
 *
 * @since 1.0.0
 */
class Toggle extends Component
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
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.toggle');
    }
}
