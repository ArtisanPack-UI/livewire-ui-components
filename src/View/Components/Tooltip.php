<?php

/**
 * Tooltip Component
 *
 * The design-system Tooltip (#59): a short label on hover and keyboard focus,
 * on the behavior engine's tooltip machine. New in 3.0; it replaces the raw
 * DaisyUI `tooltip` classes and the `tooltip*` props of 2.x components.
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

use ArtisanPack\LivewireUiComponents\Support\TriggerElement;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

/**
 * Tooltip Component Class
 *
 * Renders `apuiTooltip` (D-019): the author's control from the default slot
 * as the trigger part (text is wrapped in a focusable span), then the
 * positioner holding the `.apui-tooltip` label (Zag's content part,
 * `role=tooltip`) with its arrow and shortcut keys. Without `open` the
 * tooltip keeps its own state; `open` forces it shown or hidden, and
 * `wire:model` binds it both ways. `class` lands on the label; every other
 * attribute lands on the element that holds the Alpine component, where
 * `open-change` is dispatched.
 *
 * @since 3.0.0
 */
class Tooltip extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The sides a tooltip sits on.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const PLACEMENTS = ['top', 'bottom', 'start', 'end'];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'tooltip';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'content', 'shortcut'];

    /**
     * Kept out of the view: the `content` prop shares its name with the
     * `content()` helper, and a `content` slot takes the name in the view.
     * The view reads the prop through `label()`.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected $except = ['content'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $content  The label. A `content` slot overrides it.
     * @param  string  $placement  The trigger's side it sits on: top, bottom, start or end.
     * @param  int  $delay  Milliseconds the pointer rests on the trigger before it shows. Focus shows it at once.
     * @param  string  $size  md for a one-line label, lg for a sentence or two.
     * @param  string|null  $shortcut  A keyboard shortcut shown after the label, as keys joined with + (⌘+K).
     * @param  bool|null  $open  Forces it shown (true) or hidden (false). Leave it out to let hover and focus show it; `wire:model` binds it.
     * @param  bool  $disabled  Never shows the label.
     * @param  bool  $portal  Renders the label at the end of the body (x-teleport). Off renders it in place.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $content = null,
        public string $placement = 'top',
        public int $delay = 400,
        public string $size = 'md',
        public ?string $shortcut = null,
        public ?bool $open = null,
        public bool $disabled = false,
        public bool $portal = true,
        public array $classes = [],
    ) {}

    /**
     * The `content` prop, for the view (see `$except`).
     *
     * @since 3.0.0
     *
     * @return string The label text.
     */
    public function label(): string
    {
        return $this->content ?? '';
    }

    /**
     * The placement to render, falling back to `top`.
     *
     * @since 3.0.0
     *
     * @return string top, bottom, start or end.
     */
    public function resolvedPlacement(): string
    {
        return in_array($this->placement, self::PLACEMENTS, true) ? $this->placement : 'top';
    }

    /**
     * The shortcut as the keys to show: `⌘+K` → `['⌘', 'K']`. A lone `+`
     * (or a trailing one, `Ctrl++`) is a key.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public function keys(): array
    {
        $shortcut = trim((string) $this->shortcut);
        if ('' === $shortcut) {
            return [];
        }
        if ('+' === $shortcut) {
            return ['+'];
        }

        $keys = preg_split('/\+(?!$)/', $shortcut) ?: [];

        return array_values(array_filter(array_map('trim', $keys), static fn (string $key): bool => '' !== $key));
    }

    /**
     * Whether the server renders the label shown: the bound property with
     * `wire:model`, else `open`.
     *
     * @since 3.0.0
     */
    public function shown(): bool
    {
        $model = $this->modelName();
        if (null === $model) {
            return true === $this->open;
        }

        $component = $this->livewireComponent();

        return null !== $component ? (bool) data_get($component, $model) : true === $this->open;
    }

    /**
     * The `x-data` expression: `apuiTooltip()` with the machine id, the
     * placement, the delay and, when it's controlled, the open state.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $options = [
            'id: '.Js::from($this->baseId())->toHtml(),
            'placement: '.Js::from($this->resolvedPlacement())->toHtml(),
            'delay: '.max(0, $this->delay),
        ];
        if ($this->disabled) {
            $options[] = 'disabled: true';
        }
        if (null !== $this->modelName()) {
            $options[] = 'open: '.$this->openExpression();
            $options[] = 'bound: true';
        } elseif (null !== $this->open) {
            $options[] = 'open: '.($this->open ? 'true' : 'false');
        }

        return 'apuiTooltip({ '.implode(', ', $options).' })';
    }

    /**
     * The default slot as the trigger part: its first element, or the text
     * wrapped in a focusable span.
     *
     * @since 3.0.0
     *
     * @param  mixed  $slot  The default slot.
     *
     * @return HtmlString The trigger.
     */
    public function triggerMarkup(mixed $slot): HtmlString
    {
        $shown      = $this->shown();
        $attributes = [
            'class'            => $this->classes['trigger'] ?? null,
            'data-scope'       => 'tooltip',
            'data-part'        => 'trigger',
            'data-state'       => $shown ? 'open' : 'closed',
            'aria-describedby' => $shown ? $this->partId('content') : null,
            'x-apui:trigger'   => '',
        ];

        $html     = $slot instanceof Htmlable ? $slot->toHtml() : e((string) $slot);
        $injected = TriggerElement::inject($html, $this->partId('trigger'), $attributes);
        if (null !== $injected) {
            return new HtmlString($injected['html']);
        }

        $wrapped = TriggerElement::inject('<span tabindex="0">'.trim($html).'</span>', $this->partId('trigger'), $attributes);

        return new HtmlString($wrapped['html'] ?? '');
    }

    /**
     * A prop or slot as markup, as `content()` renders it. Views call this
     * one, because a `content` slot takes the `$content` name.
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
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.tooltip');
    }
}
