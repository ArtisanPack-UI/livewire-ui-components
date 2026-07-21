<?php

declare(strict_types=1);
/**
 * Accordion
 *
 * This file contains the Accordion class for the ArtisanPack UI Livewire UI Components package.
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

/**
 * Accordion Class
 *
 * Provides functionality for the Accordion component.
 *
 * @since 1.0.0
 */
class Accordion extends BaseComponent
{
    public bool $usePlusMinus;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $id  Optional ID for the component.
     * @param  bool|null  $noJoin  Whether to disable the join styling.
     * @param  string  $uuid  Unique identifier for the component.
     * @param  mixed  $collapsePlusMinus  Whether to use plus/minus icon style.
     * @param  string|null  $glass  Glass effect variant ('frosted', 'liquid', 'transparent').
     * @param  string|null  $glassTint  Tailwind color name or hex code for glass tint.
     * @param  int|null  $glassTintOpacity  Tint opacity (10-100).
     *
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?bool $noJoin = false,
        public string $uuid = '',
        mixed $collapsePlusMinus = false,

        // Glass effect props
        public ?string $glass = null,
        public ?string $glassTint = null,
        public ?int $glassTintOpacity = null,
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.uniqid().$id;
        }
        $this->usePlusMinus = (true === $collapsePlusMinus || '' === $collapsePlusMinus);
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
        return view('livewire-ui-components::components.accordion');
    }
}
