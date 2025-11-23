<?php

declare(strict_types=1);
/**
 * Icon
 *
 * This file contains the Icon class for the ArtisanPack UI Livewire UI Components package.
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

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\View\Component;

/**
 * Icon Class
 *
 * Provides functionality for the Icon component.
 *
 * @since 1.0.0
 */
class Icon extends Component
{
    public function __construct(
        public string $name,
        public ?string $id = null,
        public ?string $label = null,
        public string $uuid = '',
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }
    }

    public function icon(): string|Stringable
    {
        $name = Str::of($this->name);

        return $name->contains('.') ? $name->replace('.', '-') : "heroicon-{$this->name}";
    }

    public function labelClasses(): ?string
    {
        // Remove `w-*` and `h-*` classes, because it applies only for icon
        return Str::replaceMatches('/(w-\w*)|(h-\w*)/', '', $this->attributes->get('class') ?? '');
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.icon');
    }
}
