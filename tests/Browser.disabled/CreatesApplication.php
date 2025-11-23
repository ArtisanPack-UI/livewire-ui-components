<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Browser;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * Creates Application trait for Dusk browser tests.
 *
 * This trait provides the application instance needed for
 * browser testing with Laravel Dusk.
 */
trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';

        if (! $app) {
            // For package testing, create a minimal Laravel application
            $app = new Application(
                $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__, 2),
            );

            $app->singleton(
                Illuminate\Contracts\Http\Kernel::class,
                Illuminate\Foundation\Http\Kernel::class,
            );

            $app->singleton(
                Illuminate\Contracts\Console\Kernel::class,
                Illuminate\Foundation\Console\Kernel::class,
            );

            $app->singleton(
                Illuminate\Contracts\Debug\ExceptionHandler::class,
                Illuminate\Foundation\Exceptions\Handler::class,
            );
        }

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
