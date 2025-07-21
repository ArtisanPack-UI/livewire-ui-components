<?php
/**
 * Nav
 *
 * This file contains the Nav class for the ArtisanPack UI Livewire UI Components package.
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
 * Nav Class
 *
 * Provides functionality for the Nav component.
 *
 * @since 1.0.0
 */

class Nav extends Component
{
    public function __construct(
        public ?bool $sticky = false,
        public ?bool $fullWidth = false,

        // Slots
        public mixed $brand = null,
        public mixed $actions = null
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.nav');
    }
}
