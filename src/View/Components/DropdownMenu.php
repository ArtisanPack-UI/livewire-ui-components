<?php

/**
 * DropdownMenu Component
 *
 * The design-system DropdownMenu (#50): actions and options anchored to a
 * trigger, on the behavior engine's menu machine. Replaces Dropdown.
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

use ArtisanPack\LivewireUiComponents\Support\RenderedIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * DropdownMenu Component Class
 *
 * Renders `apuiDropdownMenu` (D-019): an outline Button trigger, then the
 * positioner holding the `.apui-dropdown-menu` panel (the machine's content
 * part). Children are `dropdown-menu-item`, `dropdown-menu-group` and
 * `dropdown-menu-separator`; `items` renders the same children from data.
 * Parts are scoped `dropdown-menu`. `wire:model` binds the open state
 * through `$wire.entangle()`; without it, `open` is plain Alpine state.
 * `class` lands on the panel; every other
 * attribute lands on the element that holds the Alpine component, where
 * `open-change`, `select` and `checked-change` are dispatched.
 *
 * @since 3.0.0
 */
class DropdownMenu extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'dropdown-menu';

    /**
     * Props that change between renders without changing the id: state, data and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'items', 'label'];

    /**
     * How many groups and separators have asked for an id, per kind.
     *
     * @since 3.0.0
     *
     * @var array<string, int>
     */
    private array $childCounts = [];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string|null  $label  The trigger button's content. A `label` slot overrides it.
     * @param  array<int, array<string, mixed>>  $items  The items as data: `{ value, label, icon, shortcut, color, disabled }`, `{ type: 'checkbox'|'radio', value, label, checked }`, `{ type: 'separator' }` and `{ type: 'group', label, items }`.
     * @param  string  $align  Which of the menu's edges lines up with the trigger's: start, center or end.
     * @param  string  $surface  The menu's surface: overlay or glass.
     * @param  bool  $portal  Renders the menu at the end of the body (x-teleport). Off renders it in place.
     * @param  array<string, string>  $classes  Extra classes per part, children's parts included.
     */
    public function __construct(
        public bool $open = false,
        public ?string $label = null,
        public array $items = [],
        public string $align = 'start',
        public string $surface = 'overlay',
        public bool $portal = true,
        public array $classes = [],
    ) {
        app(RenderedIds::class)->enterMenu($this);
    }

    /**
     * The menu whose children are rendering, if any. The stack lives in the
     * request-scoped RenderedIds, so a render that throws can't leak into
     * the next request (Octane included).
     *
     * @since 3.0.0
     *
     * @return self|null The menu.
     */
    public static function current(): ?self
    {
        return app(RenderedIds::class)->currentMenu();
    }

    /**
     * Leaves the stack once the menu's view has rendered its children.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(RenderedIds::class)->leaveMenu($this);
    }

    /**
     * The next stable id for a child that has no `id`: a group or a
     * separator, numbered in render order within this menu.
     *
     * @since 3.0.0
     *
     * @param  string  $kind  `group` or `separator`.
     *
     * @return string The id.
     */
    public function nextChildId(string $kind): string
    {
        $this->childCounts[$kind] = ($this->childCounts[$kind] ?? 0) + 1;

        return $this->partId($kind.'-'.$this->childCounts[$kind]);
    }

    /**
     * The `items` prop as entries the view renders: each with a `type`
     * (item, checkbox, radio, separator or group) and, for groups, their own
     * entries.
     *
     * @since 3.0.0
     *
     * @param  array<int, mixed>|null  $items  Entries to normalize. Defaults to the prop.
     *
     * @return list<array<string, mixed>> The entries.
     */
    public function entries(?array $items = null): array
    {
        $entries = [];
        foreach ($items ?? $this->items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $type = is_string($item['type'] ?? null) ? $item['type'] : 'item';
            if ('group' === $type) {
                $entries[] = [
                    'type'  => 'group',
                    'label' => isset($item['label']) ? (string) $item['label'] : null,
                    'id'    => isset($item['id']) ? (string) $item['id'] : null,
                    'items' => $this->entries(is_array($item['items'] ?? null) ? $item['items'] : []),
                ];

                continue;
            }

            if ('separator' === $type) {
                $entries[] = ['type' => 'separator'];

                continue;
            }

            $entries[] = [
                'type'     => in_array($type, ['checkbox', 'radio'], true) ? $type : 'item',
                'value'    => (string) ($item['value'] ?? $item['label'] ?? ''),
                'label'    => (string) ($item['label'] ?? $item['value'] ?? ''),
                'icon'     => isset($item['icon']) ? (string) $item['icon'] : null,
                'shortcut' => isset($item['shortcut']) ? (string) $item['shortcut'] : null,
                'color'    => isset($item['color']) ? (string) $item['color'] : null,
                'disabled' => (bool) ($item['disabled'] ?? false),
                'checked'  => (bool) ($item['checked'] ?? false),
            ];
        }

        return $entries;
    }

    /**
     * The `x-data` expression: `apuiDropdownMenu()` with the machine id, the
     * open state and the alignment.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiDropdownMenu({ id: %s, open: %s, align: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            Js::from(in_array($this->align, ['start', 'center', 'end'], true) ? $this->align : 'start')->toHtml(),
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.dropdown-menu');
    }
}
