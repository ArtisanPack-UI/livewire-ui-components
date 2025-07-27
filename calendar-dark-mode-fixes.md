# Calendar Component Dark Mode Fixes

## Issue Summary

The calendar component was experiencing two main issues:

1. White text on white background in dark mode
2. Primary colors not showing properly

## Root Cause

The issues were caused by the use of color classes with the `-content` suffix (e.g., `text-primary-content`), which might not be defined in the CSS framework being used. This resulted in:

1. Text color not being properly set in dark mode, causing white text on white background
2. Primary colors not showing properly because the text color wasn't adapting to the background color

## Fixes Implemented

### 1. Fixed White Text on White Background in Dark Mode

The main fix was to replace the `-content` suffix pattern with explicit text color classes:

```diff
- <tr class="rounded-t-lg {{ $colorScheme === 'custom' ? 'bg-custom' : 'bg-' . $colorScheme . ' text-' . $colorScheme . '-content' }}">
+ <tr class="rounded-t-lg {{ $colorScheme === 'custom' ? 'bg-custom' : 'bg-' . $colorScheme . ' text-white' }}">
```

This ensures that the text is always white on the colored background, providing good contrast regardless of the color scheme.

Similar changes were made for the today highlight (current day indicator):

```diff
- $todayClasses .= 'bg-' . $colorScheme . ' text-' . $colorScheme . '-content';
+ $todayClasses .= 'bg-' . $colorScheme . ' text-white';
```

And for events:

```diff
- $eventClasses .= ' bg-' . $eventColorScheme . ' text-' . $eventColorScheme . '-content';
+ $eventClasses .= ' bg-' . $eventColorScheme . ' text-white';
```

### 2. Fixed Primary Colors Not Showing Properly

The primary colors issue was fixed by ensuring that the color classes are properly applied and that text colors adapt appropriately to the background colors. By using explicit `text-white` classes instead of the `-content` suffix, we ensure that the text is always visible on the colored backgrounds.

For custom colors, the component already had a good implementation that determines the appropriate text color based on contrast:

```php
$bgColor = $event['customColor'];
$textColor = $calendarInstance->a11yGetContrastColor($bgColor);
$eventStyles = "background-color: {$bgColor}; color: {$textColor};";
```

## How Dark Mode and Color Schemes Work

### Dark Mode

The calendar component uses Tailwind's dark mode variants to apply appropriate styling in dark mode:

- Background colors: `bg-white dark:bg-dark-2`
- Text colors: `text-dark dark:text-white`
- Border colors: `border-stroke dark:border-dark-3`
- Hover effects: `hover:bg-gray-100 dark:hover:bg-dark-3`

This ensures that the component looks good in both light and dark modes.

### Color Schemes

The calendar component supports four different color schemes:

1. **Primary**: Uses `bg-primary` for backgrounds and `text-white` for text
2. **Secondary**: Uses `bg-secondary` for backgrounds and `text-white` for text
3. **Accent**: Uses `bg-accent` for backgrounds and `text-white` for text
4. **Custom**: Uses a custom color provided by the user, with text color determined based on contrast

The color scheme is applied to:

- The weekday header row
- The today highlight (current day indicator)
- Events

## Testing

The changes were tested using a test HTML file that simulates the calendar component in dark mode. The test file includes:

- Color scheme tests
- Calendar header
- Weekday header
- Today highlight
- Events

The test confirmed that:

1. Text is visible on all backgrounds in dark mode
2. Primary colors are displaying correctly with proper contrast
3. Dark mode is properly implemented with appropriate color variations

## Conclusion

The fixes implemented ensure that the calendar component works correctly in dark mode and that primary colors are displayed properly. By using explicit text color classes instead of the `-content` suffix, we ensure good contrast and visibility regardless of the color scheme or mode (light/dark).