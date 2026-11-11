<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Support\IconRegistry;

/*
 * `registered_icons` works from either published config file: the one the
 * `artisanpack-package-config` tag writes (config/artisanpack/
 * livewire-ui-components.php, read as `artisanpack.livewire-ui-components`)
 * and the one the `livewire-ui-components.config` tag writes
 * (config/livewire-ui-components.php, read as `livewire-ui-components`).
 */

afterEach(function (): void {
    IconRegistry::reset();
});

/**
 * Re-runs the provider's config merge, as boot does after the app's config
 * files load.
 */
function remergeLivewireUiConfig(): void
{
    $provider = app()->getProvider(LivewireUiComponentsServiceProvider::class);

    (fn () => $this->mergeConfiguration())->call($provider);
}

test('icons in config/livewire-ui-components.php reach the registry', function (): void {
    config()->set('livewire-ui-components', ['registered_icons' => ['rocket' => '<path d="M1 1" />']]);
    remergeLivewireUiConfig();

    expect(config('artisanpack.livewire-ui-components.registered_icons'))->toHaveKey('rocket')
        ->and(IconRegistry::resolve('rocket'))->toBe('<path d="M1 1" />')
        ->and(config('artisanpack.livewire-ui-components.prefix'))->toBe('artisanpack');
});

test('icons in config/artisanpack/livewire-ui-components.php reach the registry and win', function (): void {
    config()->set('livewire-ui-components', ['registered_icons' => ['rocket' => '<path d="M1 1" />']]);
    config()->set('artisanpack.livewire-ui-components', ['registered_icons' => ['rocket' => '<path d="M2 2" />', 'star' => '<circle r="1" />']]);
    remergeLivewireUiConfig();

    expect(IconRegistry::resolve('rocket'))->toBe('<path d="M2 2" />')
        ->and(IconRegistry::resolve('star'))->toBe('<circle r="1" />');
});
