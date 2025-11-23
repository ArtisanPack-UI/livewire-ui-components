<?php

declare(strict_types=1);
/**
 * Swap
 *
 * This file contains the Swap class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;

/**
 * Swap Class
 *
 * Provides functionality for the Swap component.
 *
 * @since 1.0.0
 */
class Swap extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id        = null,
        public ?string $true      = null,
        public ?string $false     = null,
        public ?string $trueIcon  = 'o-sun',
        public ?string $falseIcon = 'o-moon',
        public ?string $iconSize  = 'h-5 w-5',
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.swap');
    }
}
