<?php
/**
 * Button Component
 *
 * A versatile button component that supports various states, icons, tooltips, and more.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage View\Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Button Component Class
 *
 * Provides a customizable button component with support for icons, spinners, tooltips, and more.
 *
 * @since 1.0.0
 */
class Button extends Component
{
    /**
     * Unique identifier for the button instance.
     *
     * @var string
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Default tooltip position.
     *
     * @var string
     * @since 1.0.0
     */
    public string $tooltipPosition = 'lg:tooltip-top';

    /**
     * Constructor for the Button component.
     *
     * @param string|null $id             Optional ID for the button.
     * @param string|null $label          Optional text label for the button.
     * @param string|null $icon           Optional icon to display before the label.
     * @param string|null $iconRight      Optional icon to display after the label.
     * @param string|null $spinner        Optional spinner target for loading states.
     * @param string|null $link           Optional URL to convert the button to a link.
     * @param bool|null   $external       Whether the link should open in a new tab.
     * @param bool|null   $noWireNavigate Disable wire:navigate for links.
     * @param bool|null   $responsive     Whether the button should be responsive.
     * @param string|null $badge          Optional badge text to display.
     * @param string|null $badgeClasses   Optional CSS classes for the badge.
     * @param string|null $tooltip        Optional tooltip text.
     * @param string|null $tooltipLeft    Optional tooltip text (left position).
     * @param string|null $tooltipRight   Optional tooltip text (right position).
     * @param string|null $tooltipBottom  Optional tooltip text (bottom position).
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $spinner = null,
        public ?string $link = null,
        public ?bool $external = false,
        public ?bool $noWireNavigate = false,
        public ?bool $responsive = false,
        public ?string $badge = null,
        public ?string $badgeClasses = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,
    ) {
        $this->uuid = "mary" . md5(serialize($this)) . $id;
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    /**
     * Determines the target for the spinner.
     *
     * If spinner is set to 1, it will use the first wire:click attribute.
     * Otherwise, it returns the spinner value directly.
     *
     * @return string|null The spinner target.
     * @since 1.0.0
     */
    public function spinnerTarget(): ?string
    {
        if ($this->spinner == 1) {
            return $this->attributes->whereStartsWith('wire:click')->first();
        }

        return $this->spinner;
    }

    /**
     * Renders the button component.
     *
     * @return View|Closure|string The rendered component.
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                @if($link)
                    <a href="{!! $link !!}"
                @else
                    <button
                @endif

                    wire:key="{{ $uuid }}"
                    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
                    {{ $attributes->class(['btn', "!inline-flex lg:tooltip $tooltipPosition" => $tooltip]) }}

                    @if($link && $external)
                        target="_blank"
                    @endif

                    @if($link && !$external && !$noWireNavigate)
                        wire:navigate
                    @endif

                    @if($tooltip)
                        data-tip="{{ $tooltip }}"
                    @endif

                    @if($spinner)
                        wire:target="{{ $spinnerTarget() }}"
                        wire:loading.attr="disabled"
                    @endif
                >

                    <!-- SPINNER LEFT -->
                    @if($spinner && !$iconRight)
                        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
                    @endif

                    <!-- ICON -->
                    @if($icon)
                        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                            <x-mary-icon :name="$icon" />
                        </span>
                    @endif

                    <!-- LABEL / SLOT -->
                    @if($label)
                        <span @class(["hidden lg:block" => $responsive ])>
                            {{ $label }}
                        </span>
                        @if(strlen($badge ?? '') > 0)
                            <span class="badge badge-sm {{ $badgeClasses }}">{{ $badge }}</span>
                        @endif
                    @else
                        {{ $slot }}
                    @endif

                    <!-- ICON RIGHT -->
                    @if($iconRight)
                        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                            <x-mary-icon :name="$iconRight" />
                        </span>
                    @endif

                    <!-- SPINNER RIGHT -->
                    @if($spinner && $iconRight)
                        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
                    @endif

                @if(!$link)
                    </button>
                @else
                    </a>
                @endif
            BLADE;
    }
}
