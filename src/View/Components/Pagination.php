<?php

declare(strict_types=1);
/**
 * Pagination
 *
 * This file contains the Pagination class for the ArtisanPack UI Livewire UI Components package.
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

        // New variant parameters
        public ?bool $simple = false,
        public ?bool $compact = false,
        public ?bool $advanced = false,
        public ?bool $minimal = false,
        public ?bool $hidePerPage = false,
        public ?bool $hidePageInfo = false,
        public ?bool $showFirstLast = true,
        public ?bool $showJumpTo = false,
        public ?int $onEachSide = 1,
        public ?string $size = 'default', // 'sm', 'default', 'lg'
        public ?bool $showPageInfo = true,
        public ?string $pageInfoTemplate = 'Showing {from} to {to} of {total} results',
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->filter(function ($value, $key) {
            return str_starts_with($key, 'wire:model.live');
        })->first();
    }

    public function isShowable(): bool
    {
        return ! empty($this->modelName()) && $this->rows instanceof LengthAwarePaginator && $this->rows->isNotEmpty();
    }

    public function getVariantClasses(): string
    {
        $classes = ['artisanpack-table-pagination'];

        if ($this->simple) {
            $classes[] = 'pagination-simple';
        } elseif ($this->compact) {
            $classes[] = 'pagination-compact';
        } elseif ($this->advanced) {
            $classes[] = 'pagination-advanced';
        } elseif ($this->minimal) {
            $classes[] = 'pagination-minimal';
        } else {
            $classes[] = 'pagination-default';
        }

        if ('default' !== $this->size) {
            $classes[] = 'pagination-'.$this->size;
        }

        return implode(' ', $classes);
    }

    public function shouldShowPerPageSelector(): bool
    {
        return ! $this->hidePerPage && ! $this->simple && ! $this->minimal && $this->isShowable();
    }

    public function shouldShowPageInfo(): bool
    {
        return $this->showPageInfo && ! $this->hidePageInfo && ! $this->simple && ! $this->minimal;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.pagination');
    }
}
