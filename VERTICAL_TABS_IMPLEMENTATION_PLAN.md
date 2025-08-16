# Vertical Tabs Implementation Plan

## Overview

This document outlines the implementation plan for adding vertical tab variants to the existing Tabs component in the ArtisanPack UI Livewire UI Components package. The vertical tabs will allow tab navigation buttons to be positioned either to the left or right side of the tab content, providing a vertical layout option.

## Current Implementation Analysis

### Current Structure
The current tabs implementation consists of:

1. **Tabs Component (`src/View/Components/Tabs.php`)**:
   - Simple constructor with customizable CSS classes
   - Properties: `id`, `selected`, `labelClass`, `activeClass`, `labelDivClass`, `tabsClass`
   - Uses Alpine.js for state management
   - Renders horizontal layout with `flex overflow-x-auto` for tab labels

2. **Tab Component (`src/View/Components/Tab.php`)**:
   - Individual tab properties: `name`, `label`, `icon`, `disabled`, `hidden`
   - Registers itself with parent Tabs component via Alpine.js
   - Content shown/hidden using `x-show` directive

3. **Current Layout Structure**:
   ```html
   <div class="relative w-full"> <!-- tabs container -->
       <div class="border-b flex overflow-x-auto"> <!-- label container -->
           <!-- tab labels rendered here -->
       </div>
       <div role="tablist" class="block"> <!-- content container -->
           <!-- tab content here -->
       </div>
   </div>
   ```

### Current API
- No explicit `style` or `orientation` props in PHP classes
- Documentation suggests `style` and `size` props but they're likely handled via CSS classes
- Components use DaisyUI's tab classes as foundation

## Proposed Vertical Tabs API

### New Properties

Add the following properties to the `Tabs` component constructor:

```php
public function __construct(
    // ... existing parameters ...
    public ?string $orientation = 'horizontal', // 'horizontal', 'vertical-left', 'vertical-right'
    public string $verticalClass = 'flex-row', // Base class for vertical layout
    public string $verticalLabelClass = 'flex-col border-r-[length:var(--border)] border-r-base-content/10 overflow-y-auto min-w-48',
    public string $verticalContentClass = 'flex-1',
) {
    // ... existing constructor logic ...
}
```

### Usage Examples

#### Vertical Left Tabs
```php
<x-artisanpack-tabs orientation="vertical-left">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>
```

#### Vertical Right Tabs
```php
<x-artisanpack-tabs orientation="vertical-right">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>
```

## Implementation Details

### 1. PHP Component Modifications

#### Tabs.php Changes
```php
<?php

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $selected = null,
        public ?string $orientation = 'horizontal',
        public string $labelClass = 'font-semibold pb-1',
        public string $activeClass = 'border-b-[length:var(--border)] border-b-base-content/50',
        public string $labelDivClass = 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto',
        public string $tabsClass = 'relative w-full',
        // New vertical-specific classes
        public string $verticalTabsClass = 'relative w-full flex',
        public string $verticalLabelClass = 'font-semibold pr-1 pl-1 py-2',
        public string $verticalActiveClass = 'border-r-[length:var(--border)] border-r-base-content/50',
        public string $verticalLabelDivClass = 'border-r-[length:var(--border)] border-r-base-content/10 flex flex-col overflow-y-auto min-w-48',
        public string $verticalContentClass = 'flex-1',
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    /**
     * Check if tabs should use vertical layout
     */
    public function isVertical(): bool
    {
        return in_array($this->orientation, ['vertical-left', 'vertical-right']);
    }

    /**
     * Check if tabs should be positioned on the right side
     */
    public function isVerticalRight(): bool
    {
        return $this->orientation === 'vertical-right';
    }

    /**
     * Get the appropriate container classes based on orientation
     */
    public function getTabsContainerClass(): string
    {
        return $this->isVertical() ? $this->verticalTabsClass : $this->tabsClass;
    }

    /**
     * Get the appropriate label div classes based on orientation
     */
    public function getLabelDivClass(): string
    {
        return $this->isVertical() ? $this->verticalLabelDivClass : $this->labelDivClass;
    }

    /**
     * Get the appropriate label classes based on orientation
     */
    public function getLabelClass(): string
    {
        return $this->isVertical() ? $this->verticalLabelClass : $this->labelClass;
    }

    /**
     * Get the appropriate active classes based on orientation
     */
    public function getActiveClass(): string
    {
        return $this->isVertical() ? $this->verticalActiveClass : $this->activeClass;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.tabs');
    }
}
```

### 2. Blade Template Modifications

#### Updated tabs.blade.php
```blade
<div
    x-data="{
        tabs: [],
        selected:
            @if($selected)
                '{{ $selected }}'
            @else
                @entangle($attributes->wire('model'))
            @endif
         ,
         init() {
             // Fix weird issue when navigating back
             document.addEventListener('livewire:navigating', () => {
                 document.querySelectorAll('.tab').forEach(el =>  el.remove());
             });
         }
    }"
    class="{{ $getTabsContainerClass() }}"
    x-class="font-semibold pb-1 border-b-[length:var(--border)] border-b-base-content/50 border-b-base-content/10 flex overflow-x-auto scrollbar-hide relative w-full"
>
    @if($isVerticalRight())
        <!-- CONTENT FIRST for right-side tabs -->
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class([$verticalContentClass]) }}>
            {{ $slot }}
        </div>
    @endif

    <!-- TAB LABELS -->
    <div class="{{ $getLabelDivClass() }}">
        <template x-for="tab in tabs">
            <a
                role="tab"
                x-html="tab.label"
                @click="tab.disabled ? null: selected = tab.name"
                :class="{ '{{ $getActiveClass() }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                class="tab {{ $getLabelClass() }}"></a>
        </template>
    </div>

    @if(!$isVerticalRight())
        <!-- CONTENT AFTER for left-side tabs or horizontal tabs -->
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class($isVertical() ? [$verticalContentClass] : ["block"]) }}>
            {{ $slot }}
        </div>
    @endif
</div>
```

### 3. CSS Considerations

#### Required Tailwind Classes

**Vertical Left Layout:**
- Container: `flex flex-row`
- Labels: `flex flex-col border-r overflow-y-auto min-w-48`
- Content: `flex-1`
- Active state: `border-r-[length:var(--border)] border-r-base-content/50`

**Vertical Right Layout:**
- Container: `flex flex-row`
- Labels: `flex flex-col border-l overflow-y-auto min-w-48 order-last`
- Content: `flex-1`
- Active state: `border-l-[length:var(--border)] border-l-base-content/50`

#### Responsive Considerations
Add responsive breakpoints for mobile devices:
```css
/* Default mobile: stack vertically */
@media (max-width: 768px) {
    .vertical-tabs {
        flex-direction: column;
    }
    .vertical-tabs .tab-labels {
        flex-direction: row;
        overflow-x: auto;
        min-width: auto;
        border-right: none;
        border-bottom: 1px solid theme('colors.base-content/10');
    }
}
```

### 4. Accessibility Enhancements

#### ARIA Attributes
Ensure proper ARIA attributes are maintained:
- `role="tablist"` on label container
- `role="tab"` on individual tabs
- `role="tabpanel"` on content areas
- `aria-orientation="vertical"` for vertical layouts
- Proper `aria-selected` states

#### Keyboard Navigation
- Arrow keys should navigate between tabs (up/down for vertical)
- Tab key should move focus to content area
- Home/End keys should jump to first/last tab

### 5. Documentation Updates

#### New Props Documentation
Add to the props table in `docs/components/tabs.md`:

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `orientation` | string | `horizontal` | Tab orientation (`horizontal`, `vertical-left`, `vertical-right`) |
| `verticalTabsClass` | string | `relative w-full flex` | CSS classes for vertical tabs container |
| `verticalLabelClass` | string | `font-semibold pr-1 pl-1 py-2` | CSS classes for vertical tab labels |
| `verticalActiveClass` | string | `border-r-[length:var(--border)] border-r-base-content/50` | CSS classes for active vertical tabs |
| `verticalLabelDivClass` | string | `border-r-[length:var(--border)] border-r-base-content/10 flex flex-col overflow-y-auto min-w-48` | CSS classes for vertical label container |
| `verticalContentClass` | string | `flex-1` | CSS classes for vertical content area |

#### New Examples Section
Add examples for vertical tabs usage with various configurations.

### 6. Testing Strategy

#### Unit Tests
```php
// tests/Unit/Components/TabsTest.php
test('tabs component detects vertical orientation', function () {
    $component = new Tabs(orientation: 'vertical-left');
    expect($component->isVertical())->toBeTrue();
    expect($component->isVerticalRight())->toBeFalse();
});

test('tabs component detects vertical right orientation', function () {
    $component = new Tabs(orientation: 'vertical-right');
    expect($component->isVertical())->toBeTrue();
    expect($component->isVerticalRight())->toBeTrue();
});

test('tabs component uses correct classes for vertical layout', function () {
    $component = new Tabs(orientation: 'vertical-left');
    expect($component->getTabsContainerClass())->toContain('flex');
    expect($component->getLabelDivClass())->toContain('flex-col');
});
```

#### Feature Tests
```php
// tests/Feature/Components/VerticalTabsTest.php
test('vertical left tabs render correctly', function () {
    $view = $this->blade('
        <x-artisanpack-tabs orientation="vertical-left">
            <x-artisanpack-tab name="tab1" label="Tab 1">Content 1</x-artisanpack-tab>
            <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
        </x-artisanpack-tabs>
    ');

    $view->assertSee('Tab 1')
         ->assertSee('Tab 2')
         ->assertSee('Content 1');
});

test('vertical right tabs render in correct order', function () {
    $view = $this->blade('
        <x-artisanpack-tabs orientation="vertical-right">
            <x-artisanpack-tab name="tab1" label="Tab 1">Content 1</x-artisanpack-tab>
        </x-artisanpack-tabs>
    ');

    // Content should appear before tabs in DOM for right-side layout
    $html = $view->render();
    $contentPosition = strpos($html, 'Content 1');
    $tabPosition = strpos($html, 'Tab 1');
    expect($contentPosition)->toBeLessThan($tabPosition);
});
```

#### Browser Tests
```php
// tests/Browser/VerticalTabsTest.php
test('vertical tabs work with javascript', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/test-vertical-tabs')
                ->assertSee('Tab 1')
                ->assertSee('Tab 2')
                ->click('@tab-2')
                ->waitForText('Content 2')
                ->assertDontSee('Content 1');
    });
});
```

## Implementation Timeline

### Phase 1: Core Implementation (2-3 days)
1. Add new properties to Tabs component
2. Create helper methods for orientation detection
3. Update Blade template with conditional logic
4. Basic vertical layout CSS

### Phase 2: Polish & Testing (2-3 days)
1. Add responsive behavior
2. Implement proper accessibility features
3. Write comprehensive tests
4. Update documentation

### Phase 3: Edge Cases & Refinement (1-2 days)
1. Handle edge cases (empty tabs, dynamic tabs)
2. Performance optimization
3. Cross-browser testing
4. Final documentation review

## Potential Challenges & Solutions

### Challenge 1: CSS Layout Complexity
**Problem**: Managing different layouts for horizontal/vertical orientations
**Solution**: Use helper methods to conditionally apply appropriate CSS classes

### Challenge 2: Responsive Behavior
**Problem**: Vertical tabs may not work well on mobile devices
**Solution**: Implement responsive breakpoints that revert to horizontal layout on small screens

### Challenge 3: Accessibility
**Problem**: Screen readers need proper orientation information
**Solution**: Add `aria-orientation` attribute and ensure proper keyboard navigation

### Challenge 4: Backward Compatibility
**Problem**: Existing implementations should continue working
**Solution**: Make `orientation="horizontal"` the default, ensure all existing props work as before

## Future Enhancements

1. **Collapsible Vertical Tabs**: Allow vertical tab labels to be collapsed on smaller screens
2. **Scroll Indicators**: Add visual indicators when vertical tabs overflow
3. **Drag & Drop**: Allow reordering of vertical tabs
4. **Theme Variations**: Provide different visual styles for vertical tabs
5. **Animation Support**: Add smooth transitions when switching between tabs

## Conclusion

This implementation plan provides a comprehensive approach to adding vertical tab variants while maintaining backward compatibility and following the existing component patterns. The phased approach allows for iterative development and testing, ensuring a robust final implementation.

The key benefits of this approach:
- **Backward Compatible**: No breaking changes to existing implementations
- **Flexible**: Supports both left and right vertical orientations
- **Responsive**: Adapts to different screen sizes
- **Accessible**: Maintains proper accessibility standards
- **Maintainable**: Uses consistent patterns with existing components