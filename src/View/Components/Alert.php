<?php
/**
 * Alert
 *
 * This file contains the Alert class for the ArtisanPack UI Livewire UI Components package.
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
 * Alert Class
 *
 * Provides functionality for the Alert component.
 *
 * @since 1.0.0
 */

class Alert extends Component
{
    public string $uuid;

    /**
     * @param ?string  $title  The title of the alert, displayed in bold.
     * @param ?string  $icon  The icon displayed at the beginning of the alert.
     * @param ?string  $description  A short description under the title.
     * @param ?bool  $shadow  Whether to apply a shadow effect to the alert.
     * @param ?bool  $dismissible  Whether the alert can be dismissed by the user.
     * @slot  mixed  $actions  Slots for actionable elements like buttons or links.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $description = null,
        public ?bool $shadow = false,
        public ?bool $dismissible = false,

        // Slots
        public mixed $actions = null
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.alert');
    }
}