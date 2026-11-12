<?php

/**
 * Loading Component
 *
 * The design-system Loading (#60): a spinner or dots that show work is in
 * progress, inline or as an overlay.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Loading Component Class
 *
 * Renders `.apui-loading` (role=status) with the indicator and label parts.
 * The default slot is visible text; without it the label is visually hidden.
 * The root waits `delay` milliseconds (inline `--apui-_delay`) before showing.
 *
 * @since 1.0.0
 */
class Loading extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $variant  spinner or dots.
     * @param  string|null  $size  xs, sm, md, lg or xl.
     * @param  string|null  $color  Tints the indicator.
     * @param  string|null  $label  What's loading, for screen readers. Defaults to "Loading", translated.
     * @param  bool  $overlay  Covers the nearest positioned ancestor.
     * @param  int  $delay  Milliseconds to wait before showing.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $variant = 'spinner',
        public ?string $size = 'md',
        public ?string $color = 'primary',
        public ?string $label = null,
        public bool $overlay = false,
        public int $delay = 300,
        public array $classes = [],
    ) {
        // A null (such as a bound optional color) falls back to the default.
        $this->variant ??= 'spinner';
        $this->size ??= 'md';
        $this->color ??= 'primary';
    }

    /**
     * The visually hidden label: `label`, else "Loading", translated.
     *
     * @since 3.0.0
     *
     * @return string The label.
     */
    public function labelText(): string
    {
        return null !== $this->label && '' !== $this->label ? $this->label : __('Loading');
    }

    /**
     * The inline style that delays the root.
     *
     * @since 3.0.0
     *
     * @return string The declaration.
     */
    public function delayStyle(): string
    {
        return '--apui-_delay: '.max(0, $this->delay).'ms';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.loading');
    }
}
