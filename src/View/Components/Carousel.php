<?php
/**
 * Carousel
 *
 * This file contains the Carousel class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Inspired by Penguin UI.
 * Thank you.
 */
/**
 * Carousel Class
 *
 * Provides functionality for the Carousel component.
 *
 * @since 1.0.0
 */
class Carousel extends Component
{
    public string $uuid;

    public function __construct(
        public array $slides,
        public ?string $id = null,
        public ?bool $withoutIndicators = false,
        public ?bool $withoutArrows = false,
        public ?bool $autoplay = false,
        public ?int $interval = 2000,

        // Slots
        public mixed $content = null,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.carousel');
    }
}
