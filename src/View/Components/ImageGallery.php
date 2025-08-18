<?php
/**
 * ImageGallery
 *
 * This file contains the ImageGallery class for the ArtisanPack UI Livewire UI Components package.
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
 * ImageGallery Class
 *
 * Provides functionality for the ImageGallery component - a grid-based image gallery.
 *
 * @since 1.0.0
 */
class ImageGallery extends Component
{
    public string $uuid;

    public function __construct(
        public array $images,                           // Required: Array of image URLs or objects
        public ?string $id = null,                     // Optional: Custom ID
        public array $columns = [                      // Responsive columns
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 4,
            'xl' => 5
        ],
        public string $aspectRatio = 'square',         // square|landscape|portrait|auto
        public string $gap = 'md',                     // xs|sm|md|lg|xl
        public bool $enableLightbox = true,            // Enable PhotoSwipe lightbox
        public bool $showCaptions = false,             // Show image captions
        public string $layout = 'grid',                // grid|masonry
        public bool $lazyLoad = true,                  // Enable lazy loading
        public ?array $filters = null,                 // Optional category filters
        public int $itemsPerPage = 0,                  // 0 = no pagination
        public string $loadingStyle = 'skeleton'       // skeleton|spinner|fade
    ) {
        $this->uuid = "artisanpack-gallery-" . md5(serialize($this)) . ($id ? "-{$id}" : '');
    }

    /**
     * Get the gap class for Tailwind
     */
    public function getGapClass(): string
    {
        return match ($this->gap) {
            'xs' => 'gap-1',
            'sm' => 'gap-2',
            'md' => 'gap-4',
            'lg' => 'gap-6',
            'xl' => 'gap-8',
            default => 'gap-4'
        };
    }

    /**
     * Get the aspect ratio class for Tailwind
     */
    public function getAspectRatioClass(): string
    {
        return match ($this->aspectRatio) {
            'square' => 'aspect-square',
            'landscape' => 'aspect-[4/3]',
            'portrait' => 'aspect-[3/4]',
            'auto' => '',
            default => 'aspect-square'
        };
    }

    /**
     * Get the grid column classes for responsive design
     */
    public function getGridColumnClasses(): string
    {
        $classes = [];

        foreach ($this->columns as $breakpoint => $cols) {
            if ($breakpoint === 'default') {
                $classes[] = "grid-cols-{$cols}";
            } else {
                $classes[] = "{$breakpoint}:grid-cols-{$cols}";
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Get loading placeholder classes
     */
    public function getLoadingClass(): string
    {
        return match ($this->loadingStyle) {
            'skeleton' => 'bg-gray-200 dark:bg-gray-700 animate-pulse',
            'spinner' => 'bg-gray-100 dark:bg-gray-800',
            'fade' => 'bg-gray-100 dark:bg-gray-800 opacity-50',
            default => 'bg-gray-200 dark:bg-gray-700 animate-pulse'
        };
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.image-gallery');
    }
}
