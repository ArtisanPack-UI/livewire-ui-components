<?php

/**
 * Empty State Component
 *
 * The design-system EmptyState (#60): explains why there's nothing here and
 * what to do next.
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

/**
 * Empty State Component Class
 *
 * Renders `.apui-empty-state` with the icon tile, title (a heading at
 * `headingLevel`), description and actions parts.
 *
 * @since 3.0.0
 */
class EmptyState extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Each variant's default icon.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const VARIANT_ICONS = [
        'first-use'  => 'inbox',
        'no-results' => 'search-x',
        'cleared'    => 'circle-check',
    ];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  first-use, no-results or cleared.
     * @param  string  $size  sm or md.
     * @param  string  $align  center or start.
     * @param  string|null  $icon  An icon name; defaults to the variant's. The `icon` slot overrides it.
     * @param  string|null  $title  Why it's empty. A `title` slot overrides it.
     * @param  string|null  $description  What to do next. A `description` slot or the default slot overrides it.
     * @param  string|null  $actions  The buttons. An `actions` slot overrides it.
     * @param  int  $headingLevel  The title's heading level, 2 to 6.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $variant = 'first-use',
        public string $size = 'md',
        public string $align = 'center',
        public ?string $icon = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $actions = null,
        public int $headingLevel = 3,
        public array $classes = [],
    ) {}

    /**
     * The icon to render: the `icon` prop, else the variant's.
     *
     * @since 3.0.0
     */
    public function iconName(): string
    {
        return null !== $this->icon && '' !== $this->icon ? $this->icon : (self::VARIANT_ICONS[$this->variant] ?? 'inbox');
    }

    /**
     * The title's tag: h2 to h6.
     *
     * @since 3.0.0
     */
    public function headingTag(): string
    {
        return 'h'.min(6, max(2, $this->headingLevel));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.empty-state');
    }
}
