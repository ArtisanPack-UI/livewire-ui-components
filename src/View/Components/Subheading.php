<?php

declare(strict_types=1);
/**
 * Subheading Component
 *
 * A component for displaying subheadings with customizable styling options.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Subheading Component Class
 *
 * Provides a customizable subheading component with support for styling options.
 *
 * @since 1.0.0
 */
class Subheading extends Component
{
    /**
     * Unique identifier for the subheading instance.
     *
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Subheading component.
     *
     * @param  string|null  $id  Optional ID for the subheading.
     * @param  string|null  $size  Custom size class. Defaults to text-lg.
     * @param  string|null  $color  Text color class.
     * @param  bool|null  $semibold  Whether to use semibold font weight.
     * @param  bool|null  $bold  Whether to use bold font weight.
     * @param  bool|null  $center  Whether to center align the subheading.
     * @param  bool|null  $muted  Whether to use muted text color.
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $size = null,
        public ?string $color = null,
        public ?bool $semibold = false,
        public ?bool $bold = false,
        public ?bool $center = false,
        public ?bool $muted = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Get the font weight class based on component properties.
     *
     * @return string The font weight class.
     *
     * @since 1.0.0
     */
    public function fontWeightClass(): string
    {
        if ($this->bold) {
            return 'font-bold';
        }

        if ($this->semibold) {
            return 'font-semibold';
        }

        return 'font-medium'; // Default to medium
    }

    /**
     * Get the text color class based on component properties.
     *
     * @return string The text color class.
     *
     * @since 1.0.0
     */
    public function colorClass(): string
    {
        if ($this->color) {
            return $this->color;
        }

        return $this->muted ? 'text-base-content/70' : 'text-base-content';
    }

    /**
     * Renders the subheading component.
     *
     * @return View The rendered component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.subheading');
    }
}
