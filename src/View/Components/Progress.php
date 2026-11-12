<?php

/**
 * Progress Component
 *
 * The design-system Progress (#60): linear progress, determinate,
 * indeterminate or segmented.
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
 * Progress Component Class
 *
 * Renders `.apui-progress`, which is the progressbar (aria-valuemin,
 * aria-valuemax and aria-valuenow, left out while indeterminate), with the
 * head (label and value) and the track with its bar, or one bar per segment.
 *
 * @since 1.0.0
 */
class Progress extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'progress';

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
     * @param  float  $max  The value at completion; with segmented, the number of segments.
     * @param  bool  $indeterminate  The amount isn't known.
     * @param  bool  $segmented  One bar per segment.
     * @param  string|null  $size  sm, md or lg.
     * @param  string|null  $color  The bar's color.
     * @param  string|null  $label  What's progressing; names the progressbar.
     * @param  string|null  $valueText  The value in words (aria-valuetext).
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public float $value = 0,
        public float $max = 100,
        public bool $indeterminate = false,
        public bool $segmented = false,
        public ?string $size = 'md',
        public ?string $color = 'primary',
        public ?string $label = null,
        public ?string $valueText = null,
        public array $classes = [],
    ) {
        // A null (such as a bound optional color) falls back to the default.
        $this->size ??= 'md';
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
     * The value part's text, or null when it doesn't render.
     *
     * @since 3.0.0
     */
    public function valueDisplay(): ?string
    {
        if (null !== $this->valueText && '' !== $this->valueText) {
            return $this->valueText;
        }

        return null !== $this->label && '' !== $this->label && ! $this->indeterminate ? $this->percent().'%' : null;
    }

    /**
     * The number of segments, and how many are done.
     *
     * @since 3.0.0
     *
     * @return array{0: int, 1: int}
     */
    public function segments(): array
    {
        $count = max(1, (int) round($this->maximum()));

        return [$count, min($count, (int) round($this->clampedValue()))];
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.progress');
    }
}
