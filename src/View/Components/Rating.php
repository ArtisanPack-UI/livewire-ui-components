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
        public int $total = 5
    ) {
        $this->uuid = "mary" . md5(serialize($this)) . $id;
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
        return <<<'HTML'
                <div class="rating gap-1 {{ $size }}" x-cloak>
                    <!-- NO RATING-->
                    <input
                        type="radio"
                        name="{{ $modelName() }}"
                        value="0"
                        class="rating-hidden hidden"
                        {{ $attributes->whereStartsWith('wire:model') }}
                    />

                    @for ($i = 1; $i <= $total; $i++)
                        <input
                            type="radio"
                            name="{{ $modelName() }}"
                            value="{{ $i }}"
                            {{ $attributes->whereStartsWith('wire:model') }}
                            {{ $attributes->class(["mask mask-star-2"]) }}
                        />
                    @endfor
                </div>
            HTML;
    }
}
