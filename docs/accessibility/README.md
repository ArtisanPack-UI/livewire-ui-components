# Accessibility Documentation

Welcome to the Accessibility Documentation for Livewire UI Components. This library is committed to providing fully accessible UI components that meet WCAG 2.1 Level AA standards.

## 📚 Documentation Index

### Core Documentation

- **[Testing Guide](TESTING.md)** - Comprehensive guide to unit testing accessibility
- **[Browser Testing Guide](BROWSER-TESTING.md)** - Guide to browser-based accessibility testing with Dusk
- **[Component Patterns](PATTERNS.md)** - Accessibility patterns used in components
- **[Implementation Plan](../../ACCESSIBILITY-IMPLEMENTATION-PLAN.md)** - Full implementation roadmap

### Quick Links

- [WCAG 2.1 Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)

---

## 🎯 Accessibility Commitment

Every component in this library:

✅ Meets WCAG 2.1 Level AA standards
✅ Supports keyboard navigation
✅ Works with screen readers
✅ Has proper focus management
✅ Provides clear error feedback
✅ Uses semantic HTML
✅ Has comprehensive accessibility tests

---

## 🧪 Testing

We implement accessibility testing at multiple levels:

### 1. Unit Tests
Test component markup for proper ARIA attributes and semantic HTML.

```bash
./vendor/bin/pest --filter=Accessibility
```

### 2. Integration Tests
Test keyboard navigation and focus management.

### 3. Browser Tests
Test real interactions with assistive technologies using Laravel Dusk.

```bash
php artisan dusk --filter=accessibility
```

### 4. Manual Testing
Periodic testing with actual screen readers (NVDA, JAWS, VoiceOver).

See the [Testing Guide](TESTING.md) and [Browser Testing Guide](BROWSER-TESTING.md) for detailed information.

---

## 🏗️ Architecture

### Component Tiers

Components are organized by accessibility complexity:

#### **Tier 1: Simple Components (25 components)**
Basic components requiring primarily semantic HTML and ARIA labels.

Examples: Alert, Badge, Icon, Progress, Separator

#### **Tier 2: Form Components (20 components)**
Form elements requiring label associations, error handling, and validation feedback.

Examples: Button, Input, Select, Checkbox, Radio, Textarea

#### **Tier 3: Interactive Components (22 components)**
Complex interactive patterns requiring focus management and keyboard navigation.

Examples: Modal, Drawer, Dropdown, Tabs, Accordion

#### **Tier 4: Advanced Components (20 components)**
Highly complex widgets with sophisticated accessibility requirements.

Examples: Table, Pagination, Steps, Calendar, Editor

---

## 🔑 Key Accessibility Features

### ARIA Patterns Implemented

- **Dialog Pattern** - Modals and Drawers with focus trapping
- **Menu Pattern** - Dropdowns with arrow key navigation
- **Tab Pattern** - Tabs with automatic activation
- **Table Pattern** - Sortable tables with proper scope
- **Form Pattern** - Complete form accessibility
- **Alert Pattern** - Live region announcements
- **Progress Pattern** - Progress indicators

### Focus Management

- **Focus Trap** - In modals and drawers
- **Focus Return** - Returns to trigger element
- **Auto-focus** - Focuses appropriate element when opened
- **Roving Tabindex** - In menus and tabs
- **Visible Focus Indicators** - Clear focus styling
- **Logical Tab Order** - Follows DOM order

### Keyboard Navigation

- **Arrow Keys** - Navigate menus, tabs, and lists
- **Home/End** - Jump to first/last item
- **Escape** - Close modals, dropdowns
- **Enter/Space** - Activate buttons and controls
- **Tab** - Move between focusable elements

### Screen Reader Support

- **Live Regions** - Announce dynamic changes
- **Accessible Names** - All interactive elements labeled
- **Error Announcements** - Form errors announced
- **State Changes** - Updates announced to users
- **Hidden Decorative Content** - Icons marked aria-hidden

---

## 📋 Component Accessibility Matrix

| Component | ARIA | Keyboard | Focus | Screen Reader | Status |
|-----------|------|----------|-------|---------------|--------|
| Alert | ✅ | N/A | ✅ | ✅ | Complete |
| Button | ✅ | ✅ | ✅ | ✅ | Complete |
| Input | ✅ | ✅ | ✅ | ✅ | Complete |
| Modal | ✅ | ✅ | ✅ | ✅ | Complete |
| Dropdown | ✅ | ✅ | ✅ | ✅ | Complete |
| Tabs | ✅ | ✅ | ✅ | ✅ | Complete |
| Table | ✅ | ✅ | ✅ | ✅ | Complete |
| ... | ... | ... | ... | ... | ... |

*See implementation plan for complete matrix*

---

## 🚀 Quick Start

### Running Accessibility Tests

```bash
# Run all accessibility tests
./vendor/bin/pest --filter=Accessibility

# Run specific component tests
./vendor/bin/pest tests/Accessibility/Examples/ButtonAccessibilityTest.php

# Run with coverage
./vendor/bin/pest --coverage --filter=Accessibility
```

### Writing New Tests

```php
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsAriaAttributes;

class MyComponentAccessibilityTest extends AccessibilityTestCase
{
    use TestsAriaAttributes;

    /** @test */
    public function component_is_accessible(): void
    {
        $html = $this->renderComponent('my-component', [
            'label' => 'Test',
        ]);

        $this->assertHasAriaLabel($html);
        $this->assertIsKeyboardFocusable($html);
    }
}
```

See [Testing Guide](TESTING.md) for comprehensive documentation.

---

## 🛠️ Development Guidelines

### Before Implementing a Component

1. ✅ Research applicable ARIA patterns
2. ✅ Review [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
3. ✅ Write accessibility tests FIRST (TDD)
4. ✅ Implement with accessibility in mind
5. ✅ Test with keyboard navigation
6. ✅ Test with screen readers
7. ✅ Validate with automated tools

### Required for Every Component

- Semantic HTML
- Proper ARIA attributes
- Keyboard navigation support
- Focus management
- Screen reader compatibility
- Error handling (for forms)
- Comprehensive tests
- Documentation

---

## 📊 Compliance Standards

### WCAG 2.1 Level AA

All components meet these criteria:

#### Perceivable
- ✅ Text alternatives for images
- ✅ Color is not the only visual means of conveying information
- ✅ 4.5:1 minimum color contrast ratio

#### Operable
- ✅ All functionality available from keyboard
- ✅ Keyboard focus is visible
- ✅ Navigation mechanisms are consistent
- ✅ Focus order follows logical sequence

#### Understandable
- ✅ Error messages are clear and helpful
- ✅ Labels and instructions provided for inputs
- ✅ Consistent identification and navigation

#### Robust
- ✅ Valid HTML and ARIA
- ✅ Compatible with assistive technologies
- ✅ Resilient to browser/AT combinations

---

## 🔍 Testing Tools

### Automated Tools

- **aXe DevTools** - Browser extension for accessibility audits
- **Lighthouse** - Google Chrome accessibility audit
- **Pa11y** - Automated accessibility testing
- **WAVE** - Web Accessibility Evaluation Tool

### Manual Testing

- **NVDA** (Windows) - Free screen reader
- **JAWS** (Windows) - Professional screen reader
- **VoiceOver** (macOS/iOS) - Built-in screen reader
- **TalkBack** (Android) - Built-in screen reader

### Keyboard Testing

Test all components with keyboard only:
- Tab key navigation
- Arrow keys
- Enter/Space activation
- Escape to close
- Home/End keys

---

## 💡 Common Patterns

### Modal/Dialog

```html
<dialog role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <h2 id="modal-title">Modal Title</h2>
  <div id="modal-content">...</div>
  <button aria-label="Close modal">×</button>
</dialog>
```

### Form Input

```html
<label for="email">Email Address</label>
<input
  id="email"
  type="email"
  aria-required="true"
  aria-invalid="false"
  aria-describedby="email-hint email-error"
/>
<div id="email-hint">We'll never share your email</div>
<div id="email-error" role="alert" aria-live="polite">
  <!-- Error message when invalid -->
</div>
```

### Dropdown Menu

```html
<button
  aria-haspopup="menu"
  aria-expanded="false"
  aria-controls="menu-1"
>
  Menu
</button>
<ul id="menu-1" role="menu" aria-labelledby="menu-button">
  <li role="menuitem">Item 1</li>
  <li role="menuitem">Item 2</li>
</ul>
```

---

## 📖 Additional Resources

### External References

- [Web Content Accessibility Guidelines (WCAG) 2.1](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices Guide](https://www.w3.org/WAI/ARIA/apg/)
- [MDN Web Accessibility](https://developer.mozilla.org/en-US/docs/Web/Accessibility)
- [A11y Project](https://www.a11yproject.com/)
- [WebAIM](https://webaim.org/)
- [Deque University](https://dequeuniversity.com/)

### Community

- [GitHub Discussions](https://github.com/your-repo/discussions)
- [Issue Tracker](https://github.com/your-repo/issues)

---

## 🤝 Contributing

We welcome contributions to improve accessibility!

When contributing:

1. Follow existing accessibility patterns
2. Write comprehensive tests
3. Document any new patterns
4. Test with keyboard and screen readers
5. Ensure WCAG 2.1 AA compliance

See [CONTRIBUTING.md](../../CONTRIBUTING.md) for details.

---

## 📄 License

This documentation is part of the Livewire UI Components library and shares the same license.

---

**Questions?** Open an issue or discussion on GitHub.

**Found an accessibility issue?** Please report it immediately - accessibility bugs are high priority!
