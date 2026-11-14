<?php

declare(strict_types=1);
/**
 * Event Modal Content Component
 *
 * The Calendar's internal child for an event's details (not public API).
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
 * The body of the Livewire Calendar's details Dialog (#64): the event's
 * category as a soft Badge and its description. The Calendar mounts it with
 * the event and its own id, keyed by the event; a 2.x replacement
 * (`event-view`) can still listen for `loadEventModal` instead, which
 * carries `selectedEvent` and the sending `calendarId`.
 *
 * @since 1.0.0
 * @since 3.0.0 Mounted with the event; design-system markup.
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
     * The id of the Calendar this belongs to; null takes any calendar's event.
     *
     * @since 3.0.0
     */
    public ?string $calendarId = null;

    /**
     * Mount the component with the event.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>|null  $event  The event, with when, category, color and description.
     * @param  string|null  $calendarId  The id of the Calendar this belongs to.
     */
    public function mount(?array $event = null, ?string $calendarId = null): void
    {
        $this->event      = $event;
        $this->calendarId = $calendarId;
    }

    /**
     * Load event data into the modal, unless it came from another calendar.
     *
     * @since 1.0.0
     * @since 3.0.0 Takes the sending calendar's id.
     *
     * @param  array  $selectedEvent  The event data to display.
     * @param  string|null  $calendarId  The id of the Calendar that sent it.
     */
    #[On('loadEventModal')]
    public function loadEvent(array $selectedEvent, ?string $calendarId = null): void
    {
        if (null !== $this->calendarId && null !== $calendarId && $calendarId !== $this->calendarId) {
            return;
        }

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
