<?php

declare(strict_types=1);
/**
 * Drawer
 *
 * This file contains the Drawer class for the ArtisanPack UI Livewire UI Components package.
 *
 * @since      1.0.0
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 *
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @author     Jacob Martella
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Livewire\WireDirective;

/**
 * Drawer Class
 *
 * Provides functionality for the Drawer component.
 *
 * @since 1.0.0
 */
class Drawer extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?bool $right = false,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $separator = false,
        public ?bool $withCloseButton = false,
        public ?bool $closeOnEscape = false,
        public ?bool $withoutTrapFocus = false,
        public ?string $openOn = null,
        public ?string $closeOn = null,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,

        // Slots
        public ?string $actions = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function id(): string
    {
        if ($this->id) {
            return $this->id;
        }

        $wireModel = $this->attributes?->wire('model')->value();
        if ($wireModel && is_string($wireModel)) {
            return $wireModel;
        }

        return $this->uuid;
    }

    public function modelName(): WireDirective
    {
        return $this->attributes->wire('model');
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
        return view('livewire-ui-components::components.drawer');
    }
}
