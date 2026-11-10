<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Support\DesignSystemAssets;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Builds the install command with the given options and a buffered output.
 *
 * @param  array<string, mixed>  $options
 *
 * @return array{0: LivewireUiComponentsInstallCommand, 1: BufferedOutput}
 */
function installCommand(array $options = []): array
{
    $command = app(LivewireUiComponentsInstallCommand::class);
    $command->setLaravel(app());

    $input = new ArrayInput($options, $command->getDefinition());
    $input->setInteractive(false);
    $buffer = new BufferedOutput;

    $command->setInput($input);
    $command->setOutput(new OutputStyle($input, $buffer));

    return [$command, $buffer];
}

beforeEach(function (): void {
    $this->appRoot = storage_path('test-install-app');
    File::deleteDirectory($this->appRoot);
    File::ensureDirectoryExists($this->appRoot);
    $this->originalBasePath = $this->app->basePath();
    $this->app->setBasePath($this->appRoot);
});

afterEach(function (): void {
    $this->app->setBasePath($this->originalBasePath);
    File::deleteDirectory($this->appRoot);
});

test('the install command no longer installs or configures daisyUI', function (): void {
    $source = File::get((new ReflectionClass(LivewireUiComponentsInstallCommand::class))->getFileName());

    expect($source)->not->toContain('setupTailwindDaisy')
        ->not->toMatch('/install[^\\n]*daisyui/i')
        ->not->toContain('themes: light --default');
});

test('without --tailwind, a Tailwind import already in app.css still gets the layer order', function (): void {
    [$command] = installCommand();
    $laravel   = "@import 'tailwindcss';\n\n@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';\n";

    $css = $command->addDesignSystemCss($laravel);

    expect($css)->toStartWith("/* ArtisanPack UI design system: the apui layer sits between Tailwind's base and components. */\n@layer theme, base, apui, components, utilities;")
        ->toContain("@import 'tailwindcss';\n@import '@artisanpack-ui/css';")
        ->not->toContain('@artisanpack-ui/css/tailwind')
        ->and(substr_count($css, 'tailwindcss'))->toBe(1);
});

test('a tailwindcss import with arguments is recognized, not imported twice', function (string $import): void {
    [$command] = installCommand();

    $css = $command->addDesignSystemCss("{$import}\n", true);

    expect(substr_count($css, "'tailwindcss'") + substr_count($css, '"tailwindcss"'))->toBe(1)
        ->and($css)->toContain("{$import}\n@import '@artisanpack-ui/css';\n@import '@artisanpack-ui/css/tailwind';");
})->with([
    'source(none)' => ['@import "tailwindcss" source(none);'],
    'prefix'       => ["@import 'tailwindcss' prefix(tw);"],
    'important'    => ['@import "tailwindcss" important;'],
]);

test('running again with --tailwind adds the pieces the first run left out', function (): void {
    [$command] = installCommand();

    $first  = $command->addDesignSystemCss("body { margin: 0; }\n");
    $second = $command->addDesignSystemCss($first, true);

    expect($second)->toContain('@layer theme, base, apui, components, utilities;')
        ->toContain("@import '@artisanpack-ui/css/tailwind';")
        ->toContain('livewire-ui-components/resources/views')
        ->and(substr_count($second, "@import '@artisanpack-ui/css';"))->toBe(1)
        ->and($command->addDesignSystemCss($second, true))->toBe($second);
});

test('warns when app.css still loads daisyUI', function (): void {
    [$command, $buffer] = installCommand();

    $command->warnAboutDaisyUi("@import 'tailwindcss';\n@plugin \"daisyui\" {\n  themes: light --default;\n}\n");
    $command->warnAboutDaisyUi("@import 'tailwindcss';\n");

    expect(substr_count($buffer->fetch(), 'still loads daisyUI'))->toBe(1);
});

test('the prebuilt path is the default until the npm packages are published', function (): void {
    expect(LivewireUiComponentsInstallCommand::DEFAULT_ASSETS)->toBe('prebuilt');
});

test('the bundler path installs the design system packages and imports them', function (): void {
    Process::fake();
    File::ensureDirectoryExists($this->appRoot.'/resources/css');
    File::put($this->appRoot.'/resources/css/app.css', "body { margin: 0; }\n");

    [$command] = installCommand();
    $command->setupBundlerAssets('npm install --save-dev');

    Process::assertRan('npm install --save-dev @artisanpack-ui/css @artisanpack-ui/behavior');

    $css = File::get($this->appRoot.'/resources/css/app.css');
    $js  = File::get($this->appRoot.'/resources/js/app.js');

    expect($css)->toStartWith("/* ArtisanPack UI design system */\n@import '@artisanpack-ui/css';")
        ->toContain('body { margin: 0; }')
        ->not->toContain('tailwind')
        ->not->toContain('daisy')
        ->and($js)->toContain("import apuiBehavior from '@artisanpack-ui/behavior/alpine';")
        ->toContain("document.addEventListener('alpine:init', () => window.Alpine.plugin(apuiBehavior));");
});

test('the bundler path adds Tailwind and the preset when asked', function (): void {
    Process::fake();

    [$command] = installCommand();
    $command->setupBundlerAssets('pnpm i -D', true);

    Process::assertRan('pnpm i -D @artisanpack-ui/css @artisanpack-ui/behavior tailwindcss @tailwindcss/vite');

    expect(File::get($this->appRoot.'/resources/css/app.css'))
        ->toContain("@layer theme, base, apui, components, utilities;\n\n@import 'tailwindcss';\n@import '@artisanpack-ui/css';\n@import '@artisanpack-ui/css/tailwind';")
        ->toContain('@source "../../vendor/artisanpack-ui/livewire-ui-components/resources/views/**/*.php";');
});

test('the Tailwind setup goes after an existing tailwindcss import', function (): void {
    [$command] = installCommand();

    $css = $command->addDesignSystemCss("@import 'tailwindcss';\n\n@theme {\n    --font-sans: 'Instrument Sans';\n}\n", true);

    expect($css)->toStartWith("/* ArtisanPack UI design system: the apui layer sits between Tailwind's base and components. */\n@layer theme, base, apui, components, utilities;")
        ->toContain("@import 'tailwindcss';\n@import '@artisanpack-ui/css';\n@import '@artisanpack-ui/css/tailwind';\n\n@theme {")
        ->and(substr_count($css, "@import 'tailwindcss'"))->toBe(1);
});

test('the CSS and JS setup is idempotent', function (): void {
    [$command] = installCommand();

    $css = $command->addDesignSystemCss('', true);
    $js  = $command->addDesignSystemJs("import './bootstrap';\n");

    expect($command->addDesignSystemCss($css, true))->toBe($css)
        ->and($command->addDesignSystemCss($css))->toBe($css)
        ->and($command->addDesignSystemJs($js))->toBe($js)
        ->and($js)->toStartWith("import './bootstrap';\n\n/* ArtisanPack UI design system");
});

test('the prebuilt path publishes the assets and shows the directives', function (): void {
    // The provider registered its targets against the app's real public path.
    $targets = array_values(ServiceProvider::pathsToPublish(LivewireUiComponentsServiceProvider::class, DesignSystemAssets::PUBLISH_TAG));
    $root    = dirname($targets[0]);

    try {
        [$command, $output] = installCommand();
        $command->setupPrebuiltAssets();

        $text = $output->fetch();

        expect(File::exists($root.'/js/alpine.global.js'))->toBeTrue()
            ->and(File::exists($root.'/css/index.css'))->toBeTrue()
            ->and($text)->toContain('@artisanpackStyles')
            ->toContain('@artisanpackScripts')
            ->toContain("before Livewire's script")
            ->toContain('--tag='.DesignSystemAssets::PUBLISH_TAG)
            ->not->toContain('daisy');
    } finally {
        File::deleteDirectory($root);

        foreach ([dirname($root), dirname($root, 2)] as $parent) {
            if (File::isDirectory($parent) && [] === File::files($parent) && [] === File::directories($parent)) {
                File::deleteDirectory($parent);
            }
        }
    }
});

test('--assets picks the path without asking', function (string $assets): void {
    [$command] = installCommand(['--assets' => $assets]);

    expect($command->askForAssetPath())->toBe($assets);
})->with(['bundler', 'prebuilt']);

test('uses the prebuilt path without asking while the npm packages are unpublished', function (): void {
    [$command, $buffer] = installCommand();

    expect(LivewireUiComponentsInstallCommand::NPM_PACKAGES_PUBLISHED)->toBeFalse()
        ->and($command->askForAssetPath())->toBe('prebuilt')
        ->and($buffer->fetch())->toContain('arrives once the npm packages are published');
});

test('--assets=bundler warns while the npm packages are unpublished', function (): void {
    [$command, $buffer] = installCommand(['--assets' => 'bundler']);

    expect($command->askForAssetPath())->toBe('bundler')
        ->and($buffer->fetch())->toContain("aren't on npm yet");
});

test('the Welcome stub declares the class the root route uses', function (): void {
    $stub = (string) file_get_contents(dirname(__DIR__, 2).'/stubs/Welcome.php');

    expect(substr_count($stub, "\nnamespace "))->toBe(1)
        ->and($stub)->toContain('namespace App\\Livewire;')
        ->and($stub)->toContain('use ArtisanPack\\LivewireUiComponents\\Traits\\Toast;')
        ->and($stub)->toContain('use Illuminate\\Support\\Collection;')
        ->and($stub)->toContain('use Livewire\\Component;');
});

test('--assets rejects anything else', function (): void {
    [$command] = installCommand(['--assets' => 'cdn']);

    $command->askForAssetPath();
})->throws(RuntimeException::class, 'The --assets option must be "bundler" or "prebuilt".');

test('--tailwind turns on the Tailwind setup without asking', function (): void {
    [$command]     = installCommand(['--tailwind' => true]);
    [$withoutFlag] = installCommand();

    expect($command->askForTailwind())->toBeTrue()
        ->and($withoutFlag->askForTailwind())->toBeFalse();
});

test('the prebuilt layout uses the asset directives instead of Vite', function (): void {
    File::put($this->appRoot.'/composer.json', '{}');
    File::ensureDirectoryExists($this->appRoot.'/routes');
    $cwd = getcwd();
    chdir($this->appRoot);

    try {
        [$command] = installCommand();
        $command->copyStubs('No', LivewireUiComponentsInstallCommand::ASSETS_PREBUILT);
    } finally {
        chdir($cwd);
    }

    $layout = File::get($this->appRoot.'/resources/views/components/layouts/app.blade.php');

    expect($layout)->not->toContain('@vite')
        ->toContain("    @artisanpackStyles\n    @artisanpackScripts\n</head>");
});
