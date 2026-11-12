<?php

/**
 * TimelineItem Component
 *
 * One event of the design-system Timeline (#61).
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * TimelineItem Component Class
 *
 * Renders a Timeline's item part (`li`): the marker (icon or dot, with
 * data-color), the head (title or the default slot, then the time), the body
 * (description) and the card (the `card` slot or prop). Place it inside
 * `<x-artisanpack-timeline>`. Every attribute lands on the item.
 *
 * 2.x props still work: `subtitle` as `time`. 2.x `first`, `last` and
 * `pending` are ignored: the list draws its own line.
 *
 * @since 1.0.0
 */
class TimelineItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x props read from the attributes, then left off the item.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = ['subtitle', 'first', 'last', 'pending'];

    /**
     * The parent's extra classes per part.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $title  What happened. The default slot replaces it.
     * @param  string|null  $time  When, as shown ("2h ago").
     * @param  string|null  $datetime  When, machine-readable (ISO 8601), for the time element.
     * @param  string|null  $icon  A registry icon name for the marker.
     * @param  string|null  $color  Tints the marker.
     * @param  string|null  $description  Muted detail under the head.
     * @param  string|null  $card  A sunken box under the head. The `card` slot replaces it.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $time = null,
        public ?string $datetime = null,
        public ?string $icon = null,
        public ?string $color = null,
        public ?string $description = null,
        public ?string $card = null,
    ) {
        $this->classes = Timeline::current()?->classes ?? [];
    }

    /**
     * The marker's color, or null for the neutral default.
     *
     * @since 3.0.0
     */
    public function colorValue(): ?string
    {
        return in_array($this->color, Stat::COLORS, true) ? $this->color : null;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.timeline-item');
    }
}
