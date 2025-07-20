<?php
/**
 * Code
 *
 * This file contains the Code class for the ArtisanPack UI Livewire UI Components package.
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
 * Code Class
 *
 * Provides functionality for the Code component.
 *
 * @since 1.0.0
 */

class Code extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = '',
        public string $language = 'javascript',
        public ?string $lightTheme = 'github_light_default',
        public ?string $darkTheme = 'github_dark',
        public ?string $lightClass = "light",
        public ?string $darkClass = "dark",
        public string $height = '200px',
        public string $lineHeight = '2',
        public bool $printMargin = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.code');
    }
}
