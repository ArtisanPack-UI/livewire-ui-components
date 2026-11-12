<?php

/**
 * Popover Component
 *
 * The design-system Popover (#58): an anchored, non-modal panel on the
 * behavior engine's popover machine.
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

use ArtisanPack\LivewireUiComponents\Support\TriggerElement;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

/**
 * Popover Component Class
 *
 * Renders `apuiPopover` (D-019): the trigger, then the positioner holding the
 * `.apui-popover` panel (Zag's content part, `role=dialog`) with its arrow,
 * title, body and actions. Text in `trigger` renders an outline Button; an
 * element in the `trigger` slot becomes the trigger itself. `wire:model`
 * binds the open state through `$wire.entangle()`; without it, `open` is
 * plain Alpine state. `class` lands on the panel; every other attribute
 * lands on the element that holds the Alpine component, where `open-change`
 * is dispatched.
 *
 * The 2.x `position` attribute still works for 3.0, deprecated (`left` is
 * `start`, `right` is `end`), and so does the `content` slot (now the
 * default slot). `offset` and the glass props are dropped. A 2.x popover
 * opened on hover; this one opens on click, as a dialog.
 *
 * @since 1.0.0
 */
class Popover extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The sides a popover sits on.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const PLACEMENTS = ['top', 'bottom', 'start', 'end'];

    /**
     * The 2.x attributes that are dropped, so they never land on the host.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const DROPPED_ATTRIBUTES = ['position', 'offset', 'glass', 'glass-tint', 'glassTint', 'glass-tint-opacity', 'glassTintOpacity'];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'popover';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'title', 'actions', 'trigger'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string  $placement  The trigger's side it sits on: top, bottom, start or end.
     * @param  bool  $arrow  Draws the arrow that points at the trigger.
     * @param  string|null  $title  The heading and the panel's accessible name. A `title` slot overrides it.
     * @param  string|null  $actions  The actions row. An `actions` slot overrides it.
     * @param  string|null  $trigger  Text for an outline Button that opens it. A `trigger` slot overrides it; an element there becomes the trigger.
     * @param  bool  $portal  Renders the panel at the end of the body (x-teleport). Off renders it in place.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $open = false,
        public string $placement = 'bottom',
        public bool $arrow = true,
        public ?string $title = null,
        public ?string $actions = null,
        public ?string $trigger = null,
        public bool $portal = true,
        public array $classes = [],
    ) {}

    /**
     * The placement to render: `placement`, else the deprecated `position`
     * (`left` → `start`, `right` → `end`), else `bottom`.
     *
     * @since 3.0.0
     *
     * @return string top, bottom, start or end.
     */
    public function resolvedPlacement(): string
    {
        $position = $this->attributes->get('position');
        if ('bottom' === $this->placement && is_string($position) && '' !== $position) {
            $mapped = ['left' => 'start', 'right' => 'end'][$position] ?? $position;
            if (in_array($mapped, self::PLACEMENTS, true)) {
                return $mapped;
            }
        }

        return in_array($this->placement, self::PLACEMENTS, true) ? $this->placement : 'bottom';
    }

    /**
     * The `x-data` expression: `apuiPopover()` with the machine id, the open
     * state, the placement and whether the panel is teleported.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiPopover({ id: %s, open: %s, placement: %s, portalled: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            Js::from($this->resolvedPlacement())->toHtml(),
            $this->portal ? 'true' : 'false',
        );
    }

    /**
     * The trigger slot's own element as the trigger part, or null when the
     * trigger is text (then an outline Button carries the part).
     *
     * @since 3.0.0
     *
     * @param  mixed  $trigger  The `trigger` prop or slot.
     *
     * @return HtmlString|null The element with the trigger part's attributes.
     */
    public function elementTrigger(mixed $trigger): ?HtmlString
    {
        if (! $trigger instanceof Htmlable) {
            return null;
        }

        $open     = $this->isOpen();
        $injected = TriggerElement::inject($trigger->toHtml(), $this->partId('trigger'), [
            'class'          => $this->classes['trigger'] ?? null,
            'data-scope'     => 'popover',
            'data-part'      => 'trigger',
            'aria-haspopup'  => 'dialog',
            'aria-expanded'  => $open ? 'true' : 'false',
            'aria-controls'  => $this->partId('content'),
            'data-state'     => $open ? 'open' : 'closed',
            'x-apui:trigger' => '',
        ]);

        return null === $injected ? null : new HtmlString($injected['html']);
    }

    /**
     * A prop or slot as markup, as `content()` renders it. Views call this
     * one, because a 2.x `content` slot takes the `$content` name.
     *
     * @since 3.0.0
     */
    public function markup(mixed $value): HtmlString
    {
        return $this->content($value);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.popover');
    }
}
