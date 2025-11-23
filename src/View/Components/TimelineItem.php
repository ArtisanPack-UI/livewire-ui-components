<?php

declare(strict_types=1);
/**
 * TimelineItem
 *
 * This file contains the TimelineItem class for the ArtisanPack UI Livewire UI Components package.
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
 * TimelineItem Class
 *
 * Provides functionality for the TimelineItem component.
 *
 * @since 1.0.0
 */
class TimelineItem extends Component
{
    public string $uuid;

    public function __construct(
        public string $title,
        public ?string $id = null,
        public ?string $subtitle = null,
        public ?string $description = null,
        public ?string $icon = null,
        public ?bool $pending = false,
        public ?bool $first = false,
        public ?bool $last = false,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.timeline-item');
    }
}
