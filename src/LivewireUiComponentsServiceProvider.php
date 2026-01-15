<?php

declare(strict_types=1);
/**
 * LivewireUiComponents Service Provider
 *
 * This service provider registers all the components, directives, and services
 * provided by the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents;

use Arr;
use ArtisanPack\LivewireUiComponents\Console\Commands\GenerateIdeHelperCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\GenerateThemeCss;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsBootcampCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * LivewireUiComponents Service Provider Class
 *
 * Registers all components, directives, and services for the package.
 *
 * @since 1.0.0
 */
class LivewireUiComponentsServiceProvider extends ServiceProvider
{
    /**
     * An array of the Blade components this package provides.
     *
     * This array is the single source of truth for all Blade components.
     *
     * @since 1.0.0
     */
    protected array $bladeComponents = [
        'accordion'        => View\Components\Accordion::class,
        'alert'            => View\Components\Alert::class,
        'avatar'           => View\Components\Avatar::class,
        'badge'            => View\Components\Badge::class,
        'breadcrumbs'      => View\Components\Breadcrumbs::class,
        'button'           => View\Components\Button::class,
        'card'             => View\Components\Card::class,
        'carousel'         => View\Components\Carousel::class,
        'chart'            => View\Components\Chart::class,
        'checkbox'         => View\Components\Checkbox::class,
        'checkbox-group'   => View\Components\CheckboxGroup::class,
        'choices'          => View\Components\Choices::class,
        'choices-offline'  => View\Components\ChoicesOffline::class,
        'code'             => View\Components\Code::class,
        'collapse'         => View\Components\Collapse::class,
        'colorpicker'      => View\Components\Colorpicker::class,
        'datepicker'       => View\Components\DatePicker::class,
        'datetime'         => View\Components\DateTime::class,
        'diff'             => View\Components\Diff::class,
        'drawer'           => View\Components\Drawer::class,
        'dropdown'         => View\Components\Dropdown::class,
        'editor'           => View\Components\Editor::class,
        'errors'           => View\Components\Errors::class,
        'fieldset'         => View\Components\Fieldset::class,
        'file'             => View\Components\File::class,
        'form'             => View\Components\Form::class,
        'group'            => View\Components\Group::class,
        'header'           => View\Components\Header::class,
        'heading'          => View\Components\Heading::class,
        'icon'             => View\Components\Icon::class,
        'image-gallery'    => View\Components\ImageGallery::class,
        'image-library'    => View\Components\ImageLibrary::class,
        'image-slider'     => View\Components\ImageSlider::class,
        'input'            => View\Components\Input::class,
        'kbd'              => View\Components\Kbd::class,
        'link'             => View\Components\Link::class,
        'list-item'        => View\Components\ListItem::class,
        'loading'          => View\Components\Loading::class,
        'main'             => View\Components\Main::class,
        'markdown'         => View\Components\Markdown::class,
        'menu'             => View\Components\Menu::class,
        'menu-item'        => View\Components\MenuItem::class,
        'menu-separator'   => View\Components\MenuSeparator::class,
        'menu-sub'         => View\Components\MenuSub::class,
        'menu-title'       => View\Components\MenuTitle::class,
        'modal'            => View\Components\Modal::class,
        'nav'              => View\Components\Nav::class,
        'pagination'       => View\Components\Pagination::class,
        'password'         => View\Components\Password::class,
        'pin'              => View\Components\Pin::class,
        'popover'          => View\Components\Popover::class,
        'profile'          => View\Components\Profile::class,
        'progress'         => View\Components\Progress::class,
        'progress-radial'  => View\Components\ProgressRadial::class,
        'radio'            => View\Components\Radio::class,
        'radio-group'      => View\Components\RadioGroup::class,
        'range'            => View\Components\Range::class,
        'rating'           => View\Components\Rating::class,
        'select'           => View\Components\Select::class,
        'select-group'     => View\Components\SelectGroup::class,
        'separator'        => View\Components\Separator::class,
        'signature'        => View\Components\Signature::class,
        'spotlight'        => View\Components\Spotlight::class,
        'stat'             => View\Components\Stat::class,
        'step'             => View\Components\Step::class,
        'steps'            => View\Components\Steps::class,
        'subheading'       => View\Components\Subheading::class,
        'swap'             => View\Components\Swap::class,
        'tab'              => View\Components\Tab::class,
        'table'            => View\Components\Table::class,
        'tabs'             => View\Components\Tabs::class,
        'tags'             => View\Components\Tags::class,
        'text'             => View\Components\Text::class,
        'textarea'         => View\Components\Textarea::class,
        'theme-toggle'     => View\Components\ThemeToggle::class,
        'timeline-item'    => View\Components\TimelineItem::class,
        'toast'            => View\Components\Toast::class,
        'toggle'           => View\Components\Toggle::class,
    ];

    /**
     * An array of the Livewire components this package provides.
     *
     * This array is the single source of truth for all Livewire components.
     *
     * @since 1.0.0
     */
    protected array $livewireComponents = [
        'calendar'            => View\Components\Calendar::class,
        'event-modal-content' => View\Components\EventModalContent::class,
    ];

    /**
     * Perform post-registration booting of services.
     *
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework.
     *
     * @since 1.0.0
     */
    public function boot(): void
    {
        $this->mergeConfiguration();

        if ($this->app->runningInConsole()) {
            // Tag the package's config file for our scaffold command to find.
            $this->publishes(
                [
                    __DIR__.'/../config/livewire-ui-components.php' => config_path('artisanpack/livewire-ui-components.php'),
                ],
                'artisanpack-package-config',
            );
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-ui-components');
        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes(
            [
                __DIR__.'/../resources/js'  => public_path('vendor/artisanpack-ui/js'),
                __DIR__.'/../resources/css' => public_path('vendor/artisanpack-ui/css'),
            ],
            'artisanpack-assets',
        );

        // Publish glass design tokens CSS separately for easy customization
        $this->publishes(
            [
                __DIR__.'/../resources/css/glass-tokens.css' => resource_path('css/artisanpack-glass-tokens.css'),
            ],
            'artisanpack-glass-tokens',
        );

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register all Blade components provided by the package.
     *
     * This method registers both standardized components and their deprecated
     * aliases for backwards compatibility. It respects the custom prefix
     * set in the package's configuration file.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function registerComponents(): void
    {
        // Rename <x-icon> from BladeUI Icons to prevent collision.
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        // Get the user-defined prefix from the config, defaulting to 'artisanpack'.
        $prefix = config('artisanpack.livewire-ui-components.prefix', 'artisanpack');

        // Register all Blade components.
        foreach ($this->bladeComponents as $alias => $class) {
            // Build the component name with prefix (e.g., 'artisanpack-button').
            // If prefix is empty, just use the alias.
            $newName = $prefix ? $prefix.'-'.$alias : $alias;

            // Register the component with the correct name.
            Blade::component($class, $newName);
        }

        // Register all Livewire components.
        foreach ($this->livewireComponents as $alias => $class) {
            $componentName = $prefix ? $prefix.'-'.$alias : $alias;
            Livewire::component($componentName, $class);
        }
    }

    /**
     * Register all Blade directives provided by the package.
     *
     * @since 1.0.0
     */
    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

    /**
     * Register the @scope Blade directive for scoped slots.
     *
     * @since 1.0.0
     */
    public function registerScopeDirective(): void
    {
        /**
         * All credits from this blade directive goes to Konrad Kalemba.
         * Just copied and modified for my very specific use case.
         *
         * https://github.com/konradkalemba/blade-components-scoped-slots
         */
        Blade::directive(
            'scope',
            function ($expression) {
                // Split the expression by `top-level` commas (not in parentheses).
                $directiveArguments = preg_split("/,(?![^\(\(]*[\)\)])/", $expression);
                $directiveArguments = array_map('trim', $directiveArguments);

                [$name, $functionArguments] = $directiveArguments;

                // Build function "uses" to inject extra external variables.
                $uses = Arr::except(array_flip($directiveArguments), [$name, $functionArguments]);
                $uses = array_flip($uses);
                array_push($uses, '$__env');
                array_push($uses, '$__bladeCompiler');
                $uses = implode(',', $uses);

                /**
                 * Slot names can`t contains dot , eg: `user.city`.
                 * So we convert `user.city` to `user___city`
                 *
                 * Later, on component it will be replaced back.
                 */
                $name = str_replace('.', '___', $name);

                return "<?php \$__bladeCompiler = \$__bladeCompiler ?? null; \$loop = null; \$__env->slot({$name}, function({$functionArguments}) use ({$uses}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
            },
        );

        Blade::directive(
            'endscope',
            function () {
                return '<?php }); ?>';
            },
        );
    }

    /**
     * Register any package services.
     *
     * This method is called before the boot method and registers
     * the package's services in the service container.
     *
     * @since 1.0.0
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/livewire-ui-components.php', 'artisanpack-livewire-ui-components-temp');

        // Register the service the package provides.
        $this->app->singleton(
            'livewire-ui-components',
            function ($app) {
                return new LivewireUiComponents;
            },
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @since 1.0.0
     *
     * @return array The array of provided services.
     */
    public function provides()
    {
        return ['livewire-ui-components'];
    }

    /**
     * Console-specific booting.
     *
     * This method is called when the application is running in the console
     * and handles publishing assets and registering commands.
     *
     * @since 1.0.0
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes(
            [
                __DIR__.'/../config/livewire-ui-components.php' => config_path('livewire-ui-components.php'),
            ],
            'livewire-ui-components.config',
        );

        $this->commands([LivewireUiComponentsInstallCommand::class,
            LivewireUiComponentsBootcampCommand::class,
            GenerateThemeCss::class,
            GenerateIdeHelperCommand::class,
        ]);
    }

    /**
     * Merges the package's default configuration with the user's customizations.
     *
     * This method ensures that the user's settings in `config/artisanpack.php`
     * take precedence over the package's default values.
     *
     * @since 1.0.0
     */
    protected function mergeConfiguration(): void
    {
        // Get the package's default configuration.
        $packageDefaults = config('artisanpack-livewire-ui-components-temp', []);

        // Get the user's custom configuration from config/artisanpack.php.
        $userConfig = config('artisanpack.livewire-ui-components', []);

        // Merge them, with the user's config overwriting the defaults.
        $mergedConfig = array_replace_recursive($packageDefaults, $userConfig);

        // Set the final, correctly merged configuration.
        config(['artisanpack.livewire-ui-components' => $mergedConfig]);
    }
}
