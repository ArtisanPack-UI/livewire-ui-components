<?php

declare(strict_types=1);
/**
 * Editor
 *
 * This file contains the Editor class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\Support\Js;

/**
 * Editor Class
 *
 * Provides functionality for the Editor component.
 *
 * @since 1.0.0
 */
class Editor extends BaseComponent
{
    public string $uuid;

    public string $uploadUrl;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $disk = 'public',
        public ?string $folder = 'editor',
        public ?bool $gplLicense = true,
        public ?array $config = [],

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid      = 'artisanpack'.md5(serialize($this)).$id;
        $this->uploadUrl = route('artisanpack.upload', absolute: false);
    }

    public function setup(): string
    {
        $setup = array_merge([
            'menubar'                     => false,
            'automatic_uploads'           => true,
            'quickbars_insert_toolbar'    => false,
            'branding'                    => false,
            'relative_urls'               => false,
            'remove_script_host'          => false,
            'height'                      => 300,
            'toolbar'                     => 'undo redo | align bullist numlist | outdent indent | quickimage quicktable',
            'quickbars_selection_toolbar' => 'bold italic underline strikethrough | forecolor backcolor | link blockquote removeformat | blocks',
        ], $this->config);

        $setup['plugins'] = str('advlist autolink lists link image table quickbars ')->append($this->config['plugins'] ?? '');

        return str(Js::from($setup)->toHtml())->trim('{}')->toString();
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
        return view('livewire-ui-components::components.editor');
    }
}
