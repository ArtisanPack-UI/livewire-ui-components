<?php

/**
 * Tabs Component
 *
 * The design-system Tabs (#56): a tablist and one panel per tab, on the
 * behavior engine's tabs machine.
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

use ArtisanPack\LivewireUiComponents\Support\NavigationContext;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

/**
 * Tabs Component Class
 *
 * Renders `apuiTabs`: the `.apui-tabs` root (Zag's root part), the tablist
 * holding a trigger per tab, then the panels. Children are `tab`, each
 * rendering its panel and registering its trigger here, since Blade renders
 * a component's slot before its view; `tabs` renders the same children from
 * data. Parts are scoped `tabs` and keep stable ids per value (D-015).
 * `wire:model` binds the selected value through `$wire.entangle()`; the
 * deprecated `selected` attribute still sets the initial value.
 *
 * @since 1.0.0
 */
class Tabs extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'tabs';

    /**
     * Props that change between renders without changing the id: state, data and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'tabs', 'label'];

    /**
     * The registered triggers, in render order.
     *
     * @since 3.0.0
     *
     * @var list<array{value: string, label: HtmlString, icon: string|null, count: string|null, disabled: bool}>
     */
    private array $triggers = [];

    /**
     * The triggers the `tabs` data registers. They come first, as their
     * panels do.
     *
     * @since 3.0.0
     *
     * @var list<array{value: string, label: HtmlString, icon: string|null, count: string|null, disabled: bool}>
     */
    private array $dataTriggers = [];

    /**
     * Whether the view is rendering the `tabs` data.
     *
     * @since 3.0.0
     */
    private bool $renderingData = false;

    /**
     * The first enabled tab, the fallback selection.
     *
     * @since 3.0.0
     */
    private ?string $firstEnabled = null;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $value  The selected tab's value. `wire:model` binds it instead. Without either, the first enabled tab.
     * @param  array<int, array<string, mixed>>  $tabs  The tabs as data: `{ value, label, icon, count, disabled, content }`.
     * @param  string  $variant  segmented or underline.
     * @param  string  $size  sm or md.
     * @param  string  $orientation  horizontal or vertical (`vertical-left` and `vertical-right` read as vertical).
     * @param  string  $activationMode  automatic (the arrow keys select) or manual (Enter or Space selects).
     * @param  string|null  $label  Names the tablist.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $value = null,
        public array $tabs = [],
        public string $variant = 'segmented',
        public string $size = 'md',
        public string $orientation = 'horizontal',
        public string $activationMode = 'automatic',
        public ?string $label = null,
        public array $classes = [],
    ) {
        $this->variant        = in_array($this->variant, ['segmented', 'underline'], true) ? $this->variant : 'segmented';
        $this->size           = in_array($this->size, ['sm', 'md'], true) ? $this->size : 'md';
        $this->orientation    = str_starts_with($this->orientation, 'vertical') ? 'vertical' : 'horizontal';
        $this->activationMode = 'manual' === $this->activationMode ? 'manual' : 'automatic';

        app(NavigationContext::class)->enter($this);
    }

    /**
     * The Tabs whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The tabs.
     */
    public static function current(): ?self
    {
        return app(NavigationContext::class)->nearest(self::class);
    }

    /**
     * Leaves the context once the view has rendered its children.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(NavigationContext::class)->leave($this);
    }

    /**
     * Registers a tab's trigger, in render order.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The tab's value.
     * @param  HtmlString  $label  Its label.
     * @param  string|null  $icon  Its icon name.
     * @param  string|null  $count  Its count.
     * @param  bool  $disabled  Whether it's disabled.
     */
    public function register(string $value, HtmlString $label, ?string $icon, ?string $count, bool $disabled): void
    {
        $trigger = compact('value', 'label', 'icon', 'count', 'disabled');
        if ($this->renderingData) {
            $this->dataTriggers[] = $trigger;
        } else {
            $this->triggers[] = $trigger;
        }

        // The first enabled tab to register: its panel may already have rendered selected.
        if (! $disabled && null === $this->firstEnabled) {
            $this->firstEnabled = $value;
        }
    }

    /**
     * The registered triggers.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: HtmlString, icon: string|null, count: string|null, disabled: bool}> The triggers.
     */
    public function triggers(): array
    {
        return [...$this->dataTriggers, ...$this->triggers];
    }

    /**
     * Marks the view rendering the `tabs` data, or done with it.
     *
     * @since 3.0.0
     *
     * @param  bool  $rendering  Whether it's rendering.
     */
    public function renderingData(bool $rendering): void
    {
        $this->renderingData = $rendering;
    }

    /**
     * The value the server renders selected: the bound Livewire property,
     * else `value`, else the deprecated `selected`, else the first enabled tab.
     *
     * @since 3.0.0
     *
     * @return string|null The value.
     */
    public function selectedValue(): ?string
    {
        $value = $this->explicitValue();

        return null !== $value && '' !== $value ? $value : $this->firstEnabled;
    }

    /**
     * Whether a tab is selected.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The tab's value.
     *
     * @return bool True when selected.
     */
    public function isSelected(string $value): bool
    {
        return $this->selectedValue() === $value;
    }

    /**
     * A per-value part id: `trigger` or `content`.
     *
     * @since 3.0.0
     *
     * @param  string  $part  The part.
     * @param  string  $value  The tab's value.
     *
     * @return string The id.
     */
    public function valuePartId(string $part, string $value): string
    {
        $safe = (string) preg_replace('/[^A-Za-z0-9_-]+/u', '-', trim($value));

        return $this->partId($part.'-'.('' === $safe ? 'tab' : $safe));
    }

    /**
     * The `tabs` prop as entries the view renders.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string, icon: string|null, count: string|null, disabled: bool, content: string}> The entries.
     */
    public function entries(): array
    {
        $entries = [];
        foreach ($this->tabs as $tab) {
            if (! is_array($tab)) {
                continue;
            }

            $entries[] = [
                'value'    => (string) ($tab['value'] ?? $tab['name'] ?? $tab['label'] ?? ''),
                'label'    => (string) ($tab['label'] ?? $tab['value'] ?? ''),
                'icon'     => isset($tab['icon']) ? (string) $tab['icon'] : null,
                'count'    => isset($tab['count']) && '' !== (string) $tab['count'] ? (string) $tab['count'] : null,
                'disabled' => (bool) ($tab['disabled'] ?? false),
                'content'  => (string) ($tab['content'] ?? ''),
            ];
        }

        return $entries;
    }

    /**
     * The JSON a trigger or panel passes its `x-apui:*` directive.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $args  The arguments.
     *
     * @return HtmlString The escaped JSON.
     */
    public function partArgs(array $args): HtmlString
    {
        return new HtmlString(e((string) json_encode($args, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)));
    }

    /**
     * The `x-data` expression: `apuiTabs()` with the machine id, the value
     * (`$wire.entangle()` of the `wire:model` property, `.live` with
     * `wire:model.live`), the orientation, the activation mode and the label.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $model = $this->modelName();
        if (null !== $model) {
            $live  = [] !== array_filter(
                array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
                static fn (string $key): bool => str_contains($key, '.live'),
            );
            $value = '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
        } else {
            $value = Js::from($this->selectedValue())->toHtml();
        }

        return sprintf(
            'apuiTabs({ id: %s, value: %s, orientation: %s, activationMode: %s%s })',
            Js::from($this->baseId())->toHtml(),
            $value,
            Js::from($this->orientation)->toHtml(),
            Js::from($this->activationMode)->toHtml(),
            $this->filled($this->label) ? ', label: '.Js::from((string) $this->label)->toHtml() : '',
        );
    }

    /**
     * The attributes for the root: everything but `wire:model`, `id` and the
     * deprecated `selected`.
     *
     * @since 3.0.0
     *
     * @return \Illuminate\View\ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): \Illuminate\View\ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id', 'selected']);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.tabs');
    }

    /**
     * The value set from outside: the bound property, `value`, or `selected`.
     *
     * @since 3.0.0
     *
     * @return string|null The value.
     */
    private function explicitValue(): ?string
    {
        $model = $this->modelName();
        if (null !== $model) {
            $component = $this->livewireComponent();
            $bound     = null !== $component ? data_get($component, $model) : null;
            if (is_scalar($bound) && '' !== (string) $bound) {
                return (string) $bound;
            }
        }

        if (null !== $this->value) {
            return $this->value;
        }

        $selected = $this->attributes?->get('selected');

        return is_scalar($selected) && '' !== (string) $selected ? (string) $selected : null;
    }
}
