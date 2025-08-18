# Rating Component Refactor Plan

## Executive Summary

This document outlines a comprehensive plan to refactor the ArtisanPack UI Rating component to simplify its API by extracting icon and color customization from CSS classes into dedicated component props. The refactor will implement the documented API that is currently missing from the actual implementation.

## Current State Analysis

### Implementation vs Documentation Gap

**Critical Finding**: The current Rating component implementation is significantly simpler than what the documentation describes:

**Current Implementation:**
- PHP Class: Only has `id` and `total` (default 5) constructor parameters  
- Blade Template: Basic radio inputs with hardcoded `mask mask-star-2` classes
- Customization: Developers must override classes manually using `!mask-circle` and `bg-{color}`
- No icon system integration
- No color prop handling

**Documented API (Not Implemented):**
- Rich prop system with `icon`, `filled-icon`, `empty-icon`
- Color props like `filled-color`, `empty-color`
- Size variants, hover effects, half-stars
- Blade Icons integration
- 24+ documented props vs 2 actual props

### Current Architecture

```php
// Current Rating.php constructor
public function __construct(
    public ?string $id = null,
    public int $total = 5
) {
    $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
}
```

```blade
{{-- Current rating.blade.php template --}}
<div class="rating gap-1 {{ $size }}" x-cloak>
    <input type="radio" name="{{ $modelName() }}" value="0" class="rating-hidden hidden" {{ $attributes->whereStartsWith('wire:model') }} />
    @for ($i = 1; $i <= $total; $i++)
        <input type="radio" name="{{ $modelName() }}" value="{{ $i }}" 
               {{ $attributes->whereStartsWith('wire:model') }}
               {{ $attributes->class(["mask mask-star-2"]) }} />
    @endfor
</div>
```

### Existing Infrastructure Available

**Icon System**: The project has a robust `artisanpack-icon` component system:
- Used by Button component: `<x-artisanpack-icon :name="$icon" />`
- Supports prefixes like `o-`, `s-`, `m-` (outline, solid, mini)
- Examples: `o-home`, `s-user`, `m-bell`

**Color System**: Based on Tailwind CSS with project-specific extensions:
- Semantic colors: `primary`, `secondary`, `accent`
- Tailwind colors: `red-500`, `blue-300`, etc.
- Text color classes: `text-primary`, `text-warning`

## Proposed Refactor

### New Component API

The refactor will implement the documented API with focus on the requested `icon` and `color` props:

```php
public function __construct(
    public ?string $id = null,
    public int $total = 5,
    
    // NEW: Icon Props
    public ?string $icon = 'heroicon-s-star',
    public ?string $filledIcon = null,
    public ?string $emptyIcon = null,
    
    // NEW: Color Props  
    public ?string $color = 'warning',
    public ?string $filledColor = null,
    public ?string $emptyColor = 'gray-200',
    
    // NEW: Additional Props (from documentation)
    public ?string $size = 'md',
    public bool $halfStars = false,
    public bool $hoverEffect = false,
    public bool $showValue = false,
    public ?string $valueFormat = '{value}',
    public bool $clearable = false,
    public ?string $clearIcon = 'heroicon-o-x-circle',
    public bool $inlineLabel = false,
    public bool $required = false,
    public bool $disabled = false,
    public bool $readonly = false,
    public ?string $helper = null,
    public ?string $error = null,
    public ?string $label = null,
    public ?string $name = null,
) {}
```

### Color Prop Logic

The `color` prop will support multiple formats:

1. **Semantic Colors**: `primary`, `secondary`, `accent`
2. **Tailwind Colors**: `red-500`, `blue-300`, `green-600`
3. **Custom Hex**: `#ff0000`, `#00ff00`
4. **Fallback Priority**: `filledColor` > `color` > default (`warning`)

### Icon Prop Logic  

The `icon` prop will integrate with the existing `artisanpack-icon` system:

1. **Default Icon**: `heroicon-s-star` (solid star)
2. **Icon Priority**: `filledIcon`/`emptyIcon` > `icon` > default
3. **Blade Icons Support**: All icons from blade-ui-kit/blade-icons package
4. **Format**: Standard icon names like `heroicon-o-star`, `phosphor-heart`, etc.

## Implementation Plan

### Phase 1: Core Refactor (Breaking Changes)

#### 1.1 Update PHP Component Class

**File**: `src/View/Components/Rating.php`

**Changes**:
- Add new constructor parameters for `icon` and `color` props
- Implement color resolution logic
- Add icon resolution methods
- Maintain backward compatibility where possible

**New Methods**:
```php
public function resolveFilledColor(): string
{
    return $this->filledColor ?? $this->color ?? 'warning';
}

public function resolveEmptyColor(): string  
{
    return $this->emptyColor ?? 'gray-200';
}

public function resolveFilledIcon(): string
{
    return $this->filledIcon ?? $this->icon ?? 'heroicon-s-star';
}

public function resolveEmptyIcon(): string
{
    return $this->emptyIcon ?? $this->icon ?? 'heroicon-o-star';
}

public function getColorClass(string $color): string
{
    // Handle hex colors
    if (str_starts_with($color, '#')) {
        return ''; // Custom CSS will be needed
    }
    
    // Handle semantic colors
    if (in_array($color, ['primary', 'secondary', 'accent'])) {
        return "text-{$color}";
    }
    
    // Handle Tailwind colors
    return "text-{$color}";
}
```

#### 1.2 Update Blade Template

**File**: `resources/views/components/rating.blade.php`

**New Template Structure**:
```blade
<div class="rating gap-1 {{ $size }}" x-cloak>
    @if($label)
        <label class="{{ $inlineLabel ? 'inline-flex items-center gap-2' : 'block mb-1' }}">
            {{ $label }}
            @if($required) <span class="text-error">*</span> @endif
        </label>
    @endif
    
    <!-- Hidden input for no rating -->
    <input type="radio" name="{{ $modelName() }}" value="0" class="rating-hidden hidden" 
           {{ $attributes->whereStartsWith('wire:model') }} />
    
    <!-- Rating inputs -->
    @for ($i = 1; $i <= $total; $i++)
        <input type="radio" 
               name="{{ $modelName() }}" 
               value="{{ $i }}"
               {{ $attributes->whereStartsWith('wire:model') }}
               {{ $disabled ? 'disabled' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               class="mask bg-transparent {{ getColorClass(resolveFilledColor()) }}"
               style="mask-image: url('data:image/svg+xml,{{ urlencode($this->getIconSvg(resolveFilledIcon())) }}');"
        />
    @endfor
    
    @if($helper)
        <p class="text-sm text-gray-500 mt-1">{{ $helper }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-error mt-1">{{ $error }}</p>
    @endif
</div>
```

#### 1.3 Icon Integration

**New Method in Rating.php**:
```php
public function getIconSvg(string $iconName): string
{
    // This would integrate with the existing icon system
    // Need to extract SVG content from artisanpack-icon component
    return app('blade-icons')->svg($iconName)->toHtml();
}
```

### Phase 2: Enhanced Features

#### 2.1 Advanced Color Handling
- Custom hex color CSS generation
- Color palette validation
- Dynamic CSS injection for custom colors

#### 2.2 Half-Star Support
- Fractional rating display
- Enhanced input handling for decimal values

#### 2.3 Accessibility Enhancements
- ARIA attributes
- Keyboard navigation
- Screen reader support

### Phase 3: Testing & Documentation

#### 3.1 Test Suite
**New Test Files**:
- `tests/Feature/Components/RatingTest.php`
- `tests/Unit/Components/RatingColorResolutionTest.php`  
- `tests/Unit/Components/RatingIconResolutionTest.php`

**Test Coverage**:
- Icon prop functionality
- Color prop functionality (all formats)
- Backward compatibility
- Edge cases and validation
- Accessibility features

#### 3.2 Documentation Updates
- Update existing documentation to match implementation
- Add migration guide for breaking changes
- Create comprehensive examples
- Document new prop options

## Breaking Changes & Migration

### Breaking Changes

1. **Constructor Signature Change**: New parameters added (non-breaking if defaults provided)
2. **CSS Class Behavior**: Direct class manipulation may not work the same way
3. **Icon System**: Transition from CSS masks to component-based icons

### Migration Path

#### For Developers Currently Using Class Overrides:

**Before**:
```blade
<x-artisanpack-rating class="!mask-circle bg-red-500" />
```

**After**:
```blade
<x-artisanpack-rating icon="heroicon-o-heart" color="red-500" />
```

#### Migration Guide Steps:

1. **Identify Current Usage**: Search codebase for rating components with class overrides
2. **Map Classes to Props**: 
   - `!mask-circle` → `icon="heroicon-o-circle"`  
   - `bg-red-500` → `color="red-500"`
3. **Update Components**: Replace class-based customization with props
4. **Test Thoroughly**: Ensure visual consistency after migration

### Backward Compatibility Strategy

- Maintain support for class-based overrides during transition period
- Add deprecation warnings for old patterns
- Provide automated migration tools if possible

## Risk Assessment

### High Risk
- **Breaking Changes**: Potential disruption to existing implementations
- **Icon System Integration**: Complexity of SVG extraction and rendering
- **Performance**: Additional prop processing overhead

### Medium Risk  
- **Color Validation**: Handling invalid color values gracefully
- **CSS Generation**: Dynamic styles for hex colors
- **Browser Compatibility**: Mask CSS support variations

### Low Risk
- **Documentation Updates**: Straightforward content changes
- **Test Coverage**: Standard testing practices
- **Migration Tooling**: Optional enhancement

## Success Criteria

### Functional Requirements
- ✅ Icon prop accepts any Blade Icons package icon
- ✅ Color prop supports primary/secondary/accent, Tailwind colors, and hex codes
- ✅ Backward compatibility for existing class-based approach (with deprecation)
- ✅ All documented features implemented and working

### Quality Requirements  
- ✅ 95%+ test coverage for new functionality
- ✅ No performance degradation compared to current implementation
- ✅ Accessibility standards maintained or improved
- ✅ Documentation matches actual implementation

### Developer Experience
- ✅ Simplified API compared to class-based approach
- ✅ Clear migration path with examples
- ✅ Comprehensive error messages for invalid props
- ✅ IDE autocompletion support for props

## Timeline Estimate

### Phase 1: Core Refactor (5-7 days)
- Day 1-2: PHP component class updates
- Day 3-4: Blade template refactor  
- Day 5-6: Icon integration implementation
- Day 7: Initial testing and bug fixes

### Phase 2: Enhanced Features (3-4 days)
- Day 1-2: Advanced color handling and hex support
- Day 3: Half-star functionality (if prioritized)
- Day 4: Accessibility enhancements

### Phase 3: Testing & Documentation (3-4 days)
- Day 1-2: Comprehensive test suite  
- Day 3: Documentation updates
- Day 4: Migration guide and examples

**Total Estimated Time: 11-15 days**

## Conclusion

This refactor represents a significant improvement to the Rating component's developer experience while implementing the documented API that users expect. The phased approach minimizes risk while ensuring a smooth transition path for existing users.

The key benefits include:
- **Simplified API**: Props instead of CSS class manipulation
- **Better Integration**: Leverage existing icon and color systems
- **Enhanced Functionality**: Full feature set as documented
- **Improved Maintainability**: Centralized logic in component class
- **Future-Proof**: Extensible architecture for additional features

This plan provides a roadmap for transforming the Rating component from a basic implementation into a fully-featured, production-ready component that matches the quality and capabilities of other components in the ArtisanPack UI ecosystem.
