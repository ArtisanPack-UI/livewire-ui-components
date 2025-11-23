<?php

declare(strict_types=1);
/**
 * Separator
 *
 * This file contains the Separator class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;

/**
 * Separator Class
 *
 * Provides functionality for the Separator component.
 *
 * @since 1.0.0
 */
class Separator extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $target = null,
        public bool $progress = false,
        public ?string $color = null,
        public ?string $image = null,
        public bool $vertical = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function progressTarget(): ?string
    {
        if (1 == $this->target) {
            return $this->attributes->whereStartsWith('target')->first();
        }

        return $this->target;
    }

    public function getColorClasses(): string
    {
        if (! $this->color) {
            return 'border-base-content/10';
        }

        // Handle hex codes
        if (str_starts_with($this->color, '#')) {
            return '';
        }

        // Handle predefined colors
        $colorMap = [
            'primary'   => 'border-primary',
            'secondary' => 'border-secondary',
            'accent'    => 'border-accent',
            'success'   => 'border-success',
            'warning'   => 'border-warning',
            'error'     => 'border-error',
        ];

        if (isset($colorMap[$this->color])) {
            return $colorMap[$this->color];
        }

        // Handle Tailwind classes
        return $this->color;
    }

    public function getProgressColorClasses(): string
    {
        if (! $this->color) {
            return 'progress-primary';
        }

        // Handle predefined colors - use more visible alternatives for problematic colors
        $colorMap = [
            'primary'   => 'progress-primary',
            'secondary' => 'progress-secondary',
            'accent'    => 'progress-accent',
            'success'   => 'progress-accent', // Use accent instead of success for better visibility
            'warning'   => 'progress-warning',
            'error'     => 'progress-error',
        ];

        if (isset($colorMap[$this->color])) {
            return $colorMap[$this->color];
        }

        // For hex codes, use a neutral progress color that's visible
        if (str_starts_with($this->color, '#')) {
            return 'progress-accent';
        }

        // For custom Tailwind classes, try to match or use accent
        if (str_contains($this->color, 'border-')) {
            // Extract color name from border class and try to match to progress
            $colorName     = str_replace(['border-', '-500', '-400', '-600', '-300', '-700'], '', $this->color);
            $progressClass = 'progress-'.$colorName;

            // Return the extracted class, fallback to accent if not standard
            return in_array($colorName, ['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'indigo']) ?
                   $progressClass : 'progress-accent';
        }

        // Default fallback
        return 'progress-accent';
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.separator');
    }
}
