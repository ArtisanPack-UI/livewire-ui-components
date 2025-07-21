<?php
/**
 * Main
 *
 * This file contains the Main class for the ArtisanPack UI Livewire UI Components package.
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
 * Main Class
 *
 * Provides functionality for the Main component.
 *
 * @since 1.0.0
 */

class Main extends Component
{
    public string $url;

    public function __construct(

        // Slots
        public mixed $sidebar = null,
        public mixed $content = null,
        public mixed $footer = null,
        public ?bool $fullWidth = false,
        public ?bool $withNav = false,
        public ?string $collapseText = 'Collapse',
        public ?string $collapseIcon = 'o-bars-3-bottom-right',
        public ?bool $collapsible = false,
    ) {
        $this->url = route('mary.toogle-sidebar', absolute: false);
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.main');
    }
}
