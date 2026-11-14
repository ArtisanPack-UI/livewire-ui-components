<?php

/**
 * Step Component
 *
 * One step of the design-system Steps (#56).
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
 * Step Component Class
 *
 * Renders a step part (`li`) with its marker, title, connector and
 * description. It reads its parent Steps for the current step, the state
 * labels and `classes`. The default slot overrides the title.
 *
 * @since 1.0.0
 */
class Step extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Extra classes per part: the parent Steps', so `classes` on Steps
     * reaches its steps.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The step's number, from 1.
     *
     * @since 3.0.0
     */
    public int $number;

    /**
     * The Steps this step renders in, if any.
     *
     * @since 3.0.0
     */
    private ?Steps $parent;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $title  The step's name. The default slot overrides it.
     * @param  string|null  $description  Supporting text under the title.
     * @param  string|null  $state  complete, current, upcoming or error. Without it, the parent's value decides.
     * @param  string|null  $icon  An icon name that replaces the marker's number or check.
     * @param  int|null  $step  The step's number. Defaults to its position (2.x API).
     * @param  string|null  $text  The 2.x name for title.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $state = null,
        public ?string $icon = null,
        public ?int $step = null,
        public ?string $text = null,
    ) {
        $this->parent  = Steps::current();
        $position      = $this->parent?->nextNumber() ?? 1;
        $this->number  = $this->step ?? $position;
        $this->classes = $this->parent?->classes ?? [];
    }

    /**
     * The step's state.
     *
     * @since 3.0.0
     *
     * @return string complete, current, upcoming or error.
     */
    public function stepState(): string
    {
        if (null === $this->parent) {
            return in_array($this->state, ['complete', 'current', 'upcoming', 'error'], true) ? $this->state : 'upcoming';
        }

        return $this->parent->stateFor($this->number, $this->state);
    }

    /**
     * The title text: `title`, else the 2.x `text`.
     *
     * @since 3.0.0
     *
     * @return string The title.
     */
    public function titleText(): string
    {
        return (string) ($this->title ?? $this->text ?? '');
    }

    /**
     * Whether the title is a button: a completed step in a clickable Steps
     * bound with `wire:model`.
     *
     * @since 3.0.0
     *
     * @return bool True when it's a button.
     */
    public function isButton(): bool
    {
        return 'complete' === $this->stepState() && true === $this->parent?->stepsAreButtons();
    }

    /**
     * The hidden state text in the marker.
     *
     * @since 3.0.0
     *
     * @return string|null The text, for complete and error steps.
     */
    public function stateLabel(): ?string
    {
        return match ($this->stepState()) {
            'complete' => $this->parent->completeLabel ?? __('Completed'),
            'error'    => $this->parent->errorLabel ?? __('Error'),
            default    => null,
        };
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.step');
    }
}
