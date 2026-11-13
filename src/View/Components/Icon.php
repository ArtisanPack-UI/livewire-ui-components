<?php

declare(strict_types=1);
/**
 * Icon
 *
 * This file contains the Icon class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * Icon Class
 *
 * The design-system Icon (#65): `span.apui-icon` around one glyph from the
 * icon registry (D-017), at sm, md, lg or xl, plain or on a soft-colored tile
 * (`variant="tile"`, which adds the `apui-icon-tile` class). The default slot
 * draws your own `<svg>` instead of the name. Unlabeled icons are decorative
 * (aria-hidden); `label` makes the root role=img with aria-label. Directional
 * icons get `data-mirror` and flip under RTL; `mirror` overrides that.
 *
 * Names outside the registry fall back to blade-icons, so the 2.x heroicon
 * (`o-plus`) and Font Awesome (`fas.user`) names keep rendering.
 *
 * 3.0 breaking change: `label` was visible text beside the icon; it is now
 * the accessible name. Put visible text next to the icon yourself.
 *
 * @since 1.0.0
 */
class Icon extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants the markup contract knows.
     *
     * @since 3.0.0
     */
    public const VARIANTS = ['plain', 'tile'];

    /**
     * The sizes the markup contract knows.
     *
     * @since 3.0.0
     */
    public const SIZES = ['sm', 'md', 'lg', 'xl'];

    /**
     * The colors the markup contract knows.
     *
     * @since 3.0.0
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $name  A registered icon name, or a 2.x blade-icons name.
     * @param  string  $variant  plain or tile.
     * @param  string  $size  sm, md, lg or xl.
     * @param  string|null  $color  The tone: a plain icon's color, a tile's soft fill.
     * @param  string|null  $label  The accessible name. Without it the icon is decorative.
     * @param  bool|null  $mirror  Flips it under RTL. Null follows the registry's directional names.
     * @param  array  $classes  Extra classes per part: root, svg.
     */
    public function __construct(
        public ?string $name = null,
        public string $variant = 'plain',
        public string $size = 'md',
        public ?string $color = null,
        public ?string $label = null,
        public ?bool $mirror = null,
        public array $classes = [],
    ) {
        $this->variant = in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'plain';
        $this->size    = in_array($this->size, self::SIZES, true) ? $this->size : 'md';
        $this->color   = in_array($this->color, self::COLORS, true) ? $this->color : null;
    }

    /**
     * The blade-icons name for a 2.x name, after the iconAlias filter
     * (`o-user` => `heroicon-o-user`, `fa.solid.user` => `fa-solid-user`).
     *
     * @since 1.0.0
     *
     * @return string|Stringable The name.
     */
    public function icon(): string|Stringable
    {
        $name = Str::of((string) applyFilters('ap.livewireUiComponents.iconAlias', (string) $this->name));

        return $name->contains('.') ? $name->replace('.', '-') : "heroicon-{$name}";
    }

    /**
     * The root's classes: `apui-icon`, plus `apui-icon-tile` for a tile.
     *
     * @since 3.0.0
     *
     * @return list<string> The classes.
     */
    public function iconClasses(): array
    {
        $classes = $this->rootClasses('apui-icon');
        if ('tile' === $this->variant) {
            array_splice($classes, 1, 0, ['apui-icon-tile']);
        }

        return $classes;
    }

    /**
     * Whether the glyph flips under RTL: `mirror`, else a directional name.
     *
     * @since 3.0.0
     *
     * @param  bool  $hasSlot  Whether the default slot draws the glyph.
     *
     * @return bool True to add `data-mirror`.
     */
    public function mirrored(bool $hasSlot = false): bool
    {
        if (null !== $this->mirror) {
            return $this->mirror;
        }

        return ! $hasSlot && IconRegistry::isDirectional($this->name);
    }

    /**
     * The `svg` part for the name: the registry's glyph, else blade-icons,
     * carrying `data-scope="icon" data-part="svg"`. Empty for an unknown name.
     *
     * @since 3.0.0
     *
     * @return HtmlString The markup.
     */
    public function svg(): HtmlString
    {
        $name = (string) $this->name;
        if ('' === $name) {
            return new HtmlString('');
        }

        $extra = ['data-scope' => 'icon', 'data-part' => 'svg'];
        $class = $this->classes['svg'] ?? null;
        if (is_string($class) && '' !== trim($class)) {
            $extra['class'] = trim($class);
        }

        $svg = IconRegistry::svg($name, $extra);
        if ('' !== $svg) {
            return new HtmlString($svg);
        }

        $fallback = IconRegistry::render($name);
        if ('' === $fallback) {
            return new HtmlString('');
        }

        // The svg part's class (classes.svg) joins any class the icon set gives.
        $class = $extra['class'] ?? null;
        unset($extra['class']);
        if (null !== $class) {
            $merged   = 0;
            $fallback = (string) preg_replace_callback(
                '/^(\s*<svg\b[^>]*?\sclass=)(["\'])(.*?)\2/s',
                static fn (array $match): string => $match[1].$match[2].trim($match[3].' '.e($class)).$match[2],
                $fallback,
                1,
                $merged,
            );
            if (0 === $merged) {
                $extra['class'] = $class;
            }
        }

        $attributes = '';
        foreach ($extra as $key => $value) {
            $attributes .= ' '.$key.'="'.e($value).'"';
        }

        return new HtmlString((string) preg_replace('/^\s*<svg\b/', '<svg'.$attributes, $fallback, 1));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     *
     * @return Closure|string|View The view.
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.icon');
    }
}
