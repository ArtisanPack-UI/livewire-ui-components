<?php

declare(strict_types=1);
/**
 * ListItem
 *
 * This file contains the ListItem class for the ArtisanPack UI Livewire UI Components package.
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
 * ListItem Class
 *
 * Provides functionality for the ListItem component.
 *
 * @since 1.0.0
 */
class ListItem extends Component
{
    public string $uuid;

    public function __construct(
        public object|array $item,
        public ?string $id = null,
        public string $avatar = 'avatar',
        public string $value = 'name',
        public ?string $subValue = '',
        public ?bool $noSeparator = false,
        public ?bool $noHover = false,
        public ?string $link = null,

        // New icon props
        public ?string $icon = null,           // Custom icon name
        public ?string $iconType = null,       // 'bullet', 'checkmark', 'arrow', 'dot'
        public ?string $iconStatus = null,     // 'new', 'completed', 'error', 'warning', 'info'
        public ?string $iconClass = 'w-4 h-4', // Icon classes
        public ?bool $noIcon = false,          // Disable icon completely

        // Slots
        public mixed $actions = null,
        public mixed $iconSlot = null,         // Custom icon slot
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function getIcon(): ?string
    {
        // Priority: custom icon > iconType > iconStatus > null
        if ($this->icon) {
            return $this->icon;
        }

        if ($this->iconType) {
            return config("livewire-ui-components.icons.list_item.{$this->iconType}");
        }

        if ($this->iconStatus) {
            return config("livewire-ui-components.icons.list_item.status.{$this->iconStatus}");
        }

        return null;
    }

    public function shouldShowIcon(): bool
    {
        return ! $this->noIcon && ($this->getIcon() || $this->iconSlot);
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.list-item');
    }
}
