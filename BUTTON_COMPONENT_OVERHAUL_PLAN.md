# Button Component Overhaul Plan

## Overview

This document outlines a comprehensive plan to overhaul the Button component in the ArtisanPack UI Livewire UI Components package. The goal is to ensure that the button component has complete support for variant, size, color, and color adjustment attributes, along with improved hover styles that maintain proper color contrast.

**Branch:** `update/button-component`
**Version:** 0.6.0 → 0.7.0
**Component Path:** `src/View/Components/Button.php`
**View Path:** `resources/views/components/button.blade.php`
**Documentation:** `docs/components/button.md`
**Tests:** `tests/Unit/Components/ButtonComponentTest.php`

---

## Current State Analysis

### Existing Implementation

#### 1. **Variant Attribute** ✅ Partially Implemented
- **Current Status:** Implemented in the Button component
- **Location:** `src/View/Components/Button.php:81-132`
- **Supported Values:** `primary`, `secondary`, `accent`, `success`, `warning`, `error`, `ghost`, `outline`
- **Implementation Details:**
  - Uses `validateVariant()` method to ensure valid variants
  - Falls back to `primary` if invalid variant provided
  - Uses `getVariantClasses()` method for backward compatibility with DaisyUI
  - Variants map to DaisyUI classes (e.g., `btn-primary`, `btn-secondary`)

#### 2. **Size Attribute** ❌ NOT Implemented
- **Current Status:** Documented but not implemented
- **Documentation Location:** `docs/components/button.md:75-83`
- **Expected Values:** `xs`, `sm`, `md`, `lg`, `xl`
- **Issue:**
  - Documentation shows size examples, but the Button component class has no `$size` property
  - DaisyUI supports button sizes through classes: `btn-xs`, `btn-sm`, `btn-md`, `btn-lg`
  - Other components (Heading, Pagination, Rating) have size attributes implemented
  - Current workaround: Users must manually add size classes via the `class` attribute

#### 3. **Color Attribute** ✅ Fully Implemented
- **Current Status:** Fully implemented with ColorGenerator integration
- **Location:** `src/View/Components/Button.php:82, 92-100, 140-157`
- **Supported Values:**
  - Predefined variants (primary, secondary, accent, success, warning, error, info, neutral, ghost, outline)
  - Tailwind color names (red, blue, green, etc.)
  - Tailwind colors with intensity (red-500, blue-600, etc.)
  - Custom hex colors (#ff6b6b, #4ecdc4, etc.)
- **Implementation Details:**
  - Uses `ColorGenerator::resolveComponentColor()` method
  - Priority: `color` prop takes precedence over `variant`
  - Generates appropriate CSS classes for backgrounds, borders, and text
  - Uses CSS custom properties for JIT compatibility
  - Automatically calculates contrasting text colors

#### 4. **Color Adjustment Attribute** ✅ Fully Implemented
- **Current Status:** Fully implemented via ColorGenerator
- **Location:** `src/View/Components/Button.php:83` and `src/Styling/ColorGenerator.php:362-406`
- **Supported Values:** `lighter`, `darker`, `transparent`, `subtle`
- **Implementation Details:**
  - `lighter`: Reduces color intensity by 400 stops (e.g., 500 → 100)
  - `darker`: Increases color intensity by 200 stops (e.g., 500 → 700)
  - `transparent`: Removes background color
  - `subtle`: Sets background to lightest shade (50)
  - Works with all color types (variants, Tailwind colors, hex codes)

### ColorGenerator Analysis

#### Hover State Implementation
- **Location:** `src/Styling/ColorGenerator.php:507-564, 595-626, 636-671`
- **Current Behavior:**
  - Generates hover/focus states for all color types
  - Uses CSS custom properties (`--artisanpack-variant-hover-color`, `--artisanpack-tailwind-hover-color`, `--artisanpack-custom-hover-color`)
  - For Tailwind colors: Increases intensity by 100 (e.g., 500 → 600)
  - For hex colors: Reduces brightness by 20%
  - Applies hover classes: `hover:[background-color:var(--custom-property)]`

#### Color Contrast Handling
- **Location:** `src/Styling/ColorGenerator.php:823-842`
- **Method:** `getContrastingTextForHex()`
- **Current Implementation:**
  - Uses brightness calculation: `(R*299 + G*587 + B*114) / 1000`
  - Threshold: 128 (brightness > 128 = black text, ≤ 128 = white text)
  - Applies to base colors but not hover states
- **Issue:** Hover state text color may break contrast when background darkens

### DaisyUI Button Classes Reference

DaisyUI provides the following button classes that should be supported:

**Size Classes:**
- `btn-xs` - Extra small
- `btn-sm` - Small
- `btn-md` - Medium (default)
- `btn-lg` - Large

**Variant Classes:**
- `btn-primary`, `btn-secondary`, `btn-accent`
- `btn-success`, `btn-warning`, `btn-error`, `btn-info`
- `btn-neutral`, `btn-ghost`, `btn-outline`

**Additional Modifiers:**
- `btn-circle` - Circular button (already supported via class attribute)
- `btn-square` - Square button (already supported via class attribute)
- `btn-wide` - Wider button
- `btn-block` - Full width

---

## Requirements & Objectives

### Primary Goals

1. **✅ Variant Attribute**
   - Maintain existing variant support
   - Ensure backward compatibility with DaisyUI variant classes
   - Validate all variant values

2. **🆕 Size Attribute**
   - Add `size` property to Button component
   - Support: `xs`, `sm`, `md`, `lg`, `xl`
   - Default to `md` when not specified
   - Generate appropriate DaisyUI size classes

3. **✅ Color Attribute**
   - Maintain existing color support
   - Continue supporting variants, Tailwind colors, and hex codes
   - Ensure ColorGenerator integration works correctly

4. **✅ Color Adjustment Attribute**
   - Maintain existing color adjustment support
   - Ensure all adjustment types work with hover states

5. **🔧 Hover Styles Enhancement**
   - Improve hover styles for `primary`, `secondary`, and `accent` variants
   - Add smooth transitions
   - Ensure hover background colors maintain proper color contrast
   - Consider implementing accessible hover states that meet WCAG AA standards

### Secondary Goals

1. **Documentation Updates**
   - Update `docs/components/button.md` to reflect all implemented features
   - Add comprehensive examples for all attributes
   - Document color contrast considerations
   - Include accessibility best practices

2. **Test Coverage**
   - Add tests for size attribute
   - Update existing tests for variant, color, and color adjustment
   - Add tests for hover state generation
   - Add accessibility tests for color contrast

3. **Performance**
   - Ensure efficient CSS class generation
   - Minimize inline styles where possible
   - Leverage Tailwind JIT for optimal bundle size

---

## Implementation Plan

### Phase 1: Add Size Attribute Support

**Files to Modify:**
- `src/View/Components/Button.php`
- `resources/views/components/button.blade.php`

**Implementation Steps:**

1. **Add size property to Button component**
   - Location: `src/View/Components/Button.php:65-86`
   - Add parameter: `public ?string $size = 'md'`
   - Add to constructor docblock

2. **Create size validation method**
   ```php
   /**
    * Validate and return a supported size.
    *
    * @param string|null $size
    * @return string
    * @since 0.7.0
    */
   private function validateSize(?string $size): string
   {
       $supportedSizes = ['xs', 'sm', 'md', 'lg', 'xl'];
       return in_array($size, $supportedSizes) ? $size : 'md';
   }
   ```

3. **Create size class method**
   ```php
   /**
    * Get size-specific CSS classes.
    *
    * @return string
    * @since 0.7.0
    */
   public function getSizeClasses(): string
   {
       $validatedSize = $this->validateSize($this->size);

       return match($validatedSize) {
           'xs' => 'btn-xs',
           'sm' => 'btn-sm',
           'md' => 'btn-md',
           'lg' => 'btn-lg',
           'xl' => 'btn-lg', // DaisyUI doesn't have btn-xl, use btn-lg
           default => 'btn-md',
       };
   }
   ```

4. **Update button blade template**
   - Location: `resources/views/components/button.blade.php:11`
   - Add size class to `$baseClasses` array
   - Example: `$baseClasses[] = $getSizeClasses();`

**Expected Outcome:**
- Users can specify button size: `<x-artisanpack-button size="lg">Large Button</x-artisanpack-button>`
- Proper DaisyUI size class applied
- Default to `md` size when not specified

---

### Phase 2: Enhance Hover Styles with Color Contrast

**Files to Modify:**
- `src/Styling/ColorGenerator.php`

**Current Issues:**
1. Hover states darken background but don't recalculate text color
2. This can cause contrast issues (dark background + black text)
3. Need to ensure WCAG AA compliance (4.5:1 contrast ratio for normal text)

**Implementation Steps:**

1. **Enhance `addVariantHoverFocusStates()` method**
   - Location: `src/Styling/ColorGenerator.php:516-564`
   - Add text color recalculation for hover states
   - Ensure contrasting text color is applied on hover

   ```php
   protected function addVariantHoverFocusStates(array &$classes, string $variant): void
   {
       // ... existing code ...

       if ($hoverHex) {
           $classes['style'] = isset($classes['style'])
               ? $classes['style'] . " {$hoverProperty}: {$hoverHex};"
               : "{$hoverProperty}: {$hoverHex};";
           $classes['hover'] = 'hover:[background-color:var(--artisanpack-variant-hover-color)]';

           // Add contrasting text color for hover state
           $hoverTextColor = $this->getContrastingTextForHex($hoverHex);
           $hoverTextProperty = '--artisanpack-variant-hover-text';
           $classes['style'] .= " {$hoverTextProperty}: " . ($hoverTextColor === 'text-black' ? '#000000' : '#ffffff') . ";";
           $classes['hover'] .= ' hover:[color:var(--artisanpack-variant-hover-text)]';
       }
   }
   ```

2. **Update `getTailwindClasses()` hover generation**
   - Location: `src/Styling/ColorGenerator.php:604-618`
   - Add text color calculation for hover states
   - Similar approach as variants

3. **Update `getHexClasses()` hover generation**
   - Location: `src/Styling/ColorGenerator.php:652-663`
   - Ensure text color updates on hover
   - Calculate contrast for adjusted hex colors

4. **Add transition classes**
   - Update button blade template to include smooth transitions
   - Add to base classes: `'transition-colors', 'duration-200', 'ease-in-out'`

5. **Special handling for primary, secondary, accent variants**
   - Ensure these three variants have optimized hover states
   - Consider slightly different hover behavior (e.g., primary might have more pronounced effect)

**Expected Outcome:**
- Hover background colors automatically darken/lighten appropriately
- Text color automatically adjusts to maintain contrast
- Smooth transitions between states
- WCAG AA compliance maintained

---

### Phase 3: Testing & Validation

**Files to Modify/Create:**
- `tests/Unit/Components/ButtonComponentTest.php`

**Test Cases to Add:**

1. **Size Attribute Tests**
   ```php
   public function test_button_size_property(): void
   {
       $sizes = ['xs', 'sm', 'md', 'lg', 'xl'];

       foreach ($sizes as $size) {
           $component = $this->createComponent(['size' => $size]);
           $this->assertEquals($size, $component->size);
       }
   }

   public function test_button_size_validation(): void
   {
       $component = $this->createComponent(['size' => 'invalid']);
       $this->assertEquals('btn-md', $component->getSizeClasses());
   }

   public function test_button_renders_with_size_classes(): void
   {
       $component = $this->createComponent(['size' => 'lg', 'label' => 'Test']);
       $html = $component->render()->render();
       $this->assertStringContainsString('btn-lg', $html);
   }
   ```

2. **Color Contrast Tests**
   ```php
   public function test_button_hover_maintains_contrast(): void
   {
       $component = $this->createComponent(['color' => 'primary']);
       $colorClasses = $component->getColorClasses();

       // Verify hover state exists
       $this->assertArrayHasKey('hover', $colorClasses);
       $this->assertArrayHasKey('style', $colorClasses);

       // Verify hover text color is defined
       $this->assertStringContainsString('--artisanpack-variant-hover-text', $colorClasses['style']);
   }
   ```

3. **Integration Tests**
   - Test combinations: size + color + variant
   - Test size + color-adjustment
   - Ensure all attributes work together

4. **Accessibility Tests**
   - Verify color contrast ratios
   - Test keyboard navigation
   - Test screen reader compatibility

**Expected Outcome:**
- 100% test coverage for new size functionality
- Validated color contrast compliance
- All existing tests continue to pass

---

### Phase 4: Documentation Updates

**Files to Modify:**
- `docs/components/button.md`

**Updates Required:**

1. **Size Section**
   - ✅ Already documented (lines 75-83)
   - Verify examples match implementation
   - Add note about `xl` mapping to `lg`

2. **Color Section**
   - Enhance explanation of color priority (color > variant)
   - Add examples showing color with size
   - Document hover behavior

3. **Color Adjustment Section**
   - Add examples with hover states
   - Document contrast considerations

4. **Props Table**
   - Update size prop with proper default value
   - Add notes about hover behavior for color variants

5. **Accessibility Section**
   - Add section on color contrast
   - Document hover state accessibility
   - Include WCAG compliance notes

6. **Examples Section**
   - Add combination examples:
     ```blade
     <x-artisanpack-button size="lg" color="primary">
         Large Primary Button
     </x-artisanpack-button>

     <x-artisanpack-button size="sm" color="red-500" color-adjustment="lighter">
         Small Light Red
     </x-artisanpack-button>
     ```

**Expected Outcome:**
- Complete, accurate documentation
- Helpful examples for all use cases
- Clear accessibility guidelines

---

### Phase 5: Backward Compatibility & Migration

**Considerations:**

1. **Variant Attribute**
   - ✅ No breaking changes
   - Continue supporting both `variant` and `color` props
   - `color` takes precedence for new implementations

2. **Size Attribute**
   - ✅ No breaking changes (new feature)
   - Default to `md` maintains current appearance
   - Users who manually added size classes will see no change

3. **Existing Color Usage**
   - ✅ No breaking changes
   - Hover state enhancements are progressive improvements
   - Existing color implementations continue to work

4. **CSS Class Application**
   - Ensure custom classes via `class` attribute still work
   - Proper class merging in blade template
   - No conflicts with DaisyUI classes

**Expected Outcome:**
- Zero breaking changes
- Seamless upgrade path
- Enhanced functionality for new implementations

---

## Technical Specifications

### Component Architecture

**Button.php Structure:**
```
Button Component
├── Properties
│   ├── $id (nullable string)
│   ├── $label (nullable string)
│   ├── $icon (nullable string)
│   ├── $iconRight (nullable string)
│   ├── $spinner (nullable string)
│   ├── $link (nullable string)
│   ├── $external (boolean)
│   ├── $noWireNavigate (boolean)
│   ├── $responsive (boolean)
│   ├── $badge (nullable string)
│   ├── $badgeClasses (nullable string)
│   ├── $tooltip (nullable string)
│   ├── $tooltipLeft (nullable string)
│   ├── $tooltipRight (nullable string)
│   ├── $tooltipBottom (nullable string)
│   ├── $variant (string, default: 'primary')
│   ├── $color (nullable string)
│   ├── $colorAdjustment (nullable string)
│   ├── $size (string, default: 'md') ← NEW
│   ├── $uuid (string)
│   ├── $tooltipPosition (string)
│   └── $resolvedColor (nullable string)
├── Methods
│   ├── __construct()
│   ├── validateVariant() (private)
│   ├── validateSize() (private) ← NEW
│   ├── getColorClasses() (public)
│   ├── getVariantClasses() (public)
│   ├── getSizeClasses() (public) ← NEW
│   ├── getFallbackVariantClasses() (protected)
│   ├── spinnerTarget() (public)
│   └── render() (public)
```

### ColorGenerator Enhancements

**Enhanced Hover State Methods:**
```
ColorGenerator
├── addVariantHoverFocusStates()
│   ├── Generate hover hex color (intensity + 100)
│   ├── Calculate contrasting text color for hover
│   ├── Set CSS custom properties
│   └── Apply hover classes
├── getTailwindClasses()
│   ├── Convert Tailwind color to hex
│   ├── Generate hover state (+100 intensity)
│   ├── Calculate hover text contrast
│   └── Return complete class array
└── getHexClasses()
    ├── Use hex color directly
    ├── Adjust brightness for hover (-20%)
    ├── Calculate hover text contrast
    └── Return complete class array
```

### CSS Class Generation Flow

```
1. User specifies attributes:
   <x-artisanpack-button size="lg" color="primary">

2. Component constructor:
   - Validates size → 'lg'
   - Validates variant → 'primary'
   - Resolves color → 'primary'

3. Class generation in blade template:
   - Base classes: ['btn', '!inline-flex', 'transition-colors', 'duration-200']
   - Color classes: getColorClasses() → ['bg' => 'bg-primary', 'text' => 'text-primary-content', ...]
   - Size classes: getSizeClasses() → 'btn-lg'
   - Hover classes: From ColorGenerator

4. Final output:
   <button class="btn !inline-flex transition-colors duration-200 bg-primary text-primary-content hover:[background-color:var(--artisanpack-variant-hover-color)] hover:[color:var(--artisanpack-variant-hover-text)] btn-lg">
```

---

## Color Contrast Strategy

### Current Contrast Calculation

**Brightness Formula:**
```php
brightness = (R * 299 + G * 587 + B * 114) / 1000
threshold = 128
text_color = brightness > 128 ? 'black' : 'white'
```

### WCAG AA Requirements

- **Normal text:** 4.5:1 contrast ratio
- **Large text (18pt/14pt bold+):** 3:1 contrast ratio
- **UI components:** 3:1 contrast ratio

### Enhanced Contrast Strategy

**For Base Colors:**
1. Calculate brightness
2. Apply threshold-based text color selection
3. Store base text color

**For Hover States:**
1. Generate hover background color (darker)
2. Recalculate brightness for hover color
3. Apply threshold-based text color selection
4. Store hover text color in CSS custom property
5. Apply via hover pseudo-class

**Special Cases:**
- **Transparent/Ghost:** No background, use current text color
- **Outline:** Transparent background, border uses color
- **Lighter adjustment:** May need dark text
- **Darker adjustment:** May need light text

### Accessibility Integration

The component should integrate with ArtisanPack Accessibility package:
- Use `A11y` facade for contrast validation (if available)
- Provide warnings for low-contrast combinations
- Suggest alternative colors when contrast is insufficient

---

## Potential Issues & Mitigations

### Issue 1: Size XL Not in DaisyUI

**Problem:** DaisyUI only supports xs, sm, md, lg (no xl)

**Solution:**
- Map `xl` to `btn-lg`
- Document this mapping
- Consider using custom CSS for true xl size in future

**Alternative:** Add custom `btn-xl` class to project's CSS

### Issue 2: Hover Text Color Complexity

**Problem:** Adding hover text colors increases CSS complexity

**Solution:**
- Use CSS custom properties to minimize inline styles
- Apply classes conditionally only when needed
- Leverage Tailwind JIT for optimal output

### Issue 3: ColorAdjustment + Hover Interaction

**Problem:** Adjusted colors (lighter, darker, subtle) need appropriate hover states

**Solution:**
- Recalculate hover colors based on adjusted base color
- Ensure hover states work for all adjustment types
- Test all combinations thoroughly

### Issue 4: Performance with Many Buttons

**Problem:** Each button with custom colors generates inline styles

**Solution:**
- Use CSS custom properties to share values
- Minimize unique color combinations
- Consider caching color calculations

### Issue 5: Transition Conflicts

**Problem:** Existing custom transitions might conflict with new transition classes

**Solution:**
- Use Tailwind's `transition-colors` to only transition colors
- Allow users to override with custom transition classes
- Document how to customize transitions

---

## Testing Strategy

### Unit Tests

1. **Size Attribute**
   - Valid sizes (xs, sm, md, lg, xl)
   - Invalid size fallback
   - Default size behavior

2. **Variant Attribute**
   - All supported variants
   - Invalid variant fallback
   - Variant validation

3. **Color Attribute**
   - Predefined variants
   - Tailwind colors
   - Tailwind colors with intensity
   - Hex colors
   - Invalid colors

4. **Color Adjustment**
   - All adjustment types
   - Adjustment with different color types
   - Invalid adjustment handling

5. **Class Generation**
   - getSizeClasses()
   - getColorClasses()
   - getVariantClasses()
   - Complete class array structure

### Integration Tests

1. **Attribute Combinations**
   - size + color
   - size + variant
   - color + colorAdjustment
   - All attributes together

2. **Rendering Tests**
   - Correct HTML output
   - Proper class application
   - CSS custom properties in style attribute

3. **Browser Tests (if applicable)**
   - Visual regression testing
   - Hover state rendering
   - Accessibility tree validation

### Accessibility Tests

1. **Color Contrast**
   - Base color contrast ratios
   - Hover color contrast ratios
   - WCAG AA compliance

2. **Keyboard Navigation**
   - Focus states
   - Tab order
   - Enter/Space activation

3. **Screen Reader**
   - Proper button semantics
   - State announcements
   - Label accessibility

---

## Success Criteria

### Functional Requirements

- ✅ Size attribute accepts xs, sm, md, lg, xl
- ✅ Size attribute defaults to md
- ✅ Invalid sizes fall back to md
- ✅ Variant attribute continues to work as expected
- ✅ Color attribute supports all documented types
- ✅ Color adjustment works with all color types
- ✅ Hover states are generated for all color types
- ✅ Text color adjusts on hover to maintain contrast

### Non-Functional Requirements

- ✅ No breaking changes to existing implementations
- ✅ All existing tests pass
- ✅ New tests added for size functionality
- ✅ Documentation updated and accurate
- ✅ Performance impact < 5ms per button render
- ✅ WCAG AA compliance for all color combinations

### User Experience

- ✅ Intuitive API for size specification
- ✅ Clear documentation with examples
- ✅ Helpful error messages for invalid values
- ✅ Smooth, accessible hover transitions
- ✅ Consistent behavior across all browsers

---

## Timeline & Milestones

### Phase 1: Size Attribute (Estimated: 2-3 hours)
- [ ] Add size property to Button.php
- [ ] Create validateSize() method
- [ ] Create getSizeClasses() method
- [ ] Update button.blade.php template
- [ ] Manual testing of size classes

### Phase 2: Hover Enhancements (Estimated: 3-4 hours)
- [ ] Update addVariantHoverFocusStates()
- [ ] Update getTailwindClasses()
- [ ] Update getHexClasses()
- [ ] Add transition classes to template
- [ ] Manual testing of hover states and contrast

### Phase 3: Testing (Estimated: 2-3 hours)
- [ ] Write size attribute tests
- [ ] Write color contrast tests
- [ ] Write integration tests
- [ ] Run full test suite
- [ ] Fix any failing tests

### Phase 4: Documentation (Estimated: 1-2 hours)
- [ ] Update button.md
- [ ] Add/update examples
- [ ] Document accessibility considerations
- [ ] Review for accuracy

### Phase 5: Review & Polish (Estimated: 1-2 hours)
- [ ] Code review
- [ ] Manual testing in demo app
- [ ] Browser testing
- [ ] Performance validation
- [ ] Final adjustments

**Total Estimated Time:** 9-14 hours

---

## Future Enhancements (Out of Scope)

1. **Custom Size Values**
   - Allow custom size classes beyond xs-xl
   - Example: `size="custom" class="h-20 w-20"`

2. **Advanced Hover Animations**
   - Ripple effects
   - Gradient shifts
   - Shadow animations

3. **Button Groups**
   - Compound button components
   - Button group wrapper component
   - Segmented control variant

4. **Loading States Enhancement**
   - Multiple loading animation types
   - Loading text customization
   - Progress indication

5. **Icon-Only Optimization**
   - Automatic circle variant for icon-only buttons
   - Square variant support
   - Size presets for icon buttons

6. **True XL Size**
   - Add custom btn-xl class to project CSS
   - Define appropriate sizing values
   - Update component to use true xl

---

## References

### DaisyUI Documentation
- [Button Component](https://daisyui.com/components/button/)
- [Color Themes](https://daisyui.com/docs/colors/)
- [Customization](https://daisyui.com/docs/customize/)

### Tailwind CSS
- [JIT Mode](https://tailwindcss.com/docs/just-in-time-mode)
- [Arbitrary Values](https://tailwindcss.com/docs/adding-custom-styles#using-arbitrary-values)
- [Hover States](https://tailwindcss.com/docs/hover-focus-and-other-states)

### Accessibility
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Color Contrast Requirements](https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum.html)
- [Button Accessibility](https://www.w3.org/WAI/ARIA/apg/patterns/button/)

### Internal References
- `src/View/Components/Button.php` - Main component class
- `src/Styling/ColorGenerator.php` - Color resolution and class generation
- `resources/views/components/button.blade.php` - Blade template
- `docs/components/button.md` - Component documentation
- `tests/Unit/Components/ButtonComponentTest.php` - Unit tests

---

## Appendix A: Example Usage After Implementation

```blade
<!-- Basic size usage -->
<x-artisanpack-button size="xs">Extra Small</x-artisanpack-button>
<x-artisanpack-button size="sm">Small</x-artisanpack-button>
<x-artisanpack-button>Medium (Default)</x-artisanpack-button>
<x-artisanpack-button size="lg">Large</x-artisanpack-button>
<x-artisanpack-button size="xl">Extra Large</x-artisanpack-button>

<!-- Size with color -->
<x-artisanpack-button size="lg" color="primary">
    Large Primary
</x-artisanpack-button>

<!-- Size with variant (backward compatible) -->
<x-artisanpack-button size="sm" variant="secondary">
    Small Secondary
</x-artisanpack-button>

<!-- Size with Tailwind color -->
<x-artisanpack-button size="md" color="purple-600">
    Medium Purple
</x-artisanpack-button>

<!-- Size with hex color -->
<x-artisanpack-button size="lg" color="#ff6b6b">
    Large Custom Red
</x-artisanpack-button>

<!-- Size with color adjustment -->
<x-artisanpack-button size="sm" color="blue-500" color-adjustment="lighter">
    Small Light Blue
</x-artisanpack-button>

<!-- All attributes together -->
<x-artisanpack-button
    size="lg"
    color="accent"
    icon="o-star"
    tooltip="Featured action"
    wire:click="doSomething">
    Featured Button
</x-artisanpack-button>

<!-- Enhanced hover with automatic contrast -->
<x-artisanpack-button color="yellow-300">
    <!-- Base: yellow-300 background with black text -->
    <!-- Hover: yellow-400 background with black text (maintained contrast) -->
    Hover Me
</x-artisanpack-button>

<x-artisanpack-button color="blue-700">
    <!-- Base: blue-700 background with white text -->
    <!-- Hover: blue-800 background with white text (maintained contrast) -->
    Hover Me Too
</x-artisanpack-button>
```

---

## Appendix B: CSS Output Examples

### Small Primary Button
```html
<button
    class="btn !inline-flex transition-colors duration-200 bg-primary text-primary-content hover:[background-color:var(--artisanpack-variant-hover-color)] hover:[color:var(--artisanpack-variant-hover-text)] btn-sm"
    style="--artisanpack-variant-hover-color: #2563eb; --artisanpack-variant-hover-text: #ffffff;">
    Small Primary
</button>
```

### Large Custom Color Button
```html
<button
    class="btn !inline-flex transition-colors duration-200 [background-color:var(--artisanpack-custom-color)] text-white hover:[background-color:var(--artisanpack-custom-hover-color)] hover:[color:var(--artisanpack-custom-hover-text)] btn-lg"
    style="--artisanpack-custom-color: #ff6b6b; --artisanpack-custom-hover-color: #cc5656; --artisanpack-custom-hover-text: #ffffff;">
    Large Custom
</button>
```

### Medium Tailwind Color with Adjustment
```html
<button
    class="btn !inline-flex transition-colors duration-200 [background-color:var(--artisanpack-tailwind-color)] text-black hover:[background-color:var(--artisanpack-tailwind-hover-color)] hover:[color:var(--artisanpack-tailwind-hover-text)] btn-md"
    style="--artisanpack-tailwind-color: #dbeafe; --artisanpack-tailwind-hover-color: #bfdbfe; --artisanpack-tailwind-hover-text: #000000;">
    Medium Light Blue
</button>
```

---

## Sign-off

**Plan Created By:** Claude (AI Assistant)
**Date:** 2025-11-08
**Reviewed By:** [Pending]
**Approved By:** [Pending]

**Next Steps:**
1. Review this plan with the development team
2. Adjust timeline and priorities as needed
3. Begin implementation of Phase 1
4. Track progress against success criteria
