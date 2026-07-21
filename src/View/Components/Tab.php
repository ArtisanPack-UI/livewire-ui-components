<?php

declare(strict_types=1);
/**
 * Tab
 *
 * This file contains the Tab class for the ArtisanPack UI Livewire UI Components package.
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

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;

/**
 * Tab Class
 *
 * Provides functionality for the Tab component.
 *
 * @since 1.0.0
 */
class Tab extends BaseComponent
{
    public string $uuid;

    /**
     * Creates a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $id  The component ID.
     * @param  string|null  $name  The name of the tab.
     * @param  string|null  $label  The label for the tab.
     * @param  string|null  $icon  The icon for the tab.
     * @param  bool  $disabled  Whether the tab is disabled.
     * @param  bool  $hidden  Whether the tab is hidden.
     * @param  string|null  $contentClasses  Custom CSS classes for the tab content panel. Overrides default padding.
     *
     * @return void
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $icon = null,
        public bool $disabled = false,
        public bool $hidden = false,
        public ?string $contentClasses = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Renders the tab label with an optional icon.
     *
     * @since 1.0.0
     *
     * @param  string  $label  The label text.
     */
    public function tabLabel(string $label): string
    {
        $fromLabel = $this->label ? $this->label : $label;

        if ($this->icon) {
            return Blade::render("
                <x-artisanpack-icon name='".$this->icon."' @class([
                'me-2',
                'whitespace-nowrap',
                'text-base-content/30 cursor-not-allowed' => '$this->disabled'
                ])>
                    <x-slot:label>
                        {$fromLabel}
                    </x-slot:label>
                </x-icon>
            ");
        }

        return Blade::render("
            <div @class([
                'whitespace-nowrap',
                'text-base-content/30 cursor-not-allowed' => '$this->disabled'
                ])>
                {$fromLabel}
            </div>
        ");
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.tab');
    }
}
