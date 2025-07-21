<?php
/**
 * Tags
 *
 * This file contains the Tags class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
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
     * @var string
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Tags component.
     *
     * @param string|null $id            Optional ID for the tags.
     * @param string|null $label         Label text for the input.
     * @param string|null $hint          Hint text displayed below the input.
     * @param string|null $hintClass     CSS class for the hint text.
     * @param string|null $icon          Icon displayed at the start of the input.
     * @param string|null $iconRight     Icon displayed at the end of the input.
     * @param bool|null   $inline        Whether to display the label inline with the input.
     * @param bool|null   $clearable     Whether to show a clear button.
     * @param string|null $prefix        Text displayed before the input.
     * @param string|null $suffix        Text displayed after the input.
     * @param mixed|null  $prepend       Content to prepend to the input.
     * @param mixed|null  $append        Content to append to the input.
     * @param string|null $errorField    Field name for error messages.
     * @param string|null $errorClass    CSS class for error messages.
     * @param bool|null   $omitError     Whether to hide error messages.
     * @param bool|null   $firstErrorOnly Whether to show only the first error message.
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

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

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

    public function isReadonly(): bool
    {
        return $this->attributes->has('readonly') && $this->attributes->get('readonly') == true;
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && $this->attributes->get('disabled') == true;
    }

    public function isRequired(): bool
    {
        return $this->attributes->has('required') && $this->attributes->get('required') == true;
    }

    /**
     * Renders the tags component.
     *
     * @return View The rendered component.
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.tags');
    }
}
