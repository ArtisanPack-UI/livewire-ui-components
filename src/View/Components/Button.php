<?php

/**
 * Button Component
 *
 * The design-system Button (#44): a native button, or an anchor with href.
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
 * Button Component Class
 *
 * Renders `.apui-button` with the icon, label, iconEnd and spinner parts
 * from the design-system manifest. Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Button extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  Visual treatment: solid, soft, outline, ghost or link.
     * @param  string  $color  Semantic color: primary, secondary, accent, neutral, success, warning, error or info.
     * @param  string  $size  Control height: xs, sm, md, lg or xl.
     * @param  bool  $iconOnly  Renders only the icon. Needs an aria-label.
     * @param  bool  $loading  Shows the spinner and sets aria-busy.
     * @param  bool  $disabled  Disables the button, or the link when href is set.
     * @param  string|null  $href  Renders an anchor with the same styles.
     * @param  string|null  $leadingIcon  Icon name before the label. The `icon` slot overrides it.
     * @param  string|null  $trailingIcon  Icon name after the label. The `iconEnd` slot overrides it.
     * @param  string  $type  The native button type: button, submit or reset.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $variant = 'solid',
        public string $color = 'primary',
        public string $size = 'md',
        public bool $iconOnly = false,
        public bool $loading = false,
        public bool $disabled = false,
        public ?string $href = null,
        public ?string $leadingIcon = null,
        public ?string $trailingIcon = null,
        public string $type = 'button',
        public array $classes = [],
    ) {}

    /**
     * Whether the button renders as an anchor.
     *
     * @since 3.0.0
     *
     * @return bool True when href is set and safe.
     */
    public function isLink(): bool
    {
        return null !== $this->linkHref();
    }

    /**
     * The href to render: null when it's empty or uses an unsafe scheme
     * (javascript:, vbscript:, data:), which renders a button instead.
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
     * Whether a link is inert: disabled or loading. It renders without href
     * and drops its click handlers.
     *
     * @since 3.0.0
     *
     * @return bool True when the link can't be followed.
     */
    public function isInertLink(): bool
    {
        return $this->isLink() && ($this->disabled || $this->loading);
    }

    /**
     * The root's attributes. An inert link loses `wire:click*`, `@click*`,
     * `x-on:click*` and inline `on*` handlers such as `onclick`, so it runs
     * no handlers.
     *
     * @since 3.0.0
     *
     * @return \Illuminate\View\ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): \Illuminate\View\ComponentAttributeBag
    {
        if (! $this->isInertLink()) {
            return $this->attributes;
        }

        return $this->attributes->filter(
            static fn (mixed $value, string $key): bool => 1 !== preg_match('/^(wire:click|@click|x-on:click|on[a-z]+$)/i', $key),
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.button');
    }
}
