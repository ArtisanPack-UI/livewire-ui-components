<?php

/**
 * Divider Component
 *
 * The design-system Divider (#58): a horizontal, vertical or labelled rule.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * Divider Component Class
 *
 * Renders `.apui-divider`: an `<hr>` for a plain horizontal rule, else a
 * `role="separator"` div with `aria-orientation`. A label (the prop or the
 * default slot) sits between two rules and names the separator through
 * `aria-labelledby`, since a separator's children are presentational. A
 * decorative divider is `aria-hidden` (or `role="none"` with a label, so
 * the label is read as text). Every attribute lands on the root.
 *
 * @since 3.0.0
 */
class Divider extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'divider';

    /**
     * Props that don't change the id: the label text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['label'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $orientation  horizontal or vertical.
     * @param  mixed  $label  Text in the middle of the rule: a string, or the default slot.
     * @param  string  $align  Where a label sits: start, center or end.
     * @param  string  $strength  subtle or strong.
     * @param  bool  $decorative  Hides the divider from assistive technology.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $orientation = 'horizontal',
        public mixed $label = null,
        public string $align = 'center',
        public string $strength = 'subtle',
        public bool $decorative = false,
        public array $classes = [],
    ) {
        $this->orientation = 'vertical' === $this->orientation ? 'vertical' : 'horizontal';
        $this->align       = in_array($this->align, ['start', 'center', 'end'], true) ? $this->align : 'center';
        $this->strength    = 'strong' === $this->strength ? 'strong' : 'subtle';
    }

    /**
     * A page-unique id for the label.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function labelId(): string
    {
        return $this->partId('label');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.divider');
    }
}
