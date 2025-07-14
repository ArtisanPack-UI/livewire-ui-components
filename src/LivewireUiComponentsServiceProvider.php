<?php
/**
 * LivewireUiComponents Service Provider
 *
 * This service provider registers all the components, directives, and services
 * provided by the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents;

use Arr;
use ArtisanPackUI\LivewireUIComponents\Console\Commands\GenerateThemeCss;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsBootcampCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use ArtisanPack\LivewireUiComponents\View\Components\Accordion;
use ArtisanPack\LivewireUiComponents\View\Components\Alert;
use ArtisanPack\LivewireUiComponents\View\Components\Avatar;
use ArtisanPack\LivewireUiComponents\View\Components\Badge;
use ArtisanPack\LivewireUiComponents\View\Components\Breadcrumbs;
use ArtisanPack\LivewireUiComponents\View\Components\Button;
use ArtisanPack\LivewireUiComponents\View\Components\Calendar;
use ArtisanPack\LivewireUiComponents\View\Components\Card;
use ArtisanPack\LivewireUiComponents\View\Components\Carousel;
use ArtisanPack\LivewireUiComponents\View\Components\Chart;
use ArtisanPack\LivewireUiComponents\View\Components\Checkbox;
use ArtisanPack\LivewireUiComponents\View\Components\Choices;
use ArtisanPack\LivewireUiComponents\View\Components\ChoicesOffline;
use ArtisanPack\LivewireUiComponents\View\Components\Code;
use ArtisanPack\LivewireUiComponents\View\Components\Collapse;
use ArtisanPack\LivewireUiComponents\View\Components\Colorpicker;
use ArtisanPack\LivewireUiComponents\View\Components\DatePicker;
use ArtisanPack\LivewireUiComponents\View\Components\DateTime;
use ArtisanPack\LivewireUiComponents\View\Components\Diff;
use ArtisanPack\LivewireUiComponents\View\Components\Drawer;
use ArtisanPack\LivewireUiComponents\View\Components\Dropdown;
use ArtisanPack\LivewireUiComponents\View\Components\Editor;
use ArtisanPack\LivewireUiComponents\View\Components\Errors;
use ArtisanPack\LivewireUiComponents\View\Components\File;
use ArtisanPack\LivewireUiComponents\View\Components\Form;
use ArtisanPack\LivewireUiComponents\View\Components\Group;
use ArtisanPack\LivewireUiComponents\View\Components\Header;
use ArtisanPack\LivewireUiComponents\View\Components\Hr;
use ArtisanPack\LivewireUiComponents\View\Components\Icon;
use ArtisanPack\LivewireUiComponents\View\Components\ImageGallery;
use ArtisanPack\LivewireUiComponents\View\Components\ImageLibrary;
use ArtisanPack\LivewireUiComponents\View\Components\Input;
use ArtisanPack\LivewireUiComponents\View\Components\Kbd;
use ArtisanPack\LivewireUiComponents\View\Components\ListItem;
use ArtisanPack\LivewireUiComponents\View\Components\Loading;
use ArtisanPack\LivewireUiComponents\View\Components\Main;
use ArtisanPack\LivewireUiComponents\View\Components\Markdown;
use ArtisanPack\LivewireUiComponents\View\Components\Menu;
use ArtisanPack\LivewireUiComponents\View\Components\MenuItem;
use ArtisanPack\LivewireUiComponents\View\Components\MenuSeparator;
use ArtisanPack\LivewireUiComponents\View\Components\MenuSub;
use ArtisanPack\LivewireUiComponents\View\Components\MenuTitle;
use ArtisanPack\LivewireUiComponents\View\Components\Modal;
use ArtisanPack\LivewireUiComponents\View\Components\Nav;
use ArtisanPack\LivewireUiComponents\View\Components\Pagination;
use ArtisanPack\LivewireUiComponents\View\Components\Password;
use ArtisanPack\LivewireUiComponents\View\Components\Pin;
use ArtisanPack\LivewireUiComponents\View\Components\Popover;
use ArtisanPack\LivewireUiComponents\View\Components\Progress;
use ArtisanPack\LivewireUiComponents\View\Components\ProgressRadial;
use ArtisanPack\LivewireUiComponents\View\Components\Radio;
use ArtisanPack\LivewireUiComponents\View\Components\Range;
use ArtisanPack\LivewireUiComponents\View\Components\Rating;
use ArtisanPack\LivewireUiComponents\View\Components\Select;
use ArtisanPack\LivewireUiComponents\View\Components\SelectGroup;
use ArtisanPack\LivewireUiComponents\View\Components\Signature;
use ArtisanPack\LivewireUiComponents\View\Components\Spotlight;
use ArtisanPack\LivewireUiComponents\View\Components\Stat;
use ArtisanPack\LivewireUiComponents\View\Components\Step;
use ArtisanPack\LivewireUiComponents\View\Components\Steps;
use ArtisanPack\LivewireUiComponents\View\Components\Swap;
use ArtisanPack\LivewireUiComponents\View\Components\Tab;
use ArtisanPack\LivewireUiComponents\View\Components\Table;
use ArtisanPack\LivewireUiComponents\View\Components\Tabs;
use ArtisanPack\LivewireUiComponents\View\Components\Tags;
use ArtisanPack\LivewireUiComponents\View\Components\Textarea;
use ArtisanPack\LivewireUiComponents\View\Components\ThemeToggle;
use ArtisanPack\LivewireUiComponents\View\Components\TimelineItem;
use ArtisanPack\LivewireUiComponents\View\Components\Toast;
use ArtisanPack\LivewireUiComponents\View\Components\Toggle;

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
     * Perform post-registration booting of services.
     *
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework.
     *
     * @return void
     * @since 1.0.0
     */
    public function boot(): void
    {
        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register all Blade components provided by the package.
     *
     * This method registers both aliased components and prefixed components
     * based on the package configuration.
     *
     * @return void
     * @since 1.0.0
     */
    public function registerComponents()
    {
        // Just rename <x-icon> provided by BladeUI Icons to <x-svg> to not collide with ours
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        /**
         * Register the components with the 'artisanpack' prefix.
         *
         * This tells Blade to look for components in the
         * 'ArtisanPack\LivewireUiComponents\View\Components' namespace
         * when it encounters a tag like <artisanpack:button />.
         */
        Blade::componentNamespace('ArtisanPack\\LivewireUiComponents\\View\\Components', 'artisanpack');
    }

    /**
     * Register all Blade directives provided by the package.
     *
     * @return void
     * @since 1.0.0
     */
    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

    /**
     * Register the @scope Blade directive for scoped slots.
     *
     * @return void
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
        Blade::directive('scope', function ($expression) {
            // Split the expression by `top-level` commas (not in parentheses)
            $directiveArguments = preg_split("/,(?![^\(\(]*[\)\)])/", $expression);
            $directiveArguments = array_map('trim', $directiveArguments);

            [$name, $functionArguments] = $directiveArguments;

            // Build function "uses" to inject extra external variables
            $uses = Arr::except(array_flip($directiveArguments), [$name, $functionArguments]);
            $uses = array_flip($uses);
            array_push($uses, '$__env');
            array_push($uses, '$__bladeCompiler');
            $uses = implode(',', $uses);

            /**
             *  Slot names can`t contains dot , eg: `user.city`.
             *  So we convert `user.city` to `user___city`
             *
             *  Later, on component it will be replaced back.
             */
            $name = str_replace('.', '___', $name);

            return "<?php \$__bladeCompiler = \$__bladeCompiler ?? null; \$loop = null; \$__env->slot({$name}, function({$functionArguments}) use ({$uses}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
        });

        Blade::directive('endscope', function () {
            return '<?php }); ?>';
        });
    }

    /**
     * Register any package services.
     *
     * This method is called before the boot method and registers
     * the package's services in the service container.
     *
     * @return void
     * @since 1.0.0
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/livewire-ui-components.php', 'livewire-ui-components');

        // Register the service the package provides.
        $this->app->singleton('livewire-ui-components', function ($app) {
            return new LivewireUiComponents();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array The array of provided services.
     * @since 1.0.0
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
     * @return void
     * @since 1.0.0
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/livewire-ui-components.php' => config_path('livewire-ui-components.php'),
        ], 'livewire-ui-components.config');

        $this->commands([LivewireUiComponentsInstallCommand::class, LivewireUiComponentsBootcampCommand::class, GenerateThemeCss::class,]);
    }
}
