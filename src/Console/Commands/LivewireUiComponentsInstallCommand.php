<?php
/**
 * LivewireUiComponents Install Command
 *
 * This command installs and configures all the necessary dependencies for the
 * ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage Console\Commands
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;
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
     * The name and signature of the console command.
     *
     * @var string
     * @since 1.0.0
     */
    protected $signature = 'livewire-ui-components:install';

    /**
     * The console command description.
     *
     * @var string
     * @since 1.0.0
     */
    protected $description = 'Install and configure ArtisanPack UI Livewire UI Components';

    /**
     * Directory separator shorthand.
     *
     * @var string
     * @since 1.0.0
     */
    protected $ds = DIRECTORY_SEPARATOR;

    /**
     * Execute the console command.
     *
     * This is the main method that orchestrates the installation process.
     *
     * @return void
     * @since 1.0.0
     */
    public function handle()
    {
        $this->info("❤️  Livewire UI Components installer");

        // Laravel 12+
        $this->checkForLaravelVersion();

        // Install Volt ?
        $shouldInstallVolt = $this->askForVolt();

        //Yarn or Npm or Bun or Pnpm ?
        $packageManagerCommand = $this->askForPackageInstaller();

        // Install Livewire/Volt
        $this->installLivewire($shouldInstallVolt);

        // Setup Tailwind and Daisy
        $this->setupTailwindDaisy($packageManagerCommand);

        // Copy stubs if is brand-new project
        $this->copyStubs($shouldInstallVolt);

        // Rename components if Jetstream or Breeze are detected
        $this->renameComponents();

        // Clear view cache
        Artisan::call('view:clear');

        $this->info("\n");
        $this->info("✅  Done!");
        $this->info("❤️  Sponsor: https://github.com/sponsors/robsontenorio");
        $this->info("\n");
    }

    /**
     * Install Livewire and optionally Volt.
     *
     * @param string $shouldInstallVolt Whether to install Volt ('Yes' or 'No').
     * @return void
     * @since 1.0.0
     */
    public function installLivewire(string $shouldInstallVolt)
    {
        $this->info("\nInstalling Livewire...\n");

        $extra = $shouldInstallVolt == 'Yes'
            ? ' livewire/volt && php artisan volt:install'
            : '';

        Process::run("composer require livewire/livewire $extra", function (string $type, string $output) {
            echo $output;
        })->throw();
    }

    /**
     * Setup Tailwind CSS and daisyUI.
     *
     * Installs the necessary npm packages and configures the CSS file.
     *
     * @param string $packageManagerCommand The package manager command to use (npm, yarn, etc.).
     * @return void
     * @since 1.0.0
     */
    public function setupTailwindDaisy(string $packageManagerCommand)
    {
        /**
         * Install daisyUI + Tailwind
         */
        $this->info("\nInstalling daisyUI + Tailwind...\n");

        Process::run("$packageManagerCommand daisyui tailwindcss @tailwindcss/vite", function (string $type, string $output) {
            echo $output;
        })->throw();

        /**
         * Setup app.css
         */
        $cssPath = base_path() . "{$this->ds}resources{$this->ds}css{$this->ds}app.css";
        $css = File::get($cssPath);

        $livewireUiComponents = <<<EOT
            \n\n
            /**
                The lines above are intact.
                The lines below were added by Livewire UI Components installer.
            */

            /** daisyUI */
            @plugin "daisyui" {
                themes: light --default, dark --prefersdark;
            }

            /* Livewire UI Components */
            @source "../../vendor/artisanpack-ui/livewire-ui-components/resources/views/**/*.php";
            @source "../../vendor/artisanpack-ui/livewire-ui-components/src/View/Components/**/*.php";

            /* Theme toggle */
            @custom-variant dark (&:where(.dark, .dark *));

            /**
            * Paginator - Traditional style
            * Because Laravel defaults does not match well the design of daisyUI.
            */

            .artisanpack-table-pagination span[aria-current="page"] > span {
                @apply bg-primary text-base-100
            }

            .artisanpack-table-pagination button {
                @apply cursor-pointer
            }
            EOT;

        $css = str($css)->append($livewireUiComponents);

        File::put($cssPath, $css);
    }

    /**
     * Rename components to avoid name collisions with existing components.
     *
     * If Jetstream or Breeze are detected, we publish config file and add a global prefix
     * to Livewire UI Components components, in order to avoid name collision with existing components.
     *
     * @return void
     * @since 1.0.0
     */
    public function renameComponents()
    {
        $composerJson = File::get(base_path() . "/composer.json");

        collect(['jetstream', 'breeze', 'livewire/flux'])->each(function (string $target) use ($composerJson) {
            if (str($composerJson)->contains($target)) {
                Artisan::call('vendor:publish --force --tag livewire-ui-components.config');

                $path = base_path() . "{$this->ds}config{$this->ds}livewire-ui-components.php";
                $config = File::get($path);
                $contents = str($config)->replace("'prefix' => ''", "'prefix' => 'artisanpack-'");
                File::put($path, $contents);

                $this->warn('---------------------------------------------');
                $this->warn("🚨`$target` was detected.🚨");
                $this->warn('---------------------------------------------');
                $this->warn("A global prefix on Livewire UI Components components was added to avoid name collision.");
                $this->warn("\n * Example: x-artisanpack-button, x-artisanpack-card ...");
                $this->warn(" * See config/livewire-ui-components.php");
                $this->warn('---------------------------------------------');
            }
        });
    }

    /**
     * Copy example demo stubs if it is a brand-new project.
     *
     * This method copies various stub files to set up a demo application
     * if no starter kit is detected.
     *
     * @param string $shouldInstallVolt Whether Volt should be installed ('Yes' or 'No').
     * @return void
     * @since 1.0.0
     */
    public function copyStubs(string $shouldInstallVolt): void
    {
        $composerJson = File::get(base_path() . "/composer.json");
        $hasKit = str($composerJson)->contains('jetstream') || str($composerJson)->contains('breeze') || str($composerJson)->contains('livewire/flux');

        if ($hasKit) {
            $this->warn('---------------------------------------------');
            $this->warn('🚨 Starter kit detected. Skipping demo components. 🚨');
            $this->warn('---------------------------------------------');

            return;
        }

        $this->info("Copying stubs...\n");

        $routes = base_path() . "{$this->ds}routes";
        $appViewComponents = "app{$this->ds}View{$this->ds}Components";
        $livewirePath = "app{$this->ds}Livewire";
        $layoutsPath = "resources{$this->ds}views{$this->ds}components{$this->ds}layouts";
        $livewireBladePath = "resources{$this->ds}views{$this->ds}livewire";

        // Blade Brand component
        $this->createDirectoryIfNotExists($appViewComponents);
        $this->copyFile(__DIR__ . "/../../../stubs/AppBrand.php", "{$appViewComponents}{$this->ds}AppBrand.php");

        // Default app layout
        $this->createDirectoryIfNotExists($layoutsPath);
        $this->copyFile(__DIR__ . "/../../../stubs/app.blade.php", "{$layoutsPath}{$this->ds}app.blade.php");

        // Livewire blade views
        $this->createDirectoryIfNotExists($livewireBladePath);

        // Demo component and its route
        if ($shouldInstallVolt == 'Yes') {
            $this->createDirectoryIfNotExists("$livewireBladePath{$this->ds}users");
            $this->copyFile(__DIR__ . "/../../../stubs/index.blade.php", "$livewireBladePath{$this->ds}users{$this->ds}index.blade.php");
            $this->copyFile(__DIR__ . "/../../../stubs/web-volt.php", "$routes{$this->ds}web.php");
        } else {
            $this->createDirectoryIfNotExists($livewirePath);
            $this->copyFile(__DIR__ . "/../../../stubs/Welcome.php", "{$livewirePath}{$this->ds}Welcome.php");
            $this->copyFile(__DIR__ . "/../../../stubs/welcome.blade.php", "{$livewireBladePath}{$this->ds}welcome.blade.php");
            $this->copyFile(__DIR__ . "/../../../stubs/web.php", "$routes{$this->ds}web.php");
        }
    }

    /**
     * Ask the user which package manager to use.
     *
     * Detects available package managers (yarn, npm, bun, pnpm) and prompts
     * the user to select one.
     *
     * @return string The selected package manager command.
     * @since 1.0.0
     */
    public function askForPackageInstaller(): string
    {
        $os = PHP_OS;
        $findCommand = stripos($os, 'WIN') === 0 ? 'where' : 'which';

        $yarn = Process::run($findCommand . ' yarn')->output();
        $npm = Process::run($findCommand . ' npm')->output();
        $bun = Process::run($findCommand . ' bun')->output();
        $pnpm = Process::run($findCommand . ' pnpm')->output();

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

        if (count($options) == 0) {
            $this->error("You need yarn or npm or bun or pnpm installed.");

            exit;
        }

        return select(
            label: 'Install with ...',
            options: $options
        );
    }

    /**
     * Ask the user if they want to install Volt.
     *
     * Prompts the user to decide whether to install Livewire/Volt alongside Livewire.
     *
     * @return string The user's choice ('Yes' or 'No').
     * @since 1.0.0
     */
    public function askForVolt(): string
    {
        return select(
            'Also install `livewire/volt` ?',
            ['Yes', 'No'],
            hint: 'No matter what is your choice, it always installs `livewire/livewire`'
        );
    }

    /**
     * Check if the Laravel version is compatible.
     *
     * Ensures that the application is running on Laravel 12 or above.
     *
     * @return void
     * @since 1.0.0
     */
    public function checkForLaravelVersion(): void
    {
        if (version_compare(app()->version(), '12.0', '<')) {
            $this->error("❌  Laravel 12 or above required.");

            exit;
        }
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param string $path The directory path to create.
     * @return void
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
     * @param string $source      The source file path.
     * @param string $destination The destination file path.
     * @return void
     * @throws RuntimeException If the file copy fails.
     * @since 1.0.0
     */
    private function copyFile(string $source, string $destination): void
    {
        $source = str_replace('/', DIRECTORY_SEPARATOR, $source);
        $destination = str_replace('/', DIRECTORY_SEPARATOR, $destination);

        if (! copy($source, $destination)) {
            throw new RuntimeException("Failed to copy {$source} to {$destination}");
        }
    }
}
