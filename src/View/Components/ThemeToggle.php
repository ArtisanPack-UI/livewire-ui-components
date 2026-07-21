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

/**
 * ThemeToggle Class
 *
 * Provides functionality for the ThemeToggle component.
 *
 * @since 1.0.0
 */
class ThemeToggle extends BaseComponent
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
        $themes = applyFilters('ap.livewireUiComponents.themeColors', $this->themes());

        $this->light      = $themes['light']['label'] ?? $this->light;
        $this->lightTheme = $themes['light']['theme'] ?? $this->lightTheme;
        $this->lightClass = $themes['light']['class'] ?? $this->lightClass;
        $this->dark       = $themes['dark']['label'] ?? $this->dark;
        $this->darkTheme  = $themes['dark']['theme'] ?? $this->darkTheme;
        $this->darkClass  = $themes['dark']['class'] ?? $this->darkClass;

        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Get the resolved theme configuration as an array.
     *
     * This is the payload passed to the `ap.livewireUiComponents.themeColors`
     * filter so subscribers can rename themes, swap class strings, or
     * change labels without editing the component.
     *
     * @since 2.1.0
     *
     * @return array{light: array{label: ?string, theme: ?string, class: ?string}, dark: array{label: ?string, theme: ?string, class: ?string}}
     */
    public function themes(): array
    {
        return [
            'light' => [
                'label' => $this->light,
                'theme' => $this->lightTheme,
                'class' => $this->lightClass,
            ],
            'dark' => [
                'label' => $this->dark,
                'theme' => $this->darkTheme,
                'class' => $this->darkClass,
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.theme-toggle');
    }
}
