---
title: Calendar Component
---

# Calendar Component

The Calendar component provides a flexible and customizable calendar display for your application. It features a responsive design with support for different views (day, week, month, year), color schemes, event display, and localization.

## Basic Usage

```php
<x-calendar />
```

## Examples

### Basic Calendar

```php
<x-calendar />
```

### Calendar with Primary Color Scheme

```php
<x-calendar color-scheme="primary" />
```

### Calendar with Secondary Color Scheme

```php
<x-calendar color-scheme="secondary" />
```

### Calendar with Accent Color Scheme

```php
<x-calendar color-scheme="accent" />
```

### Calendar with Custom Color

```php
<x-calendar color-scheme="custom" custom-color="#8A2BE2" />
```

### Different Calendar Views

```php
<!-- Day View -->
<x-calendar view="day" />

<!-- Week View -->
<x-calendar view="week" />

<!-- Month View (default) -->
<x-calendar view="month" />

<!-- Year View -->
<x-calendar view="year" />
```

### Multiple Month Calendar

```php
<x-calendar :months="3" />
```

### Calendar with Weekend Highlighting

```php
<x-calendar :weekendHighlight="true" />
```

### Calendar with Sunday as First Day of Week

```php
<x-calendar :sundayStart="true" />
```

### Calendar with Custom Locale

```php
<x-calendar locale="fr-FR" />
```

### Calendar with Events

```php
@php
$events = [
    [
        'id' => 'meeting-1',
        'date' => '2025-07-20',
        'label' => 'Team Meeting',
        'title' => 'Quarterly planning session',
        'description' => 'Discuss Q3 goals and initiatives',
        'start_time' => '10:00',
        'end_time' => '11:30',
        'colorScheme' => 'primary',
    ],
    [
        'id' => 'conference-1',
        'range' => ['2025-07-25', '2025-07-27'],
        'label' => 'Conference',
        'title' => 'Annual industry conference',
        'description' => 'Networking and learning opportunities',
        'start_time' => '09:00',
        'end_time' => '17:00',
        'colorScheme' => 'accent',
    ],
    [
        'id' => 'custom-event',
        'date' => '2025-07-15',
        'label' => 'Custom Event',
        'title' => 'Event with custom color',
        'description' => 'This event uses a custom color',
        'colorScheme' => 'custom',
        'customColor' => '#FF5733',
    ]
];
@endphp

<x-calendar :events="$events" />
```

### Calendar with Custom Event Modal

```php
<x-calendar :events="$events">
    <x-slot:eventModalContent>
        {{-- 
            You have access to the $selectedEvent variable here.
            You can use it to display any event data.
        --}}
        <div>
            <h3 class="text-2xl font-bold text-dark dark:text-white">{{ $selectedEvent['title'] }}</h3>
            
            @if(!empty($selectedEvent['description']))
                <p class="mt-4 mb-4 text-base-content">{{ $selectedEvent['description'] }}</p>
            @endif
            
            {{-- Custom fields and layout --}}
            <div class="bg-gray-100 dark:bg-base-300 p-4 rounded-lg mt-4">
                <p class="text-sm text-base-content">
                    <span class="font-semibold">Location:</span> 
                    {{ $selectedEvent['location'] ?? 'No location specified' }}
                </p>
                
                <p class="text-sm text-base-content mt-2">
                    <span class="font-semibold">Organizer:</span> 
                    {{ $selectedEvent['organizer'] ?? 'No organizer specified' }}
                </p>
            </div>
            
            {{-- You can even add forms or buttons for event actions --}}
            <div class="mt-6 flex space-x-3">
                <button class="btn btn-primary">Edit Event</button>
                <button class="btn btn-outline btn-error">Delete Event</button>
            </div>
        </div>
    </x-slot:eventModalContent>
</x-calendar>
```

### Calendar with Custom Configuration

```php
@php
$config = [
    'settings' => [
        'selected' => [
            'dates' => ['2025-07-10']
        ],
        'visibility' => [
            'theme' => 'light'
        ]
    ]
];
@endphp

<x-calendar :config="$config" />
```

## Props

| Prop               | Type          | Default     | Description                                                        |
|--------------------|---------------|-------------|--------------------------------------------------------------------|
| `id`               | string\|null  | `null`      | Optional ID for the calendar element                               |
| `months`           | int\|null     | `1`         | Number of months to display                                        |
| `locale`           | string\|null  | `'en-EN'`   | Locale for the calendar                                            |
| `weekendHighlight` | boolean\|null | `false`     | Whether to highlight weekends                                      |
| `sundayStart`      | boolean\|null | `false`     | Whether the week starts on Sunday                                  |
| `colorScheme`      | string\|null  | `'primary'` | Color scheme for the calendar (primary, secondary, accent, custom) |
| `customColor`      | string\|null  | `null`      | Hex color code for custom color scheme                             |
| `view`             | string\|null  | `'month'`   | Calendar view (day, week, month, year)                             |
| `config`           | array\|null   | `[]`        | Additional configuration options                                   |
| `events`           | array\|null   | `[]`        | Array of events to display on the calendar                         |

## Event Format

Events can be specified in two formats:

### Single Date Event

```php
[
    'id' => 'event-1',                  // Unique identifier for the event
    'date' => '2025-07-15',             // Date in Y-m-d format
    'label' => 'Meeting',               // Event label (displayed on calendar)
    'title' => 'Team planning meeting', // Event title (displayed in popup)
    'description' => 'Meeting details', // Event description (displayed in popup)
    'start_time' => '14:00',            // Optional start time
    'end_time' => '15:30',              // Optional end time
    'colorScheme' => 'primary',         // Color scheme (primary, secondary, accent, custom)
    'customColor' => '#FF5733',         // Custom color (when colorScheme is 'custom')
    'css' => 'custom-class'             // Optional additional CSS classes
]
```

### Date Range Event

```php
[
    'id' => 'event-2',                  // Unique identifier for the event
    'range' => ['2025-07-20', '2025-07-25'], // Start and end dates
    'label' => 'Conference',            // Event label (displayed on calendar)
    'title' => 'Annual conference',     // Event title (displayed in popup)
    'description' => 'Conference details', // Event description (displayed in popup)
    'start_time' => '09:00',            // Optional start time
    'end_time' => '17:00',              // Optional end time
    'colorScheme' => 'accent',          // Color scheme (primary, secondary, accent, custom)
    'customColor' => null,              // Custom color (when colorScheme is 'custom')
    'css' => 'custom-class'             // Optional additional CSS classes
]
```

## Customizing the Event Modal

The Calendar component provides two ways to customize the event modal that appears when a user clicks on an event:

1. Using the `eventModalContent` slot for simple customizations
2. Creating a custom Livewire component for more complex customizations

### Method 1: Using the Event Modal Slot

For simple customizations, you can use the `eventModalContent` slot:

```php
<x-calendar :events="$events">
    <x-slot:eventModalContent>
        {{-- Your custom modal content here --}}
    </x-slot:eventModalContent>
</x-calendar>
```

Within the `eventModalContent` slot, you have access to the `$selectedEvent` variable, which contains all the data for the currently selected event:

```php
<x-slot:eventModalContent>
    <div>
        <h3 class="text-2xl font-bold">{{ $selectedEvent['title'] }}</h3>
        <p class="mt-4">{{ $selectedEvent['description'] }}</p>
        
        {{-- Access any custom fields you've added to your events --}}
        @if(isset($selectedEvent['location']))
            <p class="mt-2"><strong>Location:</strong> {{ $selectedEvent['location'] }}</p>
        @endif
    </div>
</x-slot:eventModalContent>
```

### Method 2: Creating a Custom Livewire Component

For more complex customizations, you can create a custom Livewire component that replaces the default `EventModalContent` component. This approach gives you more flexibility and allows you to add custom logic, methods, and properties.

#### Step 1: Create a Custom Livewire Component

Create a new Livewire component that will handle the event modal content:

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\View\View;

class CustomEventModal extends Component
{
    public ?array $event = null;
    
    // You can add additional properties here
    public bool $isEditing = false;
    public array $editableEvent = [];

    #[On('loadEventModal')]
    public function loadEvent(array $selectedEvent): void
    {
        $this->event = $selectedEvent;
        $this->resetState();
    }
    
    // You can add additional methods here
    public function resetState(): void
    {
        $this->isEditing = false;
        $this->editableEvent = [];
    }
    
    public function startEditing(): void
    {
        $this->isEditing = true;
        $this->editableEvent = $this->event;
    }
    
    public function saveChanges(): void
    {
        // Handle saving changes to the event
        $this->isEditing = false;
        
        // Emit an event to update the calendar
        $this->dispatch('eventUpdated', event: $this->editableEvent);
    }

    public function render(): View
    {
        return view('livewire.custom-event-modal');
    }
}
```

#### Step 2: Create a Blade Template for Your Component

Create a corresponding Blade template for your custom component:

```php
{{-- resources/views/livewire/custom-event-modal.blade.php --}}
<div>
    @if ($event)
        <div>
            @if ($isEditing)
                {{-- Edit Mode --}}
                <form wire:submit.prevent="saveChanges">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input type="text" wire:model="editableEvent.title" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="editableEvent.description" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Location</label>
                        <input type="text" wire:model="editableEvent.location" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    
                    <div class="flex space-x-3 mt-6">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" wire:click="resetState" class="btn btn-outline">Cancel</button>
                    </div>
                </form>
            @else
                {{-- View Mode --}}
                <h3 class="text-2xl font-bold text-dark dark:text-white">{{ $event['title'] ?? $event['label'] }}</h3>
                
                @if (!empty($event['description']))
                    <p class="mt-4 mb-4 text-base-content">{{ $event['description'] }}</p>
                @endif
                
                <div class="bg-gray-100 dark:bg-base-300 p-4 rounded-lg mt-4">
                    <div class="flex items-center space-x-2 text-base-content mb-2">
                        <span class="font-semibold">Date:</span>
                        @if (isset($event['range']))
                            <span>{{ $event['start_date'] }} - {{ $event['end_date'] }}</span>
                        @else
                            <span>{{ date('j M Y', strtotime($event['date'])) }}</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2 text-base-content mb-2">
                        <span class="font-semibold">Time:</span>
                        @if (!empty($event['start_time']))
                            <span>{{ $event['start_time'] }}@if (!empty($event['end_time'])) - {{ $event['end_time'] }}@endif</span>
                        @else
                            <span>All day</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2 text-base-content">
                        <span class="font-semibold">Location:</span>
                        <span>{{ $event['location'] ?? 'No location specified' }}</span>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <button wire:click="startEditing" class="btn btn-primary">Edit Event</button>
                    <button class="btn btn-outline btn-error">Delete Event</button>
                </div>
            @endif
        </div>
    @endif
</div>
```

#### Step 3: Use Your Custom Component with the Calendar

Pass your custom component's name to the Calendar component using the `eventView` parameter:

```php
<x-calendar 
    :events="$events" 
    eventView="custom-event-modal"
/>
```

### Example: Custom Event Modal with Tabs

Here's an example of a more complex custom event modal with tabs:

```php
<x-calendar :events="$events">
    <x-slot:eventModalContent>
        <div x-data="{ activeTab: 'details' }">
            {{-- Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <button 
                        @click="activeTab = 'details'" 
                        :class="{ 'border-primary text-primary': activeTab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'details' }"
                        class="py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Details
                    </button>
                    <button 
                        @click="activeTab = 'attendees'" 
                        :class="{ 'border-primary text-primary': activeTab === 'attendees', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'attendees' }"
                        class="py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Attendees
                    </button>
                    <button 
                        @click="activeTab = 'actions'" 
                        :class="{ 'border-primary text-primary': activeTab === 'actions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'actions' }"
                        class="py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Actions
                    </button>
                </nav>
            </div>
            
            {{-- Tab Content --}}
            <div class="mt-4">
                {{-- Details Tab --}}
                <div x-show="activeTab === 'details'">
                    <h3 class="text-2xl font-bold">{{ $selectedEvent['title'] }}</h3>
                    <p class="mt-4">{{ $selectedEvent['description'] }}</p>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold">Date & Time</h4>
                            <p>{{ $selectedEvent['start_date'] ?? date('j M', strtotime($selectedEvent['date'])) }}</p>
                            <p>{{ $selectedEvent['start_time'] ?? 'All day' }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold">Location</h4>
                            <p>{{ $selectedEvent['location'] ?? 'No location specified' }}</p>
                        </div>
                    </div>
                </div>
                
                {{-- Attendees Tab --}}
                <div x-show="activeTab === 'attendees'">
                    <h3 class="text-xl font-semibold mb-4">Attendees</h3>
                    @if(isset($selectedEvent['attendees']) && count($selectedEvent['attendees']) > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($selectedEvent['attendees'] as $attendee)
                                <li class="py-3 flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-3">
                                        {{ substr($attendee['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $attendee['name'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $attendee['email'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No attendees for this event</p>
                    @endif
                </div>
                
                {{-- Actions Tab --}}
                <div x-show="activeTab === 'actions'">
                    <h3 class="text-xl font-semibold mb-4">Event Actions</h3>
                    <div class="space-y-3">
                        <button class="btn btn-primary w-full">Edit Event</button>
                        <button class="btn btn-outline w-full">Duplicate Event</button>
                        <button class="btn btn-outline btn-error w-full">Delete Event</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:eventModalContent>
</x-calendar>
```

### Understanding the EventModalContent Component

The default `EventModalContent` component is a Livewire component that displays event details in the calendar modal. It:

1. Listens for the `loadEventModal` event dispatched by the Calendar component
2. Receives the selected event data and stores it in the `$event` property
3. Renders a view that displays the event details

When you create a custom component, you need to ensure it:

1. Has a public `$event` property to store the event data
2. Listens for the `loadEventModal` event using the `#[On('loadEventModal')]` attribute
3. Has a `loadEvent()` method that accepts the selected event data
4. Renders a view that displays the event data

## Color Schemes

The Calendar component supports four color schemes:

1. **Primary** (`color-scheme="primary"`) - Uses your application's primary color
2. **Secondary** (`color-scheme="secondary"`) - Uses your application's secondary color
3. **Accent** (`color-scheme="accent"`) - Uses your application's accent color
4. **Custom** (`color-scheme="custom" custom-color="#HEX"`) - Uses a custom color specified by the `custom-color` prop

Each event can also have its own color scheme by specifying the `colorScheme` property in the event data.

## Calendar Views

The Calendar component supports four different views:

1. **Day** (`view="day"`) - Shows a detailed view of a single day
2. **Week** (`view="week"`) - Shows a week view with days as columns
3. **Month** (`view="month"`) - Shows a traditional month calendar (default)
4. **Year** (`view="year"`) - Shows a year overview with months

Users can switch between views using the view selector in the calendar header.

## Event Interactions

Events on the calendar are interactive:

- Hovering over an event shows a subtle scale animation
- Clicking on an event displays a modal with detailed information
- Events with the same color scheme are visually grouped

## Responsive Behavior

The calendar adapts to different screen sizes:

- **Large screens**: Full day names, more detailed event information
- **Medium screens**: Short day names (e.g., "Mon" instead of "Monday")
- **Small screens**: Minimal day names (e.g., "M" instead of "Monday"), compact layout

## Styling

The Calendar component uses Tailwind CSS for styling. You can customize the appearance by:

1. Using the provided props (`colorScheme`, `customColor`, etc.)
2. Adding custom CSS classes via the `css` property in events
3. Providing custom configuration via the `config` prop

### Default Classes

- Calendar container: `mx-auto px-4 lg:container`
- Calendar header: `flex justify-between items-center p-4 border-b border-stroke dark:border-dark-3`
- Weekdays: `grid grid-cols-7 text-center py-2 border-b border-stroke dark:border-dark-3`
- Month grid: `grid grid-cols-7 gap-px`
- Day cell: `p-1 h-24 sm:h-28 md:h-32 border border-stroke dark:border-dark-3`
- Event: `rounded-md p-1 text-xs mb-1 truncate shadow-sm`

## Methods

The Calendar component includes several helper methods:

| Method | Description |
|--------|-------------|
| `getContrastColor(string $hexColor)` | Determines appropriate text color (black or white) based on background color |
| `addCss(string $config)` | Adds Tailwind CSS classes to the calendar configuration |
| `popups()` | Generates HTML for event popups |
| `customColorScript()` | Generates JavaScript for handling custom colors |
| `setup()` | Sets up the calendar configuration |

## Accessibility

The Calendar component follows accessibility best practices:

- Uses semantic HTML for calendar structure
- Provides proper labeling for dates and events
- Supports keyboard navigation
- Includes appropriate ARIA attributes
- Ensures sufficient color contrast for all color schemes

## Related Components

- [DatePicker](datepicker.md) - Date selection input
- [DateTime](datetime.md) - Date and time selection input
