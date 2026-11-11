<?php

/**
 * Link Component
 *
 * The design-system Link (#56): an anchor, or a button styled as a link
 * when there's no href.
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
 * Link Component Class
 *
 * Renders `.apui-link` with `data-tone`, `data-standalone` and the icon and
 * iconEnd parts. Every attribute lands on the root, so `wire:click` on a
 * link without href works on the button it renders.
 *
 * @since 1.0.0
 */
class Link extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The Breadcrumbs this link is a level of, when it's composed inside one
     * (2.x usage). It then registers with the trail instead of rendering.
     *
     * @since 3.0.0
     */
    public ?Breadcrumbs $trail;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $href  The destination. Without one (or with an unsafe scheme), a button renders instead.
     * @param  string  $tone  primary or muted. Emitted as data-tone.
     * @param  bool  $standalone  A link on its own line: medium weight, underlined on hover.
     * @param  bool  $external  Opens in a new tab and says so.
     * @param  string|null  $externalLabel  The visually hidden "opens in a new tab" text. Defaults to __( '(opens in a new tab)' ).
     * @param  string|null  $leadingIcon  Icon name before the text. An `icon` slot overrides it.
     * @param  string|null  $trailingIcon  Icon name after the text. An `iconEnd` slot overrides it.
     * @param  bool  $navigate  Adds wire:navigate to an internal link (default true). Off-site, `#`, `mailto:` and new-tab links never get it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $href = null,
        public string $tone = 'primary',
        public bool $standalone = false,
        public bool $external = false,
        public ?string $externalLabel = null,
        public ?string $leadingIcon = null,
        public ?string $trailingIcon = null,
        public bool $navigate = true,
        public array $classes = [],
    ) {
        $this->externalLabel ??= __('(opens in a new tab)');
        $this->trail         = Breadcrumbs::current();
    }

    /**
     * The href: null when it's empty or uses an unsafe scheme, which renders
     * the button form.
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
     * The tone, falling back to primary for unknown values.
     *
     * @since 3.0.0
     *
     * @return string primary or muted.
     */
    public function toneValue(): string
    {
        return in_array($this->tone, ['primary', 'muted'], true) ? $this->tone : 'primary';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.link');
    }
}
