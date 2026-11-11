<?php

/**
 * Dialog Component
 *
 * The design-system Dialog (#49): a modal window on the behavior engine's
 * dialog machine. Replaces Modal.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Dialog Component Class
 *
 * Renders `apuiDialog` (D-019): an optional trigger, then the backdrop and
 * the positioner holding the `.apui-dialog` panel (the machine's content
 * part) with its header, body and footer. `wire:model` binds the open state
 * through `$wire.entangle()`; without it, `open` is plain Alpine state.
 * `class` lands on the panel; every other attribute lands on the element
 * that holds the Alpine component, where `open-change` is dispatched.
 *
 * @since 3.0.0
 */
class Dialog extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'dialog';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'title', 'description', 'footer', 'trigger', 'closeLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string  $size  The panel's width: sm, md or lg.
     * @param  string  $role  dialog, or alertdialog for confirmations: the backdrop doesn't close it and focus starts on the close button.
     * @param  string  $surface  The panel's surface: overlay or glass.
     * @param  string|null  $color  Tints the header icon.
     * @param  string|null  $icon  An icon name from the registry, before the title. The `icon` slot overrides it.
     * @param  string|null  $title  The heading and the panel's accessible name. A `title` slot overrides it.
     * @param  string|null  $description  Supporting text under the title. A `description` slot overrides it.
     * @param  string|null  $footer  The actions row. A `footer` slot overrides it.
     * @param  string|null  $trigger  Content for a trigger button that opens the dialog. A `trigger` slot overrides it.
     * @param  bool  $modal  Traps focus, locks scroll and hides the rest of the page from assistive technology.
     * @param  bool  $portal  Renders the dialog at the end of the body (x-teleport). Off renders it in place.
     * @param  bool  $hideClose  Hides the close button. Escape still closes the dialog.
     * @param  string|null  $closeLabel  The close button's accessible name. Defaults to "Close", translated.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $open = false,
        public string $size = 'md',
        public string $role = 'dialog',
        public string $surface = 'overlay',
        public ?string $color = null,
        public ?string $icon = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $footer = null,
        public ?string $trigger = null,
        public bool $modal = true,
        public bool $portal = true,
        public bool $hideClose = false,
        public ?string $closeLabel = null,
        public array $classes = [],
    ) {}

    /**
     * The `x-data` expression: `apuiDialog()` with the machine id, the open
     * state, the role and modality.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiDialog({ id: %s, open: %s, role: %s, modal: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            Js::from('alertdialog' === $this->role ? 'alertdialog' : 'dialog')->toHtml(),
            $this->modal ? 'true' : 'false',
        );
    }

    /**
     * The close button's accessible name: `closeLabel`, else "Close",
     * translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function closeTriggerLabel(): string
    {
        return null !== $this->closeLabel && '' !== $this->closeLabel ? $this->closeLabel : __('Close');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.dialog');
    }
}
