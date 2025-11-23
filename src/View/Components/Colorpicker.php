<?php

declare(strict_types=1);
/**
 * Colorpicker
 *
 * This file contains the Colorpicker class for the ArtisanPack UI Livewire UI Components package.
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
 * Colorpicker Class
 *
 * Provides functionality for the Colorpicker component.
 *
 * @since 1.0.0
 */
class Colorpicker extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = '',
        public ?string $iconRight = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?bool $inline = false,
        public ?bool $clearable = false,
        public ?bool $random = false,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function isReadonly(): bool
    {
        return $this->attributes->has('readonly') && true == $this->attributes->get('readonly');
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && true == $this->attributes->get('disabled');
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.colorpicker');
    }
}
