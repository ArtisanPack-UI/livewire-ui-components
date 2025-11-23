<?php

declare(strict_types=1);
/**
 * Signature
 *
 * This file contains the Signature class for the ArtisanPack UI Livewire UI Components package.
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
 * Signature Class
 *
 * Provides functionality for the Signature component.
 *
 * @since 1.0.0
 */
class Signature extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $height = '250',
        public ?string $clearText = 'Clear',
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label text-xs pt-1',
        public ?array $config = [],
        public ?string $clearBtnStyle = null,

        // Validations
        public ?string $errorClass = 'text-error text-xs pt-1',
        public ?string $errorField = null,
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function setup(): string
    {
        return json_encode(array_merge([], $this->config));
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
        return view('livewire-ui-components::components.signature');
    }
}
