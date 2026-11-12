<?php

/**
 * Accordion Component
 *
 * The design-system Accordion (#58): stacked sections that expand and
 * collapse, on the behavior engine's accordion machine.
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
use Illuminate\View\ComponentAttributeBag;

/**
 * Accordion Component Class
 *
 * Renders `apuiAccordion`: the `.apui-accordion` root (Zag's root part)
 * holding one item per `accordion-item` child, after the items rendered
 * from `items` data. Each item renders its title (a heading at
 * `headingLevel`), its trigger and its panel. Parts are scoped `accordion`
 * and keep stable ids per value (D-015). `wire:model` binds the open
 * items through `$wire.entangle()`: an array, or a string (or null) for a
 * single accordion.
 *
 * @since 1.0.0
 */
class Accordion extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'accordion';

    /**
     * Props that change between renders without changing the id: state and data.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'items'];

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  array<int, string>|string|null  $value  The open items' values. `wire:model` binds it instead.
     * @param  array<int, array<string, mixed>>  $items  The items as data: `{ value, title, content, disabled }`.
     * @param  bool  $multiple  Lets several items be open at once.
     * @param  bool  $collapsible  Lets the open item of a single accordion close.
     * @param  string  $variant  default, separated or flush.
     * @param  int  $headingLevel  The items' heading level, 2 to 6.
     * @param  bool  $disabled  Disables every item.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array|string|null $value = null,
        public array $items = [],
        public bool $multiple = false,
        public bool $collapsible = true,
        public string $variant = 'default',
        public int $headingLevel = 3,
        public bool $disabled = false,
        public array $classes = [],
    ) {
        $this->variant      = in_array($this->variant, ['default', 'separated', 'flush'], true) ? $this->variant : 'default';
        $this->headingLevel = max(2, min(6, $this->headingLevel));

        app(NavigationContext::class)->enter($this);
    }

    /**
     * The Accordion whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The accordion.
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
     * The open items the server renders: the bound Livewire property, else `value`.
     *
     * @since 3.0.0
     *
     * @return list<string> The values.
     */
    public function openValues(): array
    {
        $value = $this->value;
        $model = $this->modelName();
        if (null !== $model) {
            $component = $this->livewireComponent();
            if (null !== $component) {
                $value = data_get($component, $model);
            }
        }

        $values = is_array($value) ? $value : [$value];

        return array_values(array_map('strval', array_filter($values, static fn (mixed $item): bool => is_scalar($item) && '' !== (string) $item)));
    }

    /**
     * Whether an item is open.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The item's value.
     *
     * @return bool True when open.
     */
    public function isItemOpen(string $value): bool
    {
        return in_array($value, $this->openValues(), true);
    }

    /**
     * A per-value part id: `item`, `trigger` or `content`.
     *
     * @since 3.0.0
     *
     * @param  string  $part  The part.
     * @param  string  $value  The item's value.
     *
     * @return string The id.
     */
    public function valuePartId(string $part, string $value): string
    {
        $safe = (string) preg_replace('/[^A-Za-z0-9_-]+/u', '-', trim($value));

        return $this->partId($part.'-'.('' === $safe ? 'item' : $safe));
    }

    /**
     * The `items` prop as entries the view renders.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, title: string, content: string, disabled: bool}> The entries.
     */
    public function entries(): array
    {
        $entries = [];
        foreach ($this->items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $entries[] = [
                'value'    => (string) ($item['value'] ?? $item['name'] ?? $item['title'] ?? ''),
                'title'    => (string) ($item['title'] ?? $item['label'] ?? $item['value'] ?? ''),
                'content'  => (string) ($item['content'] ?? ''),
                'disabled' => (bool) ($item['disabled'] ?? false),
            ];
        }

        return $entries;
    }

    /**
     * The JSON a part passes its `x-apui:*` directive.
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
     * The `x-data` expression: `apuiAccordion()` with the machine id, the
     * open items (`$wire.entangle()` of the `wire:model` property, `.live`
     * with `wire:model.live`), and the multiple, collapsible and disabled options.
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
            $value = Js::from($this->openValues())->toHtml();
        }

        return sprintf(
            'apuiAccordion({ id: %s, value: %s, multiple: %s, collapsible: %s, disabled: %s })',
            Js::from($this->baseId())->toHtml(),
            $value,
            $this->multiple ? 'true' : 'false',
            $this->collapsible ? 'true' : 'false',
            $this->disabled ? 'true' : 'false',
        );
    }

    /**
     * The attributes for the root: everything but `wire:model` and `id`.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id']);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.accordion');
    }
}
