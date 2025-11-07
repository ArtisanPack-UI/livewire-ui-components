<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\Browser;

/**
 * Browser tests for Tabs component accessibility
 *
 * Tests tab pattern implementation including arrow key navigation,
 * automatic activation, and proper ARIA attributes.
 *
 * @group browser
 * @group accessibility
 * @package ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility
 */
class TabsBrowserTest extends AccessibilityBrowserTestCase
{
    /**
     * Test tabs have proper roles
     *
     * @test
     */
    public function tabs_have_proper_roles(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            $this->assertHasRole($browser, '[dusk="tablist"]', 'tablist');
            $this->assertHasRole($browser, '[dusk="tab-1"]', 'tab');
            $this->assertHasRole($browser, '[dusk="tab-2"]', 'tab');
            $this->assertHasRole($browser, '[dusk="tabpanel-1"]', 'tabpanel');
        });
    }

    /**
     * Test selected tab has aria-selected
     *
     * @test
     */
    public function selected_tab_has_aria_selected(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            // First tab should be selected
            $this->assertAriaSelected($browser, '[dusk="tab-1"]', true);
            $this->assertAriaSelected($browser, '[dusk="tab-2"]', false);

            // Click second tab
            $browser->click('[dusk="tab-2"]')
                    ->pause(100);

            // Second tab should now be selected
            $this->assertAriaSelected($browser, '[dusk="tab-1"]', false);
            $this->assertAriaSelected($browser, '[dusk="tab-2"]', true);
        });
    }

    /**
     * Test tab controls tabpanel
     *
     * @test
     */
    public function tab_controls_tabpanel(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            $controls = $browser->attribute('[dusk="tab-1"]', 'aria-controls');
            $this->assertStringContainsString('tabpanel', $controls);

            // Tabpanel should reference tab
            $labelledBy = $browser->attribute('[dusk="tabpanel-1"]', 'aria-labelledby');
            $tabId = $browser->attribute('[dusk="tab-1"]', 'id');
            $this->assertEquals($tabId, $labelledBy);
        });
    }

    /**
     * Test arrow key navigation between tabs
     *
     * @test
     */
    public function tabs_support_arrow_key_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-1"]');

            $this->testArrowKeyNavigation($browser, '[dusk="tablist"]', [
                '[dusk="tab-1"]',
                '[dusk="tab-2"]',
                '[dusk="tab-3"]',
            ]);
        });
    }

    /**
     * Test Home key jumps to first tab
     *
     * @test
     */
    public function home_key_jumps_to_first_tab(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-3"]')
                    ->keys('[dusk="tablist"]', '{home}')
                    ->pause(100);

            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-1\"]');"
            )[0];

            $this->assertTrue($isFocused, 'Home key should focus first tab');

            // First tab should be selected (automatic activation)
            $this->assertAriaSelected($browser, '[dusk="tab-1"]', true);
        });
    }

    /**
     * Test End key jumps to last tab
     *
     * @test
     */
    public function end_key_jumps_to_last_tab(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-1"]')
                    ->keys('[dusk="tablist"]', '{end}')
                    ->pause(100);

            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-3\"]');"
            )[0];

            $this->assertTrue($isFocused, 'End key should focus last tab');

            // Last tab should be selected (automatic activation)
            $this->assertAriaSelected($browser, '[dusk="tab-3"]', true);
        });
    }

    /**
     * Test automatic activation on focus
     *
     * @test
     */
    public function tabs_automatically_activate_on_focus(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-1"]')
                    ->keys('[dusk="tablist"]', '{arrow_right}')
                    ->pause(200);

            // Tab 2 should be focused AND selected
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-2\"]');"
            )[0];

            $this->assertTrue($isFocused, 'Arrow right should focus next tab');
            $this->assertAriaSelected($browser, '[dusk="tab-2"]', true);

            // Corresponding tabpanel should be visible
            $browser->assertVisible('[dusk="tabpanel-2"]');
        });
    }

    /**
     * Test tabs wrap around with arrow keys
     *
     * @test
     */
    public function arrow_keys_wrap_around_tabs(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-3"]')
                    ->keys('[dusk="tablist"]', '{arrow_right}')
                    ->pause(100);

            // Should wrap to first tab
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-1\"]');"
            )[0];

            $this->assertTrue($isFocused, 'Arrow right should wrap to first tab');

            // Test wrapping backwards
            $browser->keys('[dusk="tablist"]', '{arrow_left}')
                    ->pause(100);

            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-3\"]');"
            )[0];

            $this->assertTrue($isFocused, 'Arrow left should wrap to last tab');
        });
    }

    /**
     * Test disabled tabs are skipped in navigation
     *
     * @test
     */
    public function arrow_navigation_skips_disabled_tabs(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs-with-disabled')
                    ->click('[dusk="tab-1"]')
                    ->keys('[dusk="tablist"]', '{arrow_right}')
                    ->pause(100);

            // Should skip disabled tab 2 and focus tab 3
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"tab-3\"]');"
            )[0];

            $this->assertTrue($isFocused, 'Should skip disabled tabs');
        });
    }

    /**
     * Test disabled tabs are not selectable
     *
     * @test
     */
    public function disabled_tabs_are_not_selectable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs-with-disabled')
                    ->click('[dusk="tab-2"]')
                    ->pause(100);

            // Tab 2 should not be selected
            $this->assertAriaSelected($browser, '[dusk="tab-2"]', false);

            // Tab 1 should still be selected
            $this->assertAriaSelected($browser, '[dusk="tab-1"]', true);
        });
    }

    /**
     * Test Tab key moves focus out of tablist
     *
     * @test
     */
    public function tab_key_moves_focus_to_tabpanel(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs')
                    ->click('[dusk="tab-1"]')
                    ->keys('[dusk="tab-1"]', '{tab}')
                    ->pause(100);

            // Focus should move to tabpanel or next focusable element
            $isFocused = $browser->script(
                "const active = document.activeElement;
                 return active.closest('[role=\"tabpanel\"]') !== null ||
                        active.matches('[dusk=\"next-focusable\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'Tab key should move focus out of tablist');
        });
    }

    /**
     * Test only selected tab is in tab order
     *
     * @test
     */
    public function only_selected_tab_is_in_tab_order(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            // Selected tab should have tabindex="0"
            $tabindex1 = $browser->attribute('[dusk="tab-1"]', 'tabindex');
            $this->assertEquals('0', $tabindex1);

            // Other tabs should have tabindex="-1"
            $tabindex2 = $browser->attribute('[dusk="tab-2"]', 'tabindex');
            $this->assertEquals('-1', $tabindex2);

            // After selecting tab 2
            $browser->click('[dusk="tab-2"]')
                    ->pause(100);

            // Tab 2 should now have tabindex="0"
            $tabindex2 = $browser->attribute('[dusk="tab-2"]', 'tabindex');
            $this->assertEquals('0', $tabindex2);

            // Tab 1 should have tabindex="-1"
            $tabindex1 = $browser->attribute('[dusk="tab-1"]', 'tabindex');
            $this->assertEquals('-1', $tabindex1);
        });
    }

    /**
     * Test tabpanel has accessible label
     *
     * @test
     */
    public function tabpanel_has_accessible_label(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            $this->assertHasAccessibleName($browser, '[dusk="tabpanel-1"]');
        });
    }

    /**
     * Test inactive tabpanels are hidden
     *
     * @test
     */
    public function inactive_tabpanels_are_hidden(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/tabs');

            // Tab 1 is selected, so tabpanel 1 is visible
            $browser->assertVisible('[dusk="tabpanel-1"]');

            // Other tabpanels should be hidden
            $browser->assertMissing('[dusk="tabpanel-2"]');
            $browser->assertMissing('[dusk="tabpanel-3"]');

            // Select tab 2
            $browser->click('[dusk="tab-2"]')
                    ->pause(100);

            // Now tabpanel 2 is visible
            $browser->assertVisible('[dusk="tabpanel-2"]');
            $browser->assertMissing('[dusk="tabpanel-1"]');
        });
    }
}
