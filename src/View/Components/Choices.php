<?php

declare(strict_types=1);
/**
 * Choices
 *
 * This file contains the Choices class for the ArtisanPack UI Livewire UI Components package.
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
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

/**
 * Choices Class
 *
 * Provides functionality for the Choices component.
 *
 * @since 1.0.0
 */
class Choices extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?bool $inline = false,
        public ?bool $clearable = false,
        public ?string $prefix = null,
        public ?string $suffix = null,

        public ?bool $searchable = false,
        public ?bool $single = false,
        public ?bool $compact = false,
        public ?string $compactText = 'selected',
        public ?bool $allowAll = false,
        public ?string $debounce = '250ms',
        public ?int $minChars = 0,
        public ?string $allowAllText = 'Select all',
        public ?string $removeAllText = 'Remove all',
        public ?string $searchFunction = 'search',
        public ?string $optionValue = 'id',
        public ?string $optionLabel = 'name',
        public ?string $optionSubLabel = '',
        public ?string $optionAvatar = 'avatar',
        public ?bool $valuesAsString = false,
        public ?string $height = 'max-h-64',
        public Collection|array $options = new Collection,
        public ?string $noResultText = 'No results found.',

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,

        // Slots
        public mixed $item = null,
        public mixed $selection = null,
        public mixed $prepend = null,
        public mixed $append = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;

        if (($this->allowAll || $this->compact) && ($this->single || $this->searchable)) {
            throw new Exception('`allow-all` and `compact` does not work combined with `single` or `searchable`.');
        }
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

    public function isRequired(): bool
    {
        return $this->attributes->has('required') && true == $this->attributes->get('required');
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && true == $this->attributes->get('disabled');
    }

    public function getOptionValue($option): mixed
    {
        $value = data_get($option, $this->optionValue);

        if ($this->valuesAsString) {
            return "'$value'";
        }

        return is_numeric($value) && ! str($value)->startsWith('0') ? $value : "'$value'";
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.choices');
    }
}
