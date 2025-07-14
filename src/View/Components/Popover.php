<?php
/**
 * Popover
 *
 * This file contains the Popover class for the ArtisanPack UI Livewire UI Components package.
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
 * Popover Class
 *
 * Provides functionality for the Popover component.
 *
 * @since 1.0.0
 */

class Popover extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $position = "bottom",
        public ?string $offset = "10",

        // Slots
        public mixed $trigger = null,
        public mixed $content = null

    ) {
        $this->uuid = "mary" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div
                    x-cloak
                    x-data="{
                            open: false,
                            timer: null,
                            show() {
                                this.open = true
                                clearTimeout(this.timer)
                            },
                            hide(){
                                this.timer = setTimeout(() => this.open = false, 300)
                            }
                        }"
                >
                  <!-- TRIGGER -->
                  <div
                    x-ref="myTrigger"
                    @mouseover="show()"
                    @mouseout="hide()"
                    {{ $trigger->attributes->class(["w-fit cursor-pointer"]) }}
                  >
                    {{ $trigger }}
                  </div>

                  <!-- CONTENT -->
                  <div
                    x-show="open"
                    x-anchor.{{ $position }}.offset.{{ $offset }}="$refs.myTrigger"
                    x-transition
                    @mouseover="show()"
                    @mouseout="hide()"
                    {{ $content->attributes->class(["z-[1] shadow-xl border-[length:var(--border)] border-base-content/10 w-fit p-3 rounded-md bg-base-100"]) }}
                  >
                    {{ $content }}
                  </div>
                </div>
            HTML;
    }
}
