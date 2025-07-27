# Calendar Component Alignment Plan

## Overview

This document outlines the steps needed to align the current Calendar component implementation with the design specified in the calendar implementation plan. The plan is organized into three main phases, following the structure of the original implementation plan.

## Current State Analysis

The current Calendar component already implements several features from the implementation plan:

- Basic properties for color scheme, custom color, and view options
- Navigation methods for moving between months
- Methods for getting weekdays and weeks for the calendar grid
- A method for determining appropriate text color based on background color
- A header with navigation buttons and month/year display
- A view selector dropdown
- A weekdays row
- A calendar grid with day cells
- Event display with color scheme support
- Today highlighting

However, there are several aspects that need to be enhanced or implemented to fully align with the planned design.

## Implementation Steps

### Phase 1: Update the Calendar Component Class

1. **Enhance Configuration Options**
   - Update the `mount` method to better handle the new properties
   - Add support for more configuration options in the `config` array
   - Implement proper validation for color schemes and custom colors

2. **Update Event Handling**
   - Enhance the event handling to support the new event properties structure
   - Add support for `title` property (renamed from `description`)
   - Add support for `start_time` property
   - Improve handling of event color schemes

3. **Add Helper Methods**
   - Add methods for view switching (day/week/month/year)
   - Add methods for improved navigation between time periods
   - Add methods for event interaction

### Phase 2: Implement Tailwind-Based Styling

1. **Update HTML Structure**
   - Modify the calendar.blade.php file to match the Tailgrid design
   - Implement the container layout with responsive padding
   - Update the header styling with flex layout and spacing
   - Enhance the calendar grid structure

2. **Implement Color Scheme Variations**
   - Update the color scheme implementation to use Tailwind's color utilities
   - Implement proper handling of custom colors
   - Ensure consistent styling across different color schemes

3. **Ensure Responsive Behavior**
   - Implement responsive text sizes using Tailwind's responsive prefixes
   - Add responsive day cell heights
   - Implement responsive day name display (full/short/minimal)

4. **Add Transitions and Hover Effects**
   - Add button hover effects
   - Add day cell hover effects
   - Add event hover effects
   - Implement view selector transitions

5. **Add Dark Mode Support**
   - Implement dark mode variants for backgrounds, text, and borders
   - Ensure proper contrast in dark mode

### Phase 3: Enhance JavaScript Functionality

1. **Implement View Switching**
   - Add support for day, week, month, and year views
   - Update the header display based on the current view
   - Implement proper rendering for each view

2. **Improve Navigation Between Time Periods**
   - Enhance navigation controls to handle different views
   - Add methods for navigating to previous/next day, week, month, or year
   - Implement "Today" button functionality

3. **Add Event Interaction Capabilities**
   - Implement click events for calendar events
   - Add hover effects for events
   - Implement a custom event system for parent components

4. **Ensure Proper Rendering with Different Color Schemes**
   - Update the custom color script to handle all views
   - Ensure consistent styling across different color schemes in all views

## Implementation Timeline

### Week 1: Calendar Component Class Updates
- Day 1-2: Enhance configuration options and update event handling
- Day 3-4: Add helper methods for view switching and navigation
- Day 5: Testing and refinement

### Week 2: Tailwind-Based Styling
- Day 1-2: Update HTML structure and implement color scheme variations
- Day 3-4: Ensure responsive behavior and add transitions/hover effects
- Day 5: Add dark mode support and testing

### Week 3: JavaScript Functionality
- Day 1-2: Implement view switching and improve navigation
- Day 3-4: Add event interaction capabilities and ensure proper rendering
- Day 5: Final testing and documentation

## Detailed Implementation Tasks

### Calendar Component Class Updates

```php
// Update mount method
public function mount(
    ?string $id = null,
    ?int $months = 1,
    ?string $locale = 'en-EN',
    ?bool $weekendHighlight = false,
    ?bool $sundayStart = false,
    ?string $colorScheme = 'primary',
    ?string $customColor = null,
    ?string $view = 'month',
    ?array $config = [],
    ?array $events = []
) {
    // Existing code...
    
    // Validate color scheme
    $validColorSchemes = ['primary', 'secondary', 'accent', 'custom'];
    $this->colorScheme = in_array($colorScheme, $validColorSchemes) ? $colorScheme : 'primary';
    
    // Validate custom color
    if ($this->colorScheme === 'custom' && !empty($customColor)) {
        $this->customColor = $customColor;
    }
    
    // Validate view
    $validViews = ['day', 'week', 'month', 'year'];
    $this->view = in_array($view, $validViews) ? $view : 'month';
    
    // Existing code...
}
```

### Tailwind-Based Styling Updates

```html
<!-- Update header styling -->
<div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-dark-3 bg-gray-2 dark:bg-dark-2 py-3 pr-4 pl-[30px]">
    <div class="flex items-center space-x-2">
        <button wire:click="goToPreviousPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out">
            <x-artisanpack-icon name="o-chevron-left" class="w-4 h-4" />
        </button>
        <button wire:click="goToNextPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out">
            <x-artisanpack-icon name="o-chevron-right" class="w-4 h-4" />
        </button>
        <button wire:click="goToToday" type="button" class="px-3 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out">
            Today
        </button>
    </div>
    
    <p class="text-base font-semibold text-dark dark:text-white sm:text-xl">
        {{ $headerText }}
    </p>
    
    <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-dark">
        <x-artisanpack-select
            :options="[
                ['name' => 'Day', 'id' => 'day'],
                ['name' => 'Week', 'id' => 'week'],
                ['name' => 'Month', 'id' => 'month'],
                ['name' => 'Year', 'id' => 'year']
            ]"
            wire:model.live="view"
            class="w-40"
        />
    </div>
</div>
```

### JavaScript Functionality Updates

```javascript
// Add view switching functionality
function switchView(view) {
    if (view === 'day') {
        // Day view logic
    } else if (view === 'week') {
        // Week view logic
    } else if (view === 'year') {
        // Year view logic
    } else {
        // Month view logic (default)
    }
    
    // Update the display after view change
    this.updateViewDisplay(view);
}

// Update display based on current view
function updateViewDisplay(view) {
    if (view === 'day') {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        this.headerText = this.currentDate.toLocaleDateString('{{ $locale }}', options);
    } else if (view === 'week') {
        // Calculate start and end of week
        // ...
    } else if (view === 'year') {
        this.headerText = this.currentYear.toString();
    } else {
        // Month view (default)
        this.headerText = `${this.currentMonth} ${this.currentYear}`;
    }
}
```

## Conclusion

By following this implementation plan, the Calendar component will be fully aligned with the design specified in the calendar implementation plan. The enhanced component will offer a flexible, responsive calendar solution with improved styling, functionality, and user experience.

The implementation will prioritize:
1. Maintaining and extending the current functionality
2. Supporting all ArtisanPack UI color schemes
3. Ensuring responsive behavior across device sizes
4. Maintaining accessibility standards
5. Providing comprehensive documentation and examples

This plan provides a clear roadmap for enhancing the Calendar component to match the Tailgrid design while supporting the ArtisanPack UI color system.