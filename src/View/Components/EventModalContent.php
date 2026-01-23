<?php

declare(strict_types=1);
/**
 * Event Modal Content Component
 *
 * The default child Livewire component for displaying event details in the calendar modal.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * EventModalContent Class
 *
 * Renders the content for a calendar event modal.
 *
 * @since 1.0.0
 */
class EventModalContent extends Component
{
    /**
     * The current event data.
     *
     * @since 1.0.0
     */
    public ?array $event = null;

    /**
     * Load event data into the modal.
     *
     * @since 1.0.0
     *
     * @param  array  $selectedEvent  The event data to display.
     */
    #[On('loadEventModal')]
    public function loadEvent(array $selectedEvent): void
    {
        $this->event = $selectedEvent;
    }

    /**
     * Render the component.
     *
     * @since 1.0.0
     *
     * @return View The rendered view.
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.event-modal-content');
    }
}
