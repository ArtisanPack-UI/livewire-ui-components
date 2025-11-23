<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Browser;

use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseDuskTestCase;

/**
 * Base Dusk test case for browser testing UI components.
 *
 * Provides configuration and utilities for browser-based testing
 * including responsive design testing and complex user interactions.
 */
abstract class DuskTestCase extends BaseDuskTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--headless', // Run headless for CI/CD
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--disable-web-security',
            '--allow-running-insecure-content',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->reject(function ($item) {
                return '--headless' === $item;
            });
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options,
            ),
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     *
     * @return bool
     */
    protected function shouldStartMaximized()
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
               isset($_ENV['DUSK_START_MAXIMIZED']);
    }

    /**
     * Test component at different screen sizes for responsive design.
     */
    protected function test_responsive_component(callable $testCallback, ?array $breakpoints = null): void
    {
        $breakpoints = $breakpoints ?: TestHelpers::responsiveBreakpoints();

        foreach ($breakpoints as $name => $dimensions) {
            $this->browse(function ($browser) use ($testCallback, $name, $dimensions): void {
                $browser->resize($dimensions['width'], $dimensions['height']);
                $testCallback($browser, $name, $dimensions);
            });
        }
    }

    /**
     * Test component accessibility with keyboard navigation.
     */
    protected function test_keyboard_navigation(callable $setupCallback, array $expectedFocusableElements): void
    {
        $this->browse(function ($browser) use ($setupCallback, $expectedFocusableElements): void {
            $setupCallback($browser);

            // Test Tab navigation
            foreach ($expectedFocusableElements as $selector) {
                $browser->keys('body', '{tab}')
                    ->assertFocused($selector);
            }

            // Test Shift+Tab navigation (reverse)
            for ($i = count($expectedFocusableElements) - 1; $i >= 0; $i--) {
                $browser->keys('body', ['{shift}', '{tab}'])
                    ->assertFocused($expectedFocusableElements[$i]);
            }
        });
    }

    /**
     * Test component for color contrast accessibility.
     */
    protected function test_color_contrast(): void
    {
        $this->browse(function ($browser): void {
            // Get computed styles and test color combinations
            $script = "
                const elements = document.querySelectorAll('*');
                const colorTests = [];
                
                elements.forEach(el => {
                    const style = window.getComputedStyle(el);
                    const color = style.color;
                    const backgroundColor = style.backgroundColor;
                    
                    if (color !== 'rgba(0, 0, 0, 0)' && backgroundColor !== 'rgba(0, 0, 0, 0)') {
                        colorTests.push({
                            element: el.tagName,
                            color: color,
                            backgroundColor: backgroundColor
                        });
                    }
                });
                
                return colorTests;
            ";

            $colorTests = $browser->driver->executeScript($script);

            foreach ($colorTests as $test) {
                if (isset($test['color']) && isset($test['backgroundColor'])) {
                    // Convert RGB to hex if needed and test contrast
                    $fg = $this->rgbToHex($test['color']);
                    $bg = $this->rgbToHex($test['backgroundColor']);

                    if ($fg && $bg) {
                        $contrast = TestHelpers::validateColorContrast($fg, $bg);
                        $this->assertTrue($contrast['wcag_aa_normal'] || $contrast['wcag_aa_large'],
                            "Poor color contrast: {$fg} on {$bg} for {$test['element']}");
                    }
                }
            }
        });
    }

    /**
     * Test component performance in browser.
     */
    protected function test_browser_performance(callable $setupCallback, callable $actionCallback, int $maxTime = 5000): void
    {
        $this->browse(function ($browser) use ($setupCallback, $actionCallback, $maxTime): void {
            $setupCallback($browser);

            $startTime = microtime(true);
            $actionCallback($browser);
            $endTime = microtime(true);

            $duration = ($endTime - $startTime) * 1000;
            $this->assertLessThan($maxTime, $duration,
                "Browser action took {$duration}ms, should be under {$maxTime}ms");
        });
    }

    /**
     * Test component with simulated slow network.
     */
    protected function test_slow_network(callable $testCallback): void
    {
        $this->browse(function ($browser) use ($testCallback): void {
            // Simulate slow network
            $browser->driver->getCommandExecutor()->execute([
                'cmd'    => 'Network.emulateNetworkConditions',
                'params' => [
                    'offline'            => false,
                    'latency'            => 500, // 500ms latency
                    'downloadThroughput' => 50000, // 50kb/s
                    'uploadThroughput'   => 20000, // 20kb/s
                ],
            ]);

            $testCallback($browser);

            // Reset network conditions
            $browser->driver->getCommandExecutor()->execute([
                'cmd'    => 'Network.emulateNetworkConditions',
                'params' => [
                    'offline'            => false,
                    'latency'            => 0,
                    'downloadThroughput' => -1,
                    'uploadThroughput'   => -1,
                ],
            ]);
        });
    }

    /**
     * Test component with various user interactions.
     */
    protected function test_user_interactions(string $selector, ?array $interactions = null): void
    {
        $interactions = $interactions ?: [
            'click', 'hover', 'focus', 'blur', 'doubleClick',
        ];

        $this->browse(function ($browser) use ($selector, $interactions): void {
            foreach ($interactions as $interaction) {
                switch ($interaction) {
                    case 'click':
                        $browser->click($selector);
                        break;
                    case 'hover':
                        $browser->mouseover($selector);
                        break;
                    case 'focus':
                        $browser->script("document.querySelector('{$selector}').focus()");
                        break;
                    case 'blur':
                        $browser->script("document.querySelector('{$selector}').blur()");
                        break;
                    case 'doubleClick':
                        $browser->doubleClick($selector);
                        break;
                }

                // Small delay between interactions
                $browser->pause(100);
            }
        });
    }

    /**
     * Test component with screen reader simulation.
     */
    protected function test_screen_reader_compatibility(): void
    {
        $this->browse(function ($browser): void {
            // Inject aXe accessibility testing library
            $browser->script("
                if (!window.axe) {
                    var script = document.createElement('script');
                    script.src = 'https://unpkg.com/axe-core@latest/axe.min.js';
                    document.head.appendChild(script);
                }
            ");

            $browser->pause(1000); // Wait for aXe to load

            // Run accessibility tests
            $results = $browser->script('
                return new Promise((resolve) => {
                    if (window.axe) {
                        axe.run().then((results) => {
                            resolve(results);
                        });
                    } else {
                        resolve({violations: []});
                    }
                });
            ');

            if (isset($results['violations'])) {
                $this->assertEmpty($results['violations'],
                    'Accessibility violations found: '.json_encode($results['violations']));
            }
        });
    }

    /**
     * Take a screenshot with automatic timestamping.
     */
    protected function takeTimestampedScreenshot(string $name): void
    {
        $this->browse(function ($browser) use ($name): void {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $browser->screenshot("{$name}_{$timestamp}");
        });
    }

    /**
     * Test component across multiple browsers if available.
     */
    protected function test_cross_browser(callable $testCallback): void
    {
        // For now, we'll test with Chrome only
        // In a full implementation, you would test with multiple browser drivers
        $this->browse($testCallback);
    }

    /**
     * Wait for Livewire to finish processing.
     */
    protected function waitForLivewire(): void
    {
        $this->browse(function ($browser): void {
            $browser->waitUntil('window.Livewire && !window.Livewire.isOffline');
        });
    }

    /**
     * Assert that component is visible at all tested breakpoints.
     */
    protected function assertVisibleAtAllBreakpoints(string $selector): void
    {
        $this->testResponsiveComponent(function ($browser, $breakpoint) use ($selector): void {
            $browser->assertVisible($selector);
        });
    }

    /**
     * Assert that component maintains functionality across breakpoints.
     */
    protected function assertFunctionalAcrossBreakpoints(string $selector, callable $functionalTest): void
    {
        $this->testResponsiveComponent(function ($browser, $breakpoint) use ($selector, $functionalTest): void {
            $functionalTest($browser, $selector, $breakpoint);
        });
    }

    /**
     * Test component loading behavior.
     */
    protected function test_loading_states(string $triggerSelector, string $loadingSelector): void
    {
        $this->browse(function ($browser) use ($triggerSelector, $loadingSelector): void {
            $browser->click($triggerSelector)
                ->assertVisible($loadingSelector)
                ->waitUntilMissing($loadingSelector);
        });
    }

    /**
     * Test form validation in browser.
     */
    protected function test_form_validation(array $formData, array $expectedErrors): void
    {
        $this->browse(function ($browser) use ($formData, $expectedErrors): void {
            foreach ($formData as $field => $value) {
                $browser->type($field, $value);
            }

            $browser->press('Submit');

            foreach ($expectedErrors as $error) {
                $browser->assertSee($error);
            }
        });
    }

    /**
     * Convert RGB color to hex.
     */
    private function rgbToHex(string $rgb): ?string
    {
        if (preg_match('/rgb\((\d+),\s*(\d+),\s*(\d+)\)/', $rgb, $matches)) {
            return sprintf('#%02x%02x%02x', $matches[1], $matches[2], $matches[3]);
        }

        if (preg_match('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', $rgb, $matches)) {
            return sprintf('#%02x%02x%02x', $matches[1], $matches[2], $matches[3]);
        }

        if (preg_match('/^#([a-fA-F0-9]{6})$/', $rgb)) {
            return $rgb;
        }

        return null;
    }
}
