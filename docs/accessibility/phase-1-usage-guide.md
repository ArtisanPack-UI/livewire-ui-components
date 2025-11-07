# Phase 1 Accessibility Features - Usage Guide

This guide explains how to use the Phase 1 accessibility features implemented in ArtisanPack UI Livewire UI Components.

## Overview

Phase 1 provides the foundation for WCAG 2.1 AA compliance:

- **PHP Traits** for accessibility features
- **Configuration** for accessibility settings
- **CSS Utilities** for visual accessibility
- **JavaScript/Alpine.js** directives for interactive accessibility

---

## Installation

### 1. Publish Assets

Publish the accessibility assets to your project:

```bash
# Publish all assets
php artisan vendor:publish --tag=artisanpack-assets

# Or publish only accessibility assets
php artisan vendor:publish --tag=artisanpack-accessibility
```

### 2. Include Assets in Your Layout

Add the accessibility CSS and JavaScript to your layout file:

```blade
<!-- In your layout file (e.g., resources/views/layouts/app.blade.php) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->

    <!-- Accessibility CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/artisanpack-ui/css/accessibility.css') }}">
</head>
<body>
    <!-- Your content -->

    <!-- Your existing scripts -->

    <!-- Accessibility JavaScript (load after Alpine.js) -->
    <script src="{{ asset('vendor/artisanpack-ui/js/accessibility.js') }}"></script>
</body>
</html>
```

### 3. Configure Accessibility Settings

Optionally publish and customize the configuration:

```bash
php artisan vendor:publish --tag=livewire-ui-components.config
```

Edit `config/livewire-ui-components.php` to customize accessibility features:

```php
'accessibility' => [
    'enabled' => true,
    'high_contrast' => true,
    'reduced_motion' => true,
    // ... other settings
],
```

---

## Using PHP Traits in Components

### HasAccessibility Trait

Add ARIA attributes and accessibility features to your custom components:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use ArtisanPack\LivewireUiComponents\Traits\HasAccessibility;

class CustomButton extends Component
{
    use HasAccessibility;

    public function __construct(
        public string $label,
        public ?string $icon = null,
    ) {
        // Set accessibility properties
        if ($icon && !$label) {
            $this->ariaLabel = 'Button with icon only';
        }
    }

    public function render()
    {
        return view('components.custom-button');
    }
}
```

In your Blade template:

```blade
<button {{ $attributes->merge($buildAccessibilityAttributes()) }}>
    @if($icon)
        <x-artisanpack-icon :name="$icon" />
    @endif
    {{ $label }}
</button>
```

### HasKeyboardNavigation Trait

Add keyboard navigation support:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use ArtisanPack\LivewireUiComponents\Traits\HasKeyboardNavigation;

class NavigationMenu extends Component
{
    use HasKeyboardNavigation;

    public function __construct()
    {
        $this->navigationDirection = 'vertical';
        $this->enableHomeEnd = true;
    }

    public function render()
    {
        return view('components.navigation-menu');
    }
}
```

In your Blade template:

```blade
<nav {{ $attributes->merge($getKeyboardAttributes()) }}>
    {!! $getAlpineKeyboardDirective() !!}

    <!-- Menu items -->
</nav>
```

### HasFocusManagement Trait

Manage focus for modals and dialogs:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use ArtisanPack\LivewireUiComponents\Traits\HasFocusManagement;

class CustomModal extends Component
{
    use HasFocusManagement;

    public function __construct()
    {
        $this->trapFocus = true;
        $this->restoreFocus = true;
        $this->autoFocus = true;
    }

    public function render()
    {
        return view('components.custom-modal');
    }
}
```

---

## Using CSS Utilities

### Focus Management

```blade
<!-- Standard focus visible -->
<button class="focus-visible">Click Me</button>

<!-- Enhanced focus with ring -->
<button class="focus-ring">Click Me</button>

<!-- Focus within container -->
<div class="focus-within-highlight">
    <input type="text" />
</div>
```

### Skip Links

Add skip navigation for keyboard users:

```blade
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header>...</header>

    <main id="main-content" tabindex="-1">
        <!-- Main content -->
    </main>
</body>
```

### Screen Reader Only Text

```blade
<!-- Hidden visually but read by screen readers -->
<span class="sr-only">Additional context for screen readers</span>

<!-- Shows on focus -->
<span class="sr-only-focusable">Visible when focused</span>
```

### Live Regions

Announce dynamic content changes:

```blade
<div aria-live="polite" aria-atomic="true" class="sr-only">
    {{ $statusMessage }}
</div>

<!-- Visual announcement -->
<div class="live-region-visual" x-show="showAnnouncement">
    {{ $announcement }}
</div>
```

### Reduced Motion Support

Animations automatically respect user preferences via CSS. To manually check:

```blade
<div
    x-data="{ duration: $store.accessibility.getAnimationDuration(300) }"
    x-transition:enter.duration="duration"
>
    Content
</div>
```

---

## Using Alpine.js Directives

### x-keyboard-nav

Add keyboard navigation to components:

```blade
<div x-keyboard-nav="{ direction: 'vertical', homeEnd: true }">
    <button>Item 1</button>
    <button>Item 2</button>
    <button>Item 3</button>
</div>
```

**Configuration options:**
- `direction`: 'vertical', 'horizontal', or 'both'
- `homeEnd`: Enable Home/End keys (default: true)
- `pageKeys`: Enable Page Up/Down keys (default: false)

### x-focus-trap

Trap focus within a container (for modals):

```blade
<div
    x-data="{ open: false }"
    x-show="open"
    x-focus-trap.inert.noscroll="open"
>
    <h2>Modal Title</h2>
    <button @click="open = false">Close</button>
</div>
```

**Modifiers:**
- `.inert` - Makes background content inert
- `.noscroll` - Prevents body scrolling

### x-live-region

Create ARIA live regions:

```blade
<div x-live-region.assertive>
    Error messages appear here
</div>

<div x-live-region.polite.atomic>
    Status updates appear here
</div>
```

**Modifiers:**
- `.assertive` - Interrupts screen reader immediately
- `.polite` - Waits for screen reader to finish (default)
- `.atomic` - Reads entire content, not just changes

### x-announce

Announce messages to screen readers:

```blade
<div x-data="{ message: '' }">
    <button @click="message = 'Action completed successfully'">
        Do Action
    </button>

    <div x-announce="message"></div>
</div>
```

### x-skip-link

Create skip links:

```blade
<a href="#" x-skip-link="'main-content'">
    Skip to main content
</a>

<main id="main-content">
    <!-- Content -->
</main>
```

---

## Using the $a11y Magic Property

Access accessibility utilities in Alpine components:

```blade
<div x-data="{ ... }">
    <!-- Announce to screen reader -->
    <button @click="$a11y.announce('Button clicked')">
        Click Me
    </button>

    <!-- Focus first/last element -->
    <button @click="$a11y.focusFirst($el.parentElement)">
        Focus First
    </button>

    <button @click="$a11y.focusLast($el.parentElement)">
        Focus Last
    </button>

    <!-- Check user preferences -->
    <div x-show="!$a11y.prefersReducedMotion()" x-transition>
        Animated content
    </div>
</div>
```

**Available methods:**
- `$a11y.announce(message, priority)` - Announce to screen reader
- `$a11y.focusFirst(container)` - Focus first focusable element
- `$a11y.focusLast(container)` - Focus last focusable element
- `$a11y.prefersReducedMotion()` - Check reduced motion preference
- `$a11y.prefersHighContrast()` - Check high contrast preference
- `$a11y.getAnimationDuration(defaultMs)` - Get animation duration based on preference

---

## Using the Accessibility Store

Access global accessibility state:

```blade
<div x-data>
    <!-- Check if user prefers reduced motion -->
    <template x-if="$store.accessibility.reducedMotion">
        <p>Reduced motion enabled</p>
    </template>

    <!-- Check if user prefers high contrast -->
    <template x-if="$store.accessibility.highContrast">
        <p>High contrast mode enabled</p>
    </template>

    <!-- Check if keyboard mode is active -->
    <template x-if="$store.accessibility.keyboardMode">
        <p>Keyboard navigation active</p>
    </template>
</div>
```

---

## Keyboard Shortcuts

Register global keyboard shortcuts:

```javascript
// In your JavaScript
window.keyboardShortcuts.register('ctrl+k', (e) => {
    // Open search/spotlight
    Alpine.store('spotlight').open = true;
});

window.keyboardShortcuts.register('?', (e) => {
    // Show keyboard shortcuts help
    Alpine.store('keyboardHelp').open = true;
});
```

---

## Configuration Options

### Environment Variables

Add to your `.env` file:

```env
# Enable/disable accessibility features globally
ACCESSIBILITY_ENABLED=true

# Enable high contrast mode support
ACCESSIBILITY_HIGH_CONTRAST=true

# Enable reduced motion support
ACCESSIBILITY_REDUCED_MOTION=true

# Show keyboard shortcut hints
ACCESSIBILITY_KEYBOARD_HINTS=true

# Validation and testing (development only)
ACCESSIBILITY_VALIDATE_CONTRAST=false
ACCESSIBILITY_STRICT_MODE=false
ACCESSIBILITY_LOG_VIOLATIONS=false
```

### Configuration File

In `config/livewire-ui-components.php`:

```php
'accessibility' => [
    'enabled' => env('ACCESSIBILITY_ENABLED', true),

    'focus_indicator' => [
        'width' => '2px',
        'color' => 'var(--primary)',
        'offset' => '2px',
        'style' => 'solid',
    ],

    'screen_reader' => [
        'announce_changes' => true,
        'live_regions' => true,
    ],

    'keyboard' => [
        'enabled' => true,
        'show_hints' => true,
        'allow_shortcuts' => true,
    ],

    'focus_management' => [
        'trap_in_modals' => true,
        'restore_on_close' => true,
        'auto_focus_first' => true,
        'skip_links' => true,
    ],
],
```

---

## Testing Accessibility

### Browser Testing

1. **Keyboard Navigation**: Try navigating with Tab, Shift+Tab, Arrow keys
2. **Screen Reader**: Test with NVDA (Windows), JAWS, or VoiceOver (Mac)
3. **Reduced Motion**: Enable in system preferences and test animations
4. **High Contrast**: Enable in system preferences and test visibility
5. **Zoom**: Zoom to 200% and verify layout

### Developer Tools

- **aXe DevTools**: Browser extension for automated testing
- **WAVE**: Web accessibility evaluation tool
- **Lighthouse**: Chrome DevTools accessibility audit

### Debug Mode

Enable debug mode to highlight accessibility issues:

```html
<body data-a11y-debug="true">
```

This will outline elements with potential issues:
- Orange outline: aria-hidden without tabindex="-1"
- Red outline: roles without labels
- Red outline: empty buttons without labels

---

## Common Patterns

### Accessible Button with Icon

```blade
<x-artisanpack-button
    icon="o-trash"
    aria-label="Delete item"
    :aria-describedby="$hasWarning ? 'delete-warning' : null"
>
    Delete
</x-artisanpack-button>

@if($hasWarning)
    <p id="delete-warning" class="sr-only">
        This action cannot be undone
    </p>
@endif
```

### Accessible Form Input

```blade
<div>
    <label for="email" class="block">
        Email Address
        <span class="required-indicator" aria-label="required"></span>
    </label>

    <input
        type="email"
        id="email"
        aria-required="true"
        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
        aria-describedby="email-hint {{ $errors->has('email') ? 'email-error' : '' }}"
    />

    <p id="email-hint" class="help-text">
        We'll never share your email
    </p>

    @error('email')
        <p id="email-error" role="alert" class="error-message">
            {{ $message }}
        </p>
    @enderror
</div>
```

### Accessible Modal

```blade
<div
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
>
    <button @click="open = true">
        Open Modal
    </button>

    <div
        x-show="open"
        x-focus-trap.inert.noscroll="open"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
        class="fixed inset-0 z-50"
    >
        <div class="modal-backdrop" @click="open = false"></div>

        <div class="modal-box">
            <h2 id="modal-title">Modal Title</h2>

            <p>Modal content...</p>

            <button @click="open = false" aria-label="Close modal">
                Close
            </button>
        </div>
    </div>
</div>
```

### Accessible Tab Navigation

```blade
<div
    x-data="{ activeTab: 0 }"
    x-keyboard-nav="{ direction: 'horizontal', homeEnd: true }"
>
    <div role="tablist" aria-label="Content sections">
        <button
            role="tab"
            :aria-selected="activeTab === 0"
            :tabindex="activeTab === 0 ? 0 : -1"
            @click="activeTab = 0"
        >
            Tab 1
        </button>
        <button
            role="tab"
            :aria-selected="activeTab === 1"
            :tabindex="activeTab === 1 ? 0 : -1"
            @click="activeTab = 1"
        >
            Tab 2
        </button>
    </div>

    <div role="tabpanel" x-show="activeTab === 0" aria-labelledby="tab-0">
        Content 1
    </div>

    <div role="tabpanel" x-show="activeTab === 1" aria-labelledby="tab-1">
        Content 2
    </div>
</div>
```

---

## Next Steps

Phase 1 provides the foundation. The next phases will:

- **Phase 2**: Audit and categorize all 87 components
- **Phase 3**: Implement accessibility features in each component
- **Phase 4**: Add comprehensive automated testing
- **Phase 5**: Create detailed documentation
- **Phase 6**: Add development tooling and automation
- **Phase 7**: Establish ongoing maintenance procedures

---

## Support

For questions or issues:
- Check the [main implementation plan](../ACCESSIBILITY-IMPLEMENTATION-PLAN.md)
- Review WCAG 2.1 guidelines: https://www.w3.org/WAI/WCAG21/quickref/
- Review ARIA patterns: https://www.w3.org/WAI/ARIA/apg/

---

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WebAIM Resources](https://webaim.org/)
- [The A11Y Project](https://www.a11yproject.com/)
