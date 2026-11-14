<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Swaps in an empty Blade compiler, so a test sees only the directives it registers.
 */
function freshBladeCompiler(): BladeCompiler
{
    $compiler = new BladeCompiler(new Filesystem, sys_get_temp_dir());

    app()->instance('blade.compiler', $compiler);
    Blade::clearResolvedInstance('blade.compiler');

    return $compiler;
}

test('the island directives register when Livewire has none of its own', function (): void {
    $compiler = freshBladeCompiler();

    app()->getProvider(LivewireUiComponentsServiceProvider::class)->registerIslandDirectives(native: false);

    expect(array_keys($compiler->getCustomDirectives()))->toBe(['island', 'endisland', 'placeholder', 'endplaceholder'])
        ->and($compiler->compileString("@island('stats') Hi @endisland"))->toContain('data-island=');
});

test('the island directives are left to Livewire when it provides them', function (): void {
    $compiler = freshBladeCompiler();

    app()->getProvider(LivewireUiComponentsServiceProvider::class)->registerIslandDirectives(native: true);

    expect($compiler->getCustomDirectives())->toBe([]);
});

test('native islands are detected from Livewire 4\'s SupportIslands feature', function (): void {
    expect(LivewireHelper::hasNativeIslands())->toBe(class_exists('Livewire\\Features\\SupportIslands\\SupportIslands'));

    if (! LivewireHelper::hasNativeIslands()) {
        expect(Blade::getCustomDirectives())->toHaveKey('island');
    }
});
