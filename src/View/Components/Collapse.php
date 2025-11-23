<?php

declare(strict_types=1);
/**
 * Collapse
 *
 * This file contains the Collapse class for the ArtisanPack UI Livewire UI Components package.
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

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Collapse Class
 *
 * Provides functionality for the Collapse component.
 *
 * @since 1.0.0
 */
class Collapse extends Component
{
    public string $uuid;

    public bool $collapsePlusMinus = false;

    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?bool $separator = false,
        public ?bool $noIcon = false,
        mixed $collapsePlusMinus = false,

        // Slots
        public mixed $heading = null,
        public mixed $content = null,
    ) {
        $this->uuid              = 'artisanpack'.uniqid().$id;
        $this->collapsePlusMinus = (true === $collapsePlusMinus || '' === $collapsePlusMinus);
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.collapse');
    }
}
