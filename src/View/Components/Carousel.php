<?php

declare(strict_types=1);
/**
 * Carousel
 *
 * This file contains the Carousel class for the ArtisanPack UI Livewire UI Components package.
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

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Inspired by Penguin UI.
 * Thank you.
 */
/**
 * Carousel Class
 *
 * Provides functionality for the Carousel component.
 *
 * @since 1.0.0
 */
class Carousel extends Component
{
    public string $uuid;

    public function __construct(
        public array $slides,
        public ?string $id = null,
        public ?bool $withoutIndicators = false,
        public ?bool $withoutArrows = false,
        public ?bool $autoplay = false,
        public ?int $interval = 2000,

        // Icon customization props
        public mixed $nextArrow = null,
        public mixed $previousArrow = null,
        public mixed $dots = null,

        // Accessibility props
        public ?string $ariaLabel = null,
        public ?string $ariaLabelledBy = null,
        public ?bool $respectsReducedMotion = true,

        // Slots
        public mixed $content = null,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Renders an icon based on whether it's a name or raw SVG
     *
     * @param  mixed  $icon  The icon content (name or SVG)
     * @param  string  $defaultName  Default icon name to use if $icon is null
     * @param  array  $classes  CSS classes to apply to the icon
     *
     * @return string Rendered icon HTML
     */
    public function renderIcon(mixed $icon, string $defaultName, array $classes = []): string
    {
        $classString = ! empty($classes) ? ' class="'.implode(' ', $classes).'"' : '';

        if (null === $icon) {
            // Use default icon name - skip if empty
            if (empty($defaultName)) {
                return '';
            }

            return Blade::render('<x-artisanpack-icon :name="$name" :class="$class" />', [
                'name'  => $defaultName,
                'class' => implode(' ', $classes),
            ]);
        }

        if ($this->isRawSvg($icon)) {
            // Return raw SVG with additional classes if needed
            return str_replace('<svg', '<svg'.$classString, $icon);
        }

        // Treat as icon name
        return Blade::render('<x-artisanpack-icon :name="$name" :class="$class" />', [
            'name'  => $icon,
            'class' => implode(' ', $classes),
        ]);
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.carousel');
    }

    /**
     * Determines if the provided content is raw SVG or an icon name
     *
     * @param  mixed  $content  The content to check
     *
     * @return bool True if content is raw SVG, false otherwise
     */
    private function isRawSvg(mixed $content): bool
    {
        return is_string($content) &&
               (str_starts_with(trim($content), '<svg') ||
                str_contains($content, '<svg'));
    }
}
