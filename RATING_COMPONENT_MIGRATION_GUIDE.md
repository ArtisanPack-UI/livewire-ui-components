# Rating Component Migration Guide

## Overview

This guide helps you migrate from the old Rating component implementation to the new enhanced version that supports dedicated icon and color props instead of requiring CSS class overrides.

## What Changed

### Before (v0.4.x and earlier)
```blade
<!-- Old way: Manual CSS class overrides -->
<x-artisanpack-rating class="!mask-circle bg-red-500" />
<x-artisanpack-rating class="!mask-heart bg-blue-600" />
```

### After (v0.5.0+)
```blade
<!-- New way: Dedicated props -->
<x-artisanpack-rating icon="heroicon-o-heart" color="red-500" />
<x-artisanpack-rating icon="heroicon-s-heart" color="blue-600" />
```

## Breaking Changes

### 1. Constructor Signature
**New parameters added** (non-breaking due to defaults):
- `icon`, `filledIcon`, `emptyIcon` - Icon customization
- `color`, `filledColor`, `emptyColor` - Color customization  
- `size`, `halfStars`, `hoverEffect` - Enhanced features
- `showValue`, `valueFormat`, `clearable` - Display options
- `label`, `helper`, `error`, `required`, `disabled`, `readonly` - Form integration

### 2. CSS Class Behavior
Direct CSS class manipulation may not work the same way as icons are now rendered using the `artisanpack-icon` component system.

### 3. Template Structure
The component now includes proper form structure with labels, helper text, and error handling.

## Migration Examples

### Basic Icon Change

**Before:**
```blade
<x-artisanpack-rating class="!mask-circle" />
```

**After:**
```blade
<x-artisanpack-rating icon="heroicon-o-heart" />
```

### Color Customization

**Before:**
```blade
<x-artisanpack-rating class="bg-red-500" />
```

**After:**
```blade
<x-artisanpack-rating color="red-500" />
```

### Complex Customization

**Before:**
```blade
<x-artisanpack-rating 
    class="!mask-heart bg-purple-600 rating-lg" 
    wire:model="userRating" 
/>
```

**After:**
```blade
<x-artisanpack-rating 
    icon="heroicon-s-heart"
    color="purple-600"
    size="lg"
    wire:model="userRating"
/>
```

### Different Icons for Filled/Empty

**Before:**
```blade
<!-- Not easily achievable with class-based approach -->
```

**After:**
```blade
<x-artisanpack-rating 
    filled-icon="heroicon-s-star"
    empty-icon="heroicon-o-star"
    filled-color="warning"
    empty-color="gray-300"
/>
```

## New Features Available

### Half-Star Support
```blade
<x-artisanpack-rating 
    :half-stars="true" 
    :value="3.5"
    label="Product Rating"
/>
```

### Custom Hex Colors
```blade
<x-artisanpack-rating 
    filled-color="#ff6b35"
    empty-color="#cccccc"
/>
```

### Interactive Features
```blade
<x-artisanpack-rating 
    :hover-effect="true"
    :show-value="true"
    value-format="{value} out of {max}"
    :clearable="true"
/>
```

### Form Integration
```blade
<x-artisanpack-rating 
    label="Rate this product"
    helper="Click on a star to rate"
    :required="true"
    error="Please provide a rating"
/>
```

### Accessibility Features
```blade
<x-artisanpack-rating 
    label="Product Rating"
    :inline-label="false"
    :disabled="false"
    :readonly="false"
/>
```

## Icon Reference

### Popular Icon Options

**Star Icons:**
- `heroicon-s-star` (default filled)
- `heroicon-o-star` (default empty)

**Heart Icons:**
- `heroicon-s-heart`
- `heroicon-o-heart`

**Other Icons:**
- `heroicon-o-thumb-up`
- `heroicon-s-thumb-up`
- `heroicon-o-face-smile`
- `heroicon-s-face-smile`

All icons from the [Blade Icons](https://blade-ui-kit.com/blade-icons) package are supported.

## Color Reference

### Semantic Colors
- `primary`
- `secondary` 
- `accent`
- `warning` (default)
- `error`
- `success`
- `info`

### Tailwind Colors
- `red-500`, `blue-600`, `green-400`, etc.
- Any valid Tailwind color name with level

### Custom Hex Colors
- `#ff0000`, `#00ff00`, `#0066cc`, etc.

## Step-by-Step Migration

### 1. Identify Current Usage
Search your codebase for rating components with class overrides:
```bash
# Find rating components with class customizations
grep -r "artisanpack-rating.*class=" resources/views/
grep -r "mask.*rating" resources/views/
```

### 2. Map Classes to Props

| Old Class Pattern | New Prop | Example |
|-------------------|----------|---------|
| `!mask-circle` | `icon="heroicon-o-circle"` | Circle rating |
| `!mask-heart` | `icon="heroicon-s-heart"` | Heart rating |
| `!mask-star` | `icon="heroicon-s-star"` | Star rating (default) |
| `bg-red-500` | `color="red-500"` | Red color |
| `bg-primary` | `color="primary"` | Primary color |
| `rating-sm` | `size="sm"` | Small size |
| `rating-lg` | `size="lg"` | Large size |

### 3. Update Components
Replace class-based customization with props:

```blade
<!-- Before -->
<x-artisanpack-rating class="!mask-heart bg-red-500 rating-lg" />

<!-- After -->
<x-artisanpack-rating 
    icon="heroicon-s-heart"
    color="red-500"
    size="lg"
/>
```

### 4. Add New Features
Take advantage of new functionality:

```blade
<x-artisanpack-rating 
    icon="heroicon-s-star"
    color="warning"
    size="md"
    label="Rate this product"
    :half-stars="true"
    :hover-effect="true"
    :show-value="true"
    helper="Click on a star to rate"
    wire:model="rating"
/>
```

### 5. Test Thoroughly
- Verify visual consistency
- Test Livewire model binding
- Validate form submission
- Check responsive behavior

## Backward Compatibility

### Supported (for transition period)
- Basic class attribute still works for non-conflicting styles
- Original `id` and `total` parameters unchanged
- Livewire `wire:model` binding unchanged

### Not Supported
- CSS mask overrides may not work as expected
- Direct background color classes may be overridden by props

## Troubleshooting

### Issue: Icons not displaying
**Solution:** Ensure the Blade Icons package is installed and configured:
```bash
composer require blade-ui-kit/blade-heroicons
```

### Issue: Colors not applying
**Solution:** Check color format:
- Use `color="red-500"` not `color="text-red-500"`
- Use `color="primary"` not `color="text-primary"`
- Use `color="#ff0000"` for hex colors

### Issue: Half-stars not working
**Solution:** Ensure `half-stars` is enabled and value is decimal:
```blade
<x-artisanpack-rating :half-stars="true" :value="3.5" />
```

### Issue: Livewire binding broken
**Solution:** Use the same binding syntax as before:
```blade
<x-artisanpack-rating wire:model="rating" />
```

## Performance Considerations

The new implementation:
- ✅ Uses the existing `artisanpack-icon` component (no additional overhead)
- ✅ Leverages Tailwind CSS classes (same performance)
- ✅ Adds minimal PHP processing for prop resolution
- ✅ Maintains the same HTML output structure

## Best Practices

### 1. Use Semantic Colors When Possible
```blade
<!-- Good -->
<x-artisanpack-rating color="primary" />

<!-- Avoid if semantic option exists -->
<x-artisanpack-rating color="blue-500" />
```

### 2. Provide Accessible Labels
```blade
<x-artisanpack-rating 
    label="Product Rating"
    :required="true"
/>
```

### 3. Use Consistent Icon Styles
```blade
<!-- Consistent: both outline -->
<x-artisanpack-rating 
    filled-icon="heroicon-o-heart"
    empty-icon="heroicon-o-heart"
/>

<!-- Inconsistent: mixed styles -->
<x-artisanpack-rating 
    filled-icon="heroicon-s-heart"
    empty-icon="heroicon-o-star"
/>
```

### 4. Handle Validation Properly
```blade
<x-artisanpack-rating 
    label="Rating"
    :required="true"
    error="{{ $errors->first('rating') }}"
/>
```

## Support

If you encounter issues during migration:

1. Check this guide for common patterns
2. Review the component tests for usage examples
3. Consult the updated documentation
4. Ensure all dependencies are up to date

The new Rating component provides a much more powerful and user-friendly API while maintaining backward compatibility where possible. The migration should be straightforward for most use cases.
