<?php
/**
 * MenuSub
 *
 * This file contains the MenuSub class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Str;
use Illuminate\View\Component;
/**
 * MenuSub Class
 *
 * Provides functionality for the MenuSub component.
 *
 * @since 1.0.0
 */

class MenuSub extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $iconClasses = null,
        public bool $open = false,
        public ?bool $hidden = false,
        public ?bool $disabled = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        if ($this->hidden === true) {
            return '';
        }

        return view('livewire-ui-components::components.menu-sub');
    }
}
