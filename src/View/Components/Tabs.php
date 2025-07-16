<?php
/**
 * Tabs
 *
 * This file contains the Tabs class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;
/**
 * Tabs Class
 *
 * Provides functionality for the Tabs component.
 *
 * @since 1.0.0
 */

class Tabs extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $selected = null,
        public string $labelClass = 'font-semibold pb-1',
        public string $activeClass = 'border-b-[length:var(--border)] border-b-base-content/50',
        public string $labelDivClass = 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto',
        public string $tabsClass = 'relative w-full',
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                    <div
                        x-data="{
                                tabs: [],
                                selected:
                                    @if($selected)
                                        '{{ $selected }}'
                                    @else
                                        @entangle($attributes->wire('model'))
                                    @endif
                                 ,
                                 init() {
                                     // Fix weird issue when navigating back
                                     document.addEventListener('livewire:navigating', () => {
                                         document.querySelectorAll('.tab').forEach(el =>  el.remove());
                                     });
                                 }
                        }"
                        class="{{ $tabsClass }}"
                        x-class="font-semibold pb-1 border-b-[length:var(--border)] border-b-base-content/50 border-b-base-content/10 flex overflow-x-auto scrollbar-hide relative w-full"
                    >
                        <!-- TAB LABELS -->
                        <div class="{{ $labelDivClass }}">
                            <template x-for="tab in tabs">
                                <a
                                    role="tab"
                                    x-html="tab.label"
                                     @click="tab.disabled ? null: selected = tab.name"
                                    :class="{ '{{ $activeClass }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                                    class="tab {{ $labelClass }}"></a>
                            </template>
                        </div>

                        <!-- TAB CONTENT -->
                        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class(["block"]) }}>
                            {{ $slot }}
                        </div>
                    </div>
                HTML;
    }
}
