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
use ArtisanPack\LivewireUiComponents\Console\Commands\GeneratePresetCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\GenerateThemeCss;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\UpgradeUiCommand;
use ArtisanPack\LivewireUiComponents\Support\DesignSystemAssets;
use ArtisanPack\LivewireUiComponents\Support\ThemeScript;
use ArtisanPack\LivewireUiComponents\Theming\DesignTokens;
use ArtisanPack\LivewireUiComponents\Theming\GlassSurface;
use ArtisanPack\LivewireUiComponents\Theming\PresetGenerator;
use ArtisanPack\LivewireUiComponents\Theming\TokenThemes;
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
     * The prefix every component also registers under, whatever the
     * configured prefix. The package's own views compose components with it
     * (`<x-artisanpack-button>`), so they render under any prefix.
     *
     * @since 3.0.0
     */
    public const INTERNAL_PREFIX = 'artisanpack';

    /**
     * An array of the Blade components this package provides.
     *
     * This array is the single source of truth for all Blade components.
     *
     * @since 1.0.0
     */
    protected array $bladeComponents = [
        'accordion'               => View\Components\Accordion::class,
        'accordion-item'          => View\Components\AccordionItem::class,
        'app-shell'               => View\Components\AppShell::class,
        'alert'                   => View\Components\Alert::class,
        'avatar'                  => View\Components\Avatar::class,
        'avatar-group'            => View\Components\AvatarGroup::class,
        'badge'                   => View\Components\Badge::class,
        'breadcrumbs'             => View\Components\Breadcrumbs::class,
        'breadcrumbs-item'        => View\Components\BreadcrumbsItem::class,
        'button'                  => View\Components\Button::class,
        'calendar'                => View\Components\CalendarGrid::class, // The markup; <livewire:artisanpack-calendar> keeps state.
        'card'                    => View\Components\Card::class,
        'carousel'                => View\Components\Carousel::class,
        'chart'                   => View\Components\Chart::class,
        'checkbox'                => View\Components\Checkbox::class,
        'checkbox-group'          => View\Components\CheckboxGroup::class,
        'choices'                 => View\Components\Choices::class, // Deprecated alias of combobox multiple.
        'choices-offline'         => View\Components\ChoicesOffline::class, // Deprecated alias of combobox multiple.
        'clipboard'               => View\Components\Clipboard::class,
        'code'                    => View\Components\Code::class,
        'code-editor'             => View\Components\CodeEditor::class,
        'collapse'                => View\Components\Collapse::class,
        'combobox'                => View\Components\Combobox::class,
        'color-picker'            => View\Components\ColorPicker::class,
        'colorpicker'             => View\Components\ColorPicker::class, // Deprecated alias of color-picker.
        'date-picker'             => View\Components\DatePicker::class,
        'datepicker'              => View\Components\DatePicker::class, // Deprecated alias of date-picker.
        'datetime'                => View\Components\DateTime::class, // Deprecated: date-picker with time.
        'dialog'                  => View\Components\Dialog::class,
        'diff'                    => View\Components\Diff::class,
        'divider'                 => View\Components\Divider::class,
        'drawer'                  => View\Components\Drawer::class,
        'dropdown'                => View\Components\Dropdown::class, // Deprecated alias of dropdown-menu.
        'dropdown-menu'           => View\Components\DropdownMenu::class,
        'dropdown-menu-group'     => View\Components\DropdownMenuGroup::class,
        'dropdown-menu-item'      => View\Components\DropdownMenuItem::class,
        'dropdown-menu-separator' => View\Components\DropdownMenuSeparator::class,
        'editor'                  => View\Components\RichTextEditor::class, // Deprecated alias of rich-text-editor.
        'empty-state'             => View\Components\EmptyState::class,
        'errors'                  => View\Components\Errors::class, // Deprecated alias of validation-summary.
        'error-state'             => View\Components\ErrorState::class,
        'fieldset'                => View\Components\Fieldset::class,
        'file'                    => View\Components\File::class,
        'form'                    => View\Components\Form::class,
        'group'                   => View\Components\Group::class, // Deprecated alias of radio-group variant="segmented".
        'grid'                    => View\Components\Grid::class,
        'header'                  => View\Components\Header::class, // Deprecated alias of page-header.
        'heading'                 => View\Components\Heading::class,
        'icon'                    => View\Components\Icon::class,
        'image-gallery'           => View\Components\ImageGallery::class,
        'image-library'           => View\Components\ImageLibrary::class,
        'image-slider'            => View\Components\ImageSlider::class,
        'input'                   => View\Components\Input::class,
        'kbd'                     => View\Components\Kbd::class,
        'kpi-card'                => View\Components\KpiCard::class,
        'link'                    => View\Components\Link::class,
        'list'                    => View\Components\ItemList::class,
        'list-item'               => View\Components\ListItem::class,
        'loading'                 => View\Components\Loading::class,
        'main'                    => View\Components\Main::class, // Deprecated alias of app-shell.
        'markdown'                => View\Components\Markdown::class,
        'menu'                    => View\Components\Menu::class,
        'menu-item'               => View\Components\MenuItem::class,
        'menu-separator'          => View\Components\MenuSeparator::class,
        'menu-sub'                => View\Components\MenuSub::class,
        'menu-title'              => View\Components\MenuTitle::class,
        'modal'                   => View\Components\Modal::class, // Deprecated alias of dialog.
        'nav'                     => View\Components\Nav::class, // Deprecated alias of navbar.
        'navbar'                  => View\Components\Navbar::class,
        'navbar-item'             => View\Components\NavbarItem::class,
        'page-header'             => View\Components\PageHeader::class,
        'pagination'              => View\Components\Pagination::class,
        'password'                => View\Components\Password::class,
        'pin'                     => View\Components\Pin::class,
        'popover'                 => View\Components\Popover::class,
        'profile'                 => View\Components\Profile::class, // Deprecated alias of user-menu.
        'progress'                => View\Components\Progress::class,
        'progress-radial'         => View\Components\ProgressRadial::class,
        'radio'                   => View\Components\Radio::class, // Deprecated alias of radio-group.
        'radio-group'             => View\Components\RadioGroup::class,
        'range'                   => View\Components\Range::class,
        'rating'                  => View\Components\Rating::class,
        'rich-text-editor'        => View\Components\RichTextEditor::class,
        'select'                  => View\Components\Select::class,
        'select-group'            => View\Components\SelectGroup::class, // Deprecated alias of select.
        'separator'               => View\Components\Separator::class, // Deprecated alias of divider.
        'signature'               => View\Components\Signature::class,
        'skeleton'                => View\Components\Skeleton::class,
        'sparkline'               => View\Components\Sparkline::class,
        'spotlight'               => View\Components\Spotlight::class,
        'stack'                   => View\Components\Stack::class,
        'stat'                    => View\Components\Stat::class,
        'streamable-content'      => View\Components\StreamableContent::class,
        'step'                    => View\Components\Step::class,
        'steps'                   => View\Components\Steps::class,
        'subheading'              => View\Components\Subheading::class, // Deprecated alias of text size="lead".
        'swap'                    => View\Components\Swap::class,
        'tab'                     => View\Components\Tab::class,
        'table'                   => View\Components\Table::class,
        'tabs'                    => View\Components\Tabs::class,
        'tags'                    => View\Components\Tags::class, // Deprecated alias of combobox multiple allow-create.
        'text'                    => View\Components\Text::class,
        'textarea'                => View\Components\Textarea::class,
        'theme-preview'           => View\Components\ThemePreview::class,
        'theme-toggle'            => View\Components\ThemeToggle::class,
        'timeline'                => View\Components\Timeline::class,
        'timeline-item'           => View\Components\TimelineItem::class,
        'toast'                   => View\Components\Toast::class,
        'toaster'                 => View\Components\Toaster::class,
        'toggle'                  => View\Components\Toggle::class,
        'tooltip'                 => View\Components\Tooltip::class,
        'user-menu'               => View\Components\UserMenu::class,
        'validation-summary'      => View\Components\ValidationSummary::class,
        'widget-grid'             => View\Components\WidgetGrid::class, // Deprecated alias of grid.
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
        $this->resetOverlayIdsPerLivewireRender();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes(
            [
                __DIR__.'/../resources/js'  => public_path('vendor/artisanpack-ui/js'),
                __DIR__.'/../resources/css' => public_path('vendor/artisanpack-ui/css'),
            ],
            'artisanpack-assets',
        );

        // The prebuilt design-system CSS and JS, for apps without a bundler (3.0).
        $this->publishes($this->designSystemAssetPaths(), DesignSystemAssets::PUBLISH_TAG);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register all Blade components provided by the package.
     *
     * Each component registers under the internal `artisanpack-` name, which
     * the package's own views use to compose components, and under the
     * prefix set in the configuration (or no prefix when it's empty). A
     * custom prefix then changes the tags the app writes without breaking
     * the components that render other components.
     *
     * @since 1.0.0
     */
    public function registerComponents(): void
    {
        // Rename <x-icon> from BladeUI Icons to prevent collision.
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        $prefix = static::componentPrefix();

        foreach ($this->bladeComponents as $alias => $class) {
            foreach (static::registeredNames($alias, $prefix) as $name) {
                Blade::component($class, $name);
            }
        }

        foreach ($this->livewireComponents as $alias => $class) {
            foreach (static::registeredNames($alias, $prefix) as $name) {
                Livewire::component($name, $class);
            }
        }
    }

    /**
     * The component prefix from the configuration, normalized: trimmed, with
     * any trailing hyphen removed (`'artisanpack-'` reads as `'artisanpack'`).
     * An empty or null prefix registers the components without one.
     *
     * @since 3.0.0
     *
     * @return string The prefix, or an empty string for none.
     */
    public static function componentPrefix(): string
    {
        $prefix = config('artisanpack.livewire-ui-components.prefix', self::INTERNAL_PREFIX);

        if (! is_string($prefix)) {
            return '';
        }

        return rtrim(trim($prefix), '-');
    }

    /**
     * A component's tag name under a prefix: `artisanpack-button`, or just
     * `button` with an empty prefix.
     *
     * @since 3.0.0
     *
     * @param  string  $alias  The component alias, such as `button`.
     * @param  string|null  $prefix  The prefix. Defaults to the configured one.
     *
     * @return string The tag name, without `x-`.
     */
    public static function componentName(string $alias, ?string $prefix = null): string
    {
        $prefix ??= static::componentPrefix();

        return '' === $prefix ? $alias : $prefix.'-'.$alias;
    }

    /**
     * The names a component registers under: the configured name, then the
     * internal `artisanpack-` name when it differs.
     *
     * @since 3.0.0
     *
     * @param  string  $alias  The component alias, such as `button`.
     * @param  string  $prefix  The normalized prefix.
     *
     * @return list<string> The tag names, without `x-`.
     */
    public static function registeredNames(string $alias, string $prefix): array
    {
        return array_values(array_unique([
            static::componentName($alias, $prefix),
            static::componentName($alias, self::INTERNAL_PREFIX),
        ]));
    }

    /**
     * The Blade components this package provides, keyed by alias.
     *
     * @since 3.0.0
     *
     * @return array<string, class-string> Component classes keyed by alias.
     */
    public function bladeComponents(): array
    {
        return $this->bladeComponents;
    }

    /**
     * The Livewire components this package provides, keyed by alias.
     *
     * @since 3.0.0
     *
     * @return array<string, class-string> Component classes keyed by alias.
     */
    public function livewireComponents(): array
    {
        return $this->livewireComponents;
    }

    /**
     * Register all Blade directives provided by the package.
     *
     * @since 1.0.0
     */
    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
        $this->registerIslandDirectives();
        $this->registerAssetDirectives();
    }

    /**
     * The prebuilt design-system files and where they're published: the
     * `assets.path` config, under the public directory. The manifest goes too,
     * because the directives read the published files' hashes from it.
     *
     * @since 3.0.0
     *
     * @return array<string, string> Package paths keyed to public paths.
     */
    public function designSystemAssetPaths(): array
    {
        $target = public_path(DesignSystemAssets::publicPath());

        return [
            __DIR__.'/../dist/css'           => $target.'/css',
            __DIR__.'/../dist/js'            => $target.'/js',
            __DIR__.'/../dist/manifest.json' => $target.'/manifest.json',
        ];
    }

    /**
     * Register the @artisanpackStyles and @artisanpackScripts Blade directives.
     *
     * They print the tags for the prebuilt design-system assets published with
     * the `artisanpack-design-system-assets` tag, with a content hash on each
     * URL for cache-busting. Put both in `<head>`: the script must run before
     * Livewire's script, which Livewire injects at the end of `<body>`.
     *
     * Usage:
     * - @artisanpackStyles - The stylesheet set in `assets.stylesheet` (`default`)
     * - @artisanpackStyles('headless') - The headless stylesheet, without component CSS
     * - @artisanpackScripts - The behavior engine's Alpine adapter
     *
     * @since 3.0.0
     */
    public function registerAssetDirectives(): void
    {
        Blade::directive(
            'artisanpackStyles',
            function ($expression) {
                return '<?php echo app(\\'.DesignSystemAssets::class."::class)->styles({$expression}); ?>";
            },
        );

        Blade::directive(
            'artisanpackScripts',
            function () {
                return '<?php echo app(\\'.DesignSystemAssets::class.'::class)->scripts(); ?>';
            },
        );

        // @apuiThemeScript in <head>: applies the stored theme before first paint (#65).
        // @apuiThemeScript([ 'storageKey' => 'site-theme', 'default' => 'dark' ]) changes the key and default.
        Blade::directive(
            'apuiThemeScript',
            function ($expression) {
                return '<?php echo \\'.ThemeScript::class.'::tag('.('' !== trim((string) $expression) ? $expression : '[]').'); ?>';
            },
        );
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
     * Register the @island and @endisland Blade directives for Islands architecture.
     *
     * Islands allow isolated updates within complex components. Different parts
     * of a component can update independently without affecting other regions.
     *
     * - Livewire 4+: Full Islands support with optimized partial updates
     * - Livewire 3: Falls back to normal rendering (still works, just less optimized)
     *
     * Usage:
     * - @island('name') ... @endisland - Basic island with wire:key isolation
     * - @island('name', ['defer' => true]) ... @endisland - Deferred loading (Livewire 4+)
     *
     * @since 2.0.0
     */
    public function registerIslandDirectives(): void
    {
        /**
         * @island directive - Creates an isolated region with wire:key.
         *
         * Syntax: @island('island-name') or @island('island-name', ['defer' => true])
         *
         * Island names are HTML-escaped using Blade's e() helper to prevent
         * attribute injection and ensure safe output in wire:key and data-island attributes.
         */
        Blade::directive(
            'island',
            function ($expression) {
                // Parse the expression to get name and options
                $parts   = array_map('trim', explode(',', $expression, 2));
                $options = isset($parts[1]) ? trim($parts[1]) : '[]';

                return "<?php
                    \$__islandName = {$parts[0]};
                    \$__islandNameEscaped = e(\$__islandName);
                    \$__islandOptions = {$options};
                    \$__islandDefer = \$__islandOptions['defer'] ?? false;
                    \$__islandSupportsOptimized = \\ArtisanPack\\LivewireUiComponents\\Support\\LivewireHelper::supportsIslands();
                    \$__islandAttributes = 'wire:key=\"island-' . \$__islandNameEscaped . '\"';
                    if (\$__islandDefer && \$__islandSupportsOptimized) {
                        \$__islandAttributes .= ' wire:intersect.once=\"\$refresh\"';
                    }
                ?><div <?php echo \$__islandAttributes; ?> data-island=\"<?php echo \$__islandNameEscaped; ?>\"<?php if(\$__islandDefer): ?> data-island-defer=\"true\"<?php endif; ?>>";
            },
        );

        /**
         * @endisland directive - Closes an island region.
         */
        Blade::directive(
            'endisland',
            function () {
                return '</div><?php unset($__islandName, $__islandNameEscaped, $__islandOptions, $__islandDefer, $__islandSupportsOptimized, $__islandAttributes); ?>';
            },
        );

        /**
         * @placeholder directive - Shows placeholder content while deferred island loads.
         *
         * Only renders in Livewire 4+ when the island is deferred.
         * In Livewire 3, the placeholder is skipped and content renders immediately.
         *
         * Uses defensive checks for variables to safely handle usage outside @island blocks.
         * The if block opens here and closes in @endplaceholder so the placeholder body
         * is conditionally rendered.
         */
        Blade::directive(
            'placeholder',
            function () {
                return '<?php if (($__islandDefer ?? false) && ($__islandSupportsOptimized ?? false)): ?><template x-if="false">';
            },
        );

        /**
         * @endplaceholder directive - Ends the placeholder region.
         *
         * Closes the template tag and the PHP if block opened by @placeholder.
         * Uses defensive checks for variables to safely handle usage outside @island blocks.
         */
        Blade::directive(
            'endplaceholder',
            function () {
                return '</template><?php endif; ?>';
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
        // Overlay ids rendered so far, per request (Octane resets scoped bindings).
        $this->app->scoped(Support\RenderedIds::class);
        // The Steps whose step children are rendering, per request.
        $this->app->scoped(Support\StepsContext::class);
        // The Breadcrumbs whose composed children are rendering, per request.
        $this->app->scoped(Support\BreadcrumbsContext::class);
        // Tabs, Menu and Navbar parents rendering their children (#56).
        $this->app->scoped(Support\NavigationContext::class);

        $this->mergeConfigFrom(__DIR__.'/../config/livewire-ui-components.php', 'artisanpack-livewire-ui-components-temp');

        // Register the service the package provides.
        $this->app->singleton(
            'livewire-ui-components',
            function ($app) {
                return new LivewireUiComponents;
            },
        );

        // The design tokens and the token-based theming built on them (3.0).
        $this->app->singleton(DesignTokens::class, fn (): DesignTokens => DesignTokens::load());
        $this->app->singleton(PresetGenerator::class);
        $this->app->singleton(TokenThemes::class);
        $this->app->singleton(GlassSurface::class);

        // The prebuilt design-system assets (#42).
        $this->app->singleton(DesignSystemAssets::class);
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
     * Resets a Livewire component's overlay ids when it starts rendering, so
     * a re-render renders the same ids as the first render (D-015).
     *
     * @since 3.0.0
     */
    protected function resetOverlayIdsPerLivewireRender(): void
    {
        if (! function_exists('Livewire\\on')) {
            return;
        }

        \Livewire\on('render', function (object $component): void {
            if (method_exists($component, 'getId')) {
                app(Support\RenderedIds::class)->reset((string) $component->getId());
            }
        });
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
            GenerateThemeCss::class,
            GeneratePresetCommand::class,
            GenerateIdeHelperCommand::class,
            UpgradeUiCommand::class,
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

        // The file the `livewire-ui-components.config` tag publishes,
        // config/livewire-ui-components.php, read as `livewire-ui-components`.
        $publishedConfig = config('livewire-ui-components', []);

        // Get the user's custom configuration from config/artisanpack/livewire-ui-components.php.
        $userConfig = config('artisanpack.livewire-ui-components', []);

        // Merge them: defaults, then the published file, then the artisanpack file.
        $mergedConfig = array_replace_recursive(
            $packageDefaults,
            is_array($publishedConfig) ? $publishedConfig : [],
            is_array($userConfig) ? $userConfig : [],
        );

        // Set the final, correctly merged configuration.
        config(['artisanpack.livewire-ui-components' => $mergedConfig]);
    }
}
