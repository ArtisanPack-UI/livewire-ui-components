<?php
/**
 * Tab
 *
 * This file contains the Tab class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
/**
 * Tab Class
 *
 * Provides functionality for the Tab component.
 *
 * @since 1.0.0
 */

class Tab extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $icon = null,
        public bool $disabled = false,
        public bool $hidden = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function tabLabel(string $label): string
    {
        $fromLabel = $this->label ? $this->label : $label;

        if ($this->icon) {
            return Blade::render("
                <x-artisanpack-icon name='" . $this->icon . "' @class([
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

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                    <a
                        class="hidden tab"
                        :class="{ 'tab-active': selected === '{{ $name }}' }"
                        data-name="{{ $name }}"
                        x-init="
                                const newItem = { name: '{{ $name }}', label: {{ json_encode($tabLabel($label)) }}, disabled: {{ $disabled ? 'true' : 'false' }}, hidden: {{ $hidden ? 'true' : 'false' }} };
                                const index = tabs.findIndex(item => item.name === '{{ $name }}');
                                index !== -1 ? tabs[index] = newItem : tabs.push(newItem);

                                Livewire.hook('morph.removed', ({el}) => {
                                    if (el.getAttribute('data-name') == '{{ $name }}'){
                                        tabs = tabs.filter(i => i.name !== '{{ $name }}')
                                    }
                                })
                            "
                    ></a>

                    <div x-show="selected === '{{ $name }}'" role="tabpanel" {{ $attributes->class("tab-content py-5 px-1") }}>
                        {{ $slot }}
                    </div>
                HTML;
    }
}
