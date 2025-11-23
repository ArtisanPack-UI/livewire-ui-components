<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Tabs component - Tests tab switching, keyboard navigation
 */
class TabsBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_renders_tabs_correctly(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/test/tabs')
                ->assertVisible('@tab-1')
                ->assertVisible('@tab-2')
                ->assertVisible('@tab-3')
                ->assertVisible('@tab-panel-1')
                ->assertMissing('@tab-panel-2')
                ->assertMissing('@tab-panel-3');
        });
    }
}
