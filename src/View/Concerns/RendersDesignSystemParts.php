<?php

/**
 * Shared helpers for the design-system Blade components.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Concerns;

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentSlot;

/**
 * Part classes, slot checks and icons for the components that render the
 * design-system markup contract (`.apui-*` roots, `data-scope`/`data-part`
 * parts). The using class declares `public array $classes`.
 *
 * @since 3.0.0
 */
trait RendersDesignSystemParts
{
    /**
     * The root's classes: the root class, then `classes.root`, through the
     * `ap.livewireUiComponents.componentClasses` filter. The `class`
     * attribute merges after them.
     *
     * @since 3.0.0
     *
     * @param  string  $rootClass  The root class, such as `apui-button`.
     *
     * @return list<string> The classes.
     */
    public function rootClasses(string $rootClass): array
    {
        return $this->filteredClasses([$rootClass, $this->classes['root'] ?? null]);
    }

    /**
     * The classes for a child component's own element, such as a menu item
     * or a tab panel: `classes[$part]` and any extra classes, through the
     * `ap.livewireUiComponents.componentClasses` filter.
     *
     * @since 3.0.0
     *
     * @param  string  $part  The `classes` key, such as `item`.
     * @param  array<int, mixed>  $extra  More classes for the element.
     *
     * @return list<string> The classes.
     */
    public function partClasses(string $part, array $extra = []): array
    {
        return $this->filteredClasses([$this->classes[$part] ?? null, ...$extra]);
    }

    /**
     * A part's ` class="…"` attribute from `classes`, or nothing.
     *
     * @since 3.0.0
     *
     * @param  string  $part  The part name, such as `label`.
     *
     * @return HtmlString The attribute, with a leading space.
     */
    public function partClass(string $part): HtmlString
    {
        $class = $this->classes[$part] ?? null;

        if (! is_string($class) || '' === trim($class)) {
            return new HtmlString('');
        }

        return new HtmlString(' class="'.e(trim($class)).'"');
    }

    /**
     * Whether a prop or slot has content: a non-empty string, or a slot with
     * content.
     *
     * @since 3.0.0
     *
     * @param  mixed  $value  A prop value or a slot.
     *
     * @return bool True when there is something to render.
     */
    public function filled(mixed $value): bool
    {
        if ($value instanceof ComponentSlot) {
            return $value->hasActualContent();
        }

        if ($value instanceof Htmlable) {
            return '' !== trim($value->toHtml());
        }

        return null !== $value && false !== $value && '' !== $value;
    }

    /**
     * A prop or slot as markup: a slot's HTML, trimmed so no stray text nodes
     * sit inside the part, or an escaped string.
     *
     * @since 3.0.0
     *
     * @param  mixed  $value  A prop value or a slot.
     *
     * @return HtmlString The markup.
     */
    public function content(mixed $value): HtmlString
    {
        if ($value instanceof Htmlable) {
            return new HtmlString(trim($value->toHtml()));
        }

        return new HtmlString(e((string) $value));
    }

    /**
     * The `<svg>` for an icon name, through the icon registry (D-017).
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The icon name.
     *
     * @return HtmlString The markup, empty when the name is unknown.
     */
    public function iconSvg(?string $name): HtmlString
    {
        return new HtmlString(IconRegistry::render($name));
    }

    /**
     * Whether an icon name mirrors under RTL.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The icon name.
     *
     * @return bool True for arrow and chevron left/right.
     */
    public function mirrors(?string $name): bool
    {
        return IconRegistry::isDirectional($name);
    }
}
