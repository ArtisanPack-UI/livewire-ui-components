---
title: Event Modal Content Component
---

# Event Modal Content Component

The Event Modal Content component is a Livewire component that provides the default content display for the calendar event modal. It's used by the Calendar Enhanced component to show event details when an event is clicked.

## Overview

This component is automatically used by the [Calendar Enhanced](Calendar-Enhanced) component as the default child component for displaying event details in a modal. You can extend or replace it with your own custom implementation.

## Basic Usage

The Event Modal Content component is typically used internally by the Calendar Enhanced component:

```php
<x-artisanpack-calendar-enhanced
    :events="$events"
/>
```

When you click an event, the Event Modal Content component will automatically display the event details.

## Custom Implementation

You can create your own event modal content by extending this component or creating a completely custom one:

### Extending the Component

Create your own Livewire component:

```php
<?php

namespace App\Livewire;

use ArtisanPack\LivewireUiComponents\View\Components\EventModalContent;

class CustomEventModalContent extends EventModalContent
{
    public function render()
    {
        return view('livewire.custom-event-modal-content');
    }
}
```

### Creating a Custom View

Create a custom Blade view at `resources/views/livewire/custom-event-modal-content.blade.php`:

```php
<div>
    @if($event)
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-4">{{ $event['title'] ?? 'Event Details' }}</h2>

            @if(isset($event['description']))
                <p class="text-gray-600 mb-4">{{ $event['description'] }}</p>
            @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                @if(isset($event['start']))
                    <div>
                        <span class="font-semibold">Start:</span>
                        <span>{{ $event['start'] }}</span>
                    </div>
                @endif

                @if(isset($event['end']))
                    <div>
                        <span class="font-semibold">End:</span>
                        <span>{{ $event['end'] }}</span>
                    </div>
                @endif
            </div>

            @if(isset($event['location']))
                <div class="mb-4">
                    <span class="font-semibold">Location:</span>
                    <span>{{ $event['location'] }}</span>
                </div>
            @endif

            <!-- Add custom content here -->
            <div class="mt-6">
                <x-artisanpack-button
                    color="primary"
                    wire:click="$dispatch('closeModal')"
                >
                    Close
                </x-artisanpack-button>
            </div>
        </div>
    @endif
</div>
```

### Using Your Custom Component

To use your custom event modal content with the Calendar Enhanced component, pass it as the `eventModalComponent` prop:

```php
<x-artisanpack-calendar-enhanced
    :events="$events"
    event-modal-component="custom-event-modal-content"
/>
```

## Event Data Structure

The component receives an event array with the following typical structure:

```php
[
    'title' => 'Event Title',
    'start' => '2024-01-15 10:00:00',
    'end' => '2024-01-15 12:00:00',
    'description' => 'Event description',
    'location' => 'Event location',
    // ... any additional custom fields
]
```

## Livewire Events

### Listening to Events

The component listens for the `loadEventModal` event:

```php
#[On('loadEventModal')]
public function loadEvent(array $selectedEvent): void
{
    $this->event = $selectedEvent;
}
```

### Dispatching Events

You can dispatch events from your custom component to communicate with parent components:

```php
// Close the modal
$this->dispatch('closeModal');

// Refresh the calendar
$this->dispatch('refreshCalendar');

// Custom event
$this->dispatch('eventUpdated', eventId: $this->event['id']);
```

## Customization Examples

### Add Edit and Delete Actions

```php
<div class="mt-6 flex gap-2">
    <x-artisanpack-button
        color="primary"
        wire:click="editEvent({{ $event['id'] }})"
    >
        Edit Event
    </x-artisanpack-button>

    <x-artisanpack-button
        color="error"
        wire:click="deleteEvent({{ $event['id'] }})"
    >
        Delete Event
    </x-artisanpack-button>

    <x-artisanpack-button
        outline
        wire:click="$dispatch('closeModal')"
    >
        Cancel
    </x-artisanpack-button>
</div>
```

### Add Attendees List

```php
@if(isset($event['attendees']) && count($event['attendees']) > 0)
    <div class="mb-4">
        <h3 class="font-semibold mb-2">Attendees</h3>
        <ul class="space-y-1">
            @foreach($event['attendees'] as $attendee)
                <li class="flex items-center gap-2">
                    <x-artisanpack-avatar
                        :image="$attendee['avatar'] ?? null"
                        :placeholder="$attendee['initials'] ?? null"
                        size="sm"
                    />
                    <span>{{ $attendee['name'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
```

### Add Status Badge

```php
@if(isset($event['status']))
    <div class="mb-4">
        <x-artisanpack-badge
            :value="$event['status']"
            :color="match($event['status']) {
                'confirmed' => 'success',
                'pending' => 'warning',
                'cancelled' => 'error',
                default => 'neutral'
            }"
        />
    </div>
@endif
```

## Best Practices

1. **Keep it focused**: The modal should display relevant event information clearly
2. **Add actions**: Include edit, delete, or other relevant action buttons
3. **Handle errors**: Add error handling for missing or invalid event data
4. **Loading states**: Show loading indicators for async operations
5. **Responsive design**: Ensure the modal content looks good on all screen sizes

## Related Components

- [Calendar Enhanced](Calendar-Enhanced) - The main calendar component that uses this component
- [Modal](Modal) - Generic modal component
- [Calendar](Calendar) - Basic calendar component
