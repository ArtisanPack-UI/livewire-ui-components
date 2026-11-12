<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/*
 * The `artisanpack.upload` route the RichTextEditor component
 * posts images to. It only stores images, on an allowed disk, in a folder that
 * stays inside the disk.
 */

beforeEach(function (): void {
    Storage::fake('public');
    Storage::fake('local');
    $this->user     = new User;
    $this->user->id = 1;
});

it('requires a signed-in user', function (): void {
    $this->postJson(route('artisanpack.upload'), ['file' => UploadedFile::fake()->image('photo.png')])
        ->assertUnauthorized();

    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('stores an image in the folder and returns its URL', function (): void {
    $response = $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload', ['disk' => 'public', 'folder' => 'editor/posts']), [
            'file' => UploadedFile::fake()->image('photo.png'),
        ])
        ->assertOk();

    $files = Storage::disk('public')->allFiles('editor/posts');
    expect($files)->toHaveCount(1)
        ->and($files[0])->toEndWith('.png')
        ->and($response->json('location'))->toBe(Storage::disk('public')->url($files[0]));
});

it('defaults to the first allowed disk and the editor folder', function (): void {
    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload'), ['file' => UploadedFile::fake()->image('photo.jpg')])
        ->assertOk();

    expect(Storage::disk('public')->allFiles('editor'))->toHaveCount(1);
});

it('rejects files that are not allowed images', function (string $name, string $mime): void {
    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload'), [
            'file' => UploadedFile::fake()->createWithContent($name, '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>')->mimeType($mime),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('file');

    expect(Storage::disk('public')->allFiles())->toBe([]);
})->with([
    'svg'  => ['drawing.svg', 'image/svg+xml'],
    'html' => ['page.html', 'text/html'],
    'php'  => ['shell.php', 'application/x-php'],
]);

it('rejects a disk that is not allowed', function (): void {
    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload', ['disk' => 'local']), ['file' => UploadedFile::fake()->image('photo.png')])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('disk');

    expect(Storage::disk('local')->allFiles())->toBe([]);
});

it('rejects a folder that could leave the disk', function (string $folder): void {
    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload', ['folder' => $folder]), ['file' => UploadedFile::fake()->image('photo.png')])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('folder');

    expect(Storage::disk('public')->allFiles())->toBe([]);
})->with(['../secrets', 'editor/../../x', '/absolute', 'with space', 'trailing/']);

it('rejects an image over the size limit', function (): void {
    config()->set('artisanpack.livewire-ui-components.uploads.max_size', 10);

    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload'), ['file' => UploadedFile::fake()->image('big.png')->size(11)])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('file');
});

it('follows the configured disks and image types, and never allows SVG', function (): void {
    config()->set('artisanpack.livewire-ui-components.uploads.disks', ['local', 'public']);
    config()->set('artisanpack.livewire-ui-components.uploads.mimes', ['png', 'svg']);

    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload', ['disk' => 'local']), ['file' => UploadedFile::fake()->image('photo.png')])
        ->assertOk();
    expect(Storage::disk('local')->allFiles('editor'))->toHaveCount(1);

    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload'), ['file' => UploadedFile::fake()->image('photo.jpg')])
        ->assertUnprocessable();

    $this->actingAs($this->user)
        ->postJson(route('artisanpack.upload'), [
            'file' => UploadedFile::fake()->createWithContent('drawing.svg', '<svg xmlns="http://www.w3.org/2000/svg"/>')->mimeType('image/svg+xml'),
        ])
        ->assertUnprocessable();
});
