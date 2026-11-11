<?php

/**
 * Breadcrumbs Component
 *
 * The design-system Breadcrumbs (#56): a nav landmark holding the trail to
 * the current page.
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

use ArtisanPack\LivewireUiComponents\Support\BreadcrumbsContext;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentAttributeBag;

/**
 * Breadcrumbs Component Class
 *
 * Renders `.apui-breadcrumbs` (a `nav`) with the list, item, link,
 * separator, current and overflow parts, from `items` and then from
 * composed `breadcrumbs-item` (or `link`) children. Past `maxItems` the middle
 * levels collapse behind the overflow button, which reveals them with Alpine.
 * Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Breadcrumbs extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The levels composed children registered, in render order.
     *
     * @since 3.0.0
     *
     * @var list<array{label: Htmlable|string, href: string|null, icon: string|null, hideLabel: bool, attributes: ComponentAttributeBag|null}>
     */
    private array $children = [];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, array<string, mixed>>  $items  The trail: `{ label, href, icon, hideLabel }`. The 2.x `link` key still works for href. The last item is the current page.
     * @param  int  $maxItems  How many entries show before the middle collapses. 0 never collapses.
     * @param  string|null  $label  The nav landmark's name. Defaults to __( 'Breadcrumb' ).
     * @param  string|null  $overflowLabel  The overflow button's name, with {count}. Defaults to __( 'Show {count} more levels' ).
     * @param  bool  $navigate  Adds wire:navigate to every internal link (default true). Off-site, `#` and `mailto:` links never get it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $items = [],
        public int $maxItems = 4,
        public ?string $label = null,
        public ?string $overflowLabel = null,
        public bool $navigate = true,
        public array $classes = [],
    ) {
        $this->label ??= __('Breadcrumb');
        $this->overflowLabel ??= __('Show {count} more levels');

        app(BreadcrumbsContext::class)->enter($this);
    }

    /**
     * The Breadcrumbs whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The Breadcrumbs.
     */
    public static function current(): ?self
    {
        return app(BreadcrumbsContext::class)->current();
    }

    /**
     * Registers a composed level: a `breadcrumbs-item` or a `link` child.
     * Children render nothing themselves; the trail renders them in order.
     *
     * @since 3.0.0
     *
     * @param  Htmlable|string  $label  The label: markup from a slot, or text.
     * @param  string|null  $href  The link, already checked.
     * @param  string|null  $icon  An icon name before the label.
     * @param  bool  $hideLabel  Keeps the label for screen readers only.
     * @param  ComponentAttributeBag|null  $attributes  The child's attributes, for its link.
     */
    public function addChild(Htmlable|string $label, ?string $href, ?string $icon = null, bool $hideLabel = false, ?ComponentAttributeBag $attributes = null): void
    {
        $this->children[] = [
            'label'      => $label,
            'href'       => $href,
            'icon'       => null !== $icon && '' !== $icon ? $icon : null,
            'hideLabel'  => $hideLabel,
            'attributes' => $attributes,
        ];
    }

    /**
     * Leaves the context once the view has rendered the children.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(BreadcrumbsContext::class)->leave($this);
    }

    /**
     * The levels, normalized: `items`, then the composed children. Each has
     * its label, safe href (null for the current page), icon, hideLabel,
     * the child's attributes and whether it starts collapsed.
     *
     * @since 3.0.0
     *
     * @return list<array{label: Htmlable|string, href: string|null, icon: string|null, hideLabel: bool, attributes: ComponentAttributeBag|null, current: bool, collapsed: bool}> The entries.
     */
    public function entries(): array
    {
        $levels = [];
        foreach (array_values(array_filter($this->items, 'is_array')) as $item) {
            $href     = $item['href'] ?? $item['link'] ?? null;
            $icon     = $item['icon'] ?? null;
            $levels[] = [
                'label'      => (string) ($item['label'] ?? ''),
                'href'       => SafeUrl::href(is_string($href) ? $href : null),
                'icon'       => is_string($icon) && '' !== $icon ? $icon : null,
                'hideLabel'  => (bool) ($item['hideLabel'] ?? false),
                'attributes' => null,
            ];
        }
        array_push($levels, ...$this->children);

        $count     = count($levels);
        $collapsed = $this->collapsedRange($count);
        $entries   = [];
        foreach ($levels as $index => $level) {
            $isCurrent = $index === $count - 1;
            $entries[] = $level + [
                'current'   => $isCurrent,
                'collapsed' => null !== $collapsed && $index >= $collapsed[0] && $index <= $collapsed[1],
            ];
            if ($isCurrent) {
                $entries[$index]['href'] = null;
            }
        }

        return $entries;
    }

    /**
     * How many levels the overflow button hides.
     *
     * @since 3.0.0
     *
     * @param  list<array{collapsed: bool}>  $entries  The entries.
     *
     * @return int The count, 0 when nothing collapses.
     */
    public function hiddenCount(array $entries): int
    {
        return count(array_filter($entries, static fn (array $entry): bool => $entry['collapsed']));
    }

    /**
     * The overflow button's accessible name, with the count filled in.
     *
     * @since 3.0.0
     *
     * @param  int  $count  How many levels are hidden.
     *
     * @return string The name.
     */
    public function overflowName(int $count): string
    {
        return str_replace('{count}', (string) $count, (string) $this->overflowLabel);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.breadcrumbs');
    }

    /**
     * The first and last index that start collapsed: everything between the
     * first item and the last `maxItems - 2`. Null when the trail fits.
     *
     * @param  int  $count  How many items there are.
     *
     * @return array{0: int, 1: int}|null The range.
     */
    private function collapsedRange(int $count): ?array
    {
        if ($this->maxItems <= 0 || $count <= $this->maxItems) {
            return null;
        }

        $tail = max(1, $this->maxItems - 2);
        $last = $count - $tail - 1;

        return $last >= 1 ? [1, $last] : null;
    }
}
