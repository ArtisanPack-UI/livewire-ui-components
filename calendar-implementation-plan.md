# Calendar Component Implementation Plan

## Overview

This document outlines the plan for enhancing the existing Calendar component in the ArtisanPack UI Livewire UI Components package to match the Tailgrid calendar design while supporting various color schemes. The implementation will follow the established patterns in the package and provide a flexible, responsive calendar component.

## Current State Analysis

### Existing Calendar Component

The current Calendar component uses the VanillaCalendar JavaScript library and provides:
- Basic calendar functionality with month navigation
- Support for single or multiple month views
- Event display with date ranges, labels, and descriptions
- Weekend highlighting and locale customization
- Sunday/Monday week start options

### Tailgrid Calendar Design

The Tailgrid calendar offers:
- A more detailed HTML structure with specific styling for days, headers, and events
- Responsive design with different text displays for mobile/desktop
- Rounded corners and consistent styling
- Clear visual hierarchy for dates, events, and navigation
- Week/Month/Year view switching

### Color System in ArtisanPack UI

The package currently supports:
- Primary, secondary, and accent color schemes
- Integration with Tailwind color palettes
- DaisyUI compatibility
- Custom color definitions via CSS variables

## Implementation Goals

1. Enhance the Calendar component to visually match the Tailgrid design
2. Maintain and extend the current functionality
3. Support all ArtisanPack UI color schemes (primary, secondary, accent, and custom)
4. Ensure responsive behavior across device sizes
5. Maintain accessibility standards
6. Provide comprehensive documentation and examples

## Component Structure

### Properties

```php
public function __construct(
    public ?string $id = null,
    public ?int $months = 1,
    public ?string $locale = 'en-EN',
    public ?bool $weekendHighlight = false,
    public ?bool $sundayStart = false,
    public ?string $colorScheme = 'primary', // New: primary, secondary, accent, or custom
    public ?string $customColor = null,      // New: hex color code for custom scheme
    public ?string $view = 'month',          // New: day, week, month, year
    public ?array $config = [],
    public ?array $events = [],
)
```

### HTML Structure

The component will use a structure similar to the Tailgrid example, but with Tailwind utility classes:

```html
<div class="mx-auto px-4 lg:container">
    <!-- Header with month/year display and view selector -->
    <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-dark-3 bg-gray-2 dark:bg-dark-2 py-3 pr-4 pl-[30px]">
        <p class="text-base font-semibold text-dark dark:text-white sm:text-xl">
            {{ month }} {{ year }}
        </p>
        
        <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-dark">
            <!-- View selector dropdown -->
            <select class="relative z-20 h-11 appearance-none rounded-[5px] text-dark dark:text-white border border-stroke dark:border-dark-3 bg-transparent pr-10 pl-5 outline-hidden">
                <option value="week" class="dark:bg-dark-2">Week</option>
                <option value="month" class="dark:bg-dark-2">Month</option>
                <option value="year" class="dark:bg-dark-2">Year</option>
            </select>
            <!-- Dropdown arrow icon -->
        </div>
    </div>
    
    <!-- Calendar grid -->
    <div class="w-full max-w-full bg-white dark:bg-dark-2 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="rounded-t-lg bg-primary text-white">
                    <!-- Day headers with responsive text -->
                    <th class="h-[60px] w-10 rounded-tl-lg p-2 text-xs lg:w-28 xl:text-base 2xl:w-40">
                        <span class="hidden lg:block">Sunday</span>
                        <span class="block lg:hidden">Sun</span>
                    </th>
                    <!-- Other day headers follow the same pattern -->
                </tr>
            </thead>
            <tbody>
                <!-- Calendar grid with dates and events -->
                <tr class="h-20 text-center">
                    <td class="ease hover:bg-gray-2 dark:hover:bg-dark relative h-28 w-10 cursor-pointer border border-stroke dark:border-dark-3 p-1 transition duration-500 md:h-[125px] lg:w-28 2xl:w-40">
                        <!-- Date cell content -->
                    </td>
                    <!-- Other date cells -->
                </tr>
                <!-- Additional rows -->
            </tbody>
        </table>
    </div>
</div>
```

## Color Scheme Implementation

### Integration with ArtisanPack UI Color System

The calendar will use Tailwind utility classes with CSS variables for theming:

```html
<!-- Primary color scheme (default) -->
<tr class="rounded-t-lg bg-primary text-white">
    <!-- Day headers -->
</tr>

<!-- Secondary color scheme -->
<tr class="rounded-t-lg bg-secondary text-white">
    <!-- Day headers -->
</tr>

<!-- Accent color scheme -->
<tr class="rounded-t-lg bg-accent text-white">
    <!-- Day headers -->
</tr>

<!-- Custom color scheme -->
<tr class="rounded-t-lg" style="background-color: {{ $customColor }}; color: {{ getContrastColor($customColor) }}">
    <!-- Day headers -->
</tr>
```

### Event Color Handling

Events will support color schemes through Tailwind utility classes:

```php
// Example event definition
[
    'id' => 'event-123',                    // Optional: Unique identifier for the event
    'date' => '2025-07-20',                 // Required: Event date
    'title' => 'Quarterly planning session', // Required: Event title (renamed from description)
    'label' => 'Team Meeting',              // Required: Short label for the event
    'start_time' => '14:00',                // Optional: Start time for the event
    'colorScheme' => 'primary',             // Optional: 'primary', 'secondary', 'accent', 'custom'
    'customColor' => '#FF5733',             // Optional: Used when colorScheme is 'custom'
]
```

## Implementation Steps

1. **Update the Calendar Component Class**
   - Add new properties for color scheme and view options
   - Enhance the configuration options
   - Update the event handling to support color schemes and new properties

2. **Implement Tailwind-Based Styling**
   - Use Tailwind utility classes for all styling
   - Implement color scheme variations using Tailwind's color utilities
   - Ensure responsive behavior using Tailwind's responsive prefixes
   - Add transitions and hover effects with Tailwind utilities

3. **Enhance JavaScript Functionality**
   - Implement view switching (day/week/month/year)
   - Improve navigation between time periods
   - Add event interaction capabilities
   - Ensure proper rendering with different color schemes

4. **Update the Render Method**
   - Replace the current VanillaCalendar implementation with custom HTML structure
   - Implement responsive design elements using Tailwind's responsive utilities
   - Apply appropriate color scheme classes dynamically

5. **Add Documentation**
   - Create usage examples
   - Document all available properties and methods
   - Provide examples of different color schemes and views

## Usage Examples

### Basic Calendar

```php
<x-calendar />
```

### Calendar with Primary Color Scheme

```php
<x-calendar color-scheme="primary" />
```

### Calendar with Custom Color

```php
<x-calendar color-scheme="custom" custom-color="#8A2BE2" />
```

### Calendar with Events

```php
<x-calendar 
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
```

### Week View Calendar

```php
<x-calendar view="week" />
```

## Responsive Behavior

The calendar will adapt to different screen sizes using Tailwind's responsive utilities:

- **Large screens**: Full day names, more detailed event information
  ```html
  <span class="hidden lg:block">Wednesday</span>
  ```

- **Medium screens**: Abbreviated day names, compact event display
  ```html
  <span class="hidden md:block lg:hidden">Wed</span>
  ```

- **Small screens**: Minimal day names, simplified event indicators
  ```html
  <span class="block md:hidden">W</span>
  ```

## Accessibility Considerations

- Proper ARIA attributes for interactive elements
- Keyboard navigation support
- Sufficient color contrast for all color schemes
- Screen reader friendly markup

## Testing Strategy

1. **Unit Tests**
   - Test component rendering with different options
   - Verify color scheme application
   - Check event display logic with new property structure

2. **Visual Tests**
   - Verify appearance with different color schemes
   - Check responsive behavior across breakpoints
   - Ensure event styling is consistent

3. **Interaction Tests**
   - Test navigation between time periods
   - Verify view switching functionality
   - Test event interactions

## Timeline and Priorities

1. **Phase 1: Core Structure**
   - Update component class with new properties
   - Implement basic HTML structure matching Tailgrid design
   - Add color scheme support

2. **Phase 2: Enhanced Functionality**
   - Implement view switching
   - Improve event display and interaction
   - Add responsive behavior

3. **Phase 3: Polish and Documentation**
   - Refine styling and transitions
   - Ensure accessibility compliance
   - Complete documentation and examples

## Conclusion

This implementation plan provides a roadmap for enhancing the Calendar component to match the Tailgrid design while supporting the ArtisanPack UI color system. The enhanced component will offer a flexible, responsive calendar solution that integrates seamlessly with the rest of the package.

By following this plan, the Calendar component will maintain consistency with other components in the package while offering the visual appeal and functionality of the Tailgrid calendar design. The implementation will prioritize Tailwind utility classes for styling and include the requested event property structure changes.
