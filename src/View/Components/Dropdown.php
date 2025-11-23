<?php

declare(strict_types=1);
/**
 * Dropdown
 *
 * This file contains the Dropdown class for the ArtisanPack UI Livewire UI Components package.
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
 * Dropdown Class
 *
 * Provides functionality for the Dropdown component.
 *
 * @since 1.0.0
 */
class Dropdown extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = 'o-chevron-down',
        public ?bool $right = false,
        public ?bool $top = false,
        public ?bool $noXAnchor = false,
        // Slots
        public mixed $trigger = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.dropdown');
    }
}
