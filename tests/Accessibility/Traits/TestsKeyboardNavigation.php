<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits;

/**
 * Trait for testing keyboard navigation
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits
 */
trait TestsKeyboardNavigation
{
    /**
     * Assert element is keyboard focusable
     *
     * @param string $html
     * @param string $element
     * @return void
     */
    protected function assertIsKeyboardFocusable(string $html, string $element = 'button'): void
    {
        $nativelyFocusable = ['a', 'button', 'input', 'select', 'textarea', 'summary'];

        if (in_array($element, $nativelyFocusable)) {
            // Check element exists and is not disabled
            $this->assertStringContainsString("<{$element}", $html);
            $this->assertStringNotContainsString('disabled', $html);
        } else {
            // Custom elements need tabindex
            $this->assertMatchesRegularExpression('/tabindex=["\']0["\']/', $html,
                "Custom interactive element must have tabindex=\"0\"");
        }
    }

    /**
     * Assert element has keyboard event handlers
     *
     * @param string $html
     * @param array $keys Example: ['Enter', 'Space', 'Escape']
     * @return void
     */
    protected function assertHasKeyboardHandlers(string $html, array $keys): void
    {
        foreach ($keys as $key) {
            $keyLower = strtolower($key);
            $patterns = [
                "@keydown.{$keyLower}",
                "@keyup.{$keyLower}",
                "@keypress.{$keyLower}",
                "onkeydown",
                "onkeyup",
            ];

            $found = false;
            foreach ($patterns as $pattern) {
                if (str_contains($html, $pattern)) {
                    $found = true;
                    break;
                }
            }

            $this->assertTrue($found,
                "Element should have keyboard handler for {$key} key");
        }
    }

    /**
     * Assert element has arrow key navigation
     *
     * @param string $html
     * @return void
     */
    protected function assertHasArrowKeyNavigation(string $html): void
    {
        $arrowKeys = ['arrow-up', 'arrow-down', 'arrow-left', 'arrow-right'];

        $hasArrowHandlers = false;
        foreach ($arrowKeys as $key) {
            if (str_contains($html, "@keydown.{$key}")) {
                $hasArrowHandlers = true;
                break;
            }
        }

        $this->assertTrue($hasArrowHandlers,
            "Element should support arrow key navigation");
    }

    /**
     * Assert element has Home/End key support
     *
     * @param string $html
     * @return void
     */
    protected function assertHasHomeEndKeys(string $html): void
    {
        $this->assertTrue(
            str_contains($html, '@keydown.home') || str_contains($html, '@keydown.end'),
            "Element should support Home and End keys"
        );
    }

    /**
     * Assert element has Escape key handler
     *
     * @param string $html
     * @return void
     */
    protected function assertHasEscapeKey(string $html): void
    {
        $this->assertTrue(
            str_contains($html, '@keydown.escape') || str_contains($html, '@keydown.window.escape'),
            "Element should have Escape key handler"
        );
    }

    /**
     * Assert element supports roving tabindex pattern
     *
     * @param string $html
     * @return void
     */
    protected function assertHasRovingTabindex(string $html): void
    {
        // Check for dynamic tabindex binding
        $this->assertTrue(
            str_contains($html, ':tabindex') || str_contains($html, 'x-bind:tabindex'),
            "Element should use roving tabindex pattern"
        );
    }

    /**
     * Assert element has Enter/Space activation
     *
     * @param string $html
     * @return void
     */
    protected function assertHasEnterSpaceActivation(string $html): void
    {
        $hasEnter = str_contains($html, '@keydown.enter') || str_contains($html, '@keyup.enter');
        $hasSpace = str_contains($html, '@keydown.space') || str_contains($html, '@keyup.space');

        $this->assertTrue(
            $hasEnter || $hasSpace,
            "Interactive element should activate with Enter or Space key"
        );
    }

    /**
     * Assert disabled element is not focusable
     *
     * @param string $html
     * @return void
     */
    protected function assertDisabledNotFocusable(string $html): void
    {
        if (str_contains($html, 'disabled')) {
            // Should have tabindex="-1" or no tabindex (for native elements)
            $hasDisabledTabindex = str_contains($html, 'tabindex="-1"') ||
                                  !str_contains($html, 'tabindex="0"');

            $this->assertTrue($hasDisabledTabindex,
                "Disabled elements should not be focusable");
        }
    }
}
