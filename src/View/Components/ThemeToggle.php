<?php

declare(strict_types=1);
/**
 * ThemeToggle
 *
 * This file contains the ThemeToggle class for the ArtisanPack UI Livewire UI Components package.
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
 * ThemeToggle Class
 *
 * Provides functionality for the ThemeToggle component.
 *
 * @since 1.0.0
 */
class ThemeToggle extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $value = null,
        public ?string $light = 'Light',
        public ?string $dark = 'Dark',
        public ?string $lightTheme = 'light',
        public ?string $darkTheme = 'dark',
        public ?string $lightClass = 'light',
        public ?string $darkClass = 'dark',
        public ?bool $withLabel = false,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.theme-toggle');
    }
}
