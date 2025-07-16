<?php
/**
 * Step
 *
 * This file contains the Step class for the ArtisanPack UI Livewire UI Components package.
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
 * Step Class
 *
 * Provides functionality for the Step component.
 *
 * @since 1.0.0
 */

class Step extends Component
{
    public string $uuid;

    public function __construct(
        public int $step,
        public string $text,
        public ?string $id = null,
        public ?string $icon = null,
        public ?string $stepClasses = null,
        public ?string $dataContent = null,

    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function iconHTML(): ?string
    {
        return Blade::render("<x-artisanpack-icon name='" . $this->icon . "' class='w-4 w-4' />");
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                    <div
                        class="hidden"
                        x-init="steps.push({ step: '{{ $step }}', text: '{{ $text }}', classes: '{{ $stepClasses }}' @if($icon) , icon: {{ json_encode($iconHTML()) }}  @endif @if($dataContent), dataContent: '{{ $dataContent }}' @endif })"
                    ></div>

                    <div x-show="current == '{{ $step }}'" {{ $attributes->class("px-1") }} >
                        {{ $slot }}
                    </div>
            BLADE;
    }
}
