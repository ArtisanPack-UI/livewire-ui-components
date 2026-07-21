<?php

declare(strict_types=1);
/**
 * Errors
 *
 * This file contains the Errors class for the ArtisanPack UI Livewire UI Components package.
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
 * Errors Class
 *
 * Provides functionality for the Errors component.
 *
 * @since 1.0.0
 */
class Errors extends BaseComponent
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $icon = 'o-x-circle',
        public ?array $only = [],
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.errors');
    }
}
