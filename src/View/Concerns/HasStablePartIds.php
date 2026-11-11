<?php

/**
 * Stable part ids and open-state binding for the design-system overlays.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Concerns;

use ArtisanPack\LivewireUiComponents\Support\RenderedIds;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Throwable;

/**
 * Stable part ids and the open-state binding for Dialog, DropdownMenu and
 * Toast.
 *
 * Every part renders a stable, server-rendered id (D-015: a part without
 * one is replaced by Livewire's morph). Ids are unique on the page: they
 * include the Livewire component's id, and a repeat in the same render (a
 * `@foreach`) gets `-2`, `-3` in render order. They never depend on text
 * or open state, so a re-render keeps them.
 *
 * The using class sets `$idPrefix` (`dialog`, `dropdown-menu`, `toast`) and
 * lists in `$idIgnore` the props that must not change the id: open state,
 * data and text.
 *
 * @since 3.0.0
 */
trait HasStablePartIds
{
    /**
     * The resolved base id, once computed.
     *
     * @since 3.0.0
     */
    private ?string $resolvedBaseId = null;

    /**
     * The component's base id: the `id` attribute as given, else one
     * derived from the `wire:model` name, else a hash of the props that
     * aren't text or state. Derived ids include the Livewire component's id
     * and are numbered when they repeat in a render.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function baseId(): string
    {
        if (null !== $this->resolvedBaseId) {
            return $this->resolvedBaseId;
        }

        $id = $this->attributes->get('id');
        if (is_string($id) && '' !== $id) {
            return $this->resolvedBaseId = $id;
        }

        $scope  = $this->livewireScope();
        $prefix = 'apui-'.$this->idPrefix;
        $model  = $this->modelName();
        if (null !== $model) {
            $id = $prefix.'-'.trim(Str::slug(str_replace('.', '-', $model)).'-'.substr(md5($scope."\0".$model), 0, 8), '-');
        } else {
            $props = array_diff_key($this->extractPublicProperties(), array_flip($this->idIgnore ?? []));
            $id    = $prefix.'-'.substr(md5($scope."\0".json_encode(
                [$props, $this->attributes->except(['class'])->getAttributes()],
                JSON_PARTIAL_OUTPUT_ON_ERROR,
            )), 0, 12);
        }

        return $this->resolvedBaseId = app(RenderedIds::class)->claim($scope, $id);
    }

    /**
     * A part's id: the base id and the part name.
     *
     * @since 3.0.0
     *
     * @param  string  $part  The part, such as `content` or `close-trigger`.
     *
     * @return string The id.
     */
    public function partId(string $part): string
    {
        return $this->baseId().'-'.$part;
    }

    /**
     * The `wire:model` name, whatever its modifiers.
     *
     * @since 3.0.0
     *
     * @return string|null The model name.
     */
    public function modelName(): ?string
    {
        $model = $this->attributes->whereStartsWith('wire:model')->first();

        return is_string($model) && '' !== $model ? $model : null;
    }

    /**
     * The open state as an Alpine expression: `$wire.entangle()` of the
     * `wire:model` property (`.live` with `wire:model.live`), else the
     * `open` prop.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function openExpression(): string
    {
        $model = $this->modelName();
        if (null === $model) {
            return $this->open ? 'true' : 'false';
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
    }

    /**
     * Whether the server renders the overlay open: the bound Livewire
     * property's value with `wire:model`, else the `open` prop. The adapter
     * takes over on the client either way.
     *
     * @since 3.0.0
     *
     * @return bool True when open.
     */
    public function isOpen(): bool
    {
        $model = $this->modelName();
        if (null === $model) {
            return $this->open;
        }

        $component = $this->livewireComponent();

        return null !== $component ? (bool) data_get($component, $model) : $this->open;
    }

    /**
     * The attributes for the element that holds the Alpine component: every
     * attribute except `class` (the panel's), `id` and `wire:model`, so event
     * listeners such as `x-on:open-change` land where the adapter dispatches.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function hostAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['class', 'id']);
    }

    /**
     * The Livewire component rendering this one, if any.
     *
     * @since 3.0.0
     *
     * @return object|null The component.
     */
    protected function livewireComponent(): ?object
    {
        try {
            $component = class_exists(Livewire::class) ? Livewire::current() : null;
        } catch (Throwable) {
            return null;
        }

        return is_object($component) ? $component : null;
    }

    /**
     * The id scope: the rendering Livewire component's id, stable across its
     * re-renders, or '' outside one.
     *
     * @since 3.0.0
     *
     * @return string The scope.
     */
    protected function livewireScope(): string
    {
        $component = $this->livewireComponent();

        return null !== $component && method_exists($component, 'getId') ? (string) $component->getId() : '';
    }
}
