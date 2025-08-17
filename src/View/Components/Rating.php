<?php
/**
 * Rating
 *
 * This file contains the Rating class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
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
 * Rating Class
 *
 * Provides functionality for the Rating component.
 *
 * @since 1.0.0
 */

class Rating extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public int $total = 5,

        // NEW: Icon Props
        public ?string $icon = 'heroicon-s-star',
        public ?string $filledIcon = null,
        public ?string $emptyIcon = null,

        // NEW: Color Props
        public ?string $color = 'warning',
        public ?string $filledColor = null,
        public ?string $emptyColor = 'gray-200',

        // NEW: Additional Props (from documentation)
        public ?string $size = 'md',
        public bool $halfStars = false,
        public bool $hoverEffect = false,
        public bool $showValue = false,
        public ?string $valueFormat = '{value}',
        public bool $clearable = false,
        public ?string $clearIcon = 'heroicon-o-x-circle',
        public bool $inlineLabel = false,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $helper = null,
        public ?string $error = null,
        public ?string $label = null,
        public ?string $name = null,
        public float|int|null $value = 0,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this) . microtime(true) . mt_rand()) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function size(): ?string
    {
        return str($this->attributes->get('class'))->match('/(rating-(..))/');
    }

    /**
     * Resolve the color for filled rating items
     */
    public function resolveFilledColor(): string
    {
        return $this->filledColor ?? $this->color ?? 'warning';
    }

    /**
     * Resolve the color for empty rating items
     */
    public function resolveEmptyColor(): string
    {
        return $this->emptyColor ?? 'gray-200';
    }

    /**
     * Resolve the icon for filled rating items
     */
    public function resolveFilledIcon(): string
    {
        return $this->filledIcon ?? $this->icon ?? 'heroicon-s-star';
    }

    /**
     * Resolve the icon for empty rating items
     */
    public function resolveEmptyIcon(): string
    {
        // If emptyIcon is explicitly set, use it
        if ($this->emptyIcon) {
            return $this->emptyIcon;
        }

        // If icon is set, use it for empty stars too
        if ($this->icon && $this->icon !== 'heroicon-s-star') {
            return $this->icon;
        }

        // Default: use outline star for empty when filled uses solid star
        return 'heroicon-o-star';
    }

    /**
     * Get CSS color class for a given color value
     */
    public function getColorClass(string $color): string
    {
        // Handle hex colors
        if (str_starts_with($color, '#')) {
            return ''; // Custom CSS will be needed via style attribute
        }

        // Handle semantic colors
        if (in_array($color, ['primary', 'secondary', 'accent', 'warning', 'error', 'success', 'info'])) {
            return "text-{$color}";
        }

        // Handle Tailwind colors (assumes format like 'red-500', 'blue-300')
        return "text-{$color}";
    }

    /**
     * Get inline style for custom hex colors
     */
    public function getColorStyle(string $color): string
    {
        if (str_starts_with($color, '#')) {
            return "color: {$color};";
        }

        return '';
    }

    /**
     * Get the size classes for the rating
     */
    public function getSizeClasses(): string
    {
        return match($this->size) {
            'sm' => 'rating-sm',
            'md' => 'rating-md',
            'lg' => 'rating-lg',
            'xl' => 'rating-xl',
            default => 'rating-md'
        };
    }

    /**
     * Get formatted value for display
     */
    public function getFormattedValue(): string
    {
        if (!$this->showValue) {
            return '';
        }

        $format = $this->valueFormat ?? '{value}';
        $currentValue = $this->value ?? 0;

        return str_replace(
            ['{value}', '{max}'],
            [$currentValue, $this->total],
            $format
        );
    }

    /**
     * Determine if a star should be filled, half-filled, or empty
     */
    public function getStarState(int $position): string
    {
        $currentValue = $this->value ?? 0;

        if (!$this->halfStars) {
            return $currentValue >= $position ? 'filled' : 'empty';
        }

        if ($currentValue >= $position) {
            return 'filled';
        } elseif ($currentValue >= ($position - 0.5)) {
            return 'half';
        } else {
            return 'empty';
        }
    }

    /**
     * Get the icon for a specific star position
     */
    public function getStarIcon(int $position): string
    {
        $state = $this->getStarState($position);

        return match($state) {
            'filled' => $this->resolveFilledIcon(),
            'half' => $this->resolveHalfIcon(),
            'empty' => $this->resolveEmptyIcon(),
        };
    }

    /**
     * Get the color class for a specific star position
     */
    public function getStarColorClass(int $position): string
    {
        $state = $this->getStarState($position);

        return match($state) {
            'filled', 'half' => $this->getColorClass($this->resolveFilledColor()),
            'empty' => $this->getColorClass($this->resolveEmptyColor()),
        };
    }

    /**
     * Get the color style for a specific star position
     */
    public function getStarColorStyle(int $position): string
    {
        $state = $this->getStarState($position);

        return match($state) {
            'filled', 'half' => $this->getColorStyle($this->resolveFilledColor()),
            'empty' => $this->getColorStyle($this->resolveEmptyColor()),
        };
    }

    /**
     * Resolve the icon for half-filled stars
     */
    public function resolveHalfIcon(): string
    {
        // Try to find a half version of the icon
        $baseIcon = $this->resolveFilledIcon();

        // Convert solid icons to half versions if available
        if (str_contains($baseIcon, 'heroicon-s-star')) {
            return 'heroicon-s-star'; // Use filled star for half (can be styled with CSS)
        }

        // Fallback to filled icon
        return $baseIcon;
    }

    /**
     * Check if the current value has a fractional part
     */
    public function hasHalfValue(): bool
    {
        $currentValue = $this->value ?? 0;
        return ($currentValue - floor($currentValue)) >= 0.5;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.rating');
    }
}
