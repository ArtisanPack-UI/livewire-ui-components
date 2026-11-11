<?php

/**
 * Range Component
 *
 * The design-system Range (#53): a slider for one value, or two thumbs for
 * a from–to range, built on native range inputs.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Range Component Class
 *
 * Renders `.apui-range`: the label, the track (fill, a native input
 * type=range per thumb and the drawn thumbs with their value bubbles), the
 * marks, the hint and the error. `class` lands on the root. With one thumb,
 * every other attribute, `wire:model` included, lands on the native input.
 * With dual, `wire:model` binds the [from, to] array through `x-modelable`
 * on the root and the other attributes land on both inputs.
 *
 * @since 1.0.0
 */
class Range extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'range';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  float|int  $min  The lowest value.
     * @param  float|int  $max  The highest value.
     * @param  float|int  $step  The step between values.
     * @param  array<int, float|int>|float|int|null  $value  The value, or [from, to] with dual.
     * @param  bool  $dual  Two thumbs for a from–to range.
     * @param  string  $showValue  When the bubble shows: never, focus or always.
     * @param  string|null  $format  The value template, such as `{value} km`.
     * @param  array<int, array{value: float|int, label: string}>  $marks  Labels under the track.
     * @param  string  $color  The fill and thumb edge.
     * @param  string  $size  Thumb size: sm or md.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the track. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the thumbs.
     * @param  array<string, string>  $thumbLabels  With dual, the names of the start and end thumbs.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int|float $min = 0,
        public int|float $max = 100,
        public int|float $step = 1,
        public int|float|array|null $value = null,
        public bool $dual = false,
        public string $showValue = 'focus',
        public ?string $format = null,
        public array $marks = [],
        public string $color = 'primary',
        public string $size = 'md',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public array $thumbLabels = [],
        public ?string $errorField = null,
        public array $classes = [],
    ) {
        $this->thumbLabels = array_merge(
            ['start' => __('Minimum'), 'end' => __('Maximum')],
            array_filter($thumbLabels, static fn (mixed $text): bool => is_string($text)),
        );
    }

    /**
     * The initial [from, to]: with one thumb, from is min and to the value
     * (or the midpoint, the native default); with dual, the value or
     * [min, max], ordered.
     *
     * @since 3.0.0
     *
     * @return array{0: float|int, 1: float|int} The values.
     */
    public function bounds(): array
    {
        if ($this->dual) {
            $values = is_array($this->value) ? array_values($this->value) : [];
            $from   = is_numeric($values[0] ?? null) ? $values[0] + 0 : $this->min;
            $to     = is_numeric($values[1] ?? null) ? $values[1] + 0 : $this->max;

            return [min($from, $to), max($from, $to)];
        }

        $value = is_numeric($this->value) ? $this->value + 0 : $this->min + ($this->max - $this->min) / 2;

        return [$this->min, $value];
    }

    /**
     * A value's position on the track, as a percentage from 0 to 100.
     *
     * @since 3.0.0
     *
     * @param  float|int  $value  The value.
     *
     * @return float The percentage.
     */
    public function percent(int|float $value): float
    {
        if ($this->max <= $this->min) {
            return 0.0;
        }

        return round(max(0, min(100, ($value - $this->min) / ($this->max - $this->min) * 100)), 4);
    }

    /**
     * A value as it reads: the format with `{value}` replaced, or the number.
     *
     * @since 3.0.0
     *
     * @param  float|int  $value  The value.
     *
     * @return string The text.
     */
    public function formatted(int|float $value): string
    {
        $text = (string) $value;

        return null === $this->format ? $text : str_replace('{value}', $text, $this->format);
    }

    /**
     * The marks as `{ value, label, at }` with string labels and the
     * percentage they sit at.
     *
     * @since 3.0.0
     *
     * @return list<array{value: float|int, label: string, at: float}> The marks.
     */
    public function markList(): array
    {
        $list = [];
        foreach ($this->marks as $mark) {
            if (is_array($mark) && isset($mark['value'], $mark['label']) && is_numeric($mark['value'])) {
                $list[] = ['value' => $mark['value'] + 0, 'label' => (string) $mark['label'], 'at' => $this->percent($mark['value'] + 0)];
            }
        }

        return $list;
    }

    /**
     * With dual, the name both thumbs post into: the `name` attribute plus
     * `[]`, so a plain form submit sends `[from, to]`.
     *
     * @since 3.0.0
     *
     * @return string|null The name, or null with one thumb or no name.
     */
    public function dualName(): ?string
    {
        $name = $this->attributes->get('name');
        if (! $this->dual || ! is_string($name) || '' === $name) {
            return null;
        }

        return str_ends_with($name, '[]') ? $name : $name.'[]';
    }

    /**
     * With dual, the id that names the field in each thumb's
     * aria-labelledby: the visible label, else the caller's
     * aria-labelledby, else the hidden copy of its aria-label.
     *
     * @since 3.0.0
     *
     * @param  string  $controlId  The base id.
     * @param  bool  $hasLabel  Whether the visible label renders.
     *
     * @return string|null The id(s), or null when nothing names the field.
     */
    public function thumbNameId(string $controlId, bool $hasLabel): ?string
    {
        if ($hasLabel) {
            return $controlId.'-label';
        }

        $labelledBy = $this->attributes->get('aria-labelledby');
        if (is_string($labelledBy) && '' !== $labelledBy) {
            return $labelledBy;
        }

        $label = $this->attributes->get('aria-label');

        return is_string($label) && '' !== $label ? $controlId.'-aria-label' : null;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.range');
    }
}
