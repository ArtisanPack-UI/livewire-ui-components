<?php

declare(strict_types=1);
/**
 * Loading
 *
 * This file contains the Loading class for the ArtisanPack UI Livewire UI Components package.
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
 * Loading Class
 *
 * Provides functionality for the Loading component.
 *
 * @since 1.0.0
 */
class Loading extends BaseComponent
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,

        // New props
        public ?string $type = null,           // 'css', 'svg', 'custom'
        public ?string $icon = null,           // Custom icon name for SVG type
        public ?string $customSvg = null,      // Custom SVG content
        public ?bool $animated = true,         // Enable/disable animation
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function getLoadingType(): string
    {
        if ($this->type) {
            return $this->type;
        }

        return config('artisanpack.livewire-ui-components.icons.loading.default_type', 'css');
    }

    public function getLoadingIcon(): ?string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return config('artisanpack.livewire-ui-components.icons.loading.spinner');
    }

    public function shouldUseSvg(): bool
    {
        return 'svg' === $this->getLoadingType() || $this->icon || $this->customSvg;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.loading');
    }
}
