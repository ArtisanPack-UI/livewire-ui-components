<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests;

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireUiComponentsServiceProvider::class,
        ];
    }
}
