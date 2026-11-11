<?php

/**
 * Rating Component
 *
 * The design-system Rating (#53): shows a star score, or collects one with
 * native radios.
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
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Rating Component Class
 *
 * Renders `.apui-rating`. Read-only, it is `role="img"` with the value
 * label, filled and half stars, the value and the review count. As an
 * input, it is `role="radiogroup"`: one label per star holding a native
 * radio. `class` lands on the root; every other attribute, `wire:model`
 * included, lands on every radio.
 *
 * @since 1.0.0
 */
class Rating extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use RendersDesignSystemParts;

    /**
     * Not a field: there's no error to show, but the trait reads these.
     *
     * @since 3.0.0
     */
    public ?string $error = null;

    /**
     * The `$errors` key the field trait reads. Unused.
     *
     * @since 3.0.0
     */
    public ?string $errorField = null;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'rating';

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'rating';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'label', 'count', 'valueLabel', 'countLabel'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  float|int|string|null  $value  The score. Livewire binds it with wire:model.
     * @param  int  $max  The number of stars.
     * @param  bool  $readonly  Shows the score instead of collecting it.
     * @param  bool  $disabled  Disables the radios.
     * @param  string  $size  sm, md or lg.
     * @param  int|null  $count  The number of reviews.
     * @param  string|null  $label  The input's accessible name. Null uses the translated "Rating".
     * @param  string|null  $valueLabel  The text alternative, with :value and :max. Null uses the translated default.
     * @param  string|null  $countLabel  The review count text, with :count. Null uses the translated plural.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int|float|string|null $value = null,
        public int $max = 5,
        public bool $readonly = false,
        public bool $disabled = false,
        public string $size = 'md',
        public ?int $count = null,
        public ?string $label = null,
        public ?string $valueLabel = null,
        public ?string $countLabel = null,
        public array $classes = [],
    ) {}

    /**
     * The score as a number, or null without one.
     *
     * @since 3.0.0
     *
     * @return float|null The score.
     */
    public function score(): ?float
    {
        return is_numeric($this->value) ? (float) $this->value : null;
    }

    /**
     * The score as shown: `4.2`, `5`.
     *
     * @since 3.0.0
     *
     * @param  float|int|null  $score  A score, or the current one.
     *
     * @return string The text.
     */
    public function formatted(int|float|null $score = null): string
    {
        $score ??= $this->score() ?? 0;

        return rtrim(rtrim(number_format((float) $score, 1, '.', ''), '0'), '.');
    }

    /**
     * The value label for a score: the template with :value and :max.
     *
     * @since 3.0.0
     *
     * @param  float|int|null  $score  A score, or the current one.
     *
     * @return string The label.
     */
    public function valueText(int|float|null $score = null): string
    {
        $replace = ['value' => $this->formatted($score), 'max' => (string) $this->max];

        if (null === $this->valueLabel || '' === $this->valueLabel) {
            return __(':value out of :max', $replace);
        }

        return str_replace([':value', ':max'], [$replace['value'], $replace['max']], $this->valueLabel);
    }

    /**
     * The review count text, or null without a count.
     *
     * @since 3.0.0
     *
     * @return string|null The text.
     */
    public function countText(): ?string
    {
        if (null === $this->count) {
            return null;
        }

        if (null === $this->countLabel || '' === $this->countLabel) {
            return trans_choice('(:count review)|(:count reviews)', $this->count, ['count' => $this->count]);
        }

        return str_replace(':count', (string) $this->count, $this->countLabel);
    }

    /**
     * The input's accessible name: the label, else the translated "Rating".
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function groupLabel(): string
    {
        return null === $this->label || '' === $this->label ? __('Rating') : $this->label;
    }

    /**
     * How a star shows read-only: filled, half or empty. The score rounds to
     * the nearest half.
     *
     * @since 3.0.0
     *
     * @param  int  $star  The star, from 1.
     *
     * @return string `filled`, `half` or `empty`.
     */
    public function starFill(int $star): string
    {
        $score = round(($this->score() ?? 0) * 2) / 2;

        if ($score >= $star) {
            return 'filled';
        }

        return $score >= $star - 0.5 ? 'half' : 'empty';
    }

    /**
     * Whether a star's radio is the checked one.
     *
     * @since 3.0.0
     *
     * @param  int  $star  The star, from 1.
     *
     * @return bool True when checked.
     */
    public function isChecked(int $star): bool
    {
        $score = $this->score();

        return null !== $score && (float) $star === $score;
    }

    /**
     * The radios' shared name: the `name` attribute, else the root id.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function groupName(): string
    {
        $name = $this->attributes->get('name');

        return is_string($name) && '' !== $name ? $name : $this->controlId();
    }

    /**
     * The control's id: the `id` attribute, else a base id that is stable
     * across Livewire re-renders and unique in the render (numbered when
     * the same group repeats), so repeated groups never share radio names
     * or label targets.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        return $this->baseId();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.rating');
    }
}
