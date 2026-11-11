<?php

/**
 * UploadRequest
 *
 * Validates an image upload from the RichTextEditor and Markdown components.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UploadRequest Class
 *
 * The `artisanpack.upload` route stores what the editors send on a public
 * disk and returns its URL, so it only takes images (no SVG, which can carry
 * script), up to a size limit, on a disk the app allows, in a folder name
 * that can't leave the disk's root. The limits come from the package's
 * `uploads` config.
 *
 * @since 3.0.0
 */
class UploadRequest extends FormRequest
{
    /**
     * The default image extensions. SVG is left out on purpose.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const DEFAULT_MIMES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    /**
     * Folder names: path segments of letters, digits, `-` and `_`.
     *
     * @since 3.0.0
     */
    public const FOLDER_PATTERN = '/^[A-Za-z0-9_-]+(\/[A-Za-z0-9_-]+)*$/';

    /**
     * Whether the user may upload. The route already requires `auth`.
     *
     * @since 3.0.0
     */
    public function authorize(): bool
    {
        return null !== $this->user();
    }

    /**
     * The validation rules.
     *
     * @since 3.0.0
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'file'   => ['required', 'file', 'mimes:'.implode(',', self::mimes()), 'max:'.self::maxSize()],
            'disk'   => ['nullable', 'string', Rule::in(self::disks())],
            'folder' => ['nullable', 'string', 'max:100', 'regex:'.self::FOLDER_PATTERN],
        ];
    }

    /**
     * The validation messages.
     *
     * @since 3.0.0
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => __('Choose an image to upload.'),
            'file.file'     => __('The upload must be a file.'),
            'file.mimes'    => __('The image must be one of: :values.'),
            'file.max'      => __('The image may not be larger than :max kilobytes.'),
            'disk.in'       => __('Uploads are not allowed on that disk.'),
            'folder.regex'  => __('The folder may only contain letters, numbers, dashes, underscores and slashes.'),
            'folder.max'    => __('The folder name is too long.'),
        ];
    }

    /**
     * The disk to store on.
     *
     * @since 3.0.0
     */
    public function disk(): string
    {
        return (string) ($this->validated('disk') ?? self::disks()[0] ?? 'public');
    }

    /**
     * The folder to store in.
     *
     * @since 3.0.0
     */
    public function folder(): string
    {
        return (string) ($this->validated('folder') ?? 'editor');
    }

    /**
     * The disks uploads may use.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public static function disks(): array
    {
        return array_values((array) config('artisanpack.livewire-ui-components.uploads.disks', ['public']));
    }

    /**
     * The allowed image extensions.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public static function mimes(): array
    {
        $mimes = (array) config('artisanpack.livewire-ui-components.uploads.mimes', self::DEFAULT_MIMES);

        return array_values(array_filter($mimes, static fn ($mime): bool => 'svg' !== strtolower((string) $mime)));
    }

    /**
     * The largest upload, in kilobytes.
     *
     * @since 3.0.0
     */
    public static function maxSize(): int
    {
        return (int) config('artisanpack.livewire-ui-components.uploads.max_size', 5120);
    }
}
