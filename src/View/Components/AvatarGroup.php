<?php

/**
 * AvatarGroup Component
 *
 * A row of overlapping design-system Avatars (#61).
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
 * AvatarGroup Component Class
 *
 * Renders `.apui-avatar-group`, a labelled group of avatars from `avatars`
 * data (first `max`, then a +N avatar) followed by any avatar children.
 *
 * @since 3.0.0
 */
class AvatarGroup extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, array<string, mixed>>  $avatars  Avatars as data: `{ name, src, alt, initials, icon, color, status }`.
     * @param  int|null  $max  How many avatars from the data show before a +N avatar. Null shows them all.
     * @param  string  $size  The data avatars' size: xs, sm, md, lg or xl.
     * @param  string|null  $label  The group's accessible name, such as "Project members".
     * @param  string|null  $moreLabel  The +N avatar's accessible name; {count} is the number hidden. Defaults to __( '{count} more' ).
     * @param  array<string, string>  $classes  Extra classes: `root`.
     */
    public function __construct(
        public array $avatars = [],
        public ?int $max = null,
        public string $size = 'md',
        public ?string $label = null,
        public ?string $moreLabel = null,
        public array $classes = [],
    ) {
        $this->moreLabel ??= __('{count} more');
    }

    /**
     * The avatars from the data that show.
     *
     * @since 3.0.0
     *
     * @return list<array<string, mixed>> The avatars.
     */
    public function shown(): array
    {
        $avatars = array_values(array_filter($this->avatars, 'is_array'));

        return null !== $this->max && $this->max >= 0 ? array_slice($avatars, 0, $this->max) : $avatars;
    }

    /**
     * How many avatars from the data are hidden behind the +N avatar.
     *
     * @since 3.0.0
     *
     * @return int The count.
     */
    public function hiddenCount(): int
    {
        return count(array_filter($this->avatars, 'is_array')) - count($this->shown());
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.avatar-group');
    }
}
