<?php

declare(strict_types=1);
/**
 * Accordion
 *
 * This file contains the Accordion class for the ArtisanPack UI Livewire UI Components package.
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
 * Accordion Class
 *
 * Provides functionality for the Accordion component.
 *
 * @since 1.0.0
 */
class Accordion extends Component
{
    public bool $usePlusMinus;

    public function __construct(
        public ?string $id = null,
        public ?bool $noJoin = false,
        public string $uuid = '',
        mixed $collapsePlusMinus = false,
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.uniqid().$id;
        }
        $this->usePlusMinus = (true === $collapsePlusMinus || '' === $collapsePlusMinus);
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.accordion');
    }
}
