<?php

/**
 * Text Component
 *
 * The design-system Text (#58): body copy with a size, tone and weight,
 * truncated to a line or clamped to a number of lines.
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
 * Text Component Class
 *
 * Renders `.apui-text` on a p, span or div with data-size, data-tone and
 * data-weight, plus data-truncate or data-clamp. Every attribute lands on
 * the root.
 *
 * @since 1.0.0
 */
class Text extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The elements Text renders.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const ELEMENTS = ['p', 'span', 'div'];

    /**
     * The sizes Text accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const SIZES = ['xs', 'sm', 'md', 'lg', 'lead'];

    /**
     * The tones Text accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const TONES = ['default', 'muted', 'success', 'warning', 'error'];

    /**
     * The weights Text accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const WEIGHTS = ['regular', 'medium', 'semibold'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $as  The element: p, span or div.
     * @param  string  $size  xs, sm, md, lg or lead. Emitted as data-size.
     * @param  string  $tone  default, muted, success, warning or error. Emitted as data-tone.
     * @param  string  $weight  regular, medium or semibold. Emitted as data-weight.
     * @param  bool  $truncate  Cuts to one line; plain text is copied to title.
     * @param  int|null  $clamp  Cuts after this many lines.
     * @param  array<string, string>  $classes  Extra classes: `root`.
     */
    public function __construct(
        public string $as = 'p',
        public string $size = 'md',
        public string $tone = 'default',
        public string $weight = 'regular',
        public bool $truncate = false,
        public ?int $clamp = null,
        public array $classes = [],
    ) {}

    /**
     * The element, falling back to p.
     *
     * @since 3.0.0
     *
     * @return string The tag.
     */
    public function tag(): string
    {
        return in_array($this->as, self::ELEMENTS, true) ? $this->as : 'p';
    }

    /**
     * The size, falling back to md.
     *
     * @since 3.0.0
     *
     * @return string The size.
     */
    public function sizeValue(): string
    {
        return in_array($this->size, self::SIZES, true) ? $this->size : 'md';
    }

    /**
     * The tone, falling back to default.
     *
     * @since 3.0.0
     *
     * @return string The tone.
     */
    public function toneValue(): string
    {
        return in_array($this->tone, self::TONES, true) ? $this->tone : 'default';
    }

    /**
     * The weight, falling back to regular.
     *
     * @since 3.0.0
     *
     * @return string The weight.
     */
    public function weightValue(): string
    {
        return in_array($this->weight, self::WEIGHTS, true) ? $this->weight : 'regular';
    }

    /**
     * The clamp line count, or null when unset or below 1.
     *
     * @since 3.0.0
     *
     * @return int|null The lines.
     */
    public function clampValue(): ?int
    {
        return null !== $this->clamp && $this->clamp >= 1 ? $this->clamp : null;
    }

    /**
     * The title for cut text: the content when it's plain text, so the full
     * value stays available. Null when nothing is cut, the content has markup
     * or a title is already set.
     *
     * @since 3.0.0
     *
     * @param  string  $html  The rendered content.
     *
     * @return string|null The title.
     */
    public function fullTextTitle(string $html): ?string
    {
        if ((! $this->truncate && null === $this->clampValue()) || $this->attributes?->has('title')) {
            return null;
        }

        $text = trim(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return '' !== $text && strip_tags($html) === $html ? $text : null;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.text');
    }
}
