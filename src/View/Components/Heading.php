<?php

/**
 * Heading Component
 *
 * The design-system Heading (#58): a title from the type scale, with the
 * semantic level and the visual size set apart.
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
 * Heading Component Class
 *
 * Renders `.apui-heading` on h1–h6 with data-level and data-size, and the
 * eyebrow part just before it. Every attribute lands on the heading.
 *
 * @since 1.0.0
 */
class Heading extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  int|string  $level  The semantic level, 1–6: renders h1–h6. Emitted as data-level.
     * @param  int|string|null  $size  The visual size, 1–6. Defaults to level. Emitted as data-size.
     * @param  string|null  $eyebrow  A short section label before the heading. An `eyebrow` slot overrides it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int|string $level = '2',
        public int|string|null $size = null,
        public ?string $eyebrow = null,
        public array $classes = [],
    ) {}

    /**
     * A level or size as 1–6, or null when it's out of range.
     *
     * @since 3.0.0
     *
     * @param  int|string|null  $value  The value.
     *
     * @return int|null The step.
     */
    public static function step(int|string|null $value): ?int
    {
        if (null === $value || '' === $value || ! is_numeric($value)) {
            return null;
        }

        $step = (int) $value;

        return $step >= 1 && $step <= 6 ? $step : null;
    }

    /**
     * The semantic level, falling back to 2.
     *
     * @since 3.0.0
     *
     * @return int The level.
     */
    public function levelValue(): int
    {
        return self::step($this->level) ?? 2;
    }

    /**
     * The visual size, falling back to the level.
     *
     * @since 3.0.0
     *
     * @return int The size.
     */
    public function sizeValue(): int
    {
        return self::step($this->size) ?? $this->levelValue();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.heading');
    }
}
