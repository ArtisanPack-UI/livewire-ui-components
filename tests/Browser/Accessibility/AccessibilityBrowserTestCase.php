<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

/**
 * Base test case for browser-based accessibility testing
 *
 * This class provides utilities for testing accessibility features that require
 * real browser interactions, such as keyboard navigation, focus management,
 * and screen reader announcements.
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility
 */
abstract class AccessibilityBrowserTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the Dusk tests are running in Sail.
     */
    protected static function runningInSail(): bool
    {
        return isset($_ENV['LARAVEL_SAIL']) && $_ENV['LARAVEL_SAIL'] === '1';
    }

    /**
     * Assert that an element has a specific role
     */
    protected function assertHasRole($browser, string $selector, string $role): void
    {
        $browser->assertAttribute($selector, 'role', $role);
    }

    /**
     * Assert that an element has an accessible name (aria-label or aria-labelledby)
     */
    protected function assertHasAccessibleName($browser, string $selector): void
    {
        $browser->with($selector, function ($element) {
            $hasLabel = $element->attribute('aria-label') !== null ||
                       $element->attribute('aria-labelledby') !== null;

            $this->assertTrue($hasLabel,
                "Element {$selector} must have aria-label or aria-labelledby");
        });
    }

    /**
     * Assert that an element can receive keyboard focus
     */
    protected function assertIsKeyboardFocusable($browser, string $selector): void
    {
        $browser->script("document.querySelector('{$selector}').focus();");

        $browser->pause(100);

        $activeElement = $browser->script(
            "return document.activeElement.matches('{$selector}');"
        )[0];

        $this->assertTrue($activeElement,
            "Element {$selector} should be keyboard focusable");
    }

    /**
     * Test keyboard navigation with Tab key
     */
    protected function testTabNavigation($browser, array $expectedOrder): void
    {
        foreach ($expectedOrder as $selector) {
            $browser->keys('body', '{tab}');
            $browser->pause(100);

            $activeElement = $browser->script(
                "return document.activeElement.matches('{$selector}');"
            )[0];

            $this->assertTrue($activeElement,
                "Tab should focus element: {$selector}");
        }
    }

    /**
     * Test arrow key navigation in a list/menu
     */
    protected function testArrowKeyNavigation($browser, string $containerSelector, array $itemSelectors): void
    {
        // Focus first item
        $browser->click($itemSelectors[0]);

        // Test down arrow
        foreach (array_slice($itemSelectors, 1) as $selector) {
            $browser->keys($containerSelector, '{arrow_down}');
            $browser->pause(100);

            $activeElement = $browser->script(
                "return document.activeElement.matches('{$selector}');"
            )[0];

            $this->assertTrue($activeElement,
                "Arrow down should focus element: {$selector}");
        }

        // Test up arrow
        foreach (array_reverse(array_slice($itemSelectors, 0, -1)) as $selector) {
            $browser->keys($containerSelector, '{arrow_up}');
            $browser->pause(100);

            $activeElement = $browser->script(
                "return document.activeElement.matches('{$selector}');"
            )[0];

            $this->assertTrue($activeElement,
                "Arrow up should focus element: {$selector}");
        }
    }

    /**
     * Test Escape key to close modal/dropdown
     */
    protected function testEscapeKey($browser, string $triggerSelector, string $contentSelector): void
    {
        // Open the component
        $browser->click($triggerSelector);
        $browser->waitFor($contentSelector);
        $browser->assertVisible($contentSelector);

        // Press Escape
        $browser->keys('body', '{escape}');
        $browser->pause(200);

        // Should be closed
        $browser->assertMissing($contentSelector);
    }

    /**
     * Test focus trap in modal/dialog
     */
    protected function testFocusTrap($browser, string $modalSelector, array $focusableSelectors): void
    {
        $browser->with($modalSelector, function ($modal) use ($focusableSelectors) {
            // Focus last focusable element
            $lastElement = end($focusableSelectors);
            $modal->click($lastElement);

            // Tab should wrap to first element
            $modal->keys('body', '{tab}');
            $modal->pause(100);

            $activeElement = $modal->script(
                "return document.activeElement.matches('{$focusableSelectors[0]}');"
            )[0];

            $this->assertTrue($activeElement,
                "Focus should trap and wrap to first element");

            // Shift+Tab should wrap to last element
            $modal->keys('body', ['{shift}', '{tab}']);
            $modal->pause(100);

            $activeElement = $modal->script(
                "return document.activeElement.matches('{$lastElement}');"
            )[0];

            $this->assertTrue($activeElement,
                "Focus should trap and wrap backwards to last element");
        });
    }

    /**
     * Test that focus returns to trigger element after closing modal
     */
    protected function testFocusReturn($browser, string $triggerSelector, string $modalSelector, string $closeSelector): void
    {
        // Click trigger to open modal
        $browser->click($triggerSelector);
        $browser->waitFor($modalSelector);

        // Close modal
        $browser->click($closeSelector);
        $browser->waitUntilMissing($modalSelector);

        // Focus should return to trigger
        $browser->pause(100);
        $activeElement = $browser->script(
            "return document.activeElement.matches('{$triggerSelector}');"
        )[0];

        $this->assertTrue($activeElement,
            "Focus should return to trigger element after modal closes");
    }

    /**
     * Assert that an element has proper ARIA expanded state
     */
    protected function assertAriaExpanded($browser, string $selector, bool $expanded): void
    {
        $expectedValue = $expanded ? 'true' : 'false';
        $browser->assertAttribute($selector, 'aria-expanded', $expectedValue);
    }

    /**
     * Assert that an element has proper ARIA selected state
     */
    protected function assertAriaSelected($browser, string $selector, bool $selected): void
    {
        $expectedValue = $selected ? 'true' : 'false';
        $browser->assertAttribute($selector, 'aria-selected', $expectedValue);
    }

    /**
     * Assert that an element has proper ARIA invalid state
     */
    protected function assertAriaInvalid($browser, string $selector, bool $invalid): void
    {
        $expectedValue = $invalid ? 'true' : 'false';
        $browser->assertAttribute($selector, 'aria-invalid', $expectedValue);
    }

    /**
     * Test that Enter and Space keys activate a button/control
     */
    protected function testEnterSpaceActivation($browser, string $selector, callable $assertActivated): void
    {
        // Test Enter key
        $browser->click($selector);
        $browser->keys($selector, '{enter}');
        $browser->pause(100);
        $assertActivated($browser, 'enter');

        // Test Space key
        $browser->click($selector);
        $browser->keys($selector, ' ');
        $browser->pause(100);
        $assertActivated($browser, 'space');
    }

    /**
     * Assert that disabled elements are not focusable
     */
    protected function assertDisabledNotFocusable($browser, string $selector): void
    {
        $browser->script("document.querySelector('{$selector}').focus();");
        $browser->pause(100);

        $isFocused = $browser->script(
            "return document.activeElement.matches('{$selector}');"
        )[0];

        $this->assertFalse($isFocused,
            "Disabled element {$selector} should not be focusable");
    }

    /**
     * Assert visible focus indicator
     */
    protected function assertHasVisibleFocus($browser, string $selector): void
    {
        $browser->click($selector);
        $browser->pause(100);

        // Check for focus-visible or other focus styling
        $hasFocusStyle = $browser->script("
            const element = document.querySelector('{$selector}');
            const styles = window.getComputedStyle(element);
            return styles.outlineWidth !== '0px' ||
                   styles.outlineStyle !== 'none' ||
                   element.classList.contains('focus-visible') ||
                   element.classList.contains('focus');
        ")[0];

        $this->assertTrue($hasFocusStyle,
            "Element {$selector} should have visible focus indicator");
    }

    /**
     * Get the accessible name of an element
     */
    protected function getAccessibleName($browser, string $selector): ?string
    {
        return $browser->script("
            const element = document.querySelector('{$selector}');
            return element.getAttribute('aria-label') ||
                   document.getElementById(element.getAttribute('aria-labelledby'))?.textContent ||
                   element.textContent;
        ")[0];
    }

    /**
     * Assert that live region announces content
     */
    protected function assertLiveRegionAnnounces($browser, string $liveRegionSelector, string $expectedText): void
    {
        $browser->with($liveRegionSelector, function ($region) use ($expectedText) {
            $region->assertAttribute('aria-live', 'polite')
                   ->assertSeeIn('@', $expectedText);
        });
    }
}
