<?php
/**
 * Chart
 *
 * This file contains the Chart class for the ArtisanPack UI Livewire UI Components package.
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
 * Chart Class
 *
 * Provides functionality for the Chart component.
 *
 * @since 1.0.0
 */

class Chart extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div
                    wire:key="{{ $uuid }}-{{ rand() }}"
                    x-data="{
                        settings: @entangle($attributes->wire('model')),
                        init(){
                            new Chart($refs.chart, this.settings);
                        }
                    }"

                    {{ $attributes->class(["relative"]) }}
                >
                    <canvas x-ref="chart"></canvas>
                </div>
            HTML;
    }
}
