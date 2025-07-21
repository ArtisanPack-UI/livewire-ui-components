<?php
/**
 * Pagination
 *
 * This file contains the Pagination class for the ArtisanPack UI Livewire UI Components package.
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

use ArrayAccess;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
/**
 * Pagination Class
 *
 * Provides functionality for the Pagination component.
 *
 * @since 1.0.0
 */

class Pagination extends Component
{
    public string $uuid;

    public function __construct(
        public ArrayAccess|array $rows,
        public ?string $id = null,
        public ?array $perPageValues = [10, 20, 50, 100],
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model.live')->first();
    }

    public function isShowable(): bool
    {
        return ! empty($this->modelName()) && $this->rows instanceof LengthAwarePaginator && $this->rows->isNotEmpty();
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.pagination');
    }
}
