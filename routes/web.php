<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('web')->prefix(config( 'artisanpack.livewire-ui-components.route_prefix'))->get('/artisanpack/toogle-sidebar', function (Request $request) {
    if ($request->collapsed) {
        session(['artisanpack-sidebar-collapsed' => $request->collapsed]);
    }
})->name('artisanpack.toogle-sidebar');

Route::middleware('web')->prefix(config( 'artisanpack.livewire-ui-components.route_prefix'))->get('/artisanpack/spotlight', function (Request $request) {
	return app()->make(config( 'artisanpack.livewire-ui-components.components.spotlight.class'))->search($request);
})->name('artisanpack.spotlight');

Route::middleware(['web', 'auth'])->prefix(config( 'artisanpack.livewire-ui-components.route_prefix'))->post('/artisanpack/upload', function (Request $request) {
    $disk = $request->disk ?? 'public';
    $folder = $request->folder ?? 'editor';

    $file = Storage::disk($disk)->put($folder, $request->file('file'), 'public');
    $url = Storage::disk($disk)->url($file);

    return ['location' => $url];
})->name('artisanpack.upload');
