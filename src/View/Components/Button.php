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
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentSlot;
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

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
     * The resolved color for the button (either from color prop or variant).
     *
     * @var string|null
     */
    public ?string $resolvedColor = null;

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
     * @param string|null $variant        Button variant (for backward compatibility).
     * @param string|null $color          Color variant, Tailwind color, or hex code.
     * @param string|null $colorAdjustment Background adjustment (lighter, darker, transparent, subtle).
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
        public ?string $variant = 'primary',
        public ?string $color = null,
        public ?string $colorAdjustment = null,
        public string $uuid = '',
        public string $tooltipPosition = 'lg:tooltip-top',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
        }

        // Handle color priority: color prop takes precedence over variant
        if ($this->color) {
            // If color is specified, use it instead of variant
            $this->resolvedColor = $this->color;
        } else {
            // If no color specified, use variant for backward compatibility
            $this->variant = $this->validateVariant($this->variant);
            $this->resolvedColor = $this->variant;
        }

        // Set tooltip based on the first non-null value if not already set
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;

        // Set tooltipPosition if not explicitly provided (using default value)
        if ($this->tooltipPosition === 'lg:tooltip-top') {
            $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
        }
    }

    /**
     * Validate and return a supported variant.
     *
     * @param string|null $variant
     * @return string
     * @since 1.0.0
     */
    private function validateVariant(?string $variant): string
    {
        $supportedVariants = [
            'primary',
            'secondary',
            'accent',
            'success',
            'warning',
            'error',
            'ghost',
            'outline'
        ];

        return in_array($variant, $supportedVariants) ? $variant : 'primary';
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @return array
     * @since 1.0.0
     */
    public function getColorClasses(): array
    {
        $colorGenerator = new ColorGenerator();
        
        // Use ColorGenerator for new color system
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->resolvedColor, 
            $this->colorAdjustment, 
            'button'
        );
        
        // If ColorGenerator didn't return classes (invalid color), fall back to DaisyUI variants
        if (empty($colorClasses) && $this->resolvedColor) {
            $colorClasses = $this->getFallbackVariantClasses($this->resolvedColor);
        }
        
        return $colorClasses;
    }
    
    /**
     * Get variant-specific CSS classes (legacy method for backward compatibility).
     *
     * @return string
     * @since 1.0.0
     */
    public function getVariantClasses(): string
    {
        // For backward compatibility, return DaisyUI button classes
        return match($this->variant) {
            'primary' => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent' => 'btn-accent',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'error' => 'btn-error',
            'ghost' => 'btn-ghost',
            'outline' => 'btn-outline',
            default => 'btn-primary',
        };
    }
    
    /**
     * Get fallback variant classes for invalid colors.
     *
     * @param string $color
     * @return array
     * @since 1.1.0
     */
    protected function getFallbackVariantClasses(string $color): array
    {
        $variantClass = match($color) {
            'primary' => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent' => 'btn-accent',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'error' => 'btn-error',
            'ghost' => 'btn-ghost',
            'outline' => 'btn-outline',
            default => 'btn-primary',
        };
        
        return ['btn' => $variantClass];
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

    public function render(): View
    {
        return view('livewire-ui-components::components.button');
    }
}
