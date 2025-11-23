<?php

declare(strict_types=1);
/**
 * Modal
 *
 * This file contains the Modal class for the ArtisanPack UI Livewire UI Components package.
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
 * Modal Class
 *
 * Provides functionality for the Modal component.
 *
 * @since 1.0.0
 */
class Modal extends Component
{
    public function __construct(
        public ?string $id = '',
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?string $boxClass = null,
        public ?bool $separator = false,
        public ?bool $persistent = false,
        public ?bool $withoutTrapFocus = false,

        // Slots
        public ?string $actions = null,
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.modal');
    }
}
