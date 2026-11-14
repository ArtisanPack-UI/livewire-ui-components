<?php

/**
 * DropdownMenuItem Component
 *
 * An action, checkbox or radio item in the design-system DropdownMenu (#50).
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * DropdownMenuItem Component Class
 *
 * Renders an `item` part (`x-apui:item`, or `x-apui:option-item` for
 * checkbox and radio items) with its icon, item-text, shortcut and, for
 * checkbox and radio items, item-indicator. Choosing it clicks it, so
 * `wire:click` runs from the keyboard too. It carries `wire:key`, and every
 * attribute lands on it.
 *
 * @since 3.0.0
 */
class DropdownMenuItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part: the menu's, so `classes` on the menu reaches
     * its items.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The menu this item renders in, if any.
     *
     * @since 3.0.0
     */
    private ?DropdownMenu $menu;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  The value `select` and `checked-change` report. Defaults to the label's text.
     * @param  string|null  $icon  An icon name from the registry, before the label.
     * @param  string|null  $shortcut  A keyboard shortcut shown at the end, such as `E`.
     * @param  string|null  $color  `error` for a danger item.
     * @param  bool  $disabled  Disables the item; the arrow keys skip it.
     * @param  string  $type  item, checkbox or radio.
     * @param  bool  $checked  Whether a checkbox or radio item is checked. It stays yours to set.
     * @param  string|null  $label  The label, when there's no default slot.
     */
    public function __construct(
        public ?string $value = null,
        public ?string $icon = null,
        public ?string $shortcut = null,
        public ?string $color = null,
        public bool $disabled = false,
        public string $type = 'item',
        public bool $checked = false,
        public ?string $label = null,
    ) {
        $this->menu    = DropdownMenu::current();
        $this->classes = $this->menu?->classes ?? [];
    }

    /**
     * Whether it's a checkbox or radio item.
     *
     * @since 3.0.0
     *
     * @return bool True for checkbox and radio.
     */
    public function isOption(): bool
    {
        return in_array($this->type, ['checkbox', 'radio'], true);
    }

    /**
     * The item's value: the `value` prop, else the label.
     *
     * @since 3.0.0
     *
     * @param  string  $text  The rendered label's text.
     *
     * @return string The value.
     */
    public function itemValue(string $text = ''): string
    {
        if (null !== $this->value && '' !== $this->value) {
            return $this->value;
        }

        return trim(html_entity_decode(strip_tags($this->label ?? $text), ENT_QUOTES | ENT_HTML5));
    }

    /**
     * The `wire:key`: the attribute, else the menu's id and the value.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The item's value.
     *
     * @return string The key.
     */
    public function itemKey(string $value): string
    {
        $key = $this->attributes->get('wire:key');
        if (is_string($key) && '' !== $key) {
            return $key;
        }

        return ($this->menu?->baseId() ?? 'apui-dropdown-menu').'-item-'.self::idSafe($value);
    }

    /**
     * The id the machine gives the item, `<menu id>/<value>`, so the server
     * renders the same one; whitespace in the value becomes `-` so the id is
     * valid. Null outside a menu.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The item's value.
     *
     * @return string|null The id.
     */
    public function itemId(string $value): ?string
    {
        return null === $this->menu ? null : $this->menu->baseId().'/'.self::idSafe($value);
    }

    /**
     * The expression for the item's `x-apui:*` parts: its value, type,
     * checked state and disabled state.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The item's value.
     *
     * @return HtmlString The expression, as a JavaScript object.
     */
    public function partArgs(string $value): HtmlString
    {
        $args = ['value' => $value];
        if ($this->isOption()) {
            $args = ['type' => $this->type, 'value' => $value, 'checked' => $this->checked];
        }
        if ($this->disabled) {
            $args['disabled'] = true;
        }

        return new HtmlString(e((string) json_encode($args, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.dropdown-menu-item');
    }

    /**
     * A value made safe for an id: whitespace runs become `-`.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The value.
     *
     * @return string The id-safe value.
     */
    private static function idSafe(string $value): string
    {
        $safe = (string) preg_replace('/\s+/u', '-', trim($value));

        return '' === $safe ? 'item' : $safe;
    }
}
