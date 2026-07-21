<?php

declare(strict_types=1);
/**
 * Pin
 *
 * This file contains the Pin class for the ArtisanPack UI Livewire UI Components package.
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
 * Pin Class
 *
 * Provides functionality for the Pin component.
 *
 * @since 1.0.0
 */
class Pin extends BaseComponent
{
    public string $uuid;

    public function __construct(
        public int $size,
        public ?string $id = null,
        public ?bool $numeric = false,
        public ?bool $hide = false,
        public ?string $hideType = 'disc',

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error text-xs pt-2',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.pin');
    }
}
