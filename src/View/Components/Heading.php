<?php
/**
 * Heading Component
 *
 * A component for displaying headings with customizable styling options.
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
 * Heading Component Class
 *
 * Provides a customizable heading component with support for different heading levels and styling options.
 *
 * @since 1.0.0
 */
class Heading extends Component
{
    /**
     * Unique identifier for the heading instance.
     *
     * @var string
     * @since 1.0.0
     */
    public string $uuid;

    /**
     * Constructor for the Heading component.
     *
     * @param string|null $id        Optional ID for the heading.
     * @param string|null $level     Heading level (1-6). Defaults to 1 (h1).
     * @param string|null $size      Custom size class. If not provided, defaults based on level.
     * @param string|null $color     Text color class.
     * @param bool|null   $semibold  Whether to use semibold font weight.
     * @param bool|null   $bold      Whether to use bold font weight.
     * @param bool|null   $extrabold Whether to use extra bold font weight.
     * @param bool|null   $center    Whether to center align the heading.
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $level = '1',
        public ?string $size = null,
        public ?string $color = null,
        public ?bool $semibold = false,
        public ?bool $bold = false,
        public ?bool $extrabold = false,
        public ?bool $center = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    /**
     * Get the appropriate size class based on heading level.
     *
     * @return string The size class.
     * @since 1.0.0
     */
    public function sizeClass(): string
    {
        if ($this->size) {
            return $this->size;
        }

        return match ($this->level) {
            '1' => 'text-4xl',
            '2' => 'text-3xl',
            '3' => 'text-2xl',
            '4' => 'text-xl',
            '5' => 'text-lg',
            '6' => 'text-base',
            default => 'text-4xl',
        };
    }

    /**
     * Get the font weight class based on component properties.
     *
     * @return string The font weight class.
     * @since 1.0.0
     */
    public function fontWeightClass(): string
    {
        if ($this->extrabold) {
            return 'font-extrabold';
        }

        if ($this->bold) {
            return 'font-bold';
        }

        if ($this->semibold) {
            return 'font-semibold';
        }

        return 'font-bold'; // Default to bold
    }

    /**
     * Renders the heading component.
     *
     * @return View The rendered component.
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.heading');
    }
}
