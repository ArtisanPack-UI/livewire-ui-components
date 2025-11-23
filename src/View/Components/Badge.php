<?php

declare(strict_types=1);
/**
 * Badge
 *
 * This file contains the Badge class for the ArtisanPack UI Livewire UI Components package.
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

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Badge Class
 *
 * Provides functionality for the Badge component.
 *
 * @since 1.0.0
 */
class Badge extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $value = null,
        public ?string $color = null,
        public ?string $colorAdjustment = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @since 1.0.0
     */
    public function getColorClasses(): array
    {
        if (! $this->color) {
            return [];
        }

        $colorGenerator = new ColorGenerator;

        // Use ColorGenerator for color resolution
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->color,
            $this->colorAdjustment,
            'badge',
        );

        return $colorClasses;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.badge');
    }
}
