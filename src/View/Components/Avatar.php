<?php

/**
 * Avatar Component
 *
 * The design-system Avatar (#61): a person or organization as an image,
 * initials or an icon.
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

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Avatar Component Class
 *
 * Renders `.apui-avatar`, role=img named after the person, with the image,
 * initials and status parts. The image sits over the initials, so a broken
 * image shows them. Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Avatar extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The colors a name hashes to, in order. Neutral is for avatars without a name.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const HASH_COLORS = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info'];

    /**
     * The semantic colors an avatar accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'neutral', 'success', 'warning', 'error', 'info'];

    /**
     * The sizes an avatar accepts.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];

    /**
     * The presence states.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const STATUSES = ['online', 'away', 'offline'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The full name: initials, accessible name and hashed color.
     * @param  string|null  $src  The image URL (relative, http, https or data:image).
     * @param  string|null  $alt  The accessible name, when it should differ from name.
     * @param  string|null  $initials  Overrides the initials taken from name.
     * @param  string|null  $icon  Icon name shown instead of initials.
     * @param  string  $size  xs, sm, md, lg or xl. Emitted as data-size.
     * @param  string  $shape  circle or square. Emitted as data-shape.
     * @param  string|null  $color  The initials' tone; defaults to a hash of name. Emitted as data-color.
     * @param  string|null  $status  online, away or offline.
     * @param  string|null  $statusLabel  The status in words. Defaults to __( 'Online' ), __( 'Away' ) or __( 'Offline' ).
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $src = null,
        public ?string $alt = null,
        public ?string $initials = null,
        public ?string $icon = null,
        public string $size = 'md',
        public string $shape = 'circle',
        public ?string $color = null,
        public ?string $status = null,
        public ?string $statusLabel = null,
        public array $classes = [],
    ) {}

    /**
     * Up to two letters from a name: the first letters of its first and last
     * words, uppercased.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The name.
     *
     * @return string The initials, or an empty string.
     */
    public static function initialsFor(?string $name): string
    {
        $words = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ([] === $words) {
            return '';
        }

        $first = mb_substr($words[0], 0, 1);
        $last  = count($words) > 1 ? mb_substr($words[count($words) - 1], 0, 1) : '';

        return mb_strtoupper($first.$last);
    }

    /**
     * The color a name hashes to: the sum of its code points over
     * {@see self::HASH_COLORS}, the same in every stack. Neutral without a name.
     *
     * @since 3.0.0
     *
     * @param  string|null  $name  The name.
     *
     * @return string The color.
     */
    public static function colorFor(?string $name): string
    {
        $name = trim((string) $name);
        if ('' === $name) {
            return 'neutral';
        }

        $sum = 0;
        foreach (mb_str_split($name) as $character) {
            $sum += mb_ord($character);
        }

        return self::HASH_COLORS[$sum % count(self::HASH_COLORS)];
    }

    /**
     * The image URL, or null when it's empty or not allowed.
     *
     * @since 3.0.0
     *
     * @return string|null The src.
     */
    public function imageSrc(): ?string
    {
        return SafeUrl::media($this->src ?? $this->attributes?->get('image'));
    }

    /**
     * The name, falling back to the deprecated 2.x `placeholder` attribute.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function nameValue(): string
    {
        $placeholder = $this->attributes?->get('placeholder');

        return trim((string) ($this->name ?? (is_string($placeholder) ? $placeholder : '')));
    }

    /**
     * The letters to show: initials, else those of the name.
     *
     * @since 3.0.0
     *
     * @return string The letters, or an empty string.
     */
    public function initialsValue(): string
    {
        if (null !== $this->initials && '' !== trim($this->initials)) {
            return trim($this->initials);
        }

        return self::initialsFor($this->nameValue());
    }

    /**
     * The icon to show instead of initials: the icon prop, else user when
     * there are no initials, else none.
     *
     * @since 3.0.0
     *
     * @return string|null The icon name.
     */
    public function iconName(): ?string
    {
        if (null !== $this->icon && '' !== $this->icon) {
            return $this->icon;
        }

        return '' === $this->initialsValue() ? 'user' : null;
    }

    /**
     * The color: the color prop when it's one of the eight, else a hash of
     * the name.
     *
     * @since 3.0.0
     *
     * @return string The color.
     */
    public function colorValue(): string
    {
        return in_array($this->color, self::COLORS, true) ? (string) $this->color : self::colorFor($this->nameValue());
    }

    /**
     * The size, falling back to md for unknown values.
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
     * The shape, falling back to circle for unknown values.
     *
     * @since 3.0.0
     *
     * @return string circle or square.
     */
    public function shapeValue(): string
    {
        return 'square' === $this->shape ? 'square' : 'circle';
    }

    /**
     * The status, or null when it's unset or unknown.
     *
     * @since 3.0.0
     *
     * @return string|null The status.
     */
    public function statusValue(): ?string
    {
        return in_array($this->status, self::STATUSES, true) ? $this->status : null;
    }

    /**
     * The accessible name: alt or name, plus the status in words. Empty when
     * the avatar is decorative.
     *
     * @since 3.0.0
     *
     * @return string The label.
     */
    public function accessibleName(): string
    {
        $alt   = $this->alt ?? $this->attributes?->get('alt');
        $label = is_string($alt) && '' !== trim($alt) ? trim($alt) : $this->nameValue();
        if ('' === $label) {
            return '';
        }

        $status = $this->statusValue();
        if (null === $status) {
            return $label;
        }

        $words = null !== $this->statusLabel && '' !== $this->statusLabel
            ? $this->statusLabel
            : match ($status) {
                'online' => __('Online'),
                'away'   => __('Away'),
                default  => __('Offline'),
            };

        return $label.' ('.$words.')';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.avatar');
    }
}
