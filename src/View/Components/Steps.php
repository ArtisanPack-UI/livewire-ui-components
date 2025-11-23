<?php

declare(strict_types=1);
/**
 * Steps
 *
 * This file contains the Steps class for the ArtisanPack UI Livewire UI Components package.
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
        public ?string $stepperClasses = null,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.steps');
    }
}
