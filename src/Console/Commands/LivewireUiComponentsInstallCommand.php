<?php

declare(strict_types=1);
/**
 * LivewireUiComponents Install Command
 *
 * This command installs and configures all the necessary dependencies for the
 * ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Support\DesignSystemAssets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

/**
 * LivewireUiComponentsInstallCommand Class
 *
 * Artisan command to install and configure all necessary dependencies for the package.
 *
 * @since 1.0.0
 */
class LivewireUiComponentsInstallCommand extends Command
{
    /**
     * The asset path for apps with a bundler: npm imports, compiled by Vite.
     *
     * @since 3.0.0
     */
    public const ASSETS_BUNDLER = 'bundler';

    /**
     * The asset path for apps without a bundler: published prebuilt files.
     *
     * @since 3.0.0
     */
    public const ASSETS_PREBUILT = 'prebuilt';

    /**
     * The asset path the installer offers first: prebuilt, which works in
     * every app. The prompt also offers the bundler path.
     *
     * @since 3.0.0
     */
    public const DEFAULT_ASSETS = self::ASSETS_PREBUILT;

    /**
     * Whether `NPM_PACKAGES` are on the npm registry. They are, from
     * design-system 1.0, so the installer always offers the bundler path.
     * Kept for code that read it during the 3.0 pre-releases.
     *
     * @since 3.0.0
     */
    public const NPM_PACKAGES_PUBLISHED = true;

    /**
     * The npm packages the bundler path installs, held to the design-system
     * major this release renders: 3.x markup needs the 1.x CSS and behavior.
     *
     * @since 3.0.0
     */
    public const NPM_PACKAGES = ['@artisanpack-ui/css@^1', '@artisanpack-ui/behavior@^1'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     * @since 1.0.0
     */
    protected $signature = 'livewire-ui-components:install
                            {--assets= : How to load the design system: bundler (npm + Vite) or prebuilt (no bundler)}
                            {--tailwind : With --assets=bundler, also set up the optional Tailwind CSS preset}';

    /**
     * The console command description.
     *
     * @var string
     *
     * @since 1.0.0
     */
    protected $description = 'Install and configure ArtisanPack UI Livewire UI Components';

    /**
     * Directory separator shorthand.
     *
     * @var string
     *
     * @since 1.0.0
     */
    protected $ds = DIRECTORY_SEPARATOR;

    /**
     * Execute the console command.
     *
     * This is the main method that orchestrates the installation process.
     *
     *
     * @since 1.0.0
     */
    public function handle(): void
    {
        $this->info(__('❤️  Livewire UI Components installer'));

        // Laravel 12+
        $this->checkForLaravelVersion();

        // Install Volt ?
        $shouldInstallVolt = $this->askForVolt();

        // Bundler (npm + Vite) or prebuilt assets (no bundler) ?
        $assets = $this->askForAssetPath();

        // Yarn or Npm or Bun or Pnpm, and Tailwind, for the bundler path only
        $packageManagerCommand = null;
        $withTailwind          = false;

        if (self::ASSETS_BUNDLER === $assets) {
            $packageManagerCommand = $this->askForPackageInstaller();
            $withTailwind          = $this->askForTailwind();
        }

        // Install Livewire/Volt
        $this->installLivewire($shouldInstallVolt);

        // Set up the design system's CSS and JS
        if (null !== $packageManagerCommand) {
            $this->setupBundlerAssets($packageManagerCommand, $withTailwind);
        } else {
            $this->setupPrebuiltAssets();
        }

        // Copy stubs if is brand-new project
        $this->copyStubs($shouldInstallVolt, $assets);

        // Check the component prefix if Jetstream, Breeze or Flux are detected
        $this->renameComponents();

        // Clear view cache
        Artisan::call('view:clear');

        $this->info("\n");
        $this->info(__('✅  Done!'));
        $this->info("\n");
    }

    /**
     * Install Livewire and optionally Volt.
     *
     * @param  string  $shouldInstallVolt  Whether to install Volt ('Yes' or 'No').
     *
     * @since 1.0.0
     */
    public function installLivewire(string $shouldInstallVolt): void
    {
        $this->info("\n".__('Installing Livewire...')."\n");

        $extra = 'Yes' == $shouldInstallVolt
            ? ' livewire/volt && php artisan volt:install'
            : '';

        Process::run("composer require livewire/livewire $extra", function (string $type, string $output): void {
            echo $output;
        })->throw();
    }

    /**
     * Set up the design system for apps with a bundler.
     *
     * Installs `@artisanpack-ui/css` and `@artisanpack-ui/behavior` (plus
     * Tailwind CSS when asked) and imports them in `resources/css/app.css`
     * and `resources/js/app.js`, which Vite compiles.
     *
     * @since 3.0.0
     *
     * @param  string  $packageManagerCommand  The package manager command to use (npm, yarn, etc.).
     * @param  bool  $withTailwind  Whether to set up the optional Tailwind CSS preset too.
     */
    public function setupBundlerAssets(string $packageManagerCommand, bool $withTailwind = false): void
    {
        $packages = self::NPM_PACKAGES;

        if ($withTailwind) {
            $packages = array_merge($packages, ['tailwindcss', '@tailwindcss/vite']);
        }

        $this->info("\n".__('Installing the ArtisanPack UI design system...')."\n");

        Process::run($packageManagerCommand.' '.implode(' ', array_map('escapeshellarg', $packages)), function (string $type, string $output): void {
            echo $output;
        })->throw();

        $cssPath = base_path("resources{$this->ds}css{$this->ds}app.css");
        $jsPath  = base_path("resources{$this->ds}js{$this->ds}app.js");

        File::ensureDirectoryExists(dirname($cssPath));
        File::ensureDirectoryExists(dirname($jsPath));

        $css = File::exists($cssPath) ? File::get($cssPath) : '';
        $this->warnAboutDaisyUi($css);

        File::put($cssPath, $this->addDesignSystemCss($css, $withTailwind));
        File::put($jsPath, $this->addDesignSystemJs(File::exists($jsPath) ? File::get($jsPath) : ''));
    }

    /**
     * Set up the design system for apps without a bundler.
     *
     * Publishes the prebuilt CSS and JS to `public/` and shows the Blade
     * directives that print their tags.
     *
     * @since 3.0.0
     */
    public function setupPrebuiltAssets(): void
    {
        $this->info("\n".__('Publishing the prebuilt ArtisanPack UI design system assets...')."\n");

        Artisan::call('vendor:publish', ['--tag' => DesignSystemAssets::PUBLISH_TAG, '--force' => true]);

        $cssPath = base_path("resources{$this->ds}css{$this->ds}app.css");
        if (File::exists($cssPath)) {
            $this->warnAboutDaisyUi(File::get($cssPath));
        }

        $this->info(__('Add these to your layout\'s <head>, before Livewire\'s script:'));
        $this->line('    @artisanpackStyles');
        $this->line('    @artisanpackScripts');
        $this->info(__('Use @artisanpackStyles(\'headless\') for the headless stylesheet.'));
        $this->info(__('After each package update, publish again with: php artisan vendor:publish --tag=:tag --force', [
            'tag' => DesignSystemAssets::PUBLISH_TAG,
        ]));
    }

    /**
     * Add the design system imports to an app's CSS.
     *
     * When Tailwind CSS is in the file, whether set up now or already there
     * (Laravel's default `app.css` imports it), the `apui` layer goes between
     * Tailwind's base and components, so Preflight never restyles a component
     * and Tailwind utilities still win. With `$withTailwind`, the Tailwind
     * preset and the component `@source` paths are added too. Each piece is
     * added only when it's missing, so running the installer again is safe.
     *
     * @since 3.0.0
     *
     * @param  string  $css  The current contents of `app.css`.
     * @param  bool  $withTailwind  Whether to set up the Tailwind CSS preset too.
     *
     * @return string The new contents.
     */
    public function addDesignSystemCss(string $css, bool $withTailwind = false): string
    {
        $tailwindImport = '/^@import\s+[\'"]tailwindcss[\'"][^;\n]*;[^\n]*$/m';
        $hasTailwind    = 1 === preg_match($tailwindImport, $css);
        $hasStylesheet  = 1 === preg_match('/@import\s+[\'"]@artisanpack-ui\/css[\'"]/', $css);

        if (! $withTailwind && ! $hasTailwind) {
            return $hasStylesheet ? $css : "/* ArtisanPack UI design system */\n@import '@artisanpack-ui/css';\n\n".ltrim($css);
        }

        $imports = [];
        if (! $hasStylesheet) {
            $imports[] = "@import '@artisanpack-ui/css';";
        }
        if ($withTailwind && ! str_contains($css, '@artisanpack-ui/css/tailwind')) {
            $imports[] = "@import '@artisanpack-ui/css/tailwind';";
        }
        if ([] !== $imports) {
            $block = implode("\n", $imports);
            $css   = $hasTailwind
                ? (string) preg_replace($tailwindImport, "\$0\n{$block}", $css, 1)
                : "@import 'tailwindcss';\n{$block}\n\n".ltrim($css);
        }

        if (1 !== preg_match('/@layer\s+theme\s*,\s*base\s*,\s*apui\s*,\s*components\s*,\s*utilities\s*;/', $css)) {
            $css = "/* ArtisanPack UI design system: the apui layer sits between Tailwind's base and components. */\n"
                ."@layer theme, base, apui, components, utilities;\n\n".ltrim($css);
        }

        if ($withTailwind && ! str_contains($css, 'livewire-ui-components/resources/views')) {
            $css = rtrim($css)."\n".<<<'EOT'

                /* Livewire UI Components */
                @source "../../vendor/artisanpack-ui/livewire-ui-components/resources/views/**/*.php";
                @source "../../vendor/artisanpack-ui/livewire-ui-components/src/View/Components/**/*.php";

                EOT;
        }

        return $css;
    }

    /**
     * Warn when the app's CSS still loads daisyUI, which 3.0 no longer uses.
     *
     * @since 3.0.0
     *
     * @param  string  $css  The contents of `app.css`.
     */
    public function warnAboutDaisyUi(string $css): void
    {
        if (1 !== preg_match('/@plugin\s+[\'"]daisyui/', $css)) {
            return;
        }

        $this->warn(__('resources/css/app.css still loads daisyUI. Livewire UI Components 3.0 is styled by the design system and no longer uses it: remove the @plugin "daisyui" block, any @apply rules that use daisyUI colors, and daisyui from package.json.'));
    }

    /**
     * Register the design system's Alpine adapter in an app's JS.
     *
     * Livewire starts Alpine, so the plugin registers on `alpine:init`. Vite's
     * module script runs before Livewire starts Alpine. Leaves the JS
     * unchanged when it already imports the adapter.
     *
     * @since 3.0.0
     *
     * @param  string  $js  The current contents of `app.js`.
     *
     * @return string The new contents.
     */
    public function addDesignSystemJs(string $js): string
    {
        if (str_contains($js, '@artisanpack-ui/behavior/alpine')) {
            return $js;
        }

        $adapter = <<<'EOT'
            /* ArtisanPack UI design system: the behavior engine's Alpine adapter. */
            import apuiBehavior from '@artisanpack-ui/behavior/alpine';

            document.addEventListener('alpine:init', () => window.Alpine.plugin(apuiBehavior));

            EOT;

        $js = rtrim($js);

        return '' === $js ? $adapter : $js."\n\n".$adapter;
    }

    /**
     * Check the component prefix when a starter kit is installed.
     *
     * Jetstream, Breeze and Flux ship components such as `<x-button>`. The
     * default `artisanpack` prefix keeps this package's components apart
     * from them, so the installer only warns when the configured prefix is
     * empty. It never publishes or rewrites a config file, so an existing
     * `config/livewire-ui-components.php` is left as it is.
     *
     * @since 1.0.0
     */
    public function renameComponents(): void
    {
        $composerJson = File::get(base_path().'/composer.json');
        $kit          = collect(['jetstream', 'breeze', 'livewire/flux'])
            ->first(fn (string $target): bool => str($composerJson)->contains($target));

        if (null === $kit) {
            return;
        }

        $prefix = LivewireUiComponentsServiceProvider::componentPrefix();

        $this->warn('---------------------------------------------');
        $this->warn(__('🚨`:target` was detected.🚨', ['target' => $kit]));
        $this->warn('---------------------------------------------');

        if ('' === $prefix) {
            $this->warn(__('The component prefix is empty, so tags such as <x-button> may collide with the starter kit\'s components.'));
            $this->warn(__('Set a prefix, such as \'artisanpack\', in your livewire-ui-components config file, then run php artisan view:clear.'));
        } else {
            $this->info(__('Components use the ":prefix" prefix (<x-:prefix-button>), so they don\'t collide with the starter kit\'s components.', ['prefix' => $prefix]));
        }

        $this->warn('---------------------------------------------');
    }

    /**
     * Copy example demo stubs if it is a brand-new project.
     *
     * This method copies various stub files to set up a demo application
     * if no starter kit is detected.
     *
     * @param  string  $shouldInstallVolt  Whether Volt should be installed ('Yes' or 'No').
     * @param  string  $assets  The asset path: 'bundler' keeps the layout's `@vite`, 'prebuilt' swaps in the asset directives.
     *
     * @since 1.0.0
     */
    public function copyStubs(string $shouldInstallVolt, string $assets = self::ASSETS_BUNDLER): void
    {
        $composerJson = File::get(base_path().'/composer.json');
        $hasKit       = str($composerJson)->contains('jetstream') || str($composerJson)->contains('breeze') || str($composerJson)->contains('livewire/flux');

        if ($hasKit) {
            $this->warn('---------------------------------------------');
            $this->warn(__('🚨 Starter kit detected. Skipping demo components. 🚨'));
            $this->warn('---------------------------------------------');

            return;
        }

        $this->info(__('Copying stubs...')."\n");

        $routes            = base_path()."{$this->ds}routes";
        $appViewComponents = "app{$this->ds}View{$this->ds}Components";
        $livewirePath      = "app{$this->ds}Livewire";
        $layoutsPath       = "resources{$this->ds}views{$this->ds}components{$this->ds}layouts";
        $livewireBladePath = "resources{$this->ds}views{$this->ds}livewire";

        // Blade Brand component
        $this->createDirectoryIfNotExists($appViewComponents);
        $this->copyFile(__DIR__.'/../../../stubs/AppBrand.php', "{$appViewComponents}{$this->ds}AppBrand.php");

        // Default app layout
        $this->createDirectoryIfNotExists($layoutsPath);
        $this->copyFile(__DIR__.'/../../../stubs/app.blade.php', "{$layoutsPath}{$this->ds}app.blade.php");

        if (self::ASSETS_PREBUILT === $assets) {
            $this->usePrebuiltAssetsInLayout("{$layoutsPath}{$this->ds}app.blade.php");
        }

        // Livewire blade views
        $this->createDirectoryIfNotExists($livewireBladePath);

        // Demo component and its route
        if ('Yes' == $shouldInstallVolt) {
            $this->createDirectoryIfNotExists("$livewireBladePath{$this->ds}users");
            $this->copyFile(__DIR__.'/../../../stubs/index.blade.php', "$livewireBladePath{$this->ds}users{$this->ds}index.blade.php");
            $this->copyFile(__DIR__.'/../../../stubs/web-volt.php', "$routes{$this->ds}web.php");
        } else {
            $this->createDirectoryIfNotExists($livewirePath);
            $this->copyFile(__DIR__.'/../../../stubs/Welcome.php', "{$livewirePath}{$this->ds}Welcome.php");
            $this->copyFile(__DIR__.'/../../../stubs/welcome.blade.php', "{$livewireBladePath}{$this->ds}welcome.blade.php");
            $this->copyFile(__DIR__.'/../../../stubs/web.php', "$routes{$this->ds}web.php");
        }
    }

    /**
     * Ask the user which package manager to use.
     *
     * Detects available package managers (yarn, npm, bun, pnpm) and prompts
     * the user to select one.
     *
     * @return string The selected package manager command.
     *
     * @since 1.0.0
     */
    public function askForPackageInstaller(): string
    {
        $os          = PHP_OS;
        $findCommand = 0 === stripos($os, 'WIN') ? 'where' : 'which';

        $yarn = Process::run($findCommand.' yarn')->output();
        $npm  = Process::run($findCommand.' npm')->output();
        $bun  = Process::run($findCommand.' bun')->output();
        $pnpm = Process::run($findCommand.' pnpm')->output();

        $options = [];

        if (Str::of($yarn)->isNotEmpty()) {
            $options = array_merge($options, ['yarn add -D' => 'yarn']);
        }

        if (Str::of($npm)->isNotEmpty()) {
            $options = array_merge($options, ['npm install --save-dev' => 'npm']);
        }

        if (Str::of($bun)->isNotEmpty()) {
            $options = array_merge($options, ['bun i -D' => 'bun']);
        }

        if (Str::of($pnpm)->isNotEmpty()) {
            $options = array_merge($options, ['pnpm i -D' => 'pnpm']);
        }

        if (0 == count($options)) {
            $this->error(__('You need yarn or npm or bun or pnpm installed.'));

            exit;
        }

        return select(
            label: __('Install with ...'),
            options: $options,
        );
    }

    /**
     * Ask the user if they want to install Volt.
     *
     * Prompts the user to decide whether to install Livewire/Volt alongside Livewire.
     *
     * @return string The user's choice ('Yes' or 'No').
     *
     * @since 1.0.0
     */
    public function askForVolt(): string
    {
        return select(
            __('Also install `livewire/volt` ?'),
            ['Yes', 'No'],
            hint: __('No matter what is your choice, it always installs `livewire/livewire`'),
        );
    }

    /**
     * Ask how the app loads the design system's CSS and JS.
     *
     * Reads `--assets` when given. Otherwise asks: with a bundler, the npm
     * packages are imported and compiled by Vite; without one, the prebuilt
     * files this package ships are published to `public/`.
     *
     * @since 3.0.0
     *
     * @throws RuntimeException When `--assets` is not 'bundler' or 'prebuilt'.
     *
     * @return string 'bundler' or 'prebuilt'.
     */
    public function askForAssetPath(): string
    {
        $option = $this->option('assets');

        if (null !== $option && '' !== $option) {
            if (! in_array($option, [self::ASSETS_BUNDLER, self::ASSETS_PREBUILT], true)) {
                throw new RuntimeException(__('The --assets option must be ":bundler" or ":prebuilt".', [
                    'bundler'  => self::ASSETS_BUNDLER,
                    'prebuilt' => self::ASSETS_PREBUILT,
                ]));
            }

            return $option;
        }

        return (string) select(
            label: __('How should your app load the design system CSS and JS?'),
            options: [
                self::ASSETS_PREBUILT => __('Without a bundler: publish the prebuilt files'),
                self::ASSETS_BUNDLER  => __('With a bundler: npm packages, compiled by Vite'),
            ],
            default: self::DEFAULT_ASSETS,
        );
    }

    /**
     * Ask whether to set up the optional Tailwind CSS preset.
     *
     * Tailwind is optional: the design system's CSS is plain CSS. The preset
     * adds utilities that read the design tokens.
     *
     * @since 3.0.0
     */
    public function askForTailwind(): bool
    {
        if ($this->option('tailwind') || ! $this->input->isInteractive()) {
            return (bool) $this->option('tailwind');
        }

        return confirm(
            label: __('Also use Tailwind CSS with the design system?'),
            default: false,
            hint: __('Optional. Adds Tailwind utilities that read the design tokens.'),
        );
    }

    /**
     * Check if the Laravel version is compatible.
     *
     * Ensures that the application is running on Laravel 12 or above.
     *
     * @since 1.0.0
     */
    public function checkForLaravelVersion(): void
    {
        if (version_compare(app()->version(), '12.0', '<')) {
            $this->error(__('❌  Laravel 12 or above required.'));

            exit;
        }
    }

    /**
     * Swap the layout's `@vite` line for the prebuilt asset directives.
     *
     * @since 3.0.0
     *
     * @param  string  $layoutPath  The copied layout file.
     */
    private function usePrebuiltAssetsInLayout(string $layoutPath): void
    {
        $layout = (string) preg_replace(
            '/^([ \t]*)@vite\(.*\)[ \t]*$/m',
            "$1@artisanpackStyles\n$1@artisanpackScripts",
            File::get($layoutPath),
            1,
        );

        File::put($layoutPath, $layout);
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param  string  $path  The directory path to create.
     *
     * @since 1.0.0
     */
    private function createDirectoryIfNotExists(string $path): void
    {
        if (! file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * Copy a file from source to destination.
     *
     * @param  string  $source  The source file path.
     * @param  string  $destination  The destination file path.
     *
     * @throws RuntimeException If the file copy fails.
     *
     * @since 1.0.0
     */
    private function copyFile(string $source, string $destination): void
    {
        $source      = str_replace('/', DIRECTORY_SEPARATOR, $source);
        $destination = str_replace('/', DIRECTORY_SEPARATOR, $destination);

        if (! copy($source, $destination)) {
            throw new RuntimeException("Failed to copy {$source} to {$destination}");
        }
    }
}
