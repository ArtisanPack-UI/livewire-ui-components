<?php
/**
 * Steps
 *
 * This file contains the Steps class for the ArtisanPack UI Livewire UI Components package.
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
 * Steps Class
 *
 * Provides functionality for the Steps component.
 *
 * @since 1.0.0
 */

class Steps extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public bool $vertical = false,
        public ?string $stepsColor = 'step-neutral',
        public ?string $stepperClasses = null

    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                <div
                        x-data="{
                                steps: [],
                                current: @entangle($attributes->wire('model')),
                                init() {
                                    // Fix weird issue when navigating back
                                    document.addEventListener('livewire:navigating', () => {
                                        document.querySelectorAll('.step').forEach(el =>  el.remove());
                                    });
                                }
                        }"
                    >
                        <!-- STEP LABELS -->
                        <ul class="steps [&>*:nth-child(2)]:before:hidden {{ $stepperClasses }}">
                            <template x-for="(step, index) in steps" :key="index">
                                <li
                                    class="step"
                                    :data-content="!step.icon ? step.dataContent || (index + 1) : ''"
                                    :class="(index + 1 <= current) && '{{ $stepsColor }} ' + step.classes"
                                >
                                        <template x-if="step.icon">
                                            <span x-html="step.icon" class="step-icon"></span>
                                        </template>
                                        <span x-html="step.text"></span>
                                </li>
                            </template>
                        </ul>

                        <!-- STEP PANELS-->
                        <div {{ $attributes->whereDoesntStartWith('wire') }}>
                            {{ $slot }}
                        </div>

                        <!-- Force Tailwind compile steps color -->
                        <span class="hidden step-primary step-error step-success step-neutral step-info step-accent"></span>
                    </div>
            BLADE;
    }
}
