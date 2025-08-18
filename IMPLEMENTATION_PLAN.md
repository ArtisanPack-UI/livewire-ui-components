# ImageGallery Component Refactoring Implementation Plan

## Overview

This document outlines the plan to refactor the current `ImageGallery` component into two distinct components:
1. **ImageSlider** - A carousel/slider component for sequential image navigation
2. **ImageGallery** - A grid-based component for displaying images in configurable layouts

## Current State Analysis

### Existing ImageGallery Component

**Files:**
- `src/View/Components/ImageGallery.php`
- `resources/views/components/image-gallery.blade.php`
- `docs/components/image-gallery.md`

**Current Features:**
- Displays images in a carousel format using DaisyUI classes
- PhotoSwipe lightbox integration for full-screen viewing
- Props: `images` (array), `id` (string|null), `withArrows` (bool|null), `withIndicators` (bool|null)
- Uses Alpine.js for initialization
- Single-column layout with `carousel` and `carousel-item` classes

**Issues Identified:**
- The `withArrows` and `withIndicators` props are defined but not implemented in the template
- Current implementation is more of a basic carousel than a true image gallery
- No responsive grid capabilities
- No tests exist for the component

## Implementation Strategy

### Phase 1: Create ImageSlider Component

#### 1.1 Component Structure
```
src/View/Components/ImageSlider.php
resources/views/components/image-slider.blade.php  
docs/components/image-slider.md
tests/Feature/ImageSliderTest.php
```

#### 1.2 ImageSlider Features
- **Navigation Controls**: Previous/Next arrows with proper accessibility
- **Indicators**: Dot indicators showing current position and total count
- **Auto-play**: Optional automatic progression with configurable timing
- **Touch/Swipe Support**: Mobile-friendly touch navigation
- **Keyboard Navigation**: Arrow key support for accessibility
- **Lazy Loading**: Performance optimization for large image sets
- **Responsive Design**: Adapts to different screen sizes

#### 1.3 ImageSlider Props
```php
public function __construct(
    public array $images,                    // Required: Array of image URLs
    public ?string $id = null,              // Optional: Custom ID
    public bool $withArrows = true,         // Show navigation arrows
    public bool $withIndicators = true,     // Show position indicators
    public bool $autoPlay = false,          // Enable auto-progression
    public int $autoPlayInterval = 5000,    // Auto-play interval in ms
    public bool $pauseOnHover = true,       // Pause auto-play on hover
    public bool $loop = true,               // Loop back to first after last
    public string $transition = 'slide',    // Transition type: slide|fade
    public int $transitionDuration = 300,   // Transition duration in ms
    public bool $showCounter = false,       // Show "X of Y" counter
    public bool $enableLightbox = true,     // Enable PhotoSwipe lightbox
    public string $aspectRatio = '16:9'     // Image container aspect ratio
)
```

#### 1.4 ImageSlider Template Structure
```html
<div x-data="imageSlider({
    autoPlay: {{ $autoPlay ? 'true' : 'false' }},
    interval: {{ $autoPlayInterval }},
    loop: {{ $loop ? 'true' : 'false' }}
})" class="relative">
    <!-- Image Container -->
    <div class="relative overflow-hidden rounded-lg">
        <!-- Images -->
        <div class="flex transition-transform duration-{{ $transitionDuration }}">
            <!-- Individual image slides -->
        </div>
        
        <!-- Loading State -->
        <div x-show="loading" class="absolute inset-0 bg-gray-100 animate-pulse">
            <!-- Loading placeholder -->
        </div>
    </div>
    
    <!-- Navigation Arrows -->
    <template x-if="{{ $withArrows ? 'true' : 'false' }}">
        <!-- Previous/Next buttons -->
    </template>
    
    <!-- Indicators -->
    <template x-if="{{ $withIndicators ? 'true' : 'false' }}">
        <!-- Dot indicators -->
    </template>
    
    <!-- Counter -->
    <template x-if="{{ $showCounter ? 'true' : 'false' }}">
        <!-- "X of Y" display -->
    </template>
</div>
```

### Phase 2: Create New ImageGallery Component

#### 2.1 Component Structure
```
src/View/Components/ImageGallery.php (replace existing)
resources/views/components/image-gallery.blade.php (replace existing)
docs/components/image-gallery.md (update existing)
tests/Feature/ImageGalleryTest.php (create new)
```

#### 2.2 ImageGallery Features
- **Responsive Grid Layout**: Configurable columns for different screen sizes
- **Aspect Ratio Control**: Consistent image sizing options
- **Gap Control**: Configurable spacing between images
- **Lightbox Integration**: PhotoSwipe integration maintained
- **Loading States**: Progressive image loading with placeholders
- **Masonry Option**: Optional masonry/Pinterest-style layout
- **Filtering**: Optional category/tag-based filtering
- **Infinite Scroll**: Optional pagination for large image sets

#### 2.3 ImageGallery Props
```php
public function __construct(
    public array $images,                           // Required: Array of image URLs or objects
    public ?string $id = null,                     // Optional: Custom ID
    public array $columns = [                      // Responsive columns
        'default' => 1,
        'sm' => 2, 
        'md' => 3, 
        'lg' => 4, 
        'xl' => 5
    ],
    public string $aspectRatio = 'square',         // square|landscape|portrait|auto
    public string $gap = 'md',                     // xs|sm|md|lg|xl
    public bool $enableLightbox = true,            // Enable PhotoSwipe lightbox
    public bool $showCaptions = false,             // Show image captions
    public string $layout = 'grid',                // grid|masonry
    public bool $lazyLoad = true,                  // Enable lazy loading
    public ?array $filters = null,                 // Optional category filters
    public int $itemsPerPage = 0,                  // 0 = no pagination
    public string $loadingStyle = 'skeleton'       // skeleton|spinner|fade
)
```

#### 2.4 ImageGallery Template Structure
```html
<div x-data="imageGallery({
    layout: '{{ $layout }}',
    lazyLoad: {{ $lazyLoad ? 'true' : 'false' }},
    lightbox: {{ $enableLightbox ? 'true' : 'false' }}
})" class="w-full">
    
    <!-- Filters (if enabled) -->
    @if($filters)
        <div class="mb-6">
            <!-- Filter buttons -->
        </div>
    @endif
    
    <!-- Gallery Grid -->
    <div class="
        grid gap-{{ $gap }}
        @foreach($columns as $breakpoint => $cols)
            @if($breakpoint === 'default')
                grid-cols-{{ $cols }}
            @else
                {{ $breakpoint }}:grid-cols-{{ $cols }}
            @endif
        @endforeach
        @if($layout === 'masonry')
            auto-rows-max
        @endif
    ">
        @foreach($images as $index => $image)
            <div class="
                group relative overflow-hidden rounded-lg
                @if($aspectRatio !== 'auto')
                    aspect-{{ $aspectRatio === 'square' ? 'square' : ($aspectRatio === 'landscape' ? '[4/3]' : '[3/4]') }}
                @endif
            ">
                <!-- Image -->
                <img 
                    src="{{ is_array($image) ? $image['url'] : $image }}"
                    alt="{{ is_array($image) ? ($image['alt'] ?? '') : '' }}"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    @if($lazyLoad && $index > 8)
                        loading="lazy"
                    @endif
                />
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300">
                    <!-- Lightbox trigger -->
                    @if($enableLightbox)
                        <button class="absolute inset-0 w-full h-full">
                            <span class="sr-only">View image {{ $index + 1 }}</span>
                        </button>
                    @endif
                </div>
                
                <!-- Caption -->
                @if($showCaptions && is_array($image) && isset($image['caption']))
                    <div class="absolute bottom-0 left-0 right-0 bg-black/75 text-white p-2">
                        <p class="text-sm">{{ $image['caption'] }}</p>
                    </div>
                @endif
                
                <!-- Loading Placeholder -->
                <div x-show="!imageLoaded" class="absolute inset-0 bg-gray-200 animate-pulse">
                    <!-- Skeleton loader -->
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination (if enabled) -->
    @if($itemsPerPage > 0)
        <div class="mt-8">
            <!-- Pagination controls -->
        </div>
    @endif
</div>
```

### Phase 3: Migration Strategy

#### 3.1 Backward Compatibility Approach
1. **Deprecation Period**: Keep the old ImageGallery temporarily with deprecation warnings
2. **Migration Guide**: Provide clear documentation for updating existing usage
3. **Automatic Detection**: Detect old usage patterns and suggest new component

#### 3.2 Breaking Changes
- The current `ImageGallery` will become `ImageSlider`
- New `ImageGallery` will have different props and behavior
- Grid layout becomes the primary focus instead of carousel

#### 3.3 Migration Steps
1. Create new `ImageSlider` component with current functionality
2. Update existing `ImageGallery` to grid-based implementation
3. Add deprecation warnings to guide users
4. Update documentation with migration examples
5. Update service provider registrations

### Phase 4: Testing Strategy

#### 4.1 ImageSlider Tests
```php
// tests/Feature/ImageSliderTest.php
test('renders with basic images')
test('shows navigation arrows when enabled')
test('shows indicators when enabled')
test('handles empty images array')
test('generates unique IDs')
test('applies custom CSS classes')
test('enables auto-play with correct interval')
test('renders accessibility attributes')
```

#### 4.2 ImageGallery Tests
```php
// tests/Feature/ImageGalleryTest.php  
test('renders grid layout with default columns')
test('applies responsive column classes')
test('handles different aspect ratios')
test('shows captions when enabled')
test('renders filter buttons')
test('handles lazy loading')
test('applies gap spacing correctly')
test('works with image objects and URLs')
```

### Phase 5: Documentation Updates

#### 5.1 New Documentation Structure
```
docs/components/image-slider.md     # New slider documentation
docs/components/image-gallery.md    # Updated gallery documentation
docs/migration/image-gallery.md     # Migration guide
```

#### 5.2 Migration Guide Content
- Clear before/after examples
- Props mapping between old and new components
- Common use case conversions
- Styling migration tips

### Phase 6: Performance Considerations

#### 6.1 ImageSlider Optimizations
- Lazy load images outside viewport
- Preload next/previous images
- Optimize Alpine.js bundle size
- Use CSS transforms for smooth animations

#### 6.2 ImageGallery Optimizations
- Intersection Observer for lazy loading
- Virtual scrolling for large datasets
- Image size optimization recommendations
- Progressive JPEG support

## Implementation Timeline

### Week 1: Foundation
- [ ] Create ImageSlider component
- [ ] Implement basic navigation and indicators
- [ ] Add PhotoSwipe integration
- [ ] Create basic tests

### Week 2: Enhancement
- [ ] Add auto-play functionality
- [ ] Implement touch/swipe support
- [ ] Add accessibility features
- [ ] Create comprehensive tests

### Week 3: Gallery Redesign  
- [ ] Create new ImageGallery component
- [ ] Implement responsive grid system
- [ ] Add filtering capabilities
- [ ] Create gallery tests

### Week 4: Integration & Documentation
- [ ] Update service provider
- [ ] Create migration documentation
- [ ] Update existing documentation
- [ ] Add backward compatibility layer

### Week 5: Testing & Refinement
- [ ] Comprehensive testing across devices
- [ ] Performance optimization
- [ ] Accessibility audit
- [ ] Final documentation review

## Risk Assessment

### High Risk
- **Breaking Changes**: Existing implementations will need updates
- **PhotoSwipe Integration**: Ensuring compatibility across both components
- **Performance**: Grid layouts with many images

### Medium Risk
- **Alpine.js Complexity**: Managing state across components  
- **Responsive Behavior**: Ensuring consistent experience across devices
- **Browser Compatibility**: Touch/swipe functionality

### Low Risk
- **Styling Conflicts**: DaisyUI class interactions
- **Documentation**: Keeping docs synchronized with implementation

## Success Criteria

1. **ImageSlider Component**:
   - ✅ Smooth navigation with arrows and indicators
   - ✅ Auto-play functionality with pause on hover
   - ✅ Touch/swipe support for mobile devices
   - ✅ Full accessibility compliance (WCAG 2.1 AA)
   - ✅ PhotoSwipe lightbox integration

2. **ImageGallery Component**:
   - ✅ Responsive grid with configurable columns
   - ✅ Multiple aspect ratio options
   - ✅ Lazy loading for performance
   - ✅ Optional filtering capabilities
   - ✅ PhotoSwipe lightbox integration

3. **Project Integration**:
   - ✅ Comprehensive test coverage (>90%)
   - ✅ Complete documentation with examples
   - ✅ Backward compatibility layer
   - ✅ Migration guide for existing users
   - ✅ Performance benchmarks met

## Conclusion

This implementation plan provides a comprehensive roadmap for transforming the current ImageGallery component into two specialized, feature-rich components. The ImageSlider will excel at sequential image presentation, while the new ImageGallery will provide flexible grid-based layouts for diverse use cases.

The phased approach ensures minimal disruption to existing users while providing clear migration paths and maintaining the high quality standards expected from the ArtisanPack UI component library.