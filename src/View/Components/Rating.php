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
        public ?string $icon = 's-star',
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
        public ?string $clearIcon = 'o-x-circle',
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


    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.rating');
    }
}
