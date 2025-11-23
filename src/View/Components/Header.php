<?php

declare(strict_types=1);
/**
 * Header
 *
 * This file contains the Header class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * Header Class
 *
 * Provides functionality for the Header component.
 *
 * @since 1.0.0
 */
class Header extends Component
{
    /**
     * Anchor link for the header.
     *
     * @since 1.0.0
     */
    public string $anchor = '';

    /**
     * Constructor
     *
     * Initializes the header component with its properties.
     *
     * @since 1.0.0
     *
     * @param  string|null  $title  Optional. The title text. Default null.
     * @param  string|null  $subtitle  Optional. The subtitle text. Default null.
     * @param  bool  $separator  Optional. Whether to show a separator. Default false.
     * @param  string|null  $progressIndicator  Optional. The wire target for the progress indicator. Default null.
     * @param  string  $progressIndicatorClass  Optional. The class for the progress indicator. Default 'progress-primary'.
     * @param  bool  $withAnchor  Optional. Whether to wrap the title in an anchor link. Default false.
     * @param  string  $size  Optional. The text size class. Default 'text-2xl'.
     * @param  int  $level  Optional. The HTML heading level (e.g., 2 for <h2>). Default 2.
     * @param  string|null  $icon  Optional. The icon name. Default null.
     * @param  string|null  $iconClasses  Optional. Additional classes for the icon. Default null.
     * @param  mixed  $middle  Optional. Slot for the middle section. Default null.
     * @param  mixed  $actions  Optional. Slot for the actions section. Default null.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $separator = false,
        public ?string $progressIndicator = null,
        public string $progressIndicatorClass = 'progress-primary',
        public ?bool $withAnchor = false,
        public ?string $size = 'text-2xl',
        public int $level = 2,

        // Icon
        public ?string $icon = null,
        public ?string $iconClasses = null,

        // Slots
        public mixed $middle = null,
        public mixed $actions = null,
    ) {
        $this->anchor = Str::slug($title);
    }

    /**
     * Get the progress indicator target.
     *
     * @since 1.0.0
     */
    public function progressTarget(): ?string
    {
        if (1 == $this->progressIndicator) {
            return $this->attributes->whereStartsWith('progress-indicator')->first();
        }

        return $this->progressIndicator;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.header');
    }
}
