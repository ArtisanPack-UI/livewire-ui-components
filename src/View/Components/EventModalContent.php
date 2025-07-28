<?php
/**
 * Default Event Modal Content Component
 *
 * The default child Livewire component for displaying event details in the calendar modal.
 *
 * @package    ArtisanPack\LivewireUiComponents
 * @subpackage ArtisanPack\LivewireUiComponents\View\Components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\View\View;

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