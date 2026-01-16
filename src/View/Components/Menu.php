<?php

declare(strict_types=1);
/**
 * Menu
 *
 * This file contains the Menu class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;

/**
 * Menu Class
 *
 * Provides functionality for the Menu component.
 *
 * @since 1.0.0
 */
class Menu extends Component
{
    public string $uuid;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $id  Optional ID for the component.
     * @param  string|null  $title  Optional title for the menu.
     * @param  string|null  $icon  Optional icon name.
     * @param  string|null  $iconClasses  CSS classes for the icon.
     * @param  bool|null  $separator  Whether to show a separator.
     * @param  bool|null  $activateByRoute  Whether to activate by route.
     * @param  string|null  $activeBgColor  Background color for active state.
     * @param  string|null  $glass  Glass effect variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name or hex code for glass tint.
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $iconClasses = 'w-4 h-4',
        public ?bool $separator = false,
        public ?bool $activateByRoute = false,
        public ?string $activeBgColor = 'bg-base-300',

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
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

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.menu');
    }
}
