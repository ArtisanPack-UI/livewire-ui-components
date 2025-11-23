<?php

declare(strict_types=1);
/**
 * Tags
 *
 * This file contains the Tags class for the ArtisanPack UI Livewire UI Components package.
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

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

/**
 * Tags Class
 *
 * Provides functionality for the Tags component.
 *
 * @since 1.0.0
 */
class Tags extends Component
{
    /**
     * Unique identifier for the tags instance.
     *
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Tags component.
     *
     * @param  string|null  $id  Optional ID for the tags.
     * @param  string|null  $label  Label text for the input.
     * @param  string|null  $hint  Hint text displayed below the input.
     * @param  string|null  $hintClass  CSS class for the hint text.
     * @param  string|null  $icon  Icon displayed at the start of the input.
     * @param  string|null  $iconRight  Icon displayed at the end of the input.
     * @param  bool|null  $inline  Whether to display the label inline with the input.
     * @param  bool|null  $clearable  Whether to show a clear button.
     * @param  string|null  $prefix  Text displayed before the input.
     * @param  string|null  $suffix  Text displayed after the input.
     * @param  mixed|null  $prepend  Content to prepend to the input.
     * @param  mixed|null  $append  Content to append to the input.
     * @param  string|null  $errorField  Field name for error messages.
     * @param  string|null  $errorClass  CSS class for error messages.
     * @param  bool|null  $omitError  Whether to hide error messages.
     * @param  bool|null  $firstErrorOnly  Whether to show only the first error message.
     * @param  bool|null  $searchable  Whether to enable search functionality.
     * @param  string|null  $debounce  Debounce delay for search input.
     * @param  int|null  $minChars  Minimum characters required to trigger search.
     * @param  string|null  $searchFunction  Name of the Livewire method to call for search.
     * @param  string|null  $optionValue  Key for option value extraction.
     * @param  string|null  $optionLabel  Key for option label extraction.
     * @param  string|null  $optionSubLabel  Key for option sub-label extraction.
     * @param  string|null  $optionAvatar  Key for option avatar extraction.
     * @param  string|null  $height  Maximum height of the dropdown.
     * @param  array|Collection  $options  Collection of available options.
     * @param  string|null  $noResultText  Text to display when no results found.
     * @param  bool|null  $allowCustomTags  Whether to allow custom tag creation.
     * @param  string|null  $customTagsText  Text to display for custom tag creation.
     *
     * @since 1.0.0
     */
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

        // Search functionality properties
        public ?bool $searchable = false,
        public ?string $debounce = '250ms',
        public ?int $minChars = 0,
        public ?string $searchFunction = 'search',
        public ?string $optionValue = 'id',
        public ?string $optionLabel = 'name',
        public ?string $optionSubLabel = '',
        public ?string $optionAvatar = 'avatar',
        public ?string $height = 'max-h-64',
        public Collection|array $options = new Collection,
        public ?string $noResultText = 'No results found.',
        public ?bool $allowCustomTags = true,
        public ?string $customTagsText = 'Press Enter to create',

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;

        // Validation logic for search configuration
        if ($this->searchable && (! $this->options || (is_array($this->options) && empty($this->options)) || ($this->options instanceof Collection && $this->options->isEmpty()))) {
            throw new Exception('When `searchable` is enabled, you must provide `options` array or collection.');
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

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && true == $this->attributes->get('disabled');
    }

    public function isRequired(): bool
    {
        return $this->attributes->has('required') && true == $this->attributes->get('required');
    }

    /**
     * Extract the value from an option using the configured option value key.
     *
     * @param  mixed  $option  The option to extract value from.
     *
     * @return mixed The extracted value.
     *
     * @since 1.0.0
     */
    public function getOptionValue($option): mixed
    {
        $value = data_get($option, $this->optionValue);

        return is_numeric($value) && ! str($value)->startsWith('0') ? $value : "'$value'";
    }

    /**
     * Check if the component has searchable options configured.
     *
     * @return bool True if searchable and has options.
     *
     * @since 1.0.0
     */
    public function hasSearchableOptions(): bool
    {
        if (! $this->searchable) {
            return false;
        }

        if (is_array($this->options)) {
            return ! empty($this->options);
        }

        if ($this->options instanceof Collection) {
            return ! $this->options->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the dropdown should be shown.
     *
     * @return bool True if dropdown should be displayed.
     *
     * @since 1.0.0
     */
    public function shouldShowDropdown(): bool
    {
        return $this->hasSearchableOptions();
    }

    /**
     * Renders the tags component.
     *
     * @return View The rendered component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.tags');
    }
}
