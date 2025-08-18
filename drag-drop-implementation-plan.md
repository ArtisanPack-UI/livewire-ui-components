# Drag and Drop Implementation Plan for File and ImageLibrary Components

## Overview

This document outlines the implementation plan for adding drag and drop functionality to the File and ImageLibrary components in the ArtisanPack UI Livewire UI Components package. The drag and drop feature will allow users to drag files from their operating system directly onto the components to upload them, providing a more intuitive and modern user experience.

## Current State Analysis

### File Component
- **Location**: `src/View/Components/File.php` and `resources/views/components/file.blade.php`
- **Current Features**: File upload with preview, cropping using Cropper.js, progress tracking, validation
- **Alpine.js Usage**: Extensive use for file handling, progress tracking, and cropping functionality
- **Documentation Gap**: The documentation mentions `with-drag-drop` prop, but it's not implemented in the actual component

### ImageLibrary Component
- **Location**: `src/View/Components/ImageLibrary.php` and `resources/views/components/image-library.blade.php`
- **Current Features**: Multiple image management, sortable with Sortable.js, individual cropping, progress tracking
- **Alpine.js Usage**: Complex state management for multiple images, cropping, and sorting
- **Missing Feature**: No drag and drop functionality mentioned in documentation or implementation

## Implementation Strategy

### 1. User Experience Design

#### File Component Drag and Drop UX
- **Drop Zone**: The entire component area becomes a drop zone when files are dragged over
- **Visual Feedback**: 
  - Highlight border and background when files are dragged over
  - Show upload icon and "Drop files here" text overlay
  - Different visual states for valid vs invalid file types
- **Behavior**:
  - Single file mode: Replace existing file if present
  - Multiple file mode: Add to existing selection
  - Show progress indication during upload
  - Maintain existing validation and error handling

#### ImageLibrary Component Drag and Drop UX
- **Drop Zone**: The entire component area or the "Add images" button area
- **Visual Feedback**:
  - Highlight the drop zone with dotted border animation
  - Show image icons and "Drop images here" text
  - Visual indication of how many files are being dropped
- **Behavior**:
  - Add dropped images to the existing collection
  - Maintain sorting capability after drop
  - Show individual progress for each uploaded image
  - Integrate with existing cropping and management features

### 2. Technical Implementation Approach

#### Core Drag and Drop Events
Both components will handle these HTML5 drag and drop events:
- `dragenter`: File enters the drop zone
- `dragover`: File is being dragged over the drop zone
- `dragleave`: File leaves the drop zone
- `drop`: File is dropped on the component

#### Alpine.js Implementation Pattern
```javascript
{
    isDragOver: false,
    dragCounter: 0,
    
    initDragDrop() {
        // Initialize drag and drop event listeners
    },
    
    handleDragEnter(e) {
        // Prevent default and increment counter
        // Set isDragOver to true on first enter
    },
    
    handleDragOver(e) {
        // Prevent default to allow drop
        // Add visual feedback
    },
    
    handleDragLeave(e) {
        // Decrement counter and remove visual feedback when counter reaches 0
    },
    
    handleDrop(e) {
        // Process dropped files
        // Reset drag state
        // Trigger file upload
    },
    
    processDroppedFiles(files) {
        // Validate file types and sizes
        // Integrate with existing upload logic
    }
}
```

### 3. Component-Specific Implementation Details

#### File Component Changes

##### PHP Component Updates (`src/View/Components/File.php`)
- Add `withDragDrop` property (boolean, default false)
- Add `dragDropText` property (string, default "Drop files here")
- Add `dragDropClass` property for custom styling
- Update constructor to handle new properties

##### Blade View Updates (`resources/views/components/file.blade.php`)
- Add drag and drop event listeners when `withDragDrop` is enabled
- Implement visual feedback overlay
- Integrate dropped files with existing `refreshImage()` method
- Add conditional styling for drag states

##### Implementation Details
```php
// New properties in File component
public ?bool $withDragDrop = false,
public ?string $dragDropText = "Drop files here",
public ?string $dragDropClass = null,
```

```blade
{{-- Drag and drop wrapper --}}
@if($withDragDrop)
<div 
    x-data="{ 
        ...existing data,
        isDragOver: false,
        dragCounter: 0,
        initDragDrop() { /* implementation */ }
    }"
    x-init="initDragDrop()"
    @dragenter="handleDragEnter($event)"
    @dragover.prevent="handleDragOver($event)"
    @dragleave="handleDragLeave($event)"
    @drop.prevent="handleDrop($event)"
    :class="isDragOver && 'border-primary bg-primary/10'"
    class="relative border-2 border-dashed border-base-300 rounded-lg transition-all duration-200"
>
    {{-- Drag overlay --}}
    <div x-show="isDragOver" class="absolute inset-0 flex items-center justify-center bg-base-100/90 rounded-lg z-10">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-primary mb-2"><!-- Upload icon --></svg>
            <p class="text-lg font-medium text-primary">{{ $dragDropText }}</p>
        </div>
    </div>
    
    {{-- Existing component content --}}
    <!-- existing content -->
</div>
@endif
```

#### ImageLibrary Component Changes

##### PHP Component Updates (`src/View/Components/ImageLibrary.php`)
- Add `withDragDrop` property (boolean, default false)
- Add `dragDropText` property (string, default "Drop images here")
- Add drag and drop specific configuration options

##### Blade View Updates (`resources/views/components/image-library.blade.php`)
- Implement drag and drop on the entire component area
- Add visual feedback for multiple file drops
- Integrate with existing `refreshMediaSources()` method
- Handle multiple file processing

##### Implementation Details
```php
// New properties in ImageLibrary component
public ?bool $withDragDrop = false,
public ?string $dragDropText = "Drop images here",
public ?string $dragDropMultipleText = "Drop {count} images here",
```

```blade
{{-- Enhanced container with drag and drop --}}
<div 
    x-data="{
        ...existing data,
        isDragOver: false,
        dragCounter: 0,
        dragFileCount: 0,
        handleMultipleDrop(files) { /* process multiple files */ }
    }"
    @if($withDragDrop)
    @dragenter="handleDragEnter($event)"
    @dragover.prevent="handleDragOver($event)" 
    @dragleave="handleDragLeave($event)"
    @drop.prevent="handleDrop($event)"
    @endif
    :class="isDragOver && 'border-primary bg-primary/5'"
    class="relative {{ $withDragDrop ? 'border-2 border-dashed border-base-300' : '' }} rounded-lg transition-all duration-200"
>
    {{-- Multi-file drag overlay --}}
    @if($withDragDrop)
    <div x-show="isDragOver" class="absolute inset-0 flex items-center justify-center bg-base-100/90 rounded-lg z-10">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-primary mb-4"><!-- Images icon --></svg>
            <p class="text-xl font-medium text-primary" x-text="dragFileCount > 1 ? 'Drop ' + dragFileCount + ' images here' : '{{ $dragDropText }}'"></p>
        </div>
    </div>
    @endif
    
    {{-- Existing component content --}}
    <!-- existing content -->
</div>
```

### 4. Styling and Visual Feedback

#### Tailwind CSS Classes
- **Drop Zone Active**: `border-primary bg-primary/10 border-2 border-dashed`
- **Drop Zone Hover**: `border-primary/50 bg-primary/5`
- **Transition Effects**: `transition-all duration-200 ease-in-out`
- **Overlay**: `absolute inset-0 bg-base-100/90 backdrop-blur-sm`

#### Animation Considerations
- Smooth transitions for border and background color changes
- Subtle scale animations for drag feedback
- Fade in/out effects for overlay content
- Respect user's `prefers-reduced-motion` settings

### 5. Accessibility Implementation

#### Keyboard Navigation
- Maintain existing keyboard functionality
- Ensure drag and drop doesn't interfere with keyboard file selection
- Provide clear focus indicators

#### Screen Reader Support
- Add `aria-label` attributes for drag zones
- Include `role="button"` on clickable areas
- Provide alternative text for drag and drop actions
- Use `aria-live` regions for upload status announcements

#### Implementation
```blade
<div 
    role="button"
    aria-label="Drag and drop files here or click to browse"
    aria-describedby="drag-drop-instructions"
    tabindex="0"
    @keydown.enter="change()"
    @keydown.space.prevent="change()"
>
    <!-- component content -->
    <div id="drag-drop-instructions" class="sr-only">
        You can drag files directly onto this area to upload them, or click to browse for files.
    </div>
</div>
```

### 6. File Validation and Error Handling

#### Client-Side Validation
- Validate file types against `accept` attribute
- Check file sizes against `max-file-size` limits
- Provide immediate feedback for invalid files
- Show specific error messages for each validation failure

#### Error States
- Invalid file type: "File type not supported"
- File too large: "File size exceeds limit"
- Too many files: "Maximum number of files exceeded"
- Upload errors: Integrate with existing error handling

#### Implementation
```javascript
validateDroppedFiles(files) {
    const validFiles = [];
    const errors = [];
    
    Array.from(files).forEach(file => {
        if (!this.isValidFileType(file)) {
            errors.push(`${file.name}: File type not supported`);
            return;
        }
        
        if (!this.isValidFileSize(file)) {
            errors.push(`${file.name}: File size too large`);
            return;
        }
        
        validFiles.push(file);
    });
    
    return { validFiles, errors };
}
```

### 7. Integration with Existing Features

#### File Component Integration
- **Preview Functionality**: Update preview immediately when files are dropped
- **Cropping**: Trigger crop modal if `cropAfterChange` is enabled
- **Progress Tracking**: Use existing progress tracking system
- **Validation**: Integrate with existing Livewire validation

#### ImageLibrary Integration
- **Sorting**: Maintain Sortable.js functionality after new images are added
- **Individual Cropping**: Each dropped image should be croppable
- **Progress Tracking**: Show progress for each uploaded image
- **Media Management**: Integrate with existing `removeMedia` and `refreshMediaOrder` methods

### 8. Testing Strategy

#### Unit Tests
- Test drag and drop event handlers
- Validate file type and size checking
- Test integration with existing upload methods
- Test error handling scenarios

#### Integration Tests
- Test with Livewire component integration
- Verify proper file upload and processing
- Test accessibility features
- Test with different file types and sizes

#### Browser Testing
- Test across different browsers (Chrome, Firefox, Safari, Edge)
- Test on different operating systems
- Test touch devices and mobile browsers
- Verify drag and drop from various sources (desktop, file manager, other browser windows)

#### Test Implementation Examples
```php
// Feature test for File component drag and drop
test('file component handles drag and drop', function () {
    Livewire::test(FileUploadComponent::class)
        ->set('withDragDrop', true)
        ->call('handleDrop', [/* mock file data */])
        ->assertFileUploaded('document');
});

// Browser test for drag and drop functionality
test('drag and drop visual feedback works', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/upload')
                ->dragFile('#file-component', 'test-file.pdf')
                ->assertVisible('.drag-overlay')
                ->drop()
                ->waitFor('.upload-progress')
                ->assertSee('Upload complete');
    });
});
```

### 9. Performance Considerations

#### File Processing
- Implement debounced drag events to avoid excessive DOM updates
- Use requestAnimationFrame for smooth animations
- Lazy load drag and drop functionality only when needed
- Optimize file reading and preview generation

#### Memory Management
- Clean up event listeners when components are destroyed
- Dispose of file objects and blob URLs properly
- Limit the number of concurrent uploads
- Implement proper error recovery

### 10. Implementation Phases

#### Phase 1: Core Functionality
1. Add basic drag and drop event handling to both components
2. Implement visual feedback for drag states
3. Add file validation and error handling
4. Integrate with existing upload systems

#### Phase 2: Enhanced UX
1. Add advanced animations and transitions
2. Implement accessibility features
3. Add custom styling options
4. Enhance error messaging

#### Phase 3: Testing and Polish
1. Comprehensive testing across browsers and devices
2. Performance optimization
3. Documentation updates
4. Bug fixes and refinements

### 11. Backward Compatibility

- The drag and drop functionality will be opt-in via props (`withDragDrop`)
- Existing functionality will remain unchanged
- Default behavior will be preserved for components not using drag and drop
- Progressive enhancement approach ensures older browsers still work

### 12. Documentation Updates

#### Component Documentation
- Update File component docs to include implemented drag and drop examples
- Add ImageLibrary drag and drop examples
- Include accessibility guidelines
- Add troubleshooting section

#### API Documentation
- Document new component properties
- Include event handling examples
- Provide customization options
- Add integration examples with different Livewire setups

## Conclusion

This implementation plan provides a comprehensive approach to adding drag and drop functionality to both File and ImageLibrary components. The solution leverages existing Alpine.js patterns, maintains accessibility standards, and integrates seamlessly with the current component architecture. The phased approach ensures a stable implementation while allowing for iterative improvements and testing.

The drag and drop feature will significantly enhance the user experience by providing a modern, intuitive way to upload files while maintaining all existing functionality and ensuring backward compatibility.
