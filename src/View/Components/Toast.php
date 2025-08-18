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
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
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
        public string $position = 'toast-top toast-end',
        public ?string $color = null,
        public ?string $colorAdjustment = null,
    ) {
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @return array
     * @since 1.1.0
     */
    public function getColorClasses(): array
    {
        if (!$this->color) {
            return [];
        }

        $colorGenerator = new ColorGenerator();
        
        // Use ColorGenerator for color resolution
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->color, 
            $this->colorAdjustment, 
            'toast'
        );
        
        return $colorClasses;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.toast');
    }
}
