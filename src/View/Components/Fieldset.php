<?php

declare(strict_types=1);
/**
 * Fieldset
 *
 * This file contains the Fieldset class for the ArtisanPack UI Livewire UI Components package.
 * It serves as a styled container for form fields, often including a header.
 *
 * @author     Jacob Martella
 * @copyright  2025 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Fieldset Class
 *
 * Provides functionality for the Fieldset component.
 *
 * @since 1.0.0
 */
class Fieldset extends Component
{
    /**
     * Constructor
     *
     * Initializes the fieldset component with its properties.
     *
     * @since 1.0.0
     *
     * @param  string|null  $title  Optional. The title to display in the header. Default null.
     * @param  string|null  $subtitle  Optional. The subtitle to display in the header. Default null.
     * @param  string|null  $icon  Optional. The icon to display in the header. Default null.
     * @param  bool  $separator  Optional. Whether to show a separator in the header. Default true.
     * @param  string|null  $bgColor  Optional. The background color class (e.g., 'bg-base-100'). Default 'bg-base-100'.
     * @param  string|null  $textColor  Optional. The text color class (e.g., 'text-warning'). Default null.
     * @param  string|null  $border  Optional. The border classes (e.g., 'border border-base-300'). Default 'border border-base-300'.
     * @param  mixed  $middle  Optional. Slot for the middle section of the header. Default null.
     * @param  mixed  $actions  Optional. Slot for the actions section of the header. Default null.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?string $icon = null,
        public ?bool $separator = true,
        public ?string $bgColor = 'bg-base-100',
        public ?string $textColor = null,
        public ?string $border = 'border border-base-300',

        // Slots
        public mixed $middle = null,
        public mixed $actions = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.fieldset');
    }
}
