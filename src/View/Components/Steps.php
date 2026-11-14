<?php

/**
 * Steps Component
 *
 * The design-system Steps (#56): progress through a sequence, as an ordered
 * list of step children.
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

use ArtisanPack\LivewireUiComponents\Support\StepsContext;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Throwable;

/**
 * Steps Component Class
 *
 * Renders `.apui-steps` (an `ol`) holding `<x-artisanpack-step>` children,
 * or the same children from `steps`. `value` (or the `wire:model`
 * property) decides which steps are complete, current and upcoming. With
 * `clickable` and `wire:model`, completed steps' titles are buttons that set
 * the property.
 *
 * @since 1.0.0
 */
class Steps extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * How many step children have asked for a number.
     *
     * @since 3.0.0
     */
    private int $stepCount = 0;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, array<string, mixed>>  $steps  The steps as data: `{ title, description, state, icon }`.
     * @param  int|null  $value  The current step, from 1. `wire:model` binds it instead.
     * @param  string  $orientation  horizontal or vertical. Emitted as data-orientation.
     * @param  bool  $clickable  Completed steps' titles become buttons that set the `wire:model` property.
     * @param  string|null  $label  The list's accessible name.
     * @param  string|null  $completeLabel  Hidden text in a complete step's marker. Defaults to __( 'Completed' ).
     * @param  string|null  $errorLabel  Hidden text in an error step's marker. Defaults to __( 'Error' ).
     * @param  array<string, string>  $classes  Extra classes per part, children's parts included.
     */
    public function __construct(
        public array $steps = [],
        public ?int $value = null,
        public string $orientation = 'horizontal',
        public bool $clickable = false,
        public ?string $label = null,
        public ?string $completeLabel = null,
        public ?string $errorLabel = null,
        public array $classes = [],
    ) {
        $this->completeLabel ??= __('Completed');
        $this->errorLabel ??= __('Error');

        app(StepsContext::class)->enter($this);
    }

    /**
     * The Steps whose children are rendering, if any.
     *
     * @since 3.0.0
     *
     * @return self|null The Steps.
     */
    public static function current(): ?self
    {
        return app(StepsContext::class)->current();
    }

    /**
     * Leaves the context once the view has rendered its children.
     *
     * @since 3.0.0
     */
    public function finishRendering(): void
    {
        app(StepsContext::class)->leave($this);
    }

    /**
     * The next step number, for a child without one.
     *
     * @since 3.0.0
     *
     * @return int The number, from 1.
     */
    public function nextNumber(): int
    {
        return ++$this->stepCount;
    }

    /**
     * The `wire:model` name, whatever its modifiers.
     *
     * @since 3.0.0
     *
     * @return string|null The model name.
     */
    public function modelName(): ?string
    {
        $model = $this->attributes->whereStartsWith('wire:model')->first();

        return is_string($model) && '' !== $model ? $model : null;
    }

    /**
     * The current step: the bound Livewire property's value with
     * `wire:model`, else `value`.
     *
     * @since 3.0.0
     *
     * @return int|null The step, from 1.
     */
    public function currentValue(): ?int
    {
        $model = $this->modelName();
        if (null !== $model) {
            try {
                $component = class_exists(Livewire::class) ? Livewire::current() : null;
            } catch (Throwable) {
                $component = null;
            }

            if (is_object($component)) {
                $bound = data_get($component, $model);

                return is_numeric($bound) ? (int) $bound : null;
            }
        }

        return $this->value;
    }

    /**
     * A step's state: its own when it's valid, else from the current step.
     *
     * @since 3.0.0
     *
     * @param  int  $number  The step's number.
     * @param  string|null  $state  The step's own state.
     *
     * @return string complete, current, upcoming or error.
     */
    public function stateFor(int $number, ?string $state): string
    {
        if (in_array($state, ['complete', 'current', 'upcoming', 'error'], true)) {
            return $state;
        }

        $current = $this->currentValue();

        return match (true) {
            null === $current     => 'upcoming',
            $number < $current    => 'complete',
            $number === $current  => 'current',
            default               => 'upcoming',
        };
    }

    /**
     * Whether a completed step's title is a button: clickable with a
     * `wire:model` to set.
     *
     * @since 3.0.0
     *
     * @return bool True when completed steps are buttons.
     */
    public function stepsAreButtons(): bool
    {
        return $this->clickable && null !== $this->modelName();
    }

    /**
     * The root's Alpine data: the bound property, entangled live, when
     * completed steps are buttons.
     *
     * @since 3.0.0
     *
     * @return string|null The `x-data` expression.
     */
    public function alpineData(): ?string
    {
        $model = $this->modelName();
        if (! $this->clickable || null === $model) {
            return null;
        }

        return '{ value: $wire.entangle('.Js::from($model)->toHtml().').live }';
    }

    /**
     * The root's attributes: everything but `wire:model` (bound through
     * Alpine instead) and non-scalar values from 2.x callers.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->whereDoesntStartWith('wire:model')
            ->filter(static fn (mixed $value): bool => is_scalar($value));
    }

    /**
     * The orientation, falling back to horizontal for unknown values.
     *
     * @since 3.0.0
     *
     * @return string horizontal or vertical.
     */
    public function orientationValue(): string
    {
        return 'vertical' === $this->orientation ? 'vertical' : 'horizontal';
    }

    /**
     * The steps data, normalized for the step children: title, description,
     * state, icon and number.
     *
     * @since 3.0.0
     *
     * @return list<array{title: string, description: string|null, state: string|null, icon: string|null, number: int}> The entries.
     */
    public function entries(): array
    {
        $entries = [];
        foreach (array_values(array_filter($this->steps, 'is_array')) as $index => $step) {
            $entries[] = [
                'title'       => (string) ($step['title'] ?? $step['label'] ?? ''),
                'description' => isset($step['description']) ? (string) $step['description'] : null,
                'state'       => isset($step['state']) ? (string) $step['state'] : null,
                'icon'        => isset($step['icon']) ? (string) $step['icon'] : null,
                'number'      => $index + 1,
            ];
        }

        return $entries;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.steps');
    }
}
