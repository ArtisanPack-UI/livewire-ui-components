# Calendar Component Implementation - Step 3

## Enhance JavaScript Functionality

This document outlines the third step in implementing the enhanced Calendar component according to the [Calendar Implementation Plan](calendar-implementation-plan.md). This step focuses on enhancing the JavaScript functionality of the Calendar component.

### 1. Implement View Switching (day/week/month/year)

The Calendar component now supports four different views:

- **Day View**: Shows a detailed view of a single day
- **Week View**: Shows a week-long period
- **Month View**: Shows a full month (default view)
- **Year View**: Shows a full year

The view can be changed using the dropdown selector in the header. The implementation includes:

```php
// Switch between day, week, month, and year views
switchView(view) {
    if (!this.calendar) return;
    
    // Update calendar view
    this.calendar.settings.selection.day = view === 'day';
    this.calendar.settings.selection.week = view === 'week';
    this.calendar.settings.selection.month = view === 'month';
    this.calendar.settings.selection.year = view === 'year';
    
    // Apply the view change
    this.calendar.setType(view);
    
    // Refresh the calendar to apply changes
    this.calendar.update();
    
    // Update display based on view
    this.updateViewDisplay(view);
}
```

The header display is automatically updated based on the current view:

```php
// Update display based on current view
updateViewDisplay(view) {
    // Adjust header display based on view
    if (view === 'day') {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        this.headerText = this.currentDate.toLocaleDateString('{{ $locale }}', options);
    } else if (view === 'week') {
        // Calculate start and end of week
        const weekStart = new Date(this.currentDate);
        const weekEnd = new Date(this.currentDate);
        const dayOfWeek = this.currentDate.getDay();
        const diff = this.currentDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : {{ $sundayStart ? 0 : 1 }});
        
        weekStart.setDate(diff);
        weekEnd.setDate(diff + 6);
        
        const startStr = weekStart.toLocaleDateString('{{ $locale }}', { month: 'short', day: 'numeric' });
        const endStr = weekEnd.toLocaleDateString('{{ $locale }}', { month: 'short', day: 'numeric', year: 'numeric' });
        
        this.headerText = `${startStr} - ${endStr}`;
    } else if (view === 'year') {
        this.headerText = this.currentYear.toString();
    } else {
        // Month view (default)
        this.headerText = `${this.currentMonth} ${this.currentYear}`;
    }
}
```

### 2. Improve Navigation Between Time Periods

Navigation controls have been added to allow users to move between time periods:

- **Previous**: Navigate to the previous day, week, month, or year (depending on the current view)
- **Today**: Jump to the current date
- **Next**: Navigate to the next day, week, month, or year (depending on the current view)

The navigation methods are implemented as follows:

```php
// Navigate to previous period based on current view
navigatePrevious() {
    if (!this.calendar) return;
    
    if (this.currentView === 'day') {
        this.calendar.prev('day');
    } else if (this.currentView === 'week') {
        this.calendar.prev('week');
    } else if (this.currentView === 'year') {
        this.calendar.prev('year');
    } else {
        // Month view (default)
        this.calendar.prev();
    }
    
    // Update the display after navigation
    this.updateViewDisplay(this.currentView);
}

// Navigate to next period based on current view
navigateNext() {
    if (!this.calendar) return;
    
    if (this.currentView === 'day') {
        this.calendar.next('day');
    } else if (this.currentView === 'week') {
        this.calendar.next('week');
    } else if (this.currentView === 'year') {
        this.calendar.next('year');
    } else {
        // Month view (default)
        this.calendar.next();
    }
    
    // Update the display after navigation
    this.updateViewDisplay(this.currentView);
}

// Navigate to today
navigateToday() {
    if (!this.calendar) return;
    
    this.calendar.today();
    this.currentDate = new Date();
    this.currentMonth = this.currentDate.toLocaleString('{{ $locale }}', { month: 'long' });
    this.currentYear = this.currentDate.getFullYear();
    
    // Update the display after navigation
    this.updateViewDisplay(this.currentView);
}
```

### 3. Add Event Interaction Capabilities

Event interactions have been enhanced to provide a better user experience:

- **Click Events**: Events can be clicked to trigger actions
- **Hover Effects**: Events have hover effects to indicate interactivity
- **Custom Events**: A custom event system has been implemented to allow parent components to respond to calendar events

The event interaction implementation includes:

```php
// Initialize event interactions
initEventInteractions() {
    // Add event listeners after calendar is initialized
    document.addEventListener('vanilla-calendar-day-click', (e) => {
        // Handle day click event
        console.log('Day clicked:', e.detail.date);
    });
    
    // Event hover interactions are handled by CSS in the addCss method
    
    // Add click event handling for events
    document.addEventListener('click', (e) => {
        const eventEl = e.target.closest('.vanilla-calendar-day__event');
        if (eventEl) {
            const eventId = eventEl.dataset.eventId;
            if (eventId) {
                console.log('Event clicked:', eventId);
                // Dispatch custom event for event click
                const event = new CustomEvent('calendar-event-click', {
                    detail: { eventId: eventId }
                });
                document.dispatchEvent(event);
            }
        }
    });
}
```

Event hover effects are implemented using Tailwind CSS classes:

```php
'event' => [
    'primary' => 'bg-primary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
    'secondary' => 'bg-secondary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
    'accent' => 'bg-accent text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
    'custom' => 'custom-color rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
],
```

### 4. Ensure Proper Rendering with Different Color Schemes

The calendar now properly renders with different color schemes in all views:

- **Primary**: Default blue color scheme
- **Secondary**: Alternative color scheme
- **Accent**: Accent color scheme
- **Custom**: Custom color scheme with user-defined color

The color scheme implementation is maintained through the existing CSS classes and the custom color script:

```php
// Apply custom color to regular event elements
document.querySelectorAll('.custom-color').forEach(el => {
    const customColor = el.closest('[data-custom-color]')?.dataset.customColor || '{$customColor}';
    if (customColor) {
        const textColor = getContrastColor(customColor);
        el.style.backgroundColor = customColor;
        el.style.color = textColor;
    }
});

// Apply custom color to header elements
document.querySelectorAll('.custom-color-header').forEach(el => {
    el.style.backgroundColor = '{$customColor}';
    el.style.color = '{$textColor}';
});

// Apply custom color to selected day elements
document.querySelectorAll('.custom-color-selected').forEach(el => {
    el.style.backgroundColor = `${'{$customColor}'}20`; // 20% opacity
    el.style.borderColor = '{$customColor}';
});
```

## Usage Examples

### Basic Calendar with View Selection

```php
<x-calendar view="month" />
```

### Calendar with Day View

```php
<x-calendar view="day" />
```

### Calendar with Week View

```php
<x-calendar view="week" />
```

### Calendar with Year View

```php
<x-calendar view="year" />
```

### Calendar with Custom Color Scheme

```php
<x-calendar 
    view="month"
    color-scheme="custom"
    custom-color="#8A2BE2"
/>
```

### Calendar with Events and Event Handling

```php
<div
    x-data="{
        handleEventClick(eventId) {
            // Handle event click
            console.log('Event clicked:', eventId);
        }
    }"
    @calendar-event-click.window="handleEventClick($event.detail.eventId)"
>
    <x-calendar 
        view="month"
        :events="[
            [
                'id' => 'meeting-1',
                'date' => '2025-07-20',
                'label' => 'Team Meeting',
                'title' => 'Quarterly planning session',
                'start_time' => '10:00',
                'colorScheme' => 'primary',
            ],
            [
                'id' => 'conference-1',
                'range' => ['2025-07-25', '2025-07-27'],
                'label' => 'Conference',
                'title' => 'Annual industry conference',
                'start_time' => '09:00',
                'colorScheme' => 'accent',
            ]
        ]"
    />
</div>
```

## Next Steps

After enhancing the JavaScript functionality for the Calendar component, the next step will be to update the render method as outlined in the implementation plan. This will involve:

1. Replacing the current VanillaCalendar implementation with custom HTML structure
2. Implementing responsive design elements using Tailwind's responsive utilities
3. Applying appropriate color scheme classes dynamically