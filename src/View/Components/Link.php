<?php
/**
 * Link Component
 *
 * A component for displaying hyperlinks with customizable styling options.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage View\Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Link Component Class
 *
 * Provides a customizable link component with support for styling options.
 *
 * @since 1.0.0
 */
class Link extends Component
{
    /**
     * Unique identifier for the link instance.
     *
     * @var string
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Link component.
     *
     * @param string|null $id             Optional ID for the link.
     * @param string|null $href           The URL the link points to.
     * @param string|null $color          Text color class.
     * @param bool|null   $underline      Whether to show underline.
     * @param bool|null   $hoverUnderline Whether to show underline only on hover.
     * @param bool|null   $external       Whether the link should open in a new tab.
     * @param bool|null   $noWireNavigate Disable wire:navigate for links.
     * @param string|null $icon           Optional icon to display before the text.
     * @param string|null $iconRight      Optional icon to display after the text.
     * @param string|null $tooltip        Optional tooltip text.
     * @param string|null $tooltipLeft    Optional tooltip text (left position).
     * @param string|null $tooltipRight   Optional tooltip text (right position).
     * @param string|null $tooltipBottom  Optional tooltip text (bottom position).
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
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    /**
     * Get the text color class based on component properties.
     *
     * @return string The text color class.
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
     * @return View|Closure|string The rendered component.
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
            <a 
                id="{{ $id }}"
                href="{{ $href }}"
                {{ $attributes->class([
                    'inline-flex items-center gap-1',
                    $colorClass(),
                    $underlineClass(),
                    "lg:tooltip $tooltipPosition" => $tooltip,
                ]) }}
                
                @if($external)
                    target="_blank"
                    rel="noopener noreferrer"
                @endif

                @if(!$external && !$noWireNavigate)
                    wire:navigate
                @endif

                @if($tooltip)
                    data-tip="{{ $tooltip }}"
                @endif
            >
                @if($icon)
                    <x-artisanpack-icon :name="$icon" />
                @endif

                {{ $slot }}

                @if($iconRight)
                    <x-artisanpack-icon :name="$iconRight" />
                @endif
            </a>
        BLADE;
    }
}
