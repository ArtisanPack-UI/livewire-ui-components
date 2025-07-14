<?php
/**
 * Form
 *
 * This file contains the Form class for the ArtisanPack UI Livewire UI Components package.
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
 * Form Class
 *
 * Provides functionality for the Form component.
 *
 * @since 1.0.0
 */

class Form extends Component
{
    public function __construct(

        // Slots
        public mixed $actions = null,
        public ?bool $noSeparator = false,
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                <form
                    {{ $attributes->whereDoesntStartWith('class') }}
                    {{ $attributes->class(['grid grid-flow-row auto-rows-min gap-3']) }}
                >

                    {{ $slot }}

                    @if ($actions)
                        @if(!$noSeparator)
                            <hr class="border-t-[length:var(--border)] border-base-content/10 my-3" />
                        @else
                            <div></div>
                        @endif

                        <div {{ $actions->attributes->class(["flex justify-end gap-3"]) }}>
                            {{ $actions}}
                        </div>
                    @endif
                </form>
                BLADE;
    }
}
