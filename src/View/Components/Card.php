<?php

declare(strict_types=1);
/**
 * Card
 *
 * This file contains the Card class for the ArtisanPack UI Livewire UI Components package.
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
 * Card Class
 *
 * Provides functionality for the Card component.
 *
 * @since 1.0.0
 */
class Card extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $separator = false,
        public ?bool $shadow = false,
        public string|bool|null $progressIndicator = null,
        public ?string $figurePosition = 'top',

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,

        // Slots
        public mixed $menu = null,
        public mixed $actions = null,
        public mixed $figure = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;

        // Validate figure position
        $this->figurePosition = in_array($this->figurePosition, ['top', 'bottom', 'left', 'right'])
            ? $this->figurePosition
            : 'top';
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

    public function progressTarget(): ?string
    {
        if (true === $this->progressIndicator) {
            return $this->attributes->whereStartsWith('progress-indicator')->first();
        }

        return $this->progressIndicator;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.card');
    }
}
