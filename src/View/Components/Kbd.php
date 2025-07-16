<?php
/**
 * Kbd
 *
 * This file contains the Kbd class for the ArtisanPack UI Livewire UI Components package.
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
 * Kbd Class
 *
 * Provides functionality for the Kbd component.
 *
 * @since 1.0.0
 */

class Kbd extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <kbd
                    wire:key="{{ $uuid }}"
                    {{ $attributes->merge(["class" => "kbd"]) }}
                >
                    {{ $slot }}
                </kbd>
            HTML;
    }
}
