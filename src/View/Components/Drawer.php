<?php
/**
 * Drawer
 *
 * This file contains the Drawer class for the ArtisanPack UI Livewire UI Components package.
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
use Livewire\WireDirective;
/**
 * Drawer Class
 *
 * Provides functionality for the Drawer component.
 *
 * @since 1.0.0
 */

class Drawer extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?bool $right = false,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $separator = false,
        public ?bool $withCloseButton = false,
        public ?bool $closeOnEscape = false,
        public ?bool $withoutTrapFocus = false,

        //Slots
        public ?string $actions = null
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function id(): string
    {
        return $this->id ?? $this->attributes?->wire('model')->value();
    }

    public function modelName(): WireDirective
    {
        return $this->attributes->wire('model');
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.drawer');
    }
    }
}
