<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits;

/**
 * Trait for testing focus management
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits
 */
trait TestsFocusManagement
{
    /**
     * Assert element has focus trap
     *
     * @param string $html
     * @return void
     */
    protected function assertHasFocusTrap(string $html): void
    {
        $this->assertTrue(
            str_contains($html, 'x-trap') || str_contains($html, 'x-bind:inert'),
            "Modal/Dialog should have focus trap (x-trap or inert)"
        );
    }

    /**
     * Assert element returns focus on close
     *
     * @param string $html
     * @return void
     */
    protected function assertReturnsFocus(string $html): void
    {
        // Check for lastFocusedElement pattern
        $this->assertTrue(
            str_contains($html, 'lastFocusedElement') ||
            str_contains($html, 'document.activeElement'),
            "Component should store and return focus to trigger element"
        );
    }

    /**
     * Assert element auto-focuses on open
     *
     * @param string $html
     * @return void
     */
    protected function assertAutoFocuses(string $html): void
    {
        $this->assertTrue(
            str_contains($html, '.focus()') ||
            str_contains($html, 'autofocus'),
            "Component should auto-focus appropriate element when opened"
        );
    }

    /**
     * Assert element has visible focus indicators
     *
     * @param string $html
     * @return void
     */
    protected function assertHasVisibleFocus(string $html): void
    {
        $focusClasses = [
            'focus:',
            'focus-visible:',
            'focus-within:',
            'ring-',
            'outline-',
        ];

        $hasFocus = false;
        foreach ($focusClasses as $class) {
            if (str_contains($html, $class)) {
                $hasFocus = true;
                break;
            }
        }

        $this->assertTrue($hasFocus,
            "Interactive element must have visible focus indicators");
    }

    /**
     * Assert element manages inert attribute
     *
     * @param string $html
     * @return void
     */
    protected function assertManagesInert(string $html): void
    {
        $this->assertTrue(
            str_contains($html, 'inert') || str_contains($html, 'x-bind:inert'),
            "Background content should be inert when modal/dialog is open"
        );
    }

    /**
     * Assert element has sequential focus navigation
     *
     * @param string $html
     * @return void
     */
    protected function assertSequentialFocus(string $html): void
    {
        // Should not have positive tabindex values
        $this->assertDoesNotMatchRegularExpression(
            '/tabindex=["\'][1-9]\d*["\']/',
            $html,
            "Should not use positive tabindex values (use 0 or -1 only)"
        );
    }

    /**
     * Assert skip link is present
     *
     * @param string $html
     * @return void
     */
    protected function assertHasSkipLink(string $html): void
    {
        $this->assertTrue(
            str_contains($html, 'skip-link') ||
            (str_contains($html, 'Skip to') && str_contains($html, 'href=')),
            "Page should have skip to main content link"
        );
    }

    /**
     * Assert focus order is logical
     *
     * @param string $html
     * @return void
     */
    protected function assertLogicalFocusOrder(string $html): void
    {
        // Check that tabindex values are either 0, -1, or not set
        $this->assertDoesNotMatchRegularExpression(
            '/tabindex=["\'][1-9]\d*["\']/',
            $html,
            "Focus order should follow DOM order (don't use positive tabindex)"
        );
    }
}
