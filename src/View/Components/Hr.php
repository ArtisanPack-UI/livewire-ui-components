<?php
/**
 * Hr
 *
 * This file contains the Hr class for the ArtisanPack UI Livewire UI Components package.
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
 * Hr Class
 *
 * Provides functionality for the Hr component.
 *
 * @since 1.0.0
 */

class Hr extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $target = null,
    ) {
        $this->uuid = "mary" . md5(serialize($this)) . $id;
    }

    public function progressTarget(): ?string
    {
        if ($this->target == 1) {
            return $this->attributes->whereStartsWith('target')->first();
        }

        return $this->target;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div class="h-[2px] border-t-[length:var(--border)] border-t-base-content/10 my-5">
                    <progress
                        class="progress progress-primary hidden h-[1px]"
                        wire:loading.class="!h-[length:var(--border)] !block"

                        @if($progressTarget())
                            wire:target="{{ $progressTarget() }}"
                        @endif></progress>
                </div>
            HTML;
    }
}
