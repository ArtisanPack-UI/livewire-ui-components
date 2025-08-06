<?php
/**
 * CheckboxGroup
 *
 * This file contains the CheckboxGroup class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

/**
 * CheckboxGroup Class
 *
 * Provides functionality for the CheckboxGroup component.
 *
 * @since 1.0.0
 */
class CheckboxGroup extends Component
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
        public Collection|array $options = new Collection(),
        public ?bool $horizontal = false,
        public ?bool $card = false,
        public ?string $cardClass = 'card card-compact border-2 border-base-300 hover:border-primary cursor-pointer transition-colors',

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
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
        return view('livewire-ui-components::components.checkbox-group');
    }
}