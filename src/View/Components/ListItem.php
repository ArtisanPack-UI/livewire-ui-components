<?php

/**
 * ListItem Component
 *
 * The design-system ListItem (#58): a row with leading, title, description
 * and trailing parts.
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
 * ListItem Component Class
 *
 * Renders `.apui-list-item`: an li inside a list, a div on its own. With
 * href or interactive the title is a link or a button that covers the row.
 * Every attribute lands on the root, so wire:click on an interactive row
 * runs when its button is pressed.
 *
 * @since 1.0.0
 */
class ListItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x attributes that fill the row from an item, deprecated.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const LEGACY_ATTRIBUTES = ['item', 'value', 'sub-value', 'subValue', 'avatar', 'link', 'no-separator', 'noSeparator', 'no-hover', 'noHover', 'icon', 'icon-type', 'iconType', 'icon-status', 'iconStatus', 'icon-class', 'iconClass', 'no-icon', 'noIcon'];

    /**
     * Whether the row renders inside a list, as an li.
     *
     * @since 3.0.0
     */
    public bool $inList;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The main text. A `title` slot overrides it.
     * @param  string|null  $description  Secondary text. A `description` slot overrides it.
     * @param  string|null  $href  Makes the title a link covering the row.
     * @param  bool  $interactive  Without href, makes the title a button covering the row.
     * @param  bool  $selected  Marks the current row: data-selected, aria-current on its control.
     * @param  bool  $disabled  Dims the row and disables its control.
     * @param  string|null  $leadingIcon  Icon name in the leading part. A `leading` slot overrides it.
     * @param  string|null  $trailing  Short text at the end. A `trailing` slot overrides it.
     * @param  bool  $navigate  Adds wire:navigate to an internal href (default true).
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $href = null,
        public bool $interactive = false,
        public bool $selected = false,
        public bool $disabled = false,
        public ?string $leadingIcon = null,
        public ?string $trailing = null,
        public bool $navigate = true,
        public array $classes = [],
    ) {
        $this->inList = null !== ItemList::current();
    }

    /**
     * The safe href, or null.
     *
     * @since 3.0.0
     *
     * @return string|null The href.
     */
    public function linkHref(): ?string
    {
        $legacy = $this->attributes?->get('link');

        return SafeUrl::href($this->href ?? (is_string($legacy) ? $legacy : null));
    }

    /**
     * The title's element: a for a link, button for an interactive row,
     * span otherwise.
     *
     * @since 3.0.0
     *
     * @return string The tag.
     */
    public function titleTag(): string
    {
        if (null !== $this->linkHref()) {
            return 'a';
        }

        return $this->interactive ? 'button' : 'span';
    }

    /**
     * A value from the deprecated 2.x `item` attribute, at the key the
     * `value`, `sub-value` or `avatar` attribute names.
     *
     * @since 3.0.0
     *
     * @param  string  $attribute  value, sub-value or avatar.
     * @param  string|null  $default  The key when the attribute isn't set.
     *
     * @return string|null The value.
     */
    public function legacyValue(string $attribute, ?string $default): ?string
    {
        $item = $this->attributes?->get('item');
        if (null === $item) {
            return null;
        }

        $key   = $this->attributes->get($attribute) ?? $this->attributes->get(lcfirst(str_replace('-', '', ucwords($attribute, '-')))) ?? $default;
        $value = is_string($key) && '' !== $key ? data_get($item, $key) : null;

        return is_scalar($value) && '' !== (string) $value ? (string) $value : null;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.list-item');
    }
}
