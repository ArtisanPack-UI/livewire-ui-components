<?php

/**
 * Separator Component
 *
 * The 2.x `separator`, now a deprecated alias of the design-system Divider
 * (#58).
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

/**
 * Separator Class
 *
 * Renders the design-system Divider from the 2.x `separator` API:
 * `vertical` is `orientation="vertical"`. The loading bar (`progress`,
 * `target`), `color` and `image` are accepted and ignored: the Divider is
 * a plain rule, and a loading bar is Progress's job.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use `<x-artisanpack-divider>`. Removed in a later major release.
 */
class Separator extends Divider
{
    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $id  Ignored; an `id` attribute still lands on the root.
     * @param  string|null  $target  Ignored.
     * @param  bool  $progress  Ignored.
     * @param  string|null  $color  Ignored.
     * @param  string|null  $image  Ignored.
     * @param  bool  $vertical  Renders a vertical rule.
     * @param  mixed  $label  Text in the middle of the rule.
     * @param  string  $strength  subtle or strong.
     * @param  bool  $decorative  Hides it from assistive technology.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $target = null,
        public bool $progress = false,
        public ?string $color = null,
        public ?string $image = null,
        public bool $vertical = false,
        mixed $label = null,
        string $strength = 'subtle',
        bool $decorative = false,
    ) {
        parent::__construct(
            orientation: $vertical ? 'vertical' : 'horizontal',
            label: $label,
            strength: $strength,
            decorative: $decorative,
        );
    }
}
