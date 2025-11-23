<?php

declare(strict_types=1);
/**
 * WithMediaSync Trait
 *
 * This trait provides functionality for synchronizing media files with models.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * WithMediaSync Trait
 *
 * Provides methods for handling media file uploads, removals, and synchronization with models.
 *
 * @since 1.0.0
 */
trait WithMediaSync
{
    /**
     * Remove a media file from the library.
     *
     * @since 1.0.0
     *
     * @param  string  $uuid  The UUID of the media file to remove.
     * @param  string  $filesModelName  The name of the files model property.
     * @param  string  $library  The name of the library property.
     * @param  string  $url  The URL of the media file.
     */
    public function removeMedia(string $uuid, string $filesModelName, string $library, string $url): void
    {
        // Updates library
        $this->{$library} = $this->{$library}->filter(fn ($image) => $image['uuid'] != $uuid);

        // Remove file
        $name                    = str($url)->after('preview-file/')->before('?expires')->toString();
        $this->{$filesModelName} = collect($this->{$filesModelName})->filter(fn ($file) => $file->getFilename() != $name)->all();
    }

    /**
     * Refresh the order of media files in the library.
     *
     * @since 1.0.0
     *
     * @param  array  $order  The new order of media files by UUID.
     * @param  string  $library  The name of the library property.
     */
    public function refreshMediaOrder(array $order, string $library): void
    {
        $this->{$library} = $this->{$library}->sortBy(function ($item) use ($order) {
            return array_search($item['uuid'], $order);
        });
    }

    /**
     * Bind temporary files with respective previews and replace existing ones, if necessary.
     *
     * @since 1.0.0
     *
     * @param  string  $filesModelName  The name of the files model property.
     * @param  string  $library  The name of the library property.
     *
     * @return void
     */
    public function refreshMediaSources(string $filesModelName, string $library): void
    {
        // New files area
        foreach ($this->{$filesModelName}['*'] ?? [] as $key => $file) {
            $this->{$library} = $this->{$library}->add(['uuid' => Str::uuid()->toString(), 'url' => $file->temporaryUrl()]);

            $key                           = $this->{$library}->keys()->last();
            $this->{$filesModelName}[$key] = $file;
        }

        // Reset new files area
        unset($this->{$filesModelName}['*']);

        // Replace existing files
        foreach ($this->{$filesModelName} as $key => $file) {
            $media        = $this->{$library}->get($key);
            $media['url'] = $file->temporaryUrl();

            $this->{$library} = $this->{$library}->replace([$key => $media]);
        }

        $this->validateOnly($filesModelName.'.*');
    }

    /**
     * Store files into permanent storage area and update the model with fresh sources.
     *
     * @since 1.0.0
     *
     * @param  Model  $model  The model to update.
     * @param  string  $library  Optional. The name of the library property. Default 'library'.
     * @param  string  $files  Optional. The name of the files property. Default 'files'.
     * @param  string  $storage_subpath  Optional. The storage subpath. Default ''.
     * @param  mixed  $model_field  Optional. The model field to update. Default 'library'.
     * @param  string  $visibility  Optional. The file visibility. Default 'public'.
     * @param  string  $disk  Optional. The storage disk. Default 'public'.
     */
    public function syncMedia(
        Model $model,
        string $library = 'library',
        string $files = 'files',
        string $storage_subpath = '',
        $model_field = 'library',
        string $visibility = 'public',
        string $disk = 'public',
    ): void {
        // Store files
        foreach ($this->{$files} as $index => $file) {
            $media = $this->{$library}->get($index);
            $name  = $this->getFileName($media);

            $file = Storage::disk($disk)->putFileAs($storage_subpath, $file, $name, $visibility);
            $url  = Storage::disk($disk)->url($file);

            // Update library
            $media['url']     = $url.'?updated_at='.time();
            $media['path']    = str($storage_subpath)->finish('/')->append($name)->toString();
            $this->{$library} = $this->{$library}->replace([$index => $media]);
        }

        // Delete removed files from library
        $diffs = $model->{$model_field}?->filter(fn ($item) => $this->{$library}->doesntContain('uuid', $item['uuid'])) ?? [];

        foreach ($diffs as $diff) {
            Storage::disk($disk)->delete($diff['path']);
        }

        // Updates model
        $model->update([$model_field => $this->{$library}]);

        // Resets files
        $this->{$files} = [];
    }

    /**
     * Get the file name from a media array.
     *
     * @since 1.0.0
     *
     * @param  array|null  $media  The media array.
     *
     * @return string|null The file name.
     */
    private function getFileName(?array $media): ?string
    {
        $name      = $media['uuid'] ?? null;
        $extension = str($media['url'] ?? null)->afterLast('.')->before('?expires')->toString();

        return "$name.$extension";
    }
}
