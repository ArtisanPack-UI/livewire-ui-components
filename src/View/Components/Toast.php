<?php

/**
 * Toast Component
 *
 * The design-system Toast (#51), rendered on its own: a static toast with no
 * machine. The toaster renders live ones.
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
 * Toast Component Class
 *
 * Renders `.apui-toast` with the icon, title, description, actions and
 * close-trigger parts, with the attributes `staticToastParts()` gives a
 * standalone toast in every stack. The close button dispatches
 * `open-change` (`{ open: false }`); hiding the toast is the owner's job.
 * Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Toast extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'toast';

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
     * @param  string|null  $title  The message. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text. A `description` slot overrides it.
     * @param  string  $color  Tints the icon. error makes the toast an alert.
     * @param  string  $variant  default, or inverse for a toast that must stand out.
     * @param  string  $surface  overlay or glass.
     * @param  string|null  $icon  An icon name from the registry. Defaults to the color's status icon. The `icon` slot overrides it.
     * @param  string|null  $actions  Buttons under the text. An `actions` slot overrides it.
     * @param  bool  $hideClose  Hides the close button.
     * @param  string|null  $closeLabel  The close button's accessible name. Defaults to "Dismiss notification", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public string $color = 'neutral',
        public string $variant = 'default',
        public string $surface = 'overlay',
        public ?string $icon = null,
        public ?string $actions = null,
        public bool $hideClose = false,
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
     * Flags a bare `<x-artisanpack-toast />`, which 2.x layouts used to
     * render the toaster: it renders nothing and raises a deprecation that
     * points to `<x-artisanpack-toaster />`.
     *
     * @since 3.0.0
     *
     * @param  bool  $hasContent  Whether a title, description, actions or icon (prop or slot) is set.
     *
     * @return bool True when the toast should render nothing.
     */
    public function isLegacyToaster(bool $hasContent): bool
    {
        if ($hasContent) {
            return false;
        }

        trigger_error(
            'A bare <x-artisanpack-toast /> rendered the toaster in 2.x and renders nothing in 3.0. Use <x-artisanpack-toaster /> in your layout.',
            E_USER_DEPRECATED,
        );

        return true;
    }

    /**
     * Zag's toast type for the color: success, warning, error or info.
     *
     * @since 3.0.0
     *
     * @return string The type.
     */
    public function toastType(): string
    {
        return Toasts::type($this->color);
    }

    /**
     * The close button's accessible name: `closeLabel`, else "Dismiss
     * notification", translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function closeTriggerLabel(): string
    {
        return null !== $this->closeLabel && '' !== $this->closeLabel ? $this->closeLabel : __('Dismiss notification');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.toast');
    }
}
