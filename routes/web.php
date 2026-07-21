<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Theme Preview Route.
 *
 * Provides a browser-based theme preview and customization tool.
 *
 * @since 2.0.0
 */
Route::middleware('web')->prefix(config('artisanpack.livewire-ui-components.route_prefix'))->get('/artisanpack/theme-preview', function () {
    return view('livewire-ui-components::demos.theme-preview');
})->name('artisanpack.theme-preview');

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

Route::middleware(['web', 'auth'])->prefix(config('artisanpack.livewire-ui-components.route_prefix'))->post('/artisanpack/upload', function (Request $request) {
    $disk   = $request->disk ?? 'public';
    $folder = $request->folder ?? 'editor';

    $file = Storage::disk($disk)->put($folder, $request->file('file'), 'public');
    $url  = Storage::disk($disk)->url($file);

    return ['location' => $url];
})->name('artisanpack.upload');
