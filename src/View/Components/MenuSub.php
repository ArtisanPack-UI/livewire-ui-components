<?php

/**
 * MenuSub Component
 *
 * A collapsible sub-menu of the design-system navigation Menu (#56).
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
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * MenuSub Component Class
 *
 * A child of `menu`: an item that opens and closes a nested list (the
 * disclosure pattern: a button with aria-expanded and aria-controls, and a
 * plain Alpine toggle; not an ARIA menu, so no behavior widget). It renders
 * open when `open` is set or it holds the current page. When the Menu isn't
 * collapsible, the item is a heading and the list always shows. Every
 * attribute lands on the item.
 *
 * @since 1.0.0
 */
class MenuSub extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part, from the Menu.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The Menu this sub-menu belongs to.
     *
     * @since 3.0.0
     */
    private ?Menu $menu;

    /**
     * Whether an item inside is the current page.
     *
     * @since 3.0.0
     */
    private bool $holdsCurrent = false;

    /**
     * The sub-menu this one is nested in, if any.
     *
     * @since 3.0.0
     */
    private ?self $parentSub;

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $label  The item's label.
     * @param  string|null  $icon  An icon name before the label.
     * @param  int|string|null  $badge  A count or status at the end of the toggle.
     * @param  bool  $open  Renders it open.
     * @param  bool  $disabled  Disables the toggle.
     * @param  bool  $hidden  Renders nothing.
     * @param  string|null  $title  Deprecated: use `label`.
     */
    public function __construct(
        public ?string $label = null,
        public ?string $icon = null,
        public string|int|null $badge = null,
        public bool $open = false,
        public bool $disabled = false,
        public bool $hidden = false,
        public ?string $title = null,
    ) {
        $context         = app(NavigationContext::class);
        $this->menu      = $context->nearest(Menu::class);
        $this->classes   = $this->menu?->classes ?? [];
        $this->parentSub = $context->nearest(self::class);
        $context->enter($this);
    }

    /**
     * Records that an item inside is the current page, so it renders open.
     *
     * @since 3.0.0
     */
    public function holdsCurrent(): void
    {
        $this->holdsCurrent = true;
        $this->parentSub?->holdsCurrent();
    }

    /**
     * Whether it renders open.
     *
     * @since 3.0.0
     *
     * @return bool True when open.
     */
    public function isOpen(): bool
    {
        return $this->open || $this->holdsCurrent || ! $this->collapsible();
    }

    /**
     * Whether it opens and closes: the Menu's `collapsible`.
     *
     * @since 3.0.0
     *
     * @return bool True when it does.
     */
    public function collapsible(): bool
    {
        return $this->menu?->collapsible ?? true;
    }

    /**
     * The list's id: stable per Menu, in render order.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function subId(): string
    {
        return $this->menu?->nextSubId() ?? 'apui-menu-sub-'.substr(md5((string) $this->labelText()), 0, 8);
    }

    /**
     * The label as given by `label` or the deprecated `title`.
     *
     * @since 3.0.0
     *
     * @return string|null The label.
     */
    public function labelText(): ?string
    {
        return $this->label ?? $this->title;
    }

    /**
     * Leaves the context once the view has rendered its items.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(NavigationContext::class)->leave($this);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.menu-sub');
    }
}
