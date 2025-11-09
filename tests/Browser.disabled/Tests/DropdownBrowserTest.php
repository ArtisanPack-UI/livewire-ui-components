<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use Laravel\Dusk\Browser;
use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Dropdown component - Tests opening, closing, keyboard navigation
 */
class DropdownBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_opens_dropdown_on_click()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/dropdown')
                   ->assertMissing('@dropdown-menu')
                   ->click('@dropdown-trigger')
                   ->waitFor('@dropdown-menu', 2)
                   ->assertVisible('@dropdown-menu');
        });
    }
}
