<?php

/**
 * Tab Component
 *
 * One tab of the design-system Tabs (#56): its trigger and its panel.
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
 * Tab Component Class
 *
 * A child of `tabs`. It registers its trigger with the Tabs when it's
 * constructed, so the Tabs can render the tablist before the panels, and
 * renders its own panel (Zag's `content` part) from its slot. Every
 * attribute lands on the panel.
 *
 * @since 1.0.0
 */
class Tab extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Tabs.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The Tabs this tab belongs to.
     *
     * @since 3.0.0
     */
    private ?Tabs $tabs;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $value  The tab's value, unique in the Tabs.
     * @param  string|null  $label  The trigger's label.
     * @param  string|null  $icon  An icon name before the label.
     * @param  int|string|null  $count  A count at the end of the trigger.
     * @param  bool  $disabled  Disables the tab; the arrow keys skip it.
     * @param  string|null  $name  Deprecated: use `value`.
     * @param  bool  $hidden  Deprecated: leave the tab out instead. Renders nothing.
     * @param  string|null  $contentClasses  Deprecated: use `class`, which lands on the panel.
     */
    public function __construct(
        public ?string $value = null,
        public ?string $label = null,
        public ?string $icon = null,
        public string|int|null $count = null,
        public bool $disabled = false,
        public ?string $name = null,
        public bool $hidden = false,
        public ?string $contentClasses = null,
    ) {
        $this->tabs    = Tabs::current();
        $this->classes = $this->tabs?->classes ?? [];

        if (! $this->hidden) {
            $this->tabs?->register(
                $this->tabValue(),
                $this->content($this->label ?? $this->tabValue()),
                $this->filled($this->icon) ? $this->icon : null,
                null !== $this->count && '' !== (string) $this->count ? (string) $this->count : null,
                $this->disabled,
            );
        }
    }

    /**
     * The tab's value: `value`, else the deprecated `name`, else its label.
     *
     * @since 3.0.0
     *
     * @return string The value.
     */
    public function tabValue(): string
    {
        foreach ([$this->value, $this->name, $this->label] as $candidate) {
            if (null !== $candidate && '' !== $candidate) {
                return $candidate;
            }
        }

        return 'tab';
    }

    /**
     * The Tabs this tab belongs to, if any.
     *
     * @since 3.0.0
     *
     * @return Tabs|null The tabs.
     */
    public function parent(): ?Tabs
    {
        return $this->tabs;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.tab');
    }
}
