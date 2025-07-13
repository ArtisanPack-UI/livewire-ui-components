<?php

namespace ArtisanPackUi\LivewireUiComponents;

use Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use ArtisanPackUi\LivewireUiComponents\Console\Commands\LivewireUiComponentsBootcampCommand;
use ArtisanPackUi\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use ArtisanPackUi\LivewireUiComponents\View\Components\Accordion;
use ArtisanPackUi\LivewireUiComponents\View\Components\Alert;
use ArtisanPackUi\LivewireUiComponents\View\Components\Avatar;
use ArtisanPackUi\LivewireUiComponents\View\Components\Badge;
use ArtisanPackUi\LivewireUiComponents\View\Components\Breadcrumbs;
use ArtisanPackUi\LivewireUiComponents\View\Components\Button;
use ArtisanPackUi\LivewireUiComponents\View\Components\Calendar;
use ArtisanPackUi\LivewireUiComponents\View\Components\Card;
use ArtisanPackUi\LivewireUiComponents\View\Components\Carousel;
use ArtisanPackUi\LivewireUiComponents\View\Components\Chart;
use ArtisanPackUi\LivewireUiComponents\View\Components\Checkbox;
use ArtisanPackUi\LivewireUiComponents\View\Components\Choices;
use ArtisanPackUi\LivewireUiComponents\View\Components\ChoicesOffline;
use ArtisanPackUi\LivewireUiComponents\View\Components\Code;
use ArtisanPackUi\LivewireUiComponents\View\Components\Collapse;
use ArtisanPackUi\LivewireUiComponents\View\Components\Colorpicker;
use ArtisanPackUi\LivewireUiComponents\View\Components\DatePicker;
use ArtisanPackUi\LivewireUiComponents\View\Components\DateTime;
use ArtisanPackUi\LivewireUiComponents\View\Components\Diff;
use ArtisanPackUi\LivewireUiComponents\View\Components\Drawer;
use ArtisanPackUi\LivewireUiComponents\View\Components\Dropdown;
use ArtisanPackUi\LivewireUiComponents\View\Components\Editor;
use ArtisanPackUi\LivewireUiComponents\View\Components\Errors;
use ArtisanPackUi\LivewireUiComponents\View\Components\File;
use ArtisanPackUi\LivewireUiComponents\View\Components\Form;
use ArtisanPackUi\LivewireUiComponents\View\Components\Group;
use ArtisanPackUi\LivewireUiComponents\View\Components\Header;
use ArtisanPackUi\LivewireUiComponents\View\Components\Hr;
use ArtisanPackUi\LivewireUiComponents\View\Components\Icon;
use ArtisanPackUi\LivewireUiComponents\View\Components\ImageGallery;
use ArtisanPackUi\LivewireUiComponents\View\Components\ImageLibrary;
use ArtisanPackUi\LivewireUiComponents\View\Components\Input;
use ArtisanPackUi\LivewireUiComponents\View\Components\Kbd;
use ArtisanPackUi\LivewireUiComponents\View\Components\ListItem;
use ArtisanPackUi\LivewireUiComponents\View\Components\Loading;
use ArtisanPackUi\LivewireUiComponents\View\Components\Main;
use ArtisanPackUi\LivewireUiComponents\View\Components\Markdown;
use ArtisanPackUi\LivewireUiComponents\View\Components\Menu;
use ArtisanPackUi\LivewireUiComponents\View\Components\MenuItem;
use ArtisanPackUi\LivewireUiComponents\View\Components\MenuSeparator;
use ArtisanPackUi\LivewireUiComponents\View\Components\MenuSub;
use ArtisanPackUi\LivewireUiComponents\View\Components\MenuTitle;
use ArtisanPackUi\LivewireUiComponents\View\Components\Modal;
use ArtisanPackUi\LivewireUiComponents\View\Components\Nav;
use ArtisanPackUi\LivewireUiComponents\View\Components\Pagination;
use ArtisanPackUi\LivewireUiComponents\View\Components\Password;
use ArtisanPackUi\LivewireUiComponents\View\Components\Pin;
use ArtisanPackUi\LivewireUiComponents\View\Components\Popover;
use ArtisanPackUi\LivewireUiComponents\View\Components\Progress;
use ArtisanPackUi\LivewireUiComponents\View\Components\ProgressRadial;
use ArtisanPackUi\LivewireUiComponents\View\Components\Radio;
use ArtisanPackUi\LivewireUiComponents\View\Components\Range;
use ArtisanPackUi\LivewireUiComponents\View\Components\Rating;
use ArtisanPackUi\LivewireUiComponents\View\Components\Select;
use ArtisanPackUi\LivewireUiComponents\View\Components\SelectGroup;
use ArtisanPackUi\LivewireUiComponents\View\Components\Signature;
use ArtisanPackUi\LivewireUiComponents\View\Components\Spotlight;
use ArtisanPackUi\LivewireUiComponents\View\Components\Stat;
use ArtisanPackUi\LivewireUiComponents\View\Components\Step;
use ArtisanPackUi\LivewireUiComponents\View\Components\Steps;
use ArtisanPackUi\LivewireUiComponents\View\Components\Swap;
use ArtisanPackUi\LivewireUiComponents\View\Components\Tab;
use ArtisanPackUi\LivewireUiComponents\View\Components\Table;
use ArtisanPackUi\LivewireUiComponents\View\Components\Tabs;
use ArtisanPackUi\LivewireUiComponents\View\Components\Tags;
use ArtisanPackUi\LivewireUiComponents\View\Components\Textarea;
use ArtisanPackUi\LivewireUiComponents\View\Components\ThemeToggle;
use ArtisanPackUi\LivewireUiComponents\View\Components\TimelineItem;
use ArtisanPackUi\LivewireUiComponents\View\Components\Toast;
use ArtisanPackUi\LivewireUiComponents\View\Components\Toggle;

class LivewireUiComponentsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
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

    public function registerComponents()
    {
        // Just rename <x-icon> provided by BladeUI Icons to <x-svg> to not collide with ours
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        // No matter if components has custom prefix or not,
        // we also register below alias to avoid naming collision,
        // because they are used inside some Livewire UI Components' components itself.
        Blade::component('artisanpack-button', Button::class);
        Blade::component('artisanpack-card', Card::class);
        Blade::component('artisanpack-icon', Icon::class);
        Blade::component('artisanpack-input', Input::class);
        Blade::component('artisanpack-list-item', ListItem::class);
        Blade::component('artisanpack-modal', Modal::class);
        Blade::component('artisanpack-menu', Menu::class);
        Blade::component('artisanpack-menu-item', MenuItem::class);
        Blade::component('artisanpack-header', Header::class);
        Blade::component('artisanpack-pagination', Pagination::class);

        $prefix = config('livewire-ui-components.prefix');

        // Blade
        Blade::component($prefix . 'accordion', Accordion::class);
        Blade::component($prefix . 'alert', Alert::class);
        Blade::component($prefix . 'avatar', Avatar::class);
        Blade::component($prefix . 'badge', Badge::class);
        Blade::component($prefix . 'breadcrumbs', Breadcrumbs::class);
        Blade::component($prefix . 'button', Button::class);
        Blade::component($prefix . 'calendar', Calendar::class);
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
    }

    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

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
     * @return array
     */
    public function provides()
    {
        return ['livewire-ui-components'];
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/livewire-ui-components.php' => config_path('livewire-ui-components.php'),
        ], 'livewire-ui-components.config');

        $this->commands([LivewireUiComponentsInstallCommand::class, LivewireUiComponentsBootcampCommand::class]);
    }
}