<?php

/**
 * Drawer Component
 *
 * The design-system Drawer (#58): a panel that slides in from an edge, on
 * the behavior engine's dialog machine.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Drawer Component Class
 *
 * Renders `apuiDrawer` (D-019): an optional trigger, then the backdrop and
 * the positioner holding the `.apui-drawer` panel (the machine's content
 * part) with its header, body and footer, every part scoped `drawer`.
 * `wire:model` binds the open state through `$wire.entangle()`; without it,
 * `open` is plain Alpine state. `class` lands on the panel; every other
 * attribute lands on the element that holds the Alpine component, where
 * `open-change` is dispatched.
 *
 * The 2.x attributes still work for 3.0, deprecated: `right` (now
 * `side="end"`), `subtitle` (`description`) and the `actions` slot
 * (`footer`). `separator`, `with-close-button`, `close-on-escape`,
 * `without-trap-focus`, `open-on`, `close-on` and the glass tint props are
 * dropped.
 *
 * @since 1.0.0
 */
class Drawer extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The sides a drawer slides in from.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const SIDES = ['end', 'start', 'bottom'];

    /**
     * The 2.x attributes that are dropped, so they never land on the host.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const DROPPED_ATTRIBUTES = [
        'right', 'subtitle', 'separator', 'with-close-button', 'withCloseButton', 'close-on-escape', 'closeOnEscape',
        'without-trap-focus', 'withoutTrapFocus', 'open-on', 'openOn', 'close-on', 'closeOn', 'glass', 'glass-tint',
        'glassTint', 'glass-tint-opacity', 'glassTintOpacity',
    ];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'drawer';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'title', 'description', 'footer', 'trigger', 'closeLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string  $side  The edge it slides in from: end, start or bottom. end is on the right in LTR, on the left in RTL.
     * @param  string  $size  The panel's width at the start or end side: sm, md or lg.
     * @param  string  $surface  The panel's surface: overlay or glass.
     * @param  string|null  $title  The heading and the panel's accessible name. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text under the title. A `description` slot overrides it.
     * @param  string|null  $footer  The actions row. A `footer` slot overrides it.
     * @param  string|null  $trigger  Content for a trigger button that opens the drawer. A `trigger` slot overrides it.
     * @param  bool  $modal  Traps focus, locks scroll and hides the rest of the page from assistive technology.
     * @param  bool  $portal  Renders the drawer at the end of the body (x-teleport). Off renders it in place.
     * @param  bool  $hideClose  Hides the close button. Escape still closes the drawer.
     * @param  string|null  $closeLabel  The close button's accessible name. Defaults to "Close", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $open = false,
        public string $side = 'end',
        public string $size = 'md',
        public string $surface = 'overlay',
        public ?string $title = null,
        public ?string $description = null,
        public ?string $footer = null,
        public ?string $trigger = null,
        public bool $modal = true,
        public bool $portal = true,
        public bool $hideClose = false,
        public ?string $closeLabel = null,
        public array $classes = [],
    ) {}

    /**
     * The side to render: `side`, or `end` for the deprecated `right`.
     *
     * @since 3.0.0
     *
     * @return string end, start or bottom.
     */
    public function resolvedSide(): string
    {
        if ($this->attributes->has('right') && false !== $this->attributes->get('right')) {
            return 'end';
        }

        return in_array($this->side, self::SIDES, true) ? $this->side : 'end';
    }

    /**
     * The `x-data` expression: `apuiDrawer()` with the machine id, the open
     * state and modality.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiDrawer({ id: %s, open: %s, modal: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            $this->modal ? 'true' : 'false',
        );
    }

    /**
     * The close button's accessible name: `closeLabel`, else "Close",
     * translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function closeTriggerLabel(): string
    {
        return null !== $this->closeLabel && '' !== $this->closeLabel ? $this->closeLabel : __('Close');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.drawer');
    }
}
