<?php
/**
 * Profile
 *
 * This file contains the Profile class for the ArtisanPack UI Livewire UI Components package.
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
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

/**
 * Profile Class
 *
 * Provides functionality for the Profile component, combining Avatar and Dropdown components.
 *
 * @since 1.0.0
 */
class Profile extends Component
{
    public string $uuid;

    /**
     * @param  ?string  $image  The URL of the avatar image.
     * @param  ?string  $alt  The HTML `alt` attribute
     * @param  ?string  $placeholder  The placeholder of the avatar.
     * @param  ?string  $title  The title text displayed beside the avatar.
     * @param  ?string  $subtitle  The subtitle text displayed beside the avatar.
     * @param  ?string  $color  Color variant, Tailwind color, or hex code for placeholder/border.
     * @param  ?string  $colorAdjustment  Background adjustment (lighter, darker, transparent, subtle).
     * @param  ?bool  $right  Position dropdown to the right.
     * @param  ?bool  $top  Position dropdown above the trigger.
     * @param  ?bool  $noXAnchor  Use native dropdown positioning instead of Alpine anchor.
     */
    public function __construct(
        public ?string $id = null,
        
        // Avatar properties
        public ?string $image = '',
        public ?string $alt = '',
        public ?string $placeholder = '',
        public ?string $color = null,
        public ?string $colorAdjustment = null,
        public ?string $title = null,
        public ?string $subtitle = null,
        
        // Dropdown properties
        public ?bool $right = false,
        public ?bool $top = false,
        public ?bool $noXAnchor = false
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @return array
     * @since 1.1.0
     */
    public function getColorClasses(): array
    {
        if (!$this->color) {
            return [];
        }

        $colorGenerator = new ColorGenerator();
        
        // Use ColorGenerator for color resolution
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->color, 
            $this->colorAdjustment, 
            'avatar'
        );
        
        return $colorClasses;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.profile');
    }
}