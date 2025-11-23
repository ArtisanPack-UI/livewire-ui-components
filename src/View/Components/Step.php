<?php

declare(strict_types=1);
/**
 * Step
 *
 * This file contains the Step class for the ArtisanPack UI Livewire UI Components package.
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
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function iconHTML(): ?string
    {
        return Blade::render("<x-artisanpack-icon name='".$this->icon."' class='w-4 w-4' />");
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.step');
    }
}
