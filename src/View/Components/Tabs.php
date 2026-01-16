<?php

declare(strict_types=1);
/**
 * Tabs
 *
 * This file contains the Tabs class for the ArtisanPack UI Livewire UI Components package.
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

use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
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

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $id  The component ID.
     * @param  string|null  $selected  The name of the selected tab.
     * @param  string|null  $orientation  The orientation of the tabs. Accepts 'horizontal', 'vertical-left', 'vertical-right'. Default 'horizontal'.
     * @param  string|null  $variant  The style variant of the tab buttons. Accepts 'rounded', 'pill'. Default null.
     * @param  string|null  $gap  The spacing between tab buttons. Accepts Tailwind gap classes like 'gap-2', 'gap-4', etc.
     * @param  string|null  $labelColorClasses  Custom Tailwind CSS classes for the inactive tab buttons, including hover/focus states.
     * @param  string|null  $activeColorClasses  Custom Tailwind CSS classes for the active tab button. This will override the default active style.
     * @param  string  $labelClass  Base CSS classes for tab labels.
     * @param  string  $activeClass  Base CSS classes for the active tab label.
     * @param  string  $labelDivClass  Base CSS classes for the container of the tab labels.
     * @param  string  $tabsClass  Base CSS classes for the main tabs container.
     * @param  string  $verticalTabsClass  CSS classes for vertical tabs container.
     * @param  string  $verticalLabelClass  CSS classes for vertical tab labels.
     * @param  string  $verticalActiveClass  CSS classes for active vertical tab labels.
     * @param  string  $verticalLabelDivClass  CSS classes for the container of vertical tab labels.
     * @param  string  $verticalContentClass  CSS classes for the content area of vertical tabs.
     * @param  string  $verticalRightActiveClass  CSS classes for active right-aligned vertical tab labels.
     * @param  string  $verticalRightLabelDivClass  CSS classes for the container of right-aligned vertical tab labels.
     * @param  string|null  $glass  Glass effect variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name or hex code for glass tint.
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     *
     * @return void
     */
    public function __construct(
        public ?string $id = null,
        public ?string $selected = null,
        public ?string $orientation = 'horizontal',
        public ?string $variant = null,
        public ?string $gap = null,
        public ?string $labelColorClasses = null,
        public ?string $activeColorClasses = null,
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

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Check if tabs should use vertical layout.
     *
     * @since 1.0.0
     */
    public function isVertical(): bool
    {
        return in_array($this->orientation, ['vertical-left', 'vertical-right']);
    }

    /**
     * Check if tabs should be positioned on the right side.
     *
     * @since 1.0.0
     */
    public function isVerticalRight(): bool
    {
        return 'vertical-right' === $this->orientation;
    }

    /**
     * Get the appropriate container classes based on orientation.
     *
     * @since 1.0.0
     */
    public function getTabsContainerClass(): string
    {
        return $this->isVertical() ? $this->verticalTabsClass : $this->tabsClass;
    }

    /**
     * Get the appropriate label div classes based on orientation.
     *
     * @since 1.0.0
     */
    public function getLabelDivClass(): string
    {
        $class = $this->labelDivClass; // Default to horizontal

        if ($this->isVerticalRight()) {
            $class = $this->verticalRightLabelDivClass;
        } elseif ($this->isVertical()) {
            $class = $this->verticalLabelDivClass;
        }

        // Add gap if specified
        if ($this->gap) {
            $class .= ' '.$this->gap;
        }

        // Remove border for rounded or pill variants
        if (in_array($this->variant, ['rounded', 'pill'])) {
            $class = Str::of($class)
                ->replace('border-b-[length:var(--border)] border-b-base-content/10', '')
                ->replace('border-r-[length:var(--border)] border-r-base-content/10', '')
                ->replace('border-l-[length:var(--border)] border-l-base-content/10', '')
                ->squish() // Removes extra spaces
                ->toString();
        }

        return $class;
    }

    /**
     * Get the appropriate label classes based on orientation and variants.
     *
     * @since 1.0.0
     */
    public function getFinalLabelClass(): string
    {
        $classes = $this->isVertical() ? $this->verticalLabelClass : $this->labelClass;

        if ('rounded' === $this->variant) {
            $classes = Str::of($classes)->replace('pb-1', 'py-2 px-4')->append(' rounded-lg');
        }

        if ('pill' === $this->variant) {
            $classes = Str::of($classes)->replace('pb-1', 'py-2 px-4')->append(' rounded-full');
        }

        if ($this->labelColorClasses) {
            $classes .= ' '.$this->labelColorClasses;
        }

        return $classes;
    }

    /**
     * Get the appropriate active classes based on orientation and custom colors.
     *
     * @since 1.0.0
     */
    public function getActiveClass(): string
    {
        if ($this->activeColorClasses) {
            return $this->activeColorClasses;
        }

        $class = $this->activeClass; // Default horizontal

        if ($this->isVerticalRight()) {
            $class = $this->verticalRightActiveClass;
        } elseif ($this->isVertical()) {
            $class = $this->verticalActiveClass;
        }

        // Remove border for rounded or pill variants as it interferes with the button style.
        if (in_array($this->variant, ['rounded', 'pill'])) {
            $class = Str::of($class)
                ->replace('border-b-[length:var(--border)] border-b-base-content/50', '')
                ->replace('border-r-[length:var(--border)] border-r-base-content/50', '')
                ->replace('border-l-[length:var(--border)] border-l-base-content/50', '')
                ->squish()
                ->toString();
        }

        return $class;
    }

    /**
     * Get the glass effect CSS classes.
     *
     * @since 2.0.0
     *
     * @return string Space-separated CSS classes.
     */
    public function glassClasses(): string
    {
        return GlassHelper::getClasses($this->glass, $this->glassTint, $this->glassTintOpacity);
    }

    /**
     * Get the glass effect inline styles including accessible text color.
     *
     * Combines custom tint color CSS variable with accessible text color
     * to ensure WCAG 2.0 AA compliance on tinted glass backgrounds.
     *
     * @since 2.0.0
     *
     * @return string Inline style string.
     */
    public function glassStyle(): string
    {
        return GlassHelper::getFullInlineStyle($this->glassTint);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.tabs');
    }
}
