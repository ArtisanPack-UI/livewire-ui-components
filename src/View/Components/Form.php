<?php

/**
 * Form Component
 *
 * The design-system Form (#53): a native form that stacks its fields and
 * ends with an actions row.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Form Component Class
 *
 * Renders `.apui-form` on a native form. Attributes (`wire:submit`,
 * `action`, `method`, `aria-label`) land on the form; the `actions` slot
 * renders the button row at the end.
 *
 * @since 1.0.0
 */
class Form extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $actions  The buttons at the end. An `actions` slot overrides it.
     * @param  bool  $divided  Separates the actions row with an edge above it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $actions = null,
        public bool $divided = false,
        public array $classes = [],
    ) {}

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.form');
    }
}
