<?php

declare(strict_types=1);
/**
 * Radio
 *
 * This file contains the Radio class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Collection;

/**
 * Radio Class
 *
 * Provides functionality for the Radio component.
 *
 * @since 1.0.0
 */
class Radio extends BaseComponent
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $optionValue = 'id',
        public ?string $optionLabel = 'name',
        public ?string $optionHint = 'hint',
        public Collection|array $options = new Collection,
        public ?bool $inline = false,
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

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.radio');
    }
}
