# Accessibility Excellence Framework Implementation Plan

**Version:** 1.0
**Date:** 2025-11-06
**Target:** WCAG 2.1 AA Compliance
**Scope:** 87 Components in ArtisanPack UI Livewire UI Components

---

## Executive Summary

This plan outlines a comprehensive approach to implementing WCAG 2.1 AA accessibility compliance across all 87 Livewire UI components. The framework leverages the existing `artisanpack-ui/accessibility` package integration and builds upon the current testing infrastructure (Pest) to create a sustainable, maintainable accessibility system.

**Current State:**
- ✅ 87 blade components identified
- ✅ `artisanpack-ui/accessibility` v1.1 integrated
- ✅ Pest testing framework with Accessibility test suite structure
- ⚠️ Partial ARIA implementation (15 components have some aria- attributes)
- ⚠️ Partial role implementation (15 components have role attributes)
- ⚠️ Limited tabindex management (9 components)
- ❌ No systematic keyboard navigation
- ❌ No focus management system
- ❌ No screen reader optimization
- ❌ No automated accessibility testing
- ❌ No accessibility documentation

---

## Phase 1: Foundation & Infrastructure (Week 1-2)

### 1.1 Accessibility Utilities & Traits

**Goal:** Create reusable PHP traits and utilities for common accessibility patterns.

**Deliverables:**

#### `src/Traits/HasAccessibility.php`
```php
<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

use ArtisanPack\Accessibility\Services\AriaAttributeBuilder;
use ArtisanPack\Accessibility\Services\KeyboardNavigationHelper;

trait HasAccessibility
{
    public ?string $ariaLabel = null;
    public ?string $ariaDescribedBy = null;
    public ?string $ariaLabelledBy = null;
    public bool $ariaHidden = false;
    public ?string $role = null;
    public ?int $tabindex = null;

    /**
     * Build accessibility attributes array
     */
    protected function buildAccessibilityAttributes(): array
    {
        return AriaAttributeBuilder::make()
            ->label($this->ariaLabel)
            ->describedBy($this->ariaDescribedBy)
            ->labelledBy($this->ariaLabelledBy)
            ->hidden($this->ariaHidden)
            ->role($this->role)
            ->tabindex($this->tabindex)
            ->build();
    }

    /**
     * Get accessibility attribute merge bag
     */
    protected function getAccessibilityAttributeString(): string
    {
        $attrs = $this->buildAccessibilityAttributes();
        return collect($attrs)
            ->map(fn($value, $key) => sprintf('%s="%s"', $key, e($value)))
            ->implode(' ');
    }
}
```

#### `src/Traits/HasKeyboardNavigation.php`
```php
<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

trait HasKeyboardNavigation
{
    public bool $keyboardNavigable = true;
    public ?string $keyboardShortcut = null;

    /**
     * Get keyboard navigation attributes
     */
    protected function getKeyboardAttributes(): array
    {
        if (!$this->keyboardNavigable) {
            return [];
        }

        $attrs = [];

        if ($this->keyboardShortcut) {
            $attrs['data-keyboard-shortcut'] = $this->keyboardShortcut;
        }

        return $attrs;
    }
}
```

#### `src/Traits/HasFocusManagement.php`
```php
<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

trait HasFocusManagement
{
    public bool $autoFocus = false;
    public bool $trapFocus = false;
    public ?string $focusTarget = null;

    /**
     * Get focus management attributes
     */
    protected function getFocusAttributes(): array
    {
        $attrs = [];

        if ($this->autoFocus) {
            $attrs['autofocus'] = 'autofocus';
        }

        if ($this->trapFocus) {
            $attrs['x-trap'] = 'open';
        }

        return $attrs;
    }
}
```

### 1.2 Accessibility Configuration

**File:** `config/livewire-ui-components.php`

Add accessibility configuration section:

```php
'accessibility' => [
    /**
     * Enable accessibility features globally
     */
    'enabled' => env('ACCESSIBILITY_ENABLED', true),

    /**
     * High contrast mode support
     */
    'high_contrast' => env('ACCESSIBILITY_HIGH_CONTRAST', true),

    /**
     * Reduced motion support
     */
    'reduced_motion' => env('ACCESSIBILITY_REDUCED_MOTION', true),

    /**
     * Minimum color contrast ratio (WCAG AA = 4.5:1)
     */
    'min_contrast_ratio' => 4.5,

    /**
     * Focus indicator settings
     */
    'focus_indicator' => [
        'width' => '2px',
        'color' => 'var(--primary)',
        'offset' => '2px',
        'style' => 'solid',
    ],

    /**
     * Screen reader preferences
     */
    'screen_reader' => [
        'announce_changes' => true,
        'live_regions' => true,
    ],
],
```

### 1.3 CSS Accessibility Utilities

**File:** `resources/css/accessibility.css`

```css
/* Focus Management */
.focus-visible:focus {
    outline: var(--focus-width, 2px) var(--focus-style, solid) var(--focus-color, var(--primary));
    outline-offset: var(--focus-offset, 2px);
}

.focus-ring {
    @apply ring-2 ring-primary ring-offset-2 ring-offset-base-100;
}

/* Skip Links */
.skip-link {
    @apply absolute -top-full left-0 bg-primary text-primary-content px-4 py-2 z-[9999];
    @apply focus:top-0;
}

/* Screen Reader Only */
.sr-only-focusable:not(:focus):not(:focus-within) {
    @apply sr-only;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    :root {
        --focus-width: 3px;
        --border-width: 2px;
    }

    .btn, .input, .select, .textarea {
        @apply border-2;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Keyboard Navigation Indicators */
[data-keyboard-focused="true"] {
    @apply ring-2 ring-primary ring-offset-2;
}

/* Live Region Styles */
[aria-live="polite"],
[aria-live="assertive"] {
    @apply sr-only;
}

.live-region-visual {
    @apply fixed bottom-4 right-4 p-4 bg-base-100 shadow-lg rounded-lg border border-base-300;
    z-index: 9999;
}
```

### 1.4 Alpine.js Accessibility Directives

**File:** `resources/js/accessibility.js`

```javascript
// Keyboard Navigation Manager
document.addEventListener('alpine:init', () => {
    Alpine.directive('keyboard-nav', (el, { expression }, { evaluate }) => {
        const config = evaluate(expression);

        el.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'ArrowUp':
                case 'ArrowDown':
                case 'ArrowLeft':
                case 'ArrowRight':
                    handleArrowNavigation(e, config);
                    break;
                case 'Home':
                case 'End':
                    handleHomeEnd(e, config);
                    break;
                case 'Enter':
                case ' ':
                    handleActivation(e, config);
                    break;
                case 'Escape':
                    handleEscape(e, config);
                    break;
                case 'Tab':
                    handleTab(e, config);
                    break;
            }
        });
    });

    // Focus Management
    Alpine.directive('focus-trap', (el, { expression }, { evaluate, cleanup }) => {
        const isActive = evaluate(expression);

        if (isActive) {
            const focusableElements = el.querySelectorAll(
                'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            const trapFocus = (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            };

            el.addEventListener('keydown', trapFocus);
            firstElement?.focus();

            cleanup(() => {
                el.removeEventListener('keydown', trapFocus);
            });
        }
    });

    // Live Regions
    Alpine.directive('live-region', (el, { modifiers }) => {
        const politeness = modifiers.includes('assertive') ? 'assertive' : 'polite';
        const atomic = modifiers.includes('atomic') ? 'true' : 'false';

        el.setAttribute('aria-live', politeness);
        el.setAttribute('aria-atomic', atomic);
        el.setAttribute('role', 'status');
    });
});

// Reduced Motion Detection
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

Alpine.store('accessibility', {
    reducedMotion: prefersReducedMotion.matches,
    highContrast: window.matchMedia('(prefers-contrast: high)').matches,

    init() {
        prefersReducedMotion.addEventListener('change', (e) => {
            this.reducedMotion = e.matches;
        });
    }
});
```

---

## Phase 2: Component Accessibility Audit & Categorization (Week 2-3)

### 2.1 Component Categories by Complexity

Based on the 87 components, categorize into 4 tiers:

#### **Tier 1: Simple Components (25 components)**
*Low accessibility complexity - mostly semantic markup*

1. Alert
2. Avatar
3. Badge
4. Breadcrumbs
5. Code
6. Diff
7. Errors
8. Heading
9. Icon
10. Kbd
11. Link
12. Loading
13. Progress
14. Progress-radial
15. Separator
16. Stat
17. Step
18. Subheading
19. Text
20. Timeline-item
21. Menu-separator
22. Menu-title
23. Group
24. Main
25. Nav

**Required Changes:**
- Add proper semantic HTML5 tags
- Add ARIA labels where text is not visible
- Ensure color contrast
- Add alt text for images

#### **Tier 2: Form Components (20 components)**
*Medium complexity - requires form accessibility patterns*

1. Button
2. Checkbox
3. Input
4. Password
5. Radio
6. Range
7. Select
8. Textarea
9. Toggle
10. Checkbox-group
11. Radio-group
12. Select-group
13. File
14. Pin
15. Signature
16. Colorpicker
17. DatePicker
18. DateTime
19. Rating
20. Tags

**Required Changes:**
- Proper label associations
- ARIA states (aria-invalid, aria-required, aria-disabled)
- Error announcements
- Keyboard navigation
- Focus management
- Help text associations (aria-describedby)

#### **Tier 3: Interactive Components (22 components)**
*High complexity - complex interaction patterns*

1. Modal
2. Drawer
3. Dropdown
4. Tabs
5. Accordion
6. Collapse
7. Carousel
8. Menu
9. Menu-item
10. Menu-sub
11. Popover
12. Spotlight
13. Toast
14. Profile
15. Theme-toggle
16. Swap
17. Calendar
18. Card
19. Table
20. Editor
21. Form
22. Fieldset

**Required Changes:**
- Complex ARIA patterns (aria-expanded, aria-controls, aria-selected)
- Keyboard navigation (Arrow keys, Home, End, Escape)
- Focus trap for modals
- Screen reader announcements
- Live regions for dynamic content
- Proper heading hierarchy

#### **Tier 4: Advanced Components (20 components)**
*Very high complexity - specialized accessibility needs*

1. Pagination (+ 7 variants)
2. Steps
3. Image-gallery
4. Image-library
5. Image-slider
6. Chart
7. Choices
8. Choices-offline
9. Markdown
10. List-item
11. Header
12. Tab

**Required Changes:**
- Complex widget patterns
- Virtual focus management
- Roving tabindex
- ARIA grid/treegrid patterns
- Data visualization accessibility
- Alternative text for visual data

### 2.2 Audit Checklist Template

For each component, document:

```markdown
## Component: [Name]

**Tier:** [1-4]
**Blade File:** resources/views/components/[name].blade.php
**PHP Class:** src/View/Components/[Name].php (if exists)
**Documentation:** docs/components/[name].md

### Current State

- [ ] Has semantic HTML
- [ ] Has ARIA attributes
- [ ] Has keyboard navigation
- [ ] Has focus management
- [ ] Has screen reader support
- [ ] Has color contrast (4.5:1 minimum)
- [ ] Has alternative text
- [ ] Has error handling
- [ ] Supports high contrast mode
- [ ] Supports reduced motion

### Required Changes

1. [Specific change 1]
2. [Specific change 2]
...

### WCAG 2.1 Success Criteria

- [ ] 1.1.1 Non-text Content (A)
- [ ] 1.3.1 Info and Relationships (A)
- [ ] 1.4.3 Contrast (Minimum) (AA)
- [ ] 2.1.1 Keyboard (A)
- [ ] 2.1.2 No Keyboard Trap (A)
- [ ] 2.4.3 Focus Order (A)
- [ ] 2.4.7 Focus Visible (AA)
- [ ] 3.2.1 On Focus (A)
- [ ] 3.2.2 On Input (A)
- [ ] 3.3.1 Error Identification (A)
- [ ] 3.3.2 Labels or Instructions (A)
- [ ] 4.1.2 Name, Role, Value (A)

### Testing Checklist

- [ ] Keyboard-only navigation works
- [ ] Screen reader announces correctly
- [ ] Focus indicators are visible
- [ ] Color contrast passes
- [ ] Works in high contrast mode
- [ ] Works with reduced motion
- [ ] aXe DevTools reports no issues
```

---

## Phase 3: Component Implementation (Week 3-8)

### 3.1 Implementation Priority

**Week 3-4: Tier 1 (Simple Components)**
- Quick wins
- Establish patterns
- Build momentum

**Week 4-5: Tier 2 (Form Components)**
- Critical for user interaction
- Common patterns
- High impact

**Week 5-6: Tier 3 (Interactive Components)**
- Complex patterns
- Requires extensive testing
- Medium-high impact

**Week 7-8: Tier 4 (Advanced Components)**
- Most complex
- Specialized solutions
- Lower usage but important

### 3.2 Example Implementation: Button Component

#### Before (button.blade.php - lines 1-10)
```blade
@if($link)
    <a href="{!! $link !!}"
@else
    <button
@endif

    wire:key="{{ $uuid }}"
    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
```

#### After (button.blade.php - enhanced)
```blade
@if($link)
    <a href="{!! $link !!}"
       role="button"
       @if($attributes->get('disabled'))
           aria-disabled="true"
           tabindex="-1"
       @else
           tabindex="{{ $tabindex ?? 0 }}"
       @endif
@else
    <button
       type="{{ $attributes->get('type') ?? 'button' }}"
@endif

    wire:key="{{ $uuid }}"
    aria-label="{{ $ariaLabel ?? $label ?? null }}"
    @if($attributes->get('disabled'))
        aria-disabled="true"
    @endif
    @if($spinner && $attributes->has('wire:target'))
        aria-busy="false"
        x-data="{ busy: false }"
        x-init="
            $wire.on('processing', () => { busy = true; $el.setAttribute('aria-busy', 'true'); });
            $wire.on('processed', () => { busy = false; $el.setAttribute('aria-busy', 'false'); });
        "
    @endif
    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
```

### 3.3 Example Implementation: Modal Component

#### Enhanced modal.blade.php
```blade
<dialog
    {{ $attributes->except('wire:model')->class(["modal"]) }}
    role="dialog"
    aria-modal="true"
    @if($title)
        aria-labelledby="modal-title-{{ $uuid }}"
    @endif
    aria-describedby="modal-content-{{ $uuid }}"

    @if($id)
        id="{{ $id }}"
    @else
        x-data="{open: @entangle($attributes->wire('model')).live }"
        x-init="
            $watch('open', value => {
                if (!value) {
                    $dispatch('close');
                    // Return focus to trigger element
                    if (window.lastFocusedElement) {
                        window.lastFocusedElement.focus();
                    }
                } else {
                    // Store last focused element
                    window.lastFocusedElement = document.activeElement;
                    $dispatch('open');
                    // Focus first focusable element
                    $nextTick(() => {
                        const firstFocusable = $el.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex=\"-1\"])');
                        firstFocusable?.focus();
                    });
                }
            })
        "
        :class="{'modal-open !animate-none': open}"
        :open="open"
        @if(!$persistent)
            @keydown.escape.window = "$wire.{{ $attributes->wire('model')->value() }} = false"
        @endif
    @endif

    x-trap="open"
    x-bind:inert="!open"
>
    <div class="modal-box {{ $boxClass }}"
         id="modal-content-{{ $uuid }}"
         role="document">

        @if(!$persistent)
            @if ($id)
                <x-artisanpack-button
                    class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]"
                    icon="o-x-mark"
                    type="button"
                    onclick="document.getElementById('{{ $id }}').close()"
                    aria-label="Close modal"
                />
            @else
                <x-artisanpack-button
                    class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]"
                    icon="o-x-mark"
                    type="button"
                    @click="$wire.{{ $attributes->wire('model')->value() }} = false"
                    aria-label="Close modal"
                />
            @endif
        @endif

        @if($title)
            <x-artisanpack-header
                :title="$title"
                :subtitle="$subtitle"
                size="text-xl"
                :separator="$separator"
                class="!mb-5"
                id="modal-title-{{ $uuid }}"
            />
        @endif

        <div>
            {{ $slot }}
        </div>

        @if($separator && $actions)
            <hr class="border-t-[length:var(--border)] border-base-content/10 mt-5" />
        @endif

        @if($actions)
            <div class="modal-action">
                {{ $actions }}
            </div>
        @endif
    </div>

    @if(!$persistent)
        <div class="modal-backdrop" aria-hidden="true">
            @if ($id)
                <button type="button" onclick="document.getElementById('{{ $id }}').close()" aria-label="Close modal">close</button>
            @else
                <button @click="$wire.{{ $attributes->wire('model')->value() }} = false" type="button" aria-label="Close modal">close</button>
            @endif
        </div>
    @endif
</dialog>
```

### 3.4 Implementation Pattern for All Components

For each component:

1. **Add Semantic Roles**
   - Use correct HTML5 semantic elements
   - Add ARIA roles where needed

2. **Implement Keyboard Navigation**
   - Add keyboard event handlers
   - Support standard key combinations
   - Implement focus management

3. **Add ARIA Attributes**
   - aria-label / aria-labelledby
   - aria-describedby
   - aria-invalid / aria-required (forms)
   - aria-expanded / aria-controls (interactive)
   - aria-selected / aria-current (navigation)
   - aria-live (dynamic content)

4. **Implement Focus Management**
   - Visible focus indicators
   - Focus trap for modals/drawers
   - Return focus on close
   - Skip links where appropriate

5. **Add Screen Reader Support**
   - Live regions for updates
   - Status announcements
   - Descriptive labels
   - Hide decorative elements (aria-hidden)

6. **Support User Preferences**
   - Respect prefers-reduced-motion
   - Support prefers-contrast: high
   - Support dark mode
   - Configurable animations

---

## Phase 4: Testing Framework (Week 6-10)

### 4.1 Automated Accessibility Testing

#### Install aXe Core for Laravel

```bash
composer require --dev axe-core/axe-core
npm install --save-dev axe-core @axe-core/playwright
```

#### Base Accessibility Test Class

**File:** `tests/Accessibility/AccessibilityTestCase.php`

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\Accessibility\Testing\AccessibilityValidator;

abstract class AccessibilityTestCase extends TestCase
{
    protected AccessibilityValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new AccessibilityValidator([
            'standard' => 'WCAG2AA',
            'rules' => $this->getAccessibilityRules(),
        ]);
    }

    /**
     * Get WCAG rules to test
     */
    protected function getAccessibilityRules(): array
    {
        return [
            // Level A
            'area-alt',
            'aria-allowed-attr',
            'aria-required-attr',
            'aria-required-children',
            'aria-required-parent',
            'aria-roles',
            'aria-valid-attr',
            'aria-valid-attr-value',
            'button-name',
            'bypass',
            'color-contrast',
            'document-title',
            'duplicate-id',
            'form-field-multiple-labels',
            'frame-title',
            'html-has-lang',
            'html-lang-valid',
            'image-alt',
            'input-button-name',
            'input-image-alt',
            'label',
            'link-name',
            'list',
            'listitem',
            'meta-refresh',
            'object-alt',
            'role-img-alt',
            'td-headers-attr',
            'th-has-data-cells',
            'valid-lang',
            'video-caption',

            // Level AA
            'color-contrast-enhanced',
            'focus-order-semantics',
            'link-in-text-block',
            'meta-viewport',
            'region',
            'scrollable-region-focusable',
        ];
    }

    /**
     * Test component for accessibility violations
     */
    protected function assertAccessible(string $html): void
    {
        $violations = $this->validator->validate($html);

        $this->assertEmpty(
            $violations,
            $this->formatViolations($violations)
        );
    }

    /**
     * Format violations for readable output
     */
    protected function formatViolations(array $violations): string
    {
        if (empty($violations)) {
            return '';
        }

        $output = "\n\nAccessibility Violations Found:\n\n";

        foreach ($violations as $violation) {
            $output .= sprintf(
                "Rule: %s (%s)\n",
                $violation['id'],
                $violation['impact']
            );
            $output .= sprintf("Description: %s\n", $violation['description']);
            $output .= sprintf("Help: %s\n", $violation['help']);
            $output .= sprintf("Help URL: %s\n\n", $violation['helpUrl']);

            foreach ($violation['nodes'] as $node) {
                $output .= sprintf("  Element: %s\n", $node['html']);
                $output .= sprintf("  Fix: %s\n\n", implode(', ', $node['failureSummary']));
            }
        }

        return $output;
    }

    /**
     * Test keyboard navigation
     */
    protected function assertKeyboardNavigable(string $html, array $expectedFocusableElements): void
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // Find all focusable elements
        $focusableQuery = "//a[@href] | //button[not(@disabled)] | //input[not(@disabled)] | //select[not(@disabled)] | //textarea[not(@disabled)] | //*[@tabindex and @tabindex != '-1']";
        $focusableElements = $xpath->query($focusableQuery);

        $this->assertGreaterThan(
            0,
            $focusableElements->length,
            'No focusable elements found'
        );
    }

    /**
     * Test color contrast
     */
    protected function assertColorContrast(string $foreground, string $background, float $minRatio = 4.5): void
    {
        $ratio = $this->calculateContrastRatio($foreground, $background);

        $this->assertGreaterThanOrEqual(
            $minRatio,
            $ratio,
            sprintf(
                'Color contrast ratio %.2f:1 does not meet WCAG AA standard (%.2f:1). Foreground: %s, Background: %s',
                $ratio,
                $minRatio,
                $foreground,
                $background
            )
        );
    }

    /**
     * Calculate contrast ratio between two colors
     */
    protected function calculateContrastRatio(string $color1, string $color2): float
    {
        $l1 = $this->getRelativeLuminance($color1);
        $l2 = $this->getRelativeLuminance($color2);

        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Get relative luminance of a color
     */
    protected function getRelativeLuminance(string $hex): float
    {
        $hex = ltrim($hex, '#');

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
```

### 4.2 Component Accessibility Tests

#### Example: Button Component Test

**File:** `tests/Accessibility/Components/ButtonAccessibilityTest.php`

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Components;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use Illuminate\Support\Facades\Blade;

class ButtonAccessibilityTest extends AccessibilityTestCase
{
    /** @test */
    public function button_has_accessible_name()
    {
        $html = Blade::render('<x-artisanpack-button>Click Me</x-artisanpack-button>');

        $this->assertStringContainsString('Click Me', $html);
        $this->assertAccessible($html);
    }

    /** @test */
    public function button_with_icon_only_has_aria_label()
    {
        $html = Blade::render('<x-artisanpack-button icon="o-home" aria-label="Go to home" />');

        $this->assertStringContainsString('aria-label="Go to home"', $html);
        $this->assertAccessible($html);
    }

    /** @test */
    public function disabled_button_has_aria_disabled()
    {
        $html = Blade::render('<x-artisanpack-button disabled>Click Me</x-artisanpack-button>');

        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertAccessible($html);
    }

    /** @test */
    public function button_link_has_role_button()
    {
        $html = Blade::render('<x-artisanpack-button link="/home">Go Home</x-artisanpack-button>');

        $this->assertStringContainsString('role="button"', $html);
        $this->assertAccessible($html);
    }

    /** @test */
    public function loading_button_has_aria_busy()
    {
        $html = Blade::render('<x-artisanpack-button spinner>Save</x-artisanpack-button>');

        $this->assertStringContainsString('aria-busy', $html);
    }

    /** @test */
    public function button_is_keyboard_accessible()
    {
        $html = Blade::render('<x-artisanpack-button>Click Me</x-artisanpack-button>');

        $this->assertKeyboardNavigable($html, ['button']);
    }

    /** @test */
    public function button_has_sufficient_color_contrast()
    {
        // Test default button colors
        $this->assertColorContrast('#ffffff', '#3b82f6'); // Primary button
        $this->assertColorContrast('#ffffff', '#64748b'); // Secondary button
        $this->assertColorContrast('#ffffff', '#ef4444'); // Error button
    }
}
```

#### Example: Modal Component Test

**File:** `tests/Accessibility/Components/ModalAccessibilityTest.php`

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Components;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class ModalAccessibilityTest extends AccessibilityTestCase
{
    /** @test */
    public function modal_has_dialog_role()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                Content
            </x-artisanpack-modal>
        ');

        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('aria-modal="true"', $html);
    }

    /** @test */
    public function modal_has_accessible_label()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                Content
            </x-artisanpack-modal>
        ');

        $this->assertStringContainsString('aria-labelledby', $html);
        $this->assertStringContainsString('Test Modal', $html);
    }

    /** @test */
    public function modal_has_focus_trap()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                Content
            </x-artisanpack-modal>
        ');

        $this->assertStringContainsString('x-trap="open"', $html);
        $this->assertStringContainsString('x-bind:inert="!open"', $html);
    }

    /** @test */
    public function modal_close_button_has_accessible_label()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                Content
            </x-artisanpack-modal>
        ');

        $this->assertStringContainsString('aria-label="Close modal"', $html);
    }

    /** @test */
    public function modal_responds_to_escape_key()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                Content
            </x-artisanpack-modal>
        ');

        $this->assertStringContainsString('@keydown.escape', $html);
    }

    /** @test */
    public function modal_passes_axe_validation()
    {
        $html = Blade::render('
            <x-artisanpack-modal wire:model="showModal" title="Test Modal">
                <p>Modal content goes here</p>
            </x-artisanpack-modal>
        ');

        $this->assertAccessible($html);
    }
}
```

### 4.3 Automated Test Generation

Create a script to generate accessibility tests for all components:

**File:** `generate-accessibility-tests.php`

```php
<?php

/**
 * Generate accessibility test stubs for all components
 */

$componentsPath = __DIR__ . '/resources/views/components';
$testsPath = __DIR__ . '/tests/Accessibility/Components';

$components = array_filter(
    scandir($componentsPath),
    fn($file) => str_ends_with($file, '.blade.php')
);

foreach ($components as $component) {
    $name = str_replace('.blade.php', '', $component);
    $className = str_replace(['-', '_'], '', ucwords($name, '-_'));

    $testFile = $testsPath . '/' . $className . 'AccessibilityTest.php';

    if (file_exists($testFile)) {
        echo "Skipping $className (already exists)\n";
        continue;
    }

    $template = <<<PHP
<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Components;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use Illuminate\Support\Facades\Blade;

class {$className}AccessibilityTest extends AccessibilityTestCase
{
    /** @test */
    public function {$name}_has_accessible_markup()
    {
        \$html = Blade::render('<x-artisanpack-{$name}>Content</x-artisanpack-{$name}>');

        \$this->assertAccessible(\$html);
    }

    /** @test */
    public function {$name}_is_keyboard_accessible()
    {
        \$html = Blade::render('<x-artisanpack-{$name}>Content</x-artisanpack-{$name}>');

        // TODO: Implement keyboard accessibility test
        \$this->markTestIncomplete('Keyboard accessibility test not yet implemented');
    }

    /** @test */
    public function {$name}_has_proper_aria_attributes()
    {
        \$html = Blade::render('<x-artisanpack-{$name}>Content</x-artisanpack-{$name}>');

        // TODO: Implement ARIA attributes test
        \$this->markTestIncomplete('ARIA attributes test not yet implemented');
    }

    /** @test */
    public function {$name}_meets_color_contrast_requirements()
    {
        // TODO: Implement color contrast test
        \$this->markTestIncomplete('Color contrast test not yet implemented');
    }
}

PHP;

    file_put_contents($testFile, $template);
    echo "Generated $testFile\n";
}

echo "\nDone! Generated " . count($components) . " test files.\n";
```

### 4.4 Continuous Integration

**File:** `.gitlab-ci.yml` (add to existing)

```yaml
accessibility-tests:
  stage: test
  script:
    - composer install
    - php artisan test --testsuite=Accessibility
  only:
    - merge_requests
    - main
  artifacts:
    when: always
    reports:
      junit: reports/accessibility-junit.xml
    paths:
      - reports/accessibility-report.html

accessibility-audit:
  stage: test
  image: mcr.microsoft.com/playwright:latest
  script:
    - npm ci
    - npm run build
    - npx playwright test --project=accessibility
  only:
    - merge_requests
    - main
  artifacts:
    when: always
    paths:
      - playwright-report/
      - accessibility-results/
```

---

## Phase 5: Documentation (Week 8-10)

### 5.1 Accessibility Guidelines Document

**File:** `docs/accessibility/guidelines.md`

```markdown
# Accessibility Guidelines

## Introduction

This document outlines the accessibility standards and best practices for ArtisanPack UI Livewire UI Components. All components in this library aim to meet WCAG 2.1 Level AA compliance.

## Core Principles

### 1. Perceivable
Information and user interface components must be presentable to users in ways they can perceive.

- **1.1 Text Alternatives**: Provide text alternatives for non-text content
- **1.2 Time-based Media**: Provide alternatives for time-based media
- **1.3 Adaptable**: Create content that can be presented in different ways
- **1.4 Distinguishable**: Make it easier for users to see and hear content

### 2. Operable
User interface components and navigation must be operable.

- **2.1 Keyboard Accessible**: Make all functionality available from a keyboard
- **2.2 Enough Time**: Provide users enough time to read and use content
- **2.3 Seizures and Physical Reactions**: Do not design content in a way that is known to cause seizures
- **2.4 Navigable**: Provide ways to help users navigate and find content
- **2.5 Input Modalities**: Make it easier for users to operate functionality through various inputs

### 3. Understandable
Information and the operation of user interface must be understandable.

- **3.1 Readable**: Make text content readable and understandable
- **3.2 Predictable**: Make web pages appear and operate in predictable ways
- **3.3 Input Assistance**: Help users avoid and correct mistakes

### 4. Robust
Content must be robust enough that it can be interpreted by a wide variety of user agents.

- **4.1 Compatible**: Maximize compatibility with current and future user agents

## Component-Specific Guidelines

### Form Components

#### Required Patterns
- All form inputs must have associated labels
- Error messages must be announced to screen readers
- Required fields must be indicated with `aria-required="true"`
- Invalid fields must be indicated with `aria-invalid="true"`
- Help text must be associated using `aria-describedby`

#### Example
\`\`\`blade
<x-artisanpack-input
    label="Email Address"
    hint="We'll never share your email"
    wire:model="email"
    required
    aria-describedby="email-help"
/>
\`\`\`

### Interactive Components

#### Modal/Dialog Pattern
- Must use `<dialog>` element or `role="dialog"`
- Must have `aria-modal="true"`
- Must have accessible label via `aria-labelledby` or `aria-label`
- Must trap focus within the modal
- Must return focus to trigger element on close
- Must respond to Escape key

#### Dropdown/Menu Pattern
- Must use proper ARIA roles (`menu`, `menuitem`)
- Must support keyboard navigation (Arrow keys, Home, End, Escape)
- Must indicate expanded state with `aria-expanded`
- Must associate trigger with menu using `aria-controls`

### Navigation Components

#### Tab Pattern
- Must use `role="tablist"`, `role="tab"`, `role="tabpanel"`
- Must indicate selected tab with `aria-selected="true"`
- Must support keyboard navigation (Arrow keys, Home, End)
- Must associate tabs with panels using `aria-controls` and `aria-labelledby`

## Keyboard Navigation Standards

### Standard Key Bindings

| Key | Action |
|-----|--------|
| Tab | Move focus to next focusable element |
| Shift+Tab | Move focus to previous focusable element |
| Enter | Activate button/link |
| Space | Activate button, toggle checkbox |
| Escape | Close modal/dialog/dropdown |
| Arrow Keys | Navigate within component (tabs, menus, etc.) |
| Home | Jump to first item |
| End | Jump to last item |

### Focus Management

- All interactive elements must be keyboard accessible
- Focus order must be logical and intuitive
- Focus must be visible (2px outline, high contrast)
- Focus must not be trapped (except in modals)
- Custom keyboard shortcuts should not override browser shortcuts

## Color Contrast Requirements

### WCAG AA Standards

- **Normal text**: Minimum 4.5:1 contrast ratio
- **Large text** (18pt+): Minimum 3:1 contrast ratio
- **UI components**: Minimum 3:1 contrast ratio
- **Graphical objects**: Minimum 3:1 contrast ratio

### Testing Tools

- Use the color contrast checker in browser DevTools
- Test with high contrast mode enabled
- Verify with automated tools (aXe, WAVE)

## Screen Reader Support

### ARIA Live Regions

Use for dynamic content updates:

\`\`\`html
<div aria-live="polite" aria-atomic="true">
    <!-- Dynamic content -->
</div>
\`\`\`

- **polite**: Announces when user is idle
- **assertive**: Announces immediately

### Hiding Content

\`\`\`html
<!-- Visually hidden but announced -->
<span class="sr-only">For screen readers only</span>

<!-- Hidden from screen readers -->
<div aria-hidden="true">Decorative content</div>
\`\`\`

## Testing Checklist

For each component, verify:

- [ ] Keyboard navigation works without mouse
- [ ] Screen reader announces all important information
- [ ] Focus indicators are clearly visible
- [ ] Color contrast meets WCAG AA standards
- [ ] Component works in high contrast mode
- [ ] Animations respect `prefers-reduced-motion`
- [ ] All interactive elements have accessible names
- [ ] Error states are properly announced
- [ ] No accessibility violations in aXe DevTools

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WebAIM Resources](https://webaim.org/resources/)
- [The A11Y Project](https://www.a11yproject.com/)
```

### 5.2 Component Documentation Template

Add accessibility section to each component doc:

**Example: `docs/components/button.md`**

```markdown
## Accessibility

### ARIA Attributes

The Button component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `aria-label` | string | Provides an accessible label when button text is not sufficient |
| `aria-describedby` | string | References element(s) that describe the button |
| `aria-disabled` | boolean | Indicates button is disabled (automatically set) |
| `aria-busy` | boolean | Indicates button is processing (when `spinner` is active) |

### Keyboard Support

| Key | Action |
|-----|--------|
| Enter / Space | Activates the button |
| Tab | Moves focus to/from the button |

### Screen Reader Behavior

- Button label is announced
- Disabled state is announced
- Loading/busy state is announced
- Icon-only buttons must have `aria-label`

### Example: Accessible Icon Button

\`\`\`blade
<x-artisanpack-button
    icon="o-trash"
    aria-label="Delete item"
    color="error"
/>
\`\`\`

### Testing

Run accessibility tests:
\`\`\`bash
php artisan test --filter ButtonAccessibilityTest
\`\`\`
```

### 5.3 Accessibility Statement

**File:** `docs/accessibility/statement.md`

```markdown
# Accessibility Statement

## Commitment

ArtisanPack UI Livewire UI Components is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.

## Conformance Status

The Web Content Accessibility Guidelines (WCAG) defines requirements for designers and developers to improve accessibility for people with disabilities. It defines three levels of conformance: Level A, Level AA, and Level AAA.

**ArtisanPack UI Livewire UI Components aims for WCAG 2.1 Level AA conformance.**

- **Level A**: All Level A success criteria are met
- **Level AA**: All Level A and AA success criteria are met
- **Level AAA**: Some Level AAA criteria are met (not required for conformance)

## Accessibility Features

Our components include the following accessibility features:

### Keyboard Navigation
- All interactive components are fully keyboard accessible
- Standard keyboard shortcuts are supported
- Focus indicators are clearly visible
- Focus is properly managed in modals and complex widgets

### Screen Reader Support
- Proper ARIA labels and descriptions
- Live regions for dynamic content
- Semantic HTML5 elements
- Alternative text for images and icons

### Visual Accessibility
- Minimum 4.5:1 color contrast ratio (WCAG AA)
- Support for high contrast mode
- Resizable text without loss of functionality
- No information conveyed by color alone

### User Preferences
- Support for reduced motion preferences
- Support for high contrast preferences
- Configurable animation speeds
- Dark mode support

## Known Issues

We are aware of the following accessibility issues and are working to address them:

- [List any known issues here]

## Feedback

We welcome your feedback on the accessibility of ArtisanPack UI Livewire UI Components. Please let us know if you encounter accessibility barriers:

- **Email**: accessibility@artisanpack.com
- **Issue Tracker**: https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues

We try to respond to feedback within 3 business days.

## Technical Specifications

ArtisanPack UI Livewire UI Components relies on the following technologies:

- HTML5
- WAI-ARIA 1.2
- CSS3
- JavaScript (Alpine.js)
- Livewire 3

These technologies are relied upon for conformance with the accessibility standards used.

## Assessment Approach

ArtisanPack UI Livewire UI Components has been assessed using the following approaches:

- **Self-evaluation**: Internal accessibility audits
- **Automated testing**: aXe, WAVE, Lighthouse
- **Manual testing**: Keyboard navigation, screen reader testing
- **User testing**: Testing with assistive technology users

## Date

This statement was created on 2025-11-06 using information from automated and manual testing.

Last reviewed: 2025-11-06
```

---

## Phase 6: Tooling & Automation (Week 9-10)

### 6.1 Artisan Commands

#### Accessibility Audit Command

**File:** `src/Console/Commands/AuditAccessibility.php`

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ArtisanPack\Accessibility\Services\AccessibilityAuditor;

class AuditAccessibility extends Command
{
    protected $signature = 'artisanpack:audit-accessibility
                            {--component= : Specific component to audit}
                            {--format=table : Output format (table, json, html)}
                            {--output= : Output file path}';

    protected $description = 'Audit components for accessibility compliance';

    public function handle()
    {
        $this->info('🔍 Auditing components for accessibility...');

        $component = $this->option('component');
        $components = $component
            ? [$component]
            : $this->getAllComponents();

        $auditor = new AccessibilityAuditor();
        $results = [];

        foreach ($components as $comp) {
            $this->info("Auditing: {$comp}");

            $result = $auditor->audit($comp);
            $results[$comp] = $result;

            if ($result['violations'] > 0) {
                $this->warn("  ⚠️  {$result['violations']} violations found");
            } else {
                $this->info("  ✅ No violations");
            }
        }

        $this->displayResults($results);

        return 0;
    }

    protected function getAllComponents(): array
    {
        $path = resource_path('views/components');
        $files = File::files($path);

        return collect($files)
            ->map(fn($file) => $file->getFilenameWithoutExtension())
            ->toArray();
    }

    protected function displayResults(array $results): void
    {
        $format = $this->option('format');

        switch ($format) {
            case 'json':
                $this->displayJsonResults($results);
                break;
            case 'html':
                $this->displayHtmlResults($results);
                break;
            default:
                $this->displayTableResults($results);
        }
    }

    protected function displayTableResults(array $results): void
    {
        $headers = ['Component', 'Violations', 'Warnings', 'Status'];
        $rows = [];

        foreach ($results as $component => $result) {
            $rows[] = [
                $component,
                $result['violations'],
                $result['warnings'],
                $result['violations'] === 0 ? '✅ Pass' : '❌ Fail',
            ];
        }

        $this->table($headers, $rows);

        $totalViolations = collect($results)->sum('violations');
        $totalWarnings = collect($results)->sum('warnings');

        $this->newLine();
        $this->info("Total Violations: {$totalViolations}");
        $this->info("Total Warnings: {$totalWarnings}");
    }

    protected function displayJsonResults(array $results): void
    {
        $output = json_encode($results, JSON_PRETTY_PRINT);

        if ($file = $this->option('output')) {
            File::put($file, $output);
            $this->info("Results saved to: {$file}");
        } else {
            $this->line($output);
        }
    }

    protected function displayHtmlResults(array $results): void
    {
        $html = view('livewire-ui-components::accessibility-report', [
            'results' => $results,
            'timestamp' => now(),
        ])->render();

        if ($file = $this->option('output')) {
            File::put($file, $html);
            $this->info("Report saved to: {$file}");
        } else {
            $this->line($html);
        }
    }
}
```

#### Generate Accessibility Report Command

**File:** `src/Console/Commands/GenerateAccessibilityReport.php`

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateAccessibilityReport extends Command
{
    protected $signature = 'artisanpack:accessibility-report
                            {--output=accessibility-report.html : Output file path}';

    protected $description = 'Generate comprehensive accessibility report';

    public function handle()
    {
        $this->info('📊 Generating accessibility report...');

        // Run tests and collect results
        $this->call('test', [
            '--testsuite' => 'Accessibility',
            '--log-junit' => 'reports/accessibility-junit.xml',
        ]);

        // Parse results
        $results = $this->parseTestResults();

        // Generate report
        $html = view('livewire-ui-components::accessibility-comprehensive-report', [
            'results' => $results,
            'timestamp' => now(),
            'summary' => $this->generateSummary($results),
        ])->render();

        $output = $this->option('output');
        File::put($output, $html);

        $this->info("✅ Report generated: {$output}");

        return 0;
    }

    protected function parseTestResults(): array
    {
        // Parse JUnit XML
        $xml = simplexml_load_file('reports/accessibility-junit.xml');

        $results = [];
        foreach ($xml->testsuite as $suite) {
            $component = (string) $suite['name'];
            $results[$component] = [
                'tests' => (int) $suite['tests'],
                'failures' => (int) $suite['failures'],
                'errors' => (int) $suite['errors'],
                'time' => (float) $suite['time'],
            ];
        }

        return $results;
    }

    protected function generateSummary(array $results): array
    {
        return [
            'total_tests' => collect($results)->sum('tests'),
            'total_failures' => collect($results)->sum('failures'),
            'total_errors' => collect($results)->sum('errors'),
            'pass_rate' => $this->calculatePassRate($results),
            'components_tested' => count($results),
        ];
    }

    protected function calculatePassRate(array $results): float
    {
        $total = collect($results)->sum('tests');
        $failures = collect($results)->sum('failures') + collect($results)->sum('errors');

        if ($total === 0) {
            return 0;
        }

        return round((($total - $failures) / $total) * 100, 2);
    }
}
```

### 6.2 IDE Snippets

Create accessibility snippets for common IDEs:

**File:** `.vscode/accessibility-snippets.code-snippets`

```json
{
  "ARIA Label": {
    "prefix": "aria-label",
    "body": [
      "aria-label=\"${1:Accessible label}\""
    ],
    "description": "Add ARIA label attribute"
  },
  "ARIA Described By": {
    "prefix": "aria-describedby",
    "body": [
      "aria-describedby=\"${1:element-id}\""
    ],
    "description": "Add ARIA describedby attribute"
  },
  "Accessible Button": {
    "prefix": "a11y-button",
    "body": [
      "<x-artisanpack-button",
      "    aria-label=\"${1:Button label}\"",
      "    @if($2:disabled)",
      "        aria-disabled=\"true\"",
      "    @endif",
      ">",
      "    ${3:Button text}",
      "</x-artisanpack-button>"
    ],
    "description": "Create accessible button component"
  },
  "Accessible Input": {
    "prefix": "a11y-input",
    "body": [
      "<x-artisanpack-input",
      "    label=\"${1:Label}\"",
      "    hint=\"${2:Help text}\"",
      "    wire:model=\"${3:model}\"",
      "    ${4:required}",
      "    aria-describedby=\"${3:model}-help\"",
      "/>"
    ],
    "description": "Create accessible input component"
  },
  "Accessible Modal": {
    "prefix": "a11y-modal",
    "body": [
      "<x-artisanpack-modal",
      "    wire:model=\"${1:showModal}\"",
      "    title=\"${2:Modal Title}\"",
      "    aria-labelledby=\"modal-title-${3:id}\"",
      ">",
      "    ${4:Modal content}",
      "</x-artisanpack-modal>"
    ],
    "description": "Create accessible modal component"
  }
}
```

### 6.3 Git Hooks

**File:** `.githooks/pre-commit`

```bash
#!/bin/bash

# Run accessibility tests before commit
echo "Running accessibility tests..."

php artisan test --testsuite=Accessibility --stop-on-failure

if [ $? -ne 0 ]; then
    echo "❌ Accessibility tests failed. Please fix violations before committing."
    exit 1
fi

echo "✅ Accessibility tests passed!"
exit 0
```

---

## Phase 7: Maintenance & Monitoring (Ongoing)

### 7.1 Accessibility Checklist for New Components

When creating new components:

```markdown
## New Component Accessibility Checklist

- [ ] Semantic HTML elements used
- [ ] Proper ARIA roles added
- [ ] ARIA states managed (expanded, selected, etc.)
- [ ] ARIA properties set (label, describedby, etc.)
- [ ] Keyboard navigation implemented
- [ ] Focus management implemented
- [ ] Focus indicators visible
- [ ] Color contrast validated (4.5:1 minimum)
- [ ] Screen reader tested
- [ ] High contrast mode tested
- [ ] Reduced motion supported
- [ ] Accessibility tests written
- [ ] Documentation updated
- [ ] Examples include accessibility features
```

### 7.2 Regular Audits

Schedule regular accessibility audits:

- **Weekly**: Automated test suite runs
- **Monthly**: Manual accessibility review of new components
- **Quarterly**: Full accessibility audit with external tools
- **Annually**: Third-party accessibility audit

### 7.3 Monitoring & Metrics

Track accessibility metrics:

```php
// Track in analytics/monitoring
[
    'accessibility' => [
        'test_pass_rate' => 98.5,
        'violations_count' => 12,
        'warnings_count' => 34,
        'components_compliant' => 85,
        'components_total' => 87,
        'compliance_rate' => 97.7,
    ]
]
```

---

## Implementation Timeline

### Week 1-2: Foundation
- ✅ Set up accessibility utilities and traits
- ✅ Create CSS and JS accessibility helpers
- ✅ Configure accessibility settings
- ✅ Document infrastructure

### Week 3-4: Tier 1 Components (25 components)
- Implement semantic markup
- Add basic ARIA attributes
- Test and validate
- Document changes

### Week 4-5: Tier 2 Components (20 components)
- Implement form accessibility patterns
- Add error handling
- Test keyboard navigation
- Document changes

### Week 5-6: Tier 3 Components (22 components)
- Implement complex ARIA patterns
- Add keyboard navigation
- Implement focus management
- Test and validate
- Document changes

### Week 7-8: Tier 4 Components (20 components)
- Implement specialized patterns
- Add advanced keyboard navigation
- Test complex interactions
- Document changes

### Week 6-10: Testing (Parallel)
- Write automated tests
- Set up CI/CD
- Create test utilities
- Generate test reports

### Week 8-10: Documentation (Parallel)
- Write accessibility guidelines
- Update component docs
- Create accessibility statement
- Generate compliance report

---

## Success Criteria

### Technical Metrics
- ✅ 100% of components pass automated aXe tests
- ✅ 100% of components have keyboard navigation
- ✅ 100% of components meet WCAG 2.1 AA color contrast
- ✅ 95%+ test coverage for accessibility features
- ✅ Zero high-severity accessibility violations

### User Experience Metrics
- ✅ All components usable with keyboard only
- ✅ All components work with screen readers (NVDA, JAWS, VoiceOver)
- ✅ All components support high contrast mode
- ✅ All animations respect reduced motion preferences
- ✅ All forms provide clear error feedback

### Documentation Metrics
- ✅ 100% of components have accessibility documentation
- ✅ Accessibility guidelines published
- ✅ Accessibility statement published
- ✅ Code examples include accessibility features
- ✅ Testing documentation complete

---

## Resources & References

### Standards & Guidelines
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA 1.2 Specification](https://www.w3.org/TR/wai-aria-1.2/)
- [ARIA Authoring Practices Guide (APG)](https://www.w3.org/WAI/ARIA/apg/)

### Testing Tools
- [aXe DevTools](https://www.deque.com/axe/devtools/)
- [WAVE](https://wave.webaim.org/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse)
- [Pa11y](https://pa11y.org/)

### Screen Readers
- [NVDA](https://www.nvaccess.org/) (Windows, Free)
- [JAWS](https://www.freedomscientific.com/products/software/jaws/) (Windows)
- [VoiceOver](https://www.apple.com/accessibility/voiceover/) (macOS/iOS, Built-in)
- [TalkBack](https://support.google.com/accessibility/android/answer/6283677) (Android, Built-in)

### Learning Resources
- [WebAIM](https://webaim.org/)
- [The A11Y Project](https://www.a11yproject.com/)
- [Inclusive Components](https://inclusive-components.design/)
- [Accessibility Developer Guide](https://www.accessibility-developer-guide.com/)

---

## Risk Mitigation

### Potential Risks

1. **Breaking Changes**
   - Risk: Accessibility changes may break existing implementations
   - Mitigation: Deprecation warnings, backward compatibility layer, migration guide

2. **Performance Impact**
   - Risk: Additional ARIA attributes and JavaScript may impact performance
   - Mitigation: Performance testing, lazy loading, optimization

3. **Browser Compatibility**
   - Risk: Not all browsers support all ARIA features
   - Mitigation: Progressive enhancement, polyfills, fallbacks

4. **Testing Coverage**
   - Risk: Automated tests may not catch all accessibility issues
   - Mitigation: Manual testing, user testing, third-party audits

5. **Maintenance Burden**
   - Risk: Keeping accessibility features up-to-date
   - Mitigation: Automated testing, regular audits, clear documentation

---

## Appendix

### A. Component Priority Matrix

| Priority | Components | Rationale |
|----------|-----------|-----------|
| **Critical** | Button, Input, Select, Modal, Alert | High usage, core functionality |
| **High** | Checkbox, Radio, Dropdown, Tabs, Table | Common interactive patterns |
| **Medium** | Card, Badge, Avatar, Progress, Menu | Visual components, moderate usage |
| **Low** | Swap, Theme-toggle, Stat | Specialized, lower usage |

### B. WCAG 2.1 AA Requirements Summary

| Criterion | Level | Requirement |
|-----------|-------|-------------|
| 1.4.3 Contrast (Minimum) | AA | 4.5:1 for normal text, 3:1 for large text |
| 2.1.1 Keyboard | A | All functionality via keyboard |
| 2.1.2 No Keyboard Trap | A | Keyboard focus not trapped |
| 2.4.7 Focus Visible | AA | Visible focus indicator |
| 3.2.1 On Focus | A | No unexpected context changes |
| 3.3.1 Error Identification | A | Errors identified in text |
| 3.3.2 Labels or Instructions | A | Labels provided for inputs |
| 4.1.2 Name, Role, Value | A | Proper ARIA implementation |

### C. Browser & AT Testing Matrix

| Browser | Screen Reader | Priority |
|---------|--------------|----------|
| Chrome | NVDA | High |
| Firefox | NVDA | High |
| Edge | JAWS | Medium |
| Safari | VoiceOver | High |
| Chrome (Mobile) | TalkBack | Medium |
| Safari (iOS) | VoiceOver | Medium |

### D. Glossary

- **ARIA**: Accessible Rich Internet Applications
- **AT**: Assistive Technology
- **SR**: Screen Reader
- **WCAG**: Web Content Accessibility Guidelines
- **WAI**: Web Accessibility Initiative
- **aXe**: Accessibility testing engine
- **Focusable**: Element that can receive keyboard focus
- **Semantic HTML**: HTML that reinforces meaning
- **Live Region**: Area where content updates are announced
- **Focus Trap**: Constraining focus within a component
- **Roving Tabindex**: Managing focus with arrow keys

---

**End of Implementation Plan**

*For questions or clarifications, contact the ArtisanPack UI team.*
