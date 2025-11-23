<?php

declare(strict_types=1);
/**
 * Alert
 *
 * This file contains the Alert class for the ArtisanPack UI Livewire UI Components package.
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
 * Alert Class
 *
 * Provides functionality for the Alert component.
 *
 * @since 1.0.0
 */
class Alert extends Component
{
    /**
     * @param  ?string  $title  The title of the alert, displayed in bold.
     * @param  ?string  $icon  The icon displayed at the beginning of the alert.
     * @param  ?string  $description  A short description under the title.
     * @param  ?bool  $shadow  Whether to apply a shadow effect to the alert.
     * @param  ?bool  $dismissible  Whether the alert can be dismissed by the user.
     * @param  ?string  $color  Color variant, Tailwind color, or hex code.
     * @param  ?string  $colorAdjustment  Background adjustment (lighter, darker, transparent, subtle).
     * @param  string  $uuid  Unique identifier for the alert instance.
     *
     * @slot  mixed  $actions  Slots for actionable elements like buttons or links.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $description = null,
        public ?bool $shadow = false,
        public ?bool $dismissible = false,
        public ?string $color = null,
        public ?string $colorAdjustment = null,

        // Slots
        public mixed $actions = null,
        public string $uuid = '',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }
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
            'alert',
        );

        return $colorClasses;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.alert');
    }
}
