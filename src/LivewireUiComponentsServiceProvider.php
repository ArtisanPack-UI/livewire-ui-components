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
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsBootcampCommand;
use ArtisanPack\LivewireUiComponents\Console\Commands\LivewireUiComponentsInstallCommand;
use ArtisanPack\LivewireUiComponents\View\Components;

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
		'accordion'        => Components\Accordion::class,
		'alert'            => Components\Alert::class,
		'avatar'           => Components\Avatar::class,
		'badge'            => Components\Badge::class,
		'breadcrumbs'      => Components\Breadcrumbs::class,
		'button'           => Components\Button::class,
		'card'             => Components\Card::class,
		'carousel'         => Components\Carousel::class,
		'chart'            => Components\Chart::class,
		'checkbox'         => Components\Checkbox::class,
		'checkbox-group'   => Components\CheckboxGroup::class,
		'choices'          => Components\Choices::class,
		'choices-offline'  => Components\ChoicesOffline::class,
		'code'             => Components\Code::class,
		'collapse'         => Components\Collapse::class,
		'colorpicker'      => Components\Colorpicker::class,
		'datepicker'       => Components\DatePicker::class,
		'datetime'         => Components\DateTime::class,
		'diff'             => Components\Diff::class,
		'drawer'           => Components\Drawer::class,
		'dropdown'         => Components\Dropdown::class,
		'editor'           => Components\Editor::class,
		'errors'           => Components\Errors::class,
		'file'             => Components\File::class,
		'form'             => Components\Form::class,
		'group'            => Components\Group::class,
		'header'           => Components\Header::class,
		'heading'          => Components\Heading::class,
		'icon'             => Components\Icon::class,
		'image-gallery'    => Components\ImageGallery::class,
		'image-library'    => Components\ImageLibrary::class,
		'image-slider'     => Components\ImageSlider::class,
		'input'            => Components\Input::class,
		'kbd'              => Components\Kbd::class,
		'link'             => Components\Link::class,
		'list-item'        => Components\ListItem::class,
		'loading'          => Components\Loading::class,
		'main'             => Components\Main::class,
		'markdown'         => Components\Markdown::class,
		'menu'             => Components\Menu::class,
		'menu-item'        => Components\MenuItem::class,
		'menu-separator'   => Components\MenuSeparator::class,
		'menu-sub'         => Components\MenuSub::class,
		'menu-title'       => Components\MenuTitle::class,
		'modal'            => Components\Modal::class,
		'nav'              => Components\Nav::class,
		'pagination'       => Components\Pagination::class,
		'password'         => Components\Password::class,
		'pin'              => Components\Pin::class,
		'popover'          => Components\Popover::class,
		'profile'          => Components\Profile::class,
		'progress'         => Components\Progress::class,
		'progress-radial'  => Components\ProgressRadial::class,
		'radio'            => Components\Radio::class,
		'radio-group'      => Components\RadioGroup::class,
		'range'            => Components\Range::class,
		'rating'           => Components\Rating::class,
		'select'           => Components\Select::class,
		'select-group'     => Components\SelectGroup::class,
		'separator'        => Components\Separator::class,
		'signature'        => Components\Signature::class,
		'spotlight'        => Components\Spotlight::class,
		'stat'             => Components\Stat::class,
		'step'             => Components\Step::class,
		'steps'            => Components\Steps::class,
		'subheading'       => Components\Subheading::class,
		'swap'             => Components\Swap::class,
		'tab'              => Components\Tab::class,
		'table'            => Components\Table::class,
		'tabs'             => Components\Tabs::class,
		'tags'             => Components\Tags::class,
		'text'             => Components\Text::class,
		'textarea'         => Components\Textarea::class,
		'theme-toggle'     => Components\ThemeToggle::class,
		'timeline-item'    => Components\TimelineItem::class,
		'toast'            => Components\Toast::class,
		'toggle'           => Components\Toggle::class,
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
		'calendar'            => Components\Calendar::class,
		'event-modal-content' => Components\EventModalContent::class,
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
			$newName = $prefix . '-' . $alias;
			$oldName = 'artisanpack-artisanpack-' . $alias;

			// 1. Register the new, correct component name (e.g., <x-artisanpack-button>).
			Blade::component( $class, $newName );

			// 2. Register the old, deprecated name for backwards compatibility.
			// This is all that's needed.
			Blade::component( $class, $oldName );

			// REMOVE any `Blade::directive(...)` calls that might be here.
		}

		// Register all Livewire components.
		foreach ( $this->livewireComponents as $alias => $class ) {
			Livewire::component( $prefix . '-' . $alias, $class );
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