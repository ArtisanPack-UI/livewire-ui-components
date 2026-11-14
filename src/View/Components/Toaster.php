<?php

/**
 * Toaster Component
 *
 * The region that shows the design-system toasts (#51): toasts dispatched
 * from Livewire, flashed to the session, or created from JavaScript.
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

use ArtisanPack\LivewireUiComponents\Support\Toasts;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Toaster Component Class
 *
 * Renders `apuiToaster` (D-019): the region (Zag's group part, a labelled
 * landmark) and a client template that renders each toast with the same
 * markup as `<x-artisanpack-toast>`. It listens for the `toast` browser
 * event, so `$this->dispatch('toast', title: …)` in a Livewire component
 * shows one; toasts flashed with `Toasts::flash()` show on load, once. It
 * is client-rendered, so it's `wire:ignore`, and `@persist`ed so toasts
 * survive `wire:navigate`: put it once per page, in the layout.
 *
 * @since 3.0.0
 */
class Toaster extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The placements the region takes.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const PLACEMENTS = ['top-start', 'top', 'top-end', 'bottom-start', 'bottom', 'bottom-end'];

    /**
     * Extra classes per part (none yet); the parts trait reads it.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $placement  Where the region sits. start and end follow the text direction.
     * @param  int  $max  At most this many show at once; the rest queue.
     * @param  int  $gap  Space between toasts, in px.
     * @param  int  $duration  Milliseconds before a toast dismisses itself, paused on hover and focus. 0 keeps them.
     * @param  string|null  $label  The region's accessible name. Defaults to "Notifications".
     * @param  string|null  $closeLabel  The close buttons' accessible name. Defaults to "Dismiss notification".
     */
    public function __construct(
        public string $placement = 'bottom-end',
        public int $max = 5,
        public int $gap = 8,
        public int $duration = 5000,
        public ?string $label = null,
        public ?string $closeLabel = null,
    ) {}

    /**
     * The region's id: the `id` attribute, else `apui-toaster`.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function toasterId(): string
    {
        $id = $this->attributes->get('id');

        return is_string($id) && '' !== $id ? $id : 'apui-toaster';
    }

    /**
     * The region's accessible name.
     *
     * @since 3.0.0
     *
     * @return string The label.
     */
    public function regionLabel(): string
    {
        return null !== $this->label && '' !== $this->label ? $this->label : __('Notifications');
    }

    /**
     * The close buttons' accessible name.
     *
     * @since 3.0.0
     *
     * @return string The label.
     */
    public function closeTriggerLabel(): string
    {
        return null !== $this->closeLabel && '' !== $this->closeLabel ? $this->closeLabel : __('Dismiss notification');
    }

    /**
     * The `x-data` expression: `apuiToaster()` with the region's options and
     * its translated labels. Flashed toasts arrive through flashedToasts().
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return 'apuiToaster('.Js::from([
            'id'         => $this->toasterId(),
            'placement'  => in_array($this->placement, self::PLACEMENTS, true) ? $this->placement : 'bottom-end',
            'max'        => max(1, $this->max),
            'gap'        => max(0, $this->gap),
            'duration'   => max(0, $this->duration),
            'label'      => $this->regionLabel(),
            'closeLabel' => $this->closeTriggerLabel(),
        ])->toHtml().')';
    }

    /**
     * The toasts flashed to the session, taken out of it, as JSON for a
     * `<script type="application/json">` the toaster reads once and removes,
     * so they don't show again when `wire:navigate` restores the page from
     * history. Null when there are none.
     *
     * @since 3.0.0
     *
     * @return string|null The JSON.
     */
    public function flashedToasts(): ?string
    {
        $toasts = Toasts::pull();

        return [] === $toasts ? null : (string) json_encode($toasts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.toaster');
    }
}
