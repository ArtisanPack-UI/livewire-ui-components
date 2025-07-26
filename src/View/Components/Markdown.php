<?php
/**
 * Markdown
 *
 * This file contains the Markdown class for the ArtisanPack UI Livewire UI Components package.
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
use Illuminate\View\Component;
/**
 * Markdown Class
 *
 * Provides functionality for the Markdown component.
 *
 * @since 1.0.0
 */

class Markdown extends Component
{
    public string $uuid;

    public string $uploadUrl;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $disk = 'public',
        public ?string $folder = 'markdown',
        public ?array $config = [],

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
        $this->uploadUrl = route('mary.upload', absolute: false);
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function setup(): string
    {
        $setup = array_merge([
            'spellChecker' => false,
            'autoSave' => false,
            'uploadImage' => true,
            'imageAccept' => 'image/png, image/jpeg, image/gif, image/avif',
            'toolbar' => [
                'heading',
                'bold',
                'italic',
                'strikethrough',
                '|',
                'code',
                'quote',
                'unordered-list',
                'ordered-list',
                'horizontal-rule',
                '|',
                'link',
                'upload-image',
                'table',
                '|',
                'preview',
                'side-by-side'
            ],
        ], $this->config);

        // Table default CSS class `.table` breaks the layout.
        // Here is a workaround
        $table = "{ 'title' : 'Table', 'name' : 'myTable', 'action' : EasyMDE.drawTable, 'className' : 'fa fa-table' }";

        return str(json_encode($setup))
            ->replace("\"", "'")
            ->trim('{}')
            ->replace("'table'", $table)
            ->toString();
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.markdown');
    }
}
