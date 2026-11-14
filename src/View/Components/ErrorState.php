<?php

/**
 * Error State Component
 *
 * The design-system ErrorState (#60): explains what failed, what's safe and
 * how to recover, in place of content that couldn't load. Not to be confused
 * with ValidationSummary (the 2.x `errors`), which lists form errors.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Error State Component Class
 *
 * Renders `.apui-error-state` (role=alert) with the icon tile or numeral,
 * title, description, code and actions parts. `focusTitle` moves focus to
 * the title when it renders, for full-page errors.
 *
 * @since 3.0.0
 */
class ErrorState extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Each variant's default icon; not-found shows a numeral instead.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const VARIANT_ICONS = [
        'request'   => 'server-crash',
        'offline'   => 'wifi-off',
        'forbidden' => 'lock',
    ];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $variant  request, offline, not-found or forbidden.
     * @param  string  $size  sm or md.
     * @param  string  $align  center or start.
     * @param  string|null  $icon  An icon name; defaults to the variant's. The `icon` slot overrides it.
     * @param  string|null  $numeral  A large status number; not-found defaults to 404.
     * @param  string|null  $title  What failed. A `title` slot overrides it.
     * @param  string|null  $description  What's safe and what to do. A `description` slot or the default slot overrides it.
     * @param  string|null  $code  A reference people can quote to support.
     * @param  string|null  $actions  Recovery buttons. An `actions` slot overrides it.
     * @param  int  $headingLevel  The title's heading level, 1 to 6.
     * @param  bool  $focusTitle  Moves focus to the title when it renders.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $variant = 'request',
        public string $size = 'md',
        public string $align = 'center',
        public ?string $icon = null,
        public ?string $numeral = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $code = null,
        public ?string $actions = null,
        public int $headingLevel = 3,
        public bool $focusTitle = false,
        public array $classes = [],
    ) {}

    /**
     * The numeral to show: `numeral`, else 404 for not-found, else null.
     *
     * @since 3.0.0
     */
    public function numeralText(): ?string
    {
        if (null !== $this->numeral && '' !== $this->numeral) {
            return $this->numeral;
        }

        return 'not-found' === $this->variant ? '404' : null;
    }

    /**
     * The icon to render, or null: an explicit `icon`, else the variant's
     * when there's no numeral.
     *
     * @since 3.0.0
     */
    public function iconName(): ?string
    {
        if (null !== $this->icon && '' !== $this->icon) {
            return $this->icon;
        }

        return null === $this->numeralText() ? (self::VARIANT_ICONS[$this->variant] ?? null) : null;
    }

    /**
     * The title's tag: h1 to h6.
     *
     * @since 3.0.0
     */
    public function headingTag(): string
    {
        return 'h'.min(6, max(1, $this->headingLevel));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.error-state');
    }
}
