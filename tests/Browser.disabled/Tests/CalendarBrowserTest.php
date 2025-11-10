<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use Laravel\Dusk\Browser;
use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Calendar component - Tests date selection, month navigation
 */
class CalendarBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_renders_current_month()
    {
        $this->browse(function (Browser $browser) {
            $currentMonth = now()->format('F Y');

            $browser->visit('/test/calendar')
                   ->assertVisible('@calendar')
                   ->assertSeeIn('@calendar-header', $currentMonth)
                   ->assertVisible('@calendar-grid');
        });
    }
}
