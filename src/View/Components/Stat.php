<?php

declare(strict_types=1);
/**
 * Stat
 *
 * This file contains the Stat class for the ArtisanPack UI Livewire UI Components package.
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
 * Stat Class
 *
 * Provides functionality for the Stat component.
 *
 * @since 1.0.0
 */
class Stat extends Component
{
    public string $uuid;

    public string $tooltipPosition = 'lg:tooltip-top';

    public function __construct(
        public ?string $id = null,
        public ?string $value = null,
        public ?string $icon = null,
        public ?string $color = '',
        public ?string $title = null,
        public ?string $description = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,

        // New size and positioning props
        public ?string $size = 'md',
        public ?string $iconPosition = 'left',
        public ?string $titlePosition = 'top',
        public ?string $contentAlign = 'left',

    ) {
        $this->uuid            = 'artisanpack'.md5(serialize($this)).$id;
        $this->tooltip         = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    /**
     * Get size-based CSS classes for different component parts.
     *
     * @return array Array of size classes for container, icon, value, title, and description
     *
     * @since 1.0.0
     */
    public function sizeClasses(): array
    {
        return match ($this->size) {
            'xs' => [
                'container'   => 'px-3 py-2',
                'icon'        => 'w-6 h-6',
                'value'       => 'text-lg',
                'title'       => 'text-xs',
                'description' => 'text-xs',
            ],
            'sm' => [
                'container'   => 'px-4 py-3',
                'icon'        => 'w-7 h-7',
                'value'       => 'text-xl',
                'title'       => 'text-xs',
                'description' => 'text-sm',
            ],
            'md' => [
                'container'   => 'px-5 py-4',
                'icon'        => 'w-9 h-9',
                'value'       => 'text-2xl',
                'title'       => 'text-sm',
                'description' => 'text-sm',
            ],
            'lg' => [
                'container'   => 'px-6 py-5',
                'icon'        => 'w-11 h-11',
                'value'       => 'text-3xl',
                'title'       => 'text-base',
                'description' => 'text-base',
            ],
            'xl' => [
                'container'   => 'px-8 py-6',
                'icon'        => 'w-14 h-14',
                'value'       => 'text-4xl',
                'title'       => 'text-lg',
                'description' => 'text-lg',
            ],
            default => [
                'container'   => 'px-5 py-4',
                'icon'        => 'w-9 h-9',
                'value'       => 'text-2xl',
                'title'       => 'text-sm',
                'description' => 'text-sm',
            ]
        };
    }

    /**
     * Get layout-based CSS classes for positioning and alignment.
     *
     * @return array Array of layout classes for container and content
     *
     * @since 1.0.0
     */
    public function layoutClasses(): array
    {
        $isVertical = in_array($this->iconPosition, ['top', 'bottom']);

        return [
            'container' => $isVertical ? 'flex flex-col items-center gap-3' : 'flex items-center gap-3',
            'content'   => match ($this->contentAlign) {
                'center' => 'text-center',
                'right'  => 'text-right rtl:text-left',
                default  => 'text-left rtl:text-right'
            },
        ];
    }

    /**
     * Determine if icon should be rendered first (left or top position).
     *
     * @return bool True if icon should render first
     *
     * @since 1.0.0
     */
    public function shouldRenderIconFirst(): bool
    {
        return in_array($this->iconPosition, ['left', 'top']);
    }

    /**
     * Determine if title should be rendered first (top position).
     *
     * @return bool True if title should render first
     *
     * @since 1.0.0
     */
    public function shouldRenderTitleFirst(): bool
    {
        return 'top' === $this->titlePosition;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.stat');
    }
}
