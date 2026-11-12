<?php

/**
 * AppShell Component
 *
 * The design-system AppShell (#58): the application frame, with a sidebar
 * and a main column as islands separated by a gutter.
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
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;

/**
 * AppShell Component Class
 *
 * Renders the `.apui-app-shell` frame: a skip link, the sidebar (an aside
 * named with `label`, holding the `brand`, the `sidebar` navigation and
 * the `footer`, with the collapse button when `collapsible`) and the main
 * landmark (the `header`, then the content from the default slot).
 * Collapsing is a small Alpine state: `wire:model` binds it through
 * `$wire.entangle()`; without it the state is kept in the session through
 * the 2.x toggle route, as `main` did. A click dispatches `collapsed-change`.
 *
 * @since 3.0.0
 */
class AppShell extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'app-shell';

    /**
     * Props that change between renders without changing the id: state and content.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['collapsed', 'brand', 'sidebar', 'header', 'footer', 'label', 'skipLabel', 'collapseLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $collapsed  Shrinks the sidebar to its icons. `wire:model` binds it instead.
     * @param  bool  $collapsible  Adds the button that collapses and expands the sidebar.
     * @param  string|null  $sidebarWidth  The expanded sidebar's width, any CSS length.
     * @param  string|null  $gutter  The space around and between the islands, any CSS length.
     * @param  mixed  $brand  The brand at the top of the sidebar: a string, or the `brand` slot.
     * @param  mixed  $sidebar  The sidebar's navigation: a string, or the `sidebar` slot.
     * @param  mixed  $header  The bar at the top of the main column: a string, or the `header` slot.
     * @param  mixed  $footer  The foot of the sidebar: a string, or the `footer` slot.
     * @param  string|null  $label  Names the sidebar. Defaults to "Sidebar", translated.
     * @param  string|null  $skipLabel  The skip link's text. Defaults to "Skip to main content", translated.
     * @param  string|null  $collapseLabel  Names the collapse button. Defaults to "Collapse sidebar", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $collapsed = false,
        public bool $collapsible = false,
        public ?string $sidebarWidth = null,
        public ?string $gutter = null,
        public mixed $brand = null,
        public mixed $sidebar = null,
        public mixed $header = null,
        public mixed $footer = null,
        public ?string $label = null,
        public ?string $skipLabel = null,
        public ?string $collapseLabel = null,
        public array $classes = [],
    ) {
        $this->label         = $this->label ?? __('Sidebar');
        $this->skipLabel     = $this->skipLabel ?? __('Skip to main content');
        $this->collapseLabel = $this->collapseLabel ?? __('Collapse sidebar');
    }

    /**
     * Whether the server renders the sidebar collapsed: the bound Livewire
     * property with `wire:model`, else `collapsed` or the session's 2.x state.
     *
     * @since 3.0.0
     *
     * @return bool True when collapsed.
     */
    public function isCollapsed(): bool
    {
        $model = $this->modelName();
        if (null !== $model) {
            $component = $this->livewireComponent();

            return null !== $component ? (bool) data_get($component, $model) : $this->collapsed;
        }

        if ($this->collapsed) {
            return true;
        }

        return $this->collapsible && 'true' === (string) session('artisanpack-sidebar-collapsed', 'false');
    }

    /**
     * Whether the frame needs its Alpine state: to toggle, or to follow `wire:model`.
     *
     * @since 3.0.0
     *
     * @return bool True when it does.
     */
    public function interactive(): bool
    {
        return $this->collapsible || null !== $this->modelName();
    }

    /**
     * The `x-data` expression: the collapsed state (`$wire.entangle()` of
     * the `wire:model` property, else the server's state) and `toggle()`,
     * which dispatches `collapsed-change` and, without `wire:model`, keeps
     * the state in the session.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $model = $this->modelName();
        if (null !== $model) {
            $state   = '$wire.entangle('.Js::from($model)->toHtml().')';
            $persist = '';
        } else {
            $state   = $this->isCollapsed() ? 'true' : 'false';
            $persist = Route::has('artisanpack.toogle-sidebar')
                ? ' fetch('.Js::from(route('artisanpack.toogle-sidebar', absolute: false))->toHtml().' + \'?collapsed=\' + this.collapsed);'
                : '';
        }

        return '{ collapsed: '.$state.', toggle() { this.collapsed = ! this.collapsed;'.$persist." this.\$dispatch('collapsed-change', { collapsed: this.collapsed }); } }";
    }

    /**
     * The root's inline style: the sidebar width and gutter tokens, when set.
     *
     * @since 3.0.0
     *
     * @return string|null The style, or null.
     */
    public function rootStyle(): ?string
    {
        $declarations = [];
        foreach (['--apui-sidebar-width' => $this->sidebarWidth, '--apui-shell-gutter' => $this->gutter] as $property => $value) {
            $value = null !== $value ? trim((string) preg_replace('/[;{}<>"]/', '', $value)) : '';
            if ('' !== $value) {
                $declarations[] = $property.': '.$value;
            }
        }

        return [] === $declarations ? null : implode('; ', $declarations);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.app-shell');
    }
}
