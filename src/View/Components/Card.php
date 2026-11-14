<?php

/**
 * Card Component
 *
 * The design-system Card (#48): a surface with optional media, header, body
 * and footer.
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

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Card Component Class
 *
 * Renders `.apui-card` with the media, header (title, description,
 * actions), body and footer parts. Named slots override the matching
 * props. Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Card extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $surface  The surface: default, raised, sunken or glass.
     * @param  bool  $interactive  Adds the hover edge. Set by href.
     * @param  string|null  $href  Makes the title a link whose hit area covers the card.
     * @param  string|null  $title  The heading. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text under the title. A `description` slot overrides it.
     * @param  string|null  $actions  Controls at the end of the header. An `actions` slot overrides it.
     * @param  string|null  $footer  The footer row. A `footer` slot overrides it.
     * @param  string|null  $media  An image URL shown edge to edge at the top. A `media` slot overrides it.
     * @param  string  $mediaAlt  Alternative text for media. Empty marks it decorative.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $surface = 'default',
        public bool $interactive = false,
        public ?string $href = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $actions = null,
        public ?string $footer = null,
        public ?string $media = null,
        public string $mediaAlt = '',
        public array $classes = [],
    ) {}

    /**
     * The title link's href: null when it's empty or uses an unsafe scheme
     * (javascript:, vbscript:, data:). The view also needs a title to link.
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
     * The media src: null when it's empty or uses javascript: or vbscript:.
     * data: images stay allowed.
     *
     * @since 3.0.0
     *
     * @return string|null The src.
     */
    public function mediaSrc(): ?string
    {
        return SafeUrl::media($this->media);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.card');
    }
}
