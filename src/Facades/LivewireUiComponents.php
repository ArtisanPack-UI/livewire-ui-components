<?php
/**
 * ArtisanPack
 *
 * This file contains the ArtisanPack class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\Facades
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * ArtisanPack Class
 *
 * Provides functionality for the ArtisanPack component.
 *
 * @since 1.0.0
 */

class LivewireUiComponents extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @since 1.0.0
     */
    protected static function getFacadeAccessor(): string
    {
        return 'livewireuicomponents';
    }
}
