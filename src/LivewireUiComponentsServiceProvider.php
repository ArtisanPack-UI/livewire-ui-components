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
	 * @var   array
	 */
	protected array $bladeComponents = [
		'accordion'        => \ArtisanPack\LivewireUiComponents\View\Components\Accordion::class,
		'alert'            => \ArtisanPack\LivewireUiComponents\View\Components\Alert::class,
		'avatar'           => \ArtisanPack\LivewireUiComponents\View\Components\Avatar::class,
		'badge'            => \ArtisanPack\LivewireUiComponents\View\Components\Badge::class,
		'breadcrumbs'      => \ArtisanPack\LivewireUiComponents\View\Components\Breadcrumbs::class,
		'button'           => \ArtisanPack\LivewireUiComponents\View\Components\Button::class,
		'card'             => \ArtisanPack\LivewireUiComponents\View\Components\Card::class,
		'carousel'         => \ArtisanPack\LivewireUiComponents\View\Components\Carousel::class,
		'chart'            => \ArtisanPack\LivewireUiComponents\View\Components\Chart::class,
		'checkbox'         => \ArtisanPack\LivewireUiComponents\View\Components\Checkbox::class,
		'checkbox-group'   => \ArtisanPack\LivewireUiComponents\View\Components\CheckboxGroup::class,
		'choices'          => \ArtisanPack\LivewireUiComponents\View\Components\Choices::class,
		'choices-offline'  => \ArtisanPack\LivewireUiComponents\View\Components\ChoicesOffline::class,
		'code'             => \ArtisanPack\LivewireUiComponents\View\Components\Code::class,
		'collapse'         => \ArtisanPack\LivewireUiComponents\View\Components\Collapse::class,
		'colorpicker'      => \ArtisanPack\LivewireUiComponents\View\Components\Colorpicker::class,
		'datepicker'       => \ArtisanPack\LivewireUiComponents\View\Components\DatePicker::class,
		'datetime'         => \ArtisanPack\LivewireUiComponents\View\Components\DateTime::class,
		'diff'             => \ArtisanPack\LivewireUiComponents\View\Components\Diff::class,
		'drawer'           => \ArtisanPack\LivewireUiComponents\View\Components\Drawer::class,
		'dropdown'         => \ArtisanPack\LivewireUiComponents\View\Components\Dropdown::class,
		'editor'           => \ArtisanPack\LivewireUiComponents\View\Components\Editor::class,
		'errors'           => \ArtisanPack\LivewireUiComponents\View\Components\Errors::class,
		'file'             => \ArtisanPack\LivewireUiComponents\View\Components\File::class,
		'form'             => \ArtisanPack\LivewireUiComponents\View\Components\Form::class,
		'group'            => \ArtisanPack\LivewireUiComponents\View\Components\Group::class,
		'header'           => \ArtisanPack\LivewireUiComponents\View\Components\Header::class,
		'heading'          => \ArtisanPack\LivewireUiComponents\View\Components\Heading::class,
		'icon'             => \ArtisanPack\LivewireUiComponents\View\Components\Icon::class,
		'image-gallery'    => \ArtisanPack\LivewireUiComponents\View\Components\ImageGallery::class,
		'image-library'    => \ArtisanPack\LivewireUiComponents\View\Components\ImageLibrary::class,
		'image-slider'     => \ArtisanPack\LivewireUiComponents\View\Components\ImageSlider::class,
		'input'            => \ArtisanPack\LivewireUiComponents\View\Components\Input::class,
		'kbd'              => \ArtisanPack\LivewireUiComponents\View\Components\Kbd::class,
		'link'             => \ArtisanPack\LivewireUiComponents\View\Components\Link::class,
		'list-item'        => \ArtisanPack\LivewireUiComponents\View\Components\ListItem::class,
		'loading'          => \ArtisanPack\LivewireUiComponents\View\Components\Loading::class,
		'main'             => \ArtisanPack\LivewireUiComponents\View\Components\Main::class,
		'markdown'         => \ArtisanPack\LivewireUiComponents\View\Components\Markdown::class,
		'menu'             => \ArtisanPack\LivewireUiComponents\View\Components\Menu::class,
		'menu-item'        => \ArtisanPack\LivewireUiComponents\View\Components\MenuItem::class,
		'menu-separator'   => \ArtisanPack\LivewireUiComponents\View\Components\MenuSeparator::class,
		'menu-sub'         => \ArtisanPack\LivewireUiComponents\View\Components\MenuSub::class,
		'menu-title'       => \ArtisanPack\LivewireUiComponents\View\Components\MenuTitle::class,
		'modal'            => \ArtisanPack\LivewireUiComponents\View\Components\Modal::class,
		'nav'              => \ArtisanPack\LivewireUiComponents\View\Components\Nav::class,
		'pagination'       => \ArtisanPack\LivewireUiComponents\View\Components\Pagination::class,
		'password'         => \ArtisanPack\LivewireUiComponents\View\Components\Password::class,
		'pin'              => \ArtisanPack\LivewireUiComponents\View\Components\Pin::class,
		'popover'          => \ArtisanPack\LivewireUiComponents\View\Components\Popover::class,
		'profile'          => \ArtisanPack\LivewireUiComponents\View\Components\Profile::class,
		'progress'         => \ArtisanPack\LivewireUiComponents\View\Components\Progress::class,
		'progress-radial'  => \ArtisanPack\LivewireUiComponents\View\Components\ProgressRadial::class,
		'radio'            => \ArtisanPack\LivewireUiComponents\View\Components\Radio::class,
		'radio-group'      => \ArtisanPack\LivewireUiComponents\View\Components\RadioGroup::class,
		'range'            => \ArtisanPack\LivewireUiComponents\View\Components\Range::class,
		'rating'           => \ArtisanPack\LivewireUiComponents\View\Components\Rating::class,
		'select'           => \ArtisanPack\LivewireUiComponents\View\Components\Select::class,
		'select-group'     => \ArtisanPack\LivewireUiComponents\View\Components\SelectGroup::class,
		'separator'        => \ArtisanPack\LivewireUiComponents\View\Components\Separator::class,
		'signature'        => \ArtisanPack\LivewireUiComponents\View\Components\Signature::class,
		'spotlight'        => \ArtisanPack\LivewireUiComponents\View\Components\Spotlight::class,
		'stat'             => \ArtisanPack\LivewireUiComponents\View\Components\Stat::class,
		'step'             => \ArtisanPack\LivewireUiComponents\View\Components\Step::class,
		'steps'            => \ArtisanPack\LivewireUiComponents\View\Components\Steps::class,
		'subheading'       => \ArtisanPack\LivewireUiComponents\View\Components\Subheading::class,
		'swap'             => \ArtisanPack\LivewireUiComponents\View\Components\Swap::class,
		'tab'              => \ArtisanPack\LivewireUiComponents\View\Components\Tab::class,
		'table'            => \ArtisanPack\LivewireUiComponents\View\Components\Table::class,
		'tabs'             => \ArtisanPack\LivewireUiComponents\View\Components\Tabs::class,
		'tags'             => \ArtisanPack\LivewireUiComponents\View\Components\Tags::class,
		'text'             => \ArtisanPack\LivewireUiComponents\View\Components\Text::class,
		'textarea'         => \ArtisanPack\LivewireUiComponents\View\Components\Textarea::class,
		'theme-toggle'     => \ArtisanPack\LivewireUiComponents\View\Components\ThemeToggle::class,
		'timeline-item'    => \ArtisanPack\LivewireUiComponents\View\Components\TimelineItem::class,
		'toast'            => \ArtisanPack\LivewireUiComponents\View\Components\Toast::class,
		'toggle'           => \ArtisanPack\LivewireUiComponents\View\Components\Toggle::class,
	];

	/**
	 * An array of the Livewire components this package provides.
	 *
	 * This array is the single source of truth for all Livewire components.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	protected array $livewireComponents = [
		'calendar'            => \ArtisanPack\LivewireUiComponents\View\Components\Calendar::class,
		'event-modal-content' => \ArtisanPack\LivewireUiComponents\View\Components\EventModalContent::class,
	];

	/**
	 * Perform post-registration booting of services.
	 *
	 * This method is called after all other service providers have been registered,
	 * meaning you have access to all other services that have been registered by the framework.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function boot(): void
	{
		$this->mergeConfiguration();

		if ( $this->app->runningInConsole() ) {
			// Tag the package's config file for our scaffold command to find.
			$this->publishes(
				[
					__DIR__ . '/../config/livewire-ui-components.php' => config_path( 'artisanpack/livewire-ui-components.php' ),
				],
				'artisanpack-package-config'
			);
		}

		$this->loadViewsFrom( __DIR__ . '/../resources/views', 'livewire-ui-components' );
		$this->registerComponents();
		$this->registerBladeDirectives();

		$this->loadRoutesFrom( __DIR__ . '/../routes/web.php' );

		$this->publishes(
			[
				__DIR__ . '/../resources/js'  => public_path( 'vendor/artisanpack-ui/js' ),
				__DIR__ . '/../resources/css' => public_path( 'vendor/artisanpack-ui/css' ),
			],
			'artisanpack-assets'
		);

		// Publishing is only necessary when using the CLI.
		if ( $this->app->runningInConsole() ) {
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
	 * @return void
	 * @since  1.0.0
	 */
	public function registerComponents()
	{
		// Rename <x-icon> from BladeUI Icons to prevent collision.
		Blade::component( 'BladeUI\Icons\Components\Icon', 'svg' );

		// Get the user-defined prefix from the config, defaulting to 'artisanpack'.
		$prefix = config( 'artisanpack.livewire-ui-components.prefix', 'artisanpack' );

		// Register all Blade components.
		foreach ( $this->bladeComponents as $alias => $class ) {
			// Build the component name with prefix (e.g., 'artisanpack-button').
			// If prefix is empty, just use the alias.
			$newName = $prefix ? $prefix . '-' . $alias : $alias;

			// Register the component with the correct name.
			Blade::component( $class, $newName );
		}

		// Register all Livewire components.
		foreach ( $this->livewireComponents as $alias => $class ) {
			$componentName = $prefix ? $prefix . '-' . $alias : $alias;
			Livewire::component( $componentName, $class );
		}
	}

	/**
	 * Register all Blade directives provided by the package.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function registerBladeDirectives(): void
	{
		$this->registerScopeDirective();
	}

	/**
	 * Register the @scope Blade directive for scoped slots.
	 *
	 * @return void
	 * @since  1.0.0
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
			function ( $expression ) {
				// Split the expression by `top-level` commas (not in parentheses).
				$directiveArguments = preg_split( "/,(?![^\(\(]*[\)\)])/", $expression );
				$directiveArguments = array_map( 'trim', $directiveArguments );

				[$name, $functionArguments] = $directiveArguments;

				// Build function "uses" to inject extra external variables.
				$uses = Arr::except( array_flip( $directiveArguments ), [ $name, $functionArguments ] );
				$uses = array_flip( $uses );
				array_push( $uses, '$__env' );
				array_push( $uses, '$__bladeCompiler' );
				$uses = implode( ',', $uses );

				/**
				 * Slot names can`t contains dot , eg: `user.city`.
				 * So we convert `user.city` to `user___city`
				 *
				 * Later, on component it will be replaced back.
				 */
				$name = str_replace( '.', '___', $name );

				return "<?php \$__bladeCompiler = \$__bladeCompiler ?? null; \$loop = null; \$__env->slot({$name}, function({$functionArguments}) use ({$uses}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
			}
		);

		Blade::directive(
			'endscope',
			function () {
				return '<?php }); ?>';
			}
		);
	}

	/**
	 * Register any package services.
	 *
	 * This method is called before the boot method and registers
	 * the package's services in the service container.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function register(): void
	{
		$this->mergeConfigFrom( __DIR__ . '/../config/livewire-ui-components.php', 'artisanpack-livewire-ui-components-temp' );

		// Register the service the package provides.
		$this->app->singleton(
			'livewire-ui-components',
			function ( $app ) {
				return new LivewireUiComponents();
			}
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array The array of provided services.
	 * @since  1.0.0
	 */
	public function provides()
	{
		return [ 'livewire-ui-components' ];
	}

	/**
	 * Console-specific booting.
	 *
	 * This method is called when the application is running in the console
	 * and handles publishing assets and registering commands.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	protected function bootForConsole(): void
	{
		// Publishing the configuration file.
		$this->publishes(
			[
				__DIR__ . '/../config/livewire-ui-components.php' => config_path( 'livewire-ui-components.php' ),
			],
			'livewire-ui-components.config'
		);

		$this->commands( [ LivewireUiComponentsInstallCommand::class,
							 LivewireUiComponentsBootcampCommand::class,
							 GenerateThemeCss::class,
							 GenerateIdeHelperCommand::class,
							 ] );
	}

	/**
	 * Merges the package's default configuration with the user's customizations.
	 *
	 * This method ensures that the user's settings in `config/artisanpack.php`
	 * take precedence over the package's default values.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	protected function mergeConfiguration(): void
	{
		// Get the package's default configuration.
		$packageDefaults = config( 'artisanpack-livewire-ui-components-temp', [] );

		// Get the user's custom configuration from config/artisanpack.php.
		$userConfig = config( 'artisanpack.livewire-ui-components', [] );

		// Merge them, with the user's config overwriting the defaults.
		$mergedConfig = array_replace_recursive( $packageDefaults, $userConfig );

		// Set the final, correctly merged configuration.
		config( [ 'artisanpack.livewire-ui-components' => $mergedConfig ] );
	}
}