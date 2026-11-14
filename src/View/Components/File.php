<?php

/**
 * File Component
 *
 * The design-system File (#53): a native file input over a drop zone, and
 * the list of uploaded files with their size, progress, errors and a remove
 * button. Uploads through wire:model on the native input.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

/**
 * File Component Class
 *
 * Renders `.apui-file`: the label, the drop zone (the native input over
 * it, the icon, title and hint), the file list and the error. `class` lands
 * on the root; every other attribute, `wire:model` included, lands on the
 * native input. Without `value`, the list reads the wire:model property of
 * the Livewire component that renders it.
 *
 * @since 1.0.0
 */
class File extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'file';

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'file';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'label', 'title', 'hint', 'error', 'invalid', 'removeLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $title  The drop zone's main line. Null uses the translated default.
     * @param  string|null  $hint  The limits under the title. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the input and the drop zone.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $accept  The accepted types.
     * @param  bool  $multiple  Allows several files.
     * @param  int|null  $maxSize  The largest file in bytes, emitted as data-max-size.
     * @param  string  $variant  dropzone or compact.
     * @param  mixed  $value  The files to list: uploads, or [ 'name', 'size', 'progress', 'error' ] entries.
     * @param  string|null  $removeLabel  The remove buttons' name, with :name. Null uses the translated default.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $label = null,
        public ?string $title = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $accept = null,
        public bool $multiple = false,
        public ?int $maxSize = null,
        public string $variant = 'dropzone',
        public mixed $value = null,
        public ?string $removeLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The drop zone's title: the prop, else the translated default.
     *
     * @since 3.0.0
     *
     * @return string The title.
     */
    public function titleText(): string
    {
        return null === $this->title || '' === $this->title ? __('Drop files here or browse') : $this->title;
    }

    /**
     * A remove button's accessible name.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The file name.
     *
     * @return string The name.
     */
    public function removeText(string $name): string
    {
        if (null === $this->removeLabel || '' === $this->removeLabel) {
            return __('Remove :name', ['name' => $name]);
        }

        return str_replace(':name', $name, $this->removeLabel);
    }

    /**
     * The files to list, from `value` or the wire:model property.
     *
     * @since 3.0.0
     *
     * @return list<array{name: string, size: int|null, progress: int|null, error: string|null, upload: string|null}> The entries; `upload` is a temporary upload's file name.
     */
    public function entries(): array
    {
        $files = $this->value ?? $this->modelValue();
        if ($files instanceof Arrayable) {
            $files = $files->toArray();
        }
        if (null === $files || '' === $files) {
            return [];
        }
        if (! is_array($files) || (isset($files['name']) && ! is_array($files['name']))) {
            $files = [$files];
        }

        $entries = [];
        foreach ($files as $file) {
            $entry = $this->entry($file);
            if (null !== $entry) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    /**
     * A size as text: `2.4 MB`, decimal units, one decimal place.
     *
     * @since 3.0.0
     *
     * @param  int  $bytes  The size.
     *
     * @return string The text.
     */
    public function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size  = (float) $bytes;
        $unit  = 0;
        while ($size >= 1000 && $unit < count($units) - 1) {
            $size /= 1000;
            $unit++;
        }

        $number = 0 === $unit ? (string) (int) $size : rtrim(rtrim(number_format($size, 1, '.', ''), '0'), '.');

        return $number.' '.$units[$unit];
    }

    /**
     * The control's id: the `id` attribute, else a base id that is stable
     * across Livewire re-renders and unique in the render (numbered when
     * the same group repeats), so repeated groups never share radio names
     * or label targets.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        return $this->baseId();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.file');
    }

    /**
     * The wire:model property's value on the Livewire component being
     * rendered, or null outside one.
     *
     * @since 3.0.0
     *
     * @return mixed The value.
     */
    protected function modelValue(): mixed
    {
        $model = $this->attributes->wire('model')->value();
        if (! is_string($model) || '' === $model) {
            return null;
        }

        try {
            $component = app('livewire')->current();
        } catch (Throwable) {
            return null;
        }

        return null === $component ? null : data_get($component, $model);
    }

    /**
     * One file as a list entry.
     *
     * @since 3.0.0
     *
     * @param  mixed  $file  An upload, an entry array or a file name.
     *
     * @return array{name: string, size: int|null, progress: int|null, error: string|null, upload: string|null}|null The entry.
     */
    protected function entry(mixed $file): ?array
    {
        if ($file instanceof UploadedFile) {
            return [
                'name'     => $file->getClientOriginalName(),
                'size'     => $this->safeSize($file),
                'progress' => null,
                'error'    => null,
                'upload'   => $file instanceof TemporaryUploadedFile ? $file->getFilename() : null,
            ];
        }

        if (is_object($file)) {
            $file = get_object_vars($file);
        }

        if (is_array($file)) {
            $name = (string) ($file['name'] ?? '');
            if ('' === $name) {
                return null;
            }

            return [
                'name'     => $name,
                'size'     => isset($file['size']) && is_numeric($file['size']) ? (int) $file['size'] : null,
                'progress' => isset($file['progress']) && is_numeric($file['progress']) ? max(0, min(100, (int) $file['progress'])) : null,
                'error'    => isset($file['error']) && '' !== (string) $file['error'] ? (string) $file['error'] : null,
                'upload'   => null,
            ];
        }

        if (is_string($file) && '' !== $file) {
            return ['name' => $file, 'size' => null, 'progress' => null, 'error' => null, 'upload' => null];
        }

        return null;
    }

    /**
     * An upload's size, or null when the file is gone.
     *
     * @since 3.0.0
     *
     * @param  UploadedFile  $file  The upload.
     *
     * @return int|null The size in bytes.
     */
    protected function safeSize(UploadedFile $file): ?int
    {
        try {
            $size = $file->getSize();
        } catch (Throwable) {
            return null;
        }

        return is_int($size) ? $size : null;
    }
}
