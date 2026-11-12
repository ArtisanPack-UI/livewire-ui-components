<?php

/**
 * Progress Radial Component
 *
 * The design-system ProgressRadial (#60): circular progress with the value
 * in the center, built from Progress.
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

/**
 * Progress Radial Component Class
 *
 * Renders `.apui-progress-radial`, the progressbar, with an svg ring (track
 * and bar circles) and the value and label in the center.
 *
 * @since 1.0.0
 */
class ProgressRadial extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'progress-radial';

    /**
     * Props that don't change the id.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'label', 'valueText'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  float  $value  The progress, from 0 to max.
     * @param  float  $max  The value at completion.
     * @param  bool  $indeterminate  The amount isn't known.
     * @param  float  $size  The ring's diameter in px.
     * @param  float  $thickness  The ring's stroke width in px.
     * @param  string|null  $color  The bar's color.
     * @param  string|null  $label  A caption under the value that names the progressbar.
     * @param  string|null  $valueText  The value in words (aria-valuetext).
     * @param  bool  $hideValue  Leaves the center empty.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public float $value = 0,
        public float $max = 100,
        public bool $indeterminate = false,
        public float $size = 64,
        public float $thickness = 6,
        public ?string $color = 'primary',
        public ?string $label = null,
        public ?string $valueText = null,
        public bool $hideValue = false,
        public array $classes = [],
    ) {
        // A null (such as a bound optional color) falls back to the default.
        $this->color ??= 'primary';
    }

    /**
     * The upper bound: `max`, or 100 when it isn't positive.
     *
     * @since 3.0.0
     */
    public function maximum(): float
    {
        return $this->max > 0 ? $this->max : 100.0;
    }

    /**
     * The value clamped to 0 to max.
     *
     * @since 3.0.0
     */
    public function clampedValue(): float
    {
        return min(max($this->value, 0.0), $this->maximum());
    }

    /**
     * The value as a whole percentage.
     *
     * @since 3.0.0
     */
    public function percent(): int
    {
        return (int) round($this->clampedValue() / $this->maximum() * 100);
    }

    /**
     * A number as an attribute: integers without a decimal point.
     *
     * @since 3.0.0
     *
     * @param  float  $number  The number.
     */
    public function number(float $number): string
    {
        return floor($number) === $number ? (string) (int) $number : (string) $number;
    }

    /**
     * The ring's geometry, with one decimal: center, radius, circumference
     * and dash offset.
     *
     * @since 3.0.0
     *
     * @return array{center: string, radius: string, circumference: string, offset: string}
     */
    public function geometry(): array
    {
        $radius        = ($this->size - $this->thickness) / 2;
        $circumference = 2 * M_PI * $radius;
        $offset        = $this->indeterminate ? $circumference * 0.75 : $circumference * (1 - $this->percent() / 100);
        $format        = static fn (float $number): string => number_format($number, 1, '.', '');

        return [
            'center'        => $format($this->size / 2),
            'radius'        => $format($radius),
            'circumference' => $format($circumference),
            'offset'        => $format($offset),
        ];
    }

    /**
     * The center's text: `valueText`, else the percentage (empty while
     * indeterminate).
     *
     * @since 3.0.0
     */
    public function valueDisplay(): string
    {
        if (null !== $this->valueText && '' !== $this->valueText) {
            return $this->valueText;
        }

        return $this->indeterminate ? '' : $this->percent().'%';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.progress-radial');
    }
}
