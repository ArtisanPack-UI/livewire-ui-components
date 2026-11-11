<?php

/**
 * StepsContext
 *
 * Holds the Steps whose step children are rendering.
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

use ArtisanPack\LivewireUiComponents\View\Components\Steps;

/**
 * StepsContext Class
 *
 * A scoped (per-request, Octane-safe) stack of the Steps being rendered, so
 * each `<x-artisanpack-step>` finds its parent's value, state labels and
 * classes, and its own number.
 *
 * @since 3.0.0
 */
final class StepsContext
{
    /**
     * The Steps being rendered, innermost last.
     *
     * @since 3.0.0
     *
     * @var list<Steps>
     */
    private array $stack = [];

    /**
     * Enters a Steps: its children render next.
     *
     * @since 3.0.0
     *
     * @param  Steps  $steps  The Steps.
     */
    public function enter(Steps $steps): void
    {
        $this->stack[] = $steps;
    }

    /**
     * Leaves a Steps once its view has rendered.
     *
     * @since 3.0.0
     *
     * @param  Steps  $steps  The Steps.
     */
    public function leave(Steps $steps): void
    {
        $index = array_search($steps, $this->stack, true);
        if (false !== $index) {
            array_splice($this->stack, $index, 1);
        }
    }

    /**
     * The Steps whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return Steps|null The Steps.
     */
    public function current(): ?Steps
    {
        return [] === $this->stack ? null : $this->stack[array_key_last($this->stack)];
    }
}
