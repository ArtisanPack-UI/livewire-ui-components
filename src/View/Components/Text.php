<?php
/**
 * Text Component
 *
 * A component for displaying paragraphs of text with customizable styling options.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage View\Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Text Component Class
 *
 * Provides a customizable text component with support for styling options.
 *
 * @since 1.0.0
 */
class Text extends Component
{
    /**
     * Unique identifier for the text instance.
     *
     * @var string
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Text component.
     *
     * @param string|null $id        Optional ID for the text element.
     * @param string|null $size      Custom size class. Defaults to text-base.
     * @param string|null $color     Text color class.
     * @param bool|null   $semibold  Whether to use semibold font weight.
     * @param bool|null   $bold      Whether to use bold font weight.
     * @param bool|null   $center    Whether to center align the text.
     * @param bool|null   $muted     Whether to use muted text color.
     * @param bool|null   $lead      Whether to style as lead paragraph (larger, more prominent).
     * @param bool|null   $prose     Whether to apply prose styling for rich text content.
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $size = null,
        public ?string $color = null,
        public ?bool $semibold = false,
        public ?bool $bold = false,
        public ?bool $center = false,
        public ?bool $muted = false,
        public ?bool $lead = false,
        public ?bool $prose = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    /**
     * Get the font weight class based on component properties.
     *
     * @return string The font weight class.
     * @since 1.0.0
     */
    public function fontWeightClass(): string
    {
        if ($this->bold) {
            return 'font-bold';
        }

        if ($this->semibold) {
            return 'font-semibold';
        }

        return $this->lead ? 'font-medium' : 'font-normal';
    }

    /**
     * Get the text color class based on component properties.
     *
     * @return string The text color class.
     * @since 1.0.0
     */
    public function colorClass(): string
    {
        if ($this->color) {
            return $this->color;
        }

        return $this->muted ? 'text-base-content/70' : 'text-base-content';
    }

    /**
     * Get the size class based on component properties.
     *
     * @return string The size class.
     * @since 1.0.0
     */
    public function sizeClass(): string
    {
        if ($this->size) {
            return $this->size;
        }

        return $this->lead ? 'text-lg' : 'text-base';
    }

    /**
     * Renders the text component.
     *
     * @return View|Closure|string The rendered component.
     * @since 1.0.0
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
            <p 
                id="{{ $id }}"
                {{ $attributes->class([
                    $sizeClass(),
                    $fontWeightClass(),
                    $colorClass(),
                    'leading-relaxed',
                    'text-center' => $center,
                    'prose prose-base max-w-none' => $prose,
                    'mb-4' => !$attributes->has('class') || !str_contains($attributes->get('class'), 'mb-'),
                ]) }}
            >
                {{ $slot }}
            </p>
        BLADE;
    }
}
