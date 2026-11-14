<?php

/**
 * Timeline Component
 *
 * The design-system Timeline (#61): events in order, as an ordered list.
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
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Timeline Component Class
 *
 * Renders `ol.apui-timeline` with data-variant (icon or dot). Its events are
 * `timeline-item` children, or the same events from `items`, rendered
 * before them (D-023). Items read the parent's `classes`. Every attribute
 * lands on the root.
 *
 * @since 3.0.0
 */
class Timeline extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants the markup contract knows.
     *
     * @since 3.0.0
     */
    public const VARIANTS = ['icon', 'dot'];

    /**
     * Create a new component instance.
     *
     * @since 3.0.0
     *
     * @param  array<int, array<string, mixed>>  $items  The events as data.
     * @param  string  $variant  icon or dot markers.
     * @param  string|null  $label  The list's accessible name.
     * @param  array<string, string>  $classes  Extra classes per part, its items' included.
     */
    public function __construct(
        public array $items = [],
        public string $variant = 'icon',
        public ?string $label = null,
        public array $classes = [],
    ) {
        app(NavigationContext::class)->enter($this);
    }

    /**
     * The Timeline whose children are rendering, if any.
     *
     * @since 3.0.0
     */
    public static function current(): ?self
    {
        $timeline = app(NavigationContext::class)->nearest(self::class);

        return $timeline instanceof self ? $timeline : null;
    }

    /**
     * Ends the children's render: later items no longer see this Timeline.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(NavigationContext::class)->leave($this);
    }

    /**
     * The variant, falling back to `icon`.
     *
     * @since 3.0.0
     */
    public function variantValue(): string
    {
        return in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'icon';
    }

    /**
     * The data items, normalized to timeline-item props.
     *
     * @since 3.0.0
     *
     * @return list<array{title: string|null, time: string|null, datetime: string|null, icon: string|null, color: string|null, description: string|null, card: string|null}>
     */
    public function entries(): array
    {
        $entries = [];
        foreach (array_values(array_filter($this->items, 'is_array')) as $item) {
            $entry = [];
            foreach (['title', 'time', 'datetime', 'icon', 'color', 'description', 'card'] as $key) {
                $value         = $item[$key] ?? null;
                $entry[$key]   = is_scalar($value) && '' !== (string) $value ? (string) $value : null;
            }
            $entries[] = $entry;
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
        return view('livewire-ui-components::components.timeline');
    }
}
