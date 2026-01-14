# ArtisanPack UI Livewire Components 2.0 Roadmap

> **Status**: Planning
> **Target**: Q1 2026
> **Last Updated**: January 2026

This document serves as the complete source of truth for the ArtisanPack UI Livewire Components 2.0 release. All GitLab issues for 2.0 development should be derived from this document.

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Release Goals](#release-goals)
3. [Breaking Changes](#breaking-changes)
4. [Workstream 1: Glass Design System](#workstream-1-glass-design-system)
5. [Workstream 2: ApexCharts Integration](#workstream-2-apexcharts-integration)
6. [Workstream 3: Enhanced Stats & Dashboard Widgets](#workstream-3-enhanced-stats--dashboard-widgets)
7. [Workstream 4: Livewire 4 Compatibility](#workstream-4-livewire-4-compatibility)
8. [Workstream 5: Advanced Data Features](#workstream-5-advanced-data-features)
9. [Workstream 6: Theming Enhancements](#workstream-6-theming-enhancements)
10. [Dependency Graph](#dependency-graph)
11. [Testing Requirements](#testing-requirements)
12. [Documentation Requirements](#documentation-requirements)
13. [Migration Guide](#migration-guide)
14. [Related GitLab Issues](#related-gitlab-issues)

---

## Executive Summary

Version 2.0 of ArtisanPack UI Livewire Components represents a major visual and technical evolution of the package. The release focuses on three primary objectives:

1. **Modern Visual Design**: Introduce a comprehensive glass design system (glassmorphism) with frosted, liquid, and transparent variants, supporting both dark and light themes with customizable color tinting inspired by iOS 26 Liquid Glass.

2. **Enhanced Data Visualization**: Replace Chart.js with ApexCharts for superior visual polish, add sparklines to stats components, and introduce dashboard widget components for building modern analytics interfaces.

3. **Livewire 4 Support**: Full adoption of Livewire 4 features while maintaining backward compatibility with Livewire 3, including Islands, `wire:sort`, `wire:intersect`, `data-loading`, streaming, and performance attributes.

---

## Release Goals

### Primary Goals

- [ ] Implement glass design system with three variants (frosted, liquid, transparent)
- [ ] Add color tinting support with configurable intensity
- [ ] Replace Chart.js with ApexCharts
- [ ] Add sparkline support to Stats component
- [ ] Full Livewire 4 compatibility with Livewire 3 fallback
- [ ] Virtual scrolling for large table datasets
- [ ] Data export functionality (CSV, Excel, PDF)

### Secondary Goals

- [ ] Theme builder CLI improvements
- [ ] High-contrast accessibility theme
- [ ] Dashboard widget components
- [ ] Islands for performance optimization

### Non-Goals for 2.0

- Theme marketplace platform (defer to 3.0)
- AI/ML component integration (defer to future)
- Mobile-specific PWA features (defer to future)
- Community contribution platform (defer to future)

---

## Breaking Changes

### Chart Component

**Change**: The existing `<x-artisanpack-chart>` component (Chart.js) will be removed and replaced with an ApexCharts implementation.

**Migration Path**:
```blade
{{-- Before (1.x - Chart.js config format) --}}
<x-artisanpack-chart wire:model="chartConfig" />

{{-- After (2.0 - ApexCharts config format) --}}
<x-artisanpack-chart :options="$apexOptions" />
```

**Rationale**: ApexCharts provides superior visual polish, better real-time support, and aligns with the modern dashboard aesthetic goals of 2.0.

### Livewire Version Requirements

**Change**: Package will support `livewire/livewire:^3.0|^4.0`

**Note**: Some Livewire 4 features (Islands, `wire:sort`, `wire:intersect`) will only function in Livewire 4 environments. The package will gracefully degrade in Livewire 3.

---

## Workstream 1: Glass Design System

### Overview

Implement a comprehensive glassmorphism design system that provides frosted, liquid, and transparent glass effects across all applicable components. Support both dark and light themes with customizable color tinting.

### Design Tokens

#### CSS Custom Properties

```css
:root {
  /* Base glass tokens */
  --glass-blur: 12px;
  --glass-opacity: 0.7;
  --glass-border-width: 1px;
  --glass-border-opacity: 0.2;
  --glass-shadow-opacity: 0.1;

  /* Tint tokens */
  --glass-tint-color: transparent;
  --glass-tint-opacity: 0.15;

  /* Frosted variant */
  --glass-frosted-blur: 16px;
  --glass-frosted-opacity: 0.8;
  --glass-frosted-saturation: 180%;

  /* Liquid variant */
  --glass-liquid-blur: 24px;
  --glass-liquid-opacity: 0.6;
  --glass-liquid-refraction: 0.5;
  --glass-liquid-border-glow: 0.3;

  /* Transparent variant */
  --glass-transparent-blur: 8px;
  --glass-transparent-opacity: 0.3;
}

[data-theme="dark"] {
  --glass-frosted-opacity: 0.7;
  --glass-border-opacity: 0.15;
  --glass-shadow-opacity: 0.2;
}
```

### Tasks

#### 1.1 Design Token System
**Priority**: High
**Complexity**: Medium
**Dependencies**: None

**Description**: Create the foundational CSS custom properties for all glass effects.

**Acceptance Criteria**:
- [ ] All glass tokens defined in CSS custom properties
- [ ] Dark mode variants for all tokens
- [ ] Tokens are configurable via Tailwind CSS `@theme` directive
- [ ] Documentation for all tokens

**Technical Notes**:
- Integrate with existing `ColorGenerator.php` for theme generation
- Tokens should be publishable via artisan command

---

#### 1.2 Glass Utility Classes
**Priority**: High
**Complexity**: Medium
**Dependencies**: 1.1

**Description**: Create CSS utility classes that can be applied to any HTML element.

**Classes to Implement**:
```css
/* Base glass classes */
.glass-frosted { }
.glass-liquid { }
.glass-transparent { }

/* Tint classes (Tailwind colors) */
.glass-tint-blue-500 { }
.glass-tint-emerald-500 { }
.glass-tint-purple-500 { }
/* ... all Tailwind default colors */

/* Tint intensity modifiers */
.glass-tint-opacity-10 { }
.glass-tint-opacity-20 { }
.glass-tint-opacity-30 { }
/* ... increments of 10 up to 100 */

/* Custom tint via CSS variable */
.glass-tint-custom { --glass-tint-color: var(--custom-tint); }
```

**Acceptance Criteria**:
- [ ] All three glass variants implemented as utility classes
- [ ] Tint classes for all Tailwind default colors
- [ ] Tint intensity modifiers (10-100 in increments of 10)
- [ ] Support for custom hex colors via CSS variable
- [ ] Classes work on any HTML element
- [ ] Dark and light mode support

**Usage Example**:
```blade
<div class="glass-frosted glass-tint-blue-500 glass-tint-opacity-20 p-6 rounded-xl">
    Content with blue-tinted frosted glass
</div>

<div class="glass-liquid" style="--glass-tint-color: #8b5cf6; --glass-tint-opacity: 0.25;">
    Custom purple-tinted liquid glass
</div>
```

---

#### 1.3 Component Glass Props
**Priority**: High
**Complexity**: High
**Dependencies**: 1.1, 1.2

**Description**: Add `glass` and `glass-tint` props to all applicable components.

**Props to Add**:
```php
// In component classes
public ?string $glass = null;      // 'frosted' | 'liquid' | 'transparent'
public ?string $glassTint = null;  // Tailwind color name or hex code
public ?int $glassTintOpacity = null; // 10-100
```

**Components to Update**:

| Component | File | Priority |
|-----------|------|----------|
| Card | card.blade.php | High |
| Modal | modal.blade.php | High |
| Drawer | drawer.blade.php | High |
| Popover | popover.blade.php | High |
| Dropdown | dropdown.blade.php | High |
| Stat | stat.blade.php | High |
| Alert | alert.blade.php | Medium |
| Badge | badge.blade.php | Medium |
| Toast | toast.blade.php | Medium |
| Table (header) | table.blade.php | Medium |
| Navbar | navbar.blade.php | Medium |
| Sidebar | sidebar.blade.php | Medium |
| Menu | menu.blade.php | Low |
| Tabs | tabs.blade.php | Low |
| Steps | steps.blade.php | Low |
| Collapse | collapse.blade.php | Low |
| Accordion | accordion.blade.php | Low |

**Acceptance Criteria**:
- [ ] All listed components support `glass` prop
- [ ] All listed components support `glass-tint` prop (Tailwind colors + hex)
- [ ] All listed components support `glass-tint-opacity` prop
- [ ] Props work correctly in both dark and light modes
- [ ] Text contrast is automatically adjusted using accessibility package
- [ ] Backward compatible - components work normally without glass props

**Usage Example**:
```blade
<x-artisanpack-card glass="frosted" glass-tint="blue-500" glass-tint-opacity="20">
    Card content
</x-artisanpack-card>

<x-artisanpack-stat
    glass="liquid"
    glass-tint="#8b5cf6"
    glass-tint-opacity="15"
    title="Revenue"
    value="$12,345"
/>

<x-artisanpack-modal glass="transparent" wire:model="showModal">
    Modal content
</x-artisanpack-modal>
```

---

#### 1.4 Accessibility Integration
**Priority**: Medium
**Complexity**: Low
**Dependencies**: 1.3

**Description**: Integrate with `artisanpack-ui/accessibility` package to ensure text remains readable on tinted glass backgrounds.

**Implementation**:
```php
// In component render logic
use function generateAccessibleTextColor;

$textColor = generateAccessibleTextColor($this->glassTint, true);
```

**Acceptance Criteria**:
- [ ] Text color automatically adjusts for contrast on tinted glass
- [ ] Uses `generateAccessibleTextColor()` from accessibility package
- [ ] Maintains WCAG 2.0 AA compliance (4.5:1 contrast ratio)
- [ ] Works with both Tailwind color names and hex codes

---

#### 1.5 Glass Dark/Light Mode Support
**Priority**: High
**Complexity**: Medium
**Dependencies**: 1.1, 1.2, 1.3

**Description**: Ensure all glass variants look appropriate in both dark and light modes.

**Dark Mode Considerations**:
- Frosted glass: Slightly more opaque to stand out against dark backgrounds
- Liquid glass: Subtle glow effect on borders
- Transparent glass: Higher blur for better readability
- Tints: Adjusted opacity for visibility

**Light Mode Considerations**:
- Frosted glass: More subtle, clean appearance
- Liquid glass: Refraction effect more visible
- Transparent glass: Lower blur, crisper edges

**Acceptance Criteria**:
- [ ] All glass variants tested in dark mode
- [ ] All glass variants tested in light mode
- [ ] Automatic adjustment via CSS custom property overrides
- [ ] No additional props needed - mode detection automatic
- [ ] Visual design approved for both modes

---

## Workstream 2: ApexCharts Integration

### Overview

Replace the existing Chart.js-based chart component with ApexCharts for superior visual design, better real-time support, and alignment with the modern dashboard aesthetic.

### Tasks

#### 2.1 ApexCharts Component
**Priority**: High
**Complexity**: High
**Dependencies**: None

**Description**: Create a new chart component using ApexCharts.

**Component API**:
```blade
<x-artisanpack-chart
    :options="$chartOptions"
    :series="$chartSeries"
    type="area"
    height="350"
    glass="frosted"
    glass-tint="blue-500"
/>
```

**Props**:
| Prop | Type | Description |
|------|------|-------------|
| `options` | array | ApexCharts options object |
| `series` | array | Chart data series |
| `type` | string | Chart type (area, bar, line, donut, radial, heatmap) |
| `height` | int/string | Chart height |
| `width` | int/string | Chart width |
| `glass` | string | Glass variant |
| `glassTint` | string | Tint color |
| `glassTintOpacity` | int | Tint intensity |

**Acceptance Criteria**:
- [ ] Component renders ApexCharts correctly
- [ ] All major chart types supported (area, bar, line, donut, radial, heatmap)
- [ ] Livewire reactive updates work
- [ ] Glass variants work on chart container
- [ ] Dark/light mode theme switching works
- [ ] NPM dependency properly documented

**Technical Notes**:
- ApexCharts requires NPM installation: `npm install apexcharts`
- Use Alpine.js for initialization and updates
- Consider lazy loading the library for performance

---

#### 2.2 Pre-styled Chart Themes
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 2.1, 1.1

**Description**: Create pre-styled chart themes that match the glass aesthetic.

**Themes to Create**:
- `artisanpack-light` - Clean, minimal theme for light mode
- `artisanpack-dark` - Dark theme with subtle glow effects
- `artisanpack-glass` - Theme optimized for glass backgrounds

**Acceptance Criteria**:
- [ ] Three themes implemented
- [ ] Themes auto-switch with dark/light mode
- [ ] Glass theme works well on all glass variants
- [ ] Consistent color palette with design tokens

---

#### 2.3 Sparkline Component
**Priority**: High
**Complexity**: Medium
**Dependencies**: 2.1

**Description**: Create a lightweight sparkline component for use in stats and inline visualizations.

**Component API**:
```blade
<x-artisanpack-sparkline
    :data="[10, 20, 15, 30, 25, 35]"
    type="line"
    color="emerald-500"
    height="40"
    width="100"
/>
```

**Acceptance Criteria**:
- [ ] Sparkline renders inline without full chart overhead
- [ ] Line and area types supported
- [ ] Custom colors supported (Tailwind + hex)
- [ ] Responsive sizing
- [ ] Integrates with Stats component

---

#### 2.4 Real-time Updates
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 2.1

**Description**: Support real-time data updates for live dashboards.

**Implementation**:
```blade
<x-artisanpack-chart
    :options="$chartOptions"
    wire:poll.5s="refreshChartData"
/>
```

**Acceptance Criteria**:
- [ ] Charts update smoothly with Livewire polling
- [ ] Animation transitions between data states
- [ ] No flickering or full re-renders
- [ ] Works with `wire:stream` in Livewire 4

---

#### 2.5 Remove Chart.js Component
**Priority**: Low
**Complexity**: Low
**Dependencies**: 2.1, 2.2, 2.3, 2.4

**Description**: Remove the deprecated Chart.js-based component.

**Files to Remove**:
- `src/View/Components/Chart.php` (old)
- `resources/views/components/chart.blade.php` (old, if separate)

**Acceptance Criteria**:
- [ ] Old Chart.js component removed
- [ ] No Chart.js references in codebase
- [ ] Migration guide documents the change
- [ ] Docs updated with ApexCharts examples

---

## Workstream 3: Enhanced Stats & Dashboard Widgets

### Overview

Upgrade the Stats component with glass variants, sparklines, and trend indicators. Introduce new dashboard widget components for building modern analytics interfaces.

### Tasks

#### 3.1 Stats Glass Variants
**Priority**: High
**Complexity**: Low
**Dependencies**: 1.3

**Description**: Add glass support to the Stats component.

**Acceptance Criteria**:
- [ ] Stats component supports `glass` prop
- [ ] Stats component supports `glass-tint` prop
- [ ] Stats component supports `glass-tint-opacity` prop
- [ ] Glass looks good with icons and values

**Usage**:
```blade
<x-artisanpack-stat
    glass="frosted"
    glass-tint="emerald-500"
    glass-tint-opacity="15"
    title="Revenue"
    value="$12,345"
    icon="o-currency-dollar"
/>
```

---

#### 3.2 Stats Sparkline Integration
**Priority**: High
**Complexity**: Medium
**Dependencies**: 2.3, 3.1

**Description**: Add inline sparkline support to stats for trend visualization.

**New Props**:
```php
public ?array $sparklineData = null;
public ?string $sparklineType = 'line'; // 'line' | 'area' | 'bar'
public ?string $sparklineColor = null;
```

**Acceptance Criteria**:
- [ ] Sparkline renders inside stat card
- [ ] Sparkline respects stat sizing
- [ ] Color matches stat theme or is customizable
- [ ] Works with glass variants

**Usage**:
```blade
<x-artisanpack-stat
    title="Users"
    value="1,234"
    :sparkline-data="[100, 120, 115, 140, 135, 160]"
    sparkline-type="area"
    sparkline-color="blue-500"
    glass="liquid"
/>
```

---

#### 3.3 Trend Indicators
**Priority**: Medium
**Complexity**: Low
**Dependencies**: 3.1

**Description**: Add trend indicator support showing change direction and percentage.

**New Props**:
```php
public ?float $change = null;      // e.g., 12.5 or -8.3
public ?string $changeLabel = null; // e.g., "vs last month"
```

**Acceptance Criteria**:
- [ ] Trend arrow shows up/down direction
- [ ] Positive changes show green, negative show red
- [ ] Percentage formatted correctly
- [ ] Optional label for context

**Usage**:
```blade
<x-artisanpack-stat
    title="Revenue"
    value="$12,345"
    :change="12.5"
    change-label="vs last month"
/>
```

---

#### 3.4 Animated Value Transitions
**Priority**: Low
**Complexity**: Medium
**Dependencies**: 3.1

**Description**: Add number counting animation when stat values change.

**Implementation**:
- Use Alpine.js with CountUp.js or custom implementation
- Animate from previous value to new value
- Respect reduced motion preferences

**Acceptance Criteria**:
- [ ] Values animate when updated via Livewire
- [ ] Animation is smooth and not distracting
- [ ] Respects `prefers-reduced-motion`
- [ ] Can be disabled via prop

---

#### 3.5 KPI Card Component
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 3.1, 3.2, 3.3

**Description**: Create a dedicated KPI card component optimized for dashboards.

**Component API**:
```blade
<x-artisanpack-kpi-card
    title="Monthly Revenue"
    value="$48,352"
    :change="15.3"
    change-label="vs last month"
    :sparkline-data="$revenueData"
    icon="o-currency-dollar"
    glass="frosted"
    glass-tint="emerald-500"
>
    <x-slot:footer>
        <a href="/revenue">View details</a>
    </x-slot:footer>
</x-artisanpack-kpi-card>
```

**Acceptance Criteria**:
- [ ] Combines stat, sparkline, and trend in one component
- [ ] Optimized layout for dashboards
- [ ] Supports footer slot for actions
- [ ] Full glass support

---

#### 3.6 Widget Grid Component
**Priority**: Low
**Complexity**: Low
**Dependencies**: None

**Description**: Create a responsive grid helper for dashboard layouts.

**Component API**:
```blade
<x-artisanpack-widget-grid cols="4" gap="6">
    <x-artisanpack-kpi-card ... />
    <x-artisanpack-kpi-card ... />
    <x-artisanpack-kpi-card ... />
    <x-artisanpack-kpi-card ... />
</x-artisanpack-widget-grid>
```

**Acceptance Criteria**:
- [ ] Responsive column layout
- [ ] Configurable gap spacing
- [ ] Works with all widget components
- [ ] Mobile-friendly collapse behavior

---

## Workstream 4: Livewire 4 Compatibility

### Overview

Implement full Livewire 4 support while maintaining backward compatibility with Livewire 3. Adopt new Livewire 4 features where they provide clear benefits.

### Version Detection

```php
// Helper for runtime version detection
function isLivewire4(): bool
{
    return version_compare(
        \Livewire\Livewire::VERSION ?? '3.0.0',
        '4.0.0',
        '>='
    );
}
```

### Tasks

#### 4.1 Toast Hook Migration
**Priority**: Critical
**Complexity**: Medium
**Dependencies**: None

**Description**: Fix the deprecated `Livewire.hook('request')` in the Toast component.

**Current Code** (toast.blade.php, lines 73-89):
```javascript
Livewire.hook('request', ({fail}) => {
    fail(({status, content, preventDefault}) => {
        // Error handling
    })
})
```

**New Code**:
```javascript
// Livewire 4
if (typeof Livewire.hook === 'function' && Livewire.hook.toString().includes('interceptRequest')) {
    Livewire.hook('interceptWireRequest', (request, respond) => {
        respond(({fail}) => {
            fail(({status, content, preventDefault}) => {
                // Error handling
            })
        })
    })
} else {
    // Livewire 3 fallback
    Livewire.hook('request', ({fail}) => {
        fail(({status, content, preventDefault}) => {
            // Error handling
        })
    })
}
```

**Acceptance Criteria**:
- [ ] Toast error handling works in Livewire 4
- [ ] Toast error handling works in Livewire 3
- [ ] No console errors in either version
- [ ] Test with 419 session expiry scenario

---

#### 4.2 Tab Hook Verification
**Priority**: High
**Complexity**: Low
**Dependencies**: None

**Description**: Verify and fix the `morph.removed` hook in the Tab component.

**Current Code** (tab.blade.php, lines 10-13):
```javascript
Livewire.hook('morph.removed', ({el}) => {
    if (el.getAttribute('data-name') == '{{ $name }}'){
        tabs = tabs.filter(i => i.name !== '{{ $name }}')
    }
})
```

**Acceptance Criteria**:
- [ ] Hook name verified for Livewire 4
- [ ] Tab cleanup works correctly in both versions
- [ ] Dynamic tab removal works

---

#### 4.3 wire:sort Support
**Priority**: High
**Complexity**: Medium
**Dependencies**: None

**Description**: Add native drag-and-drop sorting to the Table component using Livewire 4's `wire:sort`.

**New Props**:
```php
public bool $sortable = false;
public ?string $sortableKey = null; // defaults to $keyBy
```

**Implementation**:
```blade
{{-- In table.blade.php --}}
@if(isLivewire4() && $sortable)
    <tbody wire:sort="{{ $attributes->wire('sort')->value() ?? 'updateOrder' }}">
@else
    <tbody>
@endif
    @foreach($rows as $row)
        <tr
            wire:key="row-{{ data_get($row, $keyBy) }}"
            @if(isLivewire4() && $sortable)
                wire:sort:item="{{ data_get($row, $sortableKey ?? $keyBy) }}"
            @endif
        >
```

**Acceptance Criteria**:
- [ ] Drag-and-drop works in Livewire 4
- [ ] Gracefully ignored in Livewire 3
- [ ] Sort handle support (`wire:sort:handle`)
- [ ] Cross-list dragging support (`wire:sort:group`)

**Usage**:
```blade
{{-- Livewire 4 --}}
<x-artisanpack-table
    :rows="$tasks"
    sortable
    wire:sort="reorderTasks"
/>
```

---

#### 4.4 wire:intersect Support
**Priority**: High
**Complexity**: Medium
**Dependencies**: None

**Description**: Add infinite scroll capability using Livewire 4's `wire:intersect`.

**Components to Update**:
- Table (infinite scroll pagination)
- Choices (lazy load options)
- Media grid (lazy load thumbnails)

**Table Implementation**:
```blade
@if(isLivewire4() && $infiniteScroll)
    <tfoot>
        <tr>
            <td colspan="{{ count($headers) }}">
                <div wire:intersect="loadMore" class="py-4 text-center">
                    <span class="data-loading:hidden">Scroll for more</span>
                    <x-artisanpack-loading class="not-data-loading:hidden" />
                </div>
            </td>
        </tr>
    </tfoot>
@endif
```

**Acceptance Criteria**:
- [ ] Infinite scroll works in Livewire 4
- [ ] Falls back to pagination in Livewire 3
- [ ] `.once`, `.half`, `.full` modifiers supported
- [ ] Works with Table, Choices components

---

#### 4.5 data-loading Attribute System
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: None

**Description**: Implement Livewire 4's `data-loading` attribute system alongside existing `wire:loading`.

**Current Pattern** (button.blade.php):
```blade
<span wire:loading.class.remove="hidden" wire:target="{{ $spinnerTarget() }}">
    Loading...
</span>
```

**New Pattern** (dual support):
```blade
{{-- Livewire 4 --}}
<span class="not-data-loading:hidden">Loading...</span>

{{-- Or with Tailwind v4 arbitrary variants --}}
<span class="hidden [[data-loading]_&]:block">Loading...</span>
```

**Components to Update**:
- Button
- Menu Item
- Header
- Card
- Separator
- Tags
- Choices

**Acceptance Criteria**:
- [ ] `data-loading` classes work in Livewire 4
- [ ] `wire:loading` continues to work in both versions
- [ ] Documentation updated with both patterns
- [ ] Tailwind v4 variants documented

---

#### 4.6 #[Computed] Attributes
**Priority**: Medium
**Complexity**: Low
**Dependencies**: None

**Description**: Migrate computed properties to use `#[Computed]` attribute in Calendar component.

**Current** (Calendar.php):
```php
public function getWeekdaysProperty(): Collection { }
public function getWeeksProperty(): Collection { }
```

**New**:
```php
use Livewire\Attributes\Computed;

#[Computed]
public function weekdays(): Collection { }

#[Computed(persist: true, seconds: 60)]
public function weeks(): Collection { }
```

**Acceptance Criteria**:
- [ ] Calendar uses `#[Computed]` attributes
- [ ] Caching works correctly
- [ ] Backward compatible with Livewire 3 (fallback to getter properties)

---

#### 4.7 #[Defer] and #[Lazy] Attributes
**Priority**: Medium
**Complexity**: Low
**Dependencies**: None

**Description**: Add deferred loading support for heavy components.

**Components to Update**:
- Chart (heavy JS library)
- Calendar (complex calculations)
- Editor (TinyMCE/rich text)
- Markdown (parser overhead)

**Implementation**:
```php
use Livewire\Attributes\Defer;

#[Defer]
class Chart extends Component
{
    public function placeholder(): View
    {
        return view('livewire-ui-components::placeholders.chart');
    }
}
```

**Acceptance Criteria**:
- [ ] Heavy components use `#[Defer]` by default
- [ ] Placeholder views created for deferred components
- [ ] Can be disabled with `:defer="false"`
- [ ] Works in Livewire 4, ignored in Livewire 3

---

#### 4.8 #[Async] and #[Renderless] Attributes
**Priority**: Low
**Complexity**: Low
**Dependencies**: None

**Description**: Add async and renderless support for non-UI operations.

**Use Cases**:
- Analytics tracking
- View count increments
- Logging operations

**Acceptance Criteria**:
- [ ] Async actions don't block UI
- [ ] Renderless actions skip re-render
- [ ] Works in Livewire 4, fallback in Livewire 3

---

#### 4.9 Islands Implementation
**Priority**: Medium
**Complexity**: High
**Dependencies**: 4.6

**Description**: Implement Islands for isolated updates in complex components.

**Calendar Islands**:
```blade
{{-- Calendar header - updates independently --}}
@island('calendar-header')
    <div class="flex items-center justify-between">
        <button wire:click="goToPreviousPeriod">Previous</button>
        <h2>{{ $headerText }}</h2>
        <button wire:click="goToNextPeriod">Next</button>
    </div>
@endisland

{{-- Calendar grid - deferred loading --}}
@island('calendar-grid', defer: true)
    @placeholder
        <x-artisanpack-loading class="h-64" />
    @endplaceholder

    <div class="grid grid-cols-7">
        <!-- Grid content -->
    </div>
@endisland
```

**Acceptance Criteria**:
- [ ] Calendar uses Islands for header/grid separation
- [ ] Header updates don't re-render grid
- [ ] Deferred grid loading works
- [ ] Falls back to normal rendering in Livewire 3

---

#### 4.10 wire:stream Support
**Priority**: Low
**Complexity**: Medium
**Dependencies**: None

**Description**: Add content streaming support for AI integration.

**Use Case**: AI writing assistant in Editor component.

```blade
<div wire:stream="aiContent">{{ $content }}</div>
```

```php
public function generateAIContent(string $prompt): void
{
    foreach ($this->aiService->streamResponse($prompt) as $chunk) {
        $this->stream(el: 'aiContent', content: $chunk);
    }
}
```

**Acceptance Criteria**:
- [ ] Streaming works with Editor component
- [ ] Real-time text appearance
- [ ] Works in Livewire 4 only (documented limitation)

---

## Workstream 5: Advanced Data Features

### Overview

Implement advanced data handling capabilities including virtual scrolling for large datasets and data export functionality.

### Tasks

#### 5.1 Virtual Scrolling
**Priority**: High
**Complexity**: High
**Dependencies**: None

**Description**: Implement virtual scrolling for Table component to handle large datasets efficiently.

**Implementation Approach**:
- Use a virtual scrolling library (e.g., `@tanstack/virtual` or custom implementation)
- Only render visible rows plus buffer
- Maintain smooth scrolling experience

**New Props**:
```php
public bool $virtualScroll = false;
public int $virtualRowHeight = 48;
public int $virtualBuffer = 10;
```

**Acceptance Criteria**:
- [ ] Table handles 10,000+ rows without performance issues
- [ ] Scroll position maintained on updates
- [ ] Works with sorting and filtering
- [ ] Works with row selection
- [ ] Fallback to standard rendering when disabled

**Usage**:
```blade
<x-artisanpack-table
    :headers="$headers"
    :rows="$largeDataset"
    virtual-scroll
    :virtual-row-height="52"
/>
```

---

#### 5.2 Data Export - CSV
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: None

**Description**: Add CSV export functionality to Table component.

**Implementation**:
- Client-side CSV generation for small datasets
- Server-side generation for large datasets
- Include visible columns only (respecting hidden columns)

**New Props**:
```php
public bool $exportable = false;
public array $exportFormats = ['csv', 'xlsx', 'pdf'];
public ?string $exportFilename = null;
```

**Acceptance Criteria**:
- [ ] CSV export works for table data
- [ ] Exports visible columns only
- [ ] Custom filename support
- [ ] Large dataset handling (streaming/chunking)

---

#### 5.3 Data Export - Excel
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 5.2

**Description**: Add Excel (XLSX) export functionality.

**Implementation**:
- Use `maatwebsite/excel` or `phpoffice/phpspreadsheet`
- Server-side generation
- Basic formatting (headers bold, column widths)

**Acceptance Criteria**:
- [ ] XLSX export works
- [ ] Headers formatted
- [ ] Column widths auto-sized
- [ ] Works with large datasets

---

#### 5.4 Data Export - PDF
**Priority**: Medium
**Complexity**: High
**Dependencies**: 5.2

**Description**: Add PDF export functionality.

**Implementation**:
- Use `barryvdh/laravel-dompdf` or `spatie/laravel-pdf`
- Server-side generation
- Basic table formatting

**Acceptance Criteria**:
- [ ] PDF export works
- [ ] Table renders correctly in PDF
- [ ] Page breaks handled
- [ ] Custom header/footer support

---

#### 5.5 Export UI Component
**Priority**: Low
**Complexity**: Low
**Dependencies**: 5.2, 5.3, 5.4

**Description**: Create dropdown UI for export options.

**Implementation**:
```blade
<x-artisanpack-dropdown>
    <x-slot:trigger>
        <x-artisanpack-button icon="o-arrow-down-tray">Export</x-artisanpack-button>
    </x-slot:trigger>

    <x-artisanpack-menu>
        <x-artisanpack-menu-item wire:click="export('csv')">CSV</x-artisanpack-menu-item>
        <x-artisanpack-menu-item wire:click="export('xlsx')">Excel</x-artisanpack-menu-item>
        <x-artisanpack-menu-item wire:click="export('pdf')">PDF</x-artisanpack-menu-item>
    </x-artisanpack-menu>
</x-artisanpack-dropdown>
```

**Acceptance Criteria**:
- [ ] Export dropdown renders when `exportable` is true
- [ ] Only enabled formats shown
- [ ] Loading state during export
- [ ] Download triggers correctly

---

## Workstream 6: Theming Enhancements

### Overview

Enhance the theming system with design tokens, glass presets, and improved theme generation tools.

### Tasks

#### 6.1 Formalize Design Token System
**Priority**: High
**Complexity**: Medium
**Dependencies**: 1.1

**Description**: Create a comprehensive design token system using CSS custom properties.

**Token Categories**:
- Colors (primary, secondary, accent, semantic)
- Typography (font families, sizes, weights)
- Spacing (consistent spacing scale)
- Border radius (rounded corners)
- Shadows (elevation system)
- Glass effects (blur, opacity, tint)
- Animation (durations, easings)

**Acceptance Criteria**:
- [ ] All tokens documented
- [ ] Tokens follow naming convention
- [ ] Tokens are overridable
- [ ] Integration with existing ColorGenerator

---

#### 6.2 Glass Theme Presets
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 1.1, 1.2, 6.1

**Description**: Create pre-built glass theme presets.

**Presets to Create**:
- `glass-frosted-light` - Light mode frosted glass theme
- `glass-frosted-dark` - Dark mode frosted glass theme
- `glass-liquid-light` - Light mode liquid glass theme
- `glass-liquid-dark` - Dark mode liquid glass theme
- `glass-minimal` - Subtle glass effects
- `glass-bold` - Strong glass effects

**Acceptance Criteria**:
- [ ] All presets created
- [ ] Presets can be applied globally or per-component
- [ ] Presets work with theme toggle
- [ ] Documentation for each preset

---

#### 6.3 Theme Builder CLI Enhancement
**Priority**: Medium
**Complexity**: Medium
**Dependencies**: 6.1, 6.2

**Description**: Enhance the artisan command for theme generation.

**New Features**:
- Glass preset selection
- Tint color configuration
- Live preview URL generation
- Export to JSON

**Command**:
```bash
php artisan artisanpack:generate-theme
# Interactive prompts:
# - Primary color
# - Secondary color
# - Accent color
# - Glass preset (frosted-light, frosted-dark, liquid-light, etc.)
# - Default tint color
# - Tint intensity
```

**Acceptance Criteria**:
- [ ] Command generates complete theme file
- [ ] Glass tokens included
- [ ] Theme preview works
- [ ] JSON export option

---

#### 6.4 High-Contrast Theme
**Priority**: Low
**Complexity**: Medium
**Dependencies**: 6.1

**Description**: Create an accessibility-focused high-contrast theme.

**Features**:
- Maximum contrast ratios
- Larger text options
- Focus indicators
- Reduced motion

**Acceptance Criteria**:
- [ ] WCAG AAA compliance
- [ ] Works with screen readers
- [ ] Respects `prefers-contrast` media query
- [ ] Can be toggled independently of dark/light mode

---

#### 6.5 Live Theme Preview Tool
**Priority**: Low
**Complexity**: High
**Dependencies**: 6.1, 6.2, 6.3

**Description**: Create a browser-based theme preview tool.

**Features**:
- Real-time color adjustment
- Component preview gallery
- Glass effect customization
- Export theme configuration

**Acceptance Criteria**:
- [ ] Web interface for theme customization
- [ ] Real-time preview updates
- [ ] Export to CSS/JSON
- [ ] Shareable preview URLs

---

## Dependency Graph

```
Phase 1: Foundation
├── 1.1 Design Token System
├── 6.1 Formalize Design Token System
└── 4.1 Toast Hook Migration (Critical)

Phase 2: Glass System
├── 1.2 Glass Utility Classes (depends on 1.1)
├── 1.3 Component Glass Props (depends on 1.1, 1.2)
├── 1.4 Accessibility Integration (depends on 1.3)
└── 1.5 Glass Dark/Light Mode (depends on 1.1, 1.2, 1.3)

Phase 3: Charts & Stats
├── 2.1 ApexCharts Component
├── 2.2 Pre-styled Chart Themes (depends on 2.1, 1.1)
├── 2.3 Sparkline Component (depends on 2.1)
├── 2.4 Real-time Updates (depends on 2.1)
├── 3.1 Stats Glass Variants (depends on 1.3)
├── 3.2 Stats Sparkline Integration (depends on 2.3, 3.1)
├── 3.3 Trend Indicators (depends on 3.1)
└── 2.5 Remove Chart.js (depends on 2.1-2.4)

Phase 4: Livewire 4 Features
├── 4.2 Tab Hook Verification
├── 4.3 wire:sort Support
├── 4.4 wire:intersect Support
├── 4.5 data-loading Attribute System
├── 4.6 #[Computed] Attributes
├── 4.7 #[Defer] and #[Lazy] Attributes
├── 4.8 #[Async] and #[Renderless] Attributes
├── 4.9 Islands Implementation (depends on 4.6)
└── 4.10 wire:stream Support

Phase 5: Advanced Features
├── 5.1 Virtual Scrolling
├── 5.2 Data Export - CSV
├── 5.3 Data Export - Excel (depends on 5.2)
├── 5.4 Data Export - PDF (depends on 5.2)
├── 5.5 Export UI Component (depends on 5.2-5.4)
├── 3.4 Animated Value Transitions
├── 3.5 KPI Card Component (depends on 3.1-3.3)
└── 3.6 Widget Grid Component

Phase 6: Theming Polish
├── 6.2 Glass Theme Presets (depends on 1.1, 1.2, 6.1)
├── 6.3 Theme Builder CLI Enhancement (depends on 6.1, 6.2)
├── 6.4 High-Contrast Theme (depends on 6.1)
└── 6.5 Live Theme Preview Tool (depends on 6.1-6.3)
```

---

## Testing Requirements

### Unit Tests

All new features must have unit tests:

```php
// Example: Glass prop tests
test('card renders with glass frosted variant', function () {
    $this->blade('<x-artisanpack-card glass="frosted">Content</x-artisanpack-card>')
        ->assertSee('glass-frosted')
        ->assertSee('Content');
});

test('stat renders with glass tint', function () {
    $this->blade('<x-artisanpack-stat glass="liquid" glass-tint="blue-500" glass-tint-opacity="20" />')
        ->assertSee('glass-liquid')
        ->assertSee('glass-tint-blue-500')
        ->assertSee('glass-tint-opacity-20');
});
```

### Livewire Tests

```php
// Example: Livewire 4 feature tests
test('table supports wire:sort in Livewire 4', function () {
    Livewire::test(TableComponent::class, ['sortable' => true])
        ->assertSeeHtml('wire:sort');
})->skip(!isLivewire4(), 'Requires Livewire 4');

test('chart updates reactively', function () {
    Livewire::test(ChartComponent::class)
        ->set('series', [10, 20, 30])
        ->assertSet('series', [10, 20, 30]);
});
```

### Browser Tests

```php
// Example: Visual regression tests
test('glass effects render correctly in dark mode', function () {
    Livewire::visit(GlassShowcase::class)
        ->click('@toggle-dark-mode')
        ->assertVisible('@glass-frosted-card')
        ->screenshot('glass-frosted-dark');
});
```

### Accessibility Tests

```php
// Example: Contrast ratio tests
test('text remains readable on tinted glass', function () {
    $tintColor = '#3b82f6';
    $textColor = generateAccessibleTextColor($tintColor);

    expect(checkContrastRatio($textColor, $tintColor))
        ->toBeGreaterThanOrEqual(4.5);
});
```

---

## Documentation Requirements

### Component Documentation Updates

For each component gaining glass support:
- [ ] Update component reference page
- [ ] Add glass props to prop table
- [ ] Add glass usage examples
- [ ] Add visual examples (screenshots/gifs)

### New Documentation Pages

- [ ] Glass Design System guide
- [ ] Theme customization guide (updated)
- [ ] ApexCharts migration guide
- [ ] Livewire 4 features guide
- [ ] Virtual scrolling guide
- [ ] Data export guide

### Migration Guide

- [ ] Chart.js to ApexCharts migration
- [ ] Livewire 3 to Livewire 4 considerations
- [ ] Breaking changes summary
- [ ] Version compatibility matrix

---

## Migration Guide

### For Users Upgrading from 1.x

#### Chart Component Migration

```blade
{{-- 1.x (Chart.js) --}}
@php
$chartConfig = [
    'type' => 'line',
    'data' => [
        'labels' => ['Jan', 'Feb', 'Mar'],
        'datasets' => [[
            'data' => [10, 20, 30],
            'borderColor' => '#3b82f6'
        ]]
    ]
];
@endphp
<x-artisanpack-chart wire:model="chartConfig" />

{{-- 2.x (ApexCharts) --}}
@php
$chartOptions = [
    'chart' => ['type' => 'line'],
    'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar']]
];
$chartSeries = [['name' => 'Data', 'data' => [10, 20, 30]]];
@endphp
<x-artisanpack-chart :options="$chartOptions" :series="$chartSeries" />
```

#### Livewire Version Compatibility

```json
// composer.json
{
    "require": {
        "livewire/livewire": "^3.0|^4.0"
    }
}
```

**Livewire 4 Only Features**:
- `wire:sort` (drag-and-drop tables)
- `wire:intersect` (infinite scroll)
- `data-loading` attribute
- Islands
- `wire:stream`

These features will be silently ignored in Livewire 3 environments.

---

## Related GitLab Issues

This roadmap addresses and supersedes the following GitLab issues:

| Issue | Title | Status |
|-------|-------|--------|
| #41 | Component Library Expansion & Modernization | Partially addressed (charts, dashboard components) |
| #42 | Advanced Theming & Customization System | Fully addressed |
| #46 | Advanced Data Components & Visualization | Partially addressed (charts, virtual scrolling, export) |

### Issues Deferred to Future Releases

| Issue | Title | Target Release |
|-------|-------|----------------|
| #43 | Advanced Form Components & Validation | 2.1 |
| #44 | Enterprise Integration & API Enhancement | 3.0 |
| #45 | AI/ML Integration & Smart Components | 3.0 |
| #47 | Mobile & Progressive Web App Enhancement | 2.2 |
| #48 | Community & Marketplace Platform | 3.0 |
| #49 | Advanced Developer Tools & IDE Integration | 2.1 |
| #50 | Internationalization & Localization Framework | 2.1 |
| #51 | ArtisanPack Ecosystem Deep Integration | 2.1 |
| #52 | Laravel 12 Optimization & Future-Proofing | 2.0 (implicitly via Livewire 4 support) |

---

## Appendix A: Glass Effect CSS Reference

### Frosted Glass
```css
.glass-frosted {
    background: rgba(255, 255, 255, var(--glass-frosted-opacity));
    backdrop-filter: blur(var(--glass-frosted-blur)) saturate(var(--glass-frosted-saturation));
    -webkit-backdrop-filter: blur(var(--glass-frosted-blur)) saturate(var(--glass-frosted-saturation));
    border: var(--glass-border-width) solid rgba(255, 255, 255, var(--glass-border-opacity));
    box-shadow: 0 4px 6px rgba(0, 0, 0, var(--glass-shadow-opacity));
}

[data-theme="dark"] .glass-frosted {
    background: rgba(0, 0, 0, var(--glass-frosted-opacity));
    border-color: rgba(255, 255, 255, var(--glass-border-opacity));
}
```

### Liquid Glass
```css
.glass-liquid {
    background: rgba(255, 255, 255, var(--glass-liquid-opacity));
    backdrop-filter: blur(var(--glass-liquid-blur));
    -webkit-backdrop-filter: blur(var(--glass-liquid-blur));
    border: var(--glass-border-width) solid rgba(255, 255, 255, var(--glass-liquid-border-glow));
    box-shadow:
        0 4px 6px rgba(0, 0, 0, var(--glass-shadow-opacity)),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}
```

### Transparent Glass
```css
.glass-transparent {
    background: rgba(255, 255, 255, var(--glass-transparent-opacity));
    backdrop-filter: blur(var(--glass-transparent-blur));
    -webkit-backdrop-filter: blur(var(--glass-transparent-blur));
    border: var(--glass-border-width) solid rgba(255, 255, 255, var(--glass-border-opacity));
}
```

### Color Tint
```css
.glass-tint-blue-500 {
    --glass-tint-color: #3b82f6;
}

.glass-tint-opacity-20 {
    --glass-tint-opacity: 0.2;
}

/* Apply tint to glass */
.glass-frosted[class*="glass-tint-"],
.glass-liquid[class*="glass-tint-"],
.glass-transparent[class*="glass-tint-"] {
    background: color-mix(
        in srgb,
        var(--glass-tint-color) calc(var(--glass-tint-opacity) * 100%),
        transparent
    );
}
```

---

## Appendix B: ApexCharts Configuration Reference

### Basic Line Chart
```php
$options = [
    'chart' => [
        'type' => 'line',
        'height' => 350,
        'toolbar' => ['show' => false],
    ],
    'stroke' => [
        'curve' => 'smooth',
        'width' => 2,
    ],
    'xaxis' => [
        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    ],
    'colors' => ['#3b82f6'],
];

$series = [
    ['name' => 'Revenue', 'data' => [10, 20, 15, 30, 25]],
];
```

### Area Chart with Glass Theme
```php
$options = [
    'chart' => [
        'type' => 'area',
        'height' => 350,
        'background' => 'transparent',
    ],
    'fill' => [
        'type' => 'gradient',
        'gradient' => [
            'shadeIntensity' => 1,
            'opacityFrom' => 0.7,
            'opacityTo' => 0.2,
        ],
    ],
    'theme' => [
        'mode' => 'dark', // or 'light'
    ],
];
```

### Sparkline Configuration
```php
$options = [
    'chart' => [
        'type' => 'line',
        'sparkline' => ['enabled' => true],
        'height' => 40,
        'width' => 100,
    ],
    'stroke' => [
        'curve' => 'smooth',
        'width' => 2,
    ],
    'colors' => ['#10b981'],
];

$series = [
    ['data' => [10, 20, 15, 30, 25, 35]],
];
```

---

## Appendix C: Livewire Version Detection

### PHP Helper
```php
<?php

namespace ArtisanPackUI\LivewireUiComponents\Support;

class LivewireVersion
{
    public static function isV4(): bool
    {
        if (!class_exists(\Livewire\Livewire::class)) {
            return false;
        }

        $version = defined('\Livewire\Livewire::VERSION')
            ? \Livewire\Livewire::VERSION
            : '3.0.0';

        return version_compare($version, '4.0.0', '>=');
    }

    public static function isV3(): bool
    {
        return !self::isV4();
    }

    public static function supports(string $feature): bool
    {
        $v4Features = [
            'islands',
            'wire:sort',
            'wire:intersect',
            'data-loading',
            'wire:stream',
            '#[Defer]',
            '#[Async]',
            '#[Renderless]',
        ];

        if (in_array($feature, $v4Features)) {
            return self::isV4();
        }

        return true;
    }
}
```

### Blade Helper
```php
// In ServiceProvider boot()
Blade::if('livewire4', function () {
    return LivewireVersion::isV4();
});
```

```blade
{{-- Usage in Blade --}}
@livewire4
    <div wire:sort="updateOrder">...</div>
@else
    <div>Drag-and-drop requires Livewire 4</div>
@endlivewire4
```

### JavaScript Helper
```javascript
// In app.js or component script
window.isLivewire4 = () => {
    return typeof Livewire !== 'undefined' &&
           Livewire.version &&
           Livewire.version.startsWith('4');
};
```

---

## Appendix D: Design Inspiration Sources

The visual direction for 2.0 is inspired by:

1. **iOS 26 Liquid Glass** - Color tinting, refraction effects, depth
2. **Modern Dashboard UIs** - Clean stats cards, sparklines, gradient backgrounds
3. **Glassmorphism Trend** - Frosted effects, transparency, blur

Reference implementations reviewed:
- Pinterest pins showing modern dashboard designs
- Sports analytics dashboards with tabbed navigation
- Dark gradient backgrounds with frosted overlays

---

*Document Version: 1.0*
*Created: January 2026*
*Author: Planning Session with Claude*
