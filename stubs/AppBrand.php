<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <a href="/" wire:navigate {{ $attributes->merge( [ 'aria-label' => config( 'app.name' ) ] ) }}>
                    <x-artisanpack-icon name="layout-grid" size="lg" color="primary" />
                    <x-artisanpack-text as="span" weight="semibold">{{ config( 'app.name' ) }}</x-artisanpack-text>
                </a>
            HTML;
    }
}
