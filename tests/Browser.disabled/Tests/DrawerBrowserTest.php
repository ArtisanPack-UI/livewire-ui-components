<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use Laravel\Dusk\Browser;
use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Drawer component - Tests opening, closing, positioning
 */
class DrawerBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_opens_drawer_from_right()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/test/drawer')
                   ->assertMissing('@drawer-content')
                   ->click('@drawer-trigger-right')
                   ->waitFor('@drawer-content', 2)
                   ->assertVisible('@drawer-content')
                   ->assertPresent('@drawer-content.drawer-right');
        });
    }
}
