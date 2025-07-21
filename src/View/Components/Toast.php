<?php
/**
 * Toast
 *
 * This file contains the Toast class for the ArtisanPack UI Livewire UI Components package.
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
 * Toast Class
 *
 * Provides functionality for the Toast component.
 *
 * @since 1.0.0
 */

class Toast extends Component
{
    public function __construct(
        public string $position = 'toast-top toast-end'
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.toast');
    }
}
