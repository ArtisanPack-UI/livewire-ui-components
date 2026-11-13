<?php

declare(strict_types=1);
/**
 * Swap
 *
 * This file contains the Swap class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * Swap Class
 *
 * The design-system Swap (#65): `label.apui-swap`, a square target whose
 * native checkbox (the control part) swaps the on and off contents. `class`
 * lands on the root; every other attribute, `wire:model` included, lands on
 * the checkbox (D-018), so it binds natively and the CSS swaps on `:checked`
 * with no script. `label` is the checkbox's accessible name.
 *
 * The 2.x API keeps working: the `true` and `false` slots are the `on` and
 * `off` slots, and `true-icon` and `false-icon` are `on-icon` and
 * `off-icon`. The 2.x `icon-size` (Tailwind classes) is ignored: `size`
 * sizes the target and its icon.
 *
 * @since 1.0.0
 */
class Swap extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The sizes the markup contract knows.
     *
     * @since 3.0.0
     */
    public const SIZES = ['sm', 'md', 'lg'];

    /**
     * The transitions the markup contract knows.
     *
     * @since 3.0.0
     */
    public const TRANSITIONS = ['fade', 'rotate', 'none'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  bool  $checked  Whether it is on (or bound with `wire:model`).
     * @param  string|null  $label  The checkbox's accessible name.
     * @param  string  $onIcon  The icon shown when on.
     * @param  string  $offIcon  The icon shown when off.
     * @param  string  $transition  fade, rotate or none.
     * @param  string  $size  sm, md or lg.
     * @param  bool  $disabled  Disables the checkbox.
     * @param  array  $classes  Extra classes per part: root, control, on, off.
     * @param  string|null  $trueIcon  Deprecated: use `on-icon`.
     * @param  string|null  $falseIcon  Deprecated: use `off-icon`.
     */
    public function __construct(
        public bool $checked = false,
        public ?string $label = null,
        public string $onIcon = 'sun',
        public string $offIcon = 'moon',
        public string $transition = 'fade',
        public string $size = 'md',
        public bool $disabled = false,
        public array $classes = [],
        public ?string $trueIcon = null,
        public ?string $falseIcon = null,
    ) {
        if (null !== $this->trueIcon && '' !== $this->trueIcon) {
            $this->onIcon = $this->legacyIcon($this->trueIcon);
        }

        if (null !== $this->falseIcon && '' !== $this->falseIcon) {
            $this->offIcon = $this->legacyIcon($this->falseIcon);
        }

        $this->transition = in_array($this->transition, self::TRANSITIONS, true) ? $this->transition : 'fade';
        $this->size       = in_array($this->size, self::SIZES, true) ? $this->size : 'md';
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     *
     * @return Closure|string|View The view.
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.swap');
    }

    /**
     * A 2.x icon name: the heroicon `o-sun` and `o-moon` defaults map to the
     * registry's `sun` and `moon`; anything else is kept for blade-icons.
     *
     * @param  string  $name  The 2.x name.
     *
     * @return string The name.
     */
    private function legacyIcon(string $name): string
    {
        return match ($name) {
            'o-sun'  => 'sun',
            'o-moon' => 'moon',
            default  => $name,
        };
    }
}
