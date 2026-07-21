<?php

declare(strict_types=1);
/**
 * Link Component
 *
 * A component for displaying hyperlinks with customizable styling options.
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

/**
 * Link Component Class
 *
 * Provides a customizable link component with support for styling options.
 *
 * @since 1.0.0
 */
class Link extends BaseComponent
{
    /**
     * Constructor for the Link component.
     *
     * @param  string|null  $id  Optional ID for the link.
     * @param  string|null  $href  The URL the link points to.
     * @param  string|null  $color  Text color class.
     * @param  bool|null  $underline  Whether to show underline.
     * @param  bool|null  $hoverUnderline  Whether to show underline only on hover.
     * @param  bool|null  $external  Whether the link should open in a new tab.
     * @param  bool|null  $noWireNavigate  Disable wire:navigate for links.
     * @param  string|null  $icon  Optional icon to display before the text.
     * @param  string|null  $iconRight  Optional icon to display after the text.
     * @param  string|null  $tooltip  Optional tooltip text.
     * @param  string|null  $tooltipLeft  Optional tooltip text (left position).
     * @param  string|null  $tooltipRight  Optional tooltip text (right position).
     * @param  string|null  $tooltipBottom  Optional tooltip text (bottom position).
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $href = null,
        public ?string $color = null,
        public ?bool $underline = false,
        public ?bool $hoverUnderline = true,
        public ?bool $external = false,
        public ?bool $noWireNavigate = false,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,
        public string $uuid = '',
        public string $tooltipPosition = 'lg:tooltip-top',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }

        // Set tooltip based on the first non-null value if not already set
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;

        // Set tooltipPosition if not explicitly provided (using default value)
        if ('lg:tooltip-top' === $this->tooltipPosition) {
            $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
        }
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

        return 'text-primary hover:text-primary-focus';
    }

    /**
     * Get the underline class based on component properties.
     *
     * @return string The underline class.
     *
     * @since 1.0.0
     */
    public function underlineClass(): string
    {
        if ($this->underline) {
            return 'underline';
        }

        if ($this->hoverUnderline) {
            return 'no-underline hover:underline';
        }

        return 'no-underline';
    }

    /**
     * Renders the link component.
     *
     * @return View The rendered component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.link');
    }
}
