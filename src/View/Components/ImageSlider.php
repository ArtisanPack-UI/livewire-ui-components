<?php

declare(strict_types=1);
/**
 * ImageSlider
 *
 * This file contains the ImageSlider class for the ArtisanPack UI Livewire UI Components package.
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
 * ImageSlider Class
 *
 * Provides functionality for the ImageSlider component - a carousel/slider for sequential image navigation.
 *
 * @since 1.0.0
 */
class ImageSlider extends BaseComponent
{
    public string $uuid;

    public function __construct(
        public array $images,                    // Required: Array of image URLs or objects
        public ?string $id = null,              // Optional: Custom ID
        public bool $withArrows = true,         // Show navigation arrows
        public bool $withIndicators = true,     // Show position indicators
        public bool $autoPlay = false,          // Enable auto-progression
        public int $autoPlayInterval = 5000,    // Auto-play interval in ms
        public bool $pauseOnHover = true,       // Pause auto-play on hover
        public bool $loop = true,               // Loop back to first after last
        public string $transition = 'slide',    // Transition type: slide|fade
        public int $transitionDuration = 300,   // Transition duration in ms
        public bool $showCounter = false,       // Show "X of Y" counter
        public bool $enableLightbox = true,     // Enable PhotoSwipe lightbox
        public string $aspectRatio = '16:9',     // Image container aspect ratio
    ) {
        $this->uuid = 'artisanpack-slider-'.md5(serialize($this)).($id ? "-{$id}" : '');
    }

    /**
     * Get the aspect ratio classes for Tailwind
     */
    public function getAspectRatioClass(): string
    {
        return match ($this->aspectRatio) {
            'square', '1:1' => 'aspect-square',
            '16:9'  => 'aspect-video',
            '4:3'   => 'aspect-[4/3]',
            '3:4'   => 'aspect-[3/4]',
            '21:9'  => 'aspect-[21/9]',
            '2:1'   => 'aspect-[2/1]',
            default => 'aspect-video'
        };
    }

    /**
     * Get transition class based on transition type
     */
    public function getTransitionClass(): string
    {
        return match ($this->transition) {
            'fade'  => 'transition-opacity',
            'slide' => 'transition-transform',
            default => 'transition-transform'
        };
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.image-slider');
    }
}
