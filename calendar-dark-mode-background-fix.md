# Calendar Component Dark Mode Background Fix

## Issue Description

The calendar component was experiencing an issue in dark mode where some background colors weren't displaying correctly. Specifically, the `bg-dark` utility class wasn't a valid class, causing the component to fall back to a white background in dark mode.

## Solution

The solution was to replace the problematic dark mode background classes with appropriate color variables from the Mary UI/theme file located at `/Users/jacobmartella/Herd/artisanpack-ui/resources/css/artisanpack-ui-theme.css`.

### Color Variables Used

From the theme file, we identified the following color variables for dark mode:

```css
[data-theme="dark"] {
    /* --- ArtisanPack UI Dark Mode Overrides --- */
    --color-neutral: #191D24;
    --color-neutral-content: #A6ADBB;
    --color-base-100: #2A303C;
    --color-base-200: #242933;
    --color-base-300: #20252E;
    --color-base-content: #A6ADBB;
    /* ... other variables ... */
}
```

### Changes Made

The following changes were made to the calendar component:

1. Replaced `dark:bg-dark` with `dark:bg-base-100`
   ```diff
   - <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-dark">
   + <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-base-100">
   ```

2. Replaced `dark:hover:bg-dark` with `dark:hover:bg-base-200`
   ```diff
   - $cellClasses = 'ease hover:bg-gray-2 dark:hover:bg-dark relative h-28 w-10 cursor-pointer border border-stroke dark:border-dark-3 p-1 transition duration-500 md:h-[125px] lg:w-28 2xl:w-40';
   + $cellClasses = 'ease hover:bg-gray-2 dark:hover:bg-base-200 relative h-28 w-10 cursor-pointer border border-stroke dark:border-base-300 p-1 transition duration-500 md:h-[125px] lg:w-28 2xl:w-40';
   ```

3. Replaced `dark:bg-dark-2` with `dark:bg-base-200`
   ```diff
   - <div class="bg-white dark:bg-dark-2 border border-stroke dark:border-dark-3 rounded-lg p-4">
   + <div class="bg-white dark:bg-base-200 border border-stroke dark:border-base-300 rounded-lg p-4">
   ```

4. Replaced `dark:hover:bg-dark-3` with `dark:hover:bg-base-300`
   ```diff
   - <button wire:click="goToPreviousPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out">
   + <button wire:click="goToPreviousPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out">
   ```

5. Replaced `dark:bg-stone-900` with `dark:bg-neutral`
   ```diff
   - <div class="w-full max-w-full bg-white dark:bg-stone-900 overflow-x-auto rounded-lg shadow-sm">
   + <div class="w-full max-w-full bg-white dark:bg-neutral overflow-x-auto rounded-lg shadow-sm">
   ```

6. Replaced `dark:bg-dark-3/50` with `dark:bg-base-300/50`
   ```diff
   - $cellClasses .= ' bg-gray-1 dark:bg-dark-3/50';
   + $cellClasses .= ' bg-gray-1 dark:bg-base-300/50';
   ```

7. Replaced `dark:bg-dark-3/30` with `dark:bg-base-300/30`
   ```diff
   - $cellClasses .= ' bg-gray-1/50 dark:bg-dark-3/30';
   + $cellClasses .= ' bg-gray-1/50 dark:bg-base-300/30';
   ```

8. Replaced `dark:border-dark-3` with `dark:border-base-300`
   ```diff
   - <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-dark-3 bg-gray-2 dark:bg-dark-2 py-3 pr-4 pl-[30px]">
   + <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-base-300 bg-gray-2 dark:bg-base-200 py-3 pr-4 pl-[30px]">
   ```

## Testing

A test file (`calendar-dark-mode-fix-test.html`) was created to verify that the dark mode background colors are displaying correctly with the changes made. This test file includes examples of all the modified elements and allows toggling between light and dark mode for comparison.

## Benefits

These changes ensure that:

1. The calendar component displays correctly in dark mode
2. The background colors use the appropriate color variables from the theme file
3. The component maintains a consistent look and feel with the rest of the application
4. The component follows the design system's color guidelines

## Future Considerations

To prevent similar issues in the future, consider:

1. Creating a comprehensive set of utility classes that map directly to the theme variables
2. Adding automated tests for dark mode styling
3. Documenting the available color variables and their intended usage