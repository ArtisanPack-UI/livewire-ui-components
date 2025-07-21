<?php
/**
 * Tabs
 *
 * This file contains the Tabs class for the ArtisanPack UI Livewire UI Components package.
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
 * Tabs Class
 *
 * Provides functionality for the Tabs component.
 *
 * @since 1.0.0
 */

class Tabs extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $selected = null,
        public string $labelClass = 'font-semibold pb-1',
        public string $activeClass = 'border-b-[length:var(--border)] border-b-base-content/50',
        public string $labelDivClass = 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto',
        public string $tabsClass = 'relative w-full',
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.tabs');
    }
}
