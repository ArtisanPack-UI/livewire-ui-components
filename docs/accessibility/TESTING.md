# Accessibility Testing Guide

## Overview

This guide explains how to test accessibility features in the Livewire UI Components library. We follow WCAG 2.1 Level AA standards and implement comprehensive testing at multiple levels.

## Table of Contents

1. [Testing Philosophy](#testing-philosophy)
2. [Test Structure](#test-structure)
3. [Writing Accessibility Tests](#writing-accessibility-tests)
4. [Available Test Utilities](#available-test-utilities)
5. [Testing Patterns by Component Type](#testing-patterns-by-component-type)
6. [Running Tests](#running-tests)
7. [Continuous Integration](#continuous-integration)

---

## Testing Philosophy

### Goals

- **WCAG 2.1 Level AA Compliance**: Every component meets accessibility standards
- **Automated Testing**: Catch accessibility issues early in development
- **Developer-Friendly**: Easy-to-use test utilities and clear error messages
- **Comprehensive Coverage**: Test all aspects of accessibility

### Test Levels

1. **Unit Tests**: Test component markup for ARIA attributes and semantic HTML
2. **Integration Tests**: Test keyboard navigation and focus management
3. **Browser Tests**: Test real user interactions with assistive technologies
4. **Manual Tests**: Periodic testing with actual screen readers

---

## Test Structure

### Directory Organization

```
tests/
├── Accessibility/
│   ├── AccessibilityTestCase.php          # Base test class
│   ├── Traits/
│   │   ├── TestsAriaAttributes.php        # ARIA testing helpers
│   │   ├── TestsKeyboardNavigation.php    # Keyboard testing helpers
│   │   └── TestsFocusManagement.php       # Focus testing helpers
│   └── Examples/
│       ├── ButtonAccessibilityTest.php    # Example button tests
│       ├── ModalAccessibilityTest.php     # Example modal tests
│       └── InputAccessibilityTest.php     # Example input tests
```

### Base Test Class

All accessibility tests extend `AccessibilityTestCase`:

```php
<?php

namespace Tests\Accessibility;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;

class MyComponentAccessibilityTest extends AccessibilityTestCase
{
    /** @test */
    public function component_is_accessible(): void
    {
        $html = $this->renderComponent('component-name', [
            'label' => 'Test',
        ]);

        $this->assertHasAriaLabel($html);
    }
}
```

---

## Writing Accessibility Tests

### 1. Test Component Markup

Test that components render with proper semantic HTML and ARIA attributes:

```php
/** @test */
public function button_has_accessible_name(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.button', [
        'label' => 'Submit Form',
    ]);

    // Check for accessible name
    $this->assertHasAccessibleName($html, 'button');
}
```

### 2. Test ARIA Attributes

```php
/** @test */
public function modal_has_proper_aria(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.modal', [
        'title' => 'Confirm',
    ]);

    $this->assertHasRole($html, 'dialog');
    $this->assertStringContainsString('aria-modal="true"', $html);
    $this->assertHasAriaLabel($html);
}
```

### 3. Test Keyboard Navigation

```php
/** @test */
public function dropdown_supports_arrow_keys(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.dropdown', [
        'label' => 'Menu',
    ]);

    $this->assertHasArrowKeyNavigation($html);
    $this->assertHasEscapeKey($html);
}
```

### 4. Test Focus Management

```php
/** @test */
public function modal_manages_focus(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.modal', [
        'title' => 'Test',
    ]);

    $this->assertHasFocusTrap($html);
    $this->assertReturnsFocus($html);
    $this->assertAutoFocuses($html);
}
```

### 5. Test Error States

```php
/** @test */
public function input_with_error_is_accessible(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.input', [
        'label' => 'Email',
        'id' => 'email-input',
    ]);

    // When there's an error
    $this->assertHasAriaInvalid($html, true);
    $this->assertHasErrorAssociation($html, 'email-input');
}
```

---

## Available Test Utilities

### AccessibilityTestCase Methods

#### Markup Assertions

- `assertHasRole(string $html, string $role)` - Check for ARIA role
- `assertHasAccessibleName(string $html, string $selector)` - Check for accessible name
- `assertHasLabel(string $html, string $inputId)` - Check for form label
- `assertSupportsKeyboard(string $html, string $element)` - Check keyboard support
- `assertHasErrorAssociation(string $html, string $inputId)` - Check error associations
- `assertHasLiveRegion(string $html, string $politeness)` - Check live regions
- `assertHasDialogAccessibility(string $html)` - Check dialog pattern
- `assertHasTableAccessibility(string $html)` - Check table accessibility
- `assertHasNavigationLandmark(string $html)` - Check nav landmarks
- `assertImagesHaveAlt(string $html)` - Check image alt text

### TestsAriaAttributes Trait

- `assertHasAriaLabel(string $html)` - Check for aria-label or aria-labelledby
- `assertHasAriaDescribedby(string $html, string $expectedId)` - Check aria-describedby
- `assertHasAriaExpanded(string $html, $state)` - Check aria-expanded state
- `assertHasAriaSelected(string $html, $state)` - Check aria-selected state
- `assertHasAriaCurrent(string $html, string $value)` - Check aria-current
- `assertHasAriaControls(string $html, string $expectedId)` - Check aria-controls
- `assertHasAriaHaspopup(string $html, string $type)` - Check aria-haspopup
- `assertHasAriaInvalid(string $html, bool $hasError)` - Check aria-invalid
- `assertHasAriaRequired(string $html, bool $isRequired)` - Check aria-required
- `assertHasAriaDisabled(string $html, bool $isDisabled)` - Check aria-disabled
- `assertHasProgressAria(string $html)` - Check progress ARIA
- `assertHasMenuButtonAria(string $html)` - Check menu button ARIA

### TestsKeyboardNavigation Trait

- `assertIsKeyboardFocusable(string $html, string $element)` - Check focusability
- `assertHasKeyboardHandlers(string $html, array $keys)` - Check keyboard handlers
- `assertHasArrowKeyNavigation(string $html)` - Check arrow key support
- `assertHasHomeEndKeys(string $html)` - Check Home/End support
- `assertHasEscapeKey(string $html)` - Check Escape key handler
- `assertHasRovingTabindex(string $html)` - Check roving tabindex
- `assertHasEnterSpaceActivation(string $html)` - Check Enter/Space activation
- `assertDisabledNotFocusable(string $html)` - Check disabled state

### TestsFocusManagement Trait

- `assertHasFocusTrap(string $html)` - Check focus trap
- `assertReturnsFocus(string $html)` - Check focus return
- `assertAutoFocuses(string $html)` - Check auto-focus
- `assertHasVisibleFocus(string $html)` - Check focus indicators
- `assertManagesInert(string $html)` - Check inert management
- `assertSequentialFocus(string $html)` - Check tab order
- `assertHasSkipLink(string $html)` - Check skip links
- `assertLogicalFocusOrder(string $html)` - Check focus order

---

## Testing Patterns by Component Type

### Simple Components (Alerts, Badges, etc.)

```php
/** @test */
public function alert_has_proper_role(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.alert', [
        'title' => 'Success',
    ]);

    $this->assertHasRole($html, 'alert');
    $this->assertHasLiveRegion($html, 'polite');
}
```

### Form Components

```php
/** @test */
public function select_has_label_association(): void
{
    $id = 'country-select';

    $html = $this->renderComponent('livewire-ui-components::components.select', [
        'label' => 'Country',
        'id' => $id,
        'options' => [
            ['value' => 'us', 'label' => 'United States'],
        ],
    ]);

    $this->assertHasLabel($html, $id);
}
```

### Interactive Components (Modals, Dropdowns, etc.)

```php
/** @test */
public function dropdown_implements_menu_pattern(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.dropdown', [
        'label' => 'Options',
    ]);

    $this->assertHasRole($html, 'menu');
    $this->assertHasArrowKeyNavigation($html);
    $this->assertHasMenuButtonAria($html);
}
```

### Navigation Components

```php
/** @test */
public function tabs_implement_tab_pattern(): void
{
    $html = $this->renderComponent('livewire-ui-components::components.tabs', []);

    $this->assertStringContainsString('role="tablist"', $html);
    $this->assertStringContainsString('role="tab"', $html);
    $this->assertStringContainsString('role="tabpanel"', $html);
    $this->assertHasArrowKeyNavigation($html);
}
```

---

## Running Tests

### Run All Accessibility Tests

```bash
./vendor/bin/pest --filter=Accessibility
```

### Run Specific Test File

```bash
./vendor/bin/pest tests/Accessibility/Examples/ButtonAccessibilityTest.php
```

### Run with Coverage

```bash
./vendor/bin/pest --coverage --filter=Accessibility
```

### Watch Mode

```bash
./vendor/bin/pest --watch --filter=Accessibility
```

---

## Continuous Integration

### GitHub Actions Example

```yaml
name: Accessibility Tests

on: [push, pull_request]

jobs:
  accessibility:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - run: composer install
      - run: ./vendor/bin/pest --filter=Accessibility
```

---

## Best Practices

### DO:

✅ Test all interactive elements for keyboard accessibility
✅ Test all form elements for proper label associations
✅ Test error states and announcements
✅ Test focus management in modals and dialogs
✅ Test ARIA attributes are present and correct
✅ Test images have alt text
✅ Test tables have proper structure

### DON'T:

❌ Skip accessibility tests for "simple" components
❌ Only test the happy path
❌ Forget to test keyboard navigation
❌ Ignore focus management
❌ Test visual appearance only
❌ Rely solely on automated tools

---

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [MDN Accessibility](https://developer.mozilla.org/en-US/docs/Web/Accessibility)
- [A11y Project Checklist](https://www.a11yproject.com/checklist/)

---

## Getting Help

If you're unsure how to test a specific accessibility feature:

1. Check the example tests in `tests/Accessibility/Examples/`
2. Review this documentation
3. Ask in the project's GitHub discussions
4. Consult WCAG 2.1 guidelines

---

## Contributing

When adding new components:

1. Write accessibility tests BEFORE implementation
2. Use existing test utilities and traits
3. Follow the patterns in example tests
4. Document any new accessibility patterns
5. Ensure all tests pass before submitting PR

---

**Remember**: Accessibility is not optional. Every component must be fully accessible to all users.
