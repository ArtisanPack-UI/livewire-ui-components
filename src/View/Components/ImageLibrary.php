<?php

declare(strict_types=1);
/**
 * ImageLibrary
 *
 * This file contains the ImageLibrary class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Collection;

/**
 * ImageLibrary Class
 *
 * Provides functionality for the ImageLibrary component.
 *
 * @since 1.0.0
 */
class ImageLibrary extends BaseComponent
{
    public string $uuid;

    public string $mimes = 'image/png, image/jpeg';

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $hideErrors = false,
        public ?bool $hideProgress = false,
        public ?string $changeText = 'Change',
        public ?string $cropText = 'Crop',
        public ?string $removeText = 'Remove',
        public ?string $cropTitleText = 'Crop image',
        public ?string $cropCancelText = 'Cancel',
        public ?string $cropSaveText = 'Crop',
        public ?string $addFilesText = 'Add images',
        public ?array $cropConfig = [],
        public Collection $preview = new Collection,

        // Drag and Drop
        public ?bool $withDragDrop = false,
        public ?string $dragDropText = 'Drop images here',
        public ?string $dragDropMultipleText = 'Drop {count} images here',
        public ?string $dragDropClass = null,

    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->wire('model');
    }

    public function libraryName(): ?string
    {
        return $this->attributes->wire('library');
    }

    public function validationMessage(string $message): string
    {
        return str($message)->after('field');
    }

    public function cropSetup(): string
    {
        return json_encode(array_merge([
            'autoCropArea'     => 1,
            'viewMode'         => 1,
            'dragMode'         => 'move',
            'checkCrossOrigin' => false,
        ], $this->cropConfig));
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.image-library');
    }
}
