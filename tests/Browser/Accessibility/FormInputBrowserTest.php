<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\Browser;

/**
 * Browser tests for Form Input accessibility
 *
 * Tests form validation announcements, error states, and proper
 * label associations in a real browser environment.
 *
 * @group browser
 * @group accessibility
 * @package ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility
 */
class FormInputBrowserTest extends AccessibilityBrowserTestCase
{
    /**
     * Test input has associated label
     *
     * @test
     */
    public function input_has_associated_label(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input');

            // Check label association via for/id
            $labelFor = $browser->attribute('[dusk="email-label"]', 'for');
            $inputId = $browser->attribute('[dusk="email-input"]', 'id');

            $this->assertEquals($labelFor, $inputId,
                'Label for attribute should match input id');
        });
    }

    /**
     * Test required input has aria-required
     *
     * @test
     */
    public function required_input_has_aria_required(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input');

            $browser->assertAttribute('[dusk="email-input"]', 'aria-required', 'true');
        });
    }

    /**
     * Test error state adds aria-invalid
     *
     * @test
     */
    public function error_state_adds_aria_invalid(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input')
                    ->type('[dusk="email-input"]', 'invalid-email')
                    ->click('[dusk="submit-button"]')
                    ->waitFor('[dusk="email-error"]');

            $this->assertAriaInvalid($browser, '[dusk="email-input"]', true);
        });
    }

    /**
     * Test error message is announced to screen readers
     *
     * @test
     */
    public function error_message_is_announced(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input')
                    ->type('[dusk="email-input"]', 'invalid-email')
                    ->click('[dusk="submit-button"]')
                    ->waitFor('[dusk="email-error"]');

            // Error should have role="alert" and aria-live="polite"
            $browser->assertAttribute('[dusk="email-error"]', 'role', 'alert')
                    ->assertAttribute('[dusk="email-error"]', 'aria-live', 'polite');

            // Input should reference error via aria-describedby
            $describedBy = $browser->attribute('[dusk="email-input"]', 'aria-describedby');
            $this->assertStringContainsString('email-error', $describedBy);
        });
    }

    /**
     * Test hint text is associated with input
     *
     * @test
     */
    public function hint_text_is_associated_with_input(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-with-hint');

            $describedBy = $browser->attribute('[dusk="password-input"]', 'aria-describedby');
            $this->assertStringContainsString('hint', $describedBy,
                'Input should reference hint via aria-describedby');
        });
    }

    /**
     * Test clearable button has accessible label
     *
     * @test
     */
    public function clearable_button_has_accessible_label(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-clearable')
                    ->type('[dusk="search-input"]', 'test query')
                    ->waitFor('[dusk="clear-button"]');

            $this->assertHasAccessibleName($browser, '[dusk="clear-button"]');

            $accessibleName = $this->getAccessibleName($browser, '[dusk="clear-button"]');
            $this->assertStringContainsString('Clear', $accessibleName);
        });
    }

    /**
     * Test clearable button can be activated with keyboard
     *
     * @test
     */
    public function clearable_button_keyboard_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-clearable')
                    ->type('[dusk="search-input"]', 'test query')
                    ->waitFor('[dusk="clear-button"]');

            $this->testEnterSpaceActivation(
                $browser,
                '[dusk="clear-button"]',
                function ($browser, $key) {
                    // Input should be cleared
                    $browser->assertInputValue('[dusk="search-input"]', '');
                }
            );
        });
    }

    /**
     * Test disabled input is not focusable
     *
     * @test
     */
    public function disabled_input_is_not_focusable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-disabled');

            $this->assertDisabledNotFocusable($browser, '[dusk="disabled-input"]');
        });
    }

    /**
     * Test password visibility toggle has proper label
     *
     * @test
     */
    public function password_toggle_has_dynamic_label(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-password');

            // Initially shows "Show password"
            $label = $browser->attribute('[dusk="toggle-visibility"]', 'aria-label');
            $this->assertEquals('Show password', $label);

            // After clicking, shows "Hide password"
            $browser->click('[dusk="toggle-visibility"]')
                    ->pause(100);

            $label = $browser->attribute('[dusk="toggle-visibility"]', 'aria-label');
            $this->assertEquals('Hide password', $label);
        });
    }

    /**
     * Test input with prefix maintains accessibility
     *
     * @test
     */
    public function input_with_prefix_maintains_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-prefix');

            // Label should still be associated
            $labelFor = $browser->attribute('[dusk="price-label"]', 'for');
            $inputId = $browser->attribute('[dusk="price-input"]', 'id');
            $this->assertEquals($labelFor, $inputId);

            // Prefix should be aria-hidden
            $browser->assertAttribute('[dusk="price-prefix"]', 'aria-hidden', 'true');
        });
    }

    /**
     * Test money input has proper inputmode
     *
     * @test
     */
    public function money_input_has_numeric_inputmode(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-input-money');

            $browser->assertAttribute('[dusk="amount-input"]', 'inputmode', 'numeric');
        });
    }

    /**
     * Test Tab navigation through form fields
     *
     * @test
     */
    public function form_supports_tab_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-complete');

            $this->testTabNavigation($browser, [
                '[dusk="name-input"]',
                '[dusk="email-input"]',
                '[dusk="password-input"]',
                '[dusk="submit-button"]',
            ]);
        });
    }

    /**
     * Test form validation announcements on submit
     *
     * @test
     */
    public function form_announces_validation_errors_on_submit(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-complete')
                    ->click('[dusk="submit-button"]')
                    ->waitFor('[dusk="validation-summary"]');

            // Summary should be announced
            $browser->assertAttribute('[dusk="validation-summary"]', 'role', 'alert')
                    ->assertAttribute('[dusk="validation-summary"]', 'aria-live', 'assertive');

            // Should list number of errors
            $browser->assertSeeIn('[dusk="validation-summary"]', 'There are 3 errors');
        });
    }

    /**
     * Test successful form submission announcement
     *
     * @test
     */
    public function form_announces_successful_submission(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/form-complete')
                    ->type('[dusk="name-input"]', 'John Doe')
                    ->type('[dusk="email-input"]', 'john@example.com')
                    ->type('[dusk="password-input"]', 'password123')
                    ->click('[dusk="submit-button"]')
                    ->waitFor('[dusk="success-message"]');

            $this->assertLiveRegionAnnounces(
                $browser,
                '[dusk="success-message"]',
                'Form submitted successfully'
            );
        });
    }
}
