# Carousel Icon Customization Implementation Plan

## Overview

This document outlines the implementation plan for adding customizable icons to the Carousel component in the ArtisanPack UI Livewire UI Components package. The goal is to allow developers to customize the next arrow, previous arrow, and dot indicator icons by providing either icon names (like the existing Icon component system) or raw SVG content.

## Current State Analysis

### Existing Implementation

The current Carousel component (`src/View/Components/Carousel.php` and `resources/views/components/carousel.blade.php`) uses:

1. **Navigation Arrows**: 
   - Hardcoded `x-artisanpack-button` components with fixed icons
   - Left arrow: `icon="o-chevron-left"`
   - Right arrow: `icon="o-chevron-right"`
   - Located at lines 46-48 in the Blade template

2. **Dot Indicators**: 
   - Simple `<button>` elements with CSS styling
   - No actual icons, just rounded buttons with background color changes
   - Located at lines 100-104 in the Blade template

3. **Current Props**:
   - `$withoutArrows`: Boolean to hide/show navigation arrows
   - `$withoutIndicators`: Boolean to hide/show dot indicators

### Icon System Architecture

The project uses a well-established icon system:

1. **Icon Component** (`src/View/Components/Icon.php`):
   - Accepts `name` prop (string)
   - Converts names to Heroicon classes via `icon()` method
   - Handles dot notation (converts to dashes)
   - Auto-prefixes with "heroicon-" for standard names

2. **Button Component** (`src/View/Components/Button.php`):
   - Accepts `icon` and `iconRight` props (strings)
   - Uses the Icon component internally for rendering

3. **Heroicons Integration**:
   - Uses `blade-ui-kit/blade-heroicons` package
   - Icon names like "o-chevron-left" become "heroicon-o-chevron-left"

## Requirements Analysis

### Functional Requirements

1. **Customizable Navigation Arrows**:
   - Allow developers to specify custom icons for next/previous buttons
   - Support both icon names and raw SVG content
   - Maintain backward compatibility with existing implementations

2. **Customizable Dot Indicators**:
   - Allow developers to specify custom icons for dot indicators
   - Support both icon names and raw SVG content  
   - Maintain current CSS-only dots as default behavior

3. **Dual Input Support**:
   - **Icon Names**: String values like "o-chevron-left", "plus", "star"
   - **Raw SVG**: Complete SVG markup for custom icons

4. **Backward Compatibility**:
   - Existing Carousel implementations must continue working unchanged
   - Default icons should remain the same as current implementation

### Non-Functional Requirements

1. **Performance**: No significant impact on rendering performance
2. **Maintainability**: Follow existing component patterns and conventions
3. **Accessibility**: Maintain or improve accessibility features
4. **Documentation**: Clear examples and usage instructions

## Proposed Solution

### 1. New Component Properties

Add the following props to the Carousel component:

```php
public function __construct(
    // ... existing props ...
    
    // New icon customization props
    public mixed $nextArrow = null,
    public mixed $previousArrow = null, 
    public mixed $dots = null,
) {
    // ... existing constructor logic ...
}
```

**Prop Details**:
- `nextArrow`: String (icon name) or raw SVG content for next button
- `previousArrow`: String (icon name) or raw SVG content for previous button  
- `dots`: String (icon name) or raw SVG content for dot indicators
- All props accept `null` to use defaults

### 2. Icon Detection Logic

Add helper methods to the Carousel class:

```php
/**
 * Determines if the provided content is raw SVG or an icon name
 */
private function isRawSvg(mixed $content): bool
{
    return is_string($content) && 
           (str_starts_with(trim($content), '<svg') || 
            str_contains($content, '<svg'));
}

/**
 * Renders an icon based on whether it's a name or raw SVG
 */
private function renderIcon(mixed $icon, string $defaultName, array $classes = []): string
{
    if ($icon === null) {
        // Use default icon name
        return view('livewire-ui-components::components.icon', [
            'name' => $defaultName,
            'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => implode(' ', $classes)])
        ])->render();
    }
    
    if ($this->isRawSvg($icon)) {
        // Return raw SVG with additional classes if needed
        $classString = !empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
        return str_replace('<svg', '<svg' . $classString, $icon);
    }
    
    // Treat as icon name
    return view('livewire-ui-components::components.icon', [
        'name' => $icon,
        'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => implode(' ', $classes)])
    ])->render();
}
```

### 3. Template Modifications

#### Navigation Arrows

Replace the hardcoded button icons (lines 46-48):

**Current**:
```blade
<x-artisanpack-button icon="o-chevron-left" @click="previous()" class="absolute cursor-pointer left-5 top-1/2 z-[2] btn-circle btn-sm" />
<x-artisanpack-button icon="o-chevron-right" @click="next()" class="absolute cursor-pointer right-5 top-1/2 z-[2] btn-circle btn-sm" />
```

**New**:
```blade
<button @click="previous()" class="absolute cursor-pointer left-5 top-1/2 z-[2] btn btn-circle btn-sm">
    {!! $renderIcon($previousArrow, 'o-chevron-left', ['w-4', 'h-4']) !!}
</button>
<button @click="next()" class="absolute cursor-pointer right-5 top-1/2 z-[2] btn btn-circle btn-sm">
    {!! $renderIcon($nextArrow, 'o-chevron-right', ['w-4', 'h-4']) !!}
</button>
```

#### Dot Indicators

Modify the dots section (lines 100-104):

**Current**:
```blade
<template x-for="(slide, index) in slides">
    <button class="size-2.5 cursor-pointer rounded-full transition hover:scale-125" @click="currentSlideIndex = index + 1" :class="[currentSlideIndex === index + 1 ? 'bg-base-content' : 'bg-base-content/30']"></button>
</template>
```

**New**:
```blade
<template x-for="(slide, index) in slides">
    @if($dots !== null)
        <button class="cursor-pointer transition hover:scale-125" @click="currentSlideIndex = index + 1" :class="[currentSlideIndex === index + 1 ? 'opacity-100' : 'opacity-30']">
            {!! $renderIcon($dots, null, ['w-2.5', 'h-2.5']) !!}
        </button>
    @else
        <button class="size-2.5 cursor-pointer rounded-full transition hover:scale-125" @click="currentSlideIndex = index + 1" :class="[currentSlideIndex === index + 1 ? 'bg-base-content' : 'bg-base-content/30']"></button>
    @endif
</template>
```

### 4. Usage Examples

#### Basic Usage (Backward Compatible)
```blade
<!-- Uses default chevron icons -->
<x-artisanpack-carousel :slides="$slides" />
```

#### Custom Icon Names
```blade
<!-- Uses different Heroicons -->
<x-artisanpack-carousel 
    :slides="$slides" 
    nextArrow="arrow-right"
    previousArrow="arrow-left" 
    dots="o-stop"
/>
```

#### Mixed Usage
```blade
<!-- Custom SVG for arrows, icon name for dots -->
<x-artisanpack-carousel 
    :slides="$slides" 
    nextArrow="<svg viewBox='0 0 24 24'><path d='M9 18l6-6-6-6'/></svg>"
    previousArrow="<svg viewBox='0 0 24 24'><path d='M15 18l-6-6 6-6'/></svg>" 
    dots="star"
/>
```

#### Raw SVG Only
```blade
<x-artisanpack-carousel 
    :slides="$slides" 
    nextArrow="<svg viewBox='0 0 24 24' fill='currentColor'><path d='M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z'/></svg>"
    previousArrow="<svg viewBox='0 0 24 24' fill='currentColor'><path d='M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z'/></svg>"
    dots="<svg viewBox='0 0 8 8' fill='currentColor'><circle cx='4' cy='4' r='3'/></svg>"
/>
```

## Implementation Steps

### Phase 1: Component Class Updates
1. Add new props to constructor
2. Implement icon detection and rendering helper methods
3. Add proper type hints and documentation
4. Ensure backward compatibility

### Phase 2: Template Updates  
1. Modify navigation arrow rendering logic
2. Update dot indicator rendering logic
3. Test with various icon types
4. Ensure Alpine.js compatibility

### Phase 3: Testing & Validation
1. Unit tests for icon detection logic
2. Integration tests for various usage patterns
3. Visual regression testing
4. Accessibility testing
5. Performance impact assessment

### Phase 4: Documentation
1. Update component documentation
2. Add usage examples to docs
3. Create migration guide for existing users
4. Update README if needed

## Technical Considerations

### 1. Security Considerations
- **SVG Sanitization**: Consider implementing SVG sanitization to prevent XSS attacks
- **Content Validation**: Validate that provided content is safe before rendering
- **Recommendation**: Use a library like `ezyang/htmlpurifier` for SVG sanitization

### 2. Performance Considerations  
- **Caching**: Consider caching rendered icon output for repeated use
- **Size Impact**: Raw SVG content could increase page size
- **Recommendation**: Document best practices for SVG optimization

### 3. Accessibility Considerations
- **Screen Readers**: Ensure custom icons maintain proper ARIA labels
- **Focus Management**: Maintain keyboard navigation capabilities
- **Color Contrast**: Document requirements for custom icon contrast

### 4. Browser Compatibility
- **SVG Support**: Modern browsers support inline SVG well
- **Fallbacks**: Consider fallback strategies for older browsers
- **Testing**: Test across major browser versions

## Alternative Approaches Considered

### 1. Slot-Based Approach
**Pros**: More flexible, allows complex content
**Cons**: More complex implementation, harder to use for simple cases
**Decision**: Prop-based approach is simpler and covers most use cases

### 2. Icon Component Integration
**Pros**: Reuses existing Icon component logic  
**Cons**: Limited to icon names only, doesn't support raw SVG
**Decision**: Custom implementation allows both icon names and SVG

### 3. CSS Class Approach
**Pros**: Very flexible, allows any styling
**Cons**: Requires CSS knowledge, less user-friendly
**Decision**: Direct icon specification is more intuitive

## Risks and Mitigation

### 1. Breaking Changes
**Risk**: Changes could break existing implementations
**Mitigation**: Maintain full backward compatibility, extensive testing

### 2. Performance Impact
**Risk**: Additional logic could slow rendering
**Mitigation**: Optimize helper methods, implement caching if needed

### 3. Security Vulnerabilities  
**Risk**: Raw SVG could introduce XSS risks
**Mitigation**: Implement proper sanitization, document security best practices

### 4. Maintenance Complexity
**Risk**: More complex component logic increases maintenance burden
**Mitigation**: Follow existing patterns, comprehensive documentation and tests

## Success Criteria

1. **Functionality**: All three icon types (nextArrow, previousArrow, dots) are customizable
2. **Compatibility**: Existing implementations work without changes
3. **Usability**: Simple API for both icon names and raw SVG
4. **Performance**: No significant rendering performance degradation  
5. **Security**: No new security vulnerabilities introduced
6. **Documentation**: Clear usage examples and migration guide
7. **Testing**: Comprehensive test coverage for new functionality

## Future Enhancements

1. **Icon Sets**: Support for other icon libraries (Font Awesome, etc.)
2. **Animation Support**: Custom animations for icon state changes
3. **Conditional Icons**: Different icons based on state/context
4. **Icon Templates**: Predefined icon combinations for common use cases

## Conclusion

This implementation plan provides a comprehensive approach to adding icon customization to the Carousel component while maintaining backward compatibility and following established patterns. The dual support for icon names and raw SVG content offers flexibility for developers while keeping the API simple and intuitive.

The phased implementation approach allows for iterative development and testing, reducing risk while ensuring a robust final implementation.