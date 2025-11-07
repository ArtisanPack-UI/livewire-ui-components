# Browser-Based Accessibility Testing

## Overview

This guide covers browser-based accessibility testing using Laravel Dusk. Browser tests verify accessibility features that can only be tested in a real browser environment, such as:

- **Keyboard navigation** - Real keyboard events and focus management
- **Focus trapping** - Focus containment in modals and dialogs
- **Screen reader announcements** - Live region updates
- **User interactions** - Click, type, and keyboard events
- **Dynamic ARIA states** - State changes during user interaction

## Table of Contents

1. [Setup](#setup)
2. [Running Browser Tests](#running-browser-tests)
3. [Writing Browser Tests](#writing-browser-tests)
4. [Available Test Utilities](#available-test-utilities)
5. [Testing Patterns](#testing-patterns)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)

---

## Setup

### Prerequisites

Laravel Dusk is already included in the project's dev dependencies:

```json
"laravel/dusk": "^8.0"
```

### Installation

1. **Install ChromeDriver**

```bash
php artisan dusk:install
```

2. **Update .env.dusk.local**

```env
APP_URL=http://localhost:8000
```

3. **Start the test server**

```bash
php artisan serve
```

### Directory Structure

```
tests/
├── Browser/
│   ├── Accessibility/
│   │   ├── AccessibilityBrowserTestCase.php  # Base test class
│   │   ├── ModalBrowserTest.php              # Modal tests
│   │   ├── DropdownBrowserTest.php           # Dropdown tests
│   │   ├── FormInputBrowserTest.php          # Form tests
│   │   └── TabsBrowserTest.php               # Tabs tests
```

---

## Running Browser Tests

### Run All Browser Accessibility Tests

```bash
php artisan dusk --filter=accessibility
```

### Run Specific Test File

```bash
php artisan dusk tests/Browser/Accessibility/ModalBrowserTest.php
```

### Run with Visible Browser (Non-Headless)

Useful for debugging:

```bash
DUSK_HEADLESS_DISABLED=1 php artisan dusk --filter=accessibility
```

### Run Specific Test Method

```bash
php artisan dusk --filter=modal_has_dialog_role_in_browser
```

---

## Writing Browser Tests

### Basic Structure

All browser accessibility tests extend `AccessibilityBrowserTestCase`:

```php
<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\Browser;

class MyComponentBrowserTest extends AccessibilityBrowserTestCase
{
    /**
     * @test
     */
    public function component_has_proper_role(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/my-component');

            $this->assertHasRole($browser, '@my-component', 'button');
        });
    }
}
```

### Test Pages

Create test pages in your application to render components for testing:

```php
// routes/web.php
Route::get('/test/modal', function () {
    return view('test.modal');
});
```

```blade
<!-- resources/views/test/modal.blade.php -->
<div>
    <button dusk="open-modal-button">Open Modal</button>

    <x-artisanpack-modal
        wire:model="showModal"
        title="Test Modal"
        dusk="test-modal"
    >
        <div dusk="modal-content">
            Modal content here
        </div>

        <x-slot:actions>
            <x-artisanpack-button dusk="modal-confirm-button">
                Confirm
            </x-artisanpack-button>
            <x-artisanpack-button dusk="modal-cancel-button">
                Cancel
            </x-artisanpack-button>
        </x-slot:actions>
    </x-artisanpack-modal>
</div>
```

### Using Dusk Selectors

Use `dusk` attributes for reliable element selection:

```blade
<!-- Good: Using dusk attribute -->
<button dusk="submit-button">Submit</button>

<!-- Avoid: Using CSS classes -->
<button class="btn-primary">Submit</button>
```

In tests:

```php
$browser->click('@submit-button'); // @ prefix for dusk selectors
```

---

## Available Test Utilities

The `AccessibilityBrowserTestCase` provides numerous helper methods:

### Role and ARIA Assertions

#### `assertHasRole($browser, string $selector, string $role)`

Assert element has specific ARIA role:

```php
$this->assertHasRole($browser, '@modal', 'dialog');
```

#### `assertHasAccessibleName($browser, string $selector)`

Assert element has accessible name (aria-label or aria-labelledby):

```php
$this->assertHasAccessibleName($browser, '@close-button');
```

#### `assertAriaExpanded($browser, string $selector, bool $expanded)`

Assert aria-expanded state:

```php
// Dropdown is closed
$this->assertAriaExpanded($browser, '@dropdown-trigger', false);

// Dropdown is open
$browser->click('@dropdown-trigger');
$this->assertAriaExpanded($browser, '@dropdown-trigger', true);
```

#### `assertAriaSelected($browser, string $selector, bool $selected)`

Assert aria-selected state:

```php
$this->assertAriaSelected($browser, '@tab-1', true);
$this->assertAriaSelected($browser, '@tab-2', false);
```

#### `assertAriaInvalid($browser, string $selector, bool $invalid)`

Assert aria-invalid state for form inputs:

```php
$browser->type('@email-input', 'invalid-email')
        ->click('@submit-button')
        ->waitFor('@email-error');

$this->assertAriaInvalid($browser, '@email-input', true);
```

### Keyboard Navigation Tests

#### `assertIsKeyboardFocusable($browser, string $selector)`

Assert element can receive keyboard focus:

```php
$this->assertIsKeyboardFocusable($browser, '@menu-item-1');
```

#### `testTabNavigation($browser, array $expectedOrder)`

Test Tab key navigation order:

```php
$this->testTabNavigation($browser, [
    '@first-input',
    '@second-input',
    '@submit-button',
]);
```

#### `testArrowKeyNavigation($browser, string $containerSelector, array $itemSelectors)`

Test arrow key navigation in menus/lists:

```php
$this->testArrowKeyNavigation($browser, '@dropdown-menu', [
    '@menu-item-1',
    '@menu-item-2',
    '@menu-item-3',
]);
```

#### `testEscapeKey($browser, string $triggerSelector, string $contentSelector)`

Test Escape key closes component:

```php
$this->testEscapeKey($browser, '@open-button', '@modal');
```

#### `testEnterSpaceActivation($browser, string $selector, callable $assertActivated)`

Test Enter and Space keys activate control:

```php
$this->testEnterSpaceActivation(
    $browser,
    '@custom-button',
    function ($browser, $key) {
        $browser->assertSee('Button activated');
    }
);
```

### Focus Management Tests

#### `testFocusTrap($browser, string $modalSelector, array $focusableSelectors)`

Test focus trap in modal/dialog:

```php
$this->testFocusTrap($browser, '@modal', [
    '@close-button',
    '@confirm-button',
    '@cancel-button',
]);
```

#### `testFocusReturn($browser, string $triggerSelector, string $modalSelector, string $closeSelector)`

Test focus returns to trigger:

```php
$this->testFocusReturn(
    $browser,
    '@open-modal',
    '@modal',
    '@close-button'
);
```

#### `assertHasVisibleFocus($browser, string $selector)`

Assert element has visible focus indicator:

```php
$browser->click('@button');
$this->assertHasVisibleFocus($browser, '@button');
```

#### `assertDisabledNotFocusable($browser, string $selector)`

Assert disabled elements cannot receive focus:

```php
$this->assertDisabledNotFocusable($browser, '@disabled-button');
```

### Live Region Tests

#### `assertLiveRegionAnnounces($browser, string $liveRegionSelector, string $expectedText)`

Assert live region announces text:

```php
$browser->click('@submit-button')
        ->waitFor('@success-message');

$this->assertLiveRegionAnnounces(
    $browser,
    '@success-message',
    'Form submitted successfully'
);
```

---

## Testing Patterns

### Testing Modal/Dialog Pattern

```php
/** @test */
public function modal_implements_dialog_pattern(): void
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/test/modal');

        // Test role and ARIA
        $browser->click('@open-modal')
                ->waitFor('@modal');

        $this->assertHasRole($browser, '@modal', 'dialog');
        $browser->assertAttribute('@modal', 'aria-modal', 'true');

        // Test focus trap
        $this->testFocusTrap($browser, '@modal', [
            '@close-button',
            '@confirm-button',
            '@cancel-button',
        ]);

        // Test Escape key
        $browser->keys('body', '{escape}')
                ->waitUntilMissing('@modal');

        // Test focus return
        $isFocused = $browser->script(
            "return document.activeElement.matches('[dusk=\"open-modal\"]');"
        )[0];
        $this->assertTrue($isFocused);
    });
}
```

### Testing Menu/Dropdown Pattern

```php
/** @test */
public function dropdown_implements_menu_pattern(): void
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/test/dropdown');

        // Test ARIA attributes
        $this->assertAriaExpanded($browser, '@trigger', false);

        $browser->click('@trigger')
                ->waitFor('@menu');

        $this->assertAriaExpanded($browser, '@trigger', true);
        $this->assertHasRole($browser, '@menu', 'menu');

        // Test keyboard navigation
        $this->testArrowKeyNavigation($browser, '@menu', [
            '@item-1',
            '@item-2',
            '@item-3',
        ]);

        // Test Home/End keys
        $browser->keys('@menu', '{home}')
                ->pause(100);

        $isFocused = $browser->script(
            "return document.activeElement.matches('[dusk=\"item-1\"]');"
        )[0];
        $this->assertTrue($isFocused);
    });
}
```

### Testing Tab Pattern

```php
/** @test */
public function tabs_implement_tab_pattern(): void
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/test/tabs');

        // Test roles
        $this->assertHasRole($browser, '@tablist', 'tablist');
        $this->assertHasRole($browser, '@tab-1', 'tab');

        // Test selection
        $this->assertAriaSelected($browser, '@tab-1', true);
        $this->assertAriaSelected($browser, '@tab-2', false);

        // Test arrow navigation
        $browser->click('@tab-1')
                ->keys('@tablist', '{arrow_right}')
                ->pause(100);

        // Should activate tab 2
        $this->assertAriaSelected($browser, '@tab-2', true);
        $browser->assertVisible('@tabpanel-2');
    });
}
```

### Testing Form Validation

```php
/** @test */
public function form_announces_validation_errors(): void
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/test/form');

        // Submit with invalid data
        $browser->type('@email', 'invalid-email')
                ->click('@submit')
                ->waitFor('@email-error');

        // Test ARIA attributes
        $this->assertAriaInvalid($browser, '@email', true);

        // Test error announcement
        $browser->assertAttribute('@email-error', 'role', 'alert')
                ->assertAttribute('@email-error', 'aria-live', 'polite');

        // Test aria-describedby association
        $describedBy = $browser->attribute('@email', 'aria-describedby');
        $this->assertStringContainsString('email-error', $describedBy);
    });
}
```

---

## Best Practices

### DO:

✅ Use `dusk` attributes for reliable element selection
✅ Wait for elements before asserting (`waitFor`, `waitUntilMissing`)
✅ Add `pause()` after keyboard events to allow state updates
✅ Test real user interactions (keyboard, mouse, focus)
✅ Test both success and error states
✅ Test dynamic ARIA attribute changes
✅ Clean up state between tests (close modals, reset forms)

### DON'T:

❌ Rely on CSS classes for element selection
❌ Test without waiting for elements to load
❌ Skip keyboard navigation tests
❌ Forget to test focus management
❌ Only test the happy path
❌ Hardcode timing values (use `waitFor` instead)

### Example: Proper Waiting

```php
// ❌ Bad: No waiting
$browser->click('@button');
$browser->assertVisible('@modal'); // May fail if modal animates

// ✅ Good: Wait for element
$browser->click('@button')
        ->waitFor('@modal')
        ->assertVisible('@modal');
```

### Example: Checking Focus

```php
// Check if element is focused
$isFocused = $browser->script(
    "return document.activeElement.matches('[dusk=\"my-button\"]');"
)[0];

$this->assertTrue($isFocused, 'Button should be focused');
```

---

## Troubleshooting

### ChromeDriver Issues

If ChromeDriver fails to start:

```bash
# Reinstall ChromeDriver
php artisan dusk:chrome-driver --detect

# Or specify version
php artisan dusk:chrome-driver 131
```

### Tests Timing Out

Increase wait times:

```php
// Wait up to 10 seconds
$browser->waitFor('@element', 10);

// Wait with custom callback
$browser->waitUsing(10, 100, function () use ($browser) {
    return $browser->script("return someCondition();")[0];
});
```

### Element Not Interactable

Ensure element is visible and not covered:

```php
// Wait for element to be visible
$browser->waitFor('@button')
        ->assertVisible('@button')
        ->click('@button');

// Scroll to element
$browser->scrollIntoView('@button')
        ->click('@button');
```

### Debugging Tests

Run tests with visible browser:

```bash
DUSK_HEADLESS_DISABLED=1 php artisan dusk tests/Browser/Accessibility/ModalBrowserTest.php
```

Take screenshots:

```php
$browser->screenshot('debug-modal-state');
```

Dump page HTML:

```php
$html = $browser->script("return document.body.innerHTML;")[0];
file_put_contents('debug.html', $html);
```

---

## Running in CI

### GitHub Actions Example

```yaml
name: Browser Accessibility Tests

on: [push, pull_request]

jobs:
  dusk:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          extensions: mbstring, dom, fileinfo

      - name: Install dependencies
        run: composer install --no-interaction

      - name: Copy .env
        run: php -r "file_exists('.env') || copy('.env.example', '.env');"

      - name: Generate key
        run: php artisan key:generate

      - name: Install ChromeDriver
        run: php artisan dusk:chrome-driver --detect

      - name: Start Chrome Driver
        run: ./vendor/laravel/dusk/bin/chromedriver-linux &

      - name: Run Laravel Server
        run: php artisan serve --no-reload &

      - name: Run Dusk Tests
        run: php artisan dusk --filter=accessibility

      - name: Upload Screenshots
        if: failure()
        uses: actions/upload-artifact@v3
        with:
          name: screenshots
          path: tests/Browser/screenshots

      - name: Upload Console Logs
        if: failure()
        uses: actions/upload-artifact@v3
        with:
          name: console-logs
          path: tests/Browser/console
```

---

## Additional Resources

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WebAIM Keyboard Testing](https://webaim.org/articles/keyboard/)
- [Unit Test Documentation](TESTING.md)
- [Accessibility README](README.md)

---

## Getting Help

If you encounter issues with browser tests:

1. Check this documentation
2. Review example tests in `tests/Browser/Accessibility/`
3. Check Laravel Dusk documentation
4. Open an issue on GitHub

---

**Remember**: Browser tests complement unit tests. Use both for comprehensive accessibility coverage!
