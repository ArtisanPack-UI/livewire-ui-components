<?php

/**
 * MenuItem Component
 *
 * One item of the design-system navigation Menu (#56): a link, or a button
 * without href.
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

use ArtisanPack\LivewireUiComponents\Support\NavigationContext;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Throwable;

/**
 * MenuItem Component Class
 *
 * A child of `menu` or `menu-sub`: an `<a>` with `href` (aria-current=page
 * when it's the current page), or a `<button>` without one, for
 * `wire:click`. The label is the slot, or `label`. Every attribute lands on
 * the link or button. Internal links get `wire:navigate` unless
 * `no-wire-navigate` or `:navigate="false"`.
 *
 * @since 1.0.0
 */
class MenuItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Menu.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The Menu this item belongs to.
     *
     * @since 3.0.0
     */
    private ?Menu $menu;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $label  The label. The slot overrides it.
     * @param  string|null  $href  The link's URL. Without it, the item is a button.
     * @param  string|null  $route  A route name, for href.
     * @param  string|null  $icon  An icon name before the label.
     * @param  int|string|null  $badge  A count or status at the end.
     * @param  bool|null  $current  Whether it's the current page. Unset, `activate-by-route` decides.
     * @param  bool  $disabled  Disables the item: no href, aria-disabled and out of the Tab order.
     * @param  bool  $external  Opens the link in a new tab, without wire:navigate.
     * @param  bool|null  $navigate  Adds wire:navigate to internal links. Default true.
     * @param  bool  $exact  With activate-by-route, match the URL exactly rather than as a prefix.
     * @param  bool|null  $activateByRoute  Marks the item current when its href matches the URL. Defaults to the Menu's.
     * @param  bool  $hidden  Renders nothing.
     * @param  string|null  $title  Deprecated: use `label` or the slot.
     * @param  string|null  $link  Deprecated: use `href`.
     * @param  bool|null  $active  Deprecated: use `current`.
     * @param  bool  $noWireNavigate  Deprecated: use `:navigate="false"`.
     */
    public function __construct(
        public ?string $label = null,
        public ?string $href = null,
        public ?string $route = null,
        public ?string $icon = null,
        public string|int|null $badge = null,
        public ?bool $current = null,
        public bool $disabled = false,
        public bool $external = false,
        public ?bool $navigate = null,
        public bool $exact = false,
        public ?bool $activateByRoute = null,
        public bool $hidden = false,
        public ?string $title = null,
        public ?string $link = null,
        public ?bool $active = null,
        public bool $noWireNavigate = false,
    ) {
        $context       = app(NavigationContext::class);
        $this->menu    = $context->nearest(Menu::class);
        $this->classes = $this->menu?->classes ?? [];

        if (! $this->hidden && $this->isCurrent()) {
            $context->nearest(MenuSub::class)?->holdsCurrent();
        }
    }

    /**
     * The link's URL: `href`, the deprecated `link`, or the `route`'s URL,
     * when its scheme is allowed (SafeUrl).
     *
     * @since 3.0.0
     *
     * @return string|null The URL, or null when it's missing or not allowed.
     */
    public function url(): ?string
    {
        return SafeUrl::href($this->requestedUrl());
    }

    /**
     * Whether a URL was given but isn't allowed (`javascript:`, `data:`, …),
     * so the item renders as a plain non-link.
     *
     * @since 3.0.0
     *
     * @return bool True when the URL was dropped.
     */
    public function hasUnsafeUrl(): bool
    {
        return null !== $this->requestedUrl() && null === $this->url();
    }

    /**
     * Whether the item is the current page: `current` (or the deprecated
     * `active`), else, with activate-by-route, whether its URL matches.
     *
     * @since 3.0.0
     *
     * @return bool True when current.
     */
    public function isCurrent(): bool
    {
        $explicit = $this->current ?? $this->active;
        if (null !== $explicit) {
            return $explicit;
        }

        $byRoute = $this->activateByRoute ?? $this->menu?->activatesByRoute() ?? false;

        return $byRoute && ! $this->disabled && $this->matchesRequest();
    }

    /**
     * Whether internal links get `wire:navigate`.
     *
     * @since 3.0.0
     *
     * @return bool True when they do.
     */
    public function navigates(): bool
    {
        return ! $this->external && ! $this->noWireNavigate && false !== $this->navigate;
    }

    /**
     * The label as given by `label` or the deprecated `title`.
     *
     * @since 3.0.0
     *
     * @return string|null The label.
     */
    public function labelText(): ?string
    {
        return $this->label ?? $this->title;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.menu-item');
    }

    /**
     * The URL as given: `href`, the deprecated `link`, or the `route`'s URL.
     *
     * @since 3.0.0
     *
     * @return string|null The URL, not yet checked.
     */
    private function requestedUrl(): ?string
    {
        foreach ([$this->href, $this->link] as $candidate) {
            if (null !== $candidate && '' !== $candidate) {
                return $candidate;
            }
        }

        if (null !== $this->route && '' !== $this->route) {
            try {
                return route($this->route);
            } catch (Throwable) {
                return null;
            }
        }

        return null;
    }

    /**
     * Whether the URL matches the request: the route, else the path, exactly
     * or as a prefix.
     *
     * @since 3.0.0
     *
     * @return bool True when it matches.
     */
    private function matchesRequest(): bool
    {
        if (null !== $this->route && '' !== $this->route && Route::has($this->route)) {
            return request()->routeIs($this->exact ? $this->route : [$this->route, $this->route.'.*']);
        }

        $url = $this->url();
        if (null === $url || str_starts_with($url, '#')) {
            return false;
        }

        $path    = '/'.trim((string) parse_url($url, PHP_URL_PATH), '/');
        $current = '/'.trim(request()->path(), '/');

        if ($this->exact || '/' === $path) {
            return $path === $current;
        }

        return $path === $current || Str::startsWith($current, $path.'/');
    }
}
