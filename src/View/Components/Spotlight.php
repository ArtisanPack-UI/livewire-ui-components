<?php
/**
 * Spotlight
 *
 * This file contains the Spotlight class for the ArtisanPack UI Livewire UI Components package.
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
 * Spotlight Class
 *
 * Provides functionality for the Spotlight component.
 *
 * @since 1.0.0
 */

class Spotlight extends Component
{

    /**
     * Constructor for the Spotlight component.
     *
     * @param string|null $id            Optional ID for the spotlight.
     * @param string|null $shortcut      Keyboard shortcut to open the spotlight (format: "modifier.key").
     * @param string|null $searchText    Placeholder text for the search input.
     * @param string|null $noResultsText Text to display when no results are found.
     * @param string|null $url           URL to fetch search results from.
     * @param mixed|null  $append        Slot for appending content to the search input.
     * @since 1.0.0
     */
    public function __construct(
        public ?string $id = null,
        public ?string $shortcut = "meta.g",
        public ?string $searchText = "Search ...",
        public ?string $noResultsText = "Nothing found.",
        public ?string $url = null,
        public string $uuid = '',

        // Slots
        public mixed $append = null
    ) {
        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
        }

        // Set url if not provided
        $this->url = $this->url ?? route('artisanpack.spotlight', absolute: false);
    }

    /**
     * Renders the spotlight component.
     *
     * @return View The rendered component.
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.spotlight');
    }
}
