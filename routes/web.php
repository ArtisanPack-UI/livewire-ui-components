<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Http\Requests\UploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('web')->prefix(config('artisanpack.livewire-ui-components.route_prefix'))->get('/artisanpack/toogle-sidebar', function (Request $request): void {
    if ($request->collapsed) {
        session(['artisanpack-sidebar-collapsed' => $request->collapsed]);
    }
})->name('artisanpack.toogle-sidebar');

Route::middleware('web')->prefix(config('artisanpack.livewire-ui-components.route_prefix'))->get('/artisanpack/spotlight', function (Request $request) {
    $results = app()->make(config('artisanpack.livewire-ui-components.components.spotlight.class'))->search($request);

    return applyFilters(
        'ap.livewireUiComponents.spotlightCommands',
        (array) $results,
        $request->user(),
    );
})->name('artisanpack.spotlight');

/**
 * Image Upload Route.
 *
 * Stores an image from the RichTextEditor component and returns its
 * URL. UploadRequest limits it to images on an allowed disk and folder (see the
 * `uploads` config).
 *
 * @since 2.0.0
 */
Route::middleware(['web', 'auth'])->prefix(config('artisanpack.livewire-ui-components.route_prefix'))->post('/artisanpack/upload', function (UploadRequest $request) {
    $disk = $request->disk();

    $file = Storage::disk($disk)->putFile($request->folder(), $request->file('file'), 'public');
    abort_if(false === $file, 500, __('The image could not be stored.'));

    return ['location' => Storage::disk($disk)->url($file)];
})->name('artisanpack.upload');
