<?php

/**
 * Accordion Item Component
 *
 * One section of the design-system Accordion (#58).
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
 * Accordion Item Component Class
 *
 * A child of `accordion`: the item, its title (a heading holding the
 * trigger and its chevron) and its panel, from the slot. Its ids and open
 * state come from the Accordion. The title is a prop or a `title` slot.
 * Every attribute lands on the item.
 *
 * @since 3.0.0
 */
class AccordionItem extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Accordion.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The Accordion this item belongs to.
     *
     * @since 3.0.0
     */
    private ?Accordion $accordion;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  The item's value, unique in the Accordion. Defaults to its title.
     * @param  mixed  $title  The trigger's text: a string, or the `title` slot.
     * @param  bool  $disabled  Disables the item; the arrow keys skip it.
     */
    public function __construct(
        public ?string $value = null,
        public mixed $title = null,
        public bool $disabled = false,
    ) {
        $this->accordion = Accordion::current();
        $this->classes   = $this->accordion?->classes ?? [];
    }

    /**
     * The item's value: `value`, else its title text.
     *
     * @since 3.0.0
     *
     * @return string The value.
     */
    public function itemValue(): string
    {
        if (null !== $this->value && '' !== $this->value) {
            return $this->value;
        }

        $title = is_string($this->title) ? trim($this->title) : '';

        return '' !== $title ? $title : 'item';
    }

    /**
     * The Accordion this item belongs to, if any.
     *
     * @since 3.0.0
     *
     * @return Accordion|null The accordion.
     */
    public function parent(): ?Accordion
    {
        return $this->accordion;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.accordion-item');
    }
}
