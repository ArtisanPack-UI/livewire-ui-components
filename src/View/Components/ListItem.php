<?php
/**
 * ListItem
 *
 * This file contains the ListItem class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Str;
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

        // Slots
        public mixed $actions = null,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.list-item');
    }
}
