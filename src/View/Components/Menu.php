<?php

/**
 * Menu Component
 *
 * The design-system navigation Menu (#56): a nav landmark holding links,
 * titles, separators and collapsible sub-menus.
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
use Illuminate\View\ComponentAttributeBag;

/**
 * Menu Component Class
 *
 * Renders the `.apui-menu` nav and its list. Children are `menu-item`,
 * `menu-sub`, `menu-title` and `menu-separator`; `items` renders the same
 * children from data. It isn't an ARIA menu and has no behavior widget: a
 * sub-menu is a disclosure, a button with aria-expanded and a plain Alpine
 * toggle. Parts are scoped `menu`. The `activate-by-route` attribute marks
 * items whose href matches the current URL as the current page.
 *
 * @since 1.0.0
 */
class Menu extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'menu';

    /**
     * Props that change between renders without changing the id.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['items', 'label'];

    /**
     * How many sub-menus have asked for an id.
     *
     * @since 3.0.0
     */
    private int $subCount = 0;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  array<int, array<string, mixed>>  $items  The menu as data: `{ label, href, icon, badge, current, disabled }`, `{ label, icon, open, items }`, `{ type: 'title', label }` and `{ type: 'separator' }`.
     * @param  bool  $collapsible  Sub-menus open and close from their item. Off shows every sub-menu under a heading.
     * @param  string|null  $label  Names the nav landmark.
     * @param  array<string, string>  $classes  Extra classes per part, children's parts included.
     */
    public function __construct(
        public array $items = [],
        public bool $collapsible = true,
        public ?string $label = null,
        public array $classes = [],
    ) {
        app(NavigationContext::class)->enter($this);
    }

    /**
     * The Menu whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The menu.
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
     * The next stable id for a sub-menu's list, numbered in render order.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function nextSubId(): string
    {
        return $this->partId('sub-'.++$this->subCount);
    }

    /**
     * Whether items match the current URL (the `activate-by-route` attribute).
     *
     * @since 3.0.0
     *
     * @return bool True when they do.
     */
    public function activatesByRoute(): bool
    {
        $value = $this->attributes?->get('activate-by-route', $this->attributes?->get('activateByRoute'));

        return null !== $value && false !== $value && 'false' !== $value;
    }

    /**
     * The attributes for the nav, without `activate-by-route`.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->except(['activate-by-route', 'activateByRoute', 'id']);
    }

    /**
     * The `items` prop as entries the view renders.
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

            $type  = is_string($item['type'] ?? null) ? $item['type'] : null;
            $label = isset($item['label']) ? (string) $item['label'] : (isset($item['title']) ? (string) $item['title'] : '');

            if ('separator' === $type) {
                $entries[] = ['type' => 'separator'];

                continue;
            }

            if ('title' === $type) {
                $entries[] = ['type' => 'title', 'label' => $label];

                continue;
            }

            if (is_array($item['items'] ?? null)) {
                $entries[] = [
                    'type'     => 'sub',
                    'label'    => $label,
                    'icon'     => isset($item['icon']) ? (string) $item['icon'] : null,
                    'badge'    => isset($item['badge']) && '' !== (string) $item['badge'] ? (string) $item['badge'] : null,
                    'disabled' => (bool) ($item['disabled'] ?? false),
                    'open'     => (bool) ($item['open'] ?? false),
                    'items'    => $this->entries($item['items']),
                ];

                continue;
            }

            $entries[] = [
                'type'     => 'item',
                'label'    => $label,
                'href'     => isset($item['href']) ? (string) $item['href'] : (isset($item['link']) ? (string) $item['link'] : null),
                'icon'     => isset($item['icon']) ? (string) $item['icon'] : null,
                'badge'    => isset($item['badge']) && '' !== (string) $item['badge'] ? (string) $item['badge'] : null,
                'current'  => (bool) ($item['current'] ?? $item['active'] ?? false),
                'disabled' => (bool) ($item['disabled'] ?? false),
            ];
        }

        return $entries;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.menu');
    }
}
