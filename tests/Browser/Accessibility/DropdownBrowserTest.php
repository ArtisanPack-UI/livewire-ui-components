<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility;

use Laravel\Dusk\Browser;

/**
 * Browser tests for Dropdown component accessibility
 *
 * Tests menu pattern keyboard navigation including arrow keys,
 * Home/End navigation, and proper ARIA attributes.
 *
 * @group browser
 * @group accessibility
 * @package ArtisanPack\LivewireUiComponents\Tests\Browser\Accessibility
 */
class DropdownBrowserTest extends AccessibilityBrowserTestCase
{
    /**
     * Test dropdown has proper menu role
     *
     * @test
     */
    public function dropdown_has_menu_role(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->assertHasRole($browser, '@dropdown-menu', 'menu');
        });
    }

    /**
     * Test dropdown button has proper ARIA attributes
     *
     * @test
     */
    public function dropdown_button_has_proper_aria(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown');

            // When closed
            $this->assertAriaExpanded($browser, '@dropdown-trigger', false);
            $browser->assertAttribute('@dropdown-trigger', 'aria-haspopup', 'menu');

            // Open dropdown
            $browser->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            // When open
            $this->assertAriaExpanded($browser, '@dropdown-trigger', true);
        });
    }

    /**
     * Test arrow key navigation in dropdown
     *
     * @test
     */
    public function dropdown_supports_arrow_key_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->testArrowKeyNavigation($browser, '@dropdown-menu', [
                '[dusk="menu-item-1"]',
                '[dusk="menu-item-2"]',
                '[dusk="menu-item-3"]',
            ]);
        });
    }

    /**
     * Test Home key jumps to first menu item
     *
     * @test
     */
    public function dropdown_home_key_jumps_to_first_item(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu')
                    ->click('[dusk="menu-item-3"]')
                    ->keys('@dropdown-menu', '{home}')
                    ->pause(100);

            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"menu-item-1\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'Home key should focus first menu item');
        });
    }

    /**
     * Test End key jumps to last menu item
     *
     * @test
     */
    public function dropdown_end_key_jumps_to_last_item(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu')
                    ->click('[dusk="menu-item-1"]')
                    ->keys('@dropdown-menu', '{end}')
                    ->pause(100);

            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"menu-item-3\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'End key should focus last menu item');
        });
    }

    /**
     * Test dropdown closes with Escape key
     *
     * @test
     */
    public function dropdown_closes_with_escape(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown');

            $this->testEscapeKey(
                $browser,
                '@dropdown-trigger',
                '@dropdown-menu'
            );

            // Focus should return to trigger
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"dropdown-trigger\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'Focus should return to trigger after Escape');
        });
    }

    /**
     * Test Enter/Space activates menu items
     *
     * @test
     */
    public function dropdown_menu_items_activate_with_enter_space(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->testEnterSpaceActivation(
                $browser,
                '[dusk="menu-item-1"]',
                function ($browser, $key) {
                    // Check that menu item was activated
                    $browser->assertSee('Item 1 activated')
                            ->pause(100);
                }
            );
        });
    }

    /**
     * Test menu items have menuitem role
     *
     * @test
     */
    public function menu_items_have_menuitem_role(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->assertHasRole($browser, '[dusk="menu-item-1"]', 'menuitem');
            $this->assertHasRole($browser, '[dusk="menu-item-2"]', 'menuitem');
            $this->assertHasRole($browser, '[dusk="menu-item-3"]', 'menuitem');
        });
    }

    /**
     * Test disabled menu items are not focusable
     *
     * @test
     */
    public function disabled_menu_items_not_focusable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown-with-disabled')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->assertDisabledNotFocusable($browser, '[dusk="disabled-menu-item"]');
        });
    }

    /**
     * Test arrow navigation skips disabled items
     *
     * @test
     */
    public function arrow_navigation_skips_disabled_items(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown-with-disabled')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu')
                    ->click('[dusk="menu-item-1"]')
                    ->keys('@dropdown-menu', '{arrow_down}')
                    ->pause(100);

            // Should skip disabled item 2 and focus item 3
            $isFocused = $browser->script(
                "return document.activeElement.matches('[dusk=\"menu-item-3\"]');"
            )[0];

            $this->assertTrue($isFocused,
                'Arrow navigation should skip disabled items');
        });
    }

    /**
     * Test clicking outside closes dropdown
     *
     * @test
     */
    public function clicking_outside_closes_dropdown(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu')
                    ->click('body')
                    ->pause(200);

            $browser->assertMissing('@dropdown-menu');
        });
    }

    /**
     * Test dropdown menu has accessible label
     *
     * @test
     */
    public function dropdown_menu_has_accessible_label(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                    ->click('@dropdown-trigger')
                    ->waitFor('@dropdown-menu');

            $this->assertHasAccessibleName($browser, '@dropdown-menu');
        });
    }
}
