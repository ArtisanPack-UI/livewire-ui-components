<?php

declare(strict_types=1);
/**
 * Breadcrumbs
 *
 * This file contains the Breadcrumbs class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Breadcrumbs Class
 *
 * Provides functionality for the Breadcrumbs component.
 *
 * @since 1.0.0
 */
class Breadcrumbs extends Component
{
    public string $uuid;

    /**
     * @param  array  $items  The steps that should be displayed. Each element supports the keys 'label', 'link', 'icon' and 'tooltip'.
     * @param  string  $separator  Any supported icon name, 'o-slash' by default.
     * @param  ?string  $linkItemClass  The classes that should be applied to each item with a link.
     * @param  ?string  $textItemClass  The classes that should be applied to each item without a link.
     * @param  ?string  $iconClass  The classes that should be applied to each items icon.
     * @param  ?string  $separatorClass  The classes that should be applied to each separator.
     * @param  ?bool  $noWireNavigate  If true, the component will not use wire:navigate on links.
     */
    public function __construct(
        public ?string $id = null,
        public array $items = [],
        public string $separator = 'o-chevron-right',
        public ?string $linkItemClass = 'hover:underline text-sm',
        public ?string $textItemClass = 'text-sm',
        public ?string $iconClass = 'h-4 w-4',
        public ?string $separatorClass = 'h-3 w-3 mx-1 text-base-content/40',
        public ?bool $noWireNavigate = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function tooltip(array $element): ?string
    {
        return $element['tooltip'] ?? $element['tooltip-left'] ?? $element['tooltip-right'] ?? $element['tooltip-bottom'] ?? $element['tooltip-top'] ?? null;
    }

    public function tooltipPosition(array $element): string
    {
        return match (true) {
            isset($element['tooltip-left'])   => 'lg:tooltip-left',
            isset($element['tooltip-right'])  => 'lg:tooltip-right',
            isset($element['tooltip-bottom']) => 'lg:tooltip-bottom',
            default                           => 'lg:tooltip-top',
        };
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.breadcrumbs');
    }
}
