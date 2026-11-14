<?php

/**
 * Navbar Component
 *
 * The design-system Navbar (#56): the top bar, with a brand, primary links
 * or a search, and actions. Replaces nav.
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

use ArtisanPack\LivewireUiComponents\Support\NavigationContext;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Navbar Component Class
 *
 * Renders the `.apui-navbar` header: the brand, the links in a nav
 * landmark (`navbar-item` children in the default slot, or `links` as
 * data, rendered first), the search and the actions. Named slots override
 * the `brand`, `search` and `actions` props. Every attribute lands on the
 * header.
 *
 * @since 3.0.0
 */
class Navbar extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $layout  bar (full width) or island (rounded).
     * @param  string  $surface  default or glass.
     * @param  bool  $sticky  Sticks to the top of the scrolling container.
     * @param  mixed  $brand  The brand: a string, or the `brand` slot.
     * @param  array<int, array<string, mixed>>  $links  The links as data: `{ label, href, current }`.
     * @param  mixed  $search  A search field: a string, or the `search` slot.
     * @param  mixed  $actions  Controls at the end: a string, or the `actions` slot.
     * @param  string|null  $label  Names the links' nav landmark. Defaults to "Main", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $layout = 'bar',
        public string $surface = 'default',
        public bool $sticky = true,
        public mixed $brand = null,
        public array $links = [],
        public mixed $search = null,
        public mixed $actions = null,
        public ?string $label = null,
        public array $classes = [],
    ) {
        $this->layout  = in_array($this->layout, ['bar', 'island'], true) ? $this->layout : 'bar';
        $this->surface = in_array($this->surface, ['default', 'glass'], true) ? $this->surface : 'default';

        app(NavigationContext::class)->enter($this);
    }

    /**
     * The Navbar whose links are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The navbar.
     */
    public static function current(): ?self
    {
        return app(NavigationContext::class)->nearest(self::class);
    }

    /**
     * Leaves the context once the view has rendered its links.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(NavigationContext::class)->leave($this);
    }

    /**
     * The links' nav landmark name.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function linksLabel(): string
    {
        return $this->filled($this->label) ? (string) $this->label : __('Main');
    }

    /**
     * The `links` prop as entries the view renders.
     *
     * @since 3.0.0
     *
     * @return list<array{label: string, href: string|null, current: bool}> The entries.
     */
    public function entries(): array
    {
        $entries = [];
        foreach ($this->links as $link) {
            if (! is_array($link)) {
                continue;
            }

            $entries[] = [
                'label'   => (string) ($link['label'] ?? ''),
                'href'    => isset($link['href']) ? SafeUrl::href((string) $link['href']) : null,
                'current' => (bool) ($link['current'] ?? false),
            ];
        }

        return $entries;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.navbar');
    }
}
