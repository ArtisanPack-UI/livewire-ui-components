<?php

declare(strict_types=1);
/**
 * Collapse
 *
 * This file contains the Collapse class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Collapse Class
 *
 * Provides functionality for the Collapse component.
 *
 * @since 1.0.0
 */
class Collapse extends Component
{
    public string $uuid;

    public bool $collapsePlusMinus = false;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $id  Optional ID for the component.
     * @param  string|null  $name  Optional name for the collapse.
     * @param  bool|null  $separator  Whether to show a separator.
     * @param  bool|null  $noIcon  Whether to hide the collapse icon.
     * @param  mixed  $collapsePlusMinus  Whether to use plus/minus icon style.
     * @param  string|null  $glass  Glass effect variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name or hex code for glass tint.
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     * @param  mixed  $heading  Slot for the collapse heading.
     * @param  mixed  $content  Slot for the collapse content.
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?bool $separator = false,
        public ?bool $noIcon = false,
        mixed $collapsePlusMinus = false,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,

        // Slots
        public mixed $heading = null,
        public mixed $content = null,
    ) {
        $this->uuid              = 'artisanpack'.uniqid().$id;
        $this->collapsePlusMinus = (true === $collapsePlusMinus || '' === $collapsePlusMinus);
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

    public function render(): View
    {
        return view('livewire-ui-components::components.collapse');
    }
}
