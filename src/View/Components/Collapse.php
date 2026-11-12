<?php

/**
 * Collapse Component
 *
 * The design-system Collapse (#58): a single disclosure, on the behavior
 * engine's collapsible machine.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * Collapse Component Class
 *
 * Renders `apuiCollapse`: the `.apui-collapse` root holding a trigger and
 * its region. A section puts the trigger first and hides the closed region;
 * a read-more puts the region first, clamped behind a fade while closed,
 * then the trigger. Parts are scoped `collapse` and keep stable ids
 * (D-015). `wire:model` binds `open` through `$wire.entangle()`. The
 * `trigger` slot replaces `label`; the 2.x `heading` and `content` slots
 * still render, deprecated.
 *
 * @since 1.0.0
 */
class Collapse extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'collapse';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'label', 'openLabel'];

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  bool  $open  Whether the region shows. `wire:model` binds it instead.
     * @param  string  $variant  section or read-more.
     * @param  mixed  $label  The trigger's text. Read-more defaults it to "Read more".
     * @param  string|null  $openLabel  The trigger's text while open. Read-more defaults it to "Show less".
     * @param  bool  $disabled  The trigger can't be focused or toggled.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $open = false,
        public string $variant = 'section',
        public mixed $label = null,
        public ?string $openLabel = null,
        public bool $disabled = false,
        public array $classes = [],
    ) {
        $this->variant = 'read-more' === $this->variant ? 'read-more' : 'section';
    }

    /**
     * Whether this is a read-more.
     *
     * @since 3.0.0
     *
     * @return bool True for read-more.
     */
    public function isReadMore(): bool
    {
        return 'read-more' === $this->variant;
    }

    /**
     * The trigger's text while closed: `label`, else "Read more" for a read-more.
     *
     * @since 3.0.0
     *
     * @return mixed The text, or null.
     */
    public function closedLabel(): mixed
    {
        if ($this->filled($this->label)) {
            return $this->label;
        }

        return $this->isReadMore() ? __('Read more') : null;
    }

    /**
     * The trigger's text while open: `openLabel`, else "Show less" for a
     * read-more, else null (the closed text stays).
     *
     * @since 3.0.0
     *
     * @return string|null The text.
     */
    public function expandedLabel(): ?string
    {
        if ($this->filled($this->openLabel)) {
            return $this->openLabel;
        }

        return $this->isReadMore() ? __('Show less') : null;
    }

    /**
     * The `x-data` expression: `apuiCollapse()` with the machine id, the
     * open state (`$wire.entangle()` of the `wire:model` property), the
     * variant and disabled.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiCollapse({ id: %s, open: %s, variant: %s, disabled: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            Js::from($this->variant)->toHtml(),
            $this->disabled ? 'true' : 'false',
        );
    }

    /**
     * The attributes for the root: everything but `wire:model` and `id`.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id']);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.collapse');
    }
}
