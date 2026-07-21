<?php

declare(strict_types=1);
/**
 * Button Component
 *
 * A versatile button component that supports various states, icons, tooltips, and more.
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

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use Illuminate\Contracts\View\View;

/**
 * Button Component Class
 *
 * Provides a customizable button component with support for icons, spinners, tooltips, and more.
 *
 * @since 1.0.0
 */
class Button extends BaseComponent
{
    /**
     * The resolved color for the button (either from color prop or variant).
     *
     * @since 1.0.0
     */
    public ?string $resolvedColor = null;

    /**
     * Constructor for the Button component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $id  Optional. ID for the button. Default null.
     * @param  string|null  $label  Optional. Text label for the button. Default null.
     * @param  string|null  $icon  Optional. Icon to display before the label. Default null.
     * @param  string|null  $iconRight  Optional. Icon to display after the label. Default null.
     * @param  string|null  $loading  Optional. Text or icon to display during loading state (auto-detected). Default null.
     * @param  string|null  $spinner  Optional. Spinner target for loading states. Default null.
     * @param  string|null  $link  Optional. URL to convert the button to a link. Default null.
     * @param  bool|null  $external  Optional. Whether the link should open in a new tab. Default false.
     * @param  bool|null  $noWireNavigate  Optional. Disable wire:navigate for links. Default false.
     * @param  bool|null  $responsive  Optional. Whether the button should be responsive. Default false.
     * @param  string|null  $badge  Optional. Badge text to display. Default null.
     * @param  string|null  $badgeClasses  Optional. CSS classes for the badge. Default null.
     * @param  string|null  $tooltip  Optional. Tooltip text. Default null.
     * @param  string|null  $tooltipLeft  Optional. Tooltip text (left position). Default null.
     * @param  string|null  $tooltipRight  Optional. Tooltip text (right position). Default null.
     * @param  string|null  $tooltipBottom  Optional. Tooltip text (bottom position). Default null.
     * @param  string|null  $variant  Optional. Button variant (for backward compatibility). Default 'primary'.
     * @param  string|null  $color  Optional. Color variant, Tailwind color, or hex code. Default null.
     * @param  string|null  $colorAdjustment  Optional. Background adjustment (lighter, darker, transparent, subtle). Default null.
     * @param  string|null  $size  Optional. Button size (xs, sm, md, lg, xl). Default 'md'.
     * @param  string  $uuid  Optional. UUID for the button. Default ''.
     * @param  string  $tooltipPosition  Optional. Tooltip position. Default 'lg:tooltip-top'.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $loading = null,
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
        public ?string $size = 'md',
        public string $uuid = '',
        public string $tooltipPosition = 'lg:tooltip-top',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }

        // Handle color priority: color prop takes precedence over variant
        if ($this->color) {
            // If color is specified, use it instead of variant
            $this->resolvedColor = $this->color;
        } else {
            // If no color specified, use variant for backward compatibility
            $this->variant       = $this->validateVariant($this->variant);
            $this->resolvedColor = $this->variant;
        }

        // Set tooltip based on the first non-null value if not already set
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;

        // Set tooltipPosition if not explicitly provided (using default value)
        if ('lg:tooltip-top' === $this->tooltipPosition) {
            $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
        }
    }

    /**
     * Get size-specific CSS classes.
     *
     * @since 1.0.0
     */
    public function getSizeClasses(): string
    {
        $validatedSize = $this->validateSize($this->size);

        return match ($validatedSize) {
            'xs'    => 'btn-xs',
            'sm'    => 'btn-sm',
            'md'    => 'btn-md',
            'lg'    => 'btn-lg',
            'xl'    => 'btn-lg', // DaisyUI doesn't have btn-xl, use btn-lg
            default => 'btn-md',
        };
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @since 1.0.0
     */
    public function getColorClasses(): array
    {
        $colorGenerator = new ColorGenerator;

        // Use ColorGenerator for new color system
        $colorClasses = $colorGenerator->resolveComponentColor(
            $this->resolvedColor,
            $this->colorAdjustment,
            'button',
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
     * @since 1.0.0
     */
    public function getVariantClasses(): string
    {
        // For backward compatibility, return DaisyUI button classes
        return match ($this->variant) {
            'primary'   => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent'    => 'btn-accent',
            'success'   => 'btn-success',
            'warning'   => 'btn-warning',
            'error'     => 'btn-error',
            'info'      => 'btn-info',
            'neutral'   => 'btn-neutral',
            'ghost'     => 'btn-ghost',
            'outline'   => 'btn-outline',
            default     => 'btn-primary',
        };
    }

    /**
     * Determines the target for the spinner.
     *
     * If spinner is set to 1, it will use the first wire:click attribute.
     * Otherwise, it returns the spinner value directly.
     *
     * @return string|null The spinner target.
     *
     * @since 1.0.0
     */
    public function spinnerTarget(): ?string
    {
        if ('1' === $this->spinner) {
            return $this->attributes->whereStartsWith('wire:click')->first();
        }

        return $this->spinner;
    }

    /**
     * Determine if the loading value is an icon.
     *
     * Icons are identified by common prefixes: o-, s-, fa-, c-, etc.
     *
     * @since 1.0.0
     *
     * @return bool True if the loading value is an icon, false otherwise.
     */
    public function isLoadingIcon(): bool
    {
        if (! $this->loading) {
            return false;
        }

        // Check if it starts with common icon prefixes
        return 1 === preg_match('/^(o-|s-|fa-|c-|heroicon-|icon-)/', $this->loading);
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.button');
    }

    /**
     * Get fallback variant classes for invalid colors.
     *
     * @since 1.0.0
     */
    protected function getFallbackVariantClasses(string $color): array
    {
        $variantClass = match ($color) {
            'primary'   => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent'    => 'btn-accent',
            'success'   => 'btn-success',
            'warning'   => 'btn-warning',
            'error'     => 'btn-error',
            'info'      => 'btn-info',
            'neutral'   => 'btn-neutral',
            'ghost'     => 'btn-ghost',
            'outline'   => 'btn-outline',
            default     => 'btn-primary',
        };

        return ['btn' => $variantClass];
    }

    /**
     * Validate and return a supported variant.
     *
     * @since 1.0.0
     *
     * @param  string|null  $variant  The variant to validate.
     *
     * @return string The validated variant.
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
            'info',
            'neutral',
            'ghost',
            'outline',
        ];

        return in_array($variant, $supportedVariants) ? $variant : 'primary';
    }

    /**
     * Validate and return a supported size.
     *
     * @since 1.0.0
     *
     * @param  string|null  $size  The size to validate.
     *
     * @return string The validated size.
     */
    private function validateSize(?string $size): string
    {
        $supportedSizes = ['xs', 'sm', 'md', 'lg', 'xl'];

        return in_array($size, $supportedSizes) ? $size : 'md';
    }
}
