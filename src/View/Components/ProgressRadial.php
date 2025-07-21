<?php
/**
 * ProgressRadial
 *
 * This file contains the ProgressRadial class for the ArtisanPack UI Livewire UI Components package.
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
 * ProgressRadial Class
 *
 * Provides functionality for the ProgressRadial component.
 *
 * @since 1.0.0
 */

class ProgressRadial extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?float $value = 0,
        public ?string $unit = '%'
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.progress-radial');
    }
}
