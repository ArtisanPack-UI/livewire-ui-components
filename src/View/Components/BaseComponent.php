<?php

declare(strict_types=1);
/**
 * BaseComponent
 *
 * Shared base class for all Blade components in this package. Provides
 * cross-cutting hook seams so packages and applications can extend
 * rendered classes and attributes without editing individual components.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      2.1.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

/**
 * BaseComponent Class
 *
 * All Blade components in this package should extend this class instead
 * of extending {@see \Illuminate\View\Component} directly so they pick up
 * the shared `ap.livewireUiComponents.componentClasses` and
 * `ap.livewireUiComponents.componentAttributes` hook seams.
 *
 * @since 2.1.0
 */
abstract class BaseComponent extends Component
{
    /**
     * Cached short component names keyed by fully-qualified class.
     *
     * @var array<class-string, string>
     */
    private static array $shortNameCache = [];

    /**
     * Merge the component's attribute bag with the provided attributes.
     *
     * Overridden to fire the `ap.livewireUiComponents.componentAttributes`
     * filter after Blade has assembled the final attribute bag. Runs on
     * every component render; the filter itself short-circuits cheaply
     * when no callbacks are registered.
     *
     * @since 2.1.0
     *
     * @param  array  $attributes  The attributes to merge into the bag.
     *
     * @return $this
     */
    public function withAttributes(array $attributes): static
    {
        parent::withAttributes($attributes);

        $this->attributes = applyFilters(
            'ap.livewireUiComponents.componentAttributes',
            $this->attributes,
            $this->componentName(),
        );

        return $this;
    }

    /**
     * Apply the `ap.livewireUiComponents.componentClasses` filter to a
     * class list.
     *
     * Components should route any class list they build in PHP through
     * this helper so subscribers can add, remove, or reorder classes
     * without editing the component. The filter receives the class list
     * plus the component name and current attribute bag.
     *
     * @since 2.1.0
     *
     * @param  array  $classes  The class list to filter.
     *
     * @return array The filtered class list.
     */
    protected function getClasses(array $classes): array
    {
        return applyFilters(
            'ap.livewireUiComponents.componentClasses',
            $classes,
            $this->componentName(),
            $this->attributes ?? new ComponentAttributeBag,
        );
    }

    /**
     * Get the short component name used as the second argument to
     * cross-cutting hooks (e.g. "Button", "Modal", "Table"). Cached
     * per subclass since the value is immutable per class.
     *
     * @since 2.1.0
     */
    protected function componentName(): string
    {
        return self::$shortNameCache[static::class] ??= class_basename(static::class);
    }
}
