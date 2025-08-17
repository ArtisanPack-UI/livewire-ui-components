# Enhanced Tags Component Documentation

## Overview

The Tags component has been enhanced with powerful search functionality while maintaining full backward compatibility. Users can now search through predefined tag options while retaining the ability to create custom tags.

## Features

### Core Features (Existing)
- Simple tag creation via text input
- Add tags by pressing Enter or comma
- Remove individual tags via X icon
- Clear all tags functionality
- Form validation integration
- Readonly/disabled states
- Auto-resizing input field

### New Search Features
- Searchable dropdown with predefined options
- Server-side and client-side search support
- Debounced search input
- Minimum character requirements
- Progress indicators during search
- "No results found" messaging
- Custom tag creation alongside predefined options
- Keyboard navigation support

## Basic Usage (Backward Compatible)

### Simple Tags (No Changes Required)
```php
<x-artisanpack-tags
    wire:model="tags"
    label="Keywords"
    placeholder="Enter keywords..."
/>
```

This continues to work exactly as before with no search functionality.

## Search Functionality

### Basic Search Usage
```php
<x-artisanpack-tags
    wire:model="selectedTags"
    label="Project Tags"
    :searchable="true"
    :options="$availableTags"
    search-function="searchTags"
    min-chars="2"
    debounce="300ms"
/>
```

### Advanced Configuration
```php
<x-artisanpack-tags
    wire:model="userSkills"
    label="Skills"
    :searchable="true"
    :options="$skills"
    search-function="searchSkills"
    option-label="name"
    option-value="id"
    option-sub-label="category"
    :allow-custom-tags="true"
    custom-tags-text="Press Enter to add custom skill"
    no-result-text="No matching skills found"
    height="max-h-48"
/>
```

### Server-Side Search Integration
```php
// In your Livewire component
public function searchSkills($query)
{
    $this->skills = Skill::where('name', 'like', "%{$query}%")
        ->limit(10)
        ->get()
        ->toArray();
}
```

## Configuration Options

### Search Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `searchable` | bool | `false` | Enable search functionality |
| `debounce` | string | `'250ms'` | Debounce delay for search input |
| `min-chars` | int | `0` | Minimum characters to trigger search |
| `search-function` | string | `'search'` | Livewire method name for search |
| `option-value` | string | `'id'` | Key for option value extraction |
| `option-label` | string | `'name'` | Key for option label extraction |
| `option-sub-label` | string | `''` | Key for option sub-label extraction |
| `option-avatar` | string | `'avatar'` | Key for option avatar extraction |
| `height` | string | `'max-h-64'` | Maximum height of dropdown |
| `options` | Collection/array | `new Collection()` | Available options for search |
| `no-result-text` | string | `'No results found.'` | Text when no results |
| `allow-custom-tags` | bool | `true` | Allow custom tag creation |
| `custom-tags-text` | string | `'Press Enter to create'` | Custom tag hint text |

### Standard Properties (Existing)

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `id` | string | null | Component ID |
| `label` | string | null | Label text |
| `hint` | string | null | Hint text |
| `icon` | string | null | Left icon |
| `icon-right` | string | null | Right icon |
| `inline` | bool | `false` | Inline label display |
| `clearable` | bool | `false` | Show clear button |
| `prefix` | string | null | Text prefix |
| `suffix` | string | null | Text suffix |

## Usage Examples

### Example 1: Technology Stack Tags
```php
// Component
public $technologies = [];
public $availableTechnologies = [
    ['id' => 1, 'name' => 'Laravel', 'category' => 'Framework'],
    ['id' => 2, 'name' => 'Vue.js', 'category' => 'Frontend'],
    ['id' => 3, 'name' => 'MySQL', 'category' => 'Database'],
];

// Template
<x-artisanpack-tags
    wire:model="technologies"
    label="Technology Stack"
    :searchable="true"
    :options="$availableTechnologies"
    option-label="name"
    option-sub-label="category"
    min-chars="2"
    :allow-custom-tags="true"
/>
```

### Example 2: User Skills with Server Search
```php
// Component
public $userSkills = [];
public $skillOptions = [];

public function searchSkills($query)
{
    $this->skillOptions = Skill::where('name', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->limit(15)
        ->get()
        ->map(function ($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'level' => $skill->level
            ];
        })
        ->toArray();
}

// Template
<x-artisanpack-tags
    wire:model="userSkills"
    label="Your Skills"
    :searchable="true"
    :options="$skillOptions"
    search-function="searchSkills"
    option-value="id"
    option-label="name"
    option-sub-label="description"
    debounce="400ms"
    min-chars="3"
    no-result-text="No skills found matching your search"
    custom-tags-text="Add as custom skill"
/>
```

### Example 3: Project Categories (No Custom Tags)
```php
// Component
public $projectCategories = [];
public $categoryOptions = [
    ['id' => 'web', 'name' => 'Web Development'],
    ['id' => 'mobile', 'name' => 'Mobile Development'],
    ['id' => 'api', 'name' => 'API Development'],
    ['id' => 'database', 'name' => 'Database Design'],
];

// Template
<x-artisanpack-tags
    wire:model="projectCategories"
    label="Project Categories"
    :searchable="true"
    :options="$categoryOptions"
    :allow-custom-tags="false"
    no-result-text="No categories match your search"
    height="max-h-48"
/>
```

## Migration Guide

### From Non-Searchable to Searchable

1. **Add the `searchable` property:**
```php
// Before
<x-artisanpack-tags wire:model="tags" label="Tags" />

// After
<x-artisanpack-tags 
    wire:model="tags" 
    label="Tags"
    :searchable="true"
    :options="$tagOptions"
/>
```

2. **Provide options data:**
```php
public $tagOptions = [
    ['id' => 1, 'name' => 'Laravel'],
    ['id' => 2, 'name' => 'PHP'],
    // ... more options
];
```

3. **Implement search method (optional for server-side search):**
```php
public function searchTags($query)
{
    $this->tagOptions = Tag::where('name', 'like', "%{$query}%")
        ->get()
        ->toArray();
}
```

## Error Handling

### Common Configuration Errors

1. **Searchable without options:**
```php
// ✗ This will throw an exception
<x-artisanpack-tags 
    :searchable="true" 
    wire:model="tags" 
/>

// ✓ Correct - provide options
<x-artisanpack-tags 
    :searchable="true" 
    :options="$tagOptions"
    wire:model="tags" 
/>
```

2. **Empty options array:**
```php
// ✗ This will throw an exception
<x-artisanpack-tags 
    :searchable="true" 
    :options="[]"
    wire:model="tags" 
/>
```

## Accessibility Features

- **Keyboard Navigation**: Full keyboard support with arrow keys, Enter, and Escape
- **ARIA Labels**: Proper ARIA labeling for screen readers
- **Focus Management**: Intelligent focus handling
- **Screen Reader Support**: Comprehensive screen reader compatibility

## Performance Considerations

### Large Option Sets
- Use server-side search for large datasets
- Implement pagination for extensive results
- Consider virtual scrolling for very large lists

### Search Optimization
- Adjust `debounce` timing based on your needs
- Set appropriate `min-chars` to reduce unnecessary requests
- Cache search results when appropriate

## Alpine.js Directive Fixes

### Recent Technical Improvements

The Tags component has been updated to resolve Alpine.js directive rendering issues that could cause problems in certain environments:

#### Issues Resolved

1. **Class Attribute Rendering Issue**
   - **Problem**: `:class` attributes were being rendered as literal text instead of being processed by Alpine.js
   - **Cause**: Invalid Alpine.js directive syntax (`x-class` and `x-classes` instead of `:class`)
   - **Solution**: Updated all instances to use proper Alpine.js syntax

2. **Alpine.js Anchor Reference Error**
   - **Problem**: Console error "Alpine: no element provided to x-anchor..."
   - **Cause**: Missing `x-ref="container"` reference for the `x-anchor` directive
   - **Solution**: Added proper element reference to resolve anchor positioning

#### Technical Changes Made

```php
// Fixed directive syntax
// Before: x-class="text-error" 
// After:  :class="'text-error'"

// Before: x-classes="fieldset-label"
// After:  :class="'fieldset-label'"

// Added missing reference
<label x-ref="container" ... >
```

#### Impact

These fixes ensure:
- Proper CSS class application in error and hint states
- Correct dropdown positioning with Alpine.js anchor directive
- Elimination of browser console errors
- Improved component reliability across different Alpine.js versions

## Browser Compatibility

- Modern browsers with ES6+ support
- Alpine.js 3.x compatibility
- Livewire 3.x compatibility

## Testing

The component includes comprehensive tests covering:
- Backward compatibility
- Search functionality with arrays and Collections
- Error handling for invalid configurations
- Helper method functionality
- Advanced configuration options
- Alpine.js directive fixes and compatibility

Use PHPUnit or your preferred testing framework to run the component tests.

## Contributing

When contributing to the Tags component:
1. Maintain backward compatibility
2. Add tests for new features
3. Update documentation
4. Follow existing code patterns
5. Ensure accessibility compliance

## Changelog

### Version 2.0.1
- ✅ Fixed Alpine.js directive syntax issues (x-class/x-classes → :class)
- ✅ Resolved Alpine.js anchor reference error
- ✅ Improved component reliability and console error elimination
- ✅ Enhanced Alpine.js compatibility across versions

### Version 2.0.0
- ✅ Added search functionality
- ✅ Server-side and client-side search support
- ✅ Configurable dropdown options
- ✅ Custom tag creation alongside predefined options
- ✅ Enhanced keyboard navigation
- ✅ Improved accessibility
- ✅ Maintained full backward compatibility

---

This component provides a comprehensive tagging solution with advanced search capabilities and robust Alpine.js integration.