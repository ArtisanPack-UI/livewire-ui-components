<?php

declare(strict_types=1);
/**
 * Password
 *
 * This file contains the Password class for the ArtisanPack UI Livewire UI Components package.
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
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Password Class
 *
 * Provides functionality for the Password component.
 *
 * @since 1.0.0
 */
class Password extends Component
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?bool $inline = false,
        public ?bool $clearable = false,

        // Password
        public ?string $passwordIcon = 'o-eye-slash',
        public ?string $passwordVisibleIcon = 'o-eye',
        public ?bool $right = false,
        public ?bool $onlyPassword = false,

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
        public string $uuid = '',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }

        // Cannot use a left icon when password toggle should be shown on the left side.
        if (($this->icon && ! $this->right) && ! $this->onlyPassword) {
            throw new Exception('Cannot use `icon` without providing `right` or `onlyPassword`.');
        }

        // Cannot use a right icon when password toggle should be shown on the right side.
        if (($this->iconRight && $this->right) && ! $this->onlyPassword) {
            throw new Exception('Cannot use `iconRight` when providing `right` and not providing `onlyPassword`.');
        }
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function placeToggleLeft(): bool
    {
        return (! $this->icon && ! $this->right) && ! $this->onlyPassword;
    }

    public function placeToggleRight(): bool
    {
        return (! $this->iconRight && $this->right) && ! $this->onlyPassword;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.password');
    }
}
