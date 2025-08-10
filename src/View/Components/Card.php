<?php
/**
 * Card
 *
 * This file contains the Card class for the ArtisanPack UI Livewire UI Components package.
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
 * Card Class
 *
 * Provides functionality for the Card component.
 *
 * @since 1.0.0
 */

class Card extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $separator = false,
        public ?bool $shadow = false,
        public ?string $progressIndicator = null,
        public ?string $figurePosition = 'top', // New prop for figure position

        // Slots
        public mixed $menu = null,
        public mixed $actions = null,
        public mixed $figure = null,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
        
        // Validate figure position
        $this->figurePosition = in_array($this->figurePosition, ['top', 'bottom', 'left', 'right']) 
            ? $this->figurePosition 
            : 'top';
    }

    public function progressTarget(): ?string
    {
        if ($this->progressIndicator == 1) {
            return $this->attributes->whereStartsWith('progress-indicator')->first();
        }

        return $this->progressIndicator;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.card');
    }
}