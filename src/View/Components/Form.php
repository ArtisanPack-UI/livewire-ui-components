<?php

declare(strict_types=1);
/**
 * Form
 *
 * This file contains the Form class for the ArtisanPack UI Livewire UI Components package.
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

/**
 * Form Class
 *
 * Provides functionality for the Form component.
 *
 * @since 1.0.0
 */
class Form extends BaseComponent
{
    public function __construct(

        // Slots
        public mixed $actions = null,
        public ?bool $noSeparator = false,
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.form');
    }
}
