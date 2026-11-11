<?php

/**
 * Navigation Context
 *
 * The parents rendering their children: Tabs for its tabs, Menu and MenuSub
 * for their items, Navbar for its links.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * Navigation Context Class
 *
 * A request-scoped stack of the navigation parents whose slots are
 * rendering (#56). A parent enters when it's constructed, before Blade
 * renders its slot, and leaves once its own view has rendered, so a child
 * finds the nearest parent of the kind it belongs to. Request-scoped, so a
 * render that throws can't leak into the next request (Octane included).
 *
 * @since 3.0.0
 */
final class NavigationContext
{
    /**
     * The parents, outermost first.
     *
     * @since 3.0.0
     *
     * @var list<object>
     */
    private array $stack = [];

    /**
     * Enters a parent.
     *
     * @since 3.0.0
     *
     * @param  object  $parent  The parent component.
     */
    public function enter(object $parent): void
    {
        $this->stack[] = $parent;
    }

    /**
     * Leaves a parent once its view has rendered.
     *
     * @since 3.0.0
     *
     * @param  object  $parent  The parent component.
     */
    public function leave(object $parent): void
    {
        $index = array_search($parent, $this->stack, true);
        if (false !== $index) {
            array_splice($this->stack, $index, 1);
        }
    }

    /**
     * The nearest rendering parent of a class.
     *
     * @since 3.0.0
     *
     * @template T of object
     *
     * @param  class-string<T>  $class  The parent's class.
     *
     * @return T|null The parent.
     */
    public function nearest(string $class): ?object
    {
        for ($index = count($this->stack) - 1; $index >= 0; $index--) {
            if ($this->stack[$index] instanceof $class) {
                return $this->stack[$index];
            }
        }

        return null;
    }
}
