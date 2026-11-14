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
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * ImageLibrary Class
 *
 * The design-system ImageLibrary (#61), Livewire only (D-003): a media
 * manager with search, a grid or list view, single or multiple selection
 * (a listbox), uploads and a details panel with editable alt text.
 *
 * It keeps the 2.x contract with the WithMediaSync trait: `wire:model` names
 * the uploads property and `wire:library` the library collection, which
 * `preview` passes in. Uploads, replace (the 2.x "change"), crop (with
 * Cropper.js on the page), remove and drag-to-reorder (with SortableJS on the
 * page) call the trait's methods. Alt text edits set `<library>.<key>.alt`.
 * The 2.x text props still override the built-in strings.
 *
 * @since 1.0.0
 */
class ImageLibrary extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The id prefix for this component's parts.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'image-library';

    /**
     * Props left out of the stable id, so new assets keep the ids.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['assets', 'preview'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  array|Collection  $assets  The assets: `{ id, src, name, alt, size, width, height, type }`.
     * @param  string  $view  `grid` or `list`.
     * @param  string  $selectable  `single` or `multiple`.
     * @param  string  $accept  The file types uploads accept.
     * @param  bool  $droppable  Files dropped on the library upload.
     * @param  string|null  $label  Names the listbox. Defaults to `__( 'Media library' )`.
     * @param  string|null  $hint  Help text under the grid.
     * @param  bool  $hideErrors  Hides the library's validation errors.
     * @param  bool  $hideProgress  Hides the upload progress bar.
     * @param  array  $cropConfig  Cropper.js options, merged over the defaults.
     * @param  array  $classes  Extra classes per part.
     * @param  array|Collection|null  $preview  The 2.x WithMediaSync library collection: `{ uuid, url, … }`.
     * @param  string|null  $changeText  Overrides "Replace".
     * @param  string|null  $cropText  Overrides "Crop".
     * @param  string|null  $removeText  Overrides "Remove".
     * @param  string|null  $cropTitleText  Overrides the crop dialog's title.
     * @param  string|null  $cropCancelText  Overrides the crop dialog's cancel button.
     * @param  string|null  $cropSaveText  Overrides the crop dialog's save button.
     * @param  string|null  $addFilesText  Overrides "Upload".
     * @param  bool|null  $withDragDrop  Deprecated: use `droppable`.
     * @param  string|null  $dragDropText  Overrides the drop overlay's text.
     * @param  string|null  $dragDropMultipleText  Deprecated and ignored: the overlay text no longer counts files.
     * @param  string|null  $dragDropClass  Deprecated: extra classes for the drop overlay; use `classes['dropzone']`.
     */
    public function __construct(
        public array|Collection $assets = [],
        public string $view = 'grid',
        public string $selectable = 'multiple',
        public string $accept = 'image/png, image/jpeg',
        public bool $droppable = false,
        public ?string $label = null,
        public ?string $hint = null,
        public bool $hideErrors = false,
        public bool $hideProgress = false,
        public array $cropConfig = [],
        public array $classes = [],
        public Collection|array|null $preview = null,
        public ?string $changeText = null,
        public ?string $cropText = null,
        public ?string $removeText = null,
        public ?string $cropTitleText = null,
        public ?string $cropCancelText = null,
        public ?string $cropSaveText = null,
        public ?string $addFilesText = null,
        public ?bool $withDragDrop = null,
        public ?string $dragDropText = null,
        public ?string $dragDropMultipleText = null,
        public ?string $dragDropClass = null,
    ) {
        $this->view       = 'list' === $this->view ? 'list' : 'grid';
        $this->selectable = 'single' === $this->selectable ? 'single' : 'multiple';

        if (null !== $this->withDragDrop) {
            $this->droppable = $this->withDragDrop;
        }

        if (null !== $this->dragDropClass && '' !== trim($this->dragDropClass) && ! isset($this->classes['dropzone'])) {
            $this->classes['dropzone'] = $this->dragDropClass;
        }
    }

    /**
     * The view, `grid` or `list`. Blade reserves `$view` in templates, so the
     * template reads it through this method.
     *
     * @since 3.0.0
     *
     * @return string The view.
     */
    public function viewMode(): string
    {
        return $this->view;
    }

    /**
     * The uploads property `wire:model` names, which WithMediaSync fills.
     *
     * @since 1.0.0
     *
     * @return string|null The property, or null when the library is read-only.
     */
    public function modelName(): ?string
    {
        $model = $this->attributes->wire('model')->value();

        return is_string($model) && '' !== $model ? $model : null;
    }

    /**
     * The library collection `wire:library` names.
     *
     * @since 1.0.0
     *
     * @return string|null The property.
     */
    public function libraryName(): ?string
    {
        $library = $this->attributes->wire('library')->value();

        return is_string($library) && '' !== $library ? $library : null;
    }

    /**
     * A validation message without Laravel's "The x field" prefix.
     *
     * @since 1.0.0
     *
     * @param  string  $message  The message.
     *
     * @return string The message after "field".
     */
    public function validationMessage(string $message): string
    {
        return str($message)->after('field')->toString();
    }

    /**
     * The Cropper.js options: the defaults, then `cropConfig`.
     *
     * @since 1.0.0
     *
     * @return string The options as JSON.
     */
    public function cropSetup(): string
    {
        return (string) json_encode(array_merge([
            'autoCropArea'     => 1,
            'viewMode'         => 1,
            'dragMode'         => 'move',
            'checkCrossOrigin' => false,
        ], $this->cropConfig));
    }

    /**
     * The assets, normalized: from `assets`, else the WithMediaSync `preview`
     * collection. `key` is the item's key in the library, which uploads and
     * alt text edits address. `id` is the item's `id`, else its `uuid`; with a
     * `wire:library`, its `uuid` comes first, since WithMediaSync's
     * removeMedia() and refreshMediaOrder() match on it.
     *
     * @since 3.0.0
     *
     * @return list<array{key: string, id: string, src: string, name: string, alt: string, meta: string, size: string, dimensions: string, type: string, image: bool}> The assets.
     */
    public function entries(): array
    {
        $source  = [] !== $this->items($this->assets) ? $this->assets : ($this->preview ?? []);
        $entries = [];
        $byUuid  = null !== $this->attributes && null !== $this->libraryName();
        foreach ($this->items($source) as $key => $asset) {
            if (! is_array($asset)) {
                continue;
            }

            $src  = (string) ($asset['src'] ?? $asset['url'] ?? '');
            $path = (string) ($asset['path'] ?? $src);
            $name = (string) ($asset['name'] ?? $asset['file_name'] ?? '');
            if ('' === $name) {
                $name = rawurldecode((string) str((string) strtok($path, '?'))->afterLast('/'));
            }

            $type       = strtoupper((string) ($asset['type'] ?? ''));
            $mime       = (string) ($asset['mime'] ?? $asset['mime_type'] ?? '');
            $extension  = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
            if ('' === $type) {
                $type = '' !== $mime ? strtoupper((string) str($mime)->afterLast('/')) : strtoupper($extension);
            }

            $image = null !== SafeUrl::media($src) && ('' !== $mime
                ? str_starts_with($mime, 'image/')
                : ! in_array($type, ['PDF', 'DOC', 'DOCX', 'XLS', 'XLSX', 'CSV', 'TXT', 'ZIP', 'MP4', 'MOV', 'MP3', 'WAV'], true));

            $dimensions = isset($asset['width'], $asset['height']) && $asset['width'] && $asset['height']
                ? $asset['width'].' × '.$asset['height']
                : '';
            $size = (string) ($asset['size'] ?? '');
            $meta = implode(' · ', array_filter([$size, $dimensions], static fn (string $part): bool => '' !== $part));

            $entries[] = [
                'key'        => (string) $key,
                'id'         => (string) ($byUuid ? ($asset['uuid'] ?? $asset['id'] ?? $key) : ($asset['id'] ?? $asset['uuid'] ?? $key)),
                'src'        => (string) SafeUrl::media($src),
                'name'       => '' !== $name ? $name : __('Untitled'),
                'alt'        => (string) ($asset['alt'] ?? $asset['alt_text'] ?? ''),
                'meta'       => $meta,
                'size'       => $size,
                'dimensions' => $dimensions,
                'type'       => $type,
                'image'      => $image,
            ];
        }

        return $entries;
    }

    /**
     * A built-in string: the 2.x text prop when set, else the translated default.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The string's name.
     *
     * @return string The string.
     */
    public function text(string $name): string
    {
        $override = match ($name) {
            'upload'      => $this->addFilesText,
            'replace'     => $this->changeText,
            'crop'        => $this->cropText,
            'remove'      => $this->removeText,
            'crop-title'  => $this->cropTitleText,
            'crop-cancel' => $this->cropCancelText,
            'crop-save'   => $this->cropSaveText,
            'drop'        => $this->dragDropText,
            'label'       => $this->label,
            default       => null,
        };

        if ($this->filled($override)) {
            return (string) $override;
        }

        return match ($name) {
            'upload'         => __('Upload'),
            'replace'        => __('Replace'),
            'crop'           => __('Crop'),
            'remove'         => __('Remove'),
            'crop-title'     => __('Crop image'),
            'crop-cancel'    => __('Cancel'),
            'crop-save'      => __('Crop'),
            'drop'           => __('Drop files to upload'),
            'label'          => __('Media library'),
            'search'         => __('Search files'),
            'view'           => __('View'),
            'grid'           => __('Grid'),
            'list'           => __('List'),
            'select-all'     => __('Select all'),
            'insert'         => __('Insert'),
            'details'        => __('Details'),
            'alt'            => __('Alt text'),
            'alt-hint'       => __('Describes the image for screen readers.'),
            'alt-missing'    => __('This image has no alt text yet.'),
            'empty'          => __('No files yet.'),
            'no-match'       => __('No files match your search.'),
            'confirm-remove' => __('Remove the selected files? This can’t be undone.'),
            'dimensions'     => __('Dimensions'),
            'size'           => __('Size'),
            'type'           => __('Type'),
            default          => $name,
        };
    }

    /**
     * Whether the library uploads: `wire:model` names an uploads property.
     *
     * @since 3.0.0
     *
     * @return bool True when uploads, replace, crop and remove are available.
     */
    public function editable(): bool
    {
        return null !== $this->modelName() && null !== $this->libraryName();
    }

    /**
     * The `x-data` expression: the library's client state and actions.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $assets = array_map(static fn (array $entry): array => [
            'key'        => $entry['key'],
            'id'         => $entry['id'],
            'src'        => $entry['src'],
            'name'       => $entry['name'],
            'alt'        => $entry['alt'],
            'size'       => $entry['size'],
            'dimensions' => $entry['dimensions'],
            'type'       => $entry['type'],
            'image'      => $entry['image'],
        ], $this->entries());

        return sprintf(
            'apuiImageLibrary({ id: %s, assets: %s, view: %s, multiple: %s, model: %s, library: %s, crop: %s, texts: %s })',
            Js::from($this->baseId())->toHtml(),
            Js::from($assets)->toHtml(),
            Js::from($this->view)->toHtml(),
            'multiple' === $this->selectable ? 'true' : 'false',
            Js::from($this->modelName())->toHtml(),
            Js::from($this->libraryName())->toHtml(),
            $this->cropSetup(),
            Js::from([
                'selected'      => trans_choice(':count selected|:count selected', 2, ['count' => '{count}']),
                'selectedOne'   => trans_choice(':count selected|:count selected', 1, ['count' => '{count}']),
                'confirmRemove' => $this->text('confirm-remove'),
            ])->toHtml(),
        );
    }

    /**
     * The root's attributes: everything but the Livewire bindings the library
     * handles itself.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->whereDoesntStartWith('wire:library')->except(['id', 'accept']);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     *
     * @return View The view.
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.image-library');
    }

    /**
     * The items of an array or collection, with their keys.
     *
     * @since 3.0.0
     *
     * @param  mixed  $source  An array or collection.
     *
     * @return array<array-key, mixed> The items.
     */
    private function items(mixed $source): array
    {
        if ($source instanceof Collection) {
            return $source->all();
        }

        return is_array($source) ? $source : [];
    }
}
