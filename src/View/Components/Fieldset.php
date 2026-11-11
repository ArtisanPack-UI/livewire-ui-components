<?php

/**
 * Fieldset Component
 *
 * The design-system Fieldset (#53): groups related fields under a legend.
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
 * Fieldset Component Class
 *
 * Renders `.apui-fieldset` on a native fieldset, with a legend holding the
 * title and description. Attributes land on the fieldset. Fields side by
 * side go in `<div data-scope="fieldset" data-part="row">`.
 *
 * @since 1.0.0
 */
class Fieldset extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  panel, divided or plain.
     * @param  string|null  $title  The group's name, in the legend. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text in the legend. A `description` slot overrides it.
     * @param  bool  $disabled  Disables every control inside.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $variant = 'panel',
        public ?string $title = null,
        public ?string $description = null,
        public bool $disabled = false,
        public array $classes = [],
    ) {}

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.fieldset');
    }
}
