<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Support\DesignSystemAssets;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

beforeEach(function (): void {
    $this->manifest = json_decode(File::get(DesignSystemAssets::packageDistPath().'/manifest.json'), true);
});

test('registers a publish tag for the prebuilt assets', function (): void {
    $paths = ServiceProvider::pathsToPublish(LivewireUiComponentsServiceProvider::class, DesignSystemAssets::PUBLISH_TAG);

    $targets = array_map(fn (string $path): string => str_replace('\\', '/', $path), array_values($paths));

    expect($paths)->toHaveCount(3)
        ->and($targets)->toContain(str_replace('\\', '/', public_path('vendor/artisanpack-ui/design-system/css')))
        ->and($targets)->toContain(str_replace('\\', '/', public_path('vendor/artisanpack-ui/design-system/js')))
        ->and($targets)->toContain(str_replace('\\', '/', public_path('vendor/artisanpack-ui/design-system/manifest.json')));

    foreach (array_keys($paths) as $source) {
        expect(file_exists($source))->toBeTrue();
    }
});

test('publishes the CSS and JS to public/vendor/artisanpack-ui/design-system', function (): void {
    $published = public_path(DesignSystemAssets::DEFAULT_PUBLIC_PATH);
    File::deleteDirectory($published);

    try {
        $this->artisan('vendor:publish', ['--tag' => DesignSystemAssets::PUBLISH_TAG, '--force' => true])
            ->assertSuccessful();

        foreach ($this->manifest['files'] as $file => $hash) {
            expect(File::exists("{$published}/{$file}"))->toBeTrue()
                ->and(substr(hash_file('sha256', "{$published}/{$file}"), 0, strlen($hash)))->toBe($hash);
        }
    } finally {
        File::deleteDirectory($published);

        foreach ([public_path('vendor/artisanpack-ui'), public_path('vendor')] as $parent) {
            if (File::isDirectory($parent) && [] === File::files($parent) && [] === File::directories($parent)) {
                File::deleteDirectory($parent);
            }
        }
    }
});

test('@artisanpackStyles prints the default stylesheet with its content hash', function (): void {
    $file = $this->manifest['styles']['default'];
    $hash = $this->manifest['files'][$file];

    expect(Blade::render('@artisanpackStyles'))
        ->toBe('<link rel="stylesheet" href="http://localhost/vendor/artisanpack-ui/design-system/'.$file.'?id='.$hash.'" data-navigate-track>');
});

test('@artisanpackStyles takes the headless stylesheet as an argument', function (): void {
    $file = $this->manifest['styles']['headless'];

    expect(Blade::render("@artisanpackStyles('headless')"))
        ->toContain('/vendor/artisanpack-ui/design-system/'.$file.'?id='.$this->manifest['files'][$file]);
});

test('the assets.stylesheet config picks the headless stylesheet', function (): void {
    config(['artisanpack.livewire-ui-components.assets.stylesheet' => 'headless']);

    expect(Blade::render('@artisanpackStyles'))
        ->toContain('/'.$this->manifest['styles']['headless'].'?id=');
});

test('an argument overrides the configured stylesheet', function (): void {
    config(['artisanpack.livewire-ui-components.assets.stylesheet' => 'headless']);

    expect(Blade::render("@artisanpackStyles('default')"))
        ->toContain('/'.$this->manifest['styles']['default'].'?id=');
});

test('@artisanpackScripts prints the Alpine adapter as a classic blocking script', function (): void {
    $file = $this->manifest['scripts']['alpine'];
    $html = Blade::render('@artisanpackScripts');

    expect($html)
        ->toBe('<script src="http://localhost/vendor/artisanpack-ui/design-system/'.$file.'?id='.$this->manifest['files'][$file].'" data-navigate-track></script>')
        // It must run before Livewire's script to register on alpine:init, so
        // it can't be deferred, async or a module (which defers).
        ->not->toContain('defer')
        ->not->toContain('async')
        ->not->toContain('type="module"');
});

test('the public path follows the assets.path config', function (): void {
    config(['artisanpack.livewire-ui-components.assets.path' => '/assets/apui/']);

    expect(app(DesignSystemAssets::class)->url('js/alpine.global.js'))
        ->toStartWith('http://localhost/assets/apui/js/alpine.global.js?id=');
});

test('a file the manifest does not list gets no hash', function (): void {
    expect(app(DesignSystemAssets::class)->url('css/unknown.css'))
        ->toBe('http://localhost/vendor/artisanpack-ui/design-system/css/unknown.css');
});

test('an unknown stylesheet throws', function (): void {
    app(DesignSystemAssets::class)->styles('tailwind');
})->throws(RuntimeException::class, 'Unknown design-system stylesheet "tailwind"');

test('a missing manifest throws', function (): void {
    (new DesignSystemAssets(storage_path('no-such-dist')))->scripts();
})->throws(RuntimeException::class, 'The design-system asset manifest is missing or invalid');

test('the hash changes with the manifest', function (): void {
    $dist = storage_path('test-dist');
    File::ensureDirectoryExists($dist);
    File::put("{$dist}/manifest.json", json_encode([
        'styles'  => ['default' => 'css/index.css', 'headless' => 'css/headless.css'],
        'scripts' => ['alpine' => 'js/alpine.global.js'],
        'files'   => ['css/index.css' => 'aaaa', 'css/headless.css' => 'bbbb', 'js/alpine.global.js' => 'cccc'],
    ]));

    $assets = new DesignSystemAssets($dist);

    expect($assets->styles())->toContain('css/index.css?id=aaaa')
        ->and($assets->styles('headless'))->toContain('css/headless.css?id=bbbb')
        ->and($assets->scripts())->toContain('js/alpine.global.js?id=cccc');

    File::deleteDirectory($dist);
});

test('the publish target follows the assets.path config', function (): void {
    config(['artisanpack.livewire-ui-components.assets.path' => '/assets/apui/']);

    $paths = (new LivewireUiComponentsServiceProvider(app()))->designSystemAssetPaths();

    expect(array_values($paths))->toBe([
        public_path('assets/apui/css'),
        public_path('assets/apui/js'),
        public_path('assets/apui/manifest.json'),
    ]);
});

test('the hash comes from the published manifest, so it matches the files the browser gets', function (): void {
    $published = public_path('test-published-assets');
    config(['artisanpack.livewire-ui-components.assets.path' => 'test-published-assets']);
    File::ensureDirectoryExists($published);
    File::put("{$published}/manifest.json", json_encode(['files' => ['css/index.css' => 'old0published0']]));

    try {
        $assets = new DesignSystemAssets;

        expect($assets->url('css/index.css'))->toBe('http://localhost/test-published-assets/css/index.css?id=old0published0')
            ->and($assets->url('js/alpine.global.js'))->toEndWith('?id='.$this->manifest['files']['js/alpine.global.js']);
    } finally {
        File::deleteDirectory($published);
    }
});
