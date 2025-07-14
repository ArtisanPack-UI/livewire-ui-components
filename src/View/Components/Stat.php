<?php
/**
 * Stat
 *
 * This file contains the Stat class for the ArtisanPack UI Livewire UI Components package.
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
 * Stat Class
 *
 * Provides functionality for the Stat component.
 *
 * @since 1.0.0
 */

class Stat extends Component
{
    public string $uuid;

    public string $tooltipPosition = 'lg:tooltip-top';

    public function __construct(
        public ?string $id = null,
        public ?string $value = null,
        public ?string $icon = null,
        public ?string $color = '',
        public ?string $title = null,
        public ?string $description = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,

    ) {
        $this->uuid = "mary" . md5(serialize($this)) . $id;
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div
                    {{ $attributes->class(["bg-base-100 rounded-lg px-5 py-4  w-full", "lg:tooltip $tooltipPosition" => $tooltip]) }}

                    @if($tooltip)
                        data-tip="{{ $tooltip }}"
                    @endif
                >
                    <div class="flex items-center gap-3">
                        @if($icon)
                            <div class="  {{ $color }}">
                                <x-mary-icon :name="$icon" class="w-9 h-9" />
                            </div>
                        @endif

                        <div class="text-left rtl:text-right">
                            @if($title)
                                <div class="text-xs text-base-content/50 whitespace-nowrap">{{ $title }}</div>
                            @endif

                            <div class="font-black text-xl">{{ $value ?? $slot }}</div>

                            @if($description)
                                <div class="stat-desc">{{ $description }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            HTML;
    }
}
