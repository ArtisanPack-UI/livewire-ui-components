<?php
/**
 * Mary
 *
 * This file contains the Mary class for the ArtisanPack UI Livewire UI Components package.
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
 * Mary Class
 *
 * Provides functionality for the Mary component.
 *
 * @since 1.0.0
 */

class LivewireUiComponents extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'livewireuicomponents';
    }
}
