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
use ArtisanPack\LivewireUiComponents\Console\Commands\GenerateThemeCss;
use ArtisanPack\LivewireUiComponents\View\Components\EventModalContent;
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
use ArtisanPack\LivewireUiComponents\View\Components\Heading;
use ArtisanPack\LivewireUiComponents\View\Components\Subheading;
use ArtisanPack\LivewireUiComponents\View\Components\Text;
use ArtisanPack\LivewireUiComponents\View\Components\Link;
use Laravel\Prompts\Concerns\Events;
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-ui-components');
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

        $prefix = config('livewire-ui-components.prefix');

        // No matter if components has custom prefix or not,
        // we also register below alias to avoid naming collision,
        // because they are used inside some Mary's components itself.
        Blade::component($prefix . 'button', Button::class);
        Blade::component($prefix . 'card', Card::class);
        Blade::component($prefix . 'icon', Icon::class);
        Blade::component($prefix . 'input', Input::class);
        Blade::component($prefix . 'list-item', ListItem::class);
        Blade::component($prefix . 'modal', Modal::class);
        Blade::component($prefix . 'menu', Menu::class);
        Blade::component($prefix . 'menu-item', MenuItem::class);
        Blade::component($prefix . 'header', Header::class);
        Blade::component($prefix . 'pagination', Pagination::class);

        // Blade
        Blade::component($prefix . 'accordion', Accordion::class);
        Blade::component($prefix . 'alert', Alert::class);
        Blade::component($prefix . 'avatar', Avatar::class);
        Blade::component($prefix . 'badge', Badge::class);
        Blade::component($prefix . 'breadcrumbs', Breadcrumbs::class);
        Blade::component($prefix . 'button', Button::class);
        Blade::component($prefix . 'card', Card::class);
        Blade::component($prefix . 'chart', Chart::class);
        Blade::component($prefix . 'checkbox', Checkbox::class);
        Blade::component($prefix . 'choices', Choices::class);
        Blade::component($prefix . 'choices-offline', ChoicesOffline::class);
        Blade::component($prefix . 'code', Code::class);
        Blade::component($prefix . 'collapse', Collapse::class);
        Blade::component($prefix . 'colorpicker', Colorpicker::class);
        Blade::component($prefix . 'datepicker', DatePicker::class);
        Blade::component($prefix . 'datetime', DateTime::class);
        Blade::component($prefix . 'diff', Diff::class);
        Blade::component($prefix . 'drawer', Drawer::class);
        Blade::component($prefix . 'dropdown', Dropdown::class);
        Blade::component($prefix . 'editor', Editor::class);
        Blade::component($prefix . 'errors', Errors::class);
        Blade::component($prefix . 'file', File::class);
        Blade::component($prefix . 'form', Form::class);
        Blade::component($prefix . 'select-group', SelectGroup::class);
        Blade::component($prefix . 'header', Header::class);
        Blade::component($prefix . 'hr', Hr::class);
        Blade::component($prefix . 'icon', Icon::class);
        Blade::component($prefix . 'image-gallery', ImageGallery::class);
        Blade::component($prefix . 'image-library', ImageLibrary::class);
        Blade::component($prefix . 'input', Input::class);
        Blade::component($prefix . 'kbd', Kbd::class);
        Blade::component($prefix . 'list-item', ListItem::class);
        Blade::component($prefix . 'loading', Loading::class);
        Blade::component($prefix . 'markdown', Markdown::class);
        Blade::component($prefix . 'modal', Modal::class);
        Blade::component($prefix . 'menu', Menu::class);
        Blade::component($prefix . 'menu-item', MenuItem::class);
        Blade::component($prefix . 'menu-separator', MenuSeparator::class);
        Blade::component($prefix . 'menu-sub', MenuSub::class);
        Blade::component($prefix . 'menu-title', MenuTitle::class);
        Blade::component($prefix . 'main', Main::class);
        Blade::component($prefix . 'nav', Nav::class);
        Blade::component($prefix . 'pagination', Pagination::class);
        Blade::component($prefix . 'password', Password::class);
        Blade::component($prefix . 'pin', Pin::class);
        Blade::component($prefix . 'popover', Popover::class);
        Blade::component($prefix . 'progress', Progress::class);
        Blade::component($prefix . 'progress-radial', ProgressRadial::class);
        Blade::component($prefix . 'radio', Radio::class);
        Blade::component($prefix . 'group', Group::class);
        Blade::component($prefix . 'range', Range::class);
        Blade::component($prefix . 'rating', Rating::class);
        Blade::component($prefix . 'select', Select::class);
        Blade::component($prefix . 'signature', Signature::class);
        Blade::component($prefix . 'spotlight', Spotlight::class);
        Blade::component($prefix . 'stat', Stat::class);
        Blade::component($prefix . 'steps', Steps::class);
        Blade::component($prefix . 'step', Step::class);
        Blade::component($prefix . 'swap', Swap::class);
        Blade::component($prefix . 'table', Table::class);
        Blade::component($prefix . 'tab', Tab::class);
        Blade::component($prefix . 'tabs', Tabs::class);
        Blade::component($prefix . 'tags', Tags::class);
        Blade::component($prefix . 'textarea', Textarea::class);
        Blade::component($prefix . 'timeline-item', TimelineItem::class);
        Blade::component($prefix . 'theme-toggle', ThemeToggle::class);
        Blade::component($prefix . 'toast', Toast::class);
        Blade::component($prefix . 'toggle', Toggle::class);
        Blade::component($prefix . 'carousel', Carousel::class);
        Blade::component($prefix . 'heading', Heading::class);
        Blade::component($prefix . 'subheading', Subheading::class);
        Blade::component($prefix . 'text', Text::class);
        Blade::component($prefix . 'link', Link::class);

		// Livewire Components
		Livewire::component($prefix . 'calendar', Calendar::class);
		Livewire::component('event-modal-content', EventModalContent::class);
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
