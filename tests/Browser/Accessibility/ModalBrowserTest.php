<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\Browser;

/**
 * Browser tests for Modal component accessibility
 *
 * Tests real keyboard navigation, focus management, and user interactions
 * that can only be verified in a real browser environment.
 *
 * @group browser
 * @group accessibility
 * @package ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility
 */
class ModalBrowserTest extends AccessibilityBrowserTestCase
{
    /**
     * Test modal opens and has proper dialog role
     *
     * @test
     */
    public function modal_has_dialog_role_in_browser(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            $this->assertHasRole($browser, '@test-modal', 'dialog');
            $browser->assertAttribute('@test-modal', 'aria-modal', 'true');
        });
    }

    /**
     * Test modal focus trap
     *
     * @test
     */
    public function modal_implements_focus_trap(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            // Test that focus is trapped within modal
            $this->testFocusTrap($browser, '@test-modal', [
                '@modal-close-button',
                '@modal-confirm-button',
                '@modal-cancel-button',
            ]);
        });
    }

    /**
     * Test focus returns to trigger after closing
     *
     * @test
     */
    public function modal_returns_focus_to_trigger_element(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal');

            $this->testFocusReturn(
                $browser,
                '@open-modal-button',
                '@test-modal',
                '@modal-close-button'
            );
        });
    }

    /**
     * Test modal can be closed with Escape key
     *
     * @test
     */
    public function modal_closes_with_escape_key(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal');

            $this->testEscapeKey(
                $browser,
                '@open-modal-button',
                '@test-modal'
            );
        });
    }

    /**
     * Test persistent modal cannot be closed with Escape
     *
     * @test
     */
    public function persistent_modal_does_not_close_with_escape(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal-persistent')
                    ->click('@open-modal-button')
                    ->waitFor('@persistent-modal')
                    ->keys('body', '{escape}')
                    ->pause(200);

            // Modal should still be visible
            $browser->assertVisible('@persistent-modal');

            // Close via button to cleanup
            $browser->click('@modal-close-button');
        });
    }

    /**
     * Test modal auto-focuses first focusable element
     *
     * @test
     */
    public function modal_auto_focuses_first_element_on_open(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal')
                    ->pause(100);

            // First focusable element should be focused
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"modal-close-button\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'Modal should auto-focus first focusable element');
        });
    }

    /**
     * Test modal close button has accessible name
     *
     * @test
     */
    public function modal_close_button_has_accessible_name(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            $this->assertHasAccessibleName($browser, '@modal-close-button');

            $accessibleName = $this->getAccessibleName($browser, '@modal-close-button');
            $this->assertStringContainsString('Close', $accessibleName);
        });
    }

    /**
     * Test modal backdrop is aria-hidden
     *
     * @test
     */
    public function modal_backdrop_is_aria_hidden(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            $browser->assertAttribute('@modal-backdrop', 'aria-hidden', 'true');
        });
    }

    /**
     * Test Tab key navigation through modal elements
     *
     * @test
     */
    public function modal_supports_tab_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            // Test tab order
            $this->testTabNavigation($browser, [
                '@modal-close-button',
                '@modal-confirm-button',
                '@modal-cancel-button',
            ]);
        });
    }

    /**
     * Test background content is inert when modal is open
     *
     * @test
     */
    public function modal_makes_background_inert(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/modal')
                    ->click('@open-modal-button')
                    ->waitFor('@test-modal');

            // Try to interact with background content
            $canFocusBackground = $browser->script("
                const backgroundButton = document.querySelector('[dusk=\"background-button\"]');
                if (!backgroundButton) return false;
                backgroundButton.focus();
                return document.activeElement === backgroundButton;
            ")[0];

            $this->assertFalse($canFocusBackground,
                'Background content should be inert when modal is open');
        });
    }
}
