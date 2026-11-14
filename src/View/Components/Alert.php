<?php

/**
 * Alert Component
 *
 * The design-system Alert (#60): an inline message about the state of a
 * page or section.
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

use ArtisanPack\LivewireUiComponents\Support\Toasts;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Alert Component Class
 *
 * Renders `.apui-alert` with the icon, title, description, actions and
 * close-trigger parts. The root is role=alert for errors and role=status
 * otherwise. A dismissible alert hides itself (the hidden attribute) when its
 * close button is pressed and dispatches `open-change` (`{ open: false }`).
 *
 * @since 1.0.0
 */
class Alert extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'alert';

    /**
     * Props that don't change the id: text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['title', 'description', 'actions', 'closeLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The headline. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text. A `description` slot or the default slot overrides it.
     * @param  string  $color  The status; error makes the alert role=alert.
     * @param  string  $variant  soft, outline or solid.
     * @param  string  $surface  default or glass.
     * @param  string|null  $icon  An icon name from the registry. Defaults to the color's status icon. The `icon` slot overrides it.
     * @param  string|null  $actions  Buttons under the text. An `actions` slot overrides it.
     * @param  bool  $dismissible  Adds a close button that hides the alert.
     * @param  string|null  $closeLabel  The close button's accessible name. Defaults to "Dismiss", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public string $color = 'neutral',
        public string $variant = 'soft',
        public string $surface = 'default',
        public ?string $icon = null,
        public ?string $actions = null,
        public bool $dismissible = false,
        public ?string $closeLabel = null,
        public array $classes = [],
    ) {}

    /**
     * The icon to render: the `icon` prop, else the color's status icon.
     *
     * @since 3.0.0
     *
     * @return string The icon name.
     */
    public function iconName(): string
    {
        return null !== $this->icon && '' !== $this->icon ? $this->icon : Toasts::statusIcon($this->color);
    }

    /**
     * The root's live-region role: alert for errors, status otherwise.
     *
     * @since 3.0.0
     *
     * @return string The role.
     */
    public function role(): string
    {
        return 'error' === $this->color ? 'alert' : 'status';
    }

    /**
     * The close button's accessible name: `closeLabel`, else "Dismiss",
     * translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function closeTriggerLabel(): string
    {
        return null !== $this->closeLabel && '' !== $this->closeLabel ? $this->closeLabel : __('Dismiss');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.alert');
    }
}
