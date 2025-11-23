<?php

declare(strict_types=1);
/**
 * Default Event Modal Content Component
 *
 * The default child Livewire component for displaying event details in the calendar modal.
 *
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class EventModalContent extends Component
{
    public ?array $event = null;

    #[On('loadEventModal')]
    public function loadEvent(array $selectedEvent): void
    {
        $this->event = $selectedEvent;
    }

    public function render(): View
    {
        return view('livewire-ui-components::components.event-modal-content');
    }
}
