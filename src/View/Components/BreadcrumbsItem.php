<?php

/**
 * BreadcrumbsItem Component
 *
 * One composed level of the design-system Breadcrumbs (#56).
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * BreadcrumbsItem Component Class
 *
 * Registers a level with its parent Breadcrumbs, which renders it in order:
 * a link with `href`, the current page when it's last. The default slot
 * overrides the label; attributes (`wire:click`) land on the link.
 *
 * @since 3.0.0
 */
class BreadcrumbsItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The Breadcrumbs this level renders in, if any.
     *
     * @since 3.0.0
     */
    public ?Breadcrumbs $trail;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $label  The level's name. The default slot overrides it.
     * @param  string|null  $href  Where it links. Ignored on the last level.
     * @param  string|null  $icon  An icon name before the label.
     * @param  bool  $hideLabel  Keeps the label for screen readers only.
     */
    public function __construct(
        public ?string $label = null,
        public ?string $href = null,
        public ?string $icon = null,
        public bool $hideLabel = false,
    ) {
        $this->trail = Breadcrumbs::current();
    }

    /**
     * The href: null when it's empty or uses an unsafe scheme.
     *
     * @since 3.0.0
     *
     * @return string|null The href.
     */
    public function linkHref(): ?string
    {
        return SafeUrl::href($this->href);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.breadcrumbs-item');
    }
}
