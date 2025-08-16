<?php
/**
 * Tabs
 *
 * This file contains the Tabs class for the ArtisanPack UI Livewire UI Components package.
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
 * Tabs Class
 *
 * Provides functionality for the Tabs component.
 *
 * @since 1.0.0
 */

class Tabs extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $selected = null,
        public ?string $orientation = 'horizontal',
        public string $labelClass = 'font-semibold pb-1',
        public string $activeClass = 'border-b-[length:var(--border)] border-b-base-content/50',
        public string $labelDivClass = 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto',
        public string $tabsClass = 'relative w-full',
        // New vertical-specific classes with responsive behavior
        public string $verticalTabsClass = 'relative w-full flex flex-col md:flex-row',
        public string $verticalLabelClass = 'font-semibold w-full px-3 py-2 md:pr-1 md:pl-1 md:py-2',
        public string $verticalActiveClass = 'border-r-[length:var(--border)] border-r-base-content/50',
        public string $verticalLabelDivClass = 'border-r-[length:var(--border)] border-r-base-content/10 flex flex-col overflow-y-auto min-w-48',
        public string $verticalContentClass = 'flex-1',
        // Right-side vertical specific classes with responsive behavior
        public string $verticalRightActiveClass = 'border-l-[length:var(--border)] border-l-base-content/50',
        public string $verticalRightLabelDivClass = 'border-l-[length:var(--border)] border-l-base-content/10 flex flex-col overflow-y-auto min-w-48',
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    /**
     * Check if tabs should use vertical layout
     */
    public function isVertical(): bool
    {
        return in_array($this->orientation, ['vertical-left', 'vertical-right']);
    }

    /**
     * Check if tabs should be positioned on the right side
     */
    public function isVerticalRight(): bool
    {
        return $this->orientation === 'vertical-right';
    }

    /**
     * Get the appropriate container classes based on orientation
     */
    public function getTabsContainerClass(): string
    {
        return $this->isVertical() ? $this->verticalTabsClass : $this->tabsClass;
    }

    /**
     * Get the appropriate label div classes based on orientation
     */
    public function getLabelDivClass(): string
    {
        if ($this->isVerticalRight()) {
            return $this->verticalRightLabelDivClass;
        }
        
        return $this->isVertical() ? $this->verticalLabelDivClass : $this->labelDivClass;
    }

    /**
     * Get the appropriate label classes based on orientation
     */
    public function getLabelClass(): string
    {
        return $this->isVertical() ? $this->verticalLabelClass : $this->labelClass;
    }

    /**
     * Get the appropriate active classes based on orientation
     */
    public function getActiveClass(): string
    {
        if ($this->isVerticalRight()) {
            return $this->verticalRightActiveClass;
        }
        
        return $this->isVertical() ? $this->verticalActiveClass : $this->activeClass;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.tabs');
    }
}
